<?php

session_start();
include '../../procesos/base.php';
include '../../reportes/reten_elect.php';
//include '../../reportes/reten_elect_consulta.php';
include '../../firma/firma.php';
include '../../firma/xades.php';
include 'generarPDFReten.php';
include '../../admin/correo.php';
include '../../procesos/funciones.php';
require_once __DIR__ . '/../../procesos/configuracion.php';
require_once __DIR__."/guardar_pxp_retencion.php";

$conf = new Configuracion();
$esquema = $conf->getNombreEsquema();
$appFirma = $conf->getPathAplicacionFIrma("app_firma");
$pathXmls = $conf->getPathXmlsFirma();
$pathARchivoP12 = $conf->getArchivoP12();
$claveFirma = $conf->getParametroEmpresa("clave_firma");

conectarse();
$conexion = conectarse();
error_reporting(0);

$datosimprimir = 0;
$forma = $_POST['formascc'];
$defaultMail = "franciis.cevallos@gmail.com";
date_default_timezone_set('America/Guayaquil');

// datos detalle factura
$campo1reten = $_POST['campo1reten'];
$campo2reten = $_POST['campo2reten'];
$campo3reten = $_POST['campo3reten'];
$campo4reten = $_POST['campo4reten'];
$campo5reten = $_POST['campo5reten'];
$campo6reten = $_POST['campo6reten'];
$campo7reten = $_POST['campo7reten'];
$arreglo1reten = explode('|', $campo1reten);
$arreglo2reten = explode('|', $campo2reten);
$arreglo3reten = explode('|', $campo3reten);
$arreglo4reten = explode('|', $campo4reten);
$arreglo5reten = explode('|', $campo5reten);
$arreglo6reten = explode('|', $campo6reten);
$arreglo7reten = explode('|', $campo7reten);
$nelemreten = count($arreglo1reten);
// fin
//contador factura compra


$conpuntoresult = $_SESSION['PV'];

// fin
$data = 0;
$valoreten = 0;
$resultreten = 0;
$fecha = date('Y-m-d', time());
$hora = date('h:i:s A', time());
$comprobar = pg_query("select id_factura from retencion_iva_factura_compra");
while ($row2 = pg_fetch_row($comprobar)) {
    if ($row2[1] == $_POST["id_factura"]) {
        $data = 2;
    }
}
$datos = 0;
$valoreten = 0;
$resultreten = 0;
$fecha = date('Y-m-d', time());
$hora = date('h:i:s A', time());




if (isset($_POST['actualizar_clave_acceso']) == "actualizar_clave_acceso") {
    $consulta_empresa = pg_query("select ruc_empresa,clave, token from empresa where id_empresa = 1");
    while ($row = pg_fetch_row($consulta_empresa)) {
        $ruc = $row[0];
    }
    $consulta_cod_docu = pg_query("select codigo from tipo_comprobante where id_tipo_comprobante = 5");
    while ($row = pg_fetch_row($consulta_cod_docu)) {
        $codDoc = $row[0];
    }
    $consulta_ambiente = pg_query("select codigo_ambi from ambiente where estado_ambi = 'Activo' ");
    while ($row = pg_fetch_row($consulta_ambiente)) {
        $ambiente = $row[0];
    }
    $consulta_emision = pg_query("select codigo_temision from tipo_emision order by codigo_temision asc  limit 1");
    while ($row = pg_fetch_row($consulta_emision)) {
        $emision = $row[0];
    }
    $consulta_num_factura = pg_query("select num_serie from retencion_fuente_factura_compra where id_factura='" . $_POST['id'] . "' and id_gastos=1 ");
    while ($row = pg_fetch_row($consulta_num_factura)) {
        $num_serie_fac = $row[0];
    }

    $num_fecha_emision = '';
    $consulta_fecha_emision = pg_query("select fecha_emision from factura_compra where id_factura_compra ='" . $_POST['id'] . "'");
    while ($row = pg_fetch_row($consulta_fecha_emision)) {
        $num_fecha_emision = $row[0];
    }

    $fechasepar = explode("-", $num_fecha_emision);

    $valorfecha = "$fechasepar[2]" . "$fechasepar[1]" . "$fechasepar[0]";
    $periodo_fiscal = "$mes" . "$anio";
    $ip = $num_serie_fac;
    $iparr = explode("-", $ip);
    $secuencialresult = $iparr[2];
    $secuencialmitad = $iparr[1];
    $secuencialinicial = $iparr[0];
    $valorcodDoc = $codDoc;
    $valortruc = $ruc;
    $valorambiente = $ambiente;
    $secuencialmitad = $iparr[1];
    $secuencialinicial = $iparr[0];
    $valortxt81 = $secuencialinicial;
    $valorsiete = $secuencialmitad;
    $valorsecuencial = $secuencialresult;
    $valoremision = $emision;
    $clave = generarClave($valorfecha, $valorcodDoc, $valortruc, $valorambiente, $valortxt81, $valorsiete . '' . $valorsecuencial, $valorfecha, $valoremision);

    //    echo '::'."UPDATE retencion_fuente_factura_compra set clave='" . $clave . "' where id_factura='" . $_POST['id'] . "' and id_gastos=1";

    $sql = "UPDATE retencion_fuente_factura_compra set clave='" . $clave . "' where id_factura='" . $_POST['id'] . "' and id_gastos=1";


    $guardar = guardarSql($conexion, $sql);
    if ($guardar == 'true') {
        $data = 1;
    } else {
        $data = 0;
    }

    $itemuno = array(
        'estado' => $data
    );
}

if (isset($_POST['reenviarcorreo']) == "reenviarcorreo") {
    $datosimprimir = 1;
    $resultado = pg_query("SELECT  P.correo, P.empresa_pro, RF.valor_compra, RF.num_autorizacion, RF.fecha FROM retencion_fuente_factura_compra RF INNER JOIN factura_compra FC ON RF.id_factura = FC.id_factura_compra INNER JOIN proveedores P ON P.id_proveedor=FC.id_proveedor where RF.id_retencion_fuente_factura_compra='" . $_POST['id'] . "'");

    while ($row = pg_fetch_row($resultado)) {
        $email = $row[0];
        $nombre = $row[1];
        $total = $row[2];
        $num_autorizacion = $row[3];
        $fecha = $row[4];
    }

    $data = correo($fecha, $total, $pathXmls . $num_autorizacion . '.xml', $num_autorizacion . '.pdf', $nombre, $email, $pathXmls . $num_autorizacion . '.xml', generarPDFReten($_POST['id']), 1);

    if ($data == 1) {
        $resultado = pg_query("UPDATE retencion_fuente_factura_compra SET  estado = '1'  WHERE id_retencion_fuente_factura_compra = '" . $_POST['id'] . "'");

        if ($resultado) {
            $data = 1; // datos actualizados
        } else {
            $data = 4; // error al momento de guadar
        }
    }

    $itemuno = array(
        'estado' => $data
    );
}

if (isset($_POST['reenviarxml']) == "reenviarxml") {
    $datosimprimir = 1;
    $consulta_clave = pg_query("SELECT  RF.clave FROM retencion_fuente_factura_compra RF INNER JOIN factura_compra FC ON RF.id_factura = FC.id_factura_compra INNER JOIN proveedores P ON P.id_proveedor=FC.id_proveedor where RF.id_retencion_fuente_factura_compra='" . $_POST['id'] . "' ");
    while ($row = pg_fetch_row($consulta_clave)) {
        $consult_clave = $row[0];
    }

    $consulta_ambiente = pg_query("select codigo_ambi from ambiente where estado_ambi = 'Activo' ");
    while ($row = pg_fetch_row($consulta_ambiente)) {
        $ambiente = $row[0];
    }

    $consulta_emision = pg_query("select codigo_temision from tipo_emision where estado_temision='Activo'");
    while ($row = pg_fetch_row($consulta_emision)) {
        $emision = $row[0]; //normal cuando generamos la clave
    }

    $consulta_cod_docu = pg_query("select codigo from tipo_comprobante where id_tipo_comprobante=1");
    while ($row = pg_fetch_row($consulta_cod_docu)) {
        $codDoc = $row[0]; //normal cuando generamos la clave
    }
    $consulta_empresa = pg_query("select ruc_empresa,clave, token from empresa where id_empresa = 1");
    while ($row = pg_fetch_row($consulta_empresa)) {
        $ruc = $row[0];
        $pass = $row[1];
        $token = $row[2];
    }

    $respuesta = consultarComprobante($ambiente, $consult_clave);
    print_r($respuesta);
    if (isset($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado)) {
        if ($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado == 'AUTORIZADO') {
            $numeroAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion;
            $fechaAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;
            $ambienteAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente;
            $data = 2;

            pg_query("UPDATE retencion_fuente_factura_compra SET fecha= '" . $fechaAutorizacion . "',  estado = '2', num_autorizacion = '" . $numeroAutorizacion . "' WHERE id_retencion_fuente_factura_compra = '" . $_POST['id'] . "'");
            $dataFile = generarXMLCDATA($respuesta);
            $doc = new DOMDocument('1.0', 'UTF-8');
            $doc->loadXML($dataFile); // xml  
            $doc->save($pathXmls . $numeroAutorizacion . '.xml');
        } else {
            $data = 7;
            pg_query("UPDATE retencion_fuente_factura_compra SET estado = '7' where id_retencion_fuente_factura_compra = '" . $_POST['id'] . "'"); // NO AUTORIZADO
        }
    } else {
        //$data = $respuesta['RespuestaAutorizacionComprobante']['autorizaciones']['autorizacion']['estado'];
        pg_query("UPDATE retencion_fuente_factura_compra SET estado = '7' where id_retencion_fuente_factura_compra = '" . $_POST['id'] . "'"); // Rechazado
    }

    $itemuno = array(
        'estado' => $data,
        'id' => $_POST["id_factura"]
    );
}
if (isset($_POST['enviarxml']) == "enviarxml") {
    $datosimprimir = 1;
    $consulta_clave = pg_query("SELECT  RF.clave FROM retencion_fuente_factura_compra RF INNER JOIN factura_compra FC ON RF.id_factura = FC.id_factura_compra INNER JOIN proveedores P ON P.id_proveedor=FC.id_proveedor where RF.id_retencion_fuente_factura_compra='" . $_POST['id'] . "' ");
    while ($row = pg_fetch_row($consulta_clave)) {
        $consult_clave = $row[0];
    }

    $consulta_ambiente = pg_query("select codigo_ambi from ambiente where estado_ambi = 'Activo' ");
    while ($row = pg_fetch_row($consulta_ambiente)) {
        $ambiente = $row[0];
    }

    $consulta_emision = pg_query("select codigo_temision from tipo_emision where estado_temision='Activo'");
    while ($row = pg_fetch_row($consulta_emision)) {
        $emision = $row[0]; //normal cuando generamos la clave
    }

    $consulta_cod_docu = pg_query("select codigo from tipo_comprobante where id_tipo_comprobante=5");
    while ($row = pg_fetch_row($consulta_cod_docu)) {
        $codDoc = $row[0]; //normal cuando generamos la clave
    }
    $consulta_empresa = pg_query("select ruc_empresa,clave, token from empresa where id_empresa = 1");
    while ($row = pg_fetch_row($consulta_empresa)) {
        $ruc = $row[0];
        $pass = $row[1];
        $token = $row[2];
    }

    $result = generarXMLRET($_POST['id'], $codDoc, $ambiente, $emision);
        print_r($result);
    $doc = new DOMDocument('1.0', 'UTF-8');
    $doc->loadXML($result); // xml 
    $doc->save($pathXmls . "fac" . '.xml');
    //exec("$appFirma " . '../../xmls/'.$esquema.'/fac', $resultado);
    exec("$appFirma " . $pathXmls . '/fac "' . $pathARchivoP12 . '" "' . $claveFirma . '"', $resultado);
    $respuesta = consultarComprobante($ambiente, $consult_clave);

        print_r($respuesta);
    if (isset($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado)) {
        if ($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado == 'AUTORIZADO') {
            $numeroAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion;
            $fechaAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;
            $ambienteAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente;

            $data = 2;
            pg_query("UPDATE retencion_fuente_factura_compra SET fecha = '" . $fechaAutorizacion . "',  estado = '2', num_autorizacion = '" . $numeroAutorizacion . "' WHERE id_retencion_fuente_factura_compra = '" . $_POST['id'] . "'");
            $dataFile = generarXMLCDATAFAC($respuesta);
            $doc = new DOMDocument('1.0', 'UTF-8');
            $doc->loadXML($dataFile); // xml  
            $doc->save($pathXmls . $numeroAutorizacion . '.xml');
        } else {
            $data = 7;
            pg_query("UPDATE retencion_fuente_factura_compra SET estado = '7' where id_retencion_fuente_factura_compra = '" . $_POST['id'] . "'"); // NO AUTORIZADO
        }
    }

    $itemuno = array(
        'estado' => $data,
        'id' => $_POST["id_factura"]
    );
    exit();
}
if ($data != 2) {

    $consulta_empresa = pg_query("select ruc_empresa,clave, token from empresa where id_empresa = 1");
    while ($row = pg_fetch_row($consulta_empresa)) {
        $ruc = $row[0];
    }
    $consulta_cod_docu = pg_query("select codigo from tipo_comprobante where id_tipo_comprobante = 5");
    while ($row = pg_fetch_row($consulta_cod_docu)) {
        $codDoc = $row[0];
    }
    $consulta_ambiente = pg_query("select codigo_ambi from ambiente where estado_ambi = 'Activo' ");
    while ($row = pg_fetch_row($consulta_ambiente)) {
        $ambiente = $row[0];
    }
    $consulta_emision = pg_query("select codigo_temision from tipo_emision order by codigo_temision asc  limit 1");
    while ($row = pg_fetch_row($consulta_emision)) {
        $emision = $row[0];
    }

    $consulta_bienes = pg_query("select SUM(total_compra::float) from detalle_factura_compra where id_factura_compra =$_POST[id_factura] and  bien_servicio='B'");
    while ($row = pg_fetch_row($consulta_bienes)) {
        $valor_totalBienes = $row[0];
    }

    if ($_POST["id_retencion_fuente"] == 0) {
        $_POST["id_retencion_fuente"] = 1;
    }


    for ($i = 0; $i <= $nelemreten; $i++) {



        if ($arreglo1reten[$i] != '') {
            $validreten = $_POST['id_retencion_fuente'];

            if ($arreglo2reten[$i] == 'IVA' || $arreglo2reten[$i] == 'IVA SERVICIOS') {
                $arreglo2reten[$i] = 2;
            } else {
                $arreglo2reten[$i] = 1;
            }
            $cont1 = 0;
            $consulta = pg_query("select max(id_retencion_fuente_factura_compra) from retencion_fuente_factura_compra");
            while ($row = pg_fetch_row($consulta)) {
                $cont1 = $row[0];
            }
            $cont1++;
            $contre = 0;
            $consultare = pg_query("select max(id_detalles_compro_reten) from detallecomprobanteretencion");
            while ($row1 = pg_fetch_row($consultare)) {
                $contre = $row1[0];
            }
            $contre++;

            pg_query("insert into retencion_fuente_factura_compra values('" . $cont1 . "', '$_POST[id_factura]', '$arreglo6reten[$i]','$_POST[fecha_retencion]','" . $hora . "','$arreglo1reten[$i]','$_POST[iva_factura]','$arreglo4reten[$i]', '$conpuntoresult', '$_POST[serie_retencion]','','1','','2','','Activo')");
            pg_query("insert into detallecomprobanteretencion values('$contre','$cont1' ,'$arreglo6reten[$i]','$arreglo1reten[$i]','$arreglo2reten[$i]','$arreglo3reten[$i]','$arreglo4reten[$i]')");
            pg_query("update retencion_fuente_factura_compra set valor_retencion='$arreglo4reten[$i]'  where id_factura='$cont1' and id_retencion_fuente='$arreglo6reten[$i]' and valor_compra='$arreglo1reten[$i]' ");
        }

        if ($_POST['valor_seleccion_si_no'] == 1) {
            $consulta_servicio = pg_query("select SUM(total_compra::float) from detalle_factura_compra where id_factura_compra =$_POST[id_factura] and  bien_servicio='S'");
            while ($row = pg_fetch_row($consulta_servicio)) {
                $valor_totalServicio = $row[0];
            }
        }
    }
    ///////////////////////////////////////////
    ////////////////UPDATE COBROS
    $valfac = pg_query("SELECT  monto_credito FROM pagos_compra where  estado='Activo' and id_factura_compra='$_POST[id_factura]'");
    $valfacresult = pg_fetch_row($valfac);

    if (isFacturaCredito($_POST["id_factura"])) {
        guardarPagoP($_POST["id_factura"], "RETENCION", "INTERNA", $_POST['total_reten_iva'], "RETENCION", "", $_POST["fecha_retencion"]);
    }


    //    print_r($valfacresult);

    //    $valfac_reten = pg_query("select  sum(dcr.valor_retenido)
    //            FROM retencion_fuente_factura_compra rf, retencion_fuentes f, detallecomprobanteretencion dcr
    //            WHERE rf.id_factura='$_POST[id_factura]' and rf.id_retencion_fuente=f.id_retencion_fuentes
    //            AND  dcr.id_retencion_fuente_factura_compra=rf.id_retencion_fuente_factura_compra ");
    //    $valfacresult_reten = pg_fetch_row($valfac_reten);

    // SI FORMA PAGO ES CREDITO INSERT PAGOS COMPRA Y INSERT PAGOS_PAGAR
    //    $resultreten = $valfacresult[0] - $_POST['total_reten_iva'];
    //    if (isFacturaCreditoc($_POST["id_factura"])) {
    //        guardarPagoPp($_POST["id_factura"], "RETENCION", "INTERNA", $_POST['total_reten_iva'], "RETENCION", "", $_POST["fecha_retencion"]);
    //    }
    //    
    //      // SI FORMA PAGO ES CONTADO INSERT PAGOS VENTA
    //    if (isFacturaContadoc($_POST["id_factura"])) {
    //        guardarPagoventaPp($_POST["id_factura"], "RETENCION", "INTERNA", $_POST['total_reten_iva'], "RETENCION", "", $_POST["fecha_retencion"]);
    //    }


    //    pg_query("UPDATE retencion_fuente_factura_venta set clave='" . $clave . "' where id_factura=$_POST[id_factura] and id_gastos=1");
    //    echo '<br>GUARDAR FACTURA VENTAttt: <br>' . "update pagos_venta set monto_credito='" . $resultreten . "' , saldo='" . $resultreten . "' where id_factura_venta='$_POST[id_factura]'"; //////////////////////////
    //    pg_query("update pagos_compra set monto_credito='" . $resultreten . "' , saldo='" . $resultreten . "' where id_factura_compra='$_POST[id_factura]'");
    ////////////////UPDATE COBROS
    ///////////////////////////////////
    /////guardar en tabla auxiliar
    $arrbs = array();
    $objb = new stdClass();
    $objs = new stdClass();
    foreach ($arreglo7reten as $key => $item) {
        if ($item == 'b') {
            if ($arreglo2reten[$key] == 1) {
                $objb->retencionF = $arreglo6reten[$key];
                $objb->baseF = $arreglo1reten[$key];
                $objb->porcentajeF = $arreglo3reten[$key];
                $objb->valorRetenidoF = $arreglo4reten[$key];
            } elseif ($arreglo2reten[$key] == 2) {
                $objb->retencionI = $arreglo6reten[$key];
                $objb->baseI = $arreglo1reten[$key];
                $objb->porcentajeI = $arreglo3reten[$key];
                $objb->valorRetenidoI = $arreglo4reten[$key];
            }
            $objb->tipo_ret = 'b';
        }
        if ($item == 's') {
            if ($arreglo2reten[$key] == 1) {
                $objs->retencionF = $arreglo6reten[$key];
                $objs->baseF = $arreglo1reten[$key];
                $objs->porcentajeF = $arreglo3reten[$key];
                $objs->valorRetenidoF = $arreglo4reten[$key];
            } elseif ($arreglo2reten[$key] == 2) {
                $objs->retencionI = $arreglo6reten[$key];
                $objs->baseI = $arreglo1reten[$key];
                $objs->porcentajeI = $arreglo3reten[$key];
                $objs->valorRetenidoI = $arreglo4reten[$key];
            }
            $objs->tipo_ret = 's';
        }
    }
    if (!empty(((array) $objb))) {
        array_push($arrbs, $objb);
    }
    if (!empty(((array) $objs))) {
        array_push($arrbs, $objs);
    }
    foreach ($arrbs as $key => $item) {
        $contafr = 0;
        $consulta = pg_query("select max(id_factura_retencion) from aux_factura_retencion");
        while ($row = pg_fetch_row($consulta)) {
            $contafr = $row[0];
        }
        $contafr++;
        $sql = "INSERT INTO aux_factura_retencion(
    id_factura_retencion, 
    id_factura, 
    id_retencion_fuente, 
    base_imponible_fuente, 
    porcentaje_fuente, 
    id_retencion_iva, 
    base_imponible_iva, 
    porcentaje_iva, 
    valor_retenido_fuente, 
    valor_retenido_iva,
    tipo_ret)
    VALUES (
    $contafr, 
    $_POST[id_factura],
    " . ($item->retencionF == NULL ? "NULL" : $item->retencionF) . ", 
    " . ($item->baseF == NULL ? "NULL" : $item->baseF) . ", 
    " . ($item->porcentajeF == NULL ? "NULL" : $item->porcentajeF) . ", 
    " . ($item->retencionI == NULL ? "NULL" : $item->retencionI) . ", 
    " . ($item->baseI == NULL ? "NULL" : $item->baseI) . ", 
    " . ($item->porcentajeI == NULL ? "NULL" : $item->porcentajeI) . ", 
    " . ($item->valorRetenidoF == NULL ? "NULL" : $item->valorRetenidoF) . ", 
    " . ($item->valorRetenidoI == NULL ? "NULL" : $item->valorRetenidoI) . ", 
    '" . ($item->tipo_ret == NULL ? "NULL" : $item->tipo_ret) . "'
    );
    ";
        //        echo '<BR>INSER AUX<BR>';
        //       echo $sql.'<BR>';
        $consultaafr = pg_query($sql);
    }




    $consulta_num_factura = pg_query("select num_serie from retencion_fuente_factura_compra where id_factura='$_POST[id_factura]' and id_gastos=1 ");
    while ($row = pg_fetch_row($consulta_num_factura)) {
        $num_serie_fac = $row[0];
    }
    $num_fecha_emision = '';
    $consulta_fecha_emision = pg_query("select fecha_emision from factura_compra where id_factura_compra ='$_POST[id_factura]'");
    while ($row = pg_fetch_row($consulta_fecha_emision)) {
        $num_fecha_emision = $row[0];
    }
    //$fecha_actual=$_POST['fecha_actual'];
    $fechasepar = explode("-", $num_fecha_emision);
    //    $fechasepar = explode("-", $fecha);
    $valorfecha = "$fechasepar[2]" . "$fechasepar[1]" . "$fechasepar[0]";
    $periodo_fiscal = "$mes" . "$anio";
    $ip = $num_serie_fac;
    $iparr = explode("-", $ip);
    $secuencialresult = $iparr[2];
    $secuencialmitad = $iparr[1];
    $secuencialinicial = $iparr[0];
    $valorcodDoc = $codDoc;
    $valortruc = $ruc;
    $valorambiente = $ambiente;
    $secuencialmitad = $iparr[1];
    $secuencialinicial = $iparr[0];
    $valortxt81 = $secuencialinicial;
    $valorsiete = $secuencialmitad;
    $valorsecuencial = $secuencialresult;
    $valoremision = $emision;
    $clave = generarClave($valorfecha, $valorcodDoc, $valortruc, $valorambiente, $valortxt81, $valorsiete . '' . $valorsecuencial, $valorfecha, $valoremision);


    $valreten = $_POST['valor_retencion'];

    $valfac = pg_query("select * from factura_compra where id_factura_compra ='$_POST[id_factura]'");
    $valfacresult = pg_fetch_row($valfac);
    $resultreten = $valfacresult[19] - $valreten;

    pg_query("UPDATE retencion_fuente_factura_compra set clave='" . $clave . "' where id_factura=$_POST[id_factura] and id_gastos=1");

    //    pg_query("update pagos_compra set monto_credito='" . $resultreten . "' , saldo='" . $resultreten . "' where id_factura_compra='$_POST[id_factura]'");
    ////////FACTURACION ELECTRONICA
    $consulta_empresa = pg_query("select ruc_empresa,clave, token from empresa where id_empresa = 1");
    while ($row = pg_fetch_row($consulta_empresa)) {
        $ruc = $row[0];
        $pass = $row[1];
        $token = $row[2];
    }

    $result = generarXMLRET($cont1, $codDoc, $ambiente, $emision);
    $doc = new DOMDocument('1.0', 'UTF-8');
    $doc->loadXML($result); // xml 
    $doc->save($pathXmls . "fac" . '.xml');
    //exec("$appFirma " . '../../xmls/'.$esquema.'/fac', $resultado);
    exec("$appFirma " . $pathXmls . '/fac "' . $pathARchivoP12 . '" "' . $claveFirma . '"', $resultado);
    $respuesta = consultarComprobante($ambiente, $clave);

    if (isset($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado)) {
        if ($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado == 'AUTORIZADO') {
            $numeroAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion;
            $fechaAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;
            $ambienteAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente;
            $data = 2;
            pg_query("UPDATE retencion_fuente_factura_compra SET fecha = '" . $fechaAutorizacion . "', estado = '2', num_autorizacion = '" . $numeroAutorizacion . "' WHERE id_retencion_fuente_factura_compra = '$cont1'");
            $dataFile = generarXMLCDATAFAC($respuesta);
            $doc = new DOMDocument('1.0', 'UTF-8');
            $doc->loadXML($dataFile); // xml     
            $doc->save($pathXmls . $numeroAutorizacion . '.xml');
            $data = 2;
        } else {
            $data = 7;
            pg_query("UPDATE retencion_fuente_factura_compra SET estado = '7' where id_retencion_fuente_factura_compra = '$cont1'"); // NO AUTORIZADO
        }
    }

    $item = array(
        'estado' => $data,
        'id' => $_POST["id_factura"], 'id_reten' => $cont1
    );

    ///////////////////////
    ////// ASIENTO CONTABLE
    // pg_query("update detalle_transaccion set credito='".$caja."' where id_detalle_transaccion='".$s[1]."'");
    //    $tran = pg_query("select * from transacciones where comprobante='$_POST[id_factura]' and id_tipo_transaccion='1' and concepto like 'COM%' and id_empresa= $conpuntoresult");
    //    $fila = pg_fetch_row($tran);
    $idtran = pg_query("select max(id_transacciones) from transacciones");
    $fila = pg_fetch_row($idtran);
    $fila[0] = $fila[0] + 1;
    $consf = pg_query("select dcr.valor_retenido,dcr.id_retencion_fuentes from detallecomprobanteretencion dcr, retencion_fuente_factura_compra rff ,factura_compra fc where 
    dcr.id_retencion_fuente_factura_compra=rff.id_retencion_fuente_factura_compra
    and rff.id_factura=fc.id_factura_compra and fc.id_factura_compra=$_POST[id_factura] and rff.id_gastos=1 and  dcr.id_trete=1");



    $consf1 = pg_query("select sum(dcr.valor_retenido)from detallecomprobanteretencion dcr, retencion_fuente_factura_compra rff ,factura_compra fc where 
    dcr.id_retencion_fuente_factura_compra=rff.id_retencion_fuente_factura_compra
    and rff.id_factura=fc.id_factura_compra and fc.id_factura_compra=$_POST[id_factura]
    ");
    $sum_retencion = pg_fetch_row($consf1);



    $xr = 0;

    while ($cont2f = pg_fetch_row($consf)) {
        $cons = pg_query("select cuenta_credito from retencion_fuentes where id_retencion_fuentes='" . $cont2f[1] . "'");

        while ($cont2 = pg_fetch_row($cons)) {
            $fila1 = 0;
            $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
            $fila1 = pg_fetch_row($iddettran);

            /////////NUEVO CODIGO//////////////////
            $sub = $_POST['sub'];
            $id_proveedor = $_POST['id_proveedor'];

            $ing = pg_query("select max(num_transaccion) from transacciones where id_tipo_transaccion='1' and id_empresa= '$_SESSION[PV]'");
            $res = pg_fetch_row($ing);
            $ing_pv = pg_query("select max(id_transaccion_pv::int) from transacciones where  id_empresa= '$_SESSION[PV]'");
            $res_pv = pg_fetch_row($ing_pv);

            $id_proveedor = $_POST['id_proveedor'];

            $prove = pg_query("select identificacion_pro from proveedores where id_proveedor='$id_proveedor'");
            $p = pg_fetch_row($prove);

            //                         echo '<br>GUARDAR FACTURA transacciones1: <br>' . "insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'RETENCION EN COMPRA PRODUCTOS, PROVEEDOR:" . $p[0] . " , COMPROBANTE: " . $_POST['num_factura'] . "', '" . $sum_retencion[0] . "', '" . $sum_retencion[0] . "', '0.00','1','" . ($res[0] + 1) . "','Activo','$id_proveedor','','','','','COM','',$conpuntoresult,'$_POST[fecha_retencion]','" . ($res_pv[0] + 1) . "')"."</br>";
            //            	 

            $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'RETENCION EN COMPRA PRODUCTOS, PROVEEDOR:" . $p[0] . " , COMPROBANTE: " . $_POST['num_factura'] . "', '" . $sum_retencion[0] . "', '" . $sum_retencion[0] . "', '0.00','1','" . ($res[0] + 1) . "','Activo','$id_proveedor','','','','','COM','',$conpuntoresult,'$_POST[fecha_retencion]','" . ($res_pv[0] + 1) . "')");



            $fila1[0] = $fila1[0] + 1;
            $plancliente = pg_query("select cuenta_debito from parametros where descripcion='CUENTAS' ");
            $fila2 = pg_fetch_row($plancliente);

            //   echo 'dt1'."insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$_POST[idCuenta_reten]','" . $sum_retencion[0] . "','0.000','Activo')"."</br>";

            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$_POST[idCuenta_reten]','" . $sum_retencion[0] . "','0.000','Activo')");
            $fila1[0] = $fila1[0] + 1;

            /////////NUEVO CODIGO//////////////////
            //   echo 'dt2'."insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $cont2[0] . "','0.000','$cont2f[0]','Activo')"."</br>";


            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $cont2[0] . "','0.000','$cont2f[0]','Activo')");

            $xr = $xr + $cont2f[0];
        }
    }




    //////////RETIENE IVA//////////
    $data_iva = 0;
    $comprobar = pg_query("select id_factura from retencion_iva_factura_compra  where id_gastos='1'");
    while ($row2 = pg_fetch_row($comprobar)) {
        if ($row2[0] == $_POST["id_factura"]) {
            $data_iva = 2;
        }
    }
    //////////RETIENE IVA//////////
    if ($data_iva != 2) {




        $cont2 = 0;
        $consultaiva = pg_query("select max(id_retencion_iva_factura_compra) from retencion_iva_factura_compra");
        while ($row = pg_fetch_row($consultaiva)) {
            $cont2 = $row[0];
        }
        $cont2++;

        $validporcentiva = $_POST['porcent_iva'];
        //         echo "insert into retencion_iva_factura_compra values('" . $cont2 . "', '$_POST[id_factura]', '$_POST[id_retencion_iva]','" . $fecha . "','" . $hora . "','$_POST[valor_facturaiva]','$_POST[iva_factura]','$_POST[valor_retencioni]', '$_POST[autorizacion_ret]','$_POST[serie_retencion]','Activo','1')".'<br>';
        // echo "insert into retencion_iva_factura_compra values('" . $cont2 . "', '$_POST[id_factura]', '$_POST[id_retencion_iva]','" . $fecha . "','" . $hora . "','$_POST[valor_facturaiva]','$_POST[iva_factura]','$_POST[valor_retencioni]', '$_POST[autorizacion_ret]','$_POST[serie_retencion]','Activo','1')".'<BR>';

        pg_query("insert into retencion_iva_factura_compra values('" . $cont2 . "', '$_POST[id_factura]', '$_POST[id_retencion_iva]','" . $fecha . "','" . $hora . "','$_POST[valor_facturaiva]','$_POST[iva_factura]','$_POST[valor_retencioni]', '$_POST[autorizacion_ret]','$_POST[serie_retencion]','Activo','1')");

        ////////////////////////////////
        ////////////////ASIENTO CONTABLE



        $consf = pg_query("select dcr.valor_retenido,dcr.id_retencion_fuentes from detallecomprobanteretencion dcr, retencion_fuente_factura_compra rff ,factura_compra fc where 
    dcr.id_retencion_fuente_factura_compra=rff.id_retencion_fuente_factura_compra
    and rff.id_factura=fc.id_factura_compra and fc.id_factura_compra=$_POST[id_factura] and rff.id_gastos=1 and  dcr.id_trete=2");
        $xi = 0;

        while ($cont2f = pg_fetch_row($consf)) {
            $cons = pg_query("select cuenta_credito from retencion_iva where id_retencion_iva='" . $cont2f[1] . "'");

            while ($cont2 = pg_fetch_row($cons)) {
                $fila1 = 0;
                $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
                $fila1 = pg_fetch_row($iddettran);
                $sub = $_POST['sub'];
                $cliente1 = $_POST['id_proveedor'];
                $ing = pg_query("select max(num_transaccion) from transacciones where id_tipo_transaccion='1'");
                $res = pg_fetch_row($ing);
                $ing_pv = pg_query("select max(id_transaccion_pv::int) from transacciones where  id_empresa= '$_SESSION[PV]'");
                $res_pv = pg_fetch_row($ing_pv);

                $fila1[0] = $fila1[0] + 1;
                //echo 'dt2'. "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $cont2[0] . "','0.000','$cont2f[0]','Activo')"."</br>";

                pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $cont2[0] . "','0.000','$cont2f[0]','Activo')");

                $xi = $xi + $cont2f[0];
            }
        }

        // 70     --> Retención IVA 30%
        // 75     --> Retención Fuente 1% Transporte
        // 74     --> Retención Fuente 1% Compras
        // 78     --> Retención Fuente 10% Honorarios
        // 77     --> Retención Fuente 8% Arriendos
        // 211    --> DESCUENTOS COMPRAS
        // 28     --> IVA
        // 23     --> Inventario Materia Prima
        //23,24,25,216,217 Excluye retenciones 

        $sql = pg_query(
            "select id_plan_cuentas from detalle_transaccion where id_transacciones='" . $fila[0] . "' "
                . "and id_plan_cuentas<>'53'" //"Retenciones IVA Proveedores"
                . "and id_plan_cuentas<>'55'" //"Retenciones en la Fuente Proveedores"
                . "and id_plan_cuentas<>'58'" //"Retenciones en la Fuente Empleados"
                . "and id_plan_cuentas<>'154'" //"Retenciones en la Fuente Socios"
                . "and id_plan_cuentas<>'164'" //"Retenciones en la Fuente Otros"
                . "and id_plan_cuentas<>'165'" //"Impuesto a la Renta por Pagar"
                . "and id_plan_cuentas<>'166'" //"Retención Fuente 2.75% Servicios"
                . "and id_plan_cuentas<>'167'" //"Retención Fuente 8% Arriendos"
                . "and id_plan_cuentas<>'168'" //"Retención Fuente 10% Honorarios"
                . "and id_plan_cuentas<>'169'" //"Iva en Compras"
                . "and id_plan_cuentas<>'170'" //"Inventario Materia Prima"
                . "and id_plan_cuentas<>'171'" //"Inventario Productos en Proceso"
                . "and id_plan_cuentas<>'172'" //"Inventario Productos Terminados"
                . "and id_plan_cuentas<>'173'" //"Inventario 12%"
                . "and id_plan_cuentas<>'174'" //"Inventario 0%"
                . "and id_plan_cuentas<>'175'" //"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'176'" //"Retención Fuente 10% Honorarios"
                . "and id_plan_cuentas<>'177'" //"Iva en Compras"
                . "and id_plan_cuentas<>'178'" //"Inventario Materia Prima"
                . "and id_plan_cuentas<>'179'" //"Inventario Productos en Proceso"
                . "and id_plan_cuentas<>'180'" //"Inventario Productos Terminados"
                . "and id_plan_cuentas<>'181'" //"Inventario 12%"
                . "and id_plan_cuentas<>'182'" //"Inventario 0%"
                . "and id_plan_cuentas<>'183'" //"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'184'" //"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'556'" //"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'52'" //"Retención Fuente 10% Honorarios"
                . "and id_plan_cuentas<>'53'" //"Iva en Compras"
                . "and id_plan_cuentas<>'63'" //"Inventario Materia Prima"
                . "and id_plan_cuentas<>'395'" //"Inventario Productos en Proceso"
                . "and id_plan_cuentas<>'470'" //"Inventario Productos Terminados"
                . "and id_plan_cuentas<>'474'" //"Inventario 12%"
                . "and id_plan_cuentas<>'475'" //"Inventario 0%"
                . "and id_plan_cuentas<>'556'" //"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'593'" //"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'614'" //"Suministros y Materiales Agrícolas"
        );
        $idPlan = pg_fetch_row($sql);
        $plancaja = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
        $caja = pg_fetch_row($plancaja);

        $tot = pg_query("select credito, id_detalle_transaccion from detalle_transaccion where id_transacciones='" . $fila[0] . "' and id_plan_cuentas='" . $caja[0] . "'");
        $s = pg_fetch_row($tot);
        $caja = $s[0] - $xi;
        if ($forma != "otros") {
            //            pg_query("update detalle_transaccion set credito='" . $caja . "' where id_detalle_transaccion='" . $s[1] . "'");
        }

        ////////////////////////////////////////////
        /////////////////////////////////////////   



        $data = 1;
        $validiva = $_POST['id_retencion_iva'];
        $valfaciva = $_POST["iva_factura"];
        $valiva = $_POST['valor_retencioni'];
    }




    // 70     --> Retención IVA 30%
    // 75     --> Retención Fuente 1% Transporte
    // 74     --> Retención Fuente 1% Compras
    // 78     --> Retención Fuente 10% Honorarios
    // 77     --> Retención Fuente 8% Arriendos
    // 211    --> DESCUENTOS COMPRAS
    // 28     --> IVA
    // 23     --> Inventario Materia Prima
    //23,24,25,216,217 Excluye retenciones 

    $sql = pg_query(
        "select id_plan_cuentas from detalle_transaccion where id_transacciones='" . $fila[0] . "' "
            . "and id_plan_cuentas<>'53'" //"Retenciones IVA Proveedores"
            . "and id_plan_cuentas<>'55'" //"Retenciones en la Fuente Proveedores"
            . "and id_plan_cuentas<>'58'" //"Retenciones en la Fuente Empleados"
            . "and id_plan_cuentas<>'154'" //"Retenciones en la Fuente Socios"
            . "and id_plan_cuentas<>'164'" //"Retenciones en la Fuente Otros"
            . "and id_plan_cuentas<>'165'" //"Impuesto a la Renta por Pagar"
            . "and id_plan_cuentas<>'166'" //"Retención Fuente 2.75% Servicios"
            . "and id_plan_cuentas<>'167'" //"Retención Fuente 8% Arriendos"
            . "and id_plan_cuentas<>'168'" //"Retención Fuente 10% Honorarios"
            . "and id_plan_cuentas<>'169'" //"Iva en Compras"
            . "and id_plan_cuentas<>'170'" //"Inventario Materia Prima"
            . "and id_plan_cuentas<>'171'" //"Inventario Productos en Proceso"
            . "and id_plan_cuentas<>'172'" //"Inventario Productos Terminados"
            . "and id_plan_cuentas<>'173'" //"Inventario 12%"
            . "and id_plan_cuentas<>'174'" //"Inventario 0%"
            . "and id_plan_cuentas<>'175'" //"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'176'" //"Retención Fuente 10% Honorarios"
            . "and id_plan_cuentas<>'177'" //"Iva en Compras"
            . "and id_plan_cuentas<>'178'" //"Inventario Materia Prima"
            . "and id_plan_cuentas<>'179'" //"Inventario Productos en Proceso"
            . "and id_plan_cuentas<>'180'" //"Inventario Productos Terminados"
            . "and id_plan_cuentas<>'181'" //"Inventario 12%"
            . "and id_plan_cuentas<>'182'" //"Inventario 0%"
            . "and id_plan_cuentas<>'183'" //"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'184'" //"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'556'" //"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'52'" //"Retención Fuente 10% Honorarios"
            . "and id_plan_cuentas<>'53'" //"Iva en Compras"
            . "and id_plan_cuentas<>'63'" //"Inventario Materia Prima"
            . "and id_plan_cuentas<>'395'" //"Inventario Productos en Proceso"
            . "and id_plan_cuentas<>'470'" //"Inventario Productos Terminados"
            . "and id_plan_cuentas<>'474'" //"Inventario 12%"
            . "and id_plan_cuentas<>'475'" //"Inventario 0%"
            . "and id_plan_cuentas<>'556'" //"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'593'" //"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'614'" //"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'311'" //"Inventario 12%"
            . "and id_plan_cuentas<>'326'" //"Inventario 0%"
            . "and id_plan_cuentas<>'327'" //"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'423'" //"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'458'" //"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'512'" //"Inventario 12%"
            . "and id_plan_cuentas<>'569'" //"Inventario 0%"
            . "and id_plan_cuentas<>'586'" //"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'612'" //"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'624'" //"Suministros y Materiales Agrícolas"
    );
    $idPlan = pg_fetch_row($sql);
    $plancaja = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
    $caja = pg_fetch_row($plancaja);
    $tot = pg_query("select credito, id_detalle_transaccion from detalle_transaccion where id_transacciones='" . $fila[0] . "' and id_plan_cuentas='" . $caja[0] . "'");
    $s = pg_fetch_row($tot);
    $caja = $s[0] - $xr;

    ///////////////////////////////////////
    ////////////////////////////////////
}
//print_r($_POST[valor_seleccion_iva]);
//francis2/12/2022
if ($datosimprimir == 1) {
    echo $data = json_encode($itemuno);
} else {
    echo $data = json_encode($item);
}
