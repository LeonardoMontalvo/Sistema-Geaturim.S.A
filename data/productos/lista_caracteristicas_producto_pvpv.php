<?php
session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();

//$codprod = $_GET["cod_productos"];

$sql = "select * from pvp_venta_editable pvp  INNER JOIN productos p  on pvp.cod_productos=p.cod_productos";
$res = pg_query($sql);
$rows = pg_fetch_all($res);
if (!$rows) {
    $rows = [];
}
echo json_encode($rows);
