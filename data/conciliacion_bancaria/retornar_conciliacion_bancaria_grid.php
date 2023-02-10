<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['com'];
$arr_data = array();

$consulta = pg_query("SELECT id_transaccion,fecha_transaccion,comprobante_movimiento,identificador,debe,monto,concepto
  FROM detalle_conciliacion,conciliacion where detalle_conciliacion.id_conciliacion=conciliacion.id_conciliacion and conciliacion.id_conciliacion='$id' 

");
while ($row = pg_fetch_row($consulta)) {
    $arr_data[] = $row[0];
    $arr_data[] = $row[1];
    $arr_data[] = $row[2];
    $arr_data[] = $row[3];
    $arr_data[] = $row[4];
    $arr_data[] = $row[5];
    $arr_data[] = $row[6];
    $arr_data[] = '1';
   
}
echo json_encode($arr_data);
?>
