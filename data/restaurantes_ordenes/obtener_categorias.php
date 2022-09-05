<?php
session_start();
include '../../procesos/base.php';
conectarse();

$sql = "select * from categoria where estado='Activo' order by nombre_categoria asc";
$res = pg_query($sql);
$rows = pg_fetch_all($res);
if (empty($rows)) {
    $rows = [];
}
echo json_encode($rows);
