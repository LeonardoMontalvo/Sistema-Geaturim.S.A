<?php
session_start();
include 'base.php';
include '../firma/firma.php';
include '../firma/xades.php';
include '../reportes/fact_elect_xml.php';
require_once __DIR__ . '/../procesos/configuracion.php';
include 'funciones.php';

//include 'generarPDF.php';
//include_once 'kardexValorizado.php';
//require_once 'procesos/detalleProductosBodega.php';


//include '../reportes/fact_guia_xml.php';

//include '../admin/correo.php';

// Auditoria
//require_once '../../procesos/auditoria.php';

date_default_timezone_set('America/Guayaquil');

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
    try {
        $respuesta = consultarComprobante($ambiente, $clave);
    } catch (Exception $e) {
        $data = -1000;
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

//echo json_encode(autorizarFactura($_POST["clave_acceso"], $_POST["id_factura"]));
