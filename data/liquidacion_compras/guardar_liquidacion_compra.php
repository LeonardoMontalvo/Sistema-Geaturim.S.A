<?php

session_start();
include '../../procesos/base.php';
include '../../reportes/liqui_elect_xml.php';
include '../../firma/firma.php';
include '../../firma/xades.php';
include '../../admin/correo.php';
include 'generarPDF.php';
include '../../procesos/funciones.php';
require_once __DIR__ . '/../../procesos/configuracion.php';
require_once '../centro_costos/guardar_detalles.php';
conectarse();
$conexion = conectarse();
$conf = new Configuracion();
$esquema = $conf->getNombreEsquema();
$appFirma = $conf->getPathAplicacionFIrma("app_firma");
$pathXmls = $conf->getPathXmlsFirma();
$pathARchivoP12 = $conf->getArchivoP12();
$claveFirma = $conf->getParametroEmpresa("clave_firma");
error_reporting(0);
$defaultMail = "jpantojarevelo@gmail.com";
$cont1 = 0;
$datos = 0;
$data = "";
$valor_Servicio = 0;
if (isset($_POST['reenviarcorreo']) == "reenviarcorreo") {

    $resultado = pg_query("SELECT C.correo, C.nombres_cli, F.total_venta ,F.num_autorizacion, F.fecha_actual  FROM liquidacion_compra F, proveedores C WHERE F.id_proveedor = C.id_proveedor AND F.id_liquidacion_compra= '" . $_POST['id'] . "'");
    while ($row = pg_fetch_row($resultado)) {
        $email = $row[0];
        $nombre = $row[1];
        $total = $row[2];
        $num_autorizacion = $row[3];
        $fecha = $row[4];
    }
    $total_venta_tot = 0;
    $total_venta_tot = round($total, 2);
    $data = correo($fecha, $total_venta_tot, '../../xmls/' . $num_autorizacion . '.xml', $num_autorizacion . '.pdf', $nombre, $email, '../../xmls/' . $num_autorizacion . '.xml', generarPDFcorreo($_POST['id']), 1);

    if ($data == 1) {
        $resultado = pg_query("UPDATE liquidacion_compra set estado_fac = '1' where id_liquidacion_compra = '" . $_POST['id'] . "'");

        if ($resultado) {
            $data = 1; // datos actualizados
        } else {
            $data = 4; // error al momento de guadar
        }
    }

    $item = array('estado' => $data, 'id' => $_POST['id']);
}
if (isset($_POST['reenviarxml']) == "reenviarxml") {
    $consulta_clave = pg_query("SELECT  F.clave  FROM liquidacion_compra F, proveedores C WHERE F.id_proveedor = C.id_proveedor AND F.id_liquidacion_compra='" . $_POST['id'] . "' ");
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

    $consulta_cod_docu = pg_query("select codigo from tipo_comprobante where id_tipo_comprobante=6");
    while ($row = pg_fetch_row($consulta_cod_docu)) {
        $codDoc = $row[0];  //normal cuando generamos la clave
    }
    $consulta_empresa = pg_query("select ruc_empresa,clave, token from empresa where id_empresa = 1");
    while ($row = pg_fetch_row($consulta_empresa)) {
        $ruc = $row[0];
        $pass = $row[1];
        $token = $row[2];
    }
    $respuesta = consultarComprobante($ambiente, $consult_clave);
    //    print_r($respuesta);
    if (isset($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado)) {
        if ($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado == 'AUTORIZADO') {
            $numeroAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion;
            $fechaAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;
            $ambienteAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente;
            $data = 2;
            pg_query("UPDATE liquidacion_compra SET fecha_autorizacion = '" . $fechaAutorizacion . "',  estado_fac = '2', num_autorizacion = '" . $numeroAutorizacion . "' WHERE id_liquidacion_compra = '" . $_POST['id'] . "'");
            $dataFile = generarXMLCDATALIQUI($respuesta);
            $doc = new DOMDocument('1.0', 'UTF-8');
            $doc->loadXML($dataFile); // xml  
            $doc->save('../../xmls/' . $numeroAutorizacion . '.xml');
        } else {
            $data = 7;
            pg_query("UPDATE liquidacion_compra SET estado_fac = '7' where id_liquidacion_compra = '" . $_POST['id'] . "'"); // NO AUTORIZADO
        }
    } else {

        $data = $respuesta['RespuestaAutorizacionComprobante']['autorizaciones']['autorizacion']['estado'];
        pg_query("UPDATE liquidacion_compra SET estado_fac = '7' where id_liquidacion_compra = '" . $_POST['id'] . "'"); // Rechazado
    }

    $item = array('estado' => $data, 'id' => $_POST['id']);
    //    echo 'aqui1';
}
if (isset($_POST['enviarxml']) == "enviarxml") {
    $consulta_clave = pg_query("SELECT  F.clave  FROM liquidacion_compra F, proveedores C WHERE F.id_proveedor = C.id_proveedor AND F.id_liquidacion_compra='" . $_POST['id'] . "' ");
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

    $consulta_cod_docu = pg_query("select codigo from tipo_comprobante where id_tipo_comprobante=6");
    while ($row = pg_fetch_row($consulta_cod_docu)) {
        $codDoc = $row[0];  //normal cuando generamos la clave
    }
    $consulta_empresa = pg_query("select ruc_empresa,clave, token from empresa where id_empresa = 1");
    while ($row = pg_fetch_row($consulta_empresa)) {
        $ruc = $row[0];
        $pass = $row[1];
        $token = $row[2];
    }

    $result = generarXMLLIQUI($_POST['id'], $codDoc, $ambiente, $emision);
    //   print_r($result);
    $doc = new DOMDocument('1.0', 'UTF-8');
    $doc->loadXML($result); // xml 
    $doc->save($pathXmls . "fac" . '.xml');
    //exec("$appFirma " . '../../xmls/' . $esquema . '/fac', $resultado);
    exec("$appFirma " . $pathXmls . '/fac "' . $pathARchivoP12 . '" "' . $claveFirma . '"', $resultado);
    $respuesta = consultarComprobante($ambiente, $consult_clave);
    //    print_r($respuesta."ff");
    if (isset($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado)) {

        if ($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado == 'AUTORIZADO') {

            $numeroAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion;
            $fechaAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;
            $ambienteAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente;
            $data = 2;
            pg_query("UPDATE liquidacion_compra SET fecha_autorizacion = '" . $fechaAutorizacion . "',  estado_fac = '2', num_autorizacion = '" . $numeroAutorizacion . "' WHERE id_liquidacion_compra = '" . $_POST['id'] . "'");
            $dataFile = generarXMLCDATALIQUI($respuesta);
            $doc = new DOMDocument('1.0', 'UTF-8');
            $doc->loadXML($dataFile); // xml  
            $doc->save($pathXmls . $numeroAutorizacion . '.xml');
        } else {
            $data = 7;
            pg_query("UPDATE liquidacion_compra SET estado_fac = '7' where id_liquidacion_compra = '" . $_POST['id'] . "'");
            //                             $data = $respuesta['RespuestaAutorizacionComprobante']['autorizaciones']['autorizacion']['estado'];
            // NO AUTORIZADO
        }
    }

    $item = array('estado' => $data, 'id' => $_POST['id']);
    //    echo 'aqui2' . $data;
}


if ($_POST["id_fac"] == "") {   //////////////comparar tipo venta///////////
    if ($_POST["tipo_venta"] == "FACTURA") {

        $cont1 = 0;
        $consulta = pg_query("select max(id_liquidacion_compra) from liquidacion_compra");
        while ($row = pg_fetch_row($consulta)) {
            $cont1 = $row[0];
        }
        $cont1++;

        $contt = 0;
        $consulta_cli = pg_query("select max(id_proveedor) from proveedores");
        while ($row = pg_fetch_row($consulta_cli)) {
            $contt = $row[0];
        }
        $contt++;
        // fin

        if ($_POST['id_proveedor'] == "") {
            $tipo = $_POST['ruc_ci'];

            if (strlen($tipo) == 10) {

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
                $secuencial = $_POST[num_factura];
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
                $consulta_cod_docu = pg_query("select codigo from tipo_comprobante where id_tipo_comprobante=6");
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
                //                echo 'LIQUIDACION COMPRA1' . "insert into liquidacion_compra values('$cont1','1','$contt','$_SESSION[id]','$cont1','$_POST[num_factura]','$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[cancelacion]','$_POST[tipo_precio]','$_POST[formaspago]','$_POST[autorizacion]','$_POST[fecha_auto]','$_POST[fecha_auto]','$_POST[tarifa0]','$_POST[tarifa12]','$_POST[iva]','$_POST[desc]','$_POST[tot]','Activo','','$_POST[tarjetas]',1,'$_POST[valor_recibo]','','1','$resultporcent','$clave','0','$_POST[formas]','$_POST[num_guia_remision]','$_POST[marca_vehiculo]','$_POST[placa_fac]','$_POST[comentario]' )";
                $sql = "insert into liquidacion_compra values('$cont1','1','$contt','$_SESSION[id]','$cont1','$_POST[num_factura]','$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[cancelacion]','$_POST[tipo_precio]','$_POST[formaspago]','$_POST[autorizacion]','$_POST[fecha_auto]','$_POST[fecha_auto]','$_POST[tarifa0]','$_POST[tarifa12]','$_POST[iva]','$_POST[desc]','$_POST[tot]','Activo','','$_POST[tarjetas]',1,'$_POST[valor_recibo]','','1','$resultporcent','$clave','0','$_POST[formas]','$_POST[num_guia_remision]','$_POST[marca_vehiculo]','$_POST[placa_fac]','$_POST[comentario]' )";

                $guardar = guardarSql($conexion, $sql);
                if ($guardar == 'true') {
                    ////datos guardados		            
                } else {
                    $data = 60; /// error al guardar
                    $item = array('estado' => $data);
                }
                //        print_r($datas);    
                //        pg_query("insert into liquidacion_compra values('$cont1','1','$contt','$_SESSION[id]','$cont1','$_POST[num_factura]','$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[cancelacion]','$_POST[tipo_precio]','$_POST[formas]','$_POST[autorizacion]','$_POST[fecha_auto]','$_POST[fecha_caducidad]','$_POST[tarifa0]','$_POST[tarifa12]','$_POST[iva]','$_POST[desc]','$_POST[tot]','Activo','','$_POST[tarjetas]',1,'$_POST[valor_recibo]','$_POST[valor_cambio]','$_POST[id_vendedor]','$resultporcent','$clave','0','$_POST[formaspago]','$_POST[num_guia_remision]','$_POST[marca_vehiculo]','$_POST[placa_fac]','$_POST[propiedad]','$_POST[num_reclamo]','$_POST[num_chasis]')");
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

                $consulta_cod_docu = pg_query("select codigo from tipo_comprobante where id_tipo_comprobante=6");
                while ($row = pg_fetch_row($consulta_cod_docu)) {
                    $codDoc = $row[0];  //normal cuando generamos la clave
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

                $result = generarXMLLIQUI($cont1, $codDoc, $ambiente, $emision);
                $doc = new DOMDocument('1.0', 'UTF-8');
                $doc->loadXML($result); // xml 
                $doc->save($pathXmls . "fac" . '.xml');
                //                exec('c:\xampp\htdocs\syswebfeb_cota\firma\pruebasFE ' . '../../xmls/fac', $resultado);
                exec("$appFirma " . $pathXmls . '/fac "' . $pathARchivoP12 . '" "' . $claveFirma . '"', $resultado);
                $respuesta = consultarComprobante($ambiente, $clave);
                if (isset($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado)) {
                    if ($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado == 'AUTORIZADO') {
                        $numeroAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion;
                        $fechaAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;
                        $ambienteAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente;
                        $data = 2;
                        pg_query("UPDATE liquidacion_compra SET fecha_autorizacion = '" . $fechaAutorizacion . "',  estado_fac = '2', num_autorizacion = '" . $numeroAutorizacion . "' WHERE id_liquidacion_compra = '$cont1'");
                        $dataFile = generarXMLCDATALIQUI($respuesta);
                        $doc = new DOMDocument('1.0', 'UTF-8');
                        $doc->loadXML($dataFile); // xml  
                        $doc->save('../../xmls/' . $numeroAutorizacion . '.xml');
                    } else {
                        $data = 7;
                        pg_query("UPDATE liquidacion_compra SET estado_fac = '7' where id_liquidacion_compra = '$cont1'"); // NO AUTORIZADO
                    }
                }

                $item = array('estado' => $data, 'id' => $cont1);
                //                echo 'aqui3' . $data;
            } else {
                if (strlen($tipo) == 13) {
                    // guardar proveedores
                    pg_query("insert into proveedores values('$contt','Ruc','$_POST[ruc_ci]','$_POST[nombre_cliente]','natural','$_POST[direccion_cliente]','$_POST[telefono_cliente]','','','','$_POST[correo]','','','Activo','1','1')");
                    // fin
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
                    $secuencial = $_POST[num_factura];
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
                    $consulta_cod_docu = pg_query("select codigo from tipo_comprobante where id_tipo_comprobante=6");
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

                    $sql = "insert into liquidacion_compra values('$cont1','1','$contt','$_SESSION[id]','$cont1','$_POST[num_factura]','$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[cancelacion]','$_POST[tipo_precio]','$_POST[formaspago]','$_POST[autorizacion]','$_POST[fecha_auto]','$_POST[fecha_auto]','$_POST[tarifa0]','$_POST[tarifa12]','$_POST[iva]','$_POST[desc]','$_POST[tot]','Activo','','$_POST[tarjetas]',1,'$_POST[valor_recibo]','','1','$resultporcent','$clave','0','$_POST[formas]','$_POST[num_guia_remision]','$_POST[marca_vehiculo]','$_POST[placa_fac]','$_POST[comentario]' )";
                    $guardar = guardarSql($conexion, $sql);
                    if ($guardar == 'true') {
                        ////datos guardados		            
                    } else {
                        $data = 60; /// error al guardar
                        $item = array('estado' => $data);
                    }
                }
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

                $consulta_cod_docu = pg_query("select codigo from tipo_comprobante where id_tipo_comprobante=6");
                while ($row = pg_fetch_row($consulta_cod_docu)) {
                    $codDoc = $row[0];  //normal cuando generamos la clave
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

                $result = generarXMLLIQUI($cont1, $codDoc, $ambiente, $emision);
                $doc = new DOMDocument('1.0', 'UTF-8');
                $doc->loadXML($result); // xml 
                $doc->save($pathXmls . "fac" . '.xml');
                //                exec('c:\xampp\htdocs\syswebfeb_cota\firma\pruebasFE ' . '../../xmls/fac', $resultado);
                exec("$appFirma " . $pathXmls . '/fac "' . $pathARchivoP12 . '" "' . $claveFirma . '"', $resultado);
                $respuesta = consultarComprobante($ambiente, $clave);
                if (isset($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado)) {
                    if ($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado == 'AUTORIZADO') {
                        $numeroAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion;
                        $fechaAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;
                        $ambienteAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente;
                        $data = 2;
                        pg_query("UPDATE liquidacion_compra SET fecha_autorizacion = '" . $fechaAutorizacion . "',  estado_fac = '2', num_autorizacion = '" . $numeroAutorizacion . "' WHERE id_liquidacion_compra = '$cont1'");
                        $dataFile = generarXMLCDATALIQUI($respuesta);
                        $doc = new DOMDocument('1.0', 'UTF-8');
                        $doc->loadXML($dataFile); // xml  
                        $doc->save('../../xmls/' . $numeroAutorizacion . '.xml');
                    } else {
                        $data = 7;
                        pg_query("UPDATE liquidacion_compra SET estado_fac = '7' where id_liquidacion_compra = '$cont1'"); // NO AUTORIZADO
                    }
                }

                $item = array('estado' => $data, 'id' => $cont1);
                //                echo 'aqui4' . $data;
            }
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
            $secuencial = $_POST[num_factura];
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
            $consulta_cod_docu = pg_query("select codigo from tipo_comprobante where id_tipo_comprobante=6");
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
            //print_r($_POST[formas]); 
            //            echo 'insert into liquidacion_compratt' . "insert into liquidacion_compra values('$cont1','1','$_POST[id_proveedor]','$_SESSION[id]','$cont1','$_POST[num_factura]','$_POST[fecha_actual]','$_POST[hora_actual]','','$_POST[tipo_precio]','$_POST[formaspago]','$_POST[autorizacion]','$_POST[fecha_auto]','$_POST[fecha_auto]','$_POST[tarifa0]','$_POST[tarifa12]','$_POST[iva]','$_POST[desc]','$_POST[tot]','Activo','','$_POST[tarjetas]',1,'$_POST[valor_recibo]','','1','$resultporcent','$clave','0','$_POST[formas]','$_POST[num_guia_remision]','','$_POST[placa_fac]','$_POST[propiedad]' )";
            $sql = "insert into liquidacion_compra values('$cont1','1','$_POST[id_proveedor]','$_SESSION[id]','$cont1','$_POST[num_factura]','$_POST[fecha_actual]','$_POST[hora_actual]','','$_POST[tipo_precio]','$_POST[formaspago]','$_POST[autorizacion]','$_POST[fecha_auto]','$_POST[fecha_auto]','$_POST[tarifa0]','$_POST[tarifa12]','$_POST[iva]','$_POST[desc]','$_POST[tot]','Activo','','$_POST[tarjetas]',1,'$_POST[valor_recibo]','','1','$resultporcent','$clave','0','$_POST[formas]','$_POST[num_guia_remision]','','$_POST[placa_fac]','$_POST[comentario]' )";
            //         print_r($sql."fffff");
            $guardar = guardarSql($conexion, $sql);
            if ($guardar == 'true') {
                ////datos guardados	
            } else {
                $data = 60; /// error al guardar
                $item = array('estado' => $data);
            }

            //            print_r($guardar);
        }
        // modificar proformas
        if ($_POST['proforma'] != "") {
            //            pg_query("Update proforma Set estado='Pasivo' where id_proforma='" . $_POST['proforma'] . "'");
        }
        // fin
        // datos detalle factura
        $campo1 = $_POST['campo1'];
        $campo2 = $_POST['campo2'];
        $campo3 = $_POST['campo3'];
        $campo4 = $_POST['campo4'];
        $campo5 = $_POST['campo5'];
        $campo6 = $_POST['campo6'];
        $campo7 = $_POST['campo7'];
        // fin       
        // agregar detalle_liquidacion_compra
        $arreglo1 = explode('|', $campo1);
        $arreglo2 = explode('|', $campo2);
        $arreglo3 = explode('|', $campo3);
        $arreglo4 = explode('|', $campo4);
        $arreglo5 = explode('|', $campo5);
        $arreglo6 = explode('|', $campo6);
        $arreglo7 = explode('|', $campo7);
        $nelem = count($arreglo1);
        $forma = "Contado";
        // fin
        //        echo 'guardar'.$guardar;
        if ($guardar == 'true') {
            if ($forma == "Credito") {
                // variables pagos
                $adelanto = $_POST['adelanto'];
                $meses = $_POST['meses'];
                $total = $_POST['tot'];
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
                if ($adelanto == "") {
                    $monto = $total;
                    $format = number_format($monto, 2, '.', '');
                    $adelanto = 0.00;
                } else {
                    $monto = $total - $adelanto;
                    $format = number_format($monto, 2, '.', '');
                }
                //                echo 'detalle liquidacion compras1'."insert into pagos_venta values('$cont2','$_POST[id_proveedor]','$cont1','$_SESSION[id]','$_POST[fecha_actual]','$adelanto','$meses','Factura','$format','$format','Activo','$_POST[fecha_dias]')";
                //                pg_query("insert into pagos_venta values('$cont2','$_POST[id_proveedor]','$cont1','$_SESSION[id]','$_POST[fecha_actual]','$adelanto','$meses','Factura','$format','$format','Activo','$_POST[fecha_dias]')");
                // fin
                // guardar meses
                if ($meses > 1) {
                    for ($i = 1; $i <= $meses - 1; $i++) {
                        // contador detalle pagos venta
                        $cont3 = 0;
                        $consulta8 = pg_query("select max(id_detalle_pagos_venta) from detalle_pagos_venta");
                        while ($row = pg_fetch_row($consulta8)) {
                            $cont3 = $row[0];
                        }
                        $cont3++;
                        // fin
                        $calcu = $monto / ($meses);
                        $nuevaFecha = date('Y-m-d', strtotime(" + $i month"));
                        $format_numero = number_format(floor($calcu), 2, '.', '');
                        //                          echo 'detalle liquidacion compras2'."insert into detalle_pagos_venta values('$cont3','$cont2','$nuevaFecha','$format_numero','$format_numero','Activo')";
                        //                        pg_query("insert into detalle_pagos_venta values('$cont3','$cont2','$nuevaFecha','$format_numero','$format_numero','Activo')");
                    }
                    $cont3++;
                    $calcu1 = floor($calcu) * ($meses - 1);
                    $ultimaFecha = date('Y-m-d', strtotime(" + $i month"));
                    $sal = $monto - $calcu1;
                    $format_numero2 = number_format($sal, 2, '.', '');
                    //                    echo 'detalle liquidacion 3'."insert into detalle_pagos_venta values('$cont3','$cont2','$ultimaFecha','$format_numero2','$format_numero2','Activo')";
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
                    //                    pg_query("insert into detalle_pagos_venta values('$cont3','$cont2','$Fecha','$format2','$format2','Activo')");
                }
                // fin       
                // guardar detalle compra
                for ($i = 1; $i < $nelem; $i++) {
                    // contador detalle factura venta
                    $cont4 = 0;
                    $consulta = pg_query("select max(id_detalle_liquidacion_compra) from detalle_liquidacion_compra");
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
                    //                    echo 'AQUI1' . "insert into detalle_liquidacion_compra values('$cont4','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','Activo','$arreglo6[$i]','$_POST[fecha_actual]')";
                    //                    $bien_servi = $_POST['bien_servi'];


                    $consulta_bien_servi = pg_query(" select bien_servicios from productos where cod_productos=$arreglo1[$i]");
                    while ($row = pg_fetch_row($consulta_bien_servi)) {
                        $valor_Servicio = $row[0];
                    }
                    //                    echo 'AQUI1' . "insert into detalle_liquidacion_compra values('$cont4','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','Activo','$arreglo6[$i]','$_POST[fecha_actual]','$valor_Servicio')";
                    pg_query("insert into detalle_liquidacion_compra values('$cont4','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','Activo','$arreglo6[$i]','$_POST[fecha_actual]','$valor_Servicio')");

                    if (!empty($arreglo7[$i])) {
                        guardarDetalleCentroCosto($cont4, $arreglo7[$i], "detalle_liquidacion_compra");
                    }

                    $consulta2 = pg_query("select * from productos where cod_productos = '$arreglo1[$i]'");
                    while ($row = pg_fetch_row($consulta2)) {
                        $stock = $row[13];
                    }
                    $cal = $stock - $arreglo2[$i];

                    //                    pg_query("Update productos Set stock='" . $cal . "' where cod_productos='" . $arreglo1[$i] . "'");
                    // fin
                    // consulta kardex valorizado
                    $cantidad = 0;
                    $precio_total = 0;
                    $precio_unitario = 0;
                    $consulta2 = pg_query("select * from kardex_valorizado where cod_productos = '$arreglo1[$i]' order by id_kardex asc");
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

                    pg_query("insert into kardex_valorizado values(" . $cont_v . ",'" . $arreglo1[$i] . "','$_POST[fecha_actual]', '" . 'Liquidacion: ' . $_POST['num_factura'] . "','','" . $cantidad_salida . "','" . $cantidad . "','" . $precio_unitario_salida . "','" . $precio_total_salida . "','','','" . $cantidad_total . "','4')");
                    // fin
                    if ($_POST['id_proveedor'] == "") {
                        $idCli = 0;
                        $consulta_cli = pg_query("select max(id_proveedor) from proveedores");
                        while ($row = pg_fetch_row($consulta_cli)) {
                            $idCli = $row[0];
                        }

                        // guardar kardex
                        pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '" . 'L.C:' . $_POST['num_factura'] . "' ,'$arreglo2[$i]','$arreglo3[$i]','$arreglo5[$i]','$arreglo1[$i]','$cal','2','','','$idCli','$cont1','LC')");
                        // fin
                    } else {
                        $cliente1 = $_POST['id_proveedor'];
                        pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '" . 'L.C:' . $_POST['num_factura'] . "' ,'$arreglo2[$i]','$arreglo3[$i]','$arreglo5[$i]','$arreglo1[$i]','$cal','2','','','$cliente1','$cont1','LC')");
                    }
                }
            } else {
                $forma = 'Contado';
                if ($forma == "Contado" || $forma == "Cheque") {

                    for ($i = 1; $i < $nelem; $i++) {

                        // contador detalle factura venta
                        $cont6 = 0;
                        $consulta = pg_query("select  max(id_detalle_liquidacion_compra) from detalle_liquidacion_compra");
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
                        // fin
                        // guardar detalle_venta
                        //                        $bien_servi = $_POST['bien_servi'];
                        //                        echo 'selectdd' . $arreglo1[$i];
                        $consulta_bien_servi = pg_query(" select bien_servicios from productos where cod_productos=$arreglo1[$i]");
                        while ($row = pg_fetch_row($consulta_bien_servi)) {
                            $valor_Servicio = $row[0];
                        }
                        //                        echo 'AQUI2' . "insert into detalle_liquidacion_compra values('$cont6','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','Activo','$arreglo6[$i]','$_POST[fecha_actual]','$valor_Servicio')";
                        $sqlde = "insert into detalle_liquidacion_compra values('$cont6','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','Activo','$arreglo6[$i]','$_POST[fecha_actual]','$valor_Servicio')";
                        if (!empty($arreglo7[$i])) {
                            guardarDetalleCentroCosto($cont6, $arreglo7[$i], "detalle_liquidacion_compra");
                        }
                        //                print_r($sqlde);
                        $guardar = guardarSql($conexion, $sqlde);
                        if ($guardar == 'true') {
                            ////datos guardados		            
                        } else {
                            $data = 60; /// error al guardar
                            $item = array('estado' => $data);
                        }
                        $contimpuesto_producto = 0;
                        $consultimpu_producto = pg_query("select max(id_detalleimpuestoproducto) from detalleimpuestoproducto");
                        while ($row = pg_fetch_row($consultimpu_producto)) {
                            $contimpuesto_producto = $row[0];
                        }
                        $contimpuesto_producto++;


                        // modificar productos general
                        $consulta2 = pg_query("select * from productos where cod_productos = '$arreglo1[$i]'");
                        while ($row = pg_fetch_row($consulta2)) {
                            $stock = $row[13];
                        }
                        $cal = $stock - $arreglo2[$i];

                        //                        pg_query("Update productos Set stock='" . $cal . "' where cod_productos='" . $arreglo1[$i] . "'");
                        // fin
                        // consulta kardex valorizado
                        $cantidad = 0;
                        $precio_total = 0;
                        $precio_unitario = 0;
                        $consulta2 = pg_query("select * from kardex_valorizado where cod_productos = '$arreglo1[$i]' order by id_kardex asc");
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

                        pg_query("insert into kardex_valorizado values(" . $cont_v . ",'" . $arreglo1[$i] . "','$_POST[fecha_actual]', '" . 'Liquidacion: ' . $_POST['num_factura'] . "','','" . $cantidad_salida . "','" . $cantidad . "','" . $precio_unitario_salida . "','" . $precio_total_salida . "','','','" . $cantidad_total . "','4')");
                        // fin
                        if ($_POST['id_proveedor'] == "") {
                            $idCli = 0;
                            $consulta_cli = pg_query("select max(id_proveedor) from proveedores");
                            while ($row = pg_fetch_row($consulta_cli)) {
                                $idCli = $row[0];
                            }

                            // guardar kardex
                            pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '" . 'L.C:' . $_POST['num_factura'] . "' ,'$arreglo2[$i]','$arreglo3[$i]','$arreglo5[$i]','$arreglo1[$i]','$cal','2','','','$idCli','$cont1','L.C')");
                            // fin
                        } else {
                            $cliente1 = $_POST['id_proveedor'];
                            pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '" . 'L.C' . $_POST['num_factura'] . "' ,'$arreglo2[$i]','$arreglo3[$i]','$arreglo5[$i]','$arreglo1[$i]','$cal','2','','','$cliente1','$cont1','L.C')");
                        }
                    }
                }
            }
            //        $data=$cont1;
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
                for ($i = 1; $i < $nelem; $i++) {
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
            if ($_POST['id_proveedor'] == "") {
                $cliente1 = $contt;
            } else {
                $cliente1 = $_POST['id_proveedor'];
            }
            pg_query("Update proveedores Set  telefono='$_POST[telefono_cliente]', correo='$_POST[correo]' where id_proveedor='$cliente1'");

            $prove = pg_query("select identificacion from proveedores where id_proveedor='$cliente1'");
            $p = pg_fetch_row($prove);
            $ing = pg_query("select max(num_transaccion) from transacciones where id_tipo_transaccion='1'");
            $res = pg_fetch_row($ing);
            $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'VENTA PRODUCTOS, CLIENTE: " . $p[0] . ", COMPROBANTE: " . $_POST['num_factura'] . "', '" . $xy . "', '$_POST[tot]', '" . $saldo . "','1','" . ($res[0] + 1) . "','Activo' )");

            /////DETALLES TRANSACCION
            $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
            $fila1 = pg_fetch_row($iddettran);
            $fila1[0] = $fila1[0] + 1;
            $merca = pg_query("select cuenta_debito from parametros where descripcion='VENTA MERCADERIA'");
            $fila3 = pg_fetch_row($merca);
            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila3[0] . "','0.000','$sum','Activo')");
            $fila1[0] = $fila1[0] + 1;
            $planiva = pg_query("select cuenta_credito from parametros where descripcion='IVA'");
            $fila2 = pg_fetch_row($planiva);
            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','0.000','" . $_POST['iva'] . "','Activo')");
            $fila1[0] = $fila1[0] + 1;
            $plancaja = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
            $fila2 = pg_fetch_row($plancaja);
            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','" . $_POST['tot'] . "','0.000','Activo')");
            /////FACTURA ELECTRONICA////
            ///1 GUARDADO
            ///2 GENERADO
            ///3 AUTORIZADO
            ///4 RECHAZADO

            $consulta_ambiente = pg_query("select codigo_ambi from ambiente where estado_ambi = 'Activo' ");
            while ($row = pg_fetch_row($consulta_ambiente)) {
                $ambiente = $row[0];
            }

            $consulta_emision = pg_query("select codigo_temision from tipo_emision where estado_temision='Activo'");
            while ($row = pg_fetch_row($consulta_emision)) {
                $emision = $row[0]; //normal cuando generamos la clave
            }

            $consulta_cod_docu = pg_query("select codigo from tipo_comprobante where id_tipo_comprobante=6");
            while ($row = pg_fetch_row($consulta_cod_docu)) {
                $codDoc = $row[0];  //normal cuando generamos la clave
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
            $result = generarXMLLIQUI($cont1, $codDoc, $ambiente, $emision);
            $doc = new DOMDocument('1.0', 'UTF-8');
            $doc->loadXML($result); // xml 
            $doc->save($pathXmls . "fac" . '.xml');
            //            exec('c:\xampp\htdocs\syswebfeb_cota\firma\pruebasFE ' . '../../xmls/fac', $resultado);
            exec("$appFirma " . $pathXmls . '/fac "' . $pathARchivoP12 . '" "' . $claveFirma . '"', $resultado);
            $respuesta = consultarComprobante($ambiente, $clave);
            //            print_r($respuesta);
            if (isset($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado)) {
                if ($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado == 'AUTORIZADO') {
                    $numeroAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion;
                    $fechaAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;
                    $ambienteAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente;

                    $data = 2;
                    //                    print_r($data);
                    pg_query("UPDATE liquidacion_compra SET fecha_autorizacion = '" . $fechaAutorizacion . "',  estado_fac = '2', num_autorizacion = '" . $numeroAutorizacion . "' WHERE id_liquidacion_compra = '$cont1'");
                    $dataFile = generarXMLCDATALIQUI($respuesta);
                    $doc = new DOMDocument('1.0', 'UTF-8');
                    $doc->loadXML($dataFile); // xml     
                    $doc->save('../../xmls/' . $numeroAutorizacion . '.xml');
                } else {
                    $data = 7;
                    pg_query("UPDATE liquidacion_compra SET estado_fac = '7' where id_liquidacion_compra = '$cont1'"); // NO AUTORIZADO
                }
            }

            $item = array('estado' => $data, 'id' => $cont1);
            //            echo 'aqui5' . $data;
            // print_r(json_encode(array('estado' => $data, 'id' => $cont1)));
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
            // contador proveedores
            $contt = 0;
            $consulta_cli = pg_query("select max(id_proveedor) from proveedores");
            while ($row = pg_fetch_row($consulta_cli)) {
                $contt = $row[0];
            }
            $contt++;
            // fin

            if ($_POST['id_proveedor'] == "") {

                $tipo = $_POST['ruc_ci'];
                if (strlen($tipo) == 10) {
                    // guardar proveedores  
                    //                    pg_query("insert into proveedores values('$contt','Cedula','$_POST[ruc_ci]','$_POST[nombre_cliente]','natural','$_POST[direccion_cliente]','$_POST[telefono_cliente]','','','','$_POST[correo]','','','Activo')");
                    // fin 
                    // guardar facturas_novalidas
                    //                    pg_query("insert into facturas_novalidas values('$cont1','$_POST[id_proveedor]','$_SESSION[id]','$cont1','$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[tipo_precio]','$_POST[formaspago]','$_POST[tarifa0]','$_POST[tarifa12]','$_POST[iva]','$_POST[desc]','$_POST[tot]','Activo')");
                    // fin
                } else {
                    if (strlen($tipo) == 13) {
                        // guardar proveedores   
                        //                        pg_query("insert into proveedores values('$contt','Ruc','$_POST[ruc_ci]','$_POST[nombre_cliente]','natural','$_POST[direccion_cliente]','$_POST[telefono_cliente]','','','','$_POST[correo]','','','Activo')");
                        // fin
                        // guardar facturas_novalidas
                        //                        pg_query("insert into facturas_novalidas values('$cont1','$_POST[id_proveedor]','$_SESSION[id]','$cont1','$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[tipo_precio]','$_POST[formaspago]','$_POST[tarifa0]','$_POST[tarifa12]','$_POST[iva]','$_POST[desc]','$_POST[tot]','Activo')");
                        // fin   
                    }
                }
            } else {
                // guardar facturas_novalidas
                //                pg_query("insert into facturas_novalidas values('$cont1','$_POST[id_proveedor]','$_SESSION[id]','$cont1','$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[tipo_precio]','$_POST[formaspago]','$_POST[tarifa0]','$_POST[tarifa12]','$_POST[iva]','$_POST[desc]','$_POST[tot]','Activo')");
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
            $campo7 = $_POST['campo7'];

            // agregar detalle_liquidacion_compra
            $arreglo1 = explode('|', $campo1);
            $arreglo2 = explode('|', $campo2);
            $arreglo3 = explode('|', $campo3);
            $arreglo4 = explode('|', $campo4);
            $arreglo5 = explode('|', $campo5);
            $arreglo6 = explode('|', $campo6);
            $arreglo7 = explode('|', $campo7);
            $nelem = count($arreglo1);
            $forma = 'Contado';

            if ($forma == "Credito") {
                // variables pagos
                $adelanto = $_POST['adelanto'];
                $meses = $_POST['meses'];
                $total = $_POST['tot'];

                // contador pagos venta
                $cont2 = 0;
                $consulta = pg_query("select max(id_pagos_venta) from pagos_venta");
                while ($row = pg_fetch_row($consulta)) {
                    $cont2 = $row[0];
                }
                $cont2++;
                // guardar pagos venta
                if ($adelanto == "") {
                    $monto = $total;
                    $format = number_format($monto, 2, '.', '');
                    $adelanto = 0.00;
                } else {
                    $monto = $total - $adelanto;
                    $format = number_format($monto, 2, '.', '');
                }
                //                pg_query("insert into pagos_venta values('$cont2','$_POST[id_proveedor]','$cont1','$_SESSION[id]','$_POST[fecha_actual]','$adelanto','$meses','Factura','$format','$format','Activo','$_POST[fecha_dias]')");
                // guardar meses
                if ($meses > 1) {
                    for ($i = 1; $i <= $meses - 1; $i++) {
                        // contador detalle pagos venta
                        $cont3 = 0;
                        $consulta8 = pg_query("select max(id_detalle_pagos_venta) from detalle_pagos_venta");
                        while ($row = pg_fetch_row($consulta8)) {
                            $cont3 = $row[0];
                        }
                        $cont3++;
                        // fin
                        $calcu = $monto / ($meses);
                        $nuevaFecha = date('Y-m-d', strtotime(" + $i month"));
                        $format_numero = number_format(floor($calcu), 2, '.', '');
                        //                        pg_query("insert into detalle_pagos_venta values('$cont3','$cont2','$nuevaFecha','$format_numero','$format_numero','Activo')");
                    }
                    $cont3++;
                    $calcu1 = floor($calcu) * ($meses - 1);
                    $ultimaFecha = date('Y-m-d', strtotime(" + $i month"));
                    $sal = $monto - $calcu1;
                    $format_numero2 = number_format($sal, 2, '.', '');
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
                    //                    pg_query("insert into detalle_pagos_venta values('$cont3','$cont2','$Fecha','$format2','$format2','Activo')");
                }

                for ($i = 1; $i < $nelem; $i++) {
                    // contador detalle_factura_novalidas
                    $cont4 = 0;
                    $consulta = pg_query("select max(id_detalle_facturas_novalidas) from detalle_facturas_novalidas");
                    while ($row = pg_fetch_row($consulta)) {
                        $cont4 = $row[0];
                    }
                    $cont4++;

                    // guardar detalle_factura_novalidas
                    pg_query("insert into detalle_facturas_novalidas values('$cont4','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','Activo','$arreglo6[$i]')");

                    // modificar productos///////////
                    $consulta2 = pg_query("select * from productos where cod_productos = '$arreglo1[$i]'");
                    while ($row = pg_fetch_row($consulta2)) {
                        $stock = $row[13];
                    }
                    $cal = $stock - $arreglo2[$i];

                    pg_query("Update productos Set stock='" . $cal . "' where cod_productos='" . $arreglo1[$i] . "'");
                    // fin
                }
            } else {
                $forma = "Contado";
                if ($forma == "Contado" || $forma == "Cheque") {
                    for ($i = 1; $i < $nelem; $i++) {
                        // contador detalle_factura_novalidas
                        $cont6 = 0;
                        $consulta = pg_query("select  max(id_detalle_facturas_novalidas) from detalle_facturas_novalidas");
                        while ($row = pg_fetch_row($consulta)) {
                            $cont6 = $row[0];
                        }
                        $cont6++;
                        // fin  
                        // guardar detalle_factura_novalidas
                        //                        pg_query("insert into detalle_facturas_novalidas values('$cont6','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','Activo','$arreglo6[$i]')");
                        // fin
                        // modificar productos
                        $consulta2 = pg_query("select * from productos where cod_productos = '$arreglo1[$i]'");
                        while ($row = pg_fetch_row($consulta2)) {
                            $stock = $row[13];
                        }
                        $cal = $stock - $arreglo2[$i];

                        pg_query("Update productos Set stock='" . $cal . "' where cod_productos='" . $arreglo1[$i] . "'");

                        // consulta kardex valorizado
                        $consulta2 = pg_query("select * from kardex_valorizado where cod_productos = '$arreglo1[$i]' order by id_kardex asc");
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

                        pg_query("insert into kardex_valorizado values(" . $cont_v . ",'" . $arreglo1[$i] . "','$_POST[fecha_actual]', '" . 'F Venta: ' . $_POST['num_factura'] . "','','" . $cantidad_salida . "','" . $cantidad . "','" . $precio_unitario_salida . "','" . $precio_total_salida . "','','','" . $cantidad_total . "','4')");
                        // fin

                        if ($_POST['id_proveedor'] == "") {
                            $idCli = 0;
                            $consulta_cli = pg_query("select max(id_proveedor) from proveedores");
                            while ($row = pg_fetch_row($consulta_cli)) {
                                $idCli = $row[0];
                            }

                            // guardar kardex
                            pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '" . 'F.V:' . $_POST['num_factura'] . "' ,'$arreglo2[$i]','$arreglo3[$i]','$arreglo5[$i]','$arreglo1[$i]','$cal','2','','','$idCli','$cont1','V')");
                            // fin
                        } else {
                            $cliente1 = $_POST['id_proveedor'];

                            pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '" . 'F.V:' . $_POST['num_factura'] . "' ,'$arreglo2[$i]','$arreglo3[$i]','$arreglo5[$i]','$arreglo1[$i]','$cal','2','','','$cliente1','$cont1','V')");
                        }
                    }
                }
            }
            //            $data = $cont1;
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
            $iva = pg_query("select valor from parametros where descripcion='IVA'");
            while ($ivavalor = pg_fetch_row($iva)) {
                $ivafin = $ivavalor[0];
            }
            $abc = ($ivafin + 100) / 100;
            $abc = number_format($abc, 3, '.', '');
            while ($bool) {
                $cuenta = pg_query("select id_plan_cuentas from productos where cod_productos='" . $auxiliar[0] . "'");
                $plan = pg_fetch_row($cuenta);
                $nelem = count($auxiliar);
                $vec = 0;
                for ($i = 1; $i < $nelem; $i++) {
                    $cuenta1 = pg_query("select id_plan_cuentas from productos where cod_productos='" . $auxiliar[$i] . "'");
                    $cIva = pg_query("select incluye_iva from productos where cod_productos='" . $auxiliar[$i] . "'");
                    $plan1 = pg_fetch_row($cuenta1);
                    $si = pg_fetch_row($cIva);
                    $tieneiva = $si[0];
                    if ($plan1[$i] == $plan[0]) {
                        /* if($tieneiva=="Si"){
                          $sum=($arreglo5[$i]/$abc)+$sum;
                          }else{
                          //$aa++;
                          $sum=$arreglo5[$i]+$sum;
                          } */
                        $sum = $arreglo5[$i] + $sum;
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
            $sum = $sum + $_POST['iva'];
            $saldo = $sum - $_POST['tot'];
            $cliente1 = "";
            if ($_POST['id_proveedor'] == "") {
                $cliente1 = $contt;
            } else {
                $cliente1 = $_POST['id_proveedor'];
            }
            $prove = pg_query("select identificacion from proveedores where id_proveedor='$cliente1'");
            $p = pg_fetch_row($prove);
            $ing = pg_query("select max(num_transaccion) from transacciones where id_tipo_transaccion='1'");
            $res = pg_fetch_row($ing);
            $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'VENTA PRODUCTOS, CLIENTE: " . $p[0] . ", COMPROBANTE: NOTA DE VENTA', '" . $sum . "', '$_POST[tot]', '" . $saldo . "','1','" . ($res[0] + 1) . "','Activo' )");

            /////DETALLES TRANSACCION
            $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
            $fila1 = pg_fetch_row($iddettran);
            $fila1[0] = $fila1[0] + 1;
            $merca = pg_query("select cuenta_debito from parametros where descripcion='VENTA MERCADERIA'");
            $fila3 = pg_fetch_row($merca);
            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila3[0] . "','0.000','$sum','Activo')");
            $fila1[0] = $fila1[0] + 1;
            /* $planiva=pg_query("select cuenta_credito from parametros where descripcion='IVA'");
              $fila2=pg_fetch_row($planiva);
              pg_query("insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','".$fila2[0]."','0.000','".$_POST['iva']."','Activo')");
              $fila1[0]=$fila1[0]+1; */
            $plancaja = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
            $fila2 = pg_fetch_row($plancaja);
            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','" . $_POST['tot'] . "','0.000','Activo')");
        }
    }

    ////////////////////////////////////////GUIA_REMISION
} else if ($_POST["id_fac"] != 0) {
}
echo $data = json_encode($item);
