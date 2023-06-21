<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['cod'];
$arr_data = array();

$consulta = pg_query("select um.id_unidades, descripcion,cantidad from productos p left join unidad_medida_productos ump on p.cod_productos=ump.cod_productos 
left join unidades_medida um on um.id_unidades=ump.id_unidades 
  where p.cod_productos='$id' and um.estado='Activo' and ump.estado='Activo'");
while ($row = pg_fetch_row($consulta)) {
     $arr_data[] = $row[0];
    $arr_data[] = $row[1]." ---- ".$row[2];
}
echo json_encode($arr_data);
?>
