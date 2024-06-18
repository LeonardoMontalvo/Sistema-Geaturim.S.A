<?php
//subido 01/11/2022
session_start();
include '../../procesos/base.php';
include '../../reportes/fact_elect_xml.php';
include '../../reportes/fact_guia_xml.php';
include '../../firma/firma.php';
include '../../firma/xades.php';
include '../../admin/correo.php';
//include 'generarPDF.php';
include __DIR__ . "./../../procesos/autorizacion_documentos/generarPDF.php";
include '../../procesos/funciones.php';
include_once '../../procesos/kardexValorizado.php';
require_once '../../procesos/detalleProductosBodega.php';
require_once __DIR__ . '/../../procesos/configuracion.php';
require_once '../centro_costos/guardar_detalles.php';
$conexion = conectarse();
date_default_timezone_set('America/Guayaquil');
$fecha_time = date('Y-m-d', time());

$_POST["tarifa0"] = 0;
$_POST["tarifa12"] = 0;

$conf = new Configuracion();
$esquema = $conf->getNombreEsquema();
$appFirma = $conf->getPathAplicacionFIrma("app_firma");
$pathXmls = $conf->getPathXmlsFirma();
$pathARchivoP12 = $conf->getArchivoP12();
$claveFirma = $conf->getParametroEmpresa("clave_firma");
$pv = $_SESSION["PV"];
$pvinv = $_SESSION['PV_INV'];

function error_log_fv($errno, $errstr, $errfile, $errline)
{
    $ddf = fopen('../../error.log', 'a');
    $errfile = explode('/', $errfile);
    $errfile = $errfile[count($errfile) - 1];
    fwrite($ddf, "[" . date("r") . "] Error $errno-$errfile-$errline: $errstr\r\n");
    fclose($ddf);
}

/* exec("$appFirma " . $pathXmls . '/fac "' . $pathARchivoP12 . '" "' . $claveFirma . '"', $resultado);
  var_dump($resultado);
  exit(); */

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
    $consulta_num_factura = pg_query("select num_serie,num_factura,fecha_actual from factura_venta where id_factura_venta='" . $_POST['id'] . "'  ");
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
    $consulta_cod_docu = pg_query("select codigo from tipo_comprobante where id_tipo_comprobante=1");
    while ($row = pg_fetch_row($consulta_cod_docu)) {
        $codDoc = $row[0]; //normal cuando generamos la clave
    }
    $valortxt9 = $fecha_actuall;
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

    //    echo '::' . "UPDATE factura_venta set clave='" . $clave . "' where id_factura_venta='" . $_POST['id'] . "' ";

    $sql = "UPDATE factura_venta set clave='" . $clave . "' where id_factura_venta='" . $_POST['id'] . "' ";


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
if (isset($_POST['reenviarcorreo']) == "reenviarcorreo") {
    $resultado = pg_query("SELECT C.correo, C.nombres_cli, F.total_venta ,F.num_autorizacion, F.fecha_actual  FROM factura_venta F, clientes C "
        . "WHERE F.id_cliente = C.id_cliente AND F.id_factura_venta= '" . $_POST['id'] . "'");
    while ($row = pg_fetch_row($resultado)) {
        $email = $row[0];
        $nombre = $row[1];
        $total = $row[2];
        $num_autorizacion = $row[3];
        $fecha = $row[4];
    }

    $total_venta_tot = 0;
    $total_venta_tot = round($total, 2);
    $data = correo($fecha, $total_venta_tot, $pathXmls . $num_autorizacion . '.xml', $num_autorizacion . '.pdf', $nombre, $email, $pathXmls . $num_autorizacion . '.xml', generarPDFcorreo($_POST['id']), 1);
    if ($data == 1) {
        $resultado = pg_query("UPDATE factura_venta set estado_fac = '1' where id_factura_venta = '" . $_POST['id'] . "'");
        if ($resultado) {
            $data = 1; // datos actualizados
        } else {
            $data = 4; // error al momento de guadar
        }
    }
    $item = array(
        'estado' => $data,
        'id' => $_POST['id']
    );
}
if (isset($_POST['reenviarxml']) == "reenviarxml") {
    $consulta_clave = pg_query("SELECT F.clave FROM factura_venta F, clientes C "
        . "WHERE F.id_cliente = C.id_cliente AND F.id_factura_venta='" . $_POST['id'] . "' ");
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
    // print_r($respuesta);
    if (isset($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado)) {
        if ($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado == 'AUTORIZADO') {
            $numeroAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion;
            $fechaAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;
            $ambienteAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente;
            $data = 2;
            pg_query("UPDATE factura_venta SET fecha_autorizacion = '" . $fechaAutorizacion . "', estado_fac = '2', "
                . "num_autorizacion = '" . $numeroAutorizacion . "' WHERE id_factura_venta = '" . $_POST['id'] . "'");
            $dataFile = generarXMLCDATA($respuesta);
            $doc = new DOMDocument('1.0', 'UTF-8');
            $doc->loadXML($dataFile); // xml  
            $doc->save($pathXmls . $numeroAutorizacion . '.xml');
        } else {
            $data = 7;
            pg_query("UPDATE factura_venta SET estado_fac = '7' where id_factura_venta = '" . $_POST['id'] . "'"); // NO AUTORIZADO
        }
    } else {

        //        $data = $respuesta['RespuestaAutorizacionComprobante']['autorizaciones']['autorizacion']['estado'];
        pg_query("UPDATE factura_venta SET estado_fac = '7' where id_factura_venta = '" . $_POST['id'] . "'"); // Rechazado
    }

    $item = array(
        'estado' => $data,
        'id' => $_POST['id']
    );
}
if (isset($_POST['enviarxml']) == "enviarxml") {
    $consulta_clave = pg_query("SELECT F.clave FROM factura_venta F, clientes C WHERE F.id_cliente = C.id_cliente "
        . "AND F.id_factura_venta='" . $_POST['id'] . "' ");
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
    $consulta_empresa = pg_query("select ruc_empresa,clave, token from empresa where id_empresa = $pv");
    while ($row = pg_fetch_row($consulta_empresa)) {
        $ruc = $row[0];
        $pass = $row[1];
        $token = $row[2];
    }

    $result = generarXML($_POST['id'], $codDoc, $ambiente, $emision);
    //  print_r($result);
    $doc = new DOMDocument('1.0', 'UTF-8');
    $doc->loadXML($result); // xml 
    $doc->save($pathXmls . "fac" . '.xml');
    //exec("$appFirma " . '../../xmls/' . $esquema . '/fac', $resultado);
    exec("$appFirma " . $pathXmls . '/fac "' . $pathARchivoP12 . '" "' . $claveFirma . '"', $resultado);
    try {
        $respuesta = consultarComprobante($ambiente, $consult_clave);
        //  print_r($respuesta);
    } catch (Exception $e) {
        //var_dump($e->getMessage());
        $data = -1000;
    }
    if (isset($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado)) {
        if ($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado == 'AUTORIZADO') {
            $numeroAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion;
            $fechaAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;
            $ambienteAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente;
            $data = 2;
            pg_query("UPDATE factura_venta SET fecha_autorizacion = '" . $fechaAutorizacion . "', estado_fac = '2', "
                . "num_autorizacion = '" . $numeroAutorizacion . "' WHERE id_factura_venta = '" . $_POST['id'] . "'");
            $dataFile = generarXMLCDATA($respuesta);
            $doc = new DOMDocument('1.0', 'UTF-8');
            $doc->loadXML($dataFile); // xml  
            $doc->save($pathXmls . $numeroAutorizacion . '.xml');
        } else {
            $data = 7;
            pg_query("UPDATE factura_venta SET estado_fac = '7' where id_factura_venta = '" . $_POST['id'] . "'");
            //                             $data = $respuesta['RespuestaAutorizacionComprobante']['autorizaciones']['autorizacion']['estado'];
            // NO AUTORIZADO
        }
    }
    $item = array('estado' => $data, 'id' => $_POST['id']);
}

///////////////////////////////GUIA REMISION//////////////////////////7
if (isset($_POST['reenviarxmlguia']) == "reenviarxmlguia") {
    $consulta_clave = pg_query("SELECT  g.clave  FROM guia_remision g ,factura_venta F, clientes C WHERE F.id_cliente = C.id_cliente and g.id_factura_venta=f.id_factura_venta AND g.id_guia_remision='" . $_POST['id'] . "' ");
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
    $consulta_clave = pg_query("SELECT  g.clave  FROM guia_remision g ,factura_venta F, clientes C WHERE F.id_cliente = C.id_cliente and g.id_factura_venta=f.id_factura_venta AND g.id_guia_remision='" . $_POST['id'] . "' ");
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

    //    print_r($result);
    $doc = new DOMDocument('1.0', 'UTF-8');
    $doc->loadXML($result); // xml 
    $doc->save($pathXmls . "fac" . '.xml');
    //exec("$appFirma " . '../../xmls/' . $esquema . '/fac', $resultado);
    exec("$appFirma " . $pathXmls . '/fac "' . $pathARchivoP12 . '" "' . $claveFirma . '"', $resultado);

    try {
        $respuesta = consultarComprobante($ambiente, $consult_clave);
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
}

if ($_POST["id_fac"] == "") {
    //    $forma = $_POST['formas'];
    //////////////comparar tipo venta///////////
    if ($_POST["tipo_venta"] == "FACTURA") {
        // contador factura venta
        $cont1 = 0;
        $consulta = pg_query("select max(id_factura_venta) from factura_venta");
        while ($row = pg_fetch_row($consulta)) {
            $cont1 = $row[0];
        }
        $cont1++;
        // fin
        // contador clientes
        $contt = 0;
        $consulta_cli = pg_query("select max(id_cliente) from clientes");
        while ($row = pg_fetch_row($consulta_cli)) {
            $contt = $row[0];
        }
        $contt++;
        // fin

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
        //echo '<br>ID CLIENTE: ' . $_POST['id_cliente'];
        if ($_POST['id_cliente'] == "") {

            $id_tipoducu = $_POST['id_tdocu'];
            if ($id_tipoducu == 1) {
                $tipo = 'Ruc';
            } else {
                if ($id_tipoducu == 2) {
                    $tipo = 'Cedula';
                } else {
                    if ($id_tipoducu == 3) {
                        $tipo = 'Pasaporte';
                    } else {
                        if ($id_tipoducu == 4) {
                            $tipo = 'VENTA A CONSUMIDOR FINAL';
                        } else {
                            if ($id_tipoducu == 5) {
                                $tipo = 'IDENTIFICACION DELEXTERIOR';
                            }
                        }
                    }
                }
            }


            $sql = "insert into clientes values('$contt','$tipo','$_POST[ruc_ci]','" . strtoupper($_POST["nombre_cliente"]) . "','natural',"
                . "'" . strtoupper($_POST["direccion_cliente"]) . "','$_POST[telefono_cliente]','','','','" . strtolower($_POST["correo"]) . "','1','','Activo','1','$id_tipoducu')";
            pg_query($sql);

            $porcentaje = 0;
            $consulta_por = pg_query("select porcentaje_tarjeta from empresa");
            while ($row = pg_fetch_row($consulta_por)) {
                $porcentaje = $row[0];
            }
            $total = $_POST['tot'];
            $resultporcent = $total * ($porcentaje / 100);

            $consulta_emision = pg_query("select codigo_temision from tipo_emision where estado_temision='Activo'");
            while ($row = pg_fetch_row($consulta_emision)) {
                $emision = $row[0]; //normal cuando generamos la clave
            }
            $secuencial = "$_POST[num_serie]" . "-" . "$_POST[num_factura]";
            $ip = $secuencial;
            $iparr = split("\-", $ip);
            $secuencialresult = $iparr[2];
            $secuencialmitad = $iparr[1];
            $secuencialinicial = $iparr[0];
            $consulta_ambiente = pg_query("select codigo_ambi from ambiente where estado_ambi = 'Activo' ");
            while ($row = pg_fetch_row($consulta_ambiente)) {
                $ambiente = $row[0];
            }
            $consulta_empresa = pg_query("select ruc_empresa,clave, token from empresa where id_empresa = $pv");
            while ($row = pg_fetch_row($consulta_empresa)) {
                $ruc = $row[0];
            }
            $consulta_cod_docu = pg_query("select codigo from tipo_comprobante where id_tipo_comprobante=1");
            while ($row = pg_fetch_row($consulta_cod_docu)) {
                $codDoc = $row[0]; //normal cuando generamos la clave
            }
            $valortxt9 = $_POST["fecha_actual"];
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
            // guardar factura venta
            $forma = $_POST['formaspago'];
            if ($forma == 'otros') {
                //                        	 echo '<br>GUARDAR FACTURA VENTA88: <br>' .  "insert into factura_venta values('$cont1',' $conpuntoresult','$contt','$_SESSION[id]','$cont1','$_POST[num_factura]',"
                //                                . "'$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[fecha_actual]','$_POST[tipo_precio]','$_POST[formaspago]', "
                //                                . "'$_POST[autorizacion]','$_POST[fecha_auto]','$_POST[fecha_auto]','$_POST[tarifa0]','$_POST[tarifa12]','$_POST[iva]',"
                //                                . "'$_POST[desc]','$_POST[tot]','Activo','$_POST[fecha_actual]','$_POST[tarjetas]',1,'1','1' ,"
                //                                . "'$_POST[id_vendedor]','$_POST[num_serie]','$resultporcent'," . ($_POST[id_beneficiario] == NULL ? "NULL" : $_POST[id_beneficiario]) . ","
                //                                . "" . ($_POST[nombre_beneficiario] == NULL ? "NULL" : $_POST[nombre_beneficiario]) . ",'$clave','0','1',"
                //                                . "'$_POST[num_guia_remision]','$_POST[marca_vehiculo]','$_POST[placa_fac]','$_POST[propiedad]','$_POST[num_reclamo]','1')";//////////////////////////
                //	 
                $sql = "insert into factura_venta 
                (
                    id_factura_venta, id_empresa, id_cliente, id_usuario, comprobante, 
                    num_factura, fecha_actual, hora_actual, fecha_cancelacion, tipo_precio, 
                    forma_pago, num_autorizacion, fecha_autorizacion, fecha_caducidad, 
                    tarifa0, tarifa12, iva_venta, descuento_venta, total_venta, estado, 
                    fecha_anulacion, tarjeta_credito, temporal, valor_recibo, valor_cambio, 
                    id_vendedor, num_serie, porc_tarje_venta, id_beneficiario, nombre_beneficiario, 
                    clave, estado_fac, id_forma_pago, serie_guia_remision, marca_vehiculo, 
                    placa_fac, propiedad, num_reclamo, num_chasis, desc_prod, desc_fact)
                values('$cont1',' $conpuntoresult','$contt','$_SESSION[id]','$cont1','$_POST[num_factura]',"
                    . "'$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[fecha_actual]','$_POST[tipo_precio]','$_POST[formaspago]', "
                    . "'$_POST[autorizacion]','$_POST[fecha_auto]','$_POST[fecha_auto]','$_POST[tarifa0]','$_POST[tarifa12]','$_POST[iva]',"
                    . "'$_POST[desc]','$_POST[tot]','Activo','$_POST[fecha_actual]','$_POST[tarjetas]',1,'1','1' ,"
                    . "'$_POST[id_vendedor]','$_POST[num_serie]','$resultporcent'," . ($_POST["id_beneficiario"] == NULL ? "NULL" : $_POST["id_beneficiario"]) . ","
                    . "" . ($_POST["nombre_beneficiario"] == NULL ? "NULL" : $_POST["nombre_beneficiario"]) . ",'$clave','0','1',"
                    . "'$_POST[num_guia_remision]','$_POST[marca_vehiculo]','$_POST[placa_fac]','$_POST[propiedad]','$_POST[num_reclamo]','1',$_POST[descprod],$_POST[descfact])";
            } else {

                //                        	 echo '<br>GUARDAR FACTURA VENTA: <br>' . "insert into factura_venta values('$cont1',' $conpuntoresult','$contt','$_SESSION[id]','$cont1','$_POST[num_factura]',"
                //                                . "'$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[fecha_actual]','$_POST[tipo_precio]','$_POST[formaspago]', "
                //                                . "'$_POST[autorizacion]','$_POST[fecha_auto]','$_POST[fecha_auto]','$_POST[tarifa0]','$_POST[tarifa12]','$_POST[iva]',"
                //                                . "'$_POST[desc]','$_POST[tot]','Activo','$_POST[fecha_actual]','$_POST[tarjetas]',1,'$_POST[valor_recibo]','$_POST[valor_cambio]' ,"
                //                                . "'$_POST[id_vendedor]','$_POST[num_serie]','$resultporcent'," . ($_POST[id_beneficiario] == NULL ? "NULL" : $_POST[id_beneficiario]) . ","
                //                                . "" . ($_POST[nombre_beneficiario] == NULL ? "NULL" : $_POST[nombre_beneficiario]) . ",'$clave','0','1',"
                //                                . "'$_POST[num_guia_remision]','$_POST[marca_vehiculo]','$_POST[placa_fac]','$_POST[propiedad]','$_POST[num_reclamo]','$_POST[num_tarjeta]')";//////////////////////////
                //	 
                //	 
                //                        
                //                        	 echo '<br>GUARDAR FACTURA VENTA99: <br>' . "insert into factura_venta values('$cont1',' $conpuntoresult','$contt','$_SESSION[id]','$cont1','$_POST[num_factura]',"
                //                                . "'$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[fecha_actual]','$_POST[tipo_precio]','$_POST[formaspago]', "
                //                                . "'$_POST[autorizacion]','$_POST[fecha_auto]','$_POST[fecha_auto]','$_POST[tarifa0]','$_POST[tarifa12]','$_POST[iva]',"
                //                                . "'$_POST[desc]','$_POST[tot]','Activo','$_POST[fecha_actual]','$_POST[tarjetas]',1,'$_POST[valor_recibo]','$_POST[valor_cambio]' ,"
                //                                . "'$_POST[id_vendedor]','$_POST[num_serie]','$resultporcent'," . ($_POST[id_beneficiario] == NULL ? "NULL" : $_POST[id_beneficiario]) . ","
                //                                . "" . ($_POST[nombre_beneficiario] == NULL ? "NULL" : $_POST[nombre_beneficiario]) . ",'$clave','0','1',"
                //                                . "'$_POST[num_guia_remision]','$_POST[marca_vehiculo]','$_POST[placa_fac]','$_POST[propiedad]','$_POST[num_reclamo]','1')";//////////////////////////
                //	 

                $sql = "insert into factura_venta 
                (
                    id_factura_venta, id_empresa, id_cliente, id_usuario, comprobante, 
                    num_factura, fecha_actual, hora_actual, fecha_cancelacion, tipo_precio, 
                    forma_pago, num_autorizacion, fecha_autorizacion, fecha_caducidad, 
                    tarifa0, tarifa12, iva_venta, descuento_venta, total_venta, estado, 
                    fecha_anulacion, tarjeta_credito, temporal, valor_recibo, valor_cambio, 
                    id_vendedor, num_serie, porc_tarje_venta, id_beneficiario, nombre_beneficiario, 
                    clave, estado_fac, id_forma_pago, serie_guia_remision, marca_vehiculo, 
                    placa_fac, propiedad, num_reclamo, num_chasis, desc_prod, desc_fact)
                values('$cont1',' $conpuntoresult','$contt','$_SESSION[id]','$cont1','$_POST[num_factura]',"
                    . "'$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[fecha_actual]','$_POST[tipo_precio]','$_POST[formaspago]', "
                    . "'$_POST[autorizacion]','$_POST[fecha_auto]','$_POST[fecha_auto]','$_POST[tarifa0]','$_POST[tarifa12]','$_POST[iva]',"
                    . "'$_POST[desc]','$_POST[tot]','Activo','$_POST[fecha_actual]','$_POST[tarjetas]',1,'$_POST[valor_recibo]','$_POST[valor_cambio]' ,"
                    . "'$_POST[id_vendedor]','$_POST[num_serie]','$resultporcent'," . ($_POST["id_beneficiario"] == NULL ? "NULL" : $_POST["id_beneficiario"]) . ","
                    . "" . ($_POST["nombre_beneficiario"] == NULL ? "NULL" : $_POST["nombre_beneficiario"]) . ",'$clave','0','1',"
                    . "'$_POST[num_guia_remision]','$_POST[marca_vehiculo]','$_POST[placa_fac]','$_POST[propiedad]','$_POST[num_reclamo]','1',$_POST[descprod],$_POST[descfact])";
            }

            $guardar = guardarSql($conexion, $sql);
            if (!empty($guardar) && !empty($_POST['id_centro_costo'])) {
                guardarDetalleCentroCosto($cont1, $_POST['id_centro_costo'], "factura_venta");
            }
            if (!empty($guardar)) {
                guardarDetalleImpuestoFactura($cont1);
            }
            if ($guardar == 'true') {
                $data = 22;
            } else {
                echo '<br>GUARDAR FACTURA OTRO1: <br>' . "insert into factura_venta values('$cont1',' $conpuntoresult','$contt','$_SESSION[id]','$cont1','$_POST[num_factura]',"
                    . "'$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[fecha_actual]','$_POST[tipo_precio]','$_POST[formaspago]', "
                    . "'$_POST[autorizacion]','$_POST[fecha_auto]','$_POST[fecha_auto]','$_POST[tarifa0]','$_POST[tarifa12]','$_POST[iva]',"
                    . "'$_POST[desc]','$_POST[tot]','Activo','$_POST[fecha_actual]','$_POST[tarjetas]',1,'$_POST[valor_recibo]','$_POST[valor_cambio]' ,"
                    . "'$_POST[id_vendedor]','$_POST[num_serie]','$resultporcent'," . ($_POST["id_beneficiario"] == NULL ? "NULL" : $_POST["id_beneficiario"]) . ","
                    . "" . ($_POST["nombre_beneficiario"] == NULL ? "NULL" : $_POST["nombre_beneficiario"]) . ",'$clave','0','1',"
                    . "'$_POST[num_guia_remision]','$_POST[marca_vehiculo]','$_POST[placa_fac]','$_POST[propiedad]','$_POST[num_reclamo]','1',$_POST[descprod],$_POST[descfact])"; //////////////////////////

                $data = 60; /// error al guardar
                $item = array('estado' => $data);
            } // fin
            /////FACTURACION ELECTRONICA
            /////FACTURACION ELECTRONICA
            /////FACTURACION ELECTRONICA
            /////FACTURACION ELECTRONICA
            /////FACTURACION ELECTRONICA
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

            $consulta_empresa = pg_query("select ruc_empresa,clave, token from empresa where id_empresa = $pv");
            while ($row = pg_fetch_row($consulta_empresa)) {
                $ruc = $row[0];
                $pass = $row[1];
                $token = $row[2];
            }

            $consulta_ambiente = pg_query("select codigo_ambi from ambiente where estado_ambi = 'Activo' ");
            while ($row = pg_fetch_row($consulta_ambiente)) {
                $ambiente = $row[0];
            }
            //                $result = generarXML($cont1, $codDoc, $ambiente, $emision);
            //                $doc = new DOMDocument('1.0', 'UTF-8');
            //                $doc->loadXML($result); // xml 
            //                $doc->save($pathXmls . "fac" . '.xml');
            //                //exec("$appFirma " . '../../xmls/' . $esquema . '/fac', $resultado);
            //                exec("$appFirma " . $pathXmls . '/fac "' . $pathARchivoP12 . '" "' . $claveFirma . '"', $resultado);
            //                $respuesta = consultarComprobante($ambiente, $clave);
            //                if (isset($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado)) {
            //                    if ($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado == 'AUTORIZADO') {
            //                        $numeroAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion;
            //                        $fechaAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;
            //                        $ambienteAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente;
            //                        $data = 2;
            //                        pg_query("UPDATE factura_venta SET fecha_autorizacion = '" . $fechaAutorizacion . "',  estado_fac = '2', "
            //                                . "num_autorizacion = '" . $numeroAutorizacion . "' WHERE id_factura_venta = '$cont1'");
            //                        $dataFile = generarXMLCDATA($respuesta);
            //                        $doc = new DOMDocument('1.0', 'UTF-8');
            //                        $doc->loadXML($dataFile); // xml  
            //                        $doc->save($pathXmls . $numeroAutorizacion . '.xml');
            //                    } else {
            //                        $data = 7;
            //                        //          pg_query("UPDATE factura_venta SET estado_fac = '7' where id_factura_venta = '$cont1'"); // NO AUTORIZADO
            //                    }
            //                }
            //                if ($guardar == 'true') {
            //                    $item = array('estado' => $data, 'id' => $cont1);
            //                }
        } else {
            // guardar factura venta
            $porcentaje = 0;
            $consulta_por = pg_query("select porcentaje_tarjeta from empresa");
            while ($row = pg_fetch_row($consulta_por)) {
                $porcentaje = $row[0];
            }
            $total = $_POST['tot'];
            $resultporcent = $total * ($porcentaje / 100);

            $consulta_emision = pg_query("select codigo_temision from tipo_emision where estado_temision='Activo'");
            while ($row = pg_fetch_row($consulta_emision)) {
                $emision = $row[0]; //normal cuando generamos la clave
            }

            $secuencial = "$_POST[num_serie]" . "-" . "$_POST[num_factura]";
            $ip = $secuencial;
            $iparr = split("\-", $ip);
            $secuencialresult = $iparr[2];
            $secuencialmitad = $iparr[1];
            $secuencialinicial = $iparr[0];
            $consulta_ambiente = pg_query("select codigo_ambi from ambiente where estado_ambi = 'Activo' ");
            while ($row = pg_fetch_row($consulta_ambiente)) {
                $ambiente = $row[0];
            }
            $consulta_empresa = pg_query("select ruc_empresa,clave, token from empresa where id_empresa = $pv");
            while ($row = pg_fetch_row($consulta_empresa)) {
                $ruc = $row[0];
            }
            $consulta_cod_docu = pg_query("select codigo from tipo_comprobante where id_tipo_comprobante=1");
            while ($row = pg_fetch_row($consulta_cod_docu)) {
                $codDoc = $row[0]; //normal cuando generamos la clave
            }
            $valortxt9 = $_POST["fecha_actual"];
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
            //          print_r("ENTRO AQUI");
            // guardar factura venta
            $forma = $_POST['formaspago'];
            if ($forma == 'otros') {
                //                	 echo '<br>GUARDAR FACTURA VENTA2: <br>' . "insert into factura_venta values('$cont1','$conpuntoresult','$_POST[id_cliente]','$_SESSION[id]','$cont1','$_POST[num_factura]',"
                //                        . "'$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[fecha_actual]','$_POST[tipo_precio]','$_POST[formaspago]',"
                //                        . "'$_POST[autorizacion]','$_POST[fecha_auto]','$_POST[fecha_auto]','$_POST[tarifa0]','$_POST[tarifa12]','$_POST[iva]','$_POST[desc]',"
                //                        . "'$_POST[tot]','Activo','$_POST[fecha_actual]','$_POST[tarjetas]',1,'1','1' ,'$_POST[id_vendedor]',"
                //                        . "'$_POST[num_serie]','$resultporcent'," . ($_POST[id_beneficiario] == NULL ? "NULL" : $_POST[id_beneficiario]) . ","
                //                        . "" . ($_POST[nombre_beneficiario] == NULL ? "NULL" : $_POST[nombre_beneficiario]) . ",'$clave','0','$_POST[formas]',"
                //                        . "'$_POST[num_guia_remision]','$_POST[marca_vehiculo]','$_POST[placa_fac]','$_POST[propiedad]','$_POST[num_reclamo]','1')";//////////////////////////
                //	 
                $sql = "insert into factura_venta 
                (
                    id_factura_venta, id_empresa, id_cliente, id_usuario, comprobante, 
                    num_factura, fecha_actual, hora_actual, fecha_cancelacion, tipo_precio, 
                    forma_pago, num_autorizacion, fecha_autorizacion, fecha_caducidad, 
                    tarifa0, tarifa12, iva_venta, descuento_venta, total_venta, estado, 
                    fecha_anulacion, tarjeta_credito, temporal, valor_recibo, valor_cambio, 
                    id_vendedor, num_serie, porc_tarje_venta, id_beneficiario, nombre_beneficiario, 
                    clave, estado_fac, id_forma_pago, serie_guia_remision, marca_vehiculo, 
                    placa_fac, propiedad, num_reclamo, num_chasis, desc_prod, desc_fact)
                values('$cont1','$conpuntoresult','$_POST[id_cliente]','$_SESSION[id]','$cont1','$_POST[num_factura]',"
                    . "'$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[fecha_actual]','$_POST[tipo_precio]','$_POST[formaspago]',"
                    . "'$_POST[autorizacion]','$_POST[fecha_auto]','$_POST[fecha_auto]','$_POST[tarifa0]','$_POST[tarifa12]','$_POST[iva]','$_POST[desc]',"
                    . "'$_POST[tot]','Activo','$_POST[fecha_actual]','$_POST[tarjetas]',1,'1','1' ,'$_POST[id_vendedor]',"
                    . "'$_POST[num_serie]','$resultporcent'," . ($_POST["id_beneficiario"] == NULL ? "NULL" : $_POST["id_beneficiario"]) . ","
                    . "" . ($_POST["nombre_beneficiario"] == NULL ? "NULL" : $_POST["nombre_beneficiario"]) . ",'$clave','0','$_POST[formas]',"
                    . "'$_POST[num_guia_remision]','$_POST[marca_vehiculo]','$_POST[placa_fac]','$_POST[propiedad]','$_POST[num_reclamo]','1',$_POST[descprod],$_POST[descfact])";
            } else {

                //                	 echo '<br>GUARDAR FACTURA VENTA1: <br>' . "insert into factura_venta values('$cont1','$conpuntoresult','$_POST[id_cliente]','$_SESSION[id]','$cont1','$_POST[num_factura]',"
                //                        . "'$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[fecha_actual]','$_POST[tipo_precio]','$_POST[formaspago]',"
                //                        . "'$_POST[autorizacion]','$_POST[fecha_auto]','$_POST[fecha_auto]','$_POST[tarifa0]','$_POST[tarifa12]','$_POST[iva]','$_POST[desc]',"
                //                        . "'$_POST[tot]','Activo','$_POST[fecha_actual]','$_POST[tarjetas]',1,'$_POST[valor_recibo]','$_POST[valor_cambio]' ,'$_POST[id_vendedor]',"
                //                        . "'$_POST[num_serie]','$resultporcent'," . ($_POST[id_beneficiario] == NULL ? "NULL" : $_POST[id_beneficiario]) . ","
                //                        . "" . ($_POST[nombre_beneficiario] == NULL ? "NULL" : $_POST[nombre_beneficiario]) . ",'$clave','0','$_POST[formas]',"
                //                        . "'$_POST[num_guia_remision]','$_POST[marca_vehiculo]','$_POST[placa_fac]','$_POST[propiedad]','$_POST[num_reclamo]','1')";//////////////////////////
                //	 

                $sql = "insert into factura_venta 
                (
                    id_factura_venta, id_empresa, id_cliente, id_usuario, comprobante, 
                    num_factura, fecha_actual, hora_actual, fecha_cancelacion, tipo_precio, 
                    forma_pago, num_autorizacion, fecha_autorizacion, fecha_caducidad, 
                    tarifa0, tarifa12, iva_venta, descuento_venta, total_venta, estado, 
                    fecha_anulacion, tarjeta_credito, temporal, valor_recibo, valor_cambio, 
                    id_vendedor, num_serie, porc_tarje_venta, id_beneficiario, nombre_beneficiario, 
                    clave, estado_fac, id_forma_pago, serie_guia_remision, marca_vehiculo, 
                    placa_fac, propiedad, num_reclamo, num_chasis, desc_prod, desc_fact)
                values('$cont1','$conpuntoresult','$_POST[id_cliente]','$_SESSION[id]','$cont1','$_POST[num_factura]',"
                    . "'$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[fecha_actual]','$_POST[tipo_precio]','$_POST[formaspago]',"
                    . "'$_POST[autorizacion]','$_POST[fecha_auto]','$_POST[fecha_auto]','$_POST[tarifa0]','$_POST[tarifa12]','$_POST[iva]','$_POST[desc]',"
                    . "'$_POST[tot]','Activo','$_POST[fecha_actual]','$_POST[tarjetas]',1,'$_POST[valor_recibo]','$_POST[valor_cambio]' ,'$_POST[id_vendedor]',"
                    . "'$_POST[num_serie]','$resultporcent'," . ($_POST["id_beneficiario"] == NULL ? "NULL" : $_POST["id_beneficiario"]) . ","
                    . "" . ($_POST["nombre_beneficiario"] == NULL ? "NULL" : $_POST["nombre_beneficiario"]) . ",'$clave','0','$_POST[formas]',"
                    . "'$_POST[num_guia_remision]','$_POST[marca_vehiculo]','$_POST[placa_fac]','$_POST[propiedad]','$_POST[num_reclamo]','1',$_POST[descprod],$_POST[descfact])";
            }

            //            echo '<br>GUARDAR FACTURA VENTA: <br>' . $sql;
            $guardar = guardarSql($conexion, $sql);
            if (!empty($guardar) && !empty($_POST['id_centro_costo'])) {
                guardarDetalleCentroCosto($cont1, $_POST['id_centro_costo'], "factura_venta");
            }
            if (!empty($guardar)) {
                guardarDetalleImpuestoFactura($cont1);
            }
            if ($guardar == 'true') {
                $data = 22;
            } else {
                //                echo '<br>GUARDAR FACTURA VENTA44444DD: <br>' . "insert into factura_venta values('$cont1','$conpuntoresult','$_POST[id_cliente]','$_SESSION[id]','$cont1','$_POST[num_factura]',"
                //                . "'$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[fecha_actual]','$_POST[tipo_precio]','$_POST[formaspago]',"
                //                . "'$_POST[autorizacion]','$_POST[fecha_auto]','$_POST[fecha_auto]','$_POST[tarifa0]','$_POST[tarifa12]','$_POST[iva]','$_POST[desc]',"
                //                . "'$_POST[tot]','Activo','$_POST[fecha_actual]','$_POST[tarjetas]',1,'$_POST[valor_recibo]','$_POST[valor_cambio]' ,'$_POST[id_vendedor]',"
                //                . "'$_POST[num_serie]','$resultporcent'," . ($_POST[id_beneficiario] == NULL ? "NULL" : $_POST[id_beneficiario]) . ","
                //                . "" . ($_POST[nombre_beneficiario] == NULL ? "NULL" : $_POST[nombre_beneficiario]) . ",'$clave','0','$_POST[formas]',"
                //                . "'$_POST[num_guia_remision]','$_POST[marca_vehiculo]','$_POST[placa_fac]','$_POST[propiedad]','$_POST[num_reclamo]','1')"; //////////////////////////

                $data = 60; /// error al guardar
                $item = array('estado' => $data);
            } // fin
        }

        // modificar proformas
        if ($_POST['proforma'] != "") {
            pg_query("Update proforma Set estado='Pasivo' where id_proforma='" . $_POST['proforma'] . "'");
        }
        // fin
        // datos detalle factura
        $campo1 = $_POST['campo1'];
        $campo2 = $_POST['campo2'];
        $campo3 = $_POST['campo3'];
        $campo4 = $_POST['campo4'];
        $campo5 = $_POST['campo5'];
        $campo6 = $_POST['campo6'];
        $campo8 = $_POST['campo8'];
        $campo9 = $_POST['campo9'];
        $campo10 = $_POST['campo10'];
        $tarifas = $_POST['tarifas'];
        $vlores_iva = $_POST['vlores_iva'];
        $cods_impuesto = $_POST['cods_impuesto'];
        $cods_tarifa = $_POST['cods_tarifa'];
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
        $arreglotarifas = explode('|', $tarifas);
        $arreglovlores_iva = explode('|', $vlores_iva);
        $arreglocods_impuesto = explode('|', $cods_impuesto);
        $arreglocods_tarifa = explode('|', $cods_tarifa);

        $nelem = count($arreglo1);
        $forma = $_POST['formaspago'];
        if ($guardar == 'true') {

            if (isset($_POST["id_proforma_tecnico"]) && $_POST["id_proforma_tecnico"] > 0) {
                actualizarProformaTecnico($_POST["id_proforma_tecnico"], "id_factura", $cont1);
            }
            if ($forma == "otros") {
                //////FACTURA
                /*  echo '::'."select sum(x.sum) from (select formas_pago_mixto.forma_pago,sum(formas_pago_mixto.valor) from factura_venta, formas_pago_mixto 
                  where factura_venta.id_factura_venta=formas_pago_mixto.id_factura_venta and factura_venta.id_factura_venta='$cont1'
                  and (formas_pago_mixto.forma_pago='CREDITO' or formas_pago_mixto.forma_pago='CPOSFECHADO')  and formas_pago_mixto.tipo_documento='FACTURA' GROUP BY formas_pago_mixto.forma_pago
                  )x"; */
                $consulta_mixto = pg_query("select sum(x.sum) from (select formas_pago_mixto.forma_pago,sum(formas_pago_mixto.valor) from factura_venta, formas_pago_mixto 
                where factura_venta.id_factura_venta=formas_pago_mixto.id_factura_venta and factura_venta.id_factura_venta='$cont1' 
                and (formas_pago_mixto.forma_pago='CREDITO' or formas_pago_mixto.forma_pago='CPOSFECHADO')  and formas_pago_mixto.tipo_documento='FACTURA' GROUP BY formas_pago_mixto.forma_pago
                )x");
                $valor_contado = "";
                while ($row = pg_fetch_row($consulta_mixto)) {
                    //                    $cont2_mixto_contado = $row[0];
                    $valor_contado = $row[0];
                }
                if ($valor_contado != "") {
                    // variables pagos
                    $adelanto = '0.00';
                    $meses = $_POST['meses'];
                    //                    $total = $valor_contado;
                }
                // fin
                // contador pagos venta
                $cont2 = 0;
                $consulta = pg_query("select max(id_pagos_venta) from pagos_venta");
                while ($row = pg_fetch_row($consulta)) {
                    $cont2 = $row[0];
                }
                $cont2++;
                // fin
                // guardar pagos venta
                //                if ($adelanto == "") {
                //                   
                //                    $adelanto = 0.00;
                //                } else {
                //                    $monto = $total - $adelanto;
                //                    $format = number_format($monto, 2, '.', '');
                //                }
                //                $monto = $total;
                //                $format = number_format($monto, 2, '.', '');
                if ($_POST['id_cliente'] == "") {
                    $idCli = 0;
                    $consulta_cli = pg_query("select max(id_cliente) from clientes");
                    while ($row = pg_fetch_row($consulta_cli)) {
                        $idCli = $row[0];
                    }

                    $sql = "insert into pagos_venta values('$cont2','$idCli','$cont1','$_SESSION[id]','$_POST[fecha_actual]','$adelanto','$meses',"
                        . "'Factura','$valor_contado','$valor_contado','Activo','$_POST[fecha_dias]','1')";
                    $guardar_nv = guardarSql($conexion, $sql);
                    if ($guardar_nv == 'true') {
                    } else {
                        error_log_fv(0, "id_factura_venta=$cont1", "guardar_factura_venta.php", 343);
                        error_log_fv(0, pg_last_error($conexion), "guardar_factura_venta.php", 360);
                        error_log_fv(0, pg_last_error($guardar_nv), "guardar_factura_venta.php", 360);
                    }
                } else {
                    $cliente1 = $_POST['id_cliente'];

                    pg_query("Update clientes Set  nombres_cli='" . strtoupper($_POST['nombre_cliente']) . "',direccion_cli='" . strtoupper($_POST['direccion_cliente']) . "', telefono='$_POST[telefono_cliente]', correo='$_POST[correo]' where id_cliente='$cliente1'");

                    //echo '<br>GUARDAR FACTURA pagos_venta1: <br>' . "insert into pagos_venta values('$cont2','$cliente1','$cont1','$_SESSION[id]','$_POST[fecha_actual]','$adelanto','1',"
                    //. "'Factura','$format','$format','Activo','$_POST[fecha_dias]','$conpuntoresult')"; //////////////////////////

                    $sql = "insert into pagos_venta values('$cont2','$cliente1','$cont1','$_SESSION[id]','$_POST[fecha_actual]','$adelanto','1',"
                        . "'Factura','$valor_contado','$valor_contado','Activo','$_POST[fecha_dias]','1')";
                    $guardar_nv = guardarSql($conexion, $sql);
                    if ($guardar_nv == 'true') {
                    } else {
                        error_log_fv(0, "id_factura_venta=$cont1", "guardar_factura_venta.php", 343);
                        error_log_fv(0, pg_last_error($conexion), "guardar_factura_venta.php", 360);
                        error_log_fv(0, pg_last_error($guardar_nv), "guardar_factura_venta.php", 360);
                    }
                }
                // guardar meses
                if ($meses > 1) {
                    //                    for ($i = 1; $i <= $meses - 1; $i++) {
                    //                        // contador detalle pagos venta
                    //                        $cont3 = 0;
                    //                        $consulta8 = pg_query("select max(id_detalle_pagos_venta) from detalle_pagos_venta");
                    //                        while ($row = pg_fetch_row($consulta8)) {
                    //                            $cont3 = $row[0];
                    //                        }
                    //                        $cont3++;
                    //                        // fin
                    //                        $calcu = $monto / ($meses);
                    //                        $nuevaFecha = date('Y-m-d', strtotime(" + $i month"));
                    //                        $format_numero = number_format(floor($calcu), 4, '.', '');
                    //                          echo '<br>GUARDAR FACTURA VENTA4F: <br>' . "insert into detalle_pagos_venta values('$cont3','$cont2','$nuevaFecha','$format_numero','$format_numero','Activo')";
                    //                        pg_query("insert into detalle_pagos_venta values('$cont3','$cont2','$nuevaFecha','$format_numero','$format_numero','Activo')");
                    //                    }
                    //
                    //                    $cont3++;
                    //                    $calcu1 = floor($calcu) * ($meses - 1);
                    //                    $ultimaFecha = date('Y-m-d', strtotime(" + $i month"));
                    //                    $sal = $monto - $calcu1;
                    //                    $format_numero2 = number_format($sal, 4, '.', '');
                    //                      echo '<br>GUARDAR FACTURA VENTA5G: <br>' . "insert into detalle_pagos_venta values('$cont3','$cont2','$ultimaFecha','$format_numero2','$format_numero2','Activo')";
                    //                    pg_query("insert into detalle_pagos_venta values('$cont3','$cont2','$ultimaFecha','$format_numero2','$format_numero2','Activo')");
                } else {
                    $cont3 = 0;
                    $consulta8 = pg_query("select max(id_detalle_pagos_venta) from detalle_pagos_venta");
                    while ($row = pg_fetch_row($consulta8)) {
                        $cont3 = $row[0];
                    }
                    $cont3++;
                    $k = 1;
                    $format2 = number_format($monto, 2, '.', '');
                    $Fecha = date('Y-m-d', strtotime(" + $k month"));
                    //                    echo '<br>GUARDAR FACTURA VENT7UA: <br>' . "insert into detalle_pagos_venta values('$cont3','$cont2','$Fecha','$format2','$format2','Activo')";
                    //     
                    //TODO pagos_venta               
                    pg_query("insert into detalle_pagos_venta values('$cont3','$cont2','$Fecha','$format2','$format2','Activo')");
                }
                // fin
                // guardar detalle compra
                for ($i = 1; $i < $nelem; $i++) {

                    // contador detalle factura venta
                    $cont4 = 0;
                    $consulta = pg_query("select max(id_detalle_venta) from detalle_factura_venta");
                    while ($row = pg_fetch_row($consulta)) {
                        $cont4 = $row[0];
                    }
                    $cont4++;
                    // fin 
                    // contador kardex
                    $cont_k = 0;
                    $consulta_k = pg_query("select max(id_kardex) from kardex");
                    while ($row = pg_fetch_row($consulta_k)) {
                        $cont_k = $row[0];
                    }
                    $cont_k++;
                    // fin
                    // contador kardex valorizado
                    $cont_v = 0;
                    $consulta_v = pg_query("select max(id_kardex) from kardex_valorizado");
                    while ($row = pg_fetch_row($consulta_v)) {
                        $cont_v = $row[0];
                    }
                    $cont_v++;
                    // fin
                    // guardar detalle_factura
                    //                    	 echo '<br>GUARDAR FACTURA VENTA1: <br>' . "insert into detalle_factura_venta values('$cont4','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]',"
                    //                            . "'$arreglo5[$i]','Activo','$arreglo6[$i]','$_POST[fecha_actual]')";//////////////////////////
                    //	 
                    //	 
                    $consulta_bien_servi = pg_query(" select bien_servicios from productos where cod_productos=$arreglo1[$i]");
                    while ($row = pg_fetch_row($consulta_bien_servi)) {
                        $valor_Servicio = $row[0];
                    }
                    if ($arreglo6[$i] == "") {
                        $arreglo6[$i] = 0;
                    } else {
                        $arreglo6[$i] = $arreglo6[$i];
                    }
                    //                    print_r("valordata1::" . $data);

                    if ($data == 22) {

                        //                        echo 'detalle_factura_venta2:' . "insert into detalle_factura_venta values('$cont4','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]',"
                        //                        . "'$arreglo4[$i]','$arreglo5[$i]','Activo','$arreglo6[$i]','$_POST[fecha_actual]','$valor_Servicio')";

                        $sql = "insert into detalle_factura_venta 
                        (
                            id_detalle_venta, id_factura_venta, cod_productos, cantidad, 
                            precio_venta, descuento_producto, total_venta, estado, pendientes, 
                            fecha_venta, bien_servicio, cantidad_unidad, unidad_medida, detalle_producto)
                        values('$cont4','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]',"
                            . "'$arreglo4[$i]','$arreglo5[$i]','Activo','$arreglo6[$i]','$_POST[fecha_actual]','$valor_Servicio','$arreglo8[$i]','$arreglo9[$i]','$arreglo10[$i]')";
                        $guardar = guardarSql($conexion, $sql);
                        if ($guardar == 'true') {
                            $data = 22;
                            guardarDetalleImpuestoProducto($arreglocods_impuesto[$i], $arreglocods_tarifa[$i], $arreglotarifas[$i], $arreglovlores_iva[$i], $arreglo5[$i], $cont4);
                        } else {
                            error_log_fv(0, "id_factura=$cont1", "guardar_factura_venta.php", 343);
                            error_log_fv(0, pg_last_error($conexion), "guardar_factura_venta.php", 360);
                            error_log_fv(0, pg_last_error($guardar), "guardar_factura_venta.php", 360);

                            // echo '<br>GUARDAR FACTURA DETALLE: <br>' . "insert into detalle_factura_venta values('$cont6','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]',"
                            //. "'$arreglo4[$i]','$arreglo5[$i]','Activo','$arreglo6[$i]','$_POST[fecha_actual]','$valor_Servicio')"; //////////////////////////
                            pg_query("DELETE FROM factura_venta WHERE id_factura_venta='$cont1';");
                            //echo '<br>GUARDAR FACTURA delete: <br>' . "DELETE FROM factura_venta WHERE id_factura_venta='$cont1';";
                            $data = 60; /// error al guardar
                            $item = array('estado' => $data);
                        } // fin
                    }


                    $contb = 0;
                    $consulta = pg_query("select max(id_detalle_productos_bodega) from detalle_producto_bodega");
                    while ($row = pg_fetch_row($consulta)) {
                        $contb = $row[0];
                    }
                    $contb++;

                    // guardar detalle productos bodega

                    $consulta_v = pg_query("select * from detalle_producto_bodega where id_bodega=$pvinv and cod_productos=$arreglo1[$i]");
                    while ($row = pg_fetch_row($consulta_v)) {
                        $cod_pro = $row[1];
                        $id_bod = $row[2];
                        $stock = $row[6];
                    }
                    $cal = $stock - $arreglo2[$i];

                    if ($cod_pro == $arreglo1[$i] && $id_bod == $conpuntoresult) {
                        /*  if ($data == 22) {
                          pg_query("Update detalle_producto_bodega Set fecha='" . $_POST[fecha_actual] . "' ,hora='" . $_POST[hora_actual] . "', "
                          . "stock='" . $cal . "' where cod_productos='" . $arreglo1[$i] . "' and id_bodega='" . $conpuntoresult . "' ");
                          } */
                        /* DESBLOQUEAR COD FRANCISCO pg_query("Update detalle_producto_bodega Set fecha='" . $_POST[fecha_actual] . "' ,hora='" . $_POST[hora_actual] . "', "
                          . "stock='" . $cal . "' where cod_productos='" . $arreglo1[$i] . "' and id_bodega='" . $conpuntoresult . "' "); */
                    } else {
                        $contb = 0;
                        $consulta = pg_query("select max(id_detalle_productos_bodega) from detalle_producto_bodega");
                        while ($row = pg_fetch_row($consulta)) {
                            $contb = $row[0];
                        }
                        $contb++;
                        $horap = date("g:ia");
                        if ($data == 22) {
                            //pg_query("insert into detalle_producto_bodega values('$contb','$arreglo1[$i]','$conpuntoresult','$_SESSION[id]','$_POST[fecha_actual]','$horap','$cal')");
                        }
                        /* DESBLOQUEAR COD FRANCISCO pg_query("insert into detalle_producto_bodega values('$contb','$arreglo1[$i]','$conpuntoresult','$_SESSION[id]',"
                          . "'$_POST[fecha_actual]','$horap','$cal')"); */
                    }
                    // consulta kardex valorizado
                    $cantidad = 0;
                    $precio_total = 0;
                    $precio_unitario = 0;
                    $consulta2 = pg_query("select * from kardex_valorizado where cod_productos = '$arreglo1[$i]' order by id_kardex desc limit 1");
                    while ($row = pg_fetch_row($consulta2)) {
                        $cantidad = $row[11];
                        $precio_unitario = $row[7]; //round($row[7], 4);
                        $precio_total = $row[8]; //round($row[8], 4);
                        $costoVenta = $row[13]; //round($row[13], 4);
                    }
                    if ($costoVenta == "0.0000") {
                        $costoVenta1 = $costoVenta1 + ($arreglo2[$i]);
                    } else {
                        $costoVenta1 = $costoVenta1 + ($costoVenta * $arreglo2[$i]);
                    }

                    //                    procesarKardexValorizadoSalida($arreglo1[$i], $_POST['fecha_actual'], 'F.V:' . $_POST['num_serie'] . '-' . $_POST['num_factura']
                    //                            , $arreglo2[$i], $arreglo3[$i], $stock, 'Activo', '1', 'V', $cont1, NULL, NULL);

                    /* procesarKardexValorizadoSalida($arreglo1[$i], $_POST['fecha_actual'], 'F.V:' . $_POST['num_serie'] . '-' . $_POST['num_factura']
                      , $arreglo2[$i], obtenerValoresPromedios($arreglo1[$i])[0]['venta_promedio'], $stock, 'Activo', '1', 'V', $cont1, NULL, NULL); */

                    //pg_query("insert into kardex_valorizado values(" . $cont_v . ",'" . $arreglo1[$i] . "','$_POST[fecha_actual]', '" . 'Venta: ' . $_POST['num_serie'] . '-' . $_POST['num_factura'] . "','','" . $cantidad_salida . "','" . $cantidad . "','" . $precio_unitario_salida . "','" . $precio_total_salida . "','','','" . $cantidad_total . "','4')");
                    // fin



                    if ($_POST['id_cliente'] == "") {
                        $idCli = 0;
                        $consulta_cli = pg_query("select max(id_cliente) from clientes");
                        while ($row = pg_fetch_row($consulta_cli)) {
                            $idCli = $row[0];
                        }
                        // guardar kardex
                        /* DESBLOQUEAR CODIGO FRANCISCO pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '" . 'F.V:' . $_POST['num_serie'] . '-' . $_POST['num_factura'] . "' ,'" . number_format($arreglo2[$i], 2, '.', '') . "',"
                          . "'" . number_format($arreglo3[$i], 4, '.', '') . "','" . number_format($arreglo5[$i], 4, '.', '') . "','$arreglo1[$i]',"
                          . "'" . number_format($cal, 2, '.', '') . "','Activo'," . NULL . "," . NULL . ",'$idCli','$cont1','V','$conpuntoresult','')"); */

                        /* pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '" . 'F.V:' . $_POST['num_serie'] . '-' . $_POST['num_factura'] . "' ,'" . number_format($arreglo2[$i], 2, '.', '') . "',"
                          . "'" . number_format(obtenerValoresPromedios($arreglo1[$i])[0]['venta_promedio'], 4, '.', '') . "','" . number_format($arreglo5[$i], 4, '.', '') . "','$arreglo1[$i]',"
                          . "'" . number_format($cal, 2, '.', '') . "','Activo'," . NULL . "," . NULL . ",'$idCli','$cont1','V','$conpuntoresult','')"); */

                        /* DESBLOQUEAR CODIGO ESTEBAN procesarKardexSalida($arreglo1[$i], 'F.V:' . $_POST['num_serie'] . '-' . $_POST['num_factura'], $arreglo2[$i], obtenerStock($arreglo1[$i], $_SESSION['PV']), 
                          obtenerValoresPromedios($arreglo1[$i])[0]['venta_promedio'], 'Activo', $conpuntoresult, 'V', $cont1, $arreglo5[$i], NULL, NULL, $idCli,
                          '', NULL, NULL, $_SESSION['id']); */

                        if ($arreglo8[$i] != 0) {
                            $arreglo2[$i] = $arreglo8[$i];
                        } else {
                            $arreglo2[$i] = $arreglo2[$i];
                        }
                        procesarKardexSalida($arreglo1[$i], 'F.V:' . $_POST['num_serie'] . '-' . $_POST['num_factura'], $arreglo2[$i], obtenerStock($arreglo1[$i], $pvinv), NULL, 'Activo', $pvinv, 'V', $cont1, $arreglo5[$i], NULL, NULL, $idCli, '', NULL, NULL, $_SESSION['id']);
                        // fin
                    } else {
                        $cliente1 = $_POST['id_cliente'];
                        pg_query("Update clientes Set  nombres_cli='" . strtoupper($_POST['nombre_cliente']) . "',direccion_cli='" . strtoupper($_POST['direccion_cliente']) . "', telefono='$_POST[telefono_cliente]', correo='$_POST[correo]' where id_cliente='$cliente1'");

                        /* DESBLOQUEAR CODIGO FRANCISCO pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '" . 'F.V:' . $_POST['num_serie'] . '-' . $_POST['num_factura'] . "' ,"
                          . "'" . number_format($arreglo2[$i], 2, '.', '') . "','" . number_format($arreglo3[$i], 4, '.', '') . "',"
                          . "'" . number_format($arreglo5[$i], 4, '.', '') . "','$arreglo1[$i]','" . number_format($cal, 2, '.', '') . "','Activo',"
                          . "" . NULL . "," . NULL . ",'$cliente1','$cont1','V','$conpuntoresult','')"); */

                        /* pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '" . 'F.V:' . $_POST['num_serie'] . '-' . $_POST['num_factura'] . "' ,"
                          . "'" . number_format($arreglo2[$i], 2, '.', '') . "','" . number_format(obtenerValoresPromedios($arreglo1[$i])[0]['venta_promedio'], 4, '.', '') . "',"
                          . "'" . number_format($arreglo5[$i], 4, '.', '') . "','$arreglo1[$i]','" . number_format($cal, 2, '.', '') . "','Activo',"
                          . "" . NULL . "," . NULL . ",'$cliente1','$cont1','V','$conpuntoresult','')"); */

                        /* DESBLOQUEAR CODIGO ESTEBAN procesarKardexSalida($arreglo1[$i], 'F.V:' . $_POST['num_serie'] . '-' . $_POST['num_factura'], $arreglo2[$i], obtenerStock($arreglo1[$i], $_SESSION['PV']), 
                          obtenerValoresPromedios($arreglo1[$i])[0]['venta_promedio'], 'Activo', $conpuntoresult, 'V', $cont1, $arreglo5[$i], NULL, NULL, $_POST['id_cliente'],
                          '', NULL, NULL, $_SESSION['id']); */

                        if ($arreglo8[$i] != 0) {
                            $arreglo2[$i] = $arreglo8[$i];
                        } else {
                            $arreglo2[$i] = $arreglo2[$i];
                        }
                        procesarKardexSalida($arreglo1[$i], 'F.V:' . $_POST['num_serie'] . '-' . $_POST['num_factura'], $arreglo2[$i], obtenerStock($arreglo1[$i], $pvinv), NULL, 'Activo', $pvinv, 'V', $cont1, $arreglo5[$i], NULL, NULL, $_POST['id_cliente'], '', NULL, NULL, $_SESSION['id']);
                    }
                    ////////////////////////
                    //Asiento Contable 
                    $cuenta = pg_query("select iva,id_plan_cuentas from productos where cod_productos ='" . $arreglo1[$i] . "'  and bien_servicios='B'");
                    $plan = pg_fetch_row($cuenta);

                    if ($plan[0] == "Si") {
                        if ($costoVenta == "0.0000") {
                            $inventario12B = $inventario12B + ($arreglo2[$i]);
                        } else {
                            $inventario12B = $inventario12B + ($costoVenta * $arreglo2[$i]);
                        }

                        $sumaSubtotalTarifa12B = $sumaSubtotalTarifa12B + $arreglo5[$i];
                        $codplanTarifa12B = $plan[1];
                        $contTarifa12++;
                    } else if ($plan[0] == "No") {
                        if ($costoVenta == "0.0000") {
                            $inventario0B = $inventario0B + ($arreglo2[$i]);
                        } else {
                            $inventario0B = $inventario0B + ($costoVenta * $arreglo2[$i]);
                        }

                        $sumaSubtotalTarifa0B = $sumaSubtotalTarifa0B + $arreglo5[$i];
                        $codplanTarifa0B = $plan[1];
                        $contTarifa0++;
                    }
                    /////////////////////////
                    //Asiento Contable 
                    $cuenta = pg_query("select iva,id_plan_cuentas from productos where cod_productos ='" . $arreglo1[$i] . "'  and bien_servicios='S'");
                    $plan = pg_fetch_row($cuenta);

                    if ($plan[0] == "Si") {
                        if ($costoVenta == "0.0000") {
                            $inventario12 = $inventario12 + ($arreglo2[$i]);
                        } else {
                            $inventario12 = $inventario12 + ($costoVenta * $arreglo2[$i]);
                        }

                        $sumaSubtotalTarifa12 = $sumaSubtotalTarifa12 + $arreglo5[$i];
                        $codplanTarifa12 = $plan[1];
                        $contTarifa12++;
                    } else if ($plan[0] == "No") {
                        if ($costoVenta == "0.0000") {
                            $inventario0 = $inventario0 + ($arreglo2[$i]);
                        } else {
                            $inventario0 = $inventario0 + ($costoVenta * $arreglo2[$i]);
                        }

                        $sumaSubtotalTarifa0 = $sumaSubtotalTarifa0 + $arreglo5[$i];
                        $codplanTarifa0 = $plan[1];
                        $contTarifa0++;
                    }
                }
                ///////////////////ASIENTO CREDITO //////////////
                // guardar asiento contable
                $idtran = pg_query("select max(id_transacciones) from transacciones");
                $fila = pg_fetch_row($idtran);
                $fila[0] = $fila[0] + 1;
                $sum = 0;
                $bool = true;
                $pos = 0;
                $vec = 0;
                $tieneiva;
                $ivafin = 0;
                $auxiliar = $arreglo1;
                $abc = 0;
                $xy = 0;
                $iva = pg_query("select valor from parametros where descripcion='IVA'");
                while ($ivavalor = pg_fetch_row($iva)) {
                    $ivafin = $ivavalor[0];
                }
                $abc = ($ivafin + 100) / 100;
                while ($bool) {
                    $cuenta = pg_query("select id_plan_cuentas from productos where cod_productos='" . $auxiliar[0] . "'");
                    $plan = pg_fetch_row($cuenta);
                    $nelem = count($auxiliar);
                    $vec = 0;
                    for ($i = 0; $i <= $nelem; $i++) {
                        $cuenta1 = pg_query("select id_plan_cuentas from productos where cod_productos='" . $auxiliar[$i] . "'");
                        $cIva = pg_query("select incluye_iva from productos where cod_productos='" . $auxiliar[$i] . "'");
                        $plan1 = pg_fetch_row($cuenta1);
                        $si = pg_fetch_row($cIva);
                        $tieneiva = $si[0];
                        if ($plan1[$i] == $plan[0]) {
                            if ($tieneiva == "Si") {
                                $sum = ($arreglo5[$i] / $abc) + $sum;
                            } else {
                                //$aa++;
                                $sum = $arreglo5[$i] + $sum;
                            }
                            //$sum=$arreglo5[$i]+$sum;
                        } else {
                            $vec[$pos] = $auxiliar[$i];
                            $pos++;
                        }
                    }
                    if ($vec == 0) {
                        $bool = false;
                    } else {
                        $auxiliar = $vec;
                        $pos = 0;
                    }
                }
                $sum = $sum;
                $xy = $sum + $_POST['iva'];
                $saldo = $xy - $_POST['tot'];
                $cliente1 = "";
                if ($_POST['id_cliente'] == "") {
                    $cliente1 = $contt;
                } else {
                    $cliente1 = $_POST['id_cliente'];
                    pg_query("Update clientes Set  nombres_cli='" . strtoupper($_POST['nombre_cliente']) . "',direccion_cli='" . strtoupper($_POST['direccion_cliente']) . "', telefono='$_POST[telefono_cliente]', correo='$_POST[correo]' where id_cliente='$cliente1'");
                }
                $prove = pg_query("select identificacion from clientes where id_cliente='$cliente1'");
                $p = pg_fetch_row($prove);
                $ing = pg_query("select max(num_transaccion) from transacciones where id_tipo_transaccion='1' and id_empresa= '$_SESSION[PV]'");
                $res = pg_fetch_row($ing);
                $ing_pv = pg_query("select max(id_transaccion_pv::int) from transacciones where  id_empresa= '$_SESSION[PV]'");
                $res_pv = pg_fetch_row($ing_pv);
                //                print_r("trans 1");
                //	 echo '<br>GUARDAR FACTURA VENTA1: <br>' . "insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'VENTA PRODUCTOS, CLIENTE: " . $p[0] . ", COMPROBANTE: " . $_POST['num_factura'] . "', '" . $_POST[tot] . "', '$_POST[tot]', '" . $saldo . "','1','" . ($res[0] + 1) . "','Activo','$cliente1','','','','','VEN','',$conpuntoresult,'$_POST[fecha_actual]')";//////////////////////////
                //	 

                $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'VENTA PRODUCTOS, CLIENTE: " . $p[0] . ", COMPROBANTE: " . $_POST['num_factura'] . ", : " . $_POST['marca_vehiculo'] . "', '" . $_POST["tot"] . "', '$_POST[tot]', '" . $saldo . "','1','" . ($res[0] + 1) . "','Activo','$cliente1','','','','','VEN','',$conpuntoresult,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')");
                $consulta_bien_servi1 = pg_query(" SELECT sum(productos.precio_compra)
                                        FROM detalle_factura_venta,productos where productos.cod_productos=detalle_factura_venta.cod_productos 
                                        and detalle_factura_venta.id_factura_venta='$cont1' and detalle_factura_venta.bien_servicio='B'");
                while ($row = pg_fetch_row($consulta_bien_servi1)) {
                    $valor_Servicio1 = $row[0];
                }
                if ($valor_Servicio1 != "") {

                    if ($inventario12B > 0) {
                        $total0total12B = $inventario12B + $inventario0B;

                        //                        echo '<br>GUARDAR FACTURA VENTAqw: <br>' . "insert into transacciones values('" . ($fila[0] + 1) . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'COSTO VENTA PRODUCTOS, CLIENTE: " . $p[0] . ", COMPROBANTE: " . $_POST['num_factura'] . ", : " . $_POST['marca_vehiculo'] . "', '" . $total0total12B . "', '" . $total0total12B . "', '0.000','1','" . ($res[0] + 1) . "','Activo','$cliente1','','','','','VEN','',$conpuntoresult,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')";

                        //Que no se guarde asiento de costo
                        ///$asiento2 = pg_query("insert into transacciones values('" . ($fila[0] + 1) . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'COSTO VENTA PRODUCTOS, CLIENTE: " . $p[0] . ", COMPROBANTE: " . $_POST['num_factura'] . ", : " . $_POST['marca_vehiculo'] . "', '" . $total0total12B . "', '" . $total0total12B . "', '0.000','1','" . ($res[0] + 1) . "','Activo','$cliente1','','','','','VEN','',$conpuntoresult,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')");
                    } else {
                        $total0total12 = $inventario12B + $inventario0B;
                        //                        echo '<br>GUARDAR FACTURA VENTAui: <br>' . "insert into transacciones values('" . ($fila[0] + 1) . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'COSTO VENTA PRODUCTOS, CLIENTE: " . $p[0] . ", COMPROBANTE: " . $_POST['num_factura'] . ", : " . $_POST['marca_vehiculo'] . "', '" . $inventario0B . "', '" . $inventario0B . "', '0.000','1','" . ($res[0] + 1) . "','Activo','$cliente1','','','','','VEN','',$conpuntoresult,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')"; //////////////////////////

                        //Que no se guarde asiento de costo
                        ///$asiento2 = pg_query("insert into transacciones values('" . ($fila[0] + 1) . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'COSTO VENTA PRODUCTOS, CLIENTE: " . $p[0] . ", COMPROBANTE: " . $_POST['num_factura'] . ", : " . $_POST['marca_vehiculo'] . "', '" . $total0total12 . "', '" . $total0total12 . "', '0.000','1','" . ($res[0] + 1) . "','Activo','$cliente1','','','','','VEN','',$conpuntoresult,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')");
                    }
                }
                /////DETALLES TRANSACCION

                $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
                $fila1 = pg_fetch_row($iddettran);
                $consulta_bien_servi_iva = pg_query("select   sum(detalle_factura_venta.total_venta) from detalle_factura_venta,productos where productos.cod_productos=detalle_factura_venta.cod_productos   and detalle_factura_venta.bien_servicio='B' and detalle_factura_venta.id_factura_venta='$cont1' and productos.iva='Si'");
                while ($row = pg_fetch_row($consulta_bien_servi_iva)) {
                    $valor_Servicio1_iva = $row[0];
                }
                ////////////////////
                //asiento generico Inventario
                //2//
                /*if ($valor_Servicio1_iva != '') {
                    if ($sumaSubtotalTarifa12B > 0) {
                        $fila1[0] = $fila1[0] + 1;
                        $merca = pg_query("select cuenta_debito from parametros where descripcion='VENTA MERCADERIA TARIFA12'");
                        $fila3 = pg_fetch_row($merca);
                        //                    echo '<br>GUARDAR FACTURA VENTA331: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila3[0] . "','0.000','$sumaSubtotalTarifa12B','Activo')"; //////////////////////////

                        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila3[0] . "','0.000','$valor_Servicio1_iva','Activo')");
                    }
                }
                $consulta_bien_servi_ivas = pg_query("select   sum(detalle_factura_venta.total_venta) from detalle_factura_venta,productos where productos.cod_productos=detalle_factura_venta.cod_productos   and detalle_factura_venta.bien_servicio='S' and detalle_factura_venta.id_factura_venta='$cont1' and productos.iva='Si'");
                while ($row = pg_fetch_row($consulta_bien_servi_ivas)) {
                    $valor_Servicio1_ivas = $row[0];
                }
                $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
                $fila1 = pg_fetch_row($iddettran);
                if ($valor_Servicio1_ivas != '') {

                    if ($sumaSubtotalTarifa12 > 0) {
                        $fila1[0] = $fila1[0] + 1;
                        $merca = pg_query("select cuenta_debito from parametros where descripcion='VENTA MERCADERIA TARIFA12'");
                        $fila3 = pg_fetch_row($merca);
                        //                    echo '<br>GUARDAR FACTURA VENTA3332: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa12','0.000','$sumaSubtotalTarifa12','Activo')"; //////////////////////////

                        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa12','0.000','$valor_Servicio1_ivas','Activo')");
                    }
                }
                $consulta_bien_servi_iva_no = pg_query("select   sum(detalle_factura_venta.total_venta) from detalle_factura_venta,productos where productos.cod_productos=detalle_factura_venta.cod_productos   and detalle_factura_venta.bien_servicio='B' and detalle_factura_venta.id_factura_venta='$cont1' and productos.iva='No'");
                while ($row = pg_fetch_row($consulta_bien_servi_iva_no)) {
                    $valor_Servicio1_iva_no = $row[0];
                }
                //                    print_r($inventario12B);
                if ($valor_Servicio1_iva_no != '') {
                    if ($sumaSubtotalTarifa0B > 0) {
                        $fila1[0] = $fila1[0] + 1;
                        $merca = pg_query("select cuenta_debito from parametros where descripcion='VENTA MERCADERIA TARIFA0'");
                        $fila3 = pg_fetch_row($merca);
                        //                    echo '<br>GUARDAR FACTURA VENTA3333: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila3[0] . "','0.000','$sumaSubtotalTarifa0','Activo')"; //////////////////////////

                        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila3[0] . "','0.000','$valor_Servicio1_iva_no','Activo')");
                    }
                }
                if ($sumaSubtotalTarifa0 > 0) {
                    $fila1[0] = $fila1[0] + 1;
                    $merca = pg_query("select cuenta_debito from parametros where descripcion='VENTA MERCADERIA TARIFA0'");
                    $fila3 = pg_fetch_row($merca);
                    //                    echo '<br>GUARDAR FACTURA VENTA334: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa0','0.000','$sumaSubtotalTarifa0','Activo')"; //////////////////////////

                    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa0','0.000','$sumaSubtotalTarifa0','Activo')");
                }
                ///////////////////

                $planiva = pg_query("select cuenta_credito from parametros where descripcion='IVA'");
                $fila2 = pg_fetch_row($planiva);
                //                echo $_POST['iva'].'<BR>';
                if ($_POST['iva'] != '0') {
                    $fila1[0] = $fila1[0] + 1;
                    //                    echo '<br>GUARDAR FACTURA VENTA3345: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','0.000','" . $_POST['iva'] . "','Activo')"; //////////////////////////

                    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','0.000','" . $_POST['iva'] . "','Activo')");
                }*/

                if ($data == 22) {
                    registrarCuentasIvaVentasTransaccion($cont1, $fila[0]);
                    registrarCuentasVentasTransaccion($cont1, $fila[0]);
                }
                //Añadir Anticipo
                ////////FACTURA
                $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
                $fila1 = pg_fetch_row($iddettran);
                $consulta_mixto = pg_query("select sum(x.sum) from (select formas_pago_mixto.forma_pago,sum(formas_pago_mixto.valor) from factura_venta, formas_pago_mixto 
                                    where factura_venta.id_factura_venta=formas_pago_mixto.id_factura_venta and factura_venta.id_factura_venta='$cont1' 
                                    and (formas_pago_mixto.forma_pago='CHEQUE'  or formas_pago_mixto.forma_pago='CONTADO')  and formas_pago_mixto.tipo_documento='FACTURA' GROUP BY formas_pago_mixto.forma_pago
                                    )x");
                $valor_contado_cheque = "";
                while ($row = pg_fetch_row($consulta_mixto)) {
                    //                    $cont2_mixto_contado = $row[0];
                    $valor_contado_cheque = $row[0];
                }
                if ($valor_contado_cheque != "") {

                    $sql = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
                    $buscaCuenta = pg_fetch_row($sql);
                    $fila1[0] = $fila1[0] + 1;
                    //                    echo '<br>GUARDAR FACTURA VENTA3345HH: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','" . $valor_contado . "','0.000','Activo')"; //////////////////////////

                    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','" . $valor_contado_cheque . "','0.000','Activo')");
                }
                //                $consulta_mixto = pg_query("select formas_pago_mixto.forma_pago,formas_pago_mixto.valor from factura_venta, formas_pago_mixto where factura_venta.id_factura_venta=formas_pago_mixto.id_factura_venta and factura_venta.id_factura_venta='$cont1' and formas_pago_mixto.forma_pago='CHEQUE'");
                //                while ($row = pg_fetch_row($consulta_mixto)) {
                //                    $cont2_mixto_cheque = $row[0];
                //                    $valor_contado = $row[1];
                //                }
                //                if ($cont2_mixto_cheque != "") {
                //
                //                    $sql = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
                //                    $buscaCuenta = pg_fetch_row($sql);
                //                    $fila1[0] = $fila1[0] + 1;
                //                echo '<br>GUARDAR FACTURA VENTA3345: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','" . $valor_contado . "','0.000','Activo')"; //////////////////////////
                //
                //                    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','" . $valor_contado . "','0.000','Activo')");
                //                }
                /////////FACTURA
                $consulta_mixto = pg_query("select formas_pago_mixto.forma_pago,formas_pago_mixto.valor from factura_venta, formas_pago_mixto where factura_venta.id_factura_venta=formas_pago_mixto.id_factura_venta and factura_venta.id_factura_venta='$cont1' and formas_pago_mixto.forma_pago='TCREDITO'  and formas_pago_mixto.tipo_documento='FACTURA'");
                while ($row = pg_fetch_row($consulta_mixto)) {
                    $cont2_mixto_tcredito = $row[0];
                    $valor_tcredito = $row[1];
                }
                if ($cont2_mixto_tcredito != "") {

                    $sql = pg_query("select cuenta_debito from parametros where descripcion='TARJETA DE CREDITO'");
                    $buscaCuenta = pg_fetch_row($sql);
                    $fila1[0] = $fila1[0] + 1;
                    //                    echo '<br>GUARDAR FACTURA VENTA3345j: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','" . $valor_contado . "','0.000','Activo')"; //////////////////////////

                    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','" . $valor_tcredito . "','0.000','Activo')");
                }
                $consulta_mixto = pg_query("select formas_pago_mixto.forma_pago,formas_pago_mixto.valor,formas_pago_mixto.id_cuenta from factura_venta, formas_pago_mixto where factura_venta.id_factura_venta=formas_pago_mixto.id_factura_venta and factura_venta.id_factura_venta='$cont1' and formas_pago_mixto.forma_pago='TRANSFERENCIAS'");
                while ($row = pg_fetch_row($consulta_mixto)) {
                    $cont2_mixto_transferencias = $row[0];
                    $valor_transferencias = $row[1];
                    $id_cuenta_banco = $row[2];
                }
                if ($cont2_mixto_transferencias != "") {

                    $sql = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
                    $buscaCuenta = pg_fetch_row($sql);
                    $fila1[0] = $fila1[0] + 1;
                    //                    echo '<br>GUARDAR FACTURA VENTA3345: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$_POST[cuenta_cheque]','" . $valor_contado . "','0.000','Activo')"; //////////////////////////

                    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$id_cuenta_banco','" . $valor_transferencias . "','0.000','Activo')");
                }
                $consulta_mixto = pg_query("select formas_pago_mixto.forma_pago,formas_pago_mixto.valor from factura_venta, formas_pago_mixto where factura_venta.id_factura_venta=formas_pago_mixto.id_factura_venta and factura_venta.id_factura_venta='$cont1' and formas_pago_mixto.forma_pago='CREDITO'  and formas_pago_mixto.tipo_documento='FACTURA'");
                while ($row = pg_fetch_row($consulta_mixto)) {
                    $cont2_mixto_credito = $row[0];
                    $valor_credito = $row[1];
                }
                if ($cont2_mixto_credito != "") {

                    $fila1[0] = $fila1[0] + 1;
                    $plancaja4 = pg_query("select cuenta_debito from parametros where descripcion='CUENTAS POR COBRAR'");
                    $fila4 = pg_fetch_row($plancaja4);
                    $totalCuentaXCobrar = $_POST['tot'];

                    //                    echo '<br>GUARDAR FACTURA VENTA33456: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila4[0] . "','" . $valor_contado . "','0.000','Activo')"; //////////////////////////

                    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila4[0] . "','" . $valor_credito . "','0.000','Activo')");
                }
                /////////////////////CHEQUE POSFECHADO////////////////////////////////////////////////////
                //                    echo '<br>GUARDAR FACTURA POST: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$_POST[cuenta_cheque]','" . $valor_contado . "','0.000','Activo')"; //////////////////////////


                $consulta_mixto = pg_query("select formas_pago_mixto.forma_pago,formas_pago_mixto.valor from factura_venta, formas_pago_mixto where factura_venta.id_factura_venta=formas_pago_mixto.id_factura_venta and factura_venta.id_factura_venta='$cont1' and formas_pago_mixto.forma_pago='CPOSFECHADO'  and formas_pago_mixto.tipo_documento='FACTURA'");
                while ($row = pg_fetch_row($consulta_mixto)) {
                    $cont2_mixto_pos = $row[0];
                    $valor_credito_post = $row[1];
                }
                if ($cont2_mixto_pos != "") {

                    $fila1[0] = $fila1[0] + 1;
                    $plancaja4 = pg_query("select cuenta_debito from parametros where descripcion='CUENTAS POR COBRAR'");
                    $fila4 = pg_fetch_row($plancaja4);
                    $totalCuentaXCobrar = $_POST['tot'];

                    //                    echo '<br>GUARDAR FACTURA VENTA33456: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila4[0] . "','" . $valor_contado . "','0.000','Activo')"; //////////////////////////

                    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila4[0] . "','" . $valor_credito_post . "','0.000','Activo')");
                }

                $consulta_mixto = pg_query("select formas_pago_mixto.forma_pago,formas_pago_mixto.valor from factura_venta, formas_pago_mixto where factura_venta.id_factura_venta=formas_pago_mixto.id_factura_venta and factura_venta.id_factura_venta='$cont1' and formas_pago_mixto.forma_pago='NOTA_CREDITO'  and formas_pago_mixto.tipo_documento='FACTURA'");
                while ($row = pg_fetch_row($consulta_mixto)) {
                    $cont2_mixto_ncredito = $row[0];
                    $valor_tcredito = $row[1];
                }
                if ($cont2_mixto_ncredito != "") {

                    $sql = pg_query("select cuenta_credito from parametros where descripcion='NC CLIENTES'");
                    $buscaCuenta = pg_fetch_row($sql);
                    $fila1[0] = $fila1[0] + 1;
                    //                    echo '<br>GUARDAR FACTURA VENTA3345j: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','" . $valor_contado . "','0.000','Activo')"; //////////////////////////

                    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','" . $valor_tcredito . "','0.000','Activo')");
                }
                ///////////////////////////////////////////////////
                //////////////////////////////CUPO////////////////////
                /////////////////////////////////////////////////////
                $consulta_mixto = pg_query("select formas_pago_mixto.forma_pago,formas_pago_mixto.valor from factura_venta, formas_pago_mixto where factura_venta.id_factura_venta=formas_pago_mixto.id_factura_venta and factura_venta.id_factura_venta='$cont1' and formas_pago_mixto.forma_pago='CUPON'  and formas_pago_mixto.tipo_documento='FACTURA'");
                while ($row = pg_fetch_row($consulta_mixto)) {
                    $cont2_mixto_cupo = $row[0];
                    $valor_cupon = $row[1];
                }
                if ($cont2_mixto_cupo != "") {

                    $sql = pg_query("select cuenta_debito from parametros where descripcion='CUPONES'");
                    $buscaCuenta = pg_fetch_row($sql);
                    $fila1[0] = $fila1[0] + 1;
                    //                    echo '<br>GUARDAR FACTURA VENTA3345j: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','" . $valor_contado . "','0.000','Activo')"; //////////////////////////

                    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','" . $valor_cupon . "','0.000','Activo')");
                }

                //                    $fila1[0] = $fila1[0] + 1;
                //                    $plancaja4 = pg_query("select cuenta_debito from parametros where descripcion='CUENTAS POR COBRAR'");
                //                    $fila4 = pg_fetch_row($plancaja4);
                //                    $totalCuentaXCobrar = $_POST['tot'];
                //                    echo '<br>GUARDAR FACTURA VENTA33456: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila4[0] . "','" . $totalCuentaXCobrar . "','0.000','Activo')"; //////////////////////////
                //
                //                    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$_POST[cuenta_cheque]','" . $totalCuentaXCobrar . "','0.000','Activo')");
                //detalle costo de ventas
                //////COSTO VENTA FACTURA  /////////////////
                $plancaja4 = pg_query("select cuenta_debito from parametros where descripcion='COSTO VENTA'");
                $fila4 = pg_fetch_row($plancaja4);
                $fila1[0] = $fila1[0] + 1;

                $consulta_bien_servi1 = pg_query(" SELECT sum(productos.precio_compra)
                        FROM detalle_factura_venta,productos where productos.cod_productos=detalle_factura_venta.cod_productos 
                        and detalle_factura_venta.id_factura_venta='$cont1' and detalle_factura_venta.bien_servicio='B'");
                while ($row = pg_fetch_row($consulta_bien_servi1)) {
                    $valor_Servicio1 = $row[0];
                }
                if ($valor_Servicio1 != '') {
                    if ($inventario12B > 0) {
                        $total0total12B = $inventario12B + $inventario0B;
                        //                        echo '<br>GUARDAR FACTURA VENTA677: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $fila4[0] . "','" .  $inventario12B . "','0.000','Activo')"; //////////////////////////

                        //Que no guarde asiento de costo
                        //pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $fila4[0] . "','" . $total0total12B . "','0.000','Activo')");

                        $plancaja44 = pg_query("select cuenta_debito from parametros where descripcion='BIENES'");
                        $fila44 = pg_fetch_row($plancaja44);


                        $fila1[0] = $fila1[0] + 1;

                        //                            echo '<br>GUARDAR FACTURA VENTA6f1: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $codplanTarifa12B . "','0.000','" .  $total0total12B . "','Activo')"; //////////////////////////

                        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $codplanTarifa12B . "','0.000','" . $total0total12B . "','Activo')");
                    } else if ($inventario0B > 0) {
                        $total0total12 = $inventario12B + $inventario0B;
                        //                       echo '<br>GUARDAR FACTURA VENTA44OTR: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $fila4[0] . "','" . $total0total12 . "','0.000','Activo')"; //////////////////////////

                        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $fila4[0] . "','" . $total0total12 . "','0.000','Activo')");
                        $plancaja44 = pg_query("select cuenta_debito from parametros where descripcion='BIENES'");
                        $fila44 = pg_fetch_row($plancaja44);


                        $fila1[0] = $fila1[0] + 1;
                        //                                echo '<br>GUARDAR FACTURA VENTA6f: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $fila44[0] . "','0.000','" . $inventario12B . "','Activo')"; //////////////////////////
                        //
                        //                                pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $fila44[0] . "','0.000','" . $inventario12B . "','Activo')");
                        //                            echo '<br>GUARDAR FACTURA VENTA6f25OTR: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','$codplanTarifa0B','0.000','" . $total0total12 . "','Activo')"; //////////////////////////

                        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','$codplanTarifa0B','0.000','" . $total0total12 . "','Activo')");
                    }
                }
                //asiento generico Inventario
                //                if ($inventario0 > 0) {
                //                    $fila1[0] = $fila1[0] + 1;
                //                    echo '<br>GUARDAR FACTURA VENTA67: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $codplanTarifa0 . "','0.000','" . $inventario0 . "','Activo')"; //////////////////////////
                //
                //                    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $codplanTarifa0 . "','0.000','" . $inventario0 . "','Activo')");
                //                }
                //                if ($inventario0B > 0) {
                //                    $fila1[0] = $fila1[0] + 1;
                //                    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $codplanTarifa0B . "','0.000','" . $inventario0B . "','Activo')");
                //                }
                //                if ($inventario12B > 0) {
                //                    $fila1[0] = $fila1[0] + 1;
                //
                //                echo '<br>GUARDAR FACTURA VENTA68: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $codplanTarifa12B . "','0.000','" . $inventario12B . "','Activo')"; //////////////////////////
                //
                //                    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $codplanTarifa12B . "','0.000','" . $inventario12B . "','Activo')");
                //                }
                //                if ($inventario12 > 0) {
                //                    $fila1[0] = $fila1[0] + 1;
                //
                ////                   echo '<br>GUARDAR FACTURA VENTA79: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $codplanTarifa12 . "','0.000','" . $inventario12 . "','Activo')"; //////////////////////////
                //
                //                    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $codplanTarifa12 . "','0.000','" . $inventario12 . "','Activo')");
                //                }
                ///////////////////
                ///////////////////
            } else {
                //               print_r($forma);
                if ($forma == "Contado") {
                    for ($i = 1; $i < $nelem; $i++) {
                        if (!empty($arreglo1[$i])) {
                            /* echo '<br>I: ' . $i; */
                            //                              echo '<br>ARREGLO1[$i]: ' . $arreglo1[$i]; 
                            //                            $sql = "select id_timpu from productos where cod_productos = $arreglo1[$i]";
                            //echo '<br>CONSULTA ID TIMPU: <br>' . $sql;
                            $consultaImpu_pro = pg_query("select id_timpu from productos where cod_productos = '$arreglo1[$i]'");
                            while ($row = pg_fetch_row($consultaImpu_pro)) {
                                $idIva = $row[0];
                            }
                            // contador detalle factura venta
                            $cont6 = 0;
                            $consulta = pg_query("select  max(id_detalle_venta) from detalle_factura_venta");
                            while ($row = pg_fetch_row($consulta)) {
                                $cont6 = $row[0];
                            }
                            $cont6++;
                            // fin  
                            // contador kardex
                            $cont_k = 0;
                            $consulta_k = pg_query("select max(id_kardex) from kardex");
                            while ($row = pg_fetch_row($consulta_k)) {
                                $cont_k = $row[0];
                            }
                            $cont_k++;
                            // fin
                            // contador kardex valorizado
                            $cont_v = 0;
                            $consulta_v = pg_query("select max(id_kardex) from kardex_valorizado");
                            while ($row = pg_fetch_row($consulta_v)) {
                                $cont_v = $row[0];
                            }
                            $cont_v++;

                            $consulta_bien_servi = pg_query(" select bien_servicios from productos where cod_productos=$arreglo1[$i]");
                            while ($row = pg_fetch_row($consulta_bien_servi)) {
                                $valor_Servicio = $row[0];
                            }
                            if ($arreglo6[$i] == "") {
                                $arreglo6[$i] = 0;
                            } else {
                                $arreglo6[$i] = $arreglo6[$i];
                            }
                            //                            print_r("valordata::" . $data);
                            if ($data == 22) {

                                //                                echo 'detalle_factura_venta1:' . "insert into detalle_factura_venta values('$cont6','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]',"
                                //                                . "'$arreglo4[$i]','$arreglo5[$i]','Activo','$arreglo6[$i]','$_POST[fecha_actual]','$valor_Servicio')";

                                $sql = "insert into detalle_factura_venta 
                                (
                                    id_detalle_venta, id_factura_venta, cod_productos, cantidad, 
                                    precio_venta, descuento_producto, total_venta, estado, pendientes, 
                                    fecha_venta, bien_servicio, cantidad_unidad, unidad_medida, detalle_producto)
                                values('$cont6','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]',"
                                    . "'$arreglo4[$i]','$arreglo5[$i]','Activo','$arreglo6[$i]','$_POST[fecha_actual]','$valor_Servicio','$arreglo8[$i]','$arreglo9[$i]','$arreglo10[$i]')";
                                $guardar = guardarSql($conexion, $sql);
                                if ($guardar == 'true') {
                                    $data = 22;
                                    guardarDetalleImpuestoProducto($arreglocods_impuesto[$i], $arreglocods_tarifa[$i], $arreglotarifas[$i], $arreglovlores_iva[$i], $arreglo5[$i], $cont6);
                                } else {
                                    error_log_fv(0, "id_factura=$cont1", "guardar_factura_venta.php", 343);
                                    error_log_fv(0, pg_last_error($conexion), "guardar_factura_venta.php", 360);
                                    error_log_fv(0, pg_last_error($guardar), "guardar_factura_venta.php", 360);

                                    //                                    echo '<br>GUARDAR FACTURA DETALLE: <br>' . "insert into detalle_factura_venta values('$cont6','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]',"
                                    //                                    . "'$arreglo4[$i]','$arreglo5[$i]','Activo','$arreglo6[$i]','$_POST[fecha_actual]','$valor_Servicio')"; 
                                    pg_query("DELETE FROM factura_venta WHERE id_factura_venta='$cont1';");
                                    //                                    echo '<br>GUARDAR FACTURA delete: <br>' . "DELETE FROM factura_venta WHERE id_factura_venta='$cont1';";
                                    $data = 60; /// error al guardar
                                    $item = array('estado' => $data);
                                } // fin
                            }


                            // fin

                            $contb = 0;
                            $consulta = pg_query("select max(id_detalle_productos_bodega) from detalle_producto_bodega");
                            while ($row = pg_fetch_row($consulta)) {
                                $contb = $row[0];
                            }
                            $contb++;

                            // guardar detalle productos bodega
                            $consulta_v = pg_query("select * from detalle_producto_bodega where id_bodega='$pvinv' and cod_productos='$arreglo1[$i]'");

                            while ($row = pg_fetch_row($consulta_v)) {
                                $cod_pro = $row[1];
                                $id_bod = $row[2];
                                $stock = $row[6];
                            }

                            $cal = $stock - $arreglo2[$i];

                            if ($cod_pro == $arreglo1[$i] && $id_bod == $conpuntoresult) {
                                /* pg_query("Update detalle_producto_bodega Set fecha='" . $_POST['fecha_actual'] . "' ,hora='" . $_POST['hora_actual'] . "', "
                                  . "stock='" . $cal . "' where cod_productos='" . $arreglo1[$i] . "' and id_bodega='" . $conpuntoresult . "' "); */
                            } else {
                                $contb = 0;
                                $consulta = pg_query("select max(id_detalle_productos_bodega) from detalle_producto_bodega");
                                while ($row = pg_fetch_row($consulta)) {
                                    $contb = $row[0];
                                }
                                $contb++;
                                $horap = date("g:ia");
                                /* pg_query("insert into detalle_producto_bodega values('$contb','$arreglo1[$i]','$conpuntoresult','$_SESSION[id]','$_POST[fecha_actual]',"
                                  . "'$horap','$cal')"); */
                            }
                            // consulta kardex valorizado
                            $cantidad = 0;
                            $precio_total = 0;
                            $precio_unitario = 0;
                            $costoVenta = 0;
                            $consulta2 = pg_query("select * from kardex_valorizado where cod_productos = '$arreglo1[$i]' order by id_kardex desc limit 1");
                            while ($row = pg_fetch_row($consulta2)) {
                                $cantidad = $row[11];
                                $precio_unitario = $row[7]; //round($row[7], 4);
                                $precio_total = $row[8]; //round($row[8], 4);
                                $costoVenta = $row[13]; //round($row[13], 4);
                            }
                            if ($costoVenta == "0.0000") {
                                $costoVenta1 = $costoVenta1 + ($arreglo2[$i]);
                            } else {
                                $costoVenta1 = $costoVenta1 + ($costoVenta * $arreglo2[$i]);
                            }


                            //en 'Activo iba 4'
                            /* DESBLOQUEAR CODIGO FRANCISCO procesarKardexValorizadoSalida($arreglo1[$i], $_POST['fecha_actual'], 'F.V:' . $_POST['num_serie'] . '-' . $_POST['num_factura']
                              , $arreglo2[$i], $arreglo3[$i], $stock, 'Activo', '1', 'V', $cont1, NULL, NULL); */

                            /* procesarKardexValorizadoSalida($arreglo1[$i], $_POST['fecha_actual'], 'F.V:' . $_POST['num_serie'] . '-' . $_POST['num_factura']
                              , $arreglo2[$i], obtenerValoresPromedios($arreglo1[$i])[0]['venta_promedio'], $stock, 'Activo', '1', 'V', $cont1, NULL, NULL); */

                            // fin
                            if ($_POST['id_cliente'] == "") {
                                $idCli = 0;
                                $consulta_cli = pg_query("select max(id_cliente) from clientes");
                                while ($row = pg_fetch_row($consulta_cli)) {
                                    $idCli = $row[0];
                                }
                                // guardar kardex en 'Activo iba 2'
                                /* DESBLOQUEAR CODIGO FRANCISCO pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '" . 'F.V:' . $_POST['num_serie'] . '-' . $_POST['num_factura'] . "' ,"
                                  . "'" . number_format($arreglo2[$i], 2, '.', '') . "','" . number_format($arreglo3[$i], 4, '.', '') . "',"
                                  . "'" . number_format($arreglo5[$i], 4, '.', '') . "','$arreglo1[$i]','" . number_format($cal, 4, '.', '') . "',"
                                  . "'Activo',NULL,NULL,'$idCli','$cont1','V','$conpuntoresult','')"); */

                                /* pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '" . 'F.V:' . $_POST['num_serie'] . '-' . $_POST['num_factura'] . "' ,"
                                  . "'" . number_format($arreglo2[$i], 2, '.', '') . "','" . number_format(obtenerValoresPromedios($arreglo1[$i])[0]['venta_promedio'], 4, '.', '') . "',"
                                  . "'" . number_format($arreglo5[$i], 4, '.', '') . "','$arreglo1[$i]','" . number_format($cal, 4, '.', '') . "',"
                                  . "'Activo',NULL,NULL,'$idCli','$cont1','V','$conpuntoresult','')"); */

                                /* DESBLOQUEAR CODIGO ESTEBAN procesarKardexSalida($arreglo1[$i], 'F.V:' . $_POST['num_serie'] . '-' . $_POST['num_factura'], $arreglo2[$i], obtenerStock($arreglo1[$i], 
                                  $_SESSION['PV']), obtenerValoresPromedios($arreglo1[$i])[0]['venta_promedio'], 'Activo', $conpuntoresult, 'V',
                                  $cont1, $arreglo5[$i], NULL, NULL, $idCli, '', NULL, NULL, $_SESSION['id']); */
                                if ($data == 22) {
                                    if ($arreglo8[$i] != 0) {
                                        $arreglo2[$i] = $arreglo8[$i];
                                    } else {
                                        $arreglo2[$i] = $arreglo2[$i];
                                    }
                                    procesarKardexSalida($arreglo1[$i], 'F.V:' . $_POST['num_serie'] . '-' . $_POST['num_factura'], $arreglo2[$i], obtenerStock($arreglo1[$i], $pvinv), NULL, 'Activo', $pvinv, 'V', $cont1, $arreglo5[$i], NULL, NULL, $idCli, '', NULL, NULL, $_SESSION['id']);
                                }
                                // fin
                            } else {
                                $cliente1 = $_POST['id_cliente'];
                                pg_query("Update clientes Set  nombres_cli='" . strtoupper($_POST['nombre_cliente']) . "',direccion_cli='" . strtoupper($_POST['direccion_cliente']) . "', telefono='$_POST[telefono_cliente]', correo='$_POST[correo]' where id_cliente='$cliente1'");

                                /* DESBLOQUEAR CODIGO FRANCISCO pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '" . 'F.V:' . $_POST['num_serie'] . '-' . $_POST['num_factura'] . "' ,"
                                  . "'" . number_format($arreglo2[$i], 2, '.', '') . "','" . number_format($arreglo3[$i], 4, '.', '') . "',"
                                  . "'" . number_format($arreglo5[$i], 4, '.', '') . "','$arreglo1[$i]','" . number_format($cal, 4, '.', '') . "',"
                                  . "'Activo', NULL , NULL,'$cliente1','$cont1','V','$conpuntoresult','')"); */

                                /* pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '" . 'F.V:' . $_POST['num_serie'] . '-' . $_POST['num_factura'] . "' ,"
                                  . "'" . number_format($arreglo2[$i], 2, '.', '') . "','" . number_format(obtenerValoresPromedios($arreglo1[$i])[0]['venta_promedio'], 4, '.', '') . "',"
                                  . "'" . number_format($arreglo5[$i], 4, '.', '') . "','$arreglo1[$i]','" . number_format($cal, 4, '.', '') . "',"
                                  . "'Activo', NULL , NULL,'$cliente1','$cont1','V','$conpuntoresult','')"); */

                                /* DESBLOQUEAR CODIGO ESTEBAN procesarKardexSalida($arreglo1[$i], 'F.V:' . $_POST['num_serie'] . '-' . $_POST['num_factura'], $arreglo2[$i], obtenerStock($arreglo1[$i], 
                                  $_SESSION['PV']), obtenerValoresPromedios($arreglo1[$i])[0]['venta_promedio'], 'Activo', $conpuntoresult, 'V',
                                  $cont1, $arreglo5[$i], NULL, NULL, $_POST['id_cliente'], '', NULL, NULL, $_SESSION['id']); */
                                if ($data == 22) {
                                    if ($arreglo8[$i] != 0) {
                                        $arreglo2[$i] = $arreglo8[$i];
                                    } else {
                                        $arreglo2[$i] = $arreglo2[$i];
                                    }
                                    procesarKardexSalida($arreglo1[$i], 'F.V:' . $_POST['num_serie'] . '-' . $_POST['num_factura'], $arreglo2[$i], obtenerStock($arreglo1[$i], $pvinv), NULL, 'Activo', $pvinv, 'V', $cont1, $arreglo5[$i], NULL, NULL, $_POST['id_cliente'], '', NULL, NULL, $_SESSION['id']);
                                }
                            }

                            ////////////////////////
                            //Asiento Contable 
                            //                            echo '<br>GUARDAR FACTURA VENTA: <br>' . "select iva,id_plan_cuentas from productos where cod_productos ='" . $arreglo1[$i] . "' and bien_servicios='B'"; //////////////////////////

                            $cuenta = pg_query("select iva,id_plan_cuentas from productos where cod_productos ='" . $arreglo1[$i] . "' and bien_servicios='B'");
                            $plan = pg_fetch_row($cuenta);

                            if ($plan[0] == "Si") {
                                if ($costoVenta == "0.0000") {
                                    $inventario12B = $inventario12B + ($arreglo2[$i]);
                                } else {
                                    $inventario12B = $inventario12B + ($costoVenta * $arreglo2[$i]);
                                }


                                $sumaSubtotalTarifa12B = $sumaSubtotalTarifa12B + $arreglo5[$i];
                                $codplanTarifa12B = $plan[1];
                                $contTarifa12++;
                            } else if ($plan[0] == "No") {
                                if ($costoVenta == "0.0000") {
                                    $inventario0B = $inventario0B + ($arreglo2[$i]);
                                } else {
                                    $inventario0B = $inventario0B + ($costoVenta * $arreglo2[$i]);
                                }


                                $sumaSubtotalTarifa0B = $sumaSubtotalTarifa0B + $arreglo5[$i];
                                $codplanTarifa0B = $plan[1];
                                $contTarifa0++;
                            }
                            //Asiento Contable 
                            $cuenta = pg_query("select iva,id_plan_cuentas from productos where cod_productos ='" . $arreglo1[$i] . "' and bien_servicios='S'");
                            $plan = pg_fetch_row($cuenta);

                            if ($plan[0] == "Si") {
                                if ($costoVenta == "0.0000") {
                                    $inventario12 = $inventario12 + ($arreglo2[$i]);
                                } else {
                                    $inventario12 = $inventario12 + ($costoVenta * $arreglo2[$i]);
                                }

                                $sumaSubtotalTarifa12 = $sumaSubtotalIva12 + $arreglo5[$i];
                                $codplanTarifa12 = $plan[1];
                                $contTarifa12++;
                            } else if ($plan[0] == "No") {
                                if ($costoVenta == "0.0000") {
                                    $inventario0 = $inventario0 + ($arreglo2[$i]);
                                } else {
                                    $inventario0 = $inventario0 + ($costoVenta * $arreglo2[$i]);
                                }

                                $sumaSubtotalTarifa0 = $sumaSubtotalTarifa0 + $arreglo5[$i];
                                $codplanTarifa0 = $plan[1];
                                $contTarifa0++;
                            }




                            /////////////////////////
                            //                            $bien_servicio = pg_query("SELECT  bien_servicios FROM productos where cod_productos='" . $arreglo1[$i] . "'");
                            //                            $bien_servicioid = pg_fetch_row($bien_servicio);
                            //                            $bien_serviciob = $bien_servicioid[0];
                            //                    print_r($bien_serviciob."gg");
                        }
                    }
                    ////////////////////////////CREACION ASIENTO CONTADO - CHEQUE
                    // guardar asiento contable

                    $idtran = pg_query("select max(id_transacciones) from transacciones");
                    $fila = pg_fetch_row($idtran);
                    $fila[0] = $fila[0] + 1;
                    $sum = 0;
                    $bool = true;
                    $pos = 0;
                    $vec = 0;
                    $tieneiva;
                    $ivafin = 0;
                    $auxiliar = $arreglo1;
                    $abc = 0;
                    $xy = 0;
                    $iva = pg_query("select valor from parametros where descripcion='IVA'");
                    while ($ivavalor = pg_fetch_row($iva)) {
                        $ivafin = $ivavalor[0];
                    }
                    $abc = ($ivafin + 100) / 100;
                    while ($bool) {
                        $cuenta = pg_query("select id_plan_cuentas from productos where cod_productos='" . $auxiliar[0] . "'");
                        $plan = pg_fetch_row($cuenta);
                        $nelem = count($auxiliar);
                        $vec = 0;
                        for ($i = 0; $i <= $nelem; $i++) {
                            $cuenta1 = pg_query("select id_plan_cuentas from productos where cod_productos='" . $auxiliar[$i] . "'");
                            $cIva = pg_query("select incluye_iva from productos where cod_productos='" . $auxiliar[$i] . "'");
                            $plan1 = pg_fetch_row($cuenta1);
                            $si = pg_fetch_row($cIva);
                            $tieneiva = $si[0];
                            if ($plan1[$i] == $plan[0]) {
                                if ($tieneiva == "Si") {
                                    $sum = ($arreglo5[$i] / $abc) + $sum;
                                } else {
                                    //$aa++;
                                    $sum = $arreglo5[$i] + $sum;
                                }
                                //$sum=$arreglo5[$i]+$sum;
                            } else {
                                $vec[$pos] = $auxiliar[$i];
                                $pos++;
                            }
                            //                            $bien_servicio = pg_query("SELECT  bien_servicios FROM productos where cod_productos='" . $arreglo1[$i] . "'");
                            //                    $bien_servicioid = pg_fetch_row($bien_servicio);
                            //                    $bien_serviciob = $bien_servicioid[0];
                            //                     print_r($bien_serviciob."ll");
                        }
                        if ($vec == 0) {
                            $bool = false;
                        } else {
                            $auxiliar = $vec;
                            $pos = 0;
                        }
                    }
                    $sum = $sum;
                    $xy = $sum + $_POST['iva'];
                    $saldo = $xy - $_POST['tot'];
                    $cliente1 = "";
                    if ($_POST['id_cliente'] == "") {
                        $cliente1 = $contt;
                    } else {
                        $cliente1 = $_POST['id_cliente'];
                        pg_query("Update clientes Set  nombres_cli='" . strtoupper($_POST['nombre_cliente']) . "',direccion_cli='" . strtoupper($_POST['direccion_cliente']) . "', telefono='$_POST[telefono_cliente]', correo='$_POST[correo]' where id_cliente='$cliente1'");
                    }
                    $prove = pg_query("select identificacion from clientes where id_cliente='$cliente1'");
                    $p = pg_fetch_row($prove);
                    $ing = pg_query("select max(num_transaccion) from transacciones where id_tipo_transaccion='1' and id_empresa= '$_SESSION[PV]'");
                    $res = pg_fetch_row($ing);
                    $ing_pv = pg_query("select max(id_transaccion_pv::int) from transacciones where  id_empresa= '$_SESSION[PV]'");
                    $res_pv = pg_fetch_row($ing_pv);
                    //                  print_r("trans2".$costoVenta1);

                    if ($data == 22) {
                        //                        echo '<br>GUARDAR FACTURA VENTA12: <br>' . "insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'VENTA PRODUCTOS, CLIENTE: " . $p[0] . ", COMPROBANTE: " . $_POST['num_factura'] . ", : " . $_POST['marca_vehiculo'] . "', '" . $xy . "', '$_POST[tot]', '" . $saldo . "','1','" . ($res[0] + 1) . "','Activo','$cliente1','','','','','VEN','',$conpuntoresult,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')"; //////////////////////////
                        $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'VENTA PRODUCTOS, CLIENTE: " . $p[0] . ", COMPROBANTE: " . $_POST['num_factura'] . ", : " . $_POST['marca_vehiculo'] . "', '" . $xy . "', '$_POST[tot]', '" . $saldo . "','1','" . ($res[0] + 1) . "','Activo','$cliente1','','','','','VEN','',$conpuntoresult,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')");
                        //                  print_r("transjj".$bien_serviciob);
                    }
                    $consulta_bien_servi = pg_query(" SELECT sum(productos.precio_compra)
                                            FROM detalle_factura_venta,productos where productos.cod_productos=detalle_factura_venta.cod_productos 
                                            and detalle_factura_venta.id_factura_venta='$cont1' and detalle_factura_venta.bien_servicio='B'");
                    while ($row = pg_fetch_row($consulta_bien_servi)) {
                        $valor_Servicio1 = $row[0];
                    }
                    //                    print_r($inventario12B);
                    if ($valor_Servicio1 != '') {
                        if ($inventario12B > 0) {
                            $total0total12B = $inventario12B + $inventario0B;

                            if ($data == 22) {
                                //                                echo '<br>GUARDAR FACTURA VENTA12: <br>' . "insert into transacciones values('" . ($fila[0] + 1) . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'COSTO VENTA PRODUCTOS, CLIENTE: " . $p[0] . ", COMPROBANTE: " . $_POST['num_factura'] . ", : " . $_POST['marca_vehiculo'] . "', '" . $total0total12B . "', '" . $total0total12B . "', '0.000','1','" . ($res[0] + 1) . "','Activo','$cliente1','','','','','VEN','',$conpuntoresult,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')"; //////////////////////////

                                ////Que no se guarde asiento de costo
                                //$asiento2 = pg_query("insert into transacciones values('" . ($fila[0] + 1) . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'COSTO VENTA PRODUCTOS, CLIENTE: " . $p[0] . ", COMPROBANTE: " . $_POST['num_factura'] . ", : " . $_POST['marca_vehiculo'] . "', '" . $total0total12B . "', '" . $total0total12B . "', '0.000','1','" . ($res[0] + 1) . "','Activo','$cliente1','','','','','VEN','',$conpuntoresult,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')");
                            }
                        } else if ($inventario0B > 0) {
                            $total0total12 = $inventario12B + $inventario0B;

                            if ($data == 22) {
                                //                                echo '<br>GUARDAR FACTURA VENTA1: <br>' . "insert into transacciones values('" . ($fila[0] + 1) . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'COSTO VENTA PRODUCTOS, CLIENTE: " . $p[0] . ", COMPROBANTE: " . $_POST['num_factura'] . ", : " . $_POST['marca_vehiculo'] . "', '" . $total0total12 . "', '" . $total0total12 . "', '0.000','1','" . ($res[0] + 1) . "','Activo','$cliente1','','','','','VEN','',$conpuntoresult,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')"; //////////////////////////

                                ////Que no se guarde asiento de costo
                                //$asiento2 = pg_query("insert into transacciones values('" . ($fila[0] + 1) . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'COSTO VENTA PRODUCTOS, CLIENTE: " . $p[0] . ", COMPROBANTE: " . $_POST['num_factura'] . ", : " . $_POST['marca_vehiculo'] . "', '" . $total0total12 . "', '" . $total0total12 . "', '0.000','1','" . ($res[0] + 1) . "','Activo','$cliente1','','','','','VEN','',$conpuntoresult,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')");
                            }
                        }
                    }

                    /////DETALLES TRANSACCION
                    ////////////////////
                    $valor_Servicio1_iva = '';
                    $consulta_bien_servi_iva = pg_query("select   sum(detalle_factura_venta.total_venta) from detalle_factura_venta,productos where productos.cod_productos=detalle_factura_venta.cod_productos   and detalle_factura_venta.bien_servicio='B' and detalle_factura_venta.id_factura_venta='$cont1' and productos.iva='Si'");
                    while ($row = pg_fetch_row($consulta_bien_servi_iva)) {
                        $valor_Servicio1_iva = $row[0];
                    }

                    //                    print_r($valor_Servicio1_iva."valor_iva");
                    /*$iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
                    $fila1 = pg_fetch_row($iddettran);
                    if ($valor_Servicio1_iva != '') {
                        //1//
                        //                    print_r($sumaSubtotalTarifa12B."valor_b12");
                        if ($sumaSubtotalTarifa12B > 0) {
                            $fila1[0] = $fila1[0] + 1;
                            $merca = pg_query("select cuenta_debito from parametros where descripcion='VENTA MERCADERIA TARIFA12'");
                            $fila3 = pg_fetch_row($merca);

                            if ($data == 22) {
                                //                                 echo '<br>GUARDAR FACTURA VENTA2B: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila3[0] . "','0.000','$valor_Servicio1_iva','Activo')"; //////////////////////////
                                pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila3[0] . "','0.000','$valor_Servicio1_iva','Activo')");
                            }
                        }
                    }
                    $consulta_bien_servi_ivas = pg_query("select   sum(detalle_factura_venta.total_venta) from detalle_factura_venta,productos where productos.cod_productos=detalle_factura_venta.cod_productos   and detalle_factura_venta.bien_servicio='S' and detalle_factura_venta.id_factura_venta='$cont1' and productos.iva='Si'");
                    while ($row = pg_fetch_row($consulta_bien_servi_ivas)) {
                        $valor_Servicio1_ivas = $row[0];
                    }
                    $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
                    $fila1 = pg_fetch_row($iddettran);
                    if ($valor_Servicio1_ivas != '') {
                        if ($sumaSubtotalTarifa12 > 0) {
                            $fila1[0] = $fila1[0] + 1;
                            $merca = pg_query("select cuenta_debito from parametros where descripcion='VENTA MERCADERIA TARIFA12'");
                            $fila3 = pg_fetch_row($merca);

                            if ($data == 22) {
                                pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa12','0.000','$sumaSubtotalTarifa12','Activo')");
                                //echo '<br>GUARDAR FACTURA VENTA2: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa12','0.000','$sumaSubtotalTarifa12','Activo')"; //////////////////////////
                            }
                        }
                    }
                    $consulta_bien_servi_iva_no = pg_query("select   sum(detalle_factura_venta.total_venta) from detalle_factura_venta,productos where productos.cod_productos=detalle_factura_venta.cod_productos   and detalle_factura_venta.bien_servicio='B' and detalle_factura_venta.id_factura_venta='$cont1' and productos.iva='No'");
                    while ($row = pg_fetch_row($consulta_bien_servi_iva_no)) {
                        $valor_Servicio1_iva_no = $row[0];
                    }
                    if ($valor_Servicio1_iva_no != '') {
                        if ($sumaSubtotalTarifa0B > 0) {
                            $fila1[0] = $fila1[0] + 1;
                            $merca = pg_query("select cuenta_debito from parametros where descripcion='VENTA MERCADERIA TARIFA0'");
                            $fila3 = pg_fetch_row($merca);

                            if ($data == 22) {
                                pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila3[0] . "','0.000','$sumaSubtotalTarifa0B','Activo')");
                                //                                echo '<br>GUARDAR FACTURA VENTA3: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila3[0] . "','0.000','$valor_Servicio1_iva_no','Activo')"; //////////////////////////
                            }
                        }
                    }

                    if ($sumaSubtotalTarifa0 > 0) {
                        $fila1[0] = $fila1[0] + 1;
                        $merca = pg_query("select cuenta_debito from parametros where descripcion='VENTA MERCADERIA TARIFA0'");
                        $fila3 = pg_fetch_row($merca);

                        if ($data == 22) {
                            //                            echo '<br>GUARDAR FACTURA VENTA33: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa0','0.000','$sumaSubtotalTarifa0','Activo')"; //////////////////////////
                            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa0','0.000','$sumaSubtotalTarifa0','Activo')");
                        }
                    }
                    ///////////////////


                    $planiva = pg_query("select cuenta_credito from parametros where descripcion='IVA'");
                    $fila2 = pg_fetch_row($planiva);
                    if ($_POST['iva'] != '0') {
                        $fila1[0] = $fila1[0] + 1;
                        //                        echo '<br>GUARDAR FACTURA VENTA33iva: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','0.000','" . $_POST['iva'] . "','Activo')"; //////////////////////////
                        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','0.000','" . $_POST['iva'] . "','Activo')");
                    }*/
                    if ($data == 22) {
                        registrarCuentasIvaVentasTransaccion($cont1, $fila[0]);
                        registrarCuentasVentasTransaccion($cont1, $fila[0]);
                    }

                    $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
                    $fila1 = pg_fetch_row($iddettran);

                    $fila1[0] = $fila1[0] + 1;
                    if ($_POST["cuenta_cheque"] != "") {

                        $plancaja4 = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
                        $fila4 = pg_fetch_row($plancaja4);

                        //                        echo '<br>GUARDAR FACTURA VENTAGGFFD: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$_POST[cuenta_cheque]','" . $_POST['tot'] . "','0.000','Activo')"; //////////////////////////


                        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$_POST[cuenta_cheque]','" . $_POST['tot'] . "','0.000','Activo')");
                    } else {
                        $plancaja4 = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
                        $fila4 = pg_fetch_row($plancaja4);


                        if ($codplanTarifa12 == '270' || $codplanTarifa0 == '270') {
                            //                            echo '<br>GUARDAR FACTURA VENTAGGFFDF: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','9','" . $_POST['tot'] . "','0.000','Activo')"; //////////////////////////

                            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','9','" . $_POST['tot'] . "','0.000','Activo')");
                        } else if ($codplanTarifa12 != '270' || $codplanTarifa0 != '270') {
                            //                            echo '<br>GUARDAR FACTURA VENTAGGFFDFtt: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila4[0] . "','" . $_POST['tot'] . "','0.000','Activo')"; //////////////////////////

                            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila4[0] . "','" . $_POST['tot'] . "','0.000','Activo')");
                        }
                    }
                    //////COSTO VENTA FACTURA  /////////////////
                    //detalle costo de ventas
                    $plancaja4 = pg_query("select cuenta_debito from parametros where descripcion='COSTO VENTA'");
                    $fila4 = pg_fetch_row($plancaja4);
                    $fila1[0] = $fila1[0] + 1;
                    $consulta_bien_servi = pg_query(" SELECT sum(productos.precio_compra)
                    FROM detalle_factura_venta,productos where productos.cod_productos=detalle_factura_venta.cod_productos 
                    and detalle_factura_venta.id_factura_venta='$cont1' and detalle_factura_venta.bien_servicio='B'");
                    while ($row = pg_fetch_row($consulta_bien_servi)) {
                        $valor_Servicio1 = $row[0];
                    }
                    if ($valor_Servicio1 != '') {

                        if ($inventario12B > 0) {
                            $total0total12B = $inventario12B + $inventario0B;

                            if ($data == 22) {
                                ////Que no se guarde asiento de costo
                                //pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $fila4[0] . "','" . $total0total12B . "','0.000','Activo')");
                                //                                echo '<br>GUARDAR FACTURA VENTA4: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $fila4[0] . "','" . $total0total12B . "','0.000','Activo')"; //////////////////////////
                            }
                            $plancaja44 = pg_query("select cuenta_debito from parametros where descripcion='BIENES'");
                            $fila44 = pg_fetch_row($plancaja44);


                            $fila1[0] = $fila1[0] + 1;

                            if ($data == 22) {
                                //                                echo '<br>GUARDAR FACTURA VENTA6f: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','$codplanTarifa12B','0.000','" . $total0total12B . "','Activo')"; //////////////////////////

                                pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','$codplanTarifa12B','0.000','" . $total0total12B . "','Activo')");
                            }
                        } else if ($inventario0B > 0) {
                            $total0total12 = $inventario12B + $inventario0B;

                            if ($data == 22) {
                                //                                echo '<br>GUARDAR FACTURA VENTA44: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $fila4[0] . "','" . $total0total12 . "','0.000','Activo')"; //////////////////////////
                                pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $fila4[0] . "','" . $total0total12 . "','0.000','Activo')");
                            }
                            $plancaja44 = pg_query("select cuenta_debito from parametros where descripcion='BIENES'");
                            $fila44 = pg_fetch_row($plancaja44);


                            $fila1[0] = $fila1[0] + 1;

                            if ($data = 22) {
                                //                                echo '<br>GUARDAR FACTURA VENTA6f: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $fila44[0] . "','0.000','" . $total0total12 . "','Activo')"; //////////////////////////
                                pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $fila44[0] . "','0.000','" . $total0total12 . "','Activo')");
                            }
                            if ($data == 22) {
                                //                                echo '<br>GUARDAR FACTURA VENTA6f25: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','$codplanTarifa0B','0.000','" . $total0total12 . "','Activo')"; //////////////////////////
                                pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','$codplanTarifa0B','0.000','" . $total0total12 . "','Activo')");
                            }
                        }
                    }
                    //asiento generico Inventario
                    //                    if ($inventario0 > 0) {
                    //                        $fila1[0] = $fila1[0] + 1;
                    //                    echo '<br>GUARDAR FACTURA VENTA55: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $codplanTarifa0 . "','0.000','" . $inventario0 . "','Activo')"; //////////////////////////
                    //
                    //
                    //                        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $codplanTarifa0 . "','0.000','" . $inventario0 . "','Activo')");
                    //                    }
                    //                    if ($inventario0B > 0) {
                    //                        $fila1[0] = $fila1[0] + 1;
                    //                        echo '<br>GUARDAR FACTURA VENTA5: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $codplanTarifa0B . "','0.000','" . $inventario0B . "','Activo')"; //////////////////////////
                    //
                    //
                    //                        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $codplanTarifa0B . "','0.000','" . $inventario0B . "','Activo')");
                    //                    }
                    //                    $fila1[0] = $fila1[0] + 1;
                    //                    if ($inventario12 > 0) {
                    //
                    //
                    //                        if ($data == 22) {
                    //                            echo '<br>GUARDAR FACTURA VENTA7: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $codplanTarifa12 . "','0.000','" . $inventario12 . "','Activo')"; //////////////////////////
                    //                            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $codplanTarifa12 . "','0.000','" . $inventario12 . "','Activo')");
                    //                        }
                    //                    }
                    ///////////////////
                    //////////////////////////////////////
                }
            }
            //            $data = $cont1;
            /////FACTURA ELECTRONICA////
            ///1 GUARDADO
            ///2 GENERADO
            ///3 AUTORIZADO
            ///4 RECHAZADO

            /* $consulta_ambiente = pg_query("select codigo_ambi from ambiente where estado_ambi = 'Activo' ");
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

              $consulta_ambiente = pg_query("select codigo_ambi from ambiente where estado_ambi = 'Activo' ");
              while ($row = pg_fetch_row($consulta_ambiente)) {
              $ambiente = $row[0];
              }
              $result = generarXML($cont1, $codDoc, $ambiente, $emision);
              //             print_r($result);
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
              //print_r($respuesta);
              if (isset($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado)) {
              if ($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado == 'AUTORIZADO') {
              $numeroAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion;
              $fechaAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;
              $ambienteAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente;
              $data = 2;
              pg_query("UPDATE factura_venta SET fecha_autorizacion = '" . $fechaAutorizacion . "',  estado_fac = '2', num_autorizacion = '" . $numeroAutorizacion . "' WHERE id_factura_venta = '$cont1'");
              $dataFile = generarXMLCDATA($respuesta);
              $doc = new DOMDocument('1.0', 'UTF-8');
              $doc->loadXML($dataFile); // xml
              $doc->save($pathXmls . $numeroAutorizacion . '.xml');
              } else {
              $data = 7;
              pg_query("UPDATE factura_venta SET estado_fac = '7' where id_factura_venta = '$cont1'"); // NO AUTORIZADO
              }
              }
              $item = array(
              'estado' => $data,
              'id' => $cont1
              ); */
            $item = array(
                'clave' => $clave,
                'id' => $cont1
            );
            ///////////////////cambio nota venta///////////////////
        }
    } else {
        if ($_POST["tipo_venta"] == "NOTA") {
            // contador factura venta no valida
            $cont1 = 0;
            $consulta = pg_query("select max(id_facturas_novalidas) from facturas_novalidas");
            while ($row = pg_fetch_row($consulta)) {
                $cont1 = $row[0];
            }
            $cont1++;
            // fin
            // contador clientes
            $contt = 0;
            $consulta_cli = pg_query("select max(id_cliente) from clientes");
            while ($row = pg_fetch_row($consulta_cli)) {
                $contt = $row[0];
            }
            $contt++;
            // fin

            $guardarnv = false;
            if ($_POST['id_cliente'] == "") {
                $tipo = $_POST['ruc_ci'];
                if (strlen($tipo) == 10) {
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
                    // guardar clientes  
                    $sql = "insert into clientes values('$contt','Cedula','$_POST[ruc_ci]','" . strtoupper($_POST["nombre_cliente"]) . "','natural',"
                        . "'" . strtoupper($_POST["direccion_cliente"]) . "','$_POST[telefono_cliente]','','','','" . strtolower($_POST["correo"]) . "','1','','Activo','1','2')";
                    pg_query($sql);
                    // fin 
                    // guardar facturas_novalidas
                    $guardarnv = !!pg_query("insert into facturas_novalidas 
                    (
                        id_facturas_novalidas, id_cliente, id_usuario, comprobante, fecha_actual, 
                        hora_actual, tipo_precio, forma_pago, tarifa0, tarifa12, iva_venta, 
                        descuento_venta, total_venta, estado, id_empresa, id_vendedor, 
                        desc_prod, desc_fact)
                    values('$cont1','$contt','$_SESSION[id]','$cont1','$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[tipo_precio]','$_POST[formaspago]','$_POST[tarifa0]','$_POST[tarifa12]','$_POST[iva]','$_POST[desc]','$_POST[tot]','Activo','$conpuntoresult','$_POST[id_vendedor]',$_POST[descprod],$_POST[descfact])");
                    if (!empty($guardarnv) && !empty($_POST['id_centro_costo'])) {
                        guardarDetalleCentroCosto($cont1, $_POST['id_centro_costo'], "facturas_novalidas");
                    }
                    // fin
                } else {
                    if (strlen($tipo) == 13) {
                        // guardar clientes   
                        $sql = "insert into clientes values('$contt','Ruc','$_POST[ruc_ci]','" . strtoupper($_POST["nombre_cliente"]) . "','natural',"
                            . "'" . strtoupper($_POST["direccion_cliente"]) . "','$_POST[telefono_cliente]','','','','" . strtolower($_POST["correo"]) . "','1','','Activo','1','1')";
                        pg_query($sql);

                        // fin
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
                        // guardar facturas_novalidas
                        $guardarnv = !!pg_query("insert into facturas_novalidas 
                        (
                            id_facturas_novalidas, id_cliente, id_usuario, comprobante, fecha_actual, 
                            hora_actual, tipo_precio, forma_pago, tarifa0, tarifa12, iva_venta, 
                            descuento_venta, total_venta, estado, id_empresa, id_vendedor, 
                            desc_prod, desc_fact)
                        values('$cont1','$contt','$_SESSION[id]','$cont1','$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[tipo_precio]','$_POST[formaspago]','$_POST[tarifa0]','$_POST[tarifa12]','$_POST[iva]','$_POST[desc]','$_POST[tot]','Activo','$conpuntoresult','$_POST[id_vendedor]',$_POST[descprod],$_POST[descfact])");
                        if (!empty($guardarnv) && !empty($_POST['id_centro_costo'])) {
                            guardarDetalleCentroCosto($cont1, $_POST['id_centro_costo'], "facturas_novalidas");
                        }
                        // fin   
                    }
                }
            } else {
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
                // guardar facturas_novalidas
                $guardarnv = !!pg_query("insert into facturas_novalidas 
                (
                    id_facturas_novalidas, id_cliente, id_usuario, comprobante, fecha_actual, 
                    hora_actual, tipo_precio, forma_pago, tarifa0, tarifa12, iva_venta, 
                    descuento_venta, total_venta, estado, id_empresa, id_vendedor, 
                    desc_prod, desc_fact)
                values('$cont1','$_POST[id_cliente]','$_SESSION[id]','$cont1','$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[tipo_precio]','$_POST[formaspago]','$_POST[tarifa0]','$_POST[tarifa12]','$_POST[iva]','$_POST[desc]','$_POST[tot]','Activo','$conpuntoresult','$_POST[id_vendedor]',$_POST[descprod],$_POST[descfact])");
                if (!empty($guardarnv) && !empty($_POST['id_centro_costo'])) {
                    guardarDetalleCentroCosto($cont1, $_POST['id_centro_costo'], "facturas_novalidas");
                }
            }

            // modificar proformas
            if ($_POST['proforma'] != "") {
                pg_query("Update proforma Set estado='Pasivo' where id_proforma='" . $_POST['proforma'] . "'");
            }

            //modificar proformas técnico
            if ($guardarnv) {
                if (isset($_POST["id_proforma_tecnico"]) && $_POST["id_proforma_tecnico"] > 0) {
                    actualizarProformaTecnico($_POST["id_proforma_tecnico"], "id_facturas_novalidas", $cont1);
                }
            }
            // fin
            // datos detalle factura
            $campo1 = $_POST['campo1'];
            $campo2 = $_POST['campo2'];
            $campo3 = $_POST['campo3'];
            $campo4 = $_POST['campo4'];
            $campo5 = $_POST['campo5'];
            $campo6 = $_POST['campo6'];
            $campo8 = $_POST['campo8'];
            $campo9 = $_POST['campo9'];
            $campo10 = $_POST['campo10'];
            $tarifas = $_POST['tarifas'];
            $vlores_iva = $_POST['vlores_iva'];
            $cods_impuesto = $_POST['cods_impuesto'];
            $cods_tarifa = $_POST['cods_tarifa'];

            // agregar detalle_facturas_novalidas
            $arreglo1 = explode('|', $campo1);
            $arreglo2 = explode('|', $campo2);
            $arreglo3 = explode('|', $campo3);
            $arreglo4 = explode('|', $campo4);
            $arreglo5 = explode('|', $campo5);
            $arreglo6 = explode('|', $campo6);
            $arreglo8 = explode('|', $campo8);
            $arreglo9 = explode('|', $campo9);
            $arreglo10 = explode('|', $campo10);
            $arreglotarifas = explode('|', $tarifas);
            $arreglovlores_iva = explode('|', $vlores_iva);
            $arreglocods_impuesto = explode('|', $cods_impuesto);
            $arreglocods_tarifa = explode('|', $cods_tarifa);

            $nelem = count($arreglo1);
            $forma = $_POST['formaspago'];
            if ($guardarnv) {
                ///////////////NOTA DE VENTA OTROS
                if ($forma == "otros") {
                    //                    echo ':otros:'."select sum(x.sum) from (select formas_pago_mixto.forma_pago,sum(formas_pago_mixto.valor) from facturas_novalidas, formas_pago_mixto 
                    //                where facturas_novalidas.id_facturas_novalidas=formas_pago_mixto.id_factura_venta  and facturas_novalidas.id_facturas_novalidas='$cont1' 
                    //                and (formas_pago_mixto.forma_pago='CREDITO' or formas_pago_mixto.forma_pago='CPOSFECHADO')  and formas_pago_mixto.tipo_documento='NOTA' GROUP BY formas_pago_mixto.forma_pago
                    //                )x";
                    $consulta_mixto = pg_query("select sum(x.sum) from (select formas_pago_mixto.forma_pago,sum(formas_pago_mixto.valor) from facturas_novalidas, formas_pago_mixto 
                where facturas_novalidas.id_facturas_novalidas=formas_pago_mixto.id_factura_venta  and facturas_novalidas.id_facturas_novalidas='$cont1' 
                and (formas_pago_mixto.forma_pago='CREDITO' or formas_pago_mixto.forma_pago='CPOSFECHADO')  and formas_pago_mixto.tipo_documento='NOTA' GROUP BY formas_pago_mixto.forma_pago
                )x");
                    $valor_contado = "";
                    while ($row = pg_fetch_row($consulta_mixto)) {
                        //                    $cont2_mixto_contado = $row[0];
                        $valor_contado = $row[0];
                    }
                    if ($valor_contado != "") {
                        // variables pagos
                        $adelanto = '0.00';
                        $meses = $_POST['meses'];
                        //                        $total = $valor_contado;
                    }
                    // contador pagos venta
                    $cont2 = 0;
                    $consulta = pg_query("select max(id_pagos_venta) from pagos_venta");
                    while ($row = pg_fetch_row($consulta)) {
                        $cont2 = $row[0];
                    }
                    $cont2++;

                    //                    $monto = $total;
                    //                    $format = number_format($monto, 2, '.', '');
                    if ($_POST['id_cliente'] == "") {
                        $idCli = 0;
                        $consulta_cli = pg_query("select max(id_cliente) from clientes");
                        while ($row = pg_fetch_row($consulta_cli)) {
                            $idCli = $row[0];
                        }
                        //                        echo '<br>GUARDAR NOTA VENTA1 NV: <br>' . "insert into pagos_venta values('$cont2','$_POST[id_cliente]','$cont1','$_SESSION[id]','$_POST[fecha_actual]','0.00','1','Nota','$format','$format','Activo','$_POST[fecha_dias]','$conpuntoresult')"; //////////////////////////

                        $sql = "insert into pagos_venta values('$cont2','$idCli','$cont1','$_SESSION[id]','$_POST[fecha_actual]','0.00','1','Nota','$valor_contado','$valor_contado','Activo','$_POST[fecha_dias]','$conpuntoresult')";
                        $guardar_nv = guardarSql($conexion, $sql);
                        if ($guardar_nv == 'true') {
                            //                            $data = 22;
                        } else {
                            error_log_fv(0, "id_factura_novalida=$cont2", "guardar_factura_venta.php", 343);
                            error_log_fv(0, pg_last_error($conexion), "guardar_factura_venta.php", 360);
                            error_log_fv(0, pg_last_error($guardar_nv), "guardar_factura_venta.php", 360);
                        }
                    } else {
                        $cliente1 = $_POST['id_cliente'];
                        pg_query("Update clientes Set  nombres_cli='" . strtoupper($_POST['nombre_cliente']) . "',direccion_cli='" . strtoupper($_POST['direccion_cliente']) . "', telefono='$_POST[telefono_cliente]', correo='$_POST[correo]' where id_cliente='$cliente1'");

                        //                        echo '<br>GUARDAR NOTA VENTA1tt NV: <br>' . "insert into pagos_venta values('$cont2','$_POST[id_cliente]','$cont1','$_SESSION[id]','$_POST[fecha_actual]','0.00','1','Nota','$format','$format','Activo','$_POST[fecha_dias]','$conpuntoresult')"; //////////////////////////

                        $sql = "insert into pagos_venta values('$cont2','$cliente1','$cont1','$_SESSION[id]','$_POST[fecha_actual]','0.00','1','Nota','$valor_contado','$valor_contado','Activo','$_POST[fecha_dias]','$conpuntoresult')";
                        $guardar_nv = guardarSql($conexion, $sql);
                        if ($guardar_nv == 'true') {
                            //                            $data = 22;
                        } else {
                            error_log_fv(0, "id_factura_novalida=$cont2", "guardar_factura_venta.php", 343);
                            error_log_fv(0, pg_last_error($conexion), "guardar_factura_venta.php", 360);
                            error_log_fv(0, pg_last_error($guardar_nv), "guardar_factura_venta.php", 360);
                        }
                    }
                    // guardar meses
                    if ($meses > 1) {
                        //                    for ($i = 1; $i <= $meses - 1; $i++) {
                        //                        // contador detalle pagos venta
                        //                        $cont3 = 0;
                        //                        $consulta8 = pg_query("select max(id_detalle_pagos_venta) from detalle_pagos_venta");
                        //                        while ($row = pg_fetch_row($consulta8)) {
                        //                            $cont3 = $row[0];
                        //                        }
                        //                        $cont3++;
                        //                        // fin
                        //
                        //                        $calcu = $monto / ($meses);
                        //                        $nuevaFecha = date('Y-m-d', strtotime(" + $i month"));
                        //                        $format_numero = number_format(floor($calcu), 2, '.', '');
                        //                        if ($guardarnv) {
                        //                            //                            echo '<br>GUARDAR NOTA VENTArr: <br>' . "insert into detalle_pagos_venta values('$cont3','$cont2','$nuevaFecha','$format_numero','$format_numero','Activo')"; //////////////////////////
                        //                            //TODO pagos_venta
                        //                            pg_query("insert into detalle_pagos_venta values('$cont3','$cont2','$nuevaFecha','$format_numero','$format_numero','Activo')");
                        //                        } else {
                        //                            $data = 60; /// error al guardar
                        //                            $item = array('estado' => $data);
                        //                        }
                        //                    }
                        //
                        //                    $cont3++;
                        //                    $calcu1 = floor($calcu) * ($meses - 1);
                        //                    $ultimaFecha = date('Y-m-d', strtotime(" + $i month"));
                        //                    $sal = $monto - $calcu1;
                        //                    $format_numero2 = number_format($sal, 2, '.', '');
                        //                    if ($guardarnv) {
                        //                        //                        echo '<br>GUARDAR NOTA VENTArrgg: <br>' . "insert into detalle_pagos_venta values('$cont3','$cont2','$ultimaFecha','$format_numero2','$format_numero2','Activo')"; //////////////////////////
                        //
                        //                        //TODO pagos_venta
                        //                        pg_query("insert into detalle_pagos_venta values('$cont3','$cont2','$ultimaFecha','$format_numero2','$format_numero2','Activo')");
                        //                    } else {
                        //                        $data = 60; /// error al guardar
                        //                        $item = array('estado' => $data);
                        //                    }
                    } else {
                        $cont3 = 0;
                        $consulta8 = pg_query("select max(id_detalle_pagos_venta) from detalle_pagos_venta");
                        while ($row = pg_fetch_row($consulta8)) {
                            $cont3 = $row[0];
                        }
                        $cont3++;

                        $k = 1;
                        $format2 = number_format($monto, 2, '.', '');
                        $Fecha = date('Y-m-d', strtotime(" + $k month"));
                        if ($guardarnv) {
                            //                            echo '<br>GUARDAR NOTA VENTArrggffff: <br>' . "insert into detalle_pagos_venta values('$cont3','$cont2','$Fecha','$format2','$format2','Activo')"; //////////////////////////
                            //TODO pagos_venta
                            pg_query("insert into detalle_pagos_venta values('$cont3','$cont2','$Fecha','$format2','$format2','Activo')");
                        } else {
                            $data = 60; /// error al guardar
                            $item = array('estado' => $data);
                        }
                    }

                    for ($i = 1; $i < $nelem; $i++) {
                        if (!empty($arreglo1[$i])) {

                            $consulta_bien_servi = pg_query(" select bien_servicios from productos where cod_productos=$arreglo1[$i]");
                            while ($row = pg_fetch_row($consulta_bien_servi)) {
                                $valor_Servicio = $row[0];
                            }
                            // contador detalle_factura_novalidas
                            $cont4 = 0;
                            $consulta = pg_query("select max(id_detalle_facturas_novalidas) from detalle_facturas_novalidas");
                            while ($row = pg_fetch_row($consulta)) {
                                $cont4 = $row[0];
                            }
                            $cont4++;
                            //                            echo '<br>GUARDAR FACTURA no_validas: <br>' . "insert into detalle_facturas_novalidas values('$cont4','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','Activo','$arreglo6[$i]')"; //////////////////////////

                            if ($guardarnv) {
                                //                                echo '<br>GUARDAR NOTA VENTArrggfffbbbf2: <br>' . "insert into detalle_facturas_novalidas values('$cont4','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','Activo','$arreglo6[$i]','$valor_Servicio','$arreglo8[$i]','$arreglo9[$i]')"; //////////////////////////

                                $sql = "insert into detalle_facturas_novalidas 
                                (
                                    id_detalle_facturas_novalidas, id_facturas_novalidas, cod_productos, 
                                    cantidad, precio_venta, descuento_producto, total_venta, estado, 
                                    pendientes, bien_servicio, cantidad_unidad, unidad_medida, detalle_producto)
                                values('$cont4','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','Activo','$arreglo6[$i]','$valor_Servicio','$arreglo8[$i]','$arreglo9[$i]','$arreglo10[$i]')";
                                $guardar = guardarSql($conexion, $sql);

                                if ($guardar == 'true') {
                                    guardarDetalleImpuestoProductoNv($arreglocods_impuesto[$i], $arreglocods_tarifa[$i], $arreglotarifas[$i], $arreglovlores_iva[$i], $arreglo5[$i], $cont4);
                                    $data = 22;
                                } else {
                                    error_log_fv(0, "id_factura=$cont1", "facturas_novalidas.php", 343);
                                    error_log_fv(0, pg_last_error($conexion), "facturas_novalidas.php", 360);
                                    error_log_fv(0, pg_last_error($guardar), "facturas_novalidas.php", 360);
                                    error_log_fv(0, pg_last_error($sql), "facturas_novalidas.php", 360);
                                    //                                    echo '<br>GUARDAR FACTURA DETALLE: <br>' . "insert into detalle_facturas_novalidas values('$cont6','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]',"
                                    //                                    . "'$arreglo4[$i]','$arreglo5[$i]','Activo','$arreglo6[$i]','$_POST[fecha_actual]','$valor_Servicio')"; //////////////////////////

                                    pg_query("DELETE FROM facturas_novalidas WHERE id_facturas_novalidas='$cont1';");

                                    //
                                    //                                    echo '<br>GUARDAR FACTURA delete: <br>' . "DELETE FROM facturas_novalidas WHERE id_facturas_novalidas='$cont1';";

                                    $data = 60; /// error al guardar
                                    $item = array('estado' => $data);
                                } // fin
                            } else {
                                $data = 60; /// error al guardar
                                $item = array('estado' => $data);
                            }


                            $contb = 0;
                            $consulta = pg_query("select max(id_detalle_productos_bodega) from detalle_producto_bodega");
                            while ($row = pg_fetch_row($consulta)) {
                                $contb = $row[0];
                            }
                            $contb++;

                            // guardar detalle productos bodega
                            $consulta_v = pg_query("select * from detalle_producto_bodega where id_bodega=$pvinv and cod_productos=$arreglo1[$i]");
                            while ($row = pg_fetch_row($consulta_v)) {
                                $cod_pro = $row[1];
                                $id_bod = $row[2];
                                $stock = $row[6];
                            }
                            $cal = $stock - $arreglo2[$i];

                            if ($cod_pro == $arreglo1[$i] && $id_bod == $conpuntoresult) {
                                /* DESBLOQUEAR pg_query("Update detalle_producto_bodega Set fecha='" . $_POST[fecha_actual] . "' ,hora='" . $_POST[hora_actual] . "', "
                                  . "stock='" . $cal . "' where cod_productos='" . $arreglo1[$i] . "' and id_bodega='" . $conpuntoresult . "' "); */
                            } else {
                                $contb = 0;
                                $consulta = pg_query("select max(id_detalle_productos_bodega) from detalle_producto_bodega");
                                while ($row = pg_fetch_row($consulta)) {
                                    $contb = $row[0];
                                }
                                $contb++;
                                $horap = date("g:ia");
                                /* DESBLOQUEAR pg_query("insert into detalle_producto_bodega values('$contb','$arreglo1[$i]','$conpuntoresult','$_SESSION[id]','$_POST[fecha_actual]','$horap','$cal')"); */
                            }
                            // contador kardex valorizado
                            // contador kardex
                            $cont_k = 0;
                            $consulta_k = pg_query("select max(id_kardex) from kardex");
                            while ($row = pg_fetch_row($consulta_k)) {
                                $cont_k = $row[0];
                            }
                            $cont_k++;
                            // fin
                            $cont_v = 0;
                            $consulta_v = pg_query("select max(id_kardex) from kardex_valorizado");
                            while ($row = pg_fetch_row($consulta_v)) {
                                $cont_v = $row[0];
                            }
                            $cont_v++;
                            // fin
                            $cantidad = 0;
                            $precio_total = 0;
                            $precio_unitario = 0;
                            $consulta2 = pg_query("select * from kardex_valorizado where cod_productos = '$arreglo1[$i]' order by id_kardex desc limit 1");
                            while ($row = pg_fetch_row($consulta2)) {
                                $cantidad = $row[11];
                                $precio_unitario = $row[7];
                                $precio_total = $row[8];
                            }

                            $cantidad_salida = $arreglo2[$i];
                            $precio_unitario_salida = number_format($precio_unitario, 4, '.', '');
                            $precio_total_salida = number_format($arreglo2[$i] * $precio_unitario, 2, '.', '');

                            $cantidad_total = $cantidad - $arreglo2[$i];
                            $precio_total_total = number_format($precio_total - $precio_total_salida, 2, '.', '');
                            $precio_unitario_total = number_format($precio_total_total / $cantidad_total, 4, '.', '');

                            /* pg_query("insert into kardex_valorizado values(" . $cont_v . ",'" . $arreglo1[$i] . "','$_POST[fecha_actual]', '" . 'Nota Venta: ' . $_POST['num_factura'] . "',"
                              . "'','" . $cantidad_salida . "','" . $cantidad . "','" . $precio_unitario_salida . "','" . $precio_total_salida . "','','','" . $cantidad_total . "','4')"); */


                            /* procesarKardexValorizadoSalida($arreglo1[$i], $_POST['fecha_actual'], 'Nota Venta: ' . $_POST['num_factura'] 
                              , $arreglo2[$i], obtenerValoresPromedios($arreglo1[$i])[0]['venta_promedio'], $cantidad, 'Activo', '1', 'V', $cont1, NULL, NULL); */
                            // fin
                            //////// COSTO DE VENTA OTROS NOTAS DE VENTA//////////////////////////                        
                            // consulta kardex valorizado
                            $cantidad = 0;
                            $precio_total = 0;
                            $precio_unitario = 0;
                            $costoVenta = 0;
                            $consulta2 = pg_query("select * from kardex_valorizado where cod_productos = '$arreglo1[$i]' order by id_kardex desc limit 1");
                            while ($row = pg_fetch_row($consulta2)) {
                                $cantidad = $row[11];
                                $precio_unitario = $row[7]; //round($row[7], 4);
                                $precio_total = $row[8]; //round($row[8], 4);
                                $costoVenta = $row[13]; //round($row[13], 4);
                            }
                            if ($costoVenta == "0.0000") {
                                $costoVenta1 = $costoVenta1 + ($arreglo2[$i]);
                            } else {
                                $costoVenta1 = $costoVenta1 + ($costoVenta * $arreglo2[$i]);
                            }
                            //////////////////////////////////////////////////////////////////////

                            if ($_POST['id_cliente'] == "") {
                                $idCli = 0;
                                $consulta_cli = pg_query("select max(id_cliente) from clientes");
                                while ($row = pg_fetch_row($consulta_cli)) {
                                    $idCli = $row[0];
                                }
                                // guardar kardex
                                //pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '" . 'N.V:' . $_POST['num_factura'] . "' ,'$arreglo2[$i]','$arreglo3[$i]','$arreglo5[$i]','$arreglo1[$i]','$cal','2','','','$idCli','$cont1','NV','$conpuntoresult')");

                                /* DESBLOQUEAR CODIGO ESTEBAN procesarKardexSalida($arreglo1[$i], 'N.V:' . $_POST['num_factura'], $arreglo2[$i], obtenerStock($arreglo1[$i], $_SESSION['PV']), 
                                  obtenerValoresPromedios($arreglo1[$i])[0]['venta_promedio'], 'Activo', $conpuntoresult, 'V',
                                  $cont1, $arreglo5[$i], NULL, NULL, $idCli, '', NULL, NULL, $_SESSION['id']); */
                                if ($data == 22) {
                                    if ($arreglo8[$i] != 0) {
                                        $arreglo2[$i] = $arreglo8[$i];
                                    } else {
                                        $arreglo2[$i] = $arreglo2[$i];
                                    }
                                    procesarKardexSalida($arreglo1[$i], 'N.V:' . $_POST['num_factura'], $arreglo2[$i], obtenerStock($arreglo1[$i], $pvinv), NULL, 'Activo', $pvinv, 'V', $cont1, $arreglo5[$i], NULL, NULL, $idCli, '', NULL, NULL, $_SESSION['id']);
                                }
                                // fin
                            } else {
                                $cliente1 = $_POST['id_cliente'];
                                pg_query("Update clientes Set  nombres_cli='" . strtoupper($_POST['nombre_cliente']) . "',direccion_cli='" . strtoupper($_POST['direccion_cliente']) . "', telefono='$_POST[telefono_cliente]', correo='$_POST[correo]' where id_cliente='$cliente1'");

                                //pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '" . 'N.V:' . $_POST['num_factura'] . "' ,'$arreglo2[$i]','$arreglo3[$i]','$arreglo5[$i]','$arreglo1[$i]','$cal','2','','','$cliente1','$cont1','NV','$conpuntoresult')");

                                /* DESBLOQUEAR CODIGO ESTEBAN procesarKardexSalida($arreglo1[$i], 'N.V:' . $_POST['num_factura'], $arreglo2[$i], obtenerStock($arreglo1[$i], $_SESSION['PV']), 
                                  obtenerValoresPromedios($arreglo1[$i])[0]['venta_promedio'], 'Activo', $conpuntoresult, 'V',
                                  $cont1, $arreglo5[$i], NULL, NULL, $_POST['id_cliente'], '', NULL, NULL, $_SESSION['id']); */
                                if ($data == 22) {
                                    if ($arreglo8[$i] != 0) {
                                        $arreglo2[$i] = $arreglo8[$i];
                                    } else {
                                        $arreglo2[$i] = $arreglo2[$i];
                                    }
                                    procesarKardexSalida($arreglo1[$i], 'N.V:' . $_POST['num_factura'], $arreglo2[$i], obtenerStock($arreglo1[$i], $pvinv), NULL, 'Activo', $pvinv, 'V', $cont1, $arreglo5[$i], NULL, NULL, $_POST['id_cliente'], '', NULL, NULL, $_SESSION['id']);
                                }
                            }
                            ///////////////////////////ASIENTO CONTABLE NOTA DE VENTA//////////////////////////////////////////////
                            $cuenta = pg_query("select iva,id_plan_cuentas from productos where cod_productos ='" . $arreglo1[$i] . "'  and bien_servicios='B'");
                            $plan = pg_fetch_row($cuenta);

                            if ($plan[0] == "Si") {
                                if ($costoVenta == "0.0000" || $costoVenta == "0") {
                                    $inventario12B = $inventario12B + ($arreglo2[$i]);
                                } else {
                                    $inventario12B = $inventario12B + ($costoVenta * $arreglo2[$i]);
                                }

                                $sumaSubtotalTarifa12B = $sumaSubtotalTarifa12B + $arreglo5[$i];
                                $codplanTarifa12B = $plan[1];
                                $contTarifa12++;
                            } else if ($plan[0] == "No") {
                                if ($costoVenta == "0.0000" || $costoVenta == "0") {
                                    $inventario0B = $inventario0B + ($arreglo2[$i]);
                                } else {
                                    $inventario0B = $inventario0B + ($costoVenta * $arreglo2[$i]);
                                }

                                $sumaSubtotalTarifa0B = $sumaSubtotalTarifa0B + $arreglo5[$i];
                                $codplanTarifa0B = $plan[1];
                                $contTarifa0++;
                            }

                            /////////////////////////////////ASIENTO CONTABLE NOTA DE VENTA/////////////////////////////
                            $cuenta = pg_query("select iva,id_plan_cuentas from productos where cod_productos ='" . $arreglo1[$i] . "'  and bien_servicios='S'");
                            $plan = pg_fetch_row($cuenta);

                            if ($plan[0] == "Si") {
                                if ($costoVenta == "0.0000") {
                                    $inventario12 = $inventario12 + ($arreglo2[$i]);
                                } else {
                                    $inventario12 = $inventario12 + ($costoVenta * $arreglo2[$i]);
                                }

                                $sumaSubtotalTarifa12 = $sumaSubtotalTarifa12 + $arreglo5[$i];
                                $codplanTarifa12 = $plan[1];
                                $contTarifa12++;
                            } else if ($plan[0] == "No") {
                                if ($costoVenta == "0.0000") {
                                    $inventario0 = $inventario0 + ($arreglo2[$i]);
                                } else {
                                    $inventario0 = $inventario0 + ($costoVenta * $arreglo2[$i]);
                                }

                                $sumaSubtotalTarifa0 = $sumaSubtotalTarifa0 + $arreglo5[$i];
                                $codplanTarifa0 = $plan[1];
                                $contTarifa0++;
                            }
                        }
                    }
                    ///////////////ASIENTO CONTABLE NOTA DE VENTA////////////////////////////7
                    $idtran = pg_query("select max(id_transacciones) from transacciones");
                    $fila = pg_fetch_row($idtran);
                    $fila[0] = $fila[0] + 1;
                    $sum = 0;
                    $bool = true;
                    $pos = 0;
                    $vec = 0;
                    $tieneiva;
                    $ivafin = 0;
                    $auxiliar = $arreglo1;
                    $abc = 0;
                    $xy = 0;
                    $iva = pg_query("select valor from parametros where descripcion='IVA'");
                    while ($ivavalor = pg_fetch_row($iva)) {
                        $ivafin = $ivavalor[0];
                    }
                    $abc = ($ivafin + 100) / 100;
                    while ($bool) {
                        $cuenta = pg_query("select id_plan_cuentas from productos where cod_productos='" . $auxiliar[0] . "'");
                        $plan = pg_fetch_row($cuenta);
                        $nelem = count($auxiliar);
                        $vec = 0;
                        for ($i = 0; $i <= $nelem; $i++) {
                            $cuenta1 = pg_query("select id_plan_cuentas from productos where cod_productos='" . $auxiliar[$i] . "'");
                            $cIva = pg_query("select incluye_iva from productos where cod_productos='" . $auxiliar[$i] . "'");
                            $plan1 = pg_fetch_row($cuenta1);
                            $si = pg_fetch_row($cIva);
                            $tieneiva = $si[0];
                            if ($plan1[$i] == $plan[0]) {
                                if ($tieneiva == "Si") {
                                    $sum = ($arreglo5[$i] / $abc) + $sum;
                                } else {
                                    //$aa++;
                                    $sum = $arreglo5[$i] + $sum;
                                }
                                //$sum=$arreglo5[$i]+$sum;
                            } else {
                                $vec[$pos] = $auxiliar[$i];
                                $pos++;
                            }
                        }
                        if ($vec == 0) {
                            $bool = false;
                        } else {
                            $auxiliar = $vec;
                            $pos = 0;
                        }
                    }
                    $sum = $sum;
                    $xy = $sum + $_POST['iva'];
                    $saldo = $xy - $_POST['tot'];
                    $cliente1 = "";
                    if ($_POST['id_cliente'] == "") {
                        $cliente1 = $contt;
                    } else {
                        $cliente1 = $_POST['id_cliente'];
                    }
                    $prove = pg_query("select identificacion from clientes where id_cliente='$cliente1'");
                    $p = pg_fetch_row($prove);
                    $ing = pg_query("select max(num_transaccion) from transacciones where id_tipo_transaccion='1'and id_empresa= '$_SESSION[PV]'");
                    $res = pg_fetch_row($ing);
                    $ing_pv = pg_query("select max(id_transaccion_pv::int) from transacciones where  id_empresa= '$_SESSION[PV]'");
                    $res_pv = pg_fetch_row($ing_pv);
                    //                print_r("trans 1");
                    //                    echo '<br>GUARDAR FACTURA VENTA1: <br>' . "insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'NOTA VENTA PRODUCTOS, CLIENTE: " . $p[0] . ", COMPROBANTE: " . $_POST['num_factura'] . ", : " . $_POST['marca_vehiculo'] . "', '" . $_POST['tot'] . "', '$_POST[tot]', '" . $saldo . "','1','" . ($res[0] + 1) . "','Activo','$cliente1','','','','','NV','',$conpuntoresult,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')"; //////////////////////////
                    //	 
                    //////////// CAMBIAR NOTA   OTROS
                    $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'NOTA VENTA PRODUCTOS, CLIENTE: " . $p[0] . ", COMPROBANTE: " . $_POST['num_factura'] . ", : " . $_POST['marca_vehiculo'] . "', '" . $_POST['tot'] . "', '$_POST[tot]', '" . $saldo . "','1','" . ($res[0] + 1) . "','Activo','$cliente1','','','','','NV','',$conpuntoresult,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')");
                    $consulta_bien_servi1 = pg_query(" SELECT sum(productos.precio_compra)
                     FROM detalle_facturas_novalidas,productos where productos.cod_productos=detalle_facturas_novalidas.cod_productos 
                     and detalle_facturas_novalidas.id_facturas_novalidas='$cont1' and detalle_facturas_novalidas.bien_servicio='B'");
                    while ($row = pg_fetch_row($consulta_bien_servi1)) {
                        $valor_Servicio1 = $row[0];
                    }
                    if ($valor_Servicio1 != "") {

                        if ($inventario12B > 0) {
                            $total0total12B = $inventario12B + $inventario0B;

                            //                            echo '<br>GUARDAR FACTURA VENTAqw: <br>' . "insert into transacciones values('" . ($fila[0] + 1) . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'COSTO NOTA VENTA PRODUCTOS, CLIENTE: " . $p[0] . ", COMPROBANTE: " . $_POST['num_factura'] . ", : " . $_POST['marca_vehiculo'] . "', '" . $total0total12B . "', '" . $total0total12B . "', '0.000','1','" . ($res[0] + 1) . "','Activo','$cliente1','','','','','NV','',$conpuntoresult,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')";

                            $asiento2 = pg_query("insert into transacciones values('" . ($fila[0] + 1) . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'COSTO NOTA VENTA PRODUCTOS, CLIENTE: " . $p[0] . ", COMPROBANTE: " . $_POST['num_factura'] . ", : " . $_POST['marca_vehiculo'] . "', '" . $total0total12B . "', '" . $total0total12B . "', '0.000','1','" . ($res[0] + 1) . "','Activo','$cliente1','','','','','NV','',$conpuntoresult,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')");
                        } else {
                            $total0total12 = $inventario12B + $inventario0B;
                            //                            echo '<br>GUARDAR FACTURA VENTAui: <br>' . "insert into transacciones values('" . ($fila[0] + 1) . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'COSTO NOTA VENTA PRODUCTOS, CLIENTE: " . $p[0] . ", COMPROBANTE: " . $_POST['num_factura'] . ", : " . $_POST['marca_vehiculo'] . "', '" . $total0total12 . "', '" . $total0total12 . "', '0.000','1','" . ($res[0] + 1) . "','Activo','$cliente1','','','','','NV','',$conpuntoresult,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')"; //////////////////////////

                            $asiento2 = pg_query("insert into transacciones values('" . ($fila[0] + 1) . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'COSTO NOTA VENTA PRODUCTOS, CLIENTE: " . $p[0] . ", COMPROBANTE: " . $_POST['num_factura'] . ", : " . $_POST['marca_vehiculo'] . "', '" . $total0total12 . "', '" . $total0total12 . "', '0.000','1','" . ($res[0] + 1) . "','Activo','$cliente1','','','','','NV','',$conpuntoresult,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')");
                        }
                    }
                    /////DETALLES TRANSACCION

                    $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
                    $fila1 = pg_fetch_row($iddettran);
                    $consulta_bien_servi_iva = pg_query("select   sum(detalle_facturas_novalidas.total_venta) from detalle_facturas_novalidas,productos where productos.cod_productos=detalle_facturas_novalidas.cod_productos   and detalle_facturas_novalidas.bien_servicio='B' and detalle_facturas_novalidas.id_facturas_novalidas='$cont1' ");
                    while ($row = pg_fetch_row($consulta_bien_servi_iva)) {
                        $valor_Servicio1_iva = $row[0];
                    }

                    if ($valor_Servicio1_iva != '') {
                        $valor_Servicio1_iva = $valor_Servicio1_iva + $_POST['iva'];
                        if ($sumaSubtotalTarifa12B > 0) {
                            $fila1[0] = $fila1[0] + 1;
                            $merca = pg_query("select cuenta_debito from parametros where descripcion='OTROS INGRESOS'");
                            $fila3 = pg_fetch_row($merca);
                            //                            echo '<br>GUARDAR FACTURA VENTA331: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila3[0] . "','0.000','$valor_Servicio1_iva','Activo')"; //////////////////////////

                            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila3[0] . "','0.000','$valor_Servicio1_iva','Activo')");
                        }
                    }
                    $consulta_bien_servi_ivas = pg_query("select   sum(detalle_facturas_novalidas.total_venta) from detalle_facturas_novalidas,productos where productos.cod_productos=detalle_facturas_novalidas.cod_productos   and detalle_facturas_novalidas.bien_servicio='S' and detalle_facturas_novalidas.id_facturas_novalidas='$cont1' and productos.iva='Si'");
                    while ($row = pg_fetch_row($consulta_bien_servi_ivas)) {
                        $valor_Servicio1_ivas = $row[0];
                    }
                    $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
                    $fila1 = pg_fetch_row($iddettran);
                    if ($valor_Servicio1_ivas != '') {

                        if ($sumaSubtotalTarifa12 > 0) {
                            $fila1[0] = $fila1[0] + 1;
                            $merca = pg_query("select cuenta_debito from parametros where descripcion='VENTA MERCADERIA TARIFA12'");
                            $fila3 = pg_fetch_row($merca);
                            //                            echo '<br>GUARDAR FACTURA VENTA3332: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa12','0.000','$sumaSubtotalTarifa12','Activo')"; //////////////////////////

                            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa12','0.000','$valor_Servicio1_ivas','Activo')");
                        }
                    }
                    //                    $consulta_bien_servi_iva_no = pg_query("select   sum(detalle_facturas_novalidas.total_venta) from detalle_facturas_novalidas,productos where productos.cod_productos=detalle_facturas_novalidas.cod_productos   and detalle_facturas_novalidas.bien_servicio='B' and detalle_facturas_novalidas.id_facturas_novalidas='$cont1' and productos.iva='No'");
                    //                    while ($row = pg_fetch_row($consulta_bien_servi_iva_no)) {
                    //                        $valor_Servicio1_iva_no = $row[0];
                    //                    }
                    //                 
                    //                    if ($valor_Servicio1_iva_no != '') {
                    //                        if ($sumaSubtotalTarifa0B > 0) {
                    //                            $fila1[0] = $fila1[0] + 1;
                    //                            $merca = pg_query("select cuenta_debito from parametros where descripcion='OTROS INGRESOS'");
                    //                            $fila3 = pg_fetch_row($merca);
                    //                            echo '<br>GUARDAR FACTURA VENTA3333: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila3[0] . "','0.000','$valor_Servicio1_iva_no','Activo')"; //////////////////////////
                    //
                    //                            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila3[0] . "','0.000','$valor_Servicio1_iva_no','Activo')");
                    //                        }
                    //                    }
                    if ($sumaSubtotalTarifa0 > 0) {
                        $fila1[0] = $fila1[0] + 1;
                        $merca = pg_query("select cuenta_debito from parametros where descripcion='VENTA MERCADERIA TARIFA0'");
                        $fila3 = pg_fetch_row($merca);
                        //                        echo '<br>GUARDAR FACTURA VENTA334: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa0','0.000','$sumaSubtotalTarifa0','Activo')"; //////////////////////////

                        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa0','0.000','$sumaSubtotalTarifa0','Activo')");
                    }

                    //Añadir Anticipo
                    $consulta_mixto = pg_query("select sum(x.sum) from (select formas_pago_mixto.forma_pago,sum(formas_pago_mixto.valor) from facturas_novalidas, formas_pago_mixto 
                    where facturas_novalidas.id_facturas_novalidas=formas_pago_mixto.id_factura_venta and facturas_novalidas.id_facturas_novalidas='$cont1' 
                    and (formas_pago_mixto.forma_pago='CHEQUE'  or formas_pago_mixto.forma_pago='CONTADO')  and formas_pago_mixto.tipo_documento='NOTA' GROUP BY formas_pago_mixto.forma_pago
                    )x");
                    $valor_contado_cheque = "";
                    while ($row = pg_fetch_row($consulta_mixto)) {

                        $valor_contado_cheque = $row[0];
                    }
                    if ($valor_contado_cheque != "") {

                        $sql = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
                        $buscaCuenta = pg_fetch_row($sql);
                        $fila1[0] = $fila1[0] + 1;
                        //                        echo '<br>GUARDAR FACTURA VENTA3345HH: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','" . $valor_contado_cheque . "','0.000','Activo')"; //////////////////////////

                        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','" . $valor_contado_cheque . "','0.000','Activo')");
                    }

                    $consulta_mixto = pg_query("select formas_pago_mixto.forma_pago,formas_pago_mixto.valor from facturas_novalidas, formas_pago_mixto where facturas_novalidas.id_facturas_novalidas=formas_pago_mixto.id_factura_venta and facturas_novalidas.id_facturas_novalidas='$cont1' and formas_pago_mixto.forma_pago='TCREDITO' and formas_pago_mixto.tipo_documento='NOTA'");
                    while ($row = pg_fetch_row($consulta_mixto)) {
                        $cont2_mixto_tcredito = $row[0];
                        $valor_tcredito = $row[1];
                    }
                    if ($cont2_mixto_tcredito != "") {

                        $sql = pg_query("select cuenta_debito from parametros where descripcion='TARJETA DE CREDITO'");
                        $buscaCuenta = pg_fetch_row($sql);
                        $fila1[0] = $fila1[0] + 1;
                        //                        echo '<br>GUARDAR FACTURA VENTA3345j: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','" . $valor_contado . "','0.000','Activo')"; //////////////////////////

                        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','" . $valor_tcredito . "','0.000','Activo')");
                    }
                    $consulta_mixto = pg_query("select formas_pago_mixto.forma_pago,formas_pago_mixto.valor,formas_pago_mixto.id_cuenta from facturas_novalidas, formas_pago_mixto where facturas_novalidas.id_facturas_novalidas=formas_pago_mixto.id_factura_venta and facturas_novalidas.id_facturas_novalidas='$cont1' and formas_pago_mixto.forma_pago='TRANSFERENCIAS' and formas_pago_mixto.tipo_documento='NOTA'");
                    while ($row = pg_fetch_row($consulta_mixto)) {
                        $cont2_mixto_transferencias = $row[0];
                        $valor_transferencias = $row[1];
                        $id_cuenta_banco = $row[2];
                    }
                    if ($cont2_mixto_transferencias != "") {

                        $sql = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
                        $buscaCuenta = pg_fetch_row($sql);
                        $fila1[0] = $fila1[0] + 1;
                        //                        echo '<br>GUARDAR FACTURA VENTA3345: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$_POST[cuenta_cheque]','" . $valor_contado . "','0.000','Activo')"; //////////////////////////

                        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$id_cuenta_banco','" . $valor_transferencias . "','0.000','Activo')");
                    }
                    $consulta_mixto = pg_query("select formas_pago_mixto.forma_pago,formas_pago_mixto.valor from facturas_novalidas, formas_pago_mixto where facturas_novalidas.id_facturas_novalidas=formas_pago_mixto.id_factura_venta and facturas_novalidas.id_facturas_novalidas='$cont1' and formas_pago_mixto.forma_pago='CREDITO' and formas_pago_mixto.tipo_documento='NOTA'");
                    while ($row = pg_fetch_row($consulta_mixto)) {
                        $cont2_mixto_credito = $row[0];
                        $valor_credito = $row[1];
                    }
                    if ($cont2_mixto_credito != "") {

                        $fila1[0] = $fila1[0] + 1;
                        $plancaja4 = pg_query("select cuenta_debito from parametros where descripcion='CUENTAS POR COBRAR'");
                        $fila4 = pg_fetch_row($plancaja4);
                        $totalCuentaXCobrar = $_POST['tot'];

                        //                        echo '<br>GUARDAR FACTURA VENTA33456: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila4[0] . "','" . $valor_contado . "','0.000','Activo')"; //////////////////////////

                        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila4[0] . "','" . $valor_credito . "','0.000','Activo')");
                    }

                    /////////////////////CHEQUE POSFECHADO////////////////////////////////////////////////////
                    //                    echo '<br>GUARDAR FACTURA POST: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$_POST[cuenta_cheque]','" . $valor_contado . "','0.000','Activo')"; //////////////////////////


                    $consulta_mixto = pg_query("select formas_pago_mixto.forma_pago,formas_pago_mixto.valor from facturas_novalidas, formas_pago_mixto where facturas_novalidas.id_facturas_novalidas=formas_pago_mixto.id_factura_venta and facturas_novalidas.id_facturas_novalidas='$cont1' and formas_pago_mixto.forma_pago='CPOSFECHADO' and formas_pago_mixto.tipo_documento='NOTA'");
                    while ($row = pg_fetch_row($consulta_mixto)) {
                        $cont2_mixto_pos = $row[0];
                        $valor_credito_post = $row[1];
                    }
                    if ($cont2_mixto_pos != "") {

                        $fila1[0] = $fila1[0] + 1;
                        $plancaja4 = pg_query("select cuenta_debito from parametros where descripcion='CUENTAS POR COBRAR'");
                        $fila4 = pg_fetch_row($plancaja4);
                        $totalCuentaXCobrar = $_POST['tot'];

                        //                        echo '<br>GUARDAR FACTURA VENTA33456: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila4[0] . "','" . $valor_contado . "','0.000','Activo')"; //////////////////////////

                        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila4[0] . "','" . $valor_credito_post . "','0.000','Activo')");
                    }


                    //////COSTO VENTA NOTA DE VENTA OTROS /////////////////
                    $plancaja4 = pg_query("select cuenta_debito from parametros where descripcion='COSTO VENTA'");
                    $fila4 = pg_fetch_row($plancaja4);
                    $fila1[0] = $fila1[0] + 1;

                    $consulta_bien_servi1 = pg_query(" SELECT sum(productos.precio_compra)
            FROM detalle_facturas_novalidas,productos where productos.cod_productos=detalle_facturas_novalidas.cod_productos 
            and detalle_facturas_novalidas.id_facturas_novalidas='$cont1' and detalle_facturas_novalidas.bien_servicio='B'");
                    while ($row = pg_fetch_row($consulta_bien_servi1)) {
                        $valor_Servicio1 = $row[0];
                    }
                    if ($valor_Servicio1 != '') {
                        if ($inventario12B > 0) {
                            $total0total12B = $inventario12B + $inventario0B;
                            //                            echo '<br>GUARDAR FACTURA VENTA677: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $fila4[0] . "','" . $total0total12B . "','0.000','Activo')"; //////////////////////////
                            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $fila4[0] . "','" . $total0total12B . "','0.000','Activo')");

                            $plancaja44 = pg_query("select cuenta_debito from parametros where descripcion='BIENES'");
                            $fila44 = pg_fetch_row($plancaja44);


                            $fila1[0] = $fila1[0] + 1;

                            //                            echo '<br>GUARDAR FACTURA VENTA6f1: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $codplanTarifa12B . "','0.000','" . $total0total12B . "','Activo')"; //////////////////////////

                            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $codplanTarifa12B . "','0.000','" . $total0total12B . "','Activo')");
                        } else if ($inventario0B > 0) {
                            $total0total12 = $inventario12B + $inventario0B;
                            //                            echo '<br>GUARDAR FACTURA VENTA44OTR: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $fila4[0] . "','" . $total0total12 . "','0.000','Activo')"; //////////////////////////
                            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $fila4[0] . "','" . $total0total12 . "','0.000','Activo')");


                            $plancaja44 = pg_query("select cuenta_debito from parametros where descripcion='BIENES'");
                            $fila44 = pg_fetch_row($plancaja44);


                            $fila1[0] = $fila1[0] + 1;
                            //                            echo '<br>GUARDAR FACTURA VENTA44OTRHH: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $fila4[0] . "','" . $total0total12 . "','0.000','Activo')"; //////////////////////////

                            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','$codplanTarifa0B','0.000','" . $total0total12 . "','Activo')");
                        }
                    }



                    ///////////////////
                    ///////////////////
                } else {
                    if ($forma == "Contado" || $forma == "otros") {
                        for ($i = 1; $i < $nelem; $i++) {
                            if (!empty($arreglo1[$i])) {

                                $consulta_bien_servi = pg_query(" select bien_servicios from productos where cod_productos=$arreglo1[$i]");
                                while ($row = pg_fetch_row($consulta_bien_servi)) {
                                    $valor_Servicio = $row[0];
                                }
                                // contador detalle_factura_novalidas
                                $cont6 = 0;
                                $consulta = pg_query("select  max(id_detalle_facturas_novalidas) from detalle_facturas_novalidas");
                                while ($row = pg_fetch_row($consulta)) {
                                    $cont6 = $row[0];
                                }
                                $cont6++;
                                // fin  
                                // guardar detalle_factura_novalidas
                                if ($guardarnv) {
                                    //echo '<br>GUARDAR NOTA VENTArrggfffbbbf1: <br>' . "insert into detalle_facturas_novalidas values('$cont6','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','Activo','$arreglo6[$i]','$valor_Servicio','$arreglo8[$i]','$arreglo9[$i]')"; //////////////////////////

                                    $sql = "insert into detalle_facturas_novalidas 
                                    (
                                        id_detalle_facturas_novalidas, id_facturas_novalidas, cod_productos, 
                                        cantidad, precio_venta, descuento_producto, total_venta, estado, 
                                        pendientes, bien_servicio, cantidad_unidad, unidad_medida, detalle_producto)
                                    values('$cont6','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','Activo','$arreglo6[$i]','$valor_Servicio','$arreglo8[$i]','$arreglo9[$i]','$arreglo10[$i]')";
                                    $guardar = guardarSql($conexion, $sql);

                                    if ($guardar == 'true') {
                                        guardarDetalleImpuestoProductoNv($arreglocods_impuesto[$i], $arreglocods_tarifa[$i], $arreglotarifas[$i], $arreglovlores_iva[$i], $arreglo5[$i], $cont6);
                                        $data = 22;
                                    } else {
                                        error_log_fv(0, "id_factura=$cont1", "facturas_novalidas.php", 343);
                                        error_log_fv(0, pg_last_error($conexion), "facturas_novalidas.php", 360);
                                        error_log_fv(0, pg_last_error($guardar), "facturas_novalidas.php", 360);
                                        error_log_fv(0, pg_last_error($sql), "facturas_novalidas.php", 360);
                                        //                                    echo '<br>GUARDAR FACTURA DETALLE: <br>' . "insert into detalle_facturas_novalidas values('$cont6','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]',"
                                        //                                    . "'$arreglo4[$i]','$arreglo5[$i]','Activo','$arreglo6[$i]','$_POST[fecha_actual]','$valor_Servicio')"; //////////////////////////

                                        pg_query("DELETE FROM facturas_novalidas WHERE id_facturas_novalidas='$cont1';");

                                        //
                                        //                                    echo '<br>GUARDAR FACTURA delete: <br>' . "DELETE FROM facturas_novalidas WHERE id_facturas_novalidas='$cont1';";

                                        $data = 60; /// error al guardar
                                        $item = array('estado' => $data);
                                    } // fin
                                } else {
                                    $data = 60; /// error al guardar
                                    $item = array('estado' => $data);
                                }
                                // fin
                                //            // modificar productos
                                //            $consulta2=pg_query("select * from productos where cod_productos = '$arreglo1[$i]'");
                                //            while($row=pg_fetch_row($consulta2))
                                //             {
                                //              $stock=$row[13];
                                //             }
                                //            $cal=$stock-$arreglo2[$i];
                                //            
                                //            pg_query("Update productos Set stock='" . $cal . "' where cod_productos='" . $arreglo1[$i] . "'");
                                //            // fin
                                $contb = 0;
                                $consulta = pg_query("select max(id_detalle_productos_bodega) from detalle_producto_bodega");
                                while ($row = pg_fetch_row($consulta)) {
                                    $contb = $row[0];
                                }
                                $contb++;

                                // guardar detalle productos bodega

                                $consulta_v = pg_query("select * from detalle_producto_bodega where id_bodega=$pvinv and cod_productos=$arreglo1[$i]");
                                while ($row = pg_fetch_row($consulta_v)) {
                                    $cod_pro = $row[1];
                                    $id_bod = $row[2];
                                    $stock = $row[6];
                                }
                                $cal = $stock - $arreglo2[$i];

                                if ($cod_pro == $arreglo1[$i] && $id_bod == $conpuntoresult) {
                                    /* DESBLOQUEAR pg_query("Update detalle_producto_bodega Set fecha='" . $_POST[fecha_actual] . "' ,hora='" . $_POST[hora_actual] . "', stock='" . $cal . "' "
                                      . "where cod_productos='" . $arreglo1[$i] . "' and id_bodega='" . $conpuntoresult . "' "); */
                                } else {
                                    $contb = 0;
                                    $consulta = pg_query("select max(id_detalle_productos_bodega) from detalle_producto_bodega");
                                    while ($row = pg_fetch_row($consulta)) {
                                        $contb = $row[0];
                                    }
                                    $contb++;
                                    $horap = date("g:ia");
                                    /* DESBLOQUEAR pg_query("insert into detalle_producto_bodega values('$contb','$arreglo1[$i]','$conpuntoresult','$_SESSION[id]',"
                                      . "'$_POST[fecha_actual]','$horap','$cal')"); */
                                }
                                // contador kardex valorizado
                                // contador kardex
                                $cont_k = 0;
                                $consulta_k = pg_query("select max(id_kardex) from kardex");
                                while ($row = pg_fetch_row($consulta_k)) {
                                    $cont_k = $row[0];
                                }
                                $cont_k++;
                                // fin
                                $cont_v = 0;
                                $consulta_v = pg_query("select max(id_kardex) from kardex_valorizado");
                                while ($row = pg_fetch_row($consulta_v)) {
                                    $cont_v = $row[0];
                                }
                                $cont_v++;
                                // fin
                                $cantidad = 0;
                                $precio_total = 0;
                                $precio_unitario = 0;
                                $consulta2 = pg_query("select * from kardex_valorizado where cod_productos = '$arreglo1[$i]' order by id_kardex desc limit 1");
                                while ($row = pg_fetch_row($consulta2)) {
                                    $cantidad = $row[11];
                                    $precio_unitario = $row[7];
                                    $precio_total = $row[8];
                                }

                                $cantidad_salida = $arreglo2[$i];
                                $precio_unitario_salida = number_format($precio_unitario, 4, '.', '');
                                $precio_total_salida = number_format($arreglo2[$i] * $precio_unitario, 2, '.', '');

                                $cantidad_total = $cantidad - $arreglo2[$i];
                                $precio_total_total = number_format($precio_total - $precio_total_salida, 2, '.', '');
                                $precio_unitario_total = number_format($precio_total_total / $cantidad_total, 4, '.', '');

                                //pg_query("insert into kardex_valorizado values(" . $cont_v . ",'" . $arreglo1[$i] . "','$_POST[fecha_actual]', '" . 'Nota Venta: ' . $_POST['num_factura'] . "','','" . $cantidad_salida . "','" . $cantidad . "','" . $precio_unitario_salida . "','" . $precio_total_salida . "','','','" . $cantidad_total . "','4')");
                                // fin
                                //////// COSTO DE VENTA OTROS NOTAS DE VENTA//////////////////////////                        
                                // consulta kardex valorizado
                                $cantidad = 0;
                                $precio_total = 0;
                                $precio_unitario = 0;
                                $costoVenta = 0;
                                $consulta2 = pg_query("select * from kardex_valorizado where cod_productos = '$arreglo1[$i]' order by id_kardex desc limit 1");
                                while ($row = pg_fetch_row($consulta2)) {
                                    $cantidad = $row[11];
                                    $precio_unitario = $row[7]; //round($row[7], 4);
                                    $precio_total = $row[8]; //round($row[8], 4);
                                    $costoVenta = $row[13]; //round($row[13], 4);
                                }
                                if ($costoVenta == "0.0000") {
                                    $costoVenta1 = $costoVenta1 + ($arreglo2[$i]);
                                } else {
                                    $costoVenta1 = $costoVenta1 + ($costoVenta * $arreglo2[$i]);
                                }
                                //////////////////////////////////////////////////////////////////////

                                if ($_POST['id_cliente'] == "") {
                                    $idCli = 0;
                                    $consulta_cli = pg_query("select max(id_cliente) from clientes");
                                    while ($row = pg_fetch_row($consulta_cli)) {
                                        $idCli = $row[0];
                                    }
                                    // guardar kardex
                                    //pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '" . 'N.V:' . $_POST['num_factura'] . "' ,'$arreglo2[$i]','$arreglo3[$i]','$arreglo5[$i]','$arreglo1[$i]','$cal','2','','','$idCli','$cont1','NV','$conpuntoresult')");

                                    /* DESBLOQUEAR CODIGO ESTEBAN procesarKardexSalida($arreglo1[$i], 'N.V:' . $_POST['num_factura'], $arreglo2[$i], obtenerStock($arreglo1[$i], $_SESSION['PV']), 
                                      obtenerValoresPromedios($arreglo1[$i])[0]['venta_promedio'], 'Activo', $conpuntoresult, 'V', $cont1, $arreglo5[$i], NULL, NULL,
                                      $idCli, '', NULL, NULL, $_SESSION['id']); */
                                    if ($data == 22) {
                                        if ($arreglo8[$i] != 0) {
                                            $arreglo2[$i] = $arreglo8[$i];
                                        } else {
                                            $arreglo2[$i] = $arreglo2[$i];
                                        }
                                        procesarKardexSalida($arreglo1[$i], 'N.V:' . $_POST['num_factura'], $arreglo2[$i], obtenerStock($arreglo1[$i], $pvinv), NULL, 'Activo', $pvinv, 'V', $cont1, $arreglo5[$i], NULL, NULL, $idCli, '', NULL, NULL, $_SESSION['id']);
                                    } // fin
                                } else {
                                    $cliente1 = $_POST['id_cliente'];
                                    pg_query("Update clientes Set nombres_cli='" . strtoupper($_POST['nombre_cliente']) . "', direccion_cli='" . strtoupper($_POST['direccion_cliente']) . "', telefono='$_POST[telefono_cliente]', correo='$_POST[correo]' where id_cliente='$cliente1'");

                                    //pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '" . 'N.V:' . $_POST['num_factura'] . "' ,'$arreglo2[$i]','$arreglo3[$i]','$arreglo5[$i]','$arreglo1[$i]','$cal','2','','','$cliente1','$cont1','NV','$conpuntoresult')");

                                    /* DESBLOQUEAR CODIGO ESTEBAN procesarKardexSalida($arreglo1[$i], 'N.V:' . $_POST['num_factura'], $arreglo2[$i], obtenerStock($arreglo1[$i], $_SESSION['PV']), 
                                      obtenerValoresPromedios($arreglo1[$i])[0]['venta_promedio'], 'Activo', $conpuntoresult, 'V', $cont1, $arreglo5[$i], NULL, NULL,
                                      $_POST['id_cliente'], '', NULL, NULL, $_SESSION['id']); */
                                    if ($data == 22) {
                                        if ($arreglo8[$i] != 0) {
                                            $arreglo2[$i] = $arreglo8[$i];
                                        } else {
                                            $arreglo2[$i] = $arreglo2[$i];
                                        }
                                        procesarKardexSalida($arreglo1[$i], 'N.V:' . $_POST['num_factura'], $arreglo2[$i], obtenerStock($arreglo1[$i], $pvinv), NULL, 'Activo', $pvinv, 'V', $cont1, $arreglo5[$i], NULL, NULL, $_POST['id_cliente'], '', NULL, NULL, $_SESSION['id']);
                                    }
                                }

                                ////////////////////////
                                //Asiento Contable NOTA DE VENTA CONTADO////
                                //                                echo '<br>GUARDAR FACTURA VENTA: <br>' . "select iva,id_plan_cuentas from productos where cod_productos ='" . $arreglo1[$i] . "' and bien_servicios='B'"; //////////////////////////

                                $cuenta = pg_query("select iva,id_plan_cuentas from productos where cod_productos ='" . $arreglo1[$i] . "' and bien_servicios='B'");
                                $plan = pg_fetch_row($cuenta);
                                //                                print_r($costoVenta . ":aqui:");
                                if ($plan[0] == "Si") {
                                    if ($costoVenta == "0.0000") {
                                        $inventario12B = $inventario12B + ($arreglo2[$i]);
                                    } else {
                                        $inventario12B = $inventario12B + ($costoVenta * $arreglo2[$i]);
                                    }


                                    $sumaSubtotalTarifa12B = $sumaSubtotalIva12B + $arreglo5[$i];
                                    $codplanTarifa12B = $plan[1];
                                    $contTarifa12++;
                                } else if ($plan[0] == "No") {
                                    if ($costoVenta == "0.0000") {
                                        $inventario0B = $inventario0B + ($arreglo2[$i]);
                                    } else {
                                        $inventario0B = $inventario0B + ($costoVenta * $arreglo2[$i]);
                                    }


                                    $sumaSubtotalTarifa0B = $sumaSubtotalTarifa0B + $arreglo5[$i];
                                    $codplanTarifa0B = $plan[1];
                                    $contTarifa0++;
                                }
                                //Asiento Contable 
                                $cuenta = pg_query("select iva,id_plan_cuentas from productos where cod_productos ='" . $arreglo1[$i] . "' and bien_servicios='S'");
                                $plan = pg_fetch_row($cuenta);

                                if ($plan[0] == "Si") {
                                    if ($costoVenta == "0.0000") {
                                        $inventario12 = $inventario12 + ($arreglo2[$i]);
                                    } else {
                                        $inventario12 = $inventario12 + ($costoVenta * $arreglo2[$i]);
                                    }

                                    $sumaSubtotalTarifa12 = $sumaSubtotalIva12 + $arreglo5[$i];
                                    $codplanTarifa12 = $plan[1];
                                    $contTarifa12++;
                                } else if ($plan[0] == "No") {
                                    if ($costoVenta == "0.0000") {
                                        $inventario0 = $inventario0 + ($arreglo2[$i]);
                                    } else {
                                        $inventario0 = $inventario0 + ($costoVenta * $arreglo2[$i]);
                                    }

                                    $sumaSubtotalTarifa0 = $sumaSubtotalTarifa0 + $arreglo5[$i];
                                    $codplanTarifa0 = $plan[1];
                                    $contTarifa0++;
                                }
                            }
                        }

                        ////////////////////////////CREACION ASIENTO CONTADO - CHEQUE NOTA VENTA
                        // guardar asiento contable

                        $idtran = pg_query("select max(id_transacciones) from transacciones");
                        $fila = pg_fetch_row($idtran);
                        $fila[0] = $fila[0] + 1;
                        $sum = 0;
                        $bool = true;
                        $pos = 0;
                        $vec = 0;
                        $tieneiva;
                        $ivafin = 0;
                        $auxiliar = $arreglo1;
                        $abc = 0;
                        $xy = 0;
                        $iva = pg_query("select valor from parametros where descripcion='IVA'");
                        while ($ivavalor = pg_fetch_row($iva)) {
                            $ivafin = $ivavalor[0];
                        }
                        $abc = ($ivafin + 100) / 100;
                        while ($bool) {
                            $cuenta = pg_query("select id_plan_cuentas from productos where cod_productos='" . $auxiliar[0] . "'");
                            $plan = pg_fetch_row($cuenta);
                            $nelem = count($auxiliar);
                            $vec = 0;
                            for ($i = 0; $i <= $nelem; $i++) {
                                $cuenta1 = pg_query("select id_plan_cuentas from productos where cod_productos='" . $auxiliar[$i] . "'");
                                $cIva = pg_query("select incluye_iva from productos where cod_productos='" . $auxiliar[$i] . "'");
                                $plan1 = pg_fetch_row($cuenta1);
                                $si = pg_fetch_row($cIva);
                                $tieneiva = $si[0];
                                if ($plan1[$i] == $plan[0]) {
                                    if ($tieneiva == "Si") {
                                        $sum = ($arreglo5[$i] / $abc) + $sum;
                                    } else {
                                        //$aa++;
                                        $sum = $arreglo5[$i] + $sum;
                                    }
                                    //$sum=$arreglo5[$i]+$sum;
                                } else {
                                    $vec[$pos] = $auxiliar[$i];
                                    $pos++;
                                }
                                //                            $bien_servicio = pg_query("SELECT  bien_servicios FROM productos where cod_productos='" . $arreglo1[$i] . "'");
                                //                    $bien_servicioid = pg_fetch_row($bien_servicio);
                                //                    $bien_serviciob = $bien_servicioid[0];
                                //                     print_r($bien_serviciob."ll");
                            }
                            if ($vec == 0) {
                                $bool = false;
                            } else {
                                $auxiliar = $vec;
                                $pos = 0;
                            }
                        }
                        $sum = $sum;
                        $xy = $sum + $_POST['iva'];
                        $saldo = $xy - $_POST['tot'];
                        $cliente1 = "";
                        if ($_POST['id_cliente'] == "") {
                            $cliente1 = $contt;
                        } else {
                            $cliente1 = $_POST['id_cliente'];
                        }
                        $prove = pg_query("select identificacion from clientes where id_cliente='$cliente1'");
                        $p = pg_fetch_row($prove);
                        $ing = pg_query("select max(num_transaccion) from transacciones where id_tipo_transaccion='1' and id_empresa= '$_SESSION[PV]'");
                        $res = pg_fetch_row($ing);
                        $ing_pv = pg_query("select max(id_transaccion_pv::int) from transacciones where  id_empresa= '$_SESSION[PV]'");
                        $res_pv = pg_fetch_row($ing_pv);
                        //                  print_r("trans2".$costoVenta1);
                        //echo '<br>GUARDAR FACTURA VENTA12: <br>' . "insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'NOTA VENTA PRODUCTOS, CLIENTE: " . $p[0] . ", COMPROBANTE: " . $_POST['num_factura'] . ", : " . $_POST['marca_vehiculo'] . "', '" . $xy . "', '$_POST[tot]', '" . $saldo . "','1','" . ($res[0] + 1) . "','Activo','$cliente1','','','','','NV','',$conpuntoresult,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')";//////////////////////////
                        if ($data == 22) {
                            $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'NOTA VENTA PRODUCTOS, CLIENTE: " . $p[0] . ", COMPROBANTE: " . $_POST['num_factura'] . ", : " . $_POST['marca_vehiculo'] . "', '" . $xy . "', '$_POST[tot]', '" . $saldo . "','1','" . ($res[0] + 1) . "','Activo','$cliente1','','','','','NV','',$conpuntoresult,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')");
                            //                  print_r("transjj".$bien_serviciob);
                        }
                        /////////////////////////////////////NOTA DE VENTA///////////
                        $consulta_bien_servi = pg_query(" SELECT sum(productos.precio_compra)
                                    FROM detalle_facturas_novalidas,productos where productos.cod_productos=detalle_facturas_novalidas.cod_productos 
                                    and detalle_facturas_novalidas.id_facturas_novalidas='$cont1' and detalle_facturas_novalidas.bien_servicio='B'");
                        while ($row = pg_fetch_row($consulta_bien_servi)) {
                            $valor_Servicio1 = $row[0];
                        }
                        //                    print_r($inventario12B);
                        if ($valor_Servicio1 != '') {
                            if ($inventario12B > 0) {
                                $total0total12B = $inventario12B + $inventario0B;

                                if ($data == 22) {
                                    //echo '<br>GUARDAR FACTURA VENTA12 nv: <br>' . "insert into transacciones values('" . ($fila[0] + 1) . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'COSTO NOTA VENTA PRODUCTOS, CLIENTE: " . $p[0] . ", COMPROBANTE: " . $_POST['num_factura'] . ", : " . $_POST['marca_vehiculo'] . "', '" . $total0total12B . "', '" . $total0total12B . "', '0.000','1','" . ($res[0] + 1) . "','Activo','$cliente1','','','','','NV','',$conpuntoresult,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')"; //////////////////////////

                                    $asiento2 = pg_query("insert into transacciones values('" . ($fila[0] + 1) . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'COSTO NOTA VENTA PRODUCTOS, CLIENTE: " . $p[0] . ", COMPROBANTE: " . $_POST['num_factura'] . ", : " . $_POST['marca_vehiculo'] . "', '" . $total0total12B . "', '" . $total0total12B . "', '0.000','1','" . ($res[0] + 1) . "','Activo','$cliente1','','','','','NV','',$conpuntoresult,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')");
                                }
                            } else if ($inventario0B > 0) {
                                $total0total12 = $inventario12B + $inventario0B;

                                if ($data == 22) {
                                    //echo '<br>GUARDAR FACTURA VENTA1 nv: <br>' . "insert into transacciones values('" . ($fila[0] + 1) . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'COSTO NOTA VENTA PRODUCTOS, CLIENTE: " . $p[0] . ", COMPROBANTE: " . $_POST['num_factura'] . ", : " . $_POST['marca_vehiculo'] . "', '" . $total0total12 . "', '" . $total0total12 . "', '0.000','1','" . ($res[0] + 1) . "','Activo','$cliente1','','','','','NV','',$conpuntoresult,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')"; //////////////////////////

                                    $asiento2 = pg_query("insert into transacciones values('" . ($fila[0] + 1) . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'COSTO NOTA VENTA PRODUCTOS, CLIENTE: " . $p[0] . ", COMPROBANTE: " . $_POST['num_factura'] . ", : " . $_POST['marca_vehiculo'] . "', '" . $total0total12 . "', '" . $total0total12 . "', '0.000','1','" . ($res[0] + 1) . "','Activo','$cliente1','','','','','NV','',$conpuntoresult,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')");
                                }
                            }
                        }

                        /////DETALLES TRANSACCION
                        ////////////////////
                        $valor_Servicio1_iva = '';
                        $consulta_bien_servi_iva = pg_query("select   sum(detalle_facturas_novalidas.total_venta) from detalle_facturas_novalidas,productos where productos.cod_productos=detalle_facturas_novalidas.cod_productos   and detalle_facturas_novalidas.bien_servicio='B' and detalle_facturas_novalidas.id_facturas_novalidas='$cont1' ");
                        while ($row = pg_fetch_row($consulta_bien_servi_iva)) {
                            $valor_Servicio1_iva = $row[0];
                        }

                        $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
                        $fila1 = pg_fetch_row($iddettran);
                        if ($valor_Servicio1_iva != '') {
                            $valor_Servicio1_iva = $valor_Servicio1_iva + $_POST['iva'];
                            //                            if ($sumaSubtotalTarifa12B > 0) {
                            $fila1[0] = $fila1[0] + 1;
                            $merca = pg_query("select cuenta_debito from parametros where descripcion='OTROS INGRESOS'");
                            $fila3 = pg_fetch_row($merca);

                            if ($data == 22) {
                                //                                    echo '<br>GUARDAR FACTURA VENTA2B nv: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila3[0] . "','0.000','$valor_Servicio1_iva','Activo')"; //////////////////////////
                                pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila3[0] . "','0.000','$valor_Servicio1_iva','Activo')");
                            }
                            //                            }
                        }
                        $consulta_bien_servi_ivas = pg_query("select   sum(detalle_facturas_novalidas.total_venta) from detalle_facturas_novalidas,productos where productos.cod_productos=detalle_facturas_novalidas.cod_productos   and detalle_facturas_novalidas.bien_servicio='S' and detalle_facturas_novalidas.id_facturas_novalidas='$cont1' and productos.iva='Si'");
                        while ($row = pg_fetch_row($consulta_bien_servi_ivas)) {
                            $valor_Servicio1_ivas = $row[0];
                        }
                        $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
                        $fila1 = pg_fetch_row($iddettran);
                        if ($valor_Servicio1_ivas != '') {
                            if ($sumaSubtotalTarifa12 > 0) {
                                $fila1[0] = $fila1[0] + 1;
                                $merca = pg_query("select cuenta_debito from parametros where descripcion='VENTA MERCADERIA TARIFA12'");
                                $fila3 = pg_fetch_row($merca);

                                if ($data == 22) {
                                    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa12','0.000','$sumaSubtotalTarifa12','Activo')");
                                    //                                    echo '<br>GUARDAR FACTURA VENTA2 nv: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa12','0.000','$valor_Servicio1_ivas','Activo')"; //////////////////////////
                                }
                            }
                        }
                        //                        $consulta_bien_servi_iva_no = pg_query("select   sum(detalle_facturas_novalidas.total_venta) from detalle_facturas_novalidas,productos where productos.cod_productos=detalle_facturas_novalidas.cod_productos   and detalle_facturas_novalidas.bien_servicio='B' and detalle_facturas_novalidas.id_facturas_novalidas='$cont1' and productos.iva='No'");
                        //                        while ($row = pg_fetch_row($consulta_bien_servi_iva_no)) {
                        //                            $valor_Servicio1_iva_no = $row[0];
                        //                        }
                        //                        if ($valor_Servicio1_iva_no != '') {
                        //                            if ($sumaSubtotalTarifa0B > 0) {
                        //                                $fila1[0] = $fila1[0] + 1;
                        //                                $merca = pg_query("select cuenta_debito from parametros where descripcion='OTROS INGRESOS'");
                        //                                $fila3 = pg_fetch_row($merca);
                        //
                        //                                if ($data == 22) {
                        //                                    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila3[0] . "','0.000','$sumaSubtotalTarifa0B','Activo')");
                        ////                                    echo '<br>GUARDAR FACTURA VENTA3 nv: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila3[0] . "','0.000','$valor_Servicio1_iva_no','Activo')"; //////////////////////////
                        //                                }
                        //                            }
                        //                        }

                        if ($sumaSubtotalTarifa0 > 0) {
                            $fila1[0] = $fila1[0] + 1;
                            $merca = pg_query("select cuenta_debito from parametros where descripcion='VENTA MERCADERIA TARIFA0'");
                            $fila3 = pg_fetch_row($merca);

                            if ($data == 22) {
                                //                                echo '<br>GUARDAR FACTURA VENTA33 nv: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa0','0.000','$sumaSubtotalTarifa0','Activo')"; //////////////////////////
                                pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa0','0.000','$sumaSubtotalTarifa0','Activo')");
                            }
                        }
                        ///////////////////
                        //                        $planiva = pg_query("select cuenta_credito from parametros where descripcion='IVA'");
                        //                        $fila2 = pg_fetch_row($planiva);
                        //                        if ($_POST['iva'] != '0') {
                        //                            $fila1[0] = $fila1[0] + 1;
                        //                            echo '<br>GUARDAR FACTURA VENTA33iva: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','0.000','" . $_POST['iva'] . "','Activo')"; //////////////////////////
                        //                            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','0.000','" . $_POST['iva'] . "','Activo')");
                        //                        }
                        $fila1[0] = $fila1[0] + 1;
                        if ($_POST["cuenta_cheque"] != "") {

                            $plancaja4 = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
                            $fila4 = pg_fetch_row($plancaja4);

                            //                            echo '<br>GUARDAR FACTURA VENTAGGFFD nv: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$_POST[cuenta_cheque]','" . $_POST['tot'] . "','0.000','Activo')"; //////////////////////////


                            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$_POST[cuenta_cheque]','" . $_POST['tot'] . "','0.000','Activo')");
                        } else {
                            $plancaja4 = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
                            $fila4 = pg_fetch_row($plancaja4);


                            if ($codplanTarifa12 == '270' || $codplanTarifa0 == '270') {
                                //                                echo '<br>GUARDAR FACTURA VENTAGGFFDF: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','9','" . $_POST['tot'] . "','0.000','Activo')"; //////////////////////////

                                pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','9','" . $_POST['tot'] . "','0.000','Activo')");
                            } else if ($codplanTarifa12 != '270' || $codplanTarifa0 != '270') {
                                //                                echo '<br>GUARDAR FACTURA VENTAGGFFDFtt: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila4[0] . "','" . $_POST['tot'] . "','0.000','Activo')"; //////////////////////////

                                pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila4[0] . "','" . $_POST['tot'] . "','0.000','Activo')");
                            }
                        }
                        /////////////////// COSTO DE VENTA NOTA DE VENTA CONTADO
                        //detalle costo de ventas
                        $plancaja4 = pg_query("select cuenta_debito from parametros where descripcion='COSTO VENTA'");
                        $fila4 = pg_fetch_row($plancaja4);
                        $fila1[0] = $fila1[0] + 1;
                        $consulta_bien_servi = pg_query(" SELECT sum(productos.precio_compra)
                                  FROM detalle_facturas_novalidas,productos where productos.cod_productos=detalle_facturas_novalidas.cod_productos 
                                 and detalle_facturas_novalidas.id_facturas_novalidas='$cont1' and detalle_facturas_novalidas.bien_servicio='B'");
                        while ($row = pg_fetch_row($consulta_bien_servi)) {
                            $valor_Servicio1 = $row[0];
                        }
                        if ($valor_Servicio1 != '') {

                            if ($inventario12B > 0) {
                                $total0total12B = $inventario12B + $inventario0B;

                                if ($data == 22) {
                                    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $fila4[0] . "','" . $total0total12B . "','0.000','Activo')");
                                    //                                    echo '<br>GUARDAR FACTURA VENTA4 nv: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $fila4[0] . "','" . $total0total12B . "','0.000','Activo')"; //////////////////////////
                                }
                                $plancaja44 = pg_query("select cuenta_debito from parametros where descripcion='BIENES'");
                                $fila44 = pg_fetch_row($plancaja44);


                                $fila1[0] = $fila1[0] + 1;

                                if ($data == 22) {
                                    //                                    echo '<br>GUARDAR FACTURA VENTA6f nv: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','$codplanTarifa12B','0.000','" . $total0total12B . "','Activo')"; //////////////////////////

                                    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','$codplanTarifa12B','0.000','" . $total0total12B . "','Activo')");
                                }
                            } else if ($inventario0B > 0) {
                                $total0total12 = $inventario12B + $inventario0B;

                                //                                print_r($inventario12B . "::");

                                if ($data == 22) {
                                    //                                    echo '<br>GUARDAR FACTURA VENTA44 nv: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $fila4[0] . "','" . $total0total12 . "','0.000','Activo')"; //////////////////////////
                                    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $fila4[0] . "','" . $total0total12 . "','0.000','Activo')");
                                }
                                $plancaja44 = pg_query("select cuenta_debito from parametros where descripcion='BIENES'");
                                $fila44 = pg_fetch_row($plancaja44);


                                $fila1[0] = $fila1[0] + 1;

                                if ($data = 22) {
                                    //                                    echo '<br>GUARDAR FACTURA VENTA6f nv: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $fila44[0] . "','0.000','" . $total0total12 . "','Activo')"; //////////////////////////
                                    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $fila44[0] . "','0.000','" . $total0total12 . "','Activo')");
                                }
                                if ($data == 22) {
                                    //                                echo '<br>GUARDAR FACTURA VENTA6f25: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','$codplanTarifa0B','0.000','" . $total0total12 . "','Activo')"; //////////////////////////
                                    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','$codplanTarifa0B','0.000','" . $total0total12 . "','Activo')");
                                }
                            }
                        }
                    }
                }
                $item = array('estado' => $data, 'id' => $cont1);
            }
        }
    }
} else if ($_POST["comprobante_quia"] != 0) {
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
    $valortxt9 = $_POST["fecha_actual"];

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
    //echo 'GUIA'."insert into guia_remision values('$contguias','$_POST[comprobante_quia]','$_POST[transportistaguia]','$_POST[fecha_actual]','$_POST[fecha_actual]','$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[num_guia]','','$clave','','$direc_guia','$_POST[direccion_cliente]','VENTA','$_POST[hora_actual]','$_POST[hora_actual]','$direc_guia.$_POST[direccion_cliente]','','','$conpuntoresult','$_POST[num_guia_remision]')";
    pg_query("insert into guia_remision values('$contguias','$_POST[comprobante_quia]','$_POST[transportistaguia]','$_POST[fecha_actual]','$_POST[fecha_actual]','$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[num_guia]','','$clave','','$direc_guia','$_POST[direccion_cliente]','VENTA','$_POST[hora_actual]','$_POST[hora_actual]','$direc_guia.$_POST[direccion_cliente]','','','$conpuntoresult','$_POST[num_guia_remision]')");
    $result = generarXMLGUIA($contguias, $codDoc, $ambiente, $emision);

    //    print_r($result);
    $doc = new DOMDocument('1.0', 'UTF-8');
    $doc->loadXML($result); // xml 
    $doc->save($pathXmls . "fac" . '.xml');
    //exec("$appFirma " . '../../xmls/' . $esquema . '/fac', $resultado);
    exec("$appFirma " . $pathXmls . '/fac "' . $pathARchivoP12 . '" "' . $claveFirma . '"', $resultado);

    try {
        $respuesta = consultarComprobante($ambiente, $consult_clave);
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

    // print_r(json_encode(array('estado' => $data, 'id' => $cont1)));
    ///////////////////cambio nota venta///////////////////
}
///pruebas
echo $data = json_encode($item);

///Actualizar proforma técnico////
function actualizarProformaTecnico($idproforma, $campo, $valor)
{
    $sql = "update proforma_tecnico
    set $campo=$valor
    where id_proforma=$idproforma";
    $res = pg_query($sql);
    if (!$res) {
        return null;
    }
    return $idproforma;
}

//francis subo inert minus correo

function guardarDetalleImpuestoProducto($codImpuesto, $codTarifa, $tarifa, $valoriva, $baseimponible, $iddetalle)
{
    $id = obtenerNextIdDetalleImpuestoProducto();
    $sql = "INSERT INTO detalle_impuesto_producto_venta(
        id_detalle_impuesto_producto_venta, cod_impuesto, cod_tarifa, 
        tarifa, valor_impuesto, base_imponible, id_detalle_venta)
    VALUES ($id, '$codImpuesto', '$codTarifa', 
            $tarifa, $valoriva, $baseimponible,$iddetalle);
    ";
    $res = pg_query($sql);
}

function obtenerNextIdDetalleImpuestoProducto()
{
    $sql = "select coalesce(max(id_detalle_impuesto_producto_venta),0)+1 from detalle_impuesto_producto_venta";
    $res = pg_query($sql);
    $row = pg_fetch_row($res);
    return $row[0];
}

function guardarDetalleImpuestoProductoNv($codImpuesto, $codTarifa, $tarifa, $valoriva, $baseimponible, $iddetalle)
{
    $id = obtenerNextIdDetalleImpuestoProductoNv();
    $sql = "INSERT INTO detalle_impuesto_producto_notaventa(
        id_detalle_impuesto_producto_notaventa, cod_impuesto, cod_tarifa, 
        tarifa, valor_impuesto, base_imponible, id_detalle_facturas_novalidas)
    VALUES ($id, '$codImpuesto', '$codTarifa', 
            $tarifa, $valoriva, $baseimponible,$iddetalle);
    ";
    $res = pg_query($sql);
}

function obtenerNextIdDetalleImpuestoProductoNv()
{
    $sql = "select coalesce(max(id_detalle_impuesto_producto_notaventa),0)+1 from detalle_impuesto_producto_notaventa";
    $res = pg_query($sql);
    $row = pg_fetch_row($res);
    return $row[0];
}


/*REGISTRAR CUENTAS ASIENTO IVA*/
function obtenerTarifasImpuestoFactura($id)
{
    $sql = "select
    di.cod_impuesto, 
    di.cod_tarifa, 
    di.tarifa, 
    sum(di.valor_impuesto)valor_impuesto, 
    sum(di.base_imponible)base_imponible
    from
    factura_venta fc
    inner join detalle_factura_venta dfc
    using(id_factura_venta)
    inner join detalle_impuesto_producto_venta di
    using(id_detalle_venta)
    where id_factura_venta=$id
    group by di.cod_tarifa, di.cod_impuesto, di.tarifa";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (!empty($rows)) {
        return $rows;
    }
    return [];
}

function obtenerIdCuentaIVAVentas($codimpuesto, $codtarifa)
{
    $sql = "select
    pc.id_cuenta_iva_ventas
    from tarifa_impuesto
    inner join tipo_impuesto using(id_timpu)
    inner join parametros_cuentas_contables_iva pc using(id_taimpuesto)
    where codigo_taimpuesto='$codtarifa' and codigo_timpu='$codimpuesto'";
    $res = pg_query($sql);
    $row = pg_fetch_row($res);
    if (empty($row)) {
        return 0;
    }
    return $row[0];
}

function obtenerIdCuentaVentas($codimpuesto, $codtarifa)
{
    $sql = "select
    pc.id_cuenta_ventas
    from tarifa_impuesto
    inner join tipo_impuesto using(id_timpu)
    inner join parametros_cuentas_contables_iva pc using(id_taimpuesto)
    where codigo_taimpuesto='$codtarifa' and codigo_timpu='$codimpuesto'";
    $res = pg_query($sql);
    $row = pg_fetch_row($res);
    if (empty($row)) {
        return 0;
    }
    return $row[0];
}

function insertDetalleTransaccion($idtransaccion, $idcuenta, $debito, $credito)
{
    $id = obtenerSiguienteIdDetTrans();
    $sql = "INSERT INTO detalle_transaccion(
        id_detalle_transaccion, id_transacciones, id_plan_cuentas, debito, 
        credito, estado, conciliado)
        VALUES ($id, $idtransaccion, $idcuenta, $debito, 
        $credito, 'Activo', null);
        ";
    $res = pg_query($sql);
    return $res;
}

function obtenerSiguienteIdDetTrans()
{
    $sql = "select coalesce(max(id_detalle_transaccion),0) max from detalle_transaccion";
    $res = pg_query($sql);
    return pg_fetch_assoc($res)["max"] + 1;
}

function registrarCuentasIvaVentasTransaccion($idfactura, $idtransaccion)
{
    $tarifasfac = obtenerTarifasImpuestoFactura($idfactura);
    foreach ($tarifasfac as $value) {
        $idcuenta = obtenerIdCuentaIVAVentas($value["cod_impuesto"], $value["cod_tarifa"]);
        insertDetalleTransaccion($idtransaccion, $idcuenta, 0, $value["valor_impuesto"]);
    }
    if (empty($tarifasfac)) {
        $idcuenta = obtenerIdCuentaIVAVentas(2, 2);
        if ($_POST['fecha_emision'] >= '2024-04-01') {
            $idcuenta = obtenerIdCuentaIVAVentas(2, 4);
        }

        insertDetalleTransaccion($idtransaccion, $idcuenta, 0, $_POST["iva"]);
    }
}

function registrarCuentasVentasTransaccion($idfactura, $idtransaccion)
{
    $tarifasfac = obtenerTarifasImpuestoFactura($idfactura);
    foreach ($tarifasfac as $value) {
        $idcuenta = obtenerIdCuentaVentas($value["cod_impuesto"], $value["cod_tarifa"]);
        insertDetalleTransaccion($idtransaccion, $idcuenta, 0, $value["base_imponible"]);
    }
    if (empty($tarifasfac)) {
        $idcuenta = obtenerIdCuentaVentas(2, 2);
        if ($_POST['fecha_emision'] >= '2024-04-01') {
            $idcuenta = obtenerIdCuentaVentas(2, 4);
        }

        insertDetalleTransaccion($idtransaccion, $idcuenta, 0, $_POST["iva"]);
    }
}

function guardarDetalleImpuestoFactura($idfactura)
{
    $detalles = json_decode($_POST["detalle_impuesto_factura"], true);
    foreach ($detalles as $key => $value) {
        $id = null;
        $sql = "select COALESCE(max(id_detalle_impuesto_factura_venta),0)+1 from detalle_impuesto_factura_venta";
        $res = pg_query($sql);
        $row = pg_fetch_row($res);
        $id = $row[0];

        $sql = "
        INSERT INTO detalle_impuesto_factura_venta(
            id_detalle_impuesto_factura_venta, cod_impuesto, cod_tarifa, 
            tarifa, valor_impuesto, base_imponible, descuento_adicional, 
            id_factura_venta)
        VALUES ($id, '$value[cod_impuesto]', '$value[cod_tarifa]', 
            $value[tarifa], $value[valor_impuesto], $value[base_imponible], $value[descuento_adicional], 
            $idfactura);
        ";

        $res = pg_query($sql);
    }
}
