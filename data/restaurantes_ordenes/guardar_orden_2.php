<?php
session_start();
include '../../procesos/base.php';
include '../../procesos/funciones.php';
include_once '../../procesos/kardexValorizado.php';
require_once '../../procesos/detalleProductosBodega.php';

date_default_timezone_set('America/Guayaquil');

$conexion = conectarse();
$fecha = date('Y-m-d H:i:s');
$puntoventa = $_SESSION["PV"];
$idusuario = $_SESSION["id"];
$fechaactual = date("Y-m-d");
$horaactual = date("h:i:s A");
$numserie = "001-001";

echo json_encode(transaccionGuardarOrden());

function transaccionGuardarOrden()
{
    global $conexion, $numserie;
    //pg_query($conexion, "BEGIN");
    $cabecera = $_POST["cabecera"];
    $productos = $_POST["productos"];
    $corden = guardarCabeceraOrden($cabecera);
    if ($corden > 0) {
        $dorden = guardarDetallesOrden($corden, $productos);
        if ($dorden == 0) {
            return 0;
        }
    }
    pg_query($conexion, "COMMIT");
    if (pg_transaction_status($conexion) !== PGSQL_TRANSACTION_INERROR) {
        $params = mapearRequestFacturaVenta(
            $cabecera["id_cliente"],
            obtenerNumFactura(),
            "MINORISATA",
            "otros",
            $cabecera["totalTarifa0"],
            $cabecera["totalTarifa12"],
            $cabecera["totalIva"],
            0,
            $cabecera["totalVenta"],
            $cabecera["identificacion"],
            $cabecera["nombres_cli"],
            $cabecera["direccion_cli"],
            $cabecera["celular"],
            $cabecera["correo"],
            $productos,
            $cabecera["tipoDocumento"],
            $numserie
        );
       /*  var_dump(urlCurl($params)); */
        return ["status" => "correcto", "id_orden" => $corden, "datos_factura" => $params];
    }
    return ["status" => "error", "mensaje" => "No se pudo guardar la orden"];
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
    $sql = "INSERT INTO restaurante_ordenes(
        id_restaurante_orden, id_punto_venta, id_cliente, id_usuario, 
        comprobante, fecha_creacion, tarifa12, tarifa0, iva, descuento, 
        total, estado)
        VALUES ($id, $puntoventa, $idcliente, $idusuario, 
        '$id', '$fecha', $tarifa12, $tarifa0, $iva, 0, 
        $total, 'Activo');
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
        $total = $cantidad * $precio;

        $sql = "INSERT INTO restaurante_detalle_ordenes(
            id_restaurante_detalle_orden, id_restaurante_orden, cod_productos, 
            cantidad, precio_venta, descuento, total)
            VALUES ($id, $idorden, $codprod, 
            $cantidad, $precio, 0, $total);";
        $res = pg_query($conexion, $sql);
        if (empty($res)) {
            return 0;
        }
    }
    return $idorden;
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
    $stock = obtenerStock($codprod, $bodega);
    if ($stock < $cantidadsalida) {
        return $stock;
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

/* function urlCurl($body)
{
    $urlexplode = explode("/", $_SERVER["REQUEST_URI"]);
    array_pop($urlexplode);
    //array_pop($urlexplode);
    $implodeurl = implode("/", $urlexplode);
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$implodeurl";
    $url .= "/guardar_factura_venta.php";
    // The submitted form data, encoded as query-string-style
    // name-value pairs
    //$body = 'monkey=uncle&rhino=aunt';
    $c = curl_init($url);
    curl_setopt($c, CURLOPT_POST, true);
    curl_setopt($c, CURLOPT_POSTFIELDS, $body);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    $page = curl_exec($c);
    curl_close($c);
    var_dump($page);
} */

function mapearRequestFacturaVenta(
    $idcliente,
    $numfactura,
    $tipoPrecio,
    $formaPago,
    $tarifa0,
    $tarifa12,
    $iva,
    $desc,
    $tot,
    $rucCi,
    $nombreCli,
    $dirCli,
    $telCli,
    $correo,
    $productos,
    $tipoVenta,
    $numserie
) {
    global $fechaactual, $horaactual;
    $campo1 = "";
    $campo2 = "";
    $campo3 = "";
    $campo4 = "";
    $campo5 = "";
    $campo6 = "";
    foreach ($productos as $prod) {
        $campo1 .= "|" . $prod["cod_producto"];
        $campo2 .= "|" . $prod["cantidad"];
        $campo3 .= "|" . $prod["precio"];
        $campo4 .= "|" . 0;
        $campo5 .= "|" . ($prod["cantidad"] * $prod["precio"]);
        $campo6 .= "|" . 0;
    }

    $parametros = [
        "id_fac" => "",
        "id_cliente" => $idcliente,
        "comprobante" => "",
        "num_factura" => $numfactura,
        "fecha_actual" => $fechaactual,
        "hora_actual" => $horaactual,
        "proforma" => "",
        "cancelacion" => "",
        "tipo_precio" => $tipoPrecio,
        "formaspago" => $formaPago,
        "adelanto" => 0.00,
        "meses" => "",
        "autorizacion" => "",
        "fecha_auto" => $fechaactual,
        "fecha_caducidad" => $fechaactual,
        "tarifa0" => $tarifa0,
        "tarifa12" => $tarifa12,
        "iva" => $iva,
        "desc" => $desc,
        "tot" => $tot,
        "ruc_ci" => $rucCi,
        "nombre_cliente" => $nombreCli,
        "direccion_cliente" => $dirCli,
        "telefono_cliente" => $telCli,
        "correo" => $correo,
        "campo1" => $campo1,
        "campo2" => $campo2,
        "campo3" => $campo3,
        "campo4" => $campo4,
        "campo5" => $campo5,
        "campo6" => $campo6,
        "tipo_venta" => $tipoVenta,
        "tarjetas" => "",
        "valor_recibo" => 1,
        "valor_cambio" => 1,
        "id_vendedor" => 1,
        "fecha_dias" => "",
        "num_guia_remision" => "000000000",
        "marca_vehiculo" => "",
        "placa_fac" => "",
        "propiedad" => "",
        "num_reclamo" => "",
        "num_chasis" => "",
        "formas" => 1,
        "num_tarjeta" => "",
        "reservacion" => "",
        "num_serie" => $numserie,
        "id_proforma_tecnico" => "0",
        "cuenta_cheque" => ""
    ];
    return $parametros;
}
