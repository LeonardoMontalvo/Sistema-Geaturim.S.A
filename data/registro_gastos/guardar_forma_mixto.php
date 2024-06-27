<?php

session_start();
include '../../procesos/base.php';
require_once '../../procesos/pagosCompra.php';
conectarse();
error_reporting(0);

$conta = $_POST["comprobante"];
$total = 0;
$forma = $_POST['formaspago_mixto'];

$fechaActual = $_POST['fecha_dias'];
if (empty($fechaActual)) {
    $fechaActual = $_POST['fecha_actual'];
}

/////datos series/////
$campo1 = $_POST['campo1'];
$campo2 = $_POST['campo2'];
$campo3 = $_POST['campo3'];
$campo4 = $_POST['campo4'];
$campo5 = $_POST['campo5'];
$campo6 = $_POST['campo6'];
$campo7 = $_POST['campo7'];



$arreglo1 = explode('|', $campo1);
$arreglo2 = explode('|', $campo2);
$arreglo3 = explode('|', $campo3);
$arreglo4 = explode('|', $campo4);
$arreglo5 = explode('|', $campo5);
$arreglo6 = explode('|', $campo6);
$arreglo7 = explode('|', $campo7);


$nelem = count($arreglo1);

///////////////////////////////////////////
for ($i = 1; $i < $nelem; $i++) {

    /////////////////contador serie venta/////////////
    $cont1 = 0;
    $consulta = pg_query("select max(id_formas_pago_mixto_g) from formas_pago_mixto_g");
    while ($row = pg_fetch_row($consulta)) {
        $cont1 = $row[0];
    }
    $cont1++;
    if ($arreglo7[$i] != "") {



        pg_query("insert into formas_pago_mixto_g values('$cont1','" . strtoupper($arreglo2[$i]) . "','$fechaActual','" . strtoupper($arreglo3[$i]) . "', '" . strtoupper($arreglo4[$i]) . "','" . strtoupper($arreglo5[$i]) . "','" . strtoupper($arreglo6[$i]) . "','Activo','" . strtoupper($arreglo7[$i]) . "')");
    } else {
        pg_query("insert into formas_pago_mixto_g values('$cont1','" . strtoupper($arreglo2[$i]) . "','$fechaActual','" . strtoupper($arreglo3[$i]) . "', '" . strtoupper($arreglo4[$i]) . "','" . strtoupper($arreglo5[$i]) . "','" . strtoupper($arreglo6[$i]) . "','Activo',null)");
    }
}

$consulta_mixto = pg_query("select sum(x.sum) from (select formas_pago_mixto_g.forma_pago,sum(formas_pago_mixto_g.valor) from gastos, formas_pago_mixto_g 
    where gastos.id_gastos=formas_pago_mixto_g.id_gastos and gastos.id_gastos='$conta' 
    and (formas_pago_mixto_g.forma_pago='CREDITO' ) GROUP BY formas_pago_mixto_g.forma_pago
    )x");
while ($row = pg_fetch_row($consulta_mixto)) {
    //                    $cont2_mixto_contado = $row[0];
    $valor_credito1 = $row[0];
}
if ($valor_credito1 != "") {
    // variables pagos
    $adelanto = '0.00';
    $meses = $_POST['meses'];
    $total = $valor_credito1;
}
$monto = $total;
$format = number_format($monto, 2, '.', '');
$cont2 = 0;
$consulta = pg_query("select max(id_pagos_compra) from pagos_compra");
while ($row = pg_fetch_row($consulta)) {
    $cont2 = $row[0];
}
$cont2++;
// fin
// variables pagos
$fechaEmision = $_POST['fecha_emision'];
if (empty($fechaEmision)) {
    $fechaEmision = $_POST['fecha_actual'];
}

guardarPagosCompra($_POST['proveedor'], $conta, $_SESSION['id'], $fechaEmision, 0, 0, 'FACTURA', $format, $format, 'Activo', 'G');

$data = 1;
echo $data;
