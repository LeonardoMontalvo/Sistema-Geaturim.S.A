<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

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
   if($arreglo7[$i]!=""){
        
   

    pg_query("insert into formas_pago_mixto_g values('$cont1','" . strtoupper($arreglo2[$i]) . "','$fechaActual','" . strtoupper($arreglo3[$i]) . "', '" . strtoupper($arreglo4[$i]) . "','" . strtoupper($arreglo5[$i]) . "','" . strtoupper($arreglo6[$i]) . "','Activo','" . strtoupper($arreglo7[$i]) . "')");
    }else{
          pg_query("insert into formas_pago_mixto_g values('$cont1','" . strtoupper($arreglo2[$i]) . "','$fechaActual','" . strtoupper($arreglo3[$i]) . "', '" . strtoupper($arreglo4[$i]) . "','" . strtoupper($arreglo5[$i]) . "','" . strtoupper($arreglo6[$i]) . "','Activo',null)");
    }
    
}
$data = 1;
echo $data;
?>
