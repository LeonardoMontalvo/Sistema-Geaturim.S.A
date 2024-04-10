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
$campo6 = $_POST['campo6'];



// fin
// contador inventario
$cont1 = 0;
$consulta = pg_query("select max(id_unidad_medida_productos) from unidad_medida_productos");
while ($row = pg_fetch_row($consulta)) {
    $cont1 = $row[0];
}
$cont1++;


pg_query($sql);
// fin
// agregar detalle inventario
$arreglo1 = explode('|', $campo1);
$arreglo2 = explode('|', $campo2);
$arreglo3 = explode('|', $campo3);
$arreglo4 = explode('|', $campo4);


$arreglo5 = explode('|', $campo5);
$arreglo6 = explode('|', $campo6);


$nelem = count($arreglo1);
// fin
//print_r($arreglo4);
for ($i = 0; $i <= $nelem; $i++) {
    // contador detalle inventario
    $cont2 = 0;
    $consulta = pg_query("select max(id_unidad_medida_productos) from unidad_medida_productos");
    while ($row = pg_fetch_row($consulta)) {
        $cont2 = $row[0];
    }
    $cont2++;
    // fin 

if($_POST[cod_productos]!="")
{
    
     pg_query("insert into unidad_medida_productos values('$cont2','$_POST[cod_productos]','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','Activo','','$arreglo5[$i]','$arreglo6[$i]')");
   $data = 1;
 
}else{
    $data = 2;
}
 
}

echo $data;
?>