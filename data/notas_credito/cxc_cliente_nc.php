<?php
session_start();
include '../../procesos/base.php';
$conexion = conectarse();
$idcliente = $_GET["id_cliente"];
$sql = "
SELECT 
id_pagos_venta, 
pv.id_factura_venta, 
fv.num_serie||'-'||fv.num_factura num_factura,
fecha_credito, 
monto_credito, 
saldo, 
fecha_dias fecha_caduca
FROM pagos_venta pv
INNER JOIN factura_venta fv
on pv.id_factura_venta=fv.id_factura_venta
WHERE pv.id_cliente=$idcliente
AND  pv.estado='Activo'
AND tipo_documento='Factura';
";
$res = pg_query($sql);
$rows = pg_fetch_all($res);
if (empty($rows)) {
    $rows = [];
}

echo json_encode($rows);
