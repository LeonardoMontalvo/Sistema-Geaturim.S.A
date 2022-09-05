<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['com'];
$arr_data = array();

$consulta = pg_query("SELECT plan_cuentas.id_plan_cuentas, codigo_plan, descripcion, fecha_inicio, fecha_fin,conciliacion.estado
  FROM plan_cuentas,conciliacion where plan_cuentas.id_plan_cuentas=conciliacion.id_plan_cuentas and conciliacion.id_conciliacion='$id' limit 1
");
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
