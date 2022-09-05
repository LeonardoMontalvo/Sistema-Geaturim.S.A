<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['com'];
$arr_data = array();

$consulta = pg_query("select R.id_cliente, C.nombres_cli, R.id_tipo_equipo, A.descripcion, R.fecha_ingreso, R.fecha_salida, R.modelo, R.nro_serie, R.id_marca, M.nombre_marca, R.id_color, O.nombre_color, R.observaciones, R.detalles, R.estado from registro_equipo R, clientes C, tipo_equipo A, marcas M, color O where R.id_cliente = C.id_cliente and R.id_tipo_equipo = A.id_tipo_equipo and R.id_marca = M.id_marca  and R.id_color = O.id_color and R.id_registro = '" . $id . "'");
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
    $arr_data[] = $row[13];
}
echo json_encode($arr_data);
?>
