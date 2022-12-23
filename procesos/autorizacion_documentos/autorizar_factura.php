<?php
include __DIR__ . '/../base.php';
include __DIR__ . '/../../firma/firma.php';
include __DIR__ . '/../../firma/xades.php';
include __DIR__ . "/../../reportes/fact_elect_xml.php";
require_once __DIR__ . '/../configuracion.php';
include __DIR__ . "/../funciones.php";

include __DIR__ . '/../../admin/correo.php';
include __DIR__ . '/generarPDF.php';

include_once __DIR__ . "/../guardar_logs.php";


$conexion = conectarse();

$conf = new Configuracion();
$esquema = $conf->getNombreEsquema();
$appFirma = $conf->getPathAplicacionFIrma("app_firma");
$pathXmls = $conf->getPathXmlsFirma();
$pathARchivoP12 = $conf->getArchivoP12();
$claveFirma = $conf->getParametroEmpresa("clave_firma");


date_default_timezone_set('America/Guayaquil');

function autorizarFactura($idfactura, $clave)
{
    global $conexion, $appFirma, $pathXmls, $pathARchivoP12, $claveFirma;
    $logfile = __DIR__ . "/../../logs/autorizar_factura.log";
    /////FACTURA ELECTRONICA////
    ///1 GUARDADO
    ///2 GENERADO
    ///3 AUTORIZADO
    ///4 RECHAZADO

    $consulta_factura = pg_query($conexion, "select id_empresa from factura_venta where id_factura_venta = '$idfactura' ");
    while ($row = pg_fetch_row($consulta_factura)) {
        $idempresa = $row[0];
    }

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
    $consulta_empresa = pg_query($conexion, "select ruc_empresa,clave, token from empresa where id_empresa = $idempresa");
    while ($row = pg_fetch_row($consulta_empresa)) {
        $ruc = $row[0];
        $pass = $row[1];
        $token = $row[2];
    }

    $result = generarXML($idfactura, $codDoc, $ambiente, $emision);

    $doc = new DOMDocument('1.0', 'UTF-8');
    $doc->loadXML($result); // xml 
    $doc->save($pathXmls . "fac" . '.xml');
    exec("$appFirma " . $pathXmls . '/fac "' . $pathARchivoP12 . '" "' . $claveFirma . '"', $resultado);
    try {
        $respuesta = consultarComprobante($ambiente, $clave);
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
                enviarCorreo($idfactura);
            } else {
                $respuestaaut = $respuesta->RespuestaAutorizacionComprobante;
                $estaoda = $respuestaaut->autorizaciones->autorizacion->estado;
                $mensaje = $respuestaaut->autorizaciones->autorizacion->mensajes->mensaje->mensaje;
                $infoadicional = $respuestaaut->autorizaciones->autorizacion->mensajes->mensaje->informacionAdicional;
                $ident = $respuestaaut->autorizaciones->autorizacion->mensajes->mensaje->identificador;
                $tipo = $respuestaaut->autorizaciones->autorizacion->mensajes->mensaje->tipo;

                $msg = "\n$estaoda - ID_FACTURA:$idfactura \n$ident - $tipo - $mensaje - $infoadicional";

                sis_error_log_file("--", $msg, __FILE__, "--", $logfile);

                $data = 7;
                pg_query($conexion, "UPDATE factura_venta SET estado_fac = '7' where id_factura_venta = '$idfactura'"); // NO AUTORIZADO
            }
        }
    } catch (Exception $e) {
        sis_error_log_file("--", $e->getMessage(), __FILE__, "--", $logfile);
    }

    $item = array(
        'estado' => $data,
        'id' => $idfactura
    );
    return $item;
}

function enviarCorreo($idfactura)
{
    global $conexion, $pathXmls;
    $resultado = pg_query($conexion, "SELECT C.correo, C.nombres_cli, F.total_venta ,F.num_autorizacion, F.fecha_actual  FROM factura_venta F, clientes C "
        . "WHERE F.id_cliente = C.id_cliente AND F.id_factura_venta= '" . $idfactura . "'");
    while ($row = pg_fetch_row($resultado)) {
        $email = $row[0];
        $nombre = $row[1];
        $total = $row[2];
        $num_autorizacion = $row[3];
        $fecha = $row[4];
    }
    $total_venta_tot = 0;
    $total_venta_tot = round($total, 2);
    $data = correo($fecha, $total_venta_tot, $pathXmls . $num_autorizacion . '.xml', $num_autorizacion . '.pdf', $nombre, $email, $pathXmls . $num_autorizacion . '.xml', generarPDFcorreo($idfactura), 1);

    if ($data == 1) {
        $resultado = pg_query($conexion, "UPDATE factura_venta set estado_fac = '1' where id_factura_venta = '" . $idfactura . "'");
        if ($resultado) {
            $data = 1; // datos actualizados
        } else {
            $data = 4; // error al momento de guadar
        }
    }
    /* $item = array(
        'estado' => $data,
        'id' => $_POST['id']
    ); */
}

autorizarFactura($_POST["id_factura"], $_POST["clave_acceso"]);
