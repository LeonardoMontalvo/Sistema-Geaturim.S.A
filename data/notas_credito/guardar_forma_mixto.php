<?php

session_start();
include '../../procesos/base.php';
include 'guardar_pxc_nc.php';
include 'proveedor_cxp_nc.php';
require_once '../../procesos/pagosCompra.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
//error_reporting(0);

$conpuntoresult = $_SESSION["PV"];

/////datos series/////
$campo1 = $_POST['campo1'];
$campo2 = $_POST['campo2'];
$campo3 = $_POST['campo3'];
$campo4 = $_POST['campo4'];
$campo5 = $_POST['campo5'];
$campo6 = $_POST['campo6'];
$campo7 = $_POST['campo7'];
$campo8 = $_POST['campo8'];

$arreglo1 = explode('|', $campo1);
$arreglo2 = explode('|', $campo2);
$arreglo3 = explode('|', $campo3);
$arreglo4 = explode('|', $campo4);
$arreglo5 = explode('|', $campo5);
$arreglo6 = explode('|', $campo6);
$arreglo7 = explode('|', $campo7);
$arreglo8 = explode('|', $campo8);

$nelem = count($arreglo1);

///////////////////////////////////////////
for ($i = 1; $i < $nelem; $i++) {
    /////////////////contador serie venta/////////////
    $cont1 = 0;
    $consulta = pg_query("select max(id_formas_pago_mixto_nv) from formas_pago_mixto_nv");
    while ($row = pg_fetch_row($consulta)) {
        $cont1 = $row[0];
    }
    $cont1++;
    if ($arreglo8[$i] == "") {

        $arreglo8[$i] = $_POST['fecha_actual'];
    } else {
        $arreglo8[$i] = $arreglo8[$i];
    }

    if ($arreglo7[$i] != "") {
        //                 echo '<br>GUARDAR FACTURA VENTAGG: <br>' . "insert into formas_pago_mixto_nv values('$cont1','" . strtoupper($arreglo2[$i]) . "','$arreglo8[$i]','" . strtoupper($arreglo3[$i]) . "', '" . strtoupper($arreglo4[$i]) . "','" . strtoupper($arreglo5[$i]) . "','" . strtoupper($arreglo6[$i]) . "','Activo','" . strtoupper($arreglo7[$i]) . "','$_POST[tipo_comprobante]')";//////////////////////////
        //	 
        pg_query("insert into formas_pago_mixto_nv values('$cont1','" . strtoupper($arreglo2[$i]) . "','$arreglo8[$i]','" . strtoupper($arreglo3[$i]) . "', '" . strtoupper($arreglo4[$i]) . "','" . strtoupper($arreglo5[$i]) . "','" . strtoupper($arreglo6[$i]) . "','Activo','" . strtoupper($arreglo7[$i]) . "','$_POST[tipo_comprobante]')");
    } else {
        //                echo '<br>GUARDAR FACTURA VENTA: <br>' . "insert into formas_pago_mixto_nv values('$cont1','" . strtoupper($arreglo2[$i]) . "','$arreglo8[$i]','" . strtoupper($arreglo3[$i]) . "', '" . strtoupper($arreglo4[$i]) . "','" . strtoupper($arreglo5[$i]) . "','" . strtoupper($arreglo6[$i]) . "','Activo',null,'$_POST[tipo_comprobante]')";
        //	 
        pg_query("insert into formas_pago_mixto_nv values('$cont1','" . strtoupper($arreglo2[$i]) . "','$arreglo8[$i]','" . strtoupper($arreglo3[$i]) . "', '" . strtoupper($arreglo4[$i]) . "','" . strtoupper($arreglo5[$i]) . "','" . strtoupper($arreglo6[$i]) . "','Activo',null,'$_POST[tipo_comprobante]')");
    }
    // Auditoria
    insert_registro('CREACION FORMA DE PAGO MIXTO CON ID: ' . $cont1);

    ////////////////////////////////
    ///////////////////modificar series////////
    ////////////////////////////////////////////

    if ($arreglo3[$i] == 'facturasxcobrar') {
        guardarPagoC($arreglo5[$i], strtoupper($arreglo3[$i]), "INTERNA", $arreglo6[$i], "", "");
    }
    if ($arreglo3[$i] == 'cuentaxpagar') {
        $fecha = date('Y-m-d');
        //$proveedor = getProveedor($_POST["id_cliente"]);
        //guardarPagosCompra($proveedor["id_proveedor"], 
        guardarPagosCompra($_POST["id_cliente"], 
        $arreglo2[$i], $_SESSION['id'], $fecha,0,0,'NOTA_C',
        $arreglo6[$i],$arreglo6[$i],'Activo','NC');
    }
}
$data = 1;
echo $data;
