<?php

session_start();
include '../../procesos/base.php';

conectarse();
error_reporting(0);
date_default_timezone_set('America/Guayaquil');
// datos detalle factura
$campo1 = $_POST['campo1'];
$campo2 = $_POST['campo2'];
$campo3 = $_POST['campo3'];
$campo4 = $_POST['campo4'];
$campo5 = $_POST['campo5'];

// fin
// contador inventario


pg_query($sql);
// fin
// agregar detalle inventario
$arreglo1 = explode('|', $campo1);
$arreglo2 = explode('|', $campo2);
$arreglo3 = explode('|', $campo3);
$arreglo4 = explode('|', $campo4);
$arreglo5 = explode('|', $campo5);

$nelem = count($arreglo1);
// fin
//print_r($arreglo4);


for ($i = 0; $i <= $nelem; $i++) {
    // contador detalle inventario
    // fin 
    $cont2 = 0;
    $consulta = pg_query("select max(id_anticipos) from anticipos");
    while ($row = pg_fetch_row($consulta)) {
        $cont2 = $row[0];
    }
    $cont2++;
//    echo '<br>GUARDAR FACTURA VENTA: <br>' . "insert into anticipos values('$cont2','$_POST[fecha_actual]','$arreglo2[$i]','$arreglo4[$i]','$arreglo3[$i]',$_POST[valor_total],'$arreglo1[$i]','Activo','$_POST[slct_anio_cf]','$_POST[select_mes]','$_SESSION[id]','$_POST[fecha_actual]')"; //////////////////////////


    pg_query("insert into anticipos values('$cont2','$_POST[fecha_actual]','$arreglo2[$i]','$arreglo4[$i]','$arreglo3[$i]',$_POST[valor_total],'$arreglo1[$i]','Activo','$_POST[slct_anio_cf]','$_POST[select_mes]','$_SESSION[id]','$_POST[fecha_actual]')");
}
$data = 1;
echo $data;
?>