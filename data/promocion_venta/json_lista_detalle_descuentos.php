<?php
session_start();
include '../../procesos/base.php';
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
$search = $_GET['_search'];
$pv = $_SESSION["PV"];
$iddescuento = $_GET["id_descuento"];

$total_pages = 0;
$count = 0;
function establecerTotalYRecords(&$page, &$total_pages, &$count, $condicionSqlCount = "")
{
    global $limit, $iddescuento;

    $count_sql = "
    select
    count(*)
    from detalle_descuento dd
    inner join productos p
    on dd.id_producto=p.cod_productos
    where id_descuento=$iddescuento
    " . $condicionSqlCount;

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

if (!$sidx)
    $sidx = 1;

$start = $limit * $page - $limit;

if ($start < 0)
    $start = 0;

$SQL = "
select
dd.id_descuento, dd.id_producto,
p.cod_barras, p.articulo
from detalle_descuento dd
inner join productos p
on dd.id_producto=p.cod_productos
where id_descuento=$iddescuento
";

$cond = "";
if ($search == 'true') {
    if ($_GET['searchOper'] == 'cn') {
        $SQL .= $cond = " where $_GET[searchField] ilike '%$_GET[searchString]%'";
    }
}
$SQL .= "ORDER BY $sidx $sord offset $start limit $limit";
establecerTotalYRecords($page, $total_pages, $count, $cond);

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
    $response["rows"][$i]["id"] = $rows[$i]["id_descuento"]."_".$rows[$i]["id_producto"];
    $response["rows"][$i]["cell"] = $rows[$i];
}
echo json_encode($response);
