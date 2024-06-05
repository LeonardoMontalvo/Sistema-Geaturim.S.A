<?php
session_start();
include '../../procesos/base.php';

$search = $_GET["search"];
$page = $_GET["page"];
$regs = 20;
$offset = ($page - 1) * $regs;
$count = 0;

$sql = "


select count(*) from tarifa_impuesto where estado='Activo' ORDER BY id_taimpuesto  ASC
";

$res = pg_query($sql);
$rows = pg_fetch_row($res);

if (!empty($rows)) {
    $count = $rows[0];
}

$sql = "select id_taimpuesto,nombre_taimpuesto from tarifa_impuesto where estado='Activo' ORDER BY id_taimpuesto  ASC";

$res = pg_query($sql);
$rows = pg_fetch_all($res);

if (empty($rows)) {
    $rows = [];
}

echo json_encode(["items" => $rows, "more" => $page * $regs < $count]);
