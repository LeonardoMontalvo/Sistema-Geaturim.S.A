<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['com'];
$arr_data = array();

$consulta = pg_query("select F.id_receta, F.fecha_modificacion, U.nombre_usuario, U.apellido_usuario, F.estado, F.costo_producto, P.articulo, F.cod_productos, F.nombre from recetas F, usuario U, productos P where F.id_usuario = U.id_usuario and P.cod_productos=F.cod_productos and F.id_receta = '" . $id . "'");
while ($row = pg_fetch_row($consulta)) {
    $arr_data[] = $row[0];
    $arr_data[] = $row[1];
    $arr_data[] = $row[2]." ".$row[3];
    $arr_data[] = $row[4];
    $arr_data[] = $row[5];
    $arr_data[] = $row[6];
    $arr_data[] = $row[7];
    $arr_data[] = $row[8];
}
echo json_encode($arr_data);
?>
