<?php

session_start();
include '../../procesos/base.php';
include '../notas_credito/notacredito_elect_xml.php';
include '../../firma/firma.php';
include '../../firma/xades.php';
include '../../admin/correo.php';
include 'generarPDFNota.php';
require_once '../../procesos/kardexValorizado.php';
require_once '../../procesos/detalleProductosBodega.php';
require_once __DIR__ . '/../../procesos/configuracion.php';

$conf = new Configuracion();
$esquema = $conf->getNombreEsquema();
$appFirma = $conf->getPathAplicacionFIrma("app_firma");
$pathXmls = $conf->getPathXmlsFirma();
$pathARchivoP12 = $conf->getArchivoP12();
$claveFirma = $conf->getParametroEmpresa("clave_firma");

conectarse();
error_reporting(0);

$defaultMail = "jpantojarevelo@gmail.com";
$conpuntoresult = $_SESSION['PV'];
$costoVenta = 0;
$costoVenta1 = 0;
$inventario0 = 0;
$inventario12 = 0;


$sumaSubtotalTarifa12 = 0;
$codplanTarifa12 = 0;
$contTarifa12 = 0;
$sumaSubtotalTarifa0 = 0;
$codplanTarifa0 = 0;
$contTarifa0 = 0;

if (isset($_POST['reenviarcorreo']) == "reenviarcorreo") {

    $resultado = pg_query("SELECT C.correo, C.nombres_cli, F.total_venta ,F.num_autorizacion, F.fecha_actual  FROM devolucion_venta F, clientes C WHERE F.id_cliente = C.id_cliente AND F.id_devolucion_venta= '" . $_POST['id'] . "'");
    while ($row = pg_fetch_row($resultado)) {
        $email = $row[0];
        $nombre = $row[1];
        $total = $row[2];
        $num_autorizacion = $row[3];
        $fecha = $row[4];
    }

    $total_venta_tot = 0;
    $total_venta_tot = round($total, 2);
    $data = correo($fecha, $total_venta_tot, $pathXmls . $num_autorizacion . '.xml', $num_autorizacion . '.pdf', $nombre, $email, $pathXmls . $num_autorizacion . '.xml', generarPDFNota($_POST['id']), 1);

    if ($data == 1) {
        $resultado = pg_query("UPDATE devolucion_venta set estado = '1' where id_devolucion_venta = '" . $_POST['id'] . "'");

        if ($resultado) {
            $data = 1; // datos actualizados
        } else {
            $data = 4; // error al momento de guadar
        }
    }


    $item = array('estado' => $data);
}

if (isset($_POST['reenviarxml']) == "reenviarxml") {
    $consulta_clave = pg_query("SELECT  F.clave  FROM devolucion_venta F, clientes C WHERE F.id_cliente = C.id_cliente AND F.id_devolucion_venta='" . $_POST['id'] . "' ");
    while ($row = pg_fetch_row($consulta_clave)) {
        $consult_clave = $row[0];
    }

    $consulta_ambiente = pg_query("select codigo_ambi from ambiente where estado_ambi = 'Activo' ");
    while ($row = pg_fetch_row($consulta_ambiente)) {
        $ambiente = $row[0];
    }


    $respuesta = consultarComprobante($ambiente, $consult_clave);
    //                                 print_r($respuesta);
    if (isset($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado)) {
        if ($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado == 'AUTORIZADO') {
            $numeroAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion;
            $fechaAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;
            $ambienteAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente;
            $data = 2;

            pg_query("UPDATE devolucion_venta SET fecha_actual = '" . $fechaAutorizacion . "',  estado = '2', num_autorizacion = '" . $numeroAutorizacion . "' WHERE id_devolucion_venta = '" . $_POST['id'] . "'");
            $dataFile = generarXMLCDATANOTA($respuesta);
            $doc = new DOMDocument('1.0', 'UTF-8');
            $doc->loadXML($dataFile); // xml     
            $doc->save($pathXmls . $numeroAutorizacion . '.xml');
        } else {
            $data = 7;
            pg_query("UPDATE devolucion_venta SET estado = '7' where id_devolucion_venta = '" . $_POST['id'] . "'"); // NO AUTORIZADO
        }
    } else {

        //                            $data = $respuesta['RespuestaAutorizacionComprobante']['autorizaciones']['autorizacion']['estado'];
        pg_query("UPDATE devolucion_venta SET estado = '7' where id_devolucion_venta = '" . $_POST['id'] . "'"); // Rechazado
    }

    $item = array('estado' => $data, 'id' => $_POST['id']);
}


if (isset($_POST['enviarxml']) == "enviarxml") {
    $consulta_clave = pg_query("SELECT  F.clave  FROM devolucion_venta F, clientes C WHERE F.id_cliente = C.id_cliente AND F.id_devolucion_venta='" . $_POST['id'] . "' ");
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

    $consulta_cod_docu = pg_query("select codigo from tipo_comprobante where id_tipo_comprobante=2");
    while ($row = pg_fetch_row($consulta_cod_docu)) {
        $codDoc = $row[0];  //normal cuando generamos la clave
    }
    $consulta_empresa = pg_query("select ruc_empresa,clave, token from empresa where id_empresa = 1");
    while ($row = pg_fetch_row($consulta_empresa)) {
        $ruc = $row[0];
        $pass = $row[1];
        $token = $row[2];
    }


    $result = generarXMLNOTA($_POST['id'], $codDoc, $ambiente, $emision);
    //    print_r($result);
    $doc = new DOMDocument('1.0', 'UTF-8');
    $doc->loadXML($result); // xml 
    $doc->save($pathXmls . "fac" . '.xml');
    //exec("$appFirma " . '../../xmls/'.$esquema.'/fac', $resultado);
    exec("$appFirma " . $pathXmls . '/fac "' . $pathARchivoP12 . '" "' . $claveFirma . '"', $resultado);

    $respuesta = consultarComprobante($ambiente, $consult_clave);
    //    print_r($respuesta);
    if (isset($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado)) {

        if ($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado == 'AUTORIZADO') {

            $numeroAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion;
            $fechaAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;
            $ambienteAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente;
            $data = 2;

            pg_query("UPDATE devolucion_venta SET fecha_actual = '" . $fechaAutorizacion . "',  estado = '2', num_autorizacion = '" . $numeroAutorizacion . "' WHERE id_devolucion_venta = '" . $_POST['id'] . "'");
            $dataFile = generarXMLCDATANOTA($respuesta);
            $doc = new DOMDocument('1.0', 'UTF-8');
            $doc->loadXML($dataFile); // xml     
            $doc->save($pathXmls . $numeroAutorizacion . '.xml');
        } else {
            $data = 7;
            pg_query("UPDATE devolucion_venta SET estado = '7' where id_devolucion_venta = '" . $_POST['id'] . "'"); // NO AUTORIZADO
        }
    }

    $item = array('estado' => $data, 'id' => $_POST['id']);
}

// datos detalle devolucion compra
$campo1 = $_POST['campo1'];
$campo2 = $_POST['campo2'];
$campo3 = $_POST['campo3'];
$campo4 = $_POST['campo4'];
$campo5 = $_POST['campo5'];
$campo6 = $_POST['campo6'];
$campo7 = $_POST['campo7'];
// fin
//GUARDAR CABECERA NOTA DE CREDITO
/* Clave de acceso para Facturacion Electronica */
////DATOS DE LA EMPRESA
$consulta_empresa = pg_query("select ruc_empresa,clave,token from empresa where id_empresa = 1");
while ($row = pg_fetch_row($consulta_empresa)) {
    $ruc = $row[0];
    $pass = $row[1];
    $token = $row[2];
}
////
////TIPO DE COMPROBANTE
$consulta_comprobante = pg_query("select codigo from tipo_comprobante where id_tipo_comprobante = 2");
while ($row = pg_fetch_row($consulta_comprobante)) {
    $comprobante = $row[0];
}
////
////TIPO DE AMBIENTE
$consulta_ambiente = pg_query("select codigo_ambi from ambiente where estado_ambi = 'Activo'");
while ($row = pg_fetch_row($consulta_ambiente)) {
    $ambiente = $row[0];
}
////
////TIPO DE EMISION
$consulta_emision = pg_query("select codigo_temision from tipo_emision order by codigo_temision asc  limit 1");
while ($row = pg_fetch_row($consulta_emision)) {
    $emision = $row[0]; //normal cuando generamos la clave
}
////
$fecha_emision = $_POST[fecha_actual];
$fechasepar = explode("-", $fecha_emision);
$dia = $fechasepar[2];
$mes = $fechasepar[1];
$anio = $fechasepar[0];
$fecha = "$dia" . "$mes" . "$anio";

////NRO DE SERIE
$serie = "$_POST[num_serie]" . "$_POST[num_nota_credito]";

$seriedigitos = explode("-", $serie);

$clave = generarClave($fecha, $comprobante, $ruc, $ambiente, $seriedigitos[0], $seriedigitos[1] . '' . $seriedigitos[2], $fecha, $emision);



// contador devolucion factura venta
$cont1 = 0;
$consulta = pg_query("select max(id_devolucion_venta) from devolucion_venta");
while ($row = pg_fetch_row($consulta)) {
    $cont1 = $row[0];
}
$cont1++;
// fin
// guardar notas credito
pg_query("insert into devolucion_venta values('$cont1','$conpuntoresult','$_POST[id_cliente]','$_SESSION[id]','$cont1','$_POST[fecha_actual]','$_POST[hora_actual]'
    ,'$_POST[tipo_comprobante]','$_POST[serie]', '$_POST[tarifa0]','$_POST[tarifa12]','$_POST[iva]','$_POST[desc]','$_POST[tot]','$_POST[observaciones]','Activo','$_POST[num_nota_credito]','$_POST[num_serie]','$clave','','$_POST[tipo_motivo]')");
// fin
// agregar detalle_dev_venta 
$arreglo1 = explode('|', $campo1);
$arreglo2 = explode('|', $campo2);
$arreglo3 = explode('|', $campo3);
$arreglo4 = explode('|', $campo4);
$arreglo5 = explode('|', $campo5);
$arreglo6 = explode('|', $campo6);
$arreglo7 = explode('|', $campo7);
$nelem = count($arreglo1);
// fin


for ($i = 0; $i <= $nelem; $i++) {
    // contador detalle devolucion venta
    $cont2 = 0;
    $consulta = pg_query("select max(id_detalle_deventa) from detalle_devolucion_venta");
    while ($row = pg_fetch_row($consulta)) {
        $cont2 = $row[0];
    }
    $cont2++;
    // fin
    // contador kardex valorizado
    $cont_v = 0;
    $consulta_v = pg_query("select max(id_kardex) from kardex_valorizado");
    while ($row = pg_fetch_row($consulta_v)) {
        $cont_v = $row[0];
    }
    $cont_v++;
    // fin 
    // contador kardex
    $cont_k = 0;
    $consulta_k = pg_query("select max(id_kardex) from kardex");
    while ($row = pg_fetch_row($consulta_k)) {
        $cont_k = $row[0];
    }
    $cont_k++;
    // fin
    // guardar detalle_factura_Venta

    pg_query("insert into detalle_devolucion_venta values('$cont2','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','Activo','$arreglo6[$i]','$arreglo7[$i]')");

    // modificar productos general
    $consulta2 = pg_query("select * from productos where cod_productos = '$arreglo1[$i]'");
    while ($row = pg_fetch_row($consulta2)) {
        $stock = $row[13];
    }
    $cal = $stock + $arreglo2[$i];

    //    pg_query("Update productos Set stock='" . $cal . "' where cod_productos='" . $arreglo1[$i] . "'");
    /////////////////////////////

    $consulta_v = pg_query("select * from detalle_producto_bodega where id_bodega=$conpuntoresult and cod_productos=$arreglo1[$i]");
    while ($row = pg_fetch_row($consulta_v)) {
        $cod_pro = $row[1];
        $id_bod = $row[2];
        $stock = $row[6];
    }

    if ($arreglo6[$i] != 0) {
        $cal = $stock + $arreglo6[$i];
    } else {
        $cal = $stock + $arreglo2[$i];
    }


    if ($cod_pro == $arreglo1[$i] && $id_bod == $conpuntoresult) {
        pg_query("Update detalle_producto_bodega Set fecha='" . $_POST[fecha_actual] . "' ,hora='" . $_POST[hora_actual] . "', stock='" . $cal . "' where cod_productos='" . $arreglo1[$i] . "' and id_bodega='" . $conpuntoresult . "' ");
    }
    // fin
    $cantidad = 0;
    $precio_total = 0;
    $precio_unitario = 0;
    // consulta kardex valorizado
    $consulta2 = pg_query("select * from kardex_valorizado where cod_productos = '$arreglo1[$i]' order by id_kardex asc");
    while ($row = pg_fetch_row($consulta2)) {
        $cantidad = $row[11];
        $precio_unitario = $row[7];
        $precio_total = $row[8];
        $costo_ven_unitario = $row[13];
        //$costoVenta = $row[13];
        $contR = 1;
    }
    if ($precio_unitario == 0) {
        $precio_unitario = $arreglo3[$i];
    }
    if ($arreglo6[$i] != 0) {
        $arreglo2[$i] = $arreglo6[$i];
    } else {
        $arreglo2[$i] = $arreglo2[$i];
    }

    $precio_unitario_entrada = obtenerCostoPromedioUnitarioAnular($arreglo1[$i], $conpuntoresult, $_POST["id_factura_venta"], 'V');
    $costoVenta = $precio_unitario_entrada;


    $costoVenta1 += round(($costoVenta * $arreglo2[$i]), 4);
    $cantidad_salida = $arreglo2[$i];
    $precio_unitario_salida = round($costoVenta, 4);
    $precio_total_salida = round(($arreglo2[$i] * $costoVenta), 4);
    $cantidad_total = $cantidad + $arreglo2[$i];
    $precio_total_total = round(($precio_total + $precio_total_salida), 4);
    $precio_unitario_total = round(($precio_total_total / $cantidad_total), 4);

    $consulta_v = pg_query("select * from detalle_producto_bodega where id_bodega=$conpuntoresult and cod_productos=$arreglo1[$i]");
    while ($row = pg_fetch_row($consulta_v)) {
        $cod_pro = $row[1];
        $id_bod = $row[2];
        $stock = $row[6];
    }
    $cliente1 = "";
    if ($_POST['id_cliente'] == "") {
        $cliente1 = $contt;

        /*  pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '" . 'N.C:' . $_POST['serie'] . "' ,"
          . "'" . number_format($arreglo2[$i], 2, '.', '') . "','" . number_format($arreglo3[$i], 4, '.', '') . "',"
          . "'" . number_format($arreglo5[$i], 4, '.', '') . "','$arreglo1[$i]','" . number_format($cal, 4, '.', '') . "',"
          . "'Activo',NULL,NULL,'$cliente1','$cont1','NC','$conpuntoresult','')"); */
        insertKardex($_POST['fecha_actual'], 'N.C:' . $_POST['serie'], $arreglo2[$i], $arreglo3[$i], $arreglo5[$i], $arreglo1[$i], $cal, 'Activo', NULL, NULL, $cliente1, $cont1, 'NC', $conpuntoresult, '');
    } else {

        $cliente1 = $_POST['id_cliente'];
        insertKardex($_POST['fecha_actual'], 'N.C:' . $_POST['serie'], $arreglo2[$i], $arreglo3[$i], $arreglo5[$i], $arreglo1[$i], $cal, 'Activo', NULL, NULL, $cliente1, $cont1, 'NC', $conpuntoresult, '');
        /* pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '" . 'N.C:' . $_POST['serie'] . "' ,"
          . "'" . number_format($arreglo2[$i], 2, '.', '') . "','" . number_format($arreglo3[$i], 4, '.', '') . "',"
          . "'" . number_format($arreglo5[$i], 4, '.', '') . "','$arreglo1[$i]','" . number_format($cal, 4, '.', '') . "',"
          . "'Activo', NULL , NULL,'$cliente1','$cont1','NC','$conpuntoresult','')"); */
    }
    /* pg_query("insert into kardex_valorizado values(" . $cont_v . ",'" . $arreglo1[$i] . "','$_POST[fecha_actual]', '" . 'N C: ' . $_POST['serie'] . '-' . $_POST['num_factura'] . "'"
      . ",'" . $cantidad_salida . "',NULL,'" . $cantidad . "','" . number_format($precio_unitario_salida, 4, ".", "") . "'"
      . ",'" . number_format($precio_total_salida, 4, ".", "") . "',NULL,NULL,'" . $cantidad_total . "','4','" . number_format($costoVenta, 4, ".", "") . "','$conpuntoresult','NC','$cont1')"); */
    procesarKardexValorizadoEntrada($arreglo1[$i], $_POST['fecha_actual'], 'N C: ' . $_POST['serie'] . '-' . $_POST['num_factura'], $arreglo2[$i], $cantidad, $precio_unitario_entrada, 'Activo', $conpuntoresult, 'NC', $cont1, NULL, NULL);

    $arreglo2[$i] = $arreglo2[$i];
    ////////////////////////
    //Asiento Contable 
    $cuenta = pg_query("select iva,id_plan_cuentas from productos where cod_productos ='" . $arreglo1[$i] . "'");
    $plan = pg_fetch_row($cuenta);

    if ($plan[0] == "Si") {
        $inventario12 = $inventario12 + ($costoVenta * $arreglo2[$i]);
        $sumaSubtotalTarifa12 = +$arreglo5[$i];
        $codplanTarifa12 = $plan[1];
        $contTarifa12++;
    } else if ($plan[0] == "No") {
        $inventario0 = $inventario0 + ($costoVenta * $arreglo2[$i]);
        $sumaSubtotalTarifa0 = +$arreglo5[$i];
        $codplanTarifa0 = $plan[1];
        $contTarifa0++;
    }
}


$consulta_comprobante = pg_query("select codigo from tipo_comprobante where id_tipo_comprobante = 2");
while ($row = pg_fetch_row($consulta_comprobante)) {
    $comprobante = $row[0];
}
$consulta_ambiente = pg_query("select codigo_ambi from ambiente where estado_ambi = 'Activo' order by codigo_ambi asc");
while ($row = pg_fetch_row($consulta_ambiente)) {
    $ambiente = $row[0];
}
$consulta_emision = pg_query("select codigo_temision from tipo_emision order by codigo_temision asc  limit 1");
while ($row = pg_fetch_row($consulta_emision)) {
    $emision = $row[0]; //normal cuando generamos la clave
}
print_r();
if ($_POST['tipo_motivo'] != "") {
    //    print_r($_POST['ruc_ci']);
    $result = generarXMLNOTA($cont1, $comprobante, $ambiente, $emision);
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

            pg_query("UPDATE devolucion_venta SET fecha_actual = '" . $fechaAutorizacion . "',  estado = '2', num_autorizacion = '" . $numeroAutorizacion . "' WHERE id_devolucion_venta = '$cont1'");
            $dataFile = generarXMLCDATANOTA($respuesta);
            $doc = new DOMDocument('1.0', 'UTF-8');
            $doc->loadXML($dataFile); // xml     
            $doc->save($pathXmls . $numeroAutorizacion . '.xml');
        } else {
            $data = 7;
            pg_query("UPDATE devolucion_venta SET estado = '7' where id_devolucion_venta = '$cont1'"); // NO AUTORIZADO
        }
    }

    $item = array('estado' => $data, 'id' => $cont1);
}
//////////////////////////////////////
//////////////////////////////////////////////////
///////////////////////////////// ASIENTO CONTABLE

if ($_POST[tipo_comprobante] == "FACTURA") {

//////////////////////////////////////
    ////update pagos venta saldo/////
    $valfac = pg_query("SELECT  monto_credito FROM pagos_venta where  estado='Activo' and tipo_documento='Factura' and id_factura_venta='$_POST[id_factura_venta]'");
    $valfacresult = pg_fetch_row($valfac);
                            //monto pagos venta -   monto nota credito      
    $total_nota_credito = $valfacresult[0] - $_POST[tot];

    pg_query("Update pagos_venta Set saldo = '$total_nota_credito', monto_credito = '$total_nota_credito' where id_factura_venta = '$_POST[id_factura_venta]' and tipo_documento='Factura'");
///////////////////////////////

    $sql = pg_query("select forma_pago from factura_venta where num_factura='" . $_POST["serie"] . "'");
    $formaPagoFac = pg_fetch_row($sql);
    $forma = "";
    //           echo ':1::.'.$formaPagoFac[0];
    if ($formaPagoFac[0] == "otros") {
        $sql = pg_query("select formas_pago_mixto.forma_pago 
           from factura_venta,formas_pago_mixto
           where factura_venta.id_factura_venta=formas_pago_mixto.id_factura_venta
           and  num_factura='" . $_POST[serie] . "' 
            and  formas_pago_mixto.tipo_documento='FACTURA' ");

        $formaPagoFac = pg_fetch_row($sql);
        $forma = "";


        if ($formaPagoFac[0] == "CONTADO") {
            //            echo ':1::';
            $plancaja = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
            $fila2 = pg_fetch_row($plancaja);
            $forma = $fila2[0];
        }
        if ($formaPagoFac[0] == "CREDITO") {
            //                    echo ':2::';
            $plancaja = pg_query("select cuenta_debito from parametros where descripcion='CUENTAS POR COBRAR'");
            $fila2 = pg_fetch_row($plancaja);
            $forma = $fila2[0];
        }
        if ($formaPagoFac[0] == "tCREDITO") {
            //                    echo ':3::';
            $plancaja = pg_query("select cuenta_debito from parametros where descripcion='TARJETA DE CREDITO'");
            $fila2 = pg_fetch_row($plancaja);
            $forma = $fila2[0];
        }
        if ($formaPagoFac[0] == "TRANSFERENCIAS") {
            //                      echo ':4::';
            $plancaja = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
            $fila2 = pg_fetch_row($plancaja);
            $forma = $fila2[0];
        }
    } else {
        $plancaja = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
        $fila2 = pg_fetch_row($plancaja);
        $forma = $fila2[0];
    }








    // guardar asiento contable TIPO FACTURA
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
    $sum = number_format($sum, 3, '.', '');
    $sum = $sum + $_POST['iva'];
    $saldo = $sum - $_POST['tot'];
    $saldo = number_format($saldo, 3, '.', '');
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



    $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'DEVOLUCIÓN VENTA PRODUCTOS, CLIENTE: " . $p[0] . ", COMPROBANTE: " . $_POST['serie'] . "', '" . $_POST[tot] . "', '$_POST[tot]', '0.000','1','" . ($res[0] + 1) . "','Activo','$cliente1','','$_POST[observaciones]','','','DVFV','',$conpuntoresult,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')");
    //Asiento Costo de ventas
    $asiento2 = pg_query("insert into transacciones values('" . ($fila[0] + 1) . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'COSTO VENTA PRODUCTOS, CLIENTE: " . $p[0] . ", COMPROBANTE: " . $_POST['serie'] . "', '" . $costoVenta1 . "', '" . $costoVenta1 . "', '0.000','1','" . ($res[0] + 1) . "','Activo','$cliente1','','','','','DVFV','',$conpuntoresult,'$_POST[fecha_actual]','" . ($res_pv[0] + 1) . "')");

    $auxiliar = $arreglo1;
    $suma = 0;
    $bool = true;
    $aa = 0;
    $ab = 0;
    $pos = 1;
    $vec = "";
    $ant = $arreglo5;
    $vec1 = "";
    while ($bool) {
        $cont1 = 0;
        $cont2 = 0;
        $aa = 0;
        $ab = 0;
        $tieneiva;
        $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
        $fila1 = pg_fetch_row($iddettran);
        $fila1[0] = $fila1[0] + 1;
        $cuenta = pg_query("select id_plan_cuentas, incluye_iva from productos where cod_productos='" . $auxiliar[1] . "'");
        while ($plan = pg_fetch_row($cuenta)) {
            $cont2 = $plan[0];
            $tieneiva = $plan[1];
        }
        if ($cont2 == 0) {
            $cuenta = pg_query("select id_plan_cuentas, incluye_iva from productos where cod_productos='" . $auxiliar[0] . "'");
            while ($plan = pg_fetch_row($cuenta)) {
                $cont2 = $plan[0];
                $tieneiva = $plan[1];
            }
        }
        $nelem1 = count($auxiliar);
        $suma = 0;
        $abc = 0;
        for ($i = 0; $i <= $nelem1; $i++) {
            $cuenta1 = pg_query("select id_plan_cuentas, incluye_iva from productos where cod_productos='" . $auxiliar[$i] . "'");
            while ($plan1 = pg_fetch_row($cuenta1)) {
                $cont1 = $plan1[0];
                $tieneiva = $plan1[1];
            }
            if ($cont1 != 0) {
                if ($cont1 == $cont2) {
                    if ($tieneiva == "Si") {
                        $abc = ($ivafin + 100) / 100;
                        $abc = number_format($abc, 3, '.', '');
                        $suma = ($ant[$i] / $abc) + $suma;
                    } else {
                        $aa++;
                        $suma = $ant[$i] + $suma;
                    }
                    //$fila1[0]++;
                    //pg_query("insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','4','".$cont2."-".$aa."','".$nelem1."-i".$i."-".$cont1."','prueba')");
                } else {
                    $ab++;
                    $vec[$pos] = $auxiliar[$i];
                    $vec1[$pos] = $ant[$i];
                    $pos++;
                    //$fila1[0]++;
                    //pg_query("insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','4','".$cont2."-".$ab."','".$nelem1."-i".$i."-".$cont1."','prueba2')");
                }
            }
            //$ant=$ant.$cont1."-";     
        }
        // $fila1[0]++;
        $suma = number_format($suma, 3, '.', '');

        if ($vec == "") {
            //$ab++;
            $bool = false;
        } else {
            //$fila1[0]++;
            //pg_query("insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','4','".$pos."','".$vec[$pos-2]."','otra vez')");
            $auxiliar = $vec;
            $ant = $vec1;
            $vec = "";
            $vec1 = "";
            $pos = 0;
        }
    }



    if ($contTarifa0 > 0) {
        $plandevolucion = pg_query("select cuenta_debito from parametros where descripcion='DEVOLUCION EN VENTAS0'");
        $cDevolucion = pg_fetch_row($plandevolucion);
        $fila1[0] = $fila1[0] + 1;
        //        echo 'fvgg' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$cDevolucion[0]','$_POST[tarifa0]','0.000','Activo')";
        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$cDevolucion[0]','$_POST[tarifa0]','0.000','Activo')");
    }
    if ($contTarifa12 > 0) {
        $plandevolucion = pg_query("select cuenta_debito from parametros where descripcion='DEVOLUCION EN VENTAS12'");
        $cDevolucion = pg_fetch_row($plandevolucion);
        $fila1[0] = $fila1[0] + 1;
        //        echo 'fv666' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$cDevolucion[0]','$_POST[tarifa12]','0.000','Activo')";
        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$cDevolucion[0]','$_POST[tarifa12]','0.000','Activo')");
    }
    $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
    $fila1 = pg_fetch_row($iddettran);

    $planiva = pg_query("select cuenta_credito from parametros where descripcion='IVA'");

    $fila2 = pg_fetch_row($planiva);
    if ($_POST['iva'] != '0.000') {
        $fila1[0] = $fila1[0] + 1;
        //        echo 'fv44' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','" . $_POST['iva'] . "','0.000','Activo')";
        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','" . $_POST['iva'] . "','0.000','Activo')");
    }
    $fila1[0] = $fila1[0] + 1;

    //    echo 'fv11' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $forma . "','0.000','" . $_POST['tot'] . "','Activo')";
    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $forma . "','0.000','" . $_POST['tot'] . "','Activo')");

    //detalle costo de ventas
    $plancaja4 = pg_query("select cuenta_debito from parametros where descripcion='COSTO VENTA'");
    $fila4 = pg_fetch_row($plancaja4);
    $fila1[0] = $fila1[0] + 1;
    //    echo 'detalle_transaccion11' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $fila4[0] . "','0.000','" . $costoVenta1 . "','Activo')";
    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $fila4[0] . "','0.000','" . $costoVenta1 . "','Activo')");


    //asiento generico Inventario
    if ($contTarifa0 > 0) {
        $fila1[0] = $fila1[0] + 1;
        //        echo '$contTarifa0' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $codplanTarifa0 . "','" . $inventario0 . "','0.000','Activo')";
        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $codplanTarifa0 . "','" . $inventario0 . "','0.000','Activo')");
    }
    if ($contTarifa12 > 0) {
        $fila1[0] = $fila1[0] + 1;
        //        echo '$contTarifa12' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $codplanTarifa12 . "','" . $inventario12 . "','0.000','Activo')";
        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $codplanTarifa12 . "','" . $inventario12 . "','0.000','Activo')");
    }

    //////////////////////////////////////
    //////////////////////////////////////
} else {
    if ($_POST[tipo_comprobante] == "NOTA") {

        $sql = pg_query("select forma_pago from factura_venta where num_factura='" . $_POST[serie] . "'");
        $formaPagoFac = pg_fetch_row($sql);
        $forma = "";
        if ($formaPagoFac[0] == "Contado") {
            $plancaja = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
            $fila2 = pg_fetch_row($plancaja);
            $forma = $fila2[0];
        }
        if ($formaPagoFac[0] == "Credito") {
            $plancaja = pg_query("select cuenta_debito from parametros where descripcion='CUENTAS POR COBRAR'");
            $fila2 = pg_fetch_row($plancaja);
            $forma = $fila2[0];
        }
        if ($formaPagoFac[0] == "TCredito") {
            $plancaja = pg_query("select cuenta_debito from parametros where descripcion='TARJETA DE CREDITO'");
            $fila2 = pg_fetch_row($plancaja);
            $forma = $fila2[0];
        }


        // guardar asiento contable NOTA
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
            for ($i = 0; $i <= $nelem; $i++) {
                $cuenta1 = pg_query("select id_plan_cuentas from productos where cod_productos='" . $auxiliar[$i] . "'");
                $cIva = pg_query("select incluye_iva from productos where cod_productos='" . $auxiliar[$i] . "'");
                $plan1 = pg_fetch_row($cuenta1);
                $si = pg_fetch_row($cIva);
                $tieneiva = $si[0];
                if ($plan1[$i] == $plan[0]) {
                    //  if($tieneiva=="Si"){
                    //      $sum=($arreglo5[$i]/$abc)+$sum;
                    // }else{
                    //$aa++;
                    //       $sum=$arreglo5[$i]+$sum;
                    // }
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
        $sum = number_format($sum, 3, '.', '');
        $sum = $sum + $_POST['iva'];
        $saldo = $sum - $_POST['tot'];
        $saldo = number_format($saldo, 3, '.', '');
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
        $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'DEVOLUCIÓN VENTA PRODUCTOS, CLIENTE: " . $p[0] . ", COMPROBANTE: " . $_POST['serie'] . "', '" . $_POST[tot] . "', '$_POST[tot]', '" . $saldo . "','1','" . ($res[0] + 1) . "','Activo','$cliente1','$_POST[observaciones]','','','','DVNV','',$conpuntoresult)");
        $asiento2 = pg_query("insert into transacciones values('" . ($fila[0] + 1) . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'COSTO VENTA PRODUCTOS, CLIENTE: " . $p[0] . ", COMPROBANTE: " . $_POST['serie'] . "', '" . $costoVenta1 . "', '" . $costoVenta1 . "', '0.000','1','" . ($res[0] + 1) . "','Activo','$cliente1','','','','','DVNV','',$conpuntoresult)");


        $auxiliar = $arreglo1;
        $suma = 0;
        $bool = true;
        $aa = 0;
        $ab = 0;
        $pos = 1;
        $vec = "";
        $ant = $arreglo5;
        $vec1 = "";
        while ($bool) {
            $cont1 = 0;
            $cont2 = 0;
            $aa = 0;
            $ab = 0;
            $tieneiva;
            $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
            $fila1 = pg_fetch_row($iddettran);
            $fila1[0] = $fila1[0] + 1;
            $cuenta = pg_query("select id_plan_cuentas, incluye_iva from productos where cod_productos='" . $auxiliar[1] . "'");
            while ($plan = pg_fetch_row($cuenta)) {
                $cont2 = $plan[0];
                $tieneiva = $plan[1];
            }
            if ($cont2 == 0) {
                $cuenta = pg_query("select id_plan_cuentas, incluye_iva from productos where cod_productos='" . $auxiliar[0] . "'");
                while ($plan = pg_fetch_row($cuenta)) {
                    $cont2 = $plan[0];
                    $tieneiva = $plan[1];
                }
            }
            $nelem1 = count($auxiliar);
            $suma = 0;
            $abc = 0;
            for ($i = 0; $i <= $nelem1; $i++) {
                $cuenta1 = pg_query("select id_plan_cuentas, incluye_iva from productos where cod_productos='" . $auxiliar[$i] . "'");
                while ($plan1 = pg_fetch_row($cuenta1)) {
                    $cont1 = $plan1[0];
                    $tieneiva = $plan1[1];
                }
                if ($cont1 != 0) {
                    if ($cont1 == $cont2) {
                        if ($tieneiva == "Si") {
                            $abc = ($ivafin + 100) / 100;
                            $abc = number_format($abc, 3, '.', '');
                            $suma = ($ant[$i] / $abc) + $suma;
                        } else {
                            $aa++;
                            $suma = $ant[$i] + $suma;
                        }
                        //$fila1[0]++;
                        //pg_query("insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','4','".$cont2."-".$aa."','".$nelem1."-i".$i."-".$cont1."','prueba')");
                    } else {
                        $ab++;
                        $vec[$pos] = $auxiliar[$i];
                        $vec1[$pos] = $ant[$i];
                        $pos++;
                        //$fila1[0]++;
                        //pg_query("insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','4','".$cont2."-".$ab."','".$nelem1."-i".$i."-".$cont1."','prueba2')");
                    }
                }
                //$ant=$ant.$cont1."-";     
            }
            // $fila1[0]++;
            $suma = number_format($suma, 3, '.', '');

            //pg_query("insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','".$cont2."','0.000','".$suma."','Activo')");
            if ($vec == "") {
                //$ab++;
                $bool = false;
            } else {
                //$fila1[0]++;
                //pg_query("insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','4','".$pos."','".$vec[$pos-2]."','otra vez')");
                $auxiliar = $vec;
                $ant = $vec1;
                $vec = "";
                $vec1 = "";
                $pos = 0;
            }
        }


        //asiento generico Inventario
        if ($sumaSubtotalTarifa0 > 0) {
            $plandevolucion = pg_query("select cuenta_debito from parametros where descripcion='DEVOLUCION EN VENTAS0'");
            $cDevolucion = pg_fetch_row($plandevolucion);
            $fila1[0] = $fila1[0] + 1;
            //            echo 'detalle_transaccion44' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$cDevolucion[0]','$_POST[tarifa0]','0.000','Activo')";
            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$cDevolucion[0]','$_POST[tarifa0]','0.000','Activo')");
        }
        if ($sumaSubtotalTarifa12 > 0) {
            $plandevolucion = pg_query("select cuenta_debito from parametros where descripcion='DEVOLUCION EN VENTAS12'");
            $cDevolucion = pg_fetch_row($plandevolucion);
            $fila1[0] = $fila1[0] + 1;
            //            echo 'detalle_transaccion333' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$cDevolucion[0]','$_POST[tarifa12]','0.000','Activo')";
            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$cDevolucion[0]','$_POST[tarifa12]','0.000','Activo')");
        }

        ///////////////////

        $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
        $fila1 = pg_fetch_row($iddettran);
        $fila1[0] = $fila1[0] + 1;
        //$planiva=pg_query("select cuenta_credito from parametros where descripcion='IVA'");
        //$fila2=pg_fetch_row($planiva);
        // pg_query("insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','".$fila2[0]."','0.000','".$_POST['iva']."','Activo','$cliente1','','','','','NC','')");
        // $fila1[0]=$fila1[0]+1;
        ///  Hacer genérico para caja,cuentas,cobrar
        //$plancaja=pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
        //$fila2=pg_fetch_row($plancaja);
        //pg_query("insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','".$fila2[0]."','0.000','".$_POST['tot']."','Activo')");
        //        echo 'detalle_transaccion112' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $forma . "','0.000','" . $_POST['tot'] . "','Activo')";
        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $forma . "','0.000','" . $_POST['tot'] . "','Activo')");

        //detalle costo de ventas
        $plancaja4 = pg_query("select cuenta_debito from parametros where descripcion='COSTO VENTA'");
        $fila4 = pg_fetch_row($plancaja4);
        $fila1[0] = $fila1[0] + 1;
        //        echo 'detalle_transaccion11' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $fila4[0] . "','0.000','" . $costoVenta1 . "','Activo')";
        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $fila4[0] . "','0.000','" . $costoVenta1 . "','Activo')");


        //asiento generico Inventario
        if ($contTarifa0 > 0) {
            $fila1[0] = $fila1[0] + 1;


            //            echo '$contTarifa0' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $codplanTarifa0 . "','" . $inventario0 . "','0.000','Activo')";
            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $codplanTarifa0 . "','" . $inventario0 . "','0.000','Activo')");
        }
        if ($contTarifa12 > 0) {
            $fila1[0] = $fila1[0] + 1;
            //            echo '$contTarifa12' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $codplanTarifa12 . "','" . $inventario12 . "','0.000','Activo')";
            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . ($fila[0] + 1) . "','" . $codplanTarifa12 . "','" . $inventario12 . "','0.000','Activo')");
        }

        //////////////////////////////////////
        //////////////////////////////////////
    }
}

//////////////////////////////////////////////////
/////////////////////////////////////////////////

echo $data = json_encode($item);
//FRANCIS13012023