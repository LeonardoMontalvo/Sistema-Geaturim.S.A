<?php

session_start();
include '../../procesos/base.php';
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
$search = $_GET['_search'];

if (!$sidx)
    $sidx = 1;
$result = pg_query("SELECT COUNT(*) AS count FROM punto_venta");
$row = pg_fetch_row($result);
$count = $row[0];
if ($count > 0 && $limit > 0) {
    $total_pages = ceil($count / $limit);
} else {
    $total_pages = 0;
}
if ($page > $total_pages)
    $page = $total_pages;
$start = $limit * $page - $limit;
if ($start < 0)
    $start = 0;

$SQL = "
select
tg.*, pc.descripcion||' '||pc.codigo_plan
from 
tipo_gasto tg
inner join plan_cuentas pc using(id_plan_cuentas) 
where tg.estado='Activo'";

if ($search == 'false') {
    
} else {
    if ($_GET['searchOper'] == 'eq') {
        $SQL .= " and $_GET[searchField] = '$_GET[searchString]' ";
    }
    if ($_GET['searchOper'] == 'cn') {
        $SQL .= " and $_GET[searchField] ilike '%$_GET[searchString]%' ";
    }
    //echo $SQL;
}
$SQL .= "ORDER BY $sidx $sord offset $start limit $limit";

$result = pg_query($SQL);
header("Content-type: text/xml;charset=utf-8");
$s = "<?xml version='1.0' encoding='utf-8'?>";
$s .= "<rows>";
$s .= "<page>" . $page . "</page>";
$s .= "<total>" . $total_pages . "</total>";
$s .= "<records>" . $count . "</records>";
while ($row = pg_fetch_row($result)) {
    $s .= "<row id='" . $row[0] . "'>";
    $s .= "<cell>" . $row[0] . "</cell>";
    $s .= "<cell>" . $row[0] . "</cell>";
    $s .= "<cell>" . $row[1] . "</cell>";
    $s .= "<cell>" . $row[4] . "</cell>";
    $s .= "<cell>" . $row[3] . "</cell>";
    $s .= "</row>";
}
$s .= "</rows>";
echo $s;
