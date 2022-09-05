<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['com'];
$arr_data = array();

$consulta = pg_query("select D.cod_productos, P.codigo, P.articulo, D.costo_unitario, D.cantidad, U.descripcion, D.id_unidades, D.costo_total from recetas F, detalle_receta D, productos P, unidades_medida U where D.cod_productos = P.cod_productos and F.id_receta = D.id_receta and D.id_unidades=U.id_unidades and D.id_receta='" . $id . "'");
while ($row = pg_fetch_row($consulta)) {
    $arr_data[] = $row[0];
    $arr_data[] = $row[1];
    $arr_data[] = $row[2];
    $arr_data[] = $row[3];
    $arr_data[] = $row[4];
    $arr_data[] = $row[5];
    $arr_data[] = $row[6];
    $arr_data[] = $row[7];
}
echo json_encode($arr_data);
?>
