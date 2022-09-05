<?php

session_start();
include '../../procesos/base.php';
include '../../reportes/reten_elect_liqui.php';
//include '../../reportes/reten_elect_consulta.php';
include '../../firma/firma.php';
include '../../firma/xades.php';
include 'generarPDFReten.php';
include '../../admin/correo.php';
conectarse();
error_reporting(0);
$datosimprimir = 0;
$defaultMail = "franciis.cevallos@gmail.com";
date_default_timezone_set('America/Guayaquil');

$campo1reten = $_POST['campo1reten'];
$campo2reten = $_POST['campo2reten'];
$campo3reten = $_POST['campo3reten'];
$campo4reten = $_POST['campo4reten'];
$campo5reten = $_POST['campo5reten'];
$campo6reten = $_POST['campo6reten'];
$arreglo1reten = explode('|', $campo1reten);
$arreglo2reten = explode('|', $campo2reten);
$arreglo3reten = explode('|', $campo3reten);
$arreglo4reten = explode('|', $campo4reten);
$arreglo5reten = explode('|', $campo5reten);
$arreglo6reten = explode('|', $campo6reten);
$nelemreten = count($arreglo1reten);
//contador factura compra

$cont2 = 0;
$consultaiva = pg_query("select max(id_retencion_iva_factura_compra) from retencion_iva_factura_compra");
while ($row = pg_fetch_row($consultaiva)) {
    $cont2 = $row[0];
}
$cont2++;
$comprobar = pg_query("select id_factura from retencion_iva_factura_compra");
while ($row2 = pg_fetch_row($comprobar)) {
    if ($row2[0] == $_POST[id_factura]) {
        $data = 2;
    }
}

// fin
//fecha actual
/* $Digital = new Date();
  $year = Digital.getYear();
  $month = Digital.getMonth();
  $day = Digital.getDay();
  $fecha_actual=$year+":"+$month+":"+$day;
  //hora actual
  $Digital = new Date();
  $hours = Digital.getHours();
  $minutes = Digital.getMinutes();
  $seconds = Digital.getSeconds();
  $hora_actual=$hours+":"+$minutes+":"+$seconds; */
$datos = 0;
$valoreten = 0;
$resultreten = 0;
$fecha = date('Y-m-d', time());
$hora = date('h:i:s A', time());


if (isset($_POST['reenviarcorreo']) == "reenviarcorreo") {
    $datosimprimir = 1;
    $resultado = pg_query("SELECT  P.correo, P.empresa_pro, RF.valor_compra, RF.num_autorizacion, RF.fecha FROM retencion_fuente_factura_compra RF INNER JOIN liquidacion_compra FC ON RF.id_factura = FC.id_liquidacion_compra INNER JOIN proveedores P ON P.id_proveedor=FC.id_proveedor where RF.id_retencion_fuente_factura_compra='" . $_POST['id'] . "'");
    while ($row = pg_fetch_row($resultado)) {
        $email = $row[0];
        $nombre = $row[1];
        $total = $row[2];
        $num_autorizacion = $row[3];
        $fecha = $row[4];
    }
    $data = correo($fecha, $total, '../../xmls/' . $num_autorizacion . '.xml', $num_autorizacion . '.pdf', $nombre, $email, '../../xmls/' . $num_autorizacion . '.xml', generarPDFReten($_POST['id']), 1);
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
    $consulta_clave = pg_query("SELECT  RF.clave FROM retencion_fuente_factura_compra RF INNER JOIN liquidacion_compra FC ON RF.id_factura = FC.id_liquidacion_compra INNER JOIN proveedores P ON P.id_proveedor=FC.id_proveedor where RF.id_retencion_fuente_factura_compra='" . $_POST['id'] . "' ");
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
    //print_r($respuesta);
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
            $doc->save('../../xmls/' . $numeroAutorizacion . '.xml');
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
        'id' => $_POST['id']
    );
}
if (isset($_POST['enviarxml']) == "enviarxml") {
    $datosimprimir = 1;
    $consulta_clave = pg_query("SELECT  RF.clave FROM retencion_fuente_factura_compra RF INNER JOIN liquidacion_compra FC ON RF.id_factura = FC.id_liquidacion_compra INNER JOIN proveedores P ON P.id_proveedor=FC.id_proveedor where RF.id_retencion_fuente_factura_compra='" . $_POST['id'] . "' ");
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
    $result = generarXMLRETLIQUI($_POST['id'], $codDoc, $ambiente, $emision);
//    print_r($result);
    $doc = new DOMDocument('1.0', 'UTF-8');
    $doc->loadXML($result); // xml 
    $doc->save('../../xmls/' . "fac" . '.xml');
    exec('D:\xampp\htdocs\syswebfeb\firma\pruebasFE ' . '../../xmls/fac', $resultado);
//    exec('c:\xampp\htdocs\syswebfeb_cota\firma\pruebasFE ' . '../../xmls/fac', $resultado);
    $respuesta = consultarComprobante($ambiente, $consult_clave);

//            print_r($respuesta);
    if (isset($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado)) {
        if ($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado == 'AUTORIZADO') {
            $numeroAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion;
            $fechaAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;
            $ambienteAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente;

            $data = 2;
            pg_query("UPDATE retencion_fuente_factura_compra SET fecha = '" . $fechaAutorizacion . "',  estado = '2', num_autorizacion = '" . $numeroAutorizacion . "' WHERE id_retencion_fuente_factura_compra = '" . $_POST['id'] . "'");
            $dataFile = generarXMLCDATAFACLIQUI($respuesta);
            $doc = new DOMDocument('1.0', 'UTF-8');
            $doc->loadXML($dataFile); // xml  
            $doc->save('../../xmls/' . $numeroAutorizacion . '.xml');
        } else {
            $data = 7;
            pg_query("UPDATE retencion_fuente_factura_compra SET estado = '7' where id_retencion_fuente_factura_compra = '" . $_POST['id'] . "'"); // NO AUTORIZADO
        }
    }

    $itemuno = array(
        'estado' => $data,
        'id' => $_POST['id']
    );
}
if ($datos != 2) {

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

    $consulta_bienes = pg_query("select SUM(total_compra::float) from detalle_factura_compra where id_liquidacion_compra =$_POST[id_factura] and  bien_servicio='B'");
    while ($row = pg_fetch_row($consulta_bienes)) {
        $valor_totalBienes = $row[0];
    }
    if ($_POST[id_retencion_fuente] == 0) {
        $_POST[id_retencion_fuente] = 1;
    }



    for ($i = 0; $i <= $nelemreten; $i++) {

//      print_r($nelemreten);

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
            while ($row = pg_fetch_row($consultare)) {
                $contre = $row[0];
            }
            $contre++;

            pg_query("insert into retencion_fuente_factura_compra values('" . $cont1 . "', '$_POST[id_factura]', '$arreglo6reten[$i]','$_POST[fecha_actual]','" . $hora . "','$arreglo1reten[$i]','$_POST[iva_factura]','$arreglo4reten[$i]', '1', '$_POST[serie_retencion]','','20','','2')");
            pg_query("insert into detallecomprobanteretencion values('$contre','$cont1' ,'$arreglo6reten[$i]','$arreglo1reten[$i]','$arreglo2reten[$i]','$arreglo3reten[$i]','$arreglo4reten[$i]')");
            pg_query("update retencion_fuente_factura_compra set valor_retencion='$arreglo4reten[$i]'  where id_factura='$cont1' and id_retencion_fuente='$arreglo6reten[$i]' and valor_compra='$arreglo1reten[$i]' ");
        }

        if ($_POST['valor_seleccion_si_no'] == 1) {
            $consulta_servicio = pg_query("select SUM(total_compra::float) from detalle_factura_compra where id_liquidacion_compra =$_POST[id_factura] and  bien_servicio='S'");
            while ($row = pg_fetch_row($consulta_servicio)) {
                $valor_totalServicio = $row[0];
            }
        }
    }
    $data_iva = 0;
    $comprobar = pg_query("select id_factura from retencion_iva_factura_compra");
    while ($row2 = pg_fetch_row($comprobar)) {
        if ($row2[0] == $_POST[id_factura]) {
            $data_iva = 2;
        }
    }
    //////////RETIENE IVA//////////
    if ($data_iva != 2) {
        // print_r("fgg");
        $validporcentiva = $_POST['porcent_iva'];

        pg_query("insert into retencion_iva_factura_compra values('$cont2', '$_POST[id_factura]', '$_POST[id_retencion_iva]','" . $fecha . "','" . $hora . "','$_POST[valor_facturaiva]','$_POST[iva_factura]','$_POST[valor_retencioni]', '$_POST[autorizacion_ret]','$_POST[serie_retencion]','Activo','1')");
        $tran = pg_query("select * from transacciones where comprobante='$_POST[id_factura]' and id_tipo_transaccion='1' and concepto like 'LC%'");
        $fila = pg_fetch_row($tran);
        $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
        $fila1 = pg_fetch_row($iddettran);
        $fila1[0] = $fila1[0] + 1;
        $cons = pg_query("select cuenta_credito from retencion_iva where id_retencion_iva='$_POST[id_retencion_iva]'");
        $cont3 = pg_fetch_row($cons);
        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $cont3[0] . "','0.000','$_POST[valor_retencioni]','Activo')");
        $x = $_POST['valor_retencioni'];
        $plancaja = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
        $caja = pg_fetch_row($plancaja);
        $tot = pg_query("select credito, id_detalle_transaccion from detalle_transaccion where id_transacciones='" . $fila[0] . "' and id_plan_cuentas='" . $caja[0] . "'");
        $s = pg_fetch_row($tot);
        $caja = $s[0] - $x;
        pg_query("update detalle_transaccion set credito='" . $caja . "' where id_detalle_transaccion='" . $s[1] . "'");
        $data = 1;
        $validiva = $_POST['id_retencion_iva'];
        $valfaciva = $_POST[iva_factura];
        $valiva = $_POST['valor_retencioni'];
    }

    $consulta_num_factura = pg_query("select num_serie from retencion_fuente_factura_compra where id_factura='$_POST[id_factura]' ");
    while ($row = pg_fetch_row($consulta_num_factura)) {
        $num_serie_fac = $row[0];
    }

    //$fecha_actual=$_POST['fecha_actual'];
    $fechasepar = explode("-", $fecha);
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

    $valfac = pg_query("select * from liquidacion_compra where id_liquidacion_compra ='$_POST[id_factura]'");
    $valfacresult = pg_fetch_row($valfac);
    $resultreten = $valfacresult[19] - $valreten;

    pg_query("UPDATE retencion_fuente_factura_compra set clave='" . $clave . "' where id_factura=$_POST[id_factura]");

    //pg_query("update liquidacion_compra set total_compra='".$resultreten."'  where id_liquidacion_compra='$_POST[id_factura]'");

    pg_query("update pagos_compra set monto_credito='" . $resultreten . "' , saldo='" . $resultreten . "' where id_liquidacion_compra='$_POST[id_factura]'");

    pg_query("update detalle_transaccion set credito='" . $caja . "' where id_detalle_transaccion='" . $s[1] . "'");

    $tran = pg_query("select * from transacciones where comprobante='$_POST[id_factura]' and id_tipo_transaccion='1' and concepto like 'LC%'");
    $fila = pg_fetch_row($tran);
    $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
    $fila1 = pg_fetch_row($iddettran);
    $fila1[0] = $fila1[0] + 1;
    $cons = pg_query("select cuenta_credito from retencion_fuentes where id_retencion_fuentes='$_POST[id_retencion_fuente]'");
    $cont2 = pg_fetch_row($cons);
    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $cont2[0] . "','$_POST[valor_retencion]','0.000','Activo')");
    $x = $_POST['valor_retencion'];
    $plancaja = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
    $caja = pg_fetch_row($plancaja);
    $tot = pg_query("select credito, id_detalle_transaccion from detalle_transaccion where id_transacciones='" . $fila[0] . "' and id_plan_cuentas='" . $caja[0] . "'");
    $s = pg_fetch_row($tot);
    $caja = $s[0] - $x;
    pg_query("update detalle_transaccion set credito='" . $caja . "' where id_detalle_transaccion='" . $s[1] . "'");

    ////////FACTURACION ELECTRONICA
    $consulta_empresa = pg_query("select ruc_empresa,clave, token from empresa where id_empresa = 1");
    while ($row = pg_fetch_row($consulta_empresa)) {
        $ruc = $row[0];
        $pass = $row[1];
        $token = $row[2];
    }

    $result = generarXMLRETLIQUI($cont1, $codDoc, $ambiente, $emision);
    $doc = new DOMDocument('1.0', 'UTF-8');
    $doc->loadXML($result); // xml 
    $doc->save('../../xmls/' . "fac" . '.xml');
   exec('D:\xampp\htdocs\syswebfeb\firma\pruebasFE ' . '../../xmls/fac', $resultado);
//    exec('c:\xampp\htdocs\syswebfeb_cota\firma\pruebasFE ' . '../../xmls/fac', $resultado);

    $respuesta = consultarComprobante($ambiente, $clave);
    if (isset($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado)) {
        if ($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado == 'AUTORIZADO') {
            $numeroAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion;
            $fechaAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;
            $ambienteAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente;
            $data = 2;
            pg_query("UPDATE retencion_fuente_factura_compra SET fecha = '" . $fechaAutorizacion . "', estado = '2', num_autorizacion = '" . $numeroAutorizacion . "' WHERE id_retencion_fuente_factura_compra = '$cont1'");
            $dataFile = generarXMLCDATAFACLIQUI($respuesta);
            $doc = new DOMDocument('1.0', 'UTF-8');
            $doc->loadXML($dataFile); // xml     
            $doc->save('../../xmls/' . $numeroAutorizacion . '.xml');
            $data = 2;
        } else {
            $data = 7;
            pg_query("UPDATE retencion_fuente_factura_compra SET estado = '7' where id_retencion_fuente_factura_compra = '$cont1'"); // NO AUTORIZADO
        }
    }

    $item = array(
        'estado' => $data,
        'id' => $cont1
    );
}

//print_r($_POST[valor_seleccion_iva]);

if ($datosimprimir == 1) {
    echo $data = json_encode($itemuno);
} else {

    echo $data = json_encode($item);
}
?>
