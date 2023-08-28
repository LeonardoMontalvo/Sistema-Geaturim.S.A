<?php
session_start();
include '../../procesos/base.php';
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
$search = $_GET['_search'];

$pv = $_SESSION["PV"];



$total_pages = 0;
$count = 0;
function establecerTotalYRecords(&$page, &$total_pages, &$count, $condicionSqlCount = "")
{
    global $limit, $pv, $inicio, $fin;

    $count_sql = "SELECT COUNT(*) AS count 
    from 
    gastos_personales
    where estado='Activo'
    and id_empresa=$pv "
        . $condicionSqlCount;

    $res = pg_query($count_sql);
    $count = pg_fetch_row($res)[0];

    if ($count > 0 && $limit > 0) {
        $total_pages = ceil($count / $limit);
    } else {
        $total_pages = 0;
    }

    if ($page > $total_pages)
        $page = $total_pages;
}


$SQL = "select 
id_gastos_personales,
identificacion_comprador,
razon_social_comprador,
num_factura,
fecha_emision,
round(total,2) total,
tipo_comprobante 
from gastos_personales
where estado='Activo'
and id_empresa=$pv
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
establecerTotalYRecords($page, $total_pages, $count, $cond);
if (!$sidx)
    $sidx = 1;

$start = $limit * $page - $limit;

if ($start < 0)
    $start = 0;
$SQL .= "ORDER BY $sidx $sord offset $start limit $limit";


$res = pg_query($SQL);
$rows = pg_fetch_all($res);
if (empty($rows)) {
    $rows = [];
}

$response = [];
$response["page"] = $page;
$response["total"] = $total_pages;
$response["records"] = $count;
for ($i = 0; $i < count($rows); $i++) {
    $response["rows"][$i]["id"] = $rows[$i]["id_gastos_personales"];
    $response["rows"][$i]["cell"] = $rows[$i];
}
echo json_encode($response);
