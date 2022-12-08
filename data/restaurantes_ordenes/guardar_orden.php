<?php
session_start();
include '../../procesos/base.php';
//include 'generarPDF.php';
include '../../procesos/funciones.php';
include_once '../../procesos/kardexValorizado.php';
require_once '../../procesos/detalleProductosBodega.php';

include '../../reportes/fact_elect_xml.php';
include '../../reportes/fact_guia_xml.php';
include '../../firma/firma.php';
include '../../firma/xades.php';
include '../../admin/correo.php';
require_once __DIR__ . '/../../procesos/configuracion.php';
// Auditoria
require_once '../../procesos/auditoria.php';

date_default_timezone_set('America/Guayaquil');

/* var_dump($_POST);
exit(); */

/* function urlCurl()
{
    $urlexplode = explode("/", $_SERVER["REQUEST_URI"]);
    array_pop($urlexplode);
    array_pop($urlexplode);
    $implodeurl = implode("/", $urlexplode);
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$implodeurl";
    $url .= "/factura_venta/guardar_factura_venta.php";
    // The submitted form data, encoded as query-string-style
    // name-value pairs
    $body = 'monkey=uncle&rhino=aunt';
    $c = curl_init($url);
    curl_setopt($c, CURLOPT_POST, true);
    curl_setopt($c, CURLOPT_POSTFIELDS, $body);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    $page = curl_exec($c);
    curl_close($c);
    var_dump($page);
}
exit(); */
$conexion = conectarse();
$fecha = date('Y-m-d H:i:s');
$puntoventa = $_SESSION["PV"];
$idusuario = $_SESSION["id"];
$fechaactual = date("Y-m-d");
$horaactual = date("h:i:s A");
$numserie = "";
$clave = "";

$sql = "select*from empresa where id_empresa=$puntoventa";
$res = pg_query($conexion, $sql);
$rows = pg_fetch_all($res);
if (!empty($rows)) {
    $numserie = $rows[0]["establecimiento"] . "-" . $rows[0]["punto_emision"];
}

$conf = new Configuracion();
$esquema = $conf->getNombreEsquema();
$appFirma = $conf->getPathAplicacionFIrma("app_firma");
$pathXmls = $conf->getPathXmlsFirma();
$pathARchivoP12 = $conf->getArchivoP12();
$claveFirma = $conf->getParametroEmpresa("clave_firma");

echo json_encode(transaccionGuardarOrden());

function transaccionGuardarOrden()
{
    global $conexion,  $clave;
    $cabecera = $_POST["cabecera"];
    $productos = $_POST["productos"];
    $formasPago = $_POST["formasPago"];

    pg_query($conexion, "BEGIN");
    $corden = guardarCabeceraOrden($cabecera);
    if ($corden > 0) {
        $dorden = guardarDetallesOrden($corden, $productos);
        if ($dorden == 0) {
            return 0;
        }
    }
    $cfactura = 0;
    if ($cabecera["tipoDocumento"] == "FACTURA") {
        $cfactura = guardarFactura($cabecera, $productos);
        if (is_array($cfactura)) {
            $kardex = guardarKardex($productos, $cfactura["id"], $cabecera["id_cliente"], "F.V");
            if ($cabecera["formaPago"] == 'otros') {
                $formas = guardarFormasPagoMixto($cfactura["id"], $formasPago, $cabecera);
            } else {
                $formas = 1;
            }
            $uorden = actualizarDocumentoOrden($cabecera["tipoDocumento"], $cfactura["id"], $corden);
        }
    } else {
        $cfactura = guardarNotaVenta($cabecera, $productos);
        if ($cfactura > 0) {
            $kardex = guardarKardex($productos, $cfactura, $cabecera["id_cliente"], "N.V");
            //$formas = guardarFormasPagoMixto($cfactura, $formasPago, $cabecera["tipoDocumento"]);
            if ($cabecera["formaPago"] == 'otros') {
                //$formas = guardarFormasPagoMixto($cfactura["id"], $formasPago, $cabecera["tipoDocumento"]);
                $formas = guardarFormasPagoMixto($cfactura, $formasPago, $cabecera);
            } else {
                $formas = 1;
            }
            $uorden = actualizarDocumentoOrden($cabecera["tipoDocumento"], $cfactura, $corden);
        }
    }
    if ($cfactura == 0) {
        pg_query($conexion, "ROLLBACK");
        return ["status" => "error", "mensaje" => "No se pudo guardar la factura."];
    }
    if (is_string($kardex)) {
        pg_query($conexion, "ROLLBACK");
        return ["status" => "error", "mensaje" => $kardex];
    }
    if ($formas == 0) {
        pg_query($conexion, "ROLLBACK");
        return ["status" => "error", "mensaje" => "No se pudo guardar formas pago."];
    }
    if ($formas == -1) {
        pg_query($conexion, "ROLLBACK");
        return ["status" => "error", "mensaje" => "No se pudo guardar pago crédito."];
    }

    /*   if ($cabecera["tipoDocumento"] == "FACTURA") {
        $clave = generarClaveFactura($numserie, $cfactura["numero"]);
        $autorizar = autorizarFactura($cfactura["id"], $clave);
        $resp = [
            "id_orden" => $corden,
            "factura" => $autorizar
        ];
        return $resp;
    } */
    pg_query($conexion, "COMMIT");
    if (pg_transaction_status($conexion) !== PGSQL_TRANSACTION_INERROR) {
        // Auditoria
        insert_registro('CREACION ORDEN RESTAURANTE CON ID: ' . $corden);
        foreach ($formas as $idforma) {
            insert_registro('CREACION FORMA DE PAGO MIXTO CON ID: ' . $idforma);
        }
        if ($cabecera["tipoDocumento"] == "FACTURA") {
            $autorizar = autorizarFactura($cfactura["id"], $clave);
            $resp = [
                "status" => "correcto",
                "id_orden" => $corden,
                "factura" => $autorizar
            ];
            return $resp;
        } else if ($cabecera["tipoDocumento"] == "NOTA") {
            return [
                "status" => "correcto",
                "id_orden" => $corden,
                "nota" => ["id" => $cfactura]
            ];
        }
    } else {
        return ["status" => "error", "mensaje" => "No se pudo guardar la orden."];
    }
}

function guardarFactura($cabecera, $productos)
{
    global $numserie, $clave;
    $nrofac = obtenerNumFactura();
    $clave = generarClaveFactura($numserie, $nrofac);
    $cfactura = guardarFacturaCabecera($cabecera, $nrofac, $clave);
    if (is_array($cfactura)) {
        $dfactura = guardarDetallesFactura($cfactura["id"], $productos);
        if ($dfactura == 0) {
            return 0;
        }
    }
    return $cfactura;
}
function guardarNotaVenta($cabecera, $productos)
{
    $cfactura = guardarNotaVentaCabecera($cabecera);
    if ($cfactura > 0) {
        $dfactura = guardarDetallesNotaVenta($cfactura, $productos);
        if ($dfactura == 0) {
            return 0;
        }
    }
    return $cfactura;
}

function guardarCabeceraOrden($datos)
{
    global $conexion, $fecha, $puntoventa, $idusuario;
    $id = obtenerIdOrden();
    $idcliente = $datos["id_cliente"];
    $iva = $datos["totalIva"];
    $tarifa12 = $datos["totalTarifa12"];
    $tarifa0 = $datos["totalTarifa0"];
    $total = $datos["totalVenta"];
    $mesa = mb_strtoupper($datos["mesa"]);
    $descuento = $datos["totalDescuento"];
    $sql = "INSERT INTO restaurante_ordenes(
        id_restaurante_orden, id_punto_venta, id_cliente, id_usuario, 
        comprobante, fecha_creacion, tarifa12, tarifa0, iva, descuento, 
        total, estado,mesa)
        VALUES ($id, $puntoventa, $idcliente, $idusuario, 
        '$id', '$fecha', $tarifa12, $tarifa0, $iva, $descuento, 
        $total, 'Activo','$mesa');
    ";
    $res = pg_query($conexion, $sql);
    if (empty($res)) {
        return 0;
    }
    return $id;
}
function guardarDetallesOrden($idorden, $datos)
{
    global $conexion;
    foreach ($datos as $detalle) {
        $id = obtenerIdDetalleOrden();
        $codprod = $detalle["cod_producto"];
        $cantidad = $detalle["cantidad"];
        $precio = $detalle["precio"];
        $total = $detalle["total_con_descuentos"];
        $descuento = $detalle["descuento"];
        $caracteristicas = json_encode($detalle["caracteristicas"], JSON_UNESCAPED_UNICODE);
        $sql = "INSERT INTO restaurante_detalle_ordenes(
            id_restaurante_detalle_orden, id_restaurante_orden, cod_productos, 
            cantidad, precio_venta, descuento, total,caracteristicas)
            VALUES ($id, $idorden, $codprod, 
            $cantidad, $precio, $descuento, $total,'$caracteristicas');";
        $res = pg_query($conexion, $sql);
        if (empty($res)) {
            var_dump($sql);
            var_dump(pg_errormessage($conexion));
            return 0;
        }
    }
    return $idorden;
}

function guardarFacturaCabecera($datos, $nrofac, $clave)
{
    global $conexion, $puntoventa, $idusuario, $fechaactual, $horaactual, $numserie;
    $id = obtenerIdFactura();
    $idcliente = $datos["id_cliente"];
    $iva = $datos["totalIva"];
    $tarifa12 = $datos["totalTarifa12"];
    $tarifa0 = $datos["totalTarifa0"];
    $total = $datos["totalVenta"];
    $tipoprecio = "MINORISTA";
    $formapago = $datos["formaPago"];
    $idvendedor = 1;
    $valorrecibido = $datos["valorRecibido"];
    $cambio = $datos["cambio"];
    $descuento = $datos["totalDescuento"];
    $sql = "INSERT INTO factura_venta(
        id_factura_venta, id_empresa, id_cliente, id_usuario, comprobante, 
        num_factura, fecha_actual, hora_actual, fecha_cancelacion, tipo_precio, 
        forma_pago, num_autorizacion, fecha_autorizacion, fecha_caducidad, 
        tarifa0, tarifa12, iva_venta, descuento_venta, total_venta, estado, 
        fecha_anulacion, tarjeta_credito, temporal, valor_recibo, valor_cambio, 
        id_vendedor, num_serie, porc_tarje_venta, id_beneficiario, nombre_beneficiario, 
        clave, estado_fac, id_forma_pago, serie_guia_remision, marca_vehiculo, 
        placa_fac, propiedad, num_reclamo, num_chasis)
        VALUES ($id, $puntoventa, $idcliente, $idusuario, $id, 
        '$nrofac', '$fechaactual', '$horaactual', '$fechaactual', '$tipoprecio', 
        '$formapago', null, null, '$fechaactual', 
        $tarifa0, $tarifa12, $iva, $descuento, $total, 'Activo', 
        '$fechaactual', null, 1, $valorrecibido, $cambio, 
        $idvendedor, '$numserie', 0, null, null, 
        '$clave', 0, 1, '000000000', null, 
        null, null, null, 1);
    ";

    $res = pg_query($conexion, $sql);
    if (empty($res)) {
        return 0;
    }
    return ["id" => $id, "numero" => $nrofac];
}
function guardarDetallesFactura($idfactura, $datos)
{
    global $conexion, $fechaactual;
    foreach ($datos as $detalle) {
        $id = obtenerIdDetalleFactura();
        $codprod = $detalle["cod_producto"];
        $cantidad = $detalle["cantidad"];
        $precio = $detalle["precio"];
        $total = $detalle["total_con_descuentos"];
        $descuento = $detalle["descuento"];
        $bienserv = $detalle["bien_servicios"];
        $sql = "
        INSERT INTO detalle_factura_venta(
            id_detalle_venta, id_factura_venta, cod_productos, cantidad, 
            precio_venta, descuento_producto, total_venta, estado, pendientes, 
            fecha_venta, bien_servicio)
            VALUES ($id, $idfactura, $codprod, $cantidad, 
            $precio, $descuento, $total, 'Activo', 0, 
            '$fechaactual', '$bienserv');";
        $res = pg_query($conexion, $sql);
        if (empty($res)) {
            return 0;
        }
    }
    return $idfactura;
}

function guardarNotaVentaCabecera($datos)
{
    global $conexion, $puntoventa, $idusuario, $fechaactual, $horaactual;
    $id = obtenerIdNotaVenta();
    //$nrofac = obtenerNumFactura();

    $idcliente = $datos["id_cliente"];
    $iva = $datos["totalIva"];
    $tarifa12 = $datos["totalTarifa12"];
    $tarifa0 = $datos["totalTarifa0"];
    $total = $datos["totalVenta"];
    $tipoprecio = "MINORISTA";
    $formapago = $datos["formaPago"];
    $idvendedor = 1;
    $descuento = $datos["totalDescuento"];
    $sql = "
    INSERT INTO facturas_novalidas(
        id_facturas_novalidas, id_cliente, id_usuario, comprobante, fecha_actual, 
        hora_actual, tipo_precio, forma_pago, tarifa0, tarifa12, iva_venta, 
        descuento_venta, total_venta, estado, id_empresa, id_vendedor)
        VALUES ($id, $idcliente, $idusuario, $id, '$fechaactual', 
        '$horaactual', '$tipoprecio', '$formapago', $tarifa0, $tarifa12, $iva, 
        $descuento, $total, 'Activo', '$puntoventa', $idvendedor);
    ";

    $res = pg_query($conexion, $sql);
    if (empty($res)) {
        return 0;
    }
    return $id;
}
function guardarDetallesNotaVenta($idnota, $datos)
{
    global $conexion;
    foreach ($datos as $detalle) {
        $id = obtenerIdDetalleNotaVenta();
        $codprod = $detalle["cod_producto"];
        $cantidad = $detalle["cantidad"];
        $precio = $detalle["precio"];
        $total = $detalle["total_con_descuentos"];
        $descuento = $detalle["descuento"];
        $bienserv = $detalle["bien_servicios"];
        $sql = "
        INSERT INTO detalle_facturas_novalidas(
            id_detalle_facturas_novalidas, id_facturas_novalidas, cod_productos, 
            cantidad, precio_venta, descuento_producto, total_venta, estado, 
            pendientes, bien_servicio)
            VALUES ($id, $idnota, $codprod, 
            $cantidad, $precio, $descuento, $total, 'Activo', 
            '0','$bienserv');
            ";
        $res = pg_query($conexion, $sql);
        if (empty($res)) {
            return 0;
        }
    }
    return $idnota;
}

function guardarFormasPagoMixto($idfactura, $formas, $cabeceradoc)
{
    global $conexion, $fechaactual;
    $ids = [];
    foreach ($formas as $forma) {
        $id = obtenerIdFormaPagoMixto();
        $formap = $forma["formaPago"];
        $nrodoc = $forma["nroDoc"];
        $valor = $forma["valor"];
        $sql = "INSERT INTO formas_pago_mixto(
            id_formas_pago_mixto, id_factura_venta, fecha_actual, forma_pago, 
            tarjeta_credito, numero_documento, valor, estado, id_cuenta, 
            tipo_documento)
            VALUES ($id, $idfactura, '$fechaactual', '$formap', 
            null, '$nrodoc', '$valor', 'Activo', null, 
            '$cabeceradoc[tipoDocumento]');
            ";
        $res = pg_query($conexion, $sql);

        if (empty($res)) {
            return 0;
        }
        array_push($ids, $id);

        if ($formap == "CREDITO") {
            $tdoc = "";
            if ($cabeceradoc["tipoDocumento"] == 'FACTURA') {
                $tdoc = 'Factura';
            } else if ($cabeceradoc["tipoDocumento"] == 'NOTA') {
                $tdoc = 'Nota';
            }
            $guardarpv = guardarPagosVenta(
                $cabeceradoc["id_cliente"],
                $idfactura,
                $fechaactual,
                $tdoc,
                $valor,
                $forma["fechaVence"]
            );
            if (empty($guardarpv)) {
                return -1;
            }
        }
    }
    return $ids;
}
function guardarKardex($productos, $idfacutra, $idcliente, $tipoDoc)
{
    global $puntoventa, $idusuario;
    foreach ($productos as $key => $detalle) {
        $codprod = $detalle["cod_producto"];
        $cantidad = $detalle["cantidad"];
        $articulo = $detalle["articulo"];
        $observaciones = "";
        $stock = verificarStock($codprod, $puntoventa, $cantidad);
        if (is_numeric($stock)) {
            return "La cantidad del producto " . $articulo . " sobrepasa el stock disponible. Disponible $stock";
        }

        procesarKardexSalida($codprod, "$tipoDoc - " . $idfacutra, $cantidad, obtenerStock($codprod, $puntoventa), NULL, 'Activo', $puntoventa, 'V', $idfacutra, null, NULL, NULL, $idcliente, $observaciones, NULL, NULL, $idusuario);
    }
}

function guardarPagosVenta($idcliente, $idfactura, $fechacredito, $tipodoc, $montocredito, $fechavence)
{
    global $puntoventa, $idusuario, $conexion;
    $id = obtenerIdPagosVenta();
    $iddpv = obtenerIdDetallePagosVenta();

    $sql = "
    INSERT INTO pagos_venta(
        id_pagos_venta, id_cliente, id_factura_venta, id_usuario, fecha_credito, 
        adelanto, meses, tipo_documento, monto_credito, saldo, estado, 
        fecha_dias, id_empresa)
        VALUES ($id, $idcliente, $idfactura, $idusuario, '$fechacredito', 
        '0.00', 1, '$tipodoc', $montocredito, $montocredito, 'Activo', 
        '$fechavence', $puntoventa);
    
        INSERT INTO detalle_pagos_venta(
        id_detalle_pagos_venta, id_pagos_venta, fecha_pago, cuota, saldo, 
        estado)
        VALUES ($iddpv,$id, '$fechacredito', $montocredito, $montocredito, 
        'Activo');
    ";
    //var_dump($sql);
    $res = pg_query($conexion, $sql);
    return $res;
}

function obtenerIdOrden()
{
    global $conexion;
    $sql = "select max(id_restaurante_orden) from restaurante_ordenes";
    $res = pg_query($conexion, $sql);
    if (pg_num_rows($res) > 0) {
        return pg_fetch_row($res)[0] + 1;
    }
    return 0;
}
function obtenerIdDetalleOrden()
{
    global $conexion;
    $sql = "select max(id_restaurante_detalle_orden) from restaurante_detalle_ordenes";
    $res = pg_query($conexion, $sql);
    if (pg_num_rows($res) > 0) {
        return pg_fetch_row($res)[0] + 1;
    }
    return 0;
}
function obtenerIdFactura()
{
    global $conexion;
    $sql = "select max(id_factura_venta) from factura_venta";
    $res = pg_query($conexion, $sql);
    if (pg_num_rows($res) > 0) {
        return pg_fetch_row($res)[0] + 1;
    }
    return 0;
}
function obtenerIdDetalleFactura()
{
    global $conexion;
    $sql = "select max(id_detalle_venta) from detalle_factura_venta";
    $res = pg_query($conexion, $sql);
    if (pg_num_rows($res) > 0) {
        return pg_fetch_row($res)[0] + 1;
    }
    return 0;
}
function obtenerIdDetalleNotaVenta()
{
    global $conexion;
    $sql = "select max(id_detalle_facturas_novalidas) from detalle_facturas_novalidas";
    $res = pg_query($conexion, $sql);
    if (pg_num_rows($res) > 0) {
        return pg_fetch_row($res)[0] + 1;
    }
    return 0;
}
function obtenerIdFormaPagoMixto()
{
    global $conexion;
    $sql = "select max(id_formas_pago_mixto) from formas_pago_mixto";
    $res = pg_query($conexion, $sql);
    if (pg_num_rows($res) > 0) {
        return pg_fetch_row($res)[0] + 1;
    }
    return 0;
}
function obtenerIdNotaVenta()
{
    global $conexion;
    $sql = "select max(id_facturas_novalidas) from facturas_novalidas";
    $res = pg_query($conexion, $sql);
    if (pg_num_rows($res) > 0) {
        return pg_fetch_row($res)[0] + 1;
    }
    return 0;
}
function obtenerIdPagosVenta()
{
    global $conexion;
    $sql = "select max(id_pagos_venta) from pagos_venta";
    $res = pg_query($conexion, $sql);
    if (pg_num_rows($res) > 0) {
        return pg_fetch_row($res)[0] + 1;
    }
    return 0;
}
function obtenerIdDetallePagosVenta()
{
    global $conexion;
    $sql = "select max(id_detalle_pagos_venta) from detalle_pagos_venta";
    $res = pg_query($conexion, $sql);
    if (pg_num_rows($res) > 0) {
        return pg_fetch_row($res)[0] + 1;
    }
    return 0;
}
function obtenerIdTransaccion()
{
    global $conexion;
    $sql = "select max(id_transacciones) from transacciones";
    $res = pg_query($conexion, $sql);
    if (pg_num_rows($res) > 0) {
        return pg_fetch_row($res)[0] + 1;
    }
    return 0;
}
function obtenerNumFactura()
{
    global $conexion;
    $sql = "select max(num_factura) from factura_venta";
    $res = pg_query($conexion, $sql);
    if (pg_num_rows($res) > 0) {
        return str_pad(intval(pg_fetch_row($res)[0]) + 1, 9, "0", STR_PAD_LEFT);
    }
    return 0;
}

function verificarStock($codprod, $bodega, $cantidadsalida)
{
    $prod = obtenerProducto($codprod);
    if ($prod["inventariable"] == "Si") {
        $stock = obtenerStock($codprod, $bodega);
        if ($stock < $cantidadsalida) {
            return $stock;
        }
    }
    return true;
}
function obtenerProducto($codprod)
{
    global $conexion;
    $sql = "select * from productos where cod_productos=$codprod;";
    $res = pg_query($conexion, $sql);
    $rows = pg_fetch_all($res);
    if (!$rows) {
        return null;
    }
    return $rows[0];
}

function generarClaveFactura($serie, $numfactura)
{
    global $fechaactual, $conexion;

    $consulta_emision = pg_query($conexion, "select codigo_temision from tipo_emision where estado_temision='Activo'");
    while ($row = pg_fetch_row($consulta_emision)) {
        $emision = $row[0]; //normal cuando generamos la clave
    }

    $secuencial = $serie . "-" . $numfactura;
    $ip = $secuencial;
    $iparr =  explode("-", $ip);
    $secuencialresult = $iparr[2];
    $secuencialmitad = $iparr[1];
    $secuencialinicial = $iparr[0];
    $consulta_ambiente = pg_query($conexion, "select codigo_ambi from ambiente where estado_ambi = 'Activo' ");
    while ($row = pg_fetch_row($consulta_ambiente)) {
        $ambiente = $row[0];
    }
    $consulta_empresa = pg_query($conexion, "select ruc_empresa,clave, token from empresa where id_empresa = 1");
    while ($row = pg_fetch_row($consulta_empresa)) {
        $ruc = $row[0];
    }
    $consulta_cod_docu = pg_query($conexion, "select codigo from tipo_comprobante where id_tipo_comprobante=1");
    while ($row = pg_fetch_row($consulta_cod_docu)) {
        $codDoc = $row[0]; //normal cuando generamos la clave
    }
    $valortxt9 = $fechaactual;
    $ip = $valortxt9;
    $fechasepar =  explode("-", $ip);
    $dia = $fechasepar[2];
    $mes = $fechasepar[1];
    $anio = $fechasepar[0];
    $valortxt9 = "$dia" . "$mes" . "$anio";
    $valorcodDoc = $codDoc;
    $valortruc = $ruc;
    $valorambiente = $ambiente;
    $secuencialmitad = $iparr[1];
    $secuencialinicial = $iparr[0];
    $valortxt81 = $secuencialinicial;
    $valorsiete = $secuencialmitad;
    $valorsecuencial = $secuencialresult;
    $valortxt9 = "$dia" . "$mes" . "$anio";
    $valoremision = $emision;

    /* var_dump($valortxt9);
    var_dump($valorcodDoc);
    var_dump($valortruc);
    var_dump($valorambiente);
    var_dump($valortxt81);
    var_dump($valorsiete . '' . $valorsecuencial);
    var_dump($valortxt9);
    var_dump($valoremision); */
    $clave = generarClave($valortxt9, $valorcodDoc, $valortruc, $valorambiente, $valortxt81, $valorsiete . '' . $valorsecuencial, $valortxt9, $valoremision);
    return $clave;
}

function autorizarFactura($idfactura, $clave)
{
    global $conexion, $appFirma, $pathXmls, $pathARchivoP12, $claveFirma;
    /////FACTURA ELECTRONICA////
    ///1 GUARDADO
    ///2 GENERADO
    ///3 AUTORIZADO
    ///4 RECHAZADO

    $consulta_ambiente = pg_query($conexion, "select codigo_ambi from ambiente where estado_ambi = 'Activo' ");
    while ($row = pg_fetch_row($consulta_ambiente)) {
        $ambiente = $row[0];
    }

    $consulta_emision = pg_query($conexion, "select codigo_temision from tipo_emision where estado_temision='Activo'");
    while ($row = pg_fetch_row($consulta_emision)) {
        $emision = $row[0]; //normal cuando generamos la clave
    }

    $consulta_cod_docu = pg_query($conexion, "select codigo from tipo_comprobante where id_tipo_comprobante=1");
    while ($row = pg_fetch_row($consulta_cod_docu)) {
        $codDoc = $row[0]; //normal cuando generamos la clave
    }
    $consulta_empresa = pg_query($conexion, "select ruc_empresa,clave, token from empresa where id_empresa = 1");
    while ($row = pg_fetch_row($consulta_empresa)) {
        $ruc = $row[0];
        $pass = $row[1];
        $token = $row[2];
    }

    $consulta_ambiente = pg_query($conexion, "select codigo_ambi from ambiente where estado_ambi = 'Activo' ");
    while ($row = pg_fetch_row($consulta_ambiente)) {
        $ambiente = $row[0];
    }
    $result = generarXML($idfactura, $codDoc, $ambiente, $emision);

    $doc = new DOMDocument('1.0', 'UTF-8');
    $doc->loadXML($result); // xml 
    $doc->save($pathXmls . "fac" . '.xml');
    exec("$appFirma " . $pathXmls . '/fac "' . $pathARchivoP12 . '" "' . $claveFirma . '"', $resultado);
    try{
        $respuesta = consultarComprobante($ambiente, $clave);
    }catch(Exception $e){
        $data=-1000;
    }
    

    if (isset($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado)) {
        if ($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado == 'AUTORIZADO') {
            $numeroAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion;
            $fechaAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;
            $ambienteAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente;
            $data = 2;
            pg_query($conexion, "UPDATE factura_venta SET fecha_autorizacion = '" . $fechaAutorizacion . "',  estado_fac = '2', num_autorizacion = '" . $numeroAutorizacion . "' WHERE id_factura_venta = '$idfactura'");
            $dataFile = generarXMLCDATA($respuesta);
            $doc = new DOMDocument('1.0', 'UTF-8');
            $doc->loadXML($dataFile); // xml     
            $doc->save($pathXmls . $numeroAutorizacion . '.xml');
        } else {
            $data = 7;
            pg_query($conexion, "UPDATE factura_venta SET estado_fac = '7' where id_factura_venta = '$idfactura'"); // NO AUTORIZADO
        }
    }

    $item = array(
        'estado' => $data,
        'id' => $idfactura
    );
    return $item;
}

function actualizarDocumentoOrden($tipoDoc, $iddoc, $idorden)
{
    global $conexion;
    $sql = "update restaurante_ordenes set
    tipo_documento='$tipoDoc',
    id_documento='$iddoc'
    where id_restaurante_orden=$idorden
    ";

    $res = pg_query($conexion, $sql);
    if (empty($res)) {
        return 0;
    }
    return $iddoc;
}

/*
function guardarTransaccion($id, $comprobante, $concepto, $debe, $haber, $tipoTrans, $numtrans, $idcliente)
{
    global $conexion, $fechaactual, $horaactual, $idusuario, $puntoventa;
    $id = obtenerIdTransaccion();
    $nrotrans = obtenerNroTransaccion();
    $idpv = obtenerIdTransaccionPv();
    $sql = "
        INSERT INTO transacciones(
        id_transacciones, id_usuario, comprobante, fecha_actual, hora_actual, 
        concepto, total_debe, total_haber, saldo, id_tipo_transaccion, 
        num_transaccion, estado, id_cliente, deposito, observacion, num_cuenta, 
        banco, identificador_cli_pro, valor_concepto, id_empresa, fecha_registro, 
        id_transaccion_pv)
        VALUES ($id, $idusuario, '$fechaactual', '$horaactual', $concepto, 
        $concepto, $debe, $haber, ?, 1, 
        $nrotrans, 'Activo', $idcliente, null, null, null, 
        null, 'VEN', null, $puntoventa, '$fechaactual', 
        '$idpv');
    ";
}
function obtenerNroTransaccion()
{
    global $conexion, $puntoventa;
    $sql = "select max(num_transaccion) from transacciones where id_tipo_transaccion='1' and id_empresa= '$puntoventa'";
    $res = pg_query($conexion, $sql);
    if (pg_num_rows($res) > 0) {
        return pg_fetch_row($res)[0] + 1;
    }
    return 0;
}
function obtenerIdTransaccionPv()
{
    global $conexion, $puntoventa;
    $sql = "select max(id_transaccion_pv::int) from transacciones where id_empresa= '$puntoventa'";
    $res = pg_query($conexion, $sql);
    if (pg_num_rows($res) > 0) {
        return pg_fetch_row($res)[0] + 1;
    }
    return 0;
}
*/
