<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['com'];
$arr_data = array();

$consulta = pg_query("select F.id_reservacion, F.fecha_actual, F.hora_actual, U.nombre_usuario, U.apellido_usuario, C.id_cliente, C.identificacion, C.nombres_cli, F.estado, F.tarifa0, F.tarifa12, F.iva, F.total from reservaciones F, clientes C, usuario U where F.id_usuario = U.id_usuario and F.id_cliente = C.id_cliente and F.id_reservacion = '" . $id . "'");
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
    $arr_data[] = $row[11];
    $arr_data[] = $row[12];
}
echo json_encode($arr_data);
?>
