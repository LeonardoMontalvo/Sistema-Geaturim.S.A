<?php

session_start();
include __DIR__ . '/../../../procesos/base.php';
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
$search = $_GET['_search'];

$bodega = $_SESSION["PV"];

$total_pages = 0;
$count = 0;

function establecerTotalYRecords(&$total_pages, &$count, $condicionSqlCount = "")
{
    global $limit, $bodega;

    $count_sql = "select
    count(*)
    from transferencias_bodega tb
    where tb.id_usuario_destino is null
    and tb.estado='Activo'
    and tb.estado_transferencia='pendiente'
    and tb.id_bodega_destino=$bodega"
        . $condicionSqlCount;

    $res = pg_query($count_sql);
    $count = pg_fetch_row($res)[0];

    if ($count > 0 && $limit > 0) {
        $total_pages = ceil($count / $limit);
    } else {
        $total_pages = 0;
    }
}

$SQL = "
select
tb.*,
tb.fecha_creacion::date,
tb.fecha_modificacion::date,
uo.nombre_usuario usuario_origen,
ud.nombre_usuario usuario_destino,
b.nombre_punto origen,
bd.nombre_punto destino
from transferencias_bodega tb
inner join usuario uo
on uo.id_usuario=tb.id_usuario_origen
left join usuario ud
on ud.id_usuario=tb.id_usuario_destino
left join punto_venta b
on b.id_punto_venta=tb.id_bodega_origen
left join punto_venta bd
on bd.id_punto_venta=tb.id_bodega_destino
where tb.id_usuario_destino is null
and tb.estado='Activo'
and tb.estado_transferencia='pendiente'
and tb.id_bodega_destino=$bodega
";
$cond = "";

if ($search == 'true') {
    if ($_GET['searchOper'] == 'eq') {
        $cond = " and $_GET[searchField] = '$_GET[searchString]'";
    }
    if ($_GET['searchOper'] == 'cn') {
        $cond = " and $_GET[searchField] ilike '%$_GET[searchString]%'";
    }
}

$SQL .= $cond;

establecerTotalYRecords($total_pages, $count, $cond);

if (!$sidx)
    $sidx = 1;

if ($page > $total_pages)
    $page = $total_pages;

$start = $limit * $page - $limit;

if ($start < 0)
    $start = 0;

$SQL .= " ORDER BY $sidx $sord offset $start limit $limit";

$result = pg_query($SQL);
header("Content-type: text/xml;charset=utf-8");
$s = "<?xml version='1.0' encoding='utf-8'?>";
$s .= "<rows>";
$s .= "<page>" . $page . "</page>";
$s .= "<total>" . $total_pages . "</total>";
$s .= "<records>" . $count . "</records>";
while ($row = pg_fetch_assoc($result)) {
    $s .= "<row id='" . $row['id_transferencia_bodega'] . "'>";
    $s .= "<cell>" . $row['id_transferencia_bodega'] . "</cell>";
    $s .= "<cell>" . $row['id_transferencia_bodega'] . "</cell>";
    $s .= "<cell>" . $row['fecha_creacion'] . "</cell>";
    $s .= "<cell>" . $row['usuario_origen'] . "</cell>";
    $s .= "<cell>" . $row['origen'] . "</cell>";
    $s .= "<cell>" . $row['destino'] . "</cell>";
    $s .= "<cell>" . $row['id_transferencia_bodega'] . "</cell>";
    $s .= "</row>";
}
$s .= "</rows>";
echo $s;
