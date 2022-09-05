<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['com'];
$arr_data = array();

$consulta = pg_query("select CC.fecha_actual, CC.hora_actual, U.nombre_usuario, U.apellido_usuario, C.id_cliente,
 C.identificacion, C.nombres_cli, CC.comprobante, CC.forma_pago, CC.monto, CC.fecha_registro from anticipo_clientes  CC, clientes C, usuario U 
where U.id_usuario = CC.id_usuario and C.id_cliente = CC.id_clientes and CC.id_anticipo_clientes='" . $id . "'");
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
