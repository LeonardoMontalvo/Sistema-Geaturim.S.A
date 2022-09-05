<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['com'];
$arr_data = array();
$conpunto = 1;
$consultapunto = pg_query("select max(id_punto_venta_empresa) from punto_venta_empresa where punto_venta_empresa.id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($consultapunto)) {
    $conpunto = $row[0];
}

$conpuntoresult = 1;
$consultapuntoresult = pg_query("select id_punto_venta from punto_venta_empresa where id_punto_venta_empresa='$conpunto'");
while ($row = pg_fetch_row($consultapuntoresult)) {
    $conpuntoresult = $row[0];
}
$consulta = pg_query("select I.fecha_actual, I.hora_actual, U.nombre_usuario, U.apellido_usuario, I.origen, I.destino, I.observaciones, I.tarifa0, I.tarifa12, "
        . "I.iva_ingreso, I.descuento_ingreso, I.total_ingreso, I.estado "
        . "from ingresos I, usuario U "
        . "where I.id_usuario = U.id_usuario and I.id_ingresos='" . $id . "' and  I.id_empresa='$conpuntoresult'");
while ($row = pg_fetch_assoc($consulta)) {
    $arr_data[] = $row['fecha_actual'];
    $arr_data[] = $row['hora_actual'];
    $arr_data[] = $row['nombre_usuario'];
    $arr_data[] = $row['apellido_usuario'];
    $arr_data[] = $row['origen'];
    $arr_data[] = $row['destino'];
    $arr_data[] = $row['observaciones'];
    $arr_data[] = $row['tarifa0'];
    $arr_data[] = $row['tarifa12'];
    $arr_data[] = $row['iva_ingreso'];
    $arr_data[] = $row['descuento_ingreso'];
    $arr_data[] = $row['total_ingreso'];
    $arr_data[] = $row['estado'];
}
echo json_encode($arr_data);
?>
