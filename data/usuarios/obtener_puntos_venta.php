<?php
session_start();
include '../../procesos/base.php';
conectarse();

$sql = "select id_punto_venta, nombre_punto from punto_venta where estado='Activo' order by nombre_punto asc";
$res = pg_query($sql);
$rows = pg_fetch_all($res);
if (empty($rows)) {
    $rows = [];
}

echo json_encode($rows);
