<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['com'];
$arr_data = array();

$consulta = pg_query("select D.cod_productos, P.codigo, P.articulo, D.cantidad, P.stock, D.id_unidades, U.descripcion, D.costo_unitario from detalle_receta D, productos P, unidades_medida U where P.cod_productos=D.cod_productos and D.id_unidades=U.id_unidades and D.id_receta = '" . $id . "'");
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
