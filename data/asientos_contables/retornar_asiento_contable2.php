<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['com'];
$arr_data = array();
$cero="0.000";
$conpuntoresult = $_SESSION['PV'];


$consulta = pg_query("select D.id_detalle_transaccion, D.id_plan_cuentas, P.codigo_plan, P.descripcion, D.debito, D.credito from plan_cuentas P, detalle_transaccion D, transacciones T where D.id_transacciones = T.id_transacciones and D.id_plan_cuentas = P.id_plan_cuentas and T.id_transacciones='" . $id . "' and D.credito='0.000'  and D.debito>'0.000' and  T.id_empresa='$conpuntoresult' order by P.codigo_plan asc");
while ($row = pg_fetch_row($consulta)) {
    $arr_data[] = $row[0];
    $arr_data[] = $row[1];
    $arr_data[] = $row[2];
    $arr_data[] = $row[3];
    $arr_data[] = $row[4];
    $arr_data[] = $row[5];
}
$consulta = pg_query("select D.id_detalle_transaccion, D.id_plan_cuentas, P.codigo_plan, P.descripcion, D.debito, D.credito from plan_cuentas P, detalle_transaccion D, transacciones T where D.id_transacciones = T.id_transacciones and D.id_plan_cuentas = P.id_plan_cuentas and T.id_transacciones='" . $id . "' and D.debito='0.000' and  T.id_empresa='$conpuntoresult'  order by P.codigo_plan desc");
while ($row = pg_fetch_row($consulta)) {
    $arr_data[] = $row[0];
    $arr_data[] = $row[1];
    $arr_data[] = $row[2];
    $arr_data[] = "      ".$row[3];
    $arr_data[] = $row[4];
    $arr_data[] = $row[5];
}


echo json_encode($arr_data);
?>
