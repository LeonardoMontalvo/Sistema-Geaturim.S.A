<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['com'];
$arr_data = array();

$consulta = pg_query(
    "SELECT  P.fecha_actual, P.hora_actual, U.nombre_usuario, U.apellido_usuario, C.id_cliente, C.identificacion, C.nombres_cli, P.forma_pago, P.tipo_pago,P.tipo_factura,P.banco  
    from pagos_cobrar P, clientes C, usuario U 
    where P.id_cliente=C.id_cliente and P.id_usuario=U.id_usuario and P.id_pagos_cobrar='" . $id . "';"
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
     $arr_data[] = $row[9];
      $arr_data[] = $row[10];
}
echo json_encode($arr_data);
