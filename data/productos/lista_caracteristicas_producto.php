<?php
session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();

$codprod = $_GET["cod_productos"];

$sql = "select*from producto_caracteristicas where cod_productos=$codprod";
$res = pg_query($sql);
$rows = pg_fetch_all($res);
if (!$rows) {
    $rows = [];
}
echo json_encode($rows);
