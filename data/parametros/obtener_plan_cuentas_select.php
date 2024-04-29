<?php
session_start();
include '../../procesos/base.php';

$search = $_GET["search"];
$page = $_GET["page"];
$regs = 20;
$offset = ($page - 1) * $regs;
$count = 0;

$sql = "
select count(*) from plan_cuentas
where estado='Activo' and cuenta='M' and (codigo_plan like '$search%' or descripcion ilike '%$search%');
";

$res = pg_query($sql);
$rows = pg_fetch_row($res);

if (!empty($rows)) {
    $count = $rows[0];
}

$sql = "select id_plan_cuentas, codigo_plan, descripcion from plan_cuentas
where estado='Activo' and cuenta='M' and (codigo_plan like '$search%' 
or descripcion ilike '%$search%') limit $regs offset $offset;";

$res = pg_query($sql);
$rows = pg_fetch_all($res);

if (empty($rows)) {
    $rows = [];
}

echo json_encode(["items" => $rows, "more" => $page * $regs < $count]);
