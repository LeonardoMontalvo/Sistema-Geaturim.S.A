<?php

session_start();
include '../../procesos/base.php';

include '../../reportes/fact_guia_xml.php';
include '../../firma/firma.php';
include '../../firma/xades.php';
include '../../admin/correo.php';
include '../../procesos/funciones.php';
include __DIR__ . "./../../procesos/autorizacion_documentos/generarPDF.php";


include_once '../../procesos/kardexValorizado.php';
require_once '../../procesos/detalleProductosBodega.php';
require_once __DIR__ . '/../../procesos/configuracion.php';
//require_once '../centro_costos/guardar_detalles.php';

$conexion = conectarse();

date_default_timezone_set('America/Guayaquil');

$fecha_time = date('Y-m-d', time());
$conf = new Configuracion();
$esquema = $conf->getNombreEsquema();
$appFirma = $conf->getPathAplicacionFIrma("app_firma");
$pathXmls = $conf->getPathXmlsFirma();
$pathARchivoP12 = $conf->getArchivoP12();
$claveFirma = $conf->getParametroEmpresa("clave_firma");

$pv = $_SESSION["PV"];

function error_log_fv($errno, $errstr, $errfile, $errline) {
    $ddf = fopen('../../error.log', 'a');
    $errfile = explode('/', $errfile);
    $errfile = $errfile[count($errfile) - 1];
    fwrite($ddf, "[" . date("r") . "] Error $errno-$errfile-$errline: $errstr\r\n");
    fclose($ddf);
}

//error_reporting(0);
$defaultMail = "jpantojarevelo@gmail.com";
$cont1 = 0;
$datos = 0;
$guardado = 0;
$descuento = $_POST["descuento"];
$costoVenta = 0;
$costoVenta1 = 0;
$inventario0 = 0;
$inventario12 = 0;
$_POST['marca_vehiculo'] = "";
$sumaSubtotalTarifa12 = 0;
$codplanTarifa12 = 0;
$contTarifa12 = 0;
$sumaSubtotalTarifa0 = 0;
$codplanTarifa0 = 0;
$contTarifa0 = 0;
$bien_serviciob = 0;
$cont2_mixto = '';
$valor_contado = 0;
if (isset($_POST['actualizar_clave_acceso']) == "actualizar_clave_acceso") {


    $consulta_emision = pg_query("select codigo_temision from tipo_emision where estado_temision='Activo'");
    while ($row = pg_fetch_row($consulta_emision)) {
        $emision = $row[0]; //normal cuando generamos la clave
    }
    $num_serie_fac = "";
    $fecha_actuall = "";
    $consulta_num_factura = pg_query("select num_serie,num_serie,fecha_actual from guia_remision where id_guia_remision='" . $_POST['id'] . "'  ");
    while ($row = pg_fetch_row($consulta_num_factura)) {
        $num_serie_fac = $row[0] . "-" . $row[1];
        $fecha_actuall = $row[2];
    }

    $secuencial = $num_serie_fac;
    $ip = $secuencial;
    $iparr = split("\-", $ip);
    $secuencialresult = $iparr[2];
    $secuencialmitad = $iparr[1];
    $secuencialinicial = $iparr[0];
    $consulta_ambiente = pg_query("select codigo_ambi from ambiente where estado_ambi = 'Activo' ");
    while ($row = pg_fetch_row($consulta_ambiente)) {
        $ambiente = $row[0];
    }
    $consulta_empresa = pg_query("select ruc_empresa,clave, token from empresa where id_empresa = 1");
    while ($row = pg_fetch_row($consulta_empresa)) {
        $ruc = $row[0];
    }
    $consulta_cod_docu = pg_query("select codigo from tipo_comprobante where id_tipo_comprobante=4");
    while ($row = pg_fetch_row($consulta_cod_docu)) {
        $codDoc = $row[0]; //normal cuando generamos la clave
    }
    $valortxt9 = $fecha_time;
    $ip = $valortxt9;
    $fechasepar = split("\-", $ip);
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

    $clave = generarClave($valortxt9, $valorcodDoc, $valortruc, $valorambiente, $valortxt81, $valorsiete . '' . $valorsecuencial, $valortxt9, $valoremision);

    $sql = "UPDATE guia_remision set clave='" . $clave . "' where id_guia_remision='" . $_POST['id'] . "' ";


    $guardar = guardarSql($conexion, $sql);
    if ($guardar == 'true') {
        $data = 1;
    } else {
        $data = 0;
    }

    $item = array(
        'estado' => $data,
        'id' => $_POST['id']
    );
}



///////////////////////////////GUIA REMISION//////////////////////////7
if (isset($_POST['reenviarxmlguia']) == "reenviarxmlguia") {
    $consulta_clave = pg_query("SELECT  g.clave  FROM guia_remision g , clientes C WHERE   g.id_guia_remision='" . $_POST['id'] . "' ");
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

    $consulta_cod_docu = pg_query("select codigo from tipo_comprobante where id_tipo_comprobante=4");
    while ($row = pg_fetch_row($consulta_cod_docu)) {
        $codDoc = $row[0];  //normal cuando generamos la clave
    }
    $consulta_empresa = pg_query("select ruc_empresa,clave, token from empresa where id_empresa = $pv");
    while ($row = pg_fetch_row($consulta_empresa)) {
        $ruc = $row[0];
        $pass = $row[1];
        $token = $row[2];
    }

    try {
        $respuesta = consultarComprobante($ambiente, $consult_clave);
    } catch (Exception $e) {
        $data = -1000;
    }
    print_r($respuesta);
    if (isset($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado)) {
        if ($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado == 'AUTORIZADO') {
            $numeroAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion;
            $fechaAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;
            $ambienteAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente;
            $data = 2;
            pg_query("UPDATE guia_remision SET fecha_actual = '" . $fechaAutorizacion . "',  estado = '2', num_autorizacion = '" . $numeroAutorizacion . "' WHERE id_guia_remision = '" . $_POST['id'] . "'");
            $dataFile = generarXMLCDATAGUIA($respuesta);
            $doc = new DOMDocument('1.0', 'UTF-8');
            $doc->loadXML($dataFile); // xml  
            $doc->save($pathXmls . $numeroAutorizacion . '.xml');
        } else {
            $data = 7;
            pg_query("UPDATE guia_remision SET estado = '7' where id_guia_remision = '" . $_POST['id'] . "'"); // NO AUTORIZADO
        }
    } else {
        //        $data = $respuesta['RespuestaAutorizacionComprobante']['autorizaciones']['autorizacion']['estado'];
        pg_query("UPDATE guia_remision SET estado = '7' where id_guia_remision = '" . $_POST['id'] . "'"); // Rechazado
    }
    $item = array('estado' => $data, 'id' => $_POST['id']);
}
if (isset($_POST['enviarxmlguia']) == "enviarxmlguia") {
    $consulta_clave = pg_query("SELECT  g.clave  FROM guia_remision g , clientes C WHERE   g.id_guia_remision='" . $_POST['id'] . "' ");
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

    $consulta_cod_docu = pg_query("select codigo from tipo_comprobante where id_tipo_comprobante=4");
    while ($row = pg_fetch_row($consulta_cod_docu)) {
        $codDoc = $row[0];  //normal cuando generamos la clave
    }
    $consulta_empresa = pg_query("select ruc_empresa,clave, token from empresa where id_empresa = $pv");
    while ($row = pg_fetch_row($consulta_empresa)) {
        $ruc = $row[0];
        $pass = $row[1];
        $token = $row[2];
    }

    $result = generarXMLGUIA($_POST['id'], $codDoc, $ambiente, $emision);

    print_r($result);
    $doc = new DOMDocument('1.0', 'UTF-8');
    $doc->loadXML($result); // xml 
    $doc->save($pathXmls . "fac" . '.xml');
    //exec("$appFirma " . '../../xmls/' . $esquema . '/fac', $resultado);
    exec("$appFirma " . $pathXmls . '/fac "' . $pathARchivoP12 . '" "' . $claveFirma . '"', $resultado);
    try {
        $respuesta = consultarComprobante($ambiente, $consult_clave);
         print_r($respuesta);
    } catch (Exception $e) {
        $data = -1000;
    }
    //    print_r($respuesta);
    if (isset($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado)) {
        if ($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado == 'AUTORIZADO') {
            $numeroAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion;
            $fechaAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;
            $ambienteAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente;
            $data = 2;

            pg_query("UPDATE guia_remision SET fecha_actual = '" . $fechaAutorizacion . "',  estado = '2', num_autorizacion = '" . $numeroAutorizacion . "' WHERE id_guia_remision = '" . $_POST['id'] . "'");

            $dataFile = generarXMLCDATAGUIA($respuesta);
            $doc = new DOMDocument('1.0', 'UTF-8');
            $doc->loadXML($dataFile); // xml  
            $doc->save($pathXmls . $numeroAutorizacion . '.xml');
        } else {
            $data = 7;
            pg_query("UPDATE guia_remision SET estado = '7' where id_guia_remision = '" . $_POST['id'] . "'");
            //                             $data = $respuesta['RespuestaAutorizacionComprobante']['autorizaciones']['autorizacion']['estado'];
            // NO AUTORIZADO
        }
    }
    $item = array('estado' => $data, 'id' => $_POST['id']);
    exit();
}


//    print_r("compronabte_guia");
$consulta_emision = pg_query("select codigo_temision from tipo_emision where estado_temision='Activo'");
while ($row = pg_fetch_row($consulta_emision)) {
    $emision = $row[0]; //normal cuando generamos la clave
}
$secuencial = "$_POST[num_guia]" . "-" . "$_POST[num_guia_remision]";
$ip = $secuencial;
$iparr = split("\-", $ip);
$secuencialresult = $iparr[2];
$secuencialmitad = $iparr[1];
$secuencialinicial = $iparr[0];
$consulta_ambiente = pg_query("select codigo_ambi from ambiente where estado_ambi = 'Activo' ");
while ($row = pg_fetch_row($consulta_ambiente)) {
    $ambiente = $row[0];
}
$consulta_empresa = pg_query("select ruc_empresa,clave, token,direccion_empresa from empresa where id_empresa = $pv");
while ($row = pg_fetch_row($consulta_empresa)) {
    $ruc = $row[0];
    $direc_guia = $row[3];
    $pass = $row[1];
    $token = $row[2];
}
$consulta_cod_docu = pg_query("select codigo from tipo_comprobante where id_tipo_comprobante=4");
while ($row = pg_fetch_row($consulta_cod_docu)) {
    $codDoc = $row[0];  //normal cuando generamos la clave
}
$valortxt9 = $_POST[fecha_actual];

$ip = $valortxt9;
$fechasepar = split("\-", $ip);
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
$clave = generarClave($valortxt9, $valorcodDoc, $valortruc, $valorambiente, $valortxt81, $valorsiete . '' . $valorsecuencial, $valortxt9, $valoremision);
$contguias = 0;
$consulta = pg_query("select max(id_guia_remision) from guia_remision");
while ($row = pg_fetch_row($consulta)) {
    $contguias = $row[0];
}
$contguias++;
$conpunto = 1;
$consultapunto = pg_query("select max(id_punto_venta_empresa) from punto_venta_empresa where punto_venta_empresa.id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($consultapunto)) {
    $conpunto = $row[0];
}

$conpuntoresult = 1;
$consultapuntoresult = pg_query("select id_punto_venta from punto_venta_empresa where id_punto_venta_empresa='$conpunto'");
while ($row = pg_fetch_row($consultapuntoresult)) {
    $conpuntoresult = $row[0];
}
//echo 'GUIA' . "insert into guia_remision (id_guia_remision, id_factura_venta, id_transportista, fecha_inicio, 
//       fecha_fin, fecha_actual, hora_actual, num_serie, estado, clave, 
//       num_autorizacion, punto_partida, punto_llegada, motivo, hora_salida, 
//       hora_llegada, ruta, codigo_estable, documento_aduanero, id_empresa, 
//       num_guia_remision,id_cliente,fecha_autorizacion,id_usuario) values('$contguias','$contguias','$_POST[transportistaguia]','$_POST[fecha_inicio]','$_POST[fecha_fin]','$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[num_guia]','','$clave','','$_POST[punto_partida]','$_POST[destino]','$_POST[tipo_motivo]','$_POST[hora_actual]','$_POST[hora_actual]','$_POST[punto_partida].$_POST[destino]','','','$conpuntoresult','$_POST[num_guia_remision]','$_POST[id_cliente]','$_POST[fecha_actual]','$_SESSION[id]')";
$sql = "insert into guia_remision (id_guia_remision, id_factura_venta, id_transportista, fecha_inicio, 
       fecha_fin, fecha_actual, hora_actual, num_serie, estado, clave, 
       num_autorizacion, punto_partida, punto_llegada, motivo, hora_salida, 
       hora_llegada, ruta, codigo_estable, documento_aduanero, id_empresa, 
       num_guia_remision,id_cliente,fecha_autorizacion,id_usuario) values('$contguias','$contguias','$_POST[transportistaguia]','$_POST[fecha_inicio]','$_POST[fecha_fin]','$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[num_guia]','','$clave','','$_POST[punto_partida]','$_POST[destino]','$_POST[tipo_motivo]','$_POST[hora_actual]','$_POST[hora_actual]','$_POST[punto_partida].$_POST[destino]','','','$conpuntoresult','$_POST[num_guia_remision]','$_POST[id_cliente]','$_POST[fecha_actual]','$_SESSION[id]')";
$guardar = guardarSql($conexion, $sql);

$campo1 = $_POST['campo1'];
$campo2 = $_POST['campo2'];
$campo3 = $_POST['campo3'];
$campo4 = $_POST['campo4'];
$campo5 = $_POST['campo5'];
$campo6 = $_POST['campo6'];
$campo8 = $_POST['campo8'];
$campo9 = $_POST['campo9'];
$campo10 = $_POST['campo10'];
// fin
// agregar detalle_factura_venta
$arreglo1 = explode('|', $campo1);
$arreglo2 = explode('|', $campo2);
$arreglo3 = explode('|', $campo3);
$arreglo4 = explode('|', $campo4);
$arreglo5 = explode('|', $campo5);
$arreglo6 = explode('|', $campo6);
$arreglo8 = explode('|', $campo8);
$arreglo9 = explode('|', $campo9);
$arreglo10 = explode('|', $campo10);
$nelem = count($arreglo1);

if ($guardar == 'true') {
    for ($i = 1; $i < $nelem; $i++) {
        $cont4 = 0;
        $consulta = pg_query("select max(id_detalle_guia_remision) from detalle_guia_remision");
        while ($row = pg_fetch_row($consulta)) {
            $cont4 = $row[0];
        }
        $cont4++;
//        echo '' . "insert into detalle_guia_remision (id_detalle_guia_remision, id_guia_remision, cod_productos, cantidad, descripcion)
//      values('$cont4','$contguias','$arreglo1[$i]','$arreglo2[$i]','')";

        $sql = "insert into detalle_guia_remision (id_detalle_guia_remision, id_guia_remision, cod_productos, cantidad, descripcion)
      values('$cont4','$contguias','$arreglo1[$i]','$arreglo2[$i]','')";
        $guardar = guardarSql($conexion, $sql);
        if ($guardar == 'true') {
            $data = 22;
        } else {
            error_log_fv(0, "id_guia$cont1", "guardar_factura_venta.php", 343);
            error_log_fv(0, pg_last_error($conexion), "guardar_factura_venta.php", 360);
            error_log_fv(0, pg_last_error($guardar), "guardar_factura_venta.php", 360);

            //                                    echo '<br>GUARDAR FACTURA DETALLE: <br>' . "insert into detalle_factura_venta values('$cont6','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]',"
            //                                    . "'$arreglo4[$i]','$arreglo5[$i]','Activo','$arreglo6[$i]','$_POST[fecha_actual]','$valor_Servicio')"; 
            pg_query("DELETE FROM guia_remision WHERE id_guia_remision='$cont1';");
            //                                    echo '<br>GUARDAR FACTURA delete: <br>' . "DELETE FROM factura_venta WHERE id_factura_venta='$cont1';";
            $data = 60; /// error al guardar
            $item = array('estado' => $data);
        } // fin
    }
}
$result = generarXMLGUIA($contguias, $codDoc, $ambiente, $emision);
//    print_r($result);
$doc = new DOMDocument('1.0', 'UTF-8');
$doc->loadXML($result); // xml 
$doc->save($pathXmls . "fac" . '.xml');
//exec("$appFirma " . '../../xmls/' . $esquema . '/fac', $resultado);
exec("$appFirma " . $pathXmls . '/fac "' . $pathARchivoP12 . '" "' . $claveFirma . '"', $resultado);

try {
    $respuesta = consultarComprobante($ambiente, $clave);
} catch (Exception $e) {
    $data = -1000;
}
//    print_r($respuesta);
if (isset($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado)) {
    if ($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado == 'AUTORIZADO') {
        $numeroAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion;
        $fechaAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;
        $ambienteAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente;
        $data = 2;
        pg_query("UPDATE guia_remision SET fecha_actual = '" . $fechaAutorizacion . "',  estado = '2', num_autorizacion = '" . $numeroAutorizacion . "' WHERE id_guia_remision = '$contguias'");
        $dataFile = generarXMLCDATAGUIA($respuesta);
        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->loadXML($dataFile); // xml     
        $doc->save($pathXmls . $numeroAutorizacion . '.xml');
    } else {
        $data = 7;
        pg_query("UPDATE guia_remision SET estado = '7' where id_guia_remision = '$contguias'"); // NO AUTORIZADO
    }
}

$item = array('estado' => $data, 'id' => $contguias);
echo $data = json_encode($item);



