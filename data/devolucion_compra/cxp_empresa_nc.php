<?php
session_start();
include '../../procesos/base.php';
$conexion = conectarse();
$idproveedor = $_GET["id_proveedor"];
$sql = "
select
pc.id_pagos_compra,
pc.id_factura_compra,
fc.num_serie num_factura,
pc.fecha_credito,
pc.monto_credito,
pc.saldo
from pagos_compra pc
INNER JOIN factura_compra fc
on pc.id_factura_compra=fc.id_factura_compra
WHERE pc.id_proveedor=$idproveedor
AND tipo_documento='FACTURA'
AND pc.estado='Activo'
AND comprao_gasto='C';
";
$res = pg_query($sql);
$rows = pg_fetch_all($res);
if (empty($rows)) {
    $rows = [];
}

echo json_encode($rows);
