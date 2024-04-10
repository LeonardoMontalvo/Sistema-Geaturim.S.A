<?php

session_start();
include '../../procesos/base.php';
include '../../reportes/reten_elect_gasto.php';
//include '../../reportes/reten_elect_consulta.php';
include '../../firma/firma.php';
include '../../firma/xades.php';
include 'generarPDFRetenGAS.php';
include '../../admin/correo.php';
require_once __DIR__ . '/../../procesos/configuracion.php';

$conf = new Configuracion();
$esquema = $conf->getNombreEsquema();
$appFirma = $conf->getPathAplicacionFIrma("app_firma");
$pathXmls = $conf->getPathXmlsFirma();
$pathARchivoP12 = $conf->getArchivoP12();
$claveFirma = $conf->getParametroEmpresa("clave_firma");

conectarse();
error_reporting(0);

$datosimprimir = 0;
 $forma = $_POST['formascc'];
$defaultMail = "franciis.cevallos@gmail.com";
date_default_timezone_set('America/Guayaquil');
$resultreten = 0;
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

$conpuntoresult = $_SESSION['PV'];
//contador factura compra
$cont1 = 0;
$consulta = pg_query("select max(id_retencion_fuente_factura_compra) from retencion_fuente_factura_compra");
while ($row = pg_fetch_row($consulta)) {
    $cont1 = $row[0];
}
$cont1++;


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
    $resultado = pg_query("SELECT  P.correo, P.empresa_pro, RF.valor_compra, RF.num_autorizacion, RF.fecha FROM retencion_fuente_factura_compra RF INNER JOIN gastos FC ON RF.id_factura = FC.id_gastos INNER JOIN proveedores P ON P.id_proveedor=FC.id_proveedor where RF.id_retencion_fuente_factura_compra='" . $_POST['id'] . "'");

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
    $consulta_clave = pg_query("SELECT  RF.clave FROM retencion_fuente_factura_compra RF INNER JOIN gastos FC ON RF.id_factura = FC.id_gastos INNER JOIN proveedores P ON P.id_proveedor=FC.id_proveedor where RF.id_retencion_fuente_factura_compra='" . $_POST['id'] . "' ");
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
//    print_r($respuesta);
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
        'id' => $_POST[id_factura]
    );
}
if (isset($_POST['enviarxml']) == "enviarxml") {
    $datosimprimir = 1;
    $consulta_clave = pg_query("SELECT  RF.clave FROM retencion_fuente_factura_compra RF INNER JOIN gastos FC ON RF.id_factura = FC.id_gastos INNER JOIN proveedores P ON P.id_proveedor=FC.id_proveedor where RF.id_retencion_fuente_factura_compra='" . $_POST['id'] . "' ");
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

//    $result = generarXMLRETGASTO($_POST['id'], $codDoc, $ambiente, $emision);
//    $doc = new DOMDocument('1.0', 'UTF-8');
//    $doc->loadXML($result); // xml 
//    $doc->save($pathXmls . "fac" . '.xml');
////    exec("$appFirma " . '../../xmls/'.$esquema.'/fac', $resultado);
//    $respuesta = consultarComprobante($ambiente, $consult_clave);

    // print_r($respuesta);
//    if (isset($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado)) {
//        if ($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado == 'AUTORIZADO') {
//            $numeroAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion;
//            $fechaAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;
//            $ambienteAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente;
//
//            $data = 2;
//            pg_query("UPDATE retencion_fuente_factura_compra SET fecha = '" . $fechaAutorizacion . "',  estado = '2', num_autorizacion = '" . $numeroAutorizacion . "' WHERE id_retencion_fuente_factura_compra = '" . $_POST['id'] . "'");
//            $dataFile = generarXMLCDATAFACGASTOS($respuesta);
//            $doc = new DOMDocument('1.0', 'UTF-8');
//            $doc->loadXML($dataFile); // xml  
//            $doc->save($pathXmls . $numeroAutorizacion . '.xml');
//        } else {
//            $data = 7;
//            pg_query("UPDATE retencion_fuente_factura_compra SET estado = '7' where id_retencion_fuente_factura_compra = '" . $_POST['id'] . "'"); // NO AUTORIZADO
//        }
//    }

    $itemuno = array(
        'estado' => $data,
        'id' => $_POST[id_factura]
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
    $consulta_bienes = pg_query("select SUM(total_compra::FLOAT) from detalle_gastos where  id_gastos ='$_POST[id_factura]' and bien_servicio='B'");

    while ($row = pg_fetch_row($consulta_bienes)) {
        $valor_totalBienes = $row[0];
    }
    if ($_POST[id_retencion_fuente] == 0) {
        $_POST[id_retencion_fuente] = 1;
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
            while ($row = pg_fetch_row($consultare)) {
                $contre = $row[0];
            }
            $contre++;
            pg_query("insert into retencion_fuente_factura_compra values('" . $cont1 . "', '$_POST[id_factura]', '$arreglo6reten[$i]','$_POST[fecha_actual]','" . $hora . "','$_POST[valor_factura]','$_POST[iva_factura]','$arreglo4reten[$i]', '$conpuntoresult', '$_POST[serie_retencion]','','10','','2')");
            pg_query("insert into detallecomprobanteretencion values('$contre','$cont1' ,'$arreglo6reten[$i]','$arreglo1reten[$i]','$arreglo2reten[$i]','$arreglo3reten[$i]','$arreglo4reten[$i]')");
            pg_query("update retencion_fuente_factura_compra set valor_retencion='$arreglo4reten[$i]'  where id_factura='$cont1' and id_retencion_fuente='$arreglo6reten[$i]' and valor_compra='$arreglo1reten[$i]' and id_gastos='10' ");
        }

        if ($_POST['valor_seleccion_si_no'] == 1) {
            $consulta_servicio = pg_query("select SUM(total_compra::FLOAT) from detalle_gastos where  id_gastos ='$_POST[id_factura]' and bien_servicio='S'");
            while ($row = pg_fetch_row($consulta_servicio)) {
                $valor_totalServicio = $row[0];
            }
        }
    }
    
    
    
     $resultreten = $valfacresult[0] - $_POST['total_reten_iva'];

//    pg_query("UPDATE retencion_fuente_factura_venta set clave='" . $clave . "' where id_factura=$_POST[id_factura] and id_gastos=1");
//    echo '<br>GUARDAR FACTURA VENTAttt: <br>' . "update pagos_venta set monto_credito='" . $resultreten . "' , saldo='" . $resultreten . "' where id_factura_venta='$_POST[id_factura]'"; //////////////////////////


//    pg_query("update pagos_compra set monto_credito='" . $resultreten . "' , saldo='" . $resultreten . "' where id_factura_compra='$_POST[id_factura]'");
    $data_iva = 0;
    $comprobar = pg_query("select id_factura from retencion_iva_factura_compra where id_gastos='10'");
    while ($row2 = pg_fetch_row($comprobar)) {
        if ($row2[0] == $_POST[id_factura]) {
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
        pg_query("insert into retencion_iva_factura_compra values('" . $cont2 . "', '$_POST[id_factura]', '$_POST[id_retencion_iva]','" . $fecha . "','" . $hora . "','$_POST[valor_facturaiva]','$_POST[iva_factura]','$_POST[valor_retencioni]', '$_POST[autorizacion_ret]','$_POST[serie_retencion]','Activo','10')");
        ////////////////////////////////
        ////////////////ASIENTO CONTABLE
        $tran = pg_query("select * from transacciones where comprobante='$_POST[id_factura]' and id_tipo_transaccion='1' and concepto like 'GASTO FACTURA%' and id_empresa= $conpuntoresult");
        $fila = pg_fetch_row($tran);


        $consf = pg_query("select dcr.valor_retenido,dcr.id_retencion_fuentes from detallecomprobanteretencion dcr, retencion_fuente_factura_compra rff ,gastos fc where 
            dcr.id_retencion_fuente_factura_compra=rff.id_retencion_fuente_factura_compra
            and rff.id_factura=fc.id_gastos and fc.id_gastos=$_POST[id_factura] and rff.id_gastos=10 and  dcr.id_trete=2");
        $xi = 0;

        while ($cont2f = pg_fetch_row($consf)) {
            $cons = pg_query("select cuenta_credito from retencion_iva where id_retencion_iva='" . $cont2f[1] . "'");

            while ($cont2 = pg_fetch_row($cons)) {
                $fila1 = 0;
                $consulta = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
                while ($row = pg_fetch_row($consulta)) {
                    $fila1 = $row[0];
                }
                $fila1++;


//	 echo '<br>GUARDAR FACTURA reten: <br>' . "insert into detalle_transaccion values('$fila1','" . $fila[0] . "','" . $cont2[0] . "','0.000','$cont2f[0]','Activo')";//////////////////////////
////	 
                pg_query("insert into detalle_transaccion values('$fila1','" . $fila[0] . "','" . $cont2[0] . "','0.000','$cont2f[0]','Activo')");

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

        $sql = pg_query("select id_plan_cuentas from detalle_transaccion where id_transacciones='" . $fila[0] . "' "
                . "and id_plan_cuentas<>'53'"//"Retenciones IVA Proveedores"
                . "and id_plan_cuentas<>'55'"//"Retenciones en la Fuente Proveedores"
                . "and id_plan_cuentas<>'58'"//"Retenciones en la Fuente Empleados"
                . "and id_plan_cuentas<>'154'"//"Retenciones en la Fuente Socios"
                . "and id_plan_cuentas<>'164'"//"Retenciones en la Fuente Otros"
                . "and id_plan_cuentas<>'165'"//"Impuesto a la Renta por Pagar"
                . "and id_plan_cuentas<>'166'"//"Retención Fuente 2.75% Servicios"
                . "and id_plan_cuentas<>'167'"//"Retención Fuente 8% Arriendos"
                . "and id_plan_cuentas<>'168'"//"Retención Fuente 10% Honorarios"
                . "and id_plan_cuentas<>'169'"//"Iva en Compras"
                . "and id_plan_cuentas<>'170'"//"Inventario Materia Prima"
                . "and id_plan_cuentas<>'171'"//"Inventario Productos en Proceso"
                . "and id_plan_cuentas<>'172'"//"Inventario Productos Terminados"
                . "and id_plan_cuentas<>'173'"//"Inventario 15%"
                . "and id_plan_cuentas<>'174'"//"Inventario 0%"
                . "and id_plan_cuentas<>'175'"//"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'176'"//"Retención Fuente 10% Honorarios"
                . "and id_plan_cuentas<>'177'"//"Iva en Compras"
                . "and id_plan_cuentas<>'178'"//"Inventario Materia Prima"
                . "and id_plan_cuentas<>'179'"//"Inventario Productos en Proceso"
                . "and id_plan_cuentas<>'180'"//"Inventario Productos Terminados"
                . "and id_plan_cuentas<>'181'"//"Inventario 15%"
                . "and id_plan_cuentas<>'182'"//"Inventario 0%"
                . "and id_plan_cuentas<>'183'"//"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'184'"//"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'556'"//"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'52'"//"Retención Fuente 10% Honorarios"
                . "and id_plan_cuentas<>'53'"//"Iva en Compras"
                . "and id_plan_cuentas<>'63'"//"Inventario Materia Prima"
                . "and id_plan_cuentas<>'395'"//"Inventario Productos en Proceso"
                . "and id_plan_cuentas<>'470'"//"Inventario Productos Terminados"
                . "and id_plan_cuentas<>'474'"//"Inventario 15%"
                . "and id_plan_cuentas<>'475'"//"Inventario 0%"
                . "and id_plan_cuentas<>'556'"//"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'593'"//"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'614'"//"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'311'"//"Inventario 15%"
                . "and id_plan_cuentas<>'326'"//"Inventario 0%"
                . "and id_plan_cuentas<>'327'"//"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'423'"//"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'458'"//"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'512'"//"Inventario 15%"
                . "and id_plan_cuentas<>'569'"//"Inventario 0%"
                . "and id_plan_cuentas<>'586'"//"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'612'"//"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'624'"//"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'433'"//"Retención Fuente 10% Honorarios"
                . "and id_plan_cuentas<>'446'"//"Iva en Compras"
                . "and id_plan_cuentas<>'447'"//"Inventario Materia Prima"
                . "and id_plan_cuentas<>'449'"//"Inventario Productos en Proceso"
                . "and id_plan_cuentas<>'480'"//"Inventario Productos Terminados"
                . "and id_plan_cuentas<>'518'"//"Inventario 15%"
                . "and id_plan_cuentas<>'521'"//"Inventario 0%"
                . "and id_plan_cuentas<>'522'"//"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'528'"//"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'540'"//"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'541'"//"Inventario 15%"
                . "and id_plan_cuentas<>'543'"//"Inventario 0%"
                . "and id_plan_cuentas<>'553'"//"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'554'"//"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'586'"//"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'601'"//"Inventario 15%"
                . "and id_plan_cuentas<>'602'"//"Inventario 0%"
                . "and id_plan_cuentas<>'604'"//"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'606'"//"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'607'"//"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'611'"//"Inventario 0%"
                . "and id_plan_cuentas<>'612'"//"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'623'"//"Suministros y Materiales Agrícolas"
                . "and id_plan_cuentas<>'624'"//"Suministros y Materiales Agrícolas"
        );
        $idPlan = pg_fetch_row($sql);
        $plancaja = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
        $caja = pg_fetch_row($plancaja);

        $tot = pg_query("select credito, id_detalle_transaccion from detalle_transaccion where id_transacciones='" . $fila[0] . "' and id_plan_cuentas='" . $caja[0] . "'");
        $s = pg_fetch_row($tot);
        $caja = $s[0] - $xi;
            if ($forma != "otros") {
        pg_query("update detalle_transaccion set credito='" . $caja . "' where id_detalle_transaccion='" . $s[1] . "'");
            } 



        $data = 1;
        $validiva = $_POST['id_retencion_iva'];
        $valfaciva = $_POST[iva_factura];
        $valiva = $_POST['valor_retencioni'];
    }

    ///////////////////

    $consulta_num_factura = pg_query("select num_serie from retencion_fuente_factura_compra where id_factura='$_POST[id_factura]' and id_gastos='10' ");
    while ($row = pg_fetch_row($consulta_num_factura)) {
        $num_serie_fac = $row[0];
    }
    $consulta_fecha_emision = pg_query("select fecha_emision from gastos where id_gastos ='$_POST[id_factura]'");
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


    $valreten = $_POST['valor_retencion'];

    $valfac = pg_query("select * from factura_compra where id_factura_compra ='$_POST[id_factura]'");
    $valfacresult = pg_fetch_row($valfac);
    $resultreten = $valfacresult[19] - $valreten;

    pg_query("UPDATE retencion_fuente_factura_compra set clave='" . $clave . "' where id_factura=$_POST[id_factura] and id_gastos='10'");

    //pg_query("update factura_compra set total_compra='".$resultreten."'  where id_factura_compra='$_POST[id_factura]'");
//  pg_query("update pagos_compra set monto_credito='" . $resultreten . "' , saldo='" . $resultreten . "' where id_factura_compra='$_POST[id_factura]'");
//  pg_query("update detalle_transaccion set credito='" . $caja . "' where id_detalle_transaccion='" . $s[1] . "'");
    ////////FACTURACION ELECTRONICA
    $consulta_empresa = pg_query("select ruc_empresa,clave, token from empresa where id_empresa = 1");
    while ($row = pg_fetch_row($consulta_empresa)) {
        $ruc = $row[0];
        $pass = $row[1];
        $token = $row[2];
    }

//    $result = generarXMLRETGASTO($cont1, $codDoc, $ambiente, $emision);
//    $doc = new DOMDocument('1.0', 'UTF-8');
//    $doc->loadXML($result); // xml 
//    $doc->save($pathXmls . "fac" . '.xml');
////    exec("$appFirma " . '../../xmls/'.$esquema.'/fac', $resultado);
//
//    $respuesta = consultarComprobante($ambiente, $clave);

//    if (isset($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado)) {
//        if ($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado == 'AUTORIZADO') {
//            $numeroAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion;
//            $fechaAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;
//            $ambienteAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente;
//            $data = 2;
//            pg_query("UPDATE retencion_fuente_factura_compra SET fecha = '" . $fechaAutorizacion . "', estado = '2', num_autorizacion = '" . $numeroAutorizacion . "' WHERE id_retencion_fuente_factura_compra = '$cont1'");
//            $dataFile = generarXMLCDATAFACGASTOS($respuesta);
//            $doc = new DOMDocument('1.0', 'UTF-8');
//            $doc->loadXML($dataFile); // xml     
//            $doc->save($pathXmls . $numeroAutorizacion . '.xml');
//            $data = 2;
//        } else {
//            $data = 7;
//            pg_query("UPDATE retencion_fuente_factura_compra SET estado = '7' where id_retencion_fuente_factura_compra = '$cont1'"); // NO AUTORIZADO
//        }
//    }

    $item = array(
        'estado' => $data,
        'id' => $_POST[id_factura]
    );

    ///////////////////////
    ////// ASIENTO CONTABLE
    // pg_query("update detalle_transaccion set credito='".$caja."' where id_detalle_transaccion='".$s[1]."'");
    $tran = pg_query("select * from transacciones where comprobante='$_POST[id_factura]' and id_tipo_transaccion='1'  and concepto like 'GASTO FACTURA%' and id_empresa= $conpuntoresult");
    $fila = pg_fetch_row($tran);


    $consf = pg_query("select dcr.valor_retenido,dcr.id_retencion_fuentes from detallecomprobanteretencion dcr, retencion_fuente_factura_compra rff ,gastos fc where 
    dcr.id_retencion_fuente_factura_compra=rff.id_retencion_fuente_factura_compra
    and rff.id_factura=fc.id_gastos and fc.id_gastos=$_POST[id_factura] and rff.id_gastos=10 and  dcr.id_trete=1");
    $xr = 0;

    while ($cont2f = pg_fetch_row($consf)) {
        $cons = pg_query("select cuenta_credito from retencion_fuentes where id_retencion_fuentes='" . $cont2f[1] . "'");

        while ($cont2 = pg_fetch_row($cons)) {
            $fila1 = 0;
            $consulta = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
            while ($row = pg_fetch_row($consulta)) {
                $fila1 = $row[0];
            }
            $fila1++;

//	 echo '<br>GUARDAR FACTURA RETEN1: <br>' . "insert into detalle_transaccion values('$fila1','" . $fila[0] . "','" . $cont2[0] . "','0.000','$cont2f[0]','Activo')";//////////////////////////
////	 

            pg_query("insert into detalle_transaccion values('$fila1','" . $fila[0] . "','" . $cont2[0] . "','0.000','$cont2f[0]','Activo')");

            $xr = $xr + $cont2f[0];
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

    $sql = pg_query("select id_plan_cuentas from detalle_transaccion where id_transacciones='" . $fila[0] . "' "
            . "and id_plan_cuentas<>'53'"//"Retenciones IVA Proveedores"
            . "and id_plan_cuentas<>'55'"//"Retenciones en la Fuente Proveedores"
            . "and id_plan_cuentas<>'58'"//"Retenciones en la Fuente Empleados"
            . "and id_plan_cuentas<>'154'"//"Retenciones en la Fuente Socios"
            . "and id_plan_cuentas<>'164'"//"Retenciones en la Fuente Otros"
            . "and id_plan_cuentas<>'165'"//"Impuesto a la Renta por Pagar"
            . "and id_plan_cuentas<>'166'"//"Retención Fuente 2.75% Servicios"
            . "and id_plan_cuentas<>'167'"//"Retención Fuente 8% Arriendos"
            . "and id_plan_cuentas<>'168'"//"Retención Fuente 10% Honorarios"
            . "and id_plan_cuentas<>'169'"//"Iva en Compras"
            . "and id_plan_cuentas<>'170'"//"Inventario Materia Prima"
            . "and id_plan_cuentas<>'171'"//"Inventario Productos en Proceso"
            . "and id_plan_cuentas<>'172'"//"Inventario Productos Terminados"
            . "and id_plan_cuentas<>'173'"//"Inventario 15%"
            . "and id_plan_cuentas<>'174'"//"Inventario 0%"
            . "and id_plan_cuentas<>'175'"//"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'176'"//"Retención Fuente 10% Honorarios"
            . "and id_plan_cuentas<>'177'"//"Iva en Compras"
            . "and id_plan_cuentas<>'178'"//"Inventario Materia Prima"
            . "and id_plan_cuentas<>'179'"//"Inventario Productos en Proceso"
            . "and id_plan_cuentas<>'180'"//"Inventario Productos Terminados"
            . "and id_plan_cuentas<>'181'"//"Inventario 15%"
            . "and id_plan_cuentas<>'182'"//"Inventario 0%"
            . "and id_plan_cuentas<>'183'"//"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'184'"//"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'556'"//"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'52'"//"Retención Fuente 10% Honorarios"
            . "and id_plan_cuentas<>'53'"//"Iva en Compras"
            . "and id_plan_cuentas<>'63'"//"Inventario Materia Prima"
            . "and id_plan_cuentas<>'395'"//"Inventario Productos en Proceso"
            . "and id_plan_cuentas<>'470'"//"Inventario Productos Terminados"
            . "and id_plan_cuentas<>'474'"//"Inventario 15%"
            . "and id_plan_cuentas<>'475'"//"Inventario 0%"
            . "and id_plan_cuentas<>'556'"//"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'593'"//"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'614'"//"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'311'"//"Inventario 15%"
            . "and id_plan_cuentas<>'326'"//"Inventario 0%"
            . "and id_plan_cuentas<>'327'"//"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'423'"//"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'458'"//"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'512'"//"Inventario 15%"
            . "and id_plan_cuentas<>'569'"//"Inventario 0%"
            . "and id_plan_cuentas<>'586'"//"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'612'"//"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'624'"//"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'433'"//"Retención Fuente 10% Honorarios"
            . "and id_plan_cuentas<>'446'"//"Iva en Compras"
            . "and id_plan_cuentas<>'447'"//"Inventario Materia Prima"
            . "and id_plan_cuentas<>'449'"//"Inventario Productos en Proceso"
            . "and id_plan_cuentas<>'480'"//"Inventario Productos Terminados"
            . "and id_plan_cuentas<>'518'"//"Inventario 15%"
            . "and id_plan_cuentas<>'521'"//"Inventario 0%"
            . "and id_plan_cuentas<>'522'"//"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'528'"//"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'540'"//"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'541'"//"Inventario 15%"
            . "and id_plan_cuentas<>'543'"//"Inventario 0%"
            . "and id_plan_cuentas<>'553'"//"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'554'"//"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'586'"//"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'601'"//"Inventario 15%"
            . "and id_plan_cuentas<>'602'"//"Inventario 0%"
            . "and id_plan_cuentas<>'604'"//"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'606'"//"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'607'"//"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'611'"//"Inventario 0%"
            . "and id_plan_cuentas<>'612'"//"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'623'"//"Suministros y Materiales Agrícolas"
            . "and id_plan_cuentas<>'624'"//"Suministros y Materiales Agrícolas"
    );
    $idPlan = pg_fetch_row($sql);
    $plancaja = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
    $caja = pg_fetch_row($plancaja);
    $tot = pg_query("select credito, id_detalle_transaccion from detalle_transaccion where id_transacciones='" . $fila[0] . "' and id_plan_cuentas='" . $caja[0] . "'");
    $s = pg_fetch_row($tot);
//    echo $xr . '<BR>';
    $caja = $s[0] - $xr;
   
    if ($forma != "otros") {
    
    pg_query("update detalle_transaccion set credito='" . $caja . "' where id_detalle_transaccion='" . $s[1] . "'");
    }
}

//print_r($_POST[valor_seleccion_iva]);

if ($datosimprimir == 1) {
    echo $data = json_encode($itemuno);
} else {

    echo $data = json_encode($item);
}
?>
