<?php
session_start();
include '../../procesos/base.php';
conectarse();

$productoid=$_GET["id_producto"];

$sql = "select * from promociones where estado='Activo' and cod_productos=$productoid";
$res = pg_query($sql);
$rows = pg_fetch_all($res);
if (empty($rows)) {
    $rows = [];
}
echo json_encode($rows);
