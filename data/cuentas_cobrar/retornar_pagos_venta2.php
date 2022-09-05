<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['com'];
$arr_data = array();

$consulta = pg_query(
    "SELECT P.id_pagos_cobrar, P.num_factura, P.tipo_factura, P.fecha_factura, P.total_factura, P.valor_pagado, P.saldo_factura, P.observaciones, P.comprobante 
    FROM pagos_cobrar P where P.id_pagos_cobrar='" . $id . "';"
);
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
}
echo json_encode($arr_data);
