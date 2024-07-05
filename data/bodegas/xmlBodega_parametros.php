<?php

session_start();
include '../../procesos/base.php';
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
$search = $_GET['_search'];

$SQL = "select 
id_punto_venta,
nombre_punto
from punto_venta 
left join parametros_punto_venta ppv using(id_punto_venta)";
$cond = "";

if ($search != 'false') {
    if ($_GET['searchOper'] == 'eq') {
        $cond = $SQL .= " where $_GET[searchField] = '$_GET[searchString]'";
    }
    if ($_GET['searchOper'] == 'ne') {
        $cond = $SQL .= " where $_GET[searchField] != '$_GET[searchString]'";
    }
    if ($_GET['searchOper'] == 'bw') {
        $cond = $SQL .= " where $_GET[searchField] like '$_GET[searchString]%'";
    }
    if ($_GET['searchOper'] == 'bn') {
        $cond = $SQL .= " where $_GET[searchField] not like '$_GET[searchString]%'";
    }
    if ($_GET['searchOper'] == 'ew') {
        $cond = $SQL .= " where $_GET[searchField] like '%$_GET[searchString]'";
    }
    if ($_GET['searchOper'] == 'en') {
        $cond = $SQL .= " where $_GET[searchField] not like '%$_GET[searchString]'";
    }
    if ($_GET['searchOper'] == 'cn') {
        $cond = $SQL .= " where $_GET[searchField] like '%$_GET[searchString]%'";
    }
    if ($_GET['searchOper'] == 'nc') {
        $cond = $SQL .= " where $_GET[searchField] not like '%$_GET[searchString]%'";
    }
    if ($_GET['searchOper'] == 'in') {
        $cond = $SQL .= " where $_GET[searchField] like '%$_GET[searchString]%'";
    }
    if ($_GET['searchOper'] == 'ni') {
        $cond = $SQL .= " where $_GET[searchField] not like '%$_GET[searchString]%'";
    }
    //echo $SQL;
}

if (!$sidx)
    $sidx = 1;
$result = pg_query("SELECT COUNT(*) AS count FROM punto_venta $cond");
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

$SQL .= " ORDER BY $sidx $sord offset $start limit $limit";
$result = pg_query($SQL);
header("Content-type: text/xml;charset=utf-8");
$s = "<?xml version='1.0' encoding='utf-8'?>";
$s .= "<rows>";
$s .= "<page>" . $page . "</page>";
$s .= "<total>" . $total_pages . "</total>";
$s .= "<records>" . $count . "</records>";

while ($row = pg_fetch_assoc($result)) {
    $s .= "<row id='" . $row["id_punto_venta"] . "'>";
    $s .= "<cell>" . $row["id_punto_venta"] . "</cell>";
    $s .= "<cell>" . $row["nombre_punto"] . "</cell>";
    $s .= "<cell>" . $row["id_punto_venta"] . "</cell>";
    $s .= "</row>";
}
$s .= "</rows>";
echo $s;
