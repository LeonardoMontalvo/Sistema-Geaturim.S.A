<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['id2'];
$arr_data = array();

$consulta = pg_query("select
P.cod_productos, P.codigo,
P.articulo, P.stock,
D.cantidad, D.precio_venta,
D.Descuento_producto, D.total_venta,
P.iva, P.incluye_iva,
P.inventariable
from productos P, detalle_reservacion D, reservaciones R
where P.cod_productos=D.cod_productos
and R.id_reservacion = D.id_reservacion
and R.estado='Activo'
and D.estado='Activo'
and R.id_reservacion='" . $id . "'");
while ($row = pg_fetch_row($consulta)) {
    $arr_data[] = $row[0];
    $arr_data[] = $row[1];
    $arr_data[] = $row[2];
    $arr_data[] = $row[3];
    $arr_data[] = $row[4];
    $arr_data[] = $row[5];
    $arr_data[] = $row[6];
    $arr_data[] = $row[7];
    $arr_data[] = $row[8];
    $arr_data[] = $row[9];
    $arr_data[] = $row[10];
}
echo json_encode($arr_data);
?>
