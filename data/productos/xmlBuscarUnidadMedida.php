<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['com'];
$arr_data = array();

$consulta = pg_query("SELECT unidades_medida.id_unidades,descripcion,cantidad, pvpmino, pvpmayo, pvpnego FROM unidad_medida_productos, productos,unidades_medida where unidades_medida.id_unidades=unidad_medida_productos.id_unidades and unidad_medida_productos.cod_productos=productos.cod_productos and productos.cod_productos=" . $id . "");
while ($row = pg_fetch_row($consulta)) {
    
    $arr_data[] = $row[0];   
    $arr_data[] = $row[1];    
    $arr_data[] = $row[2];
    $arr_data[] = $row[3];
    $arr_data[] = $row[4];
    $arr_data[] = $row[5];
   
}
echo json_encode($arr_data);
?>
