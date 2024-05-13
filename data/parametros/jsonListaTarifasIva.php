<?php
session_start();
include '../../procesos/base.php';
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
$search = $_GET['_search'];
$pv = (empty($_GET['id_punto_venta']) ? 0 : $_GET['id_punto_venta']);



$total_pages = 0;
$count = 0;
function establecerTotalYRecords(&$page, &$total_pages, &$count, $condicionSqlCount = "")
{
    global $limit;

    $count_sql = "
    SELECT count(*) 
    FROM tarifa_impuesto where id_timpu=1;
    "
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

if (!$sidx)
    $sidx = 1;

$start = $limit * $page - $limit;

if ($start < 0)
    $start = 0;

$SQL = "
SELECT 
ti.id_taimpuesto, 
ti.nombre_taimpuesto,
ti.valor,
pcc.id_cuenta_iva_compras,
pcc.id_cuenta_iva_ventas,
pcc.id_cuenta_ventas,
pcc.id_cuenta_dev_ventas
FROM tarifa_impuesto ti
left join parametros_cuentas_contables_iva pcc
using(id_taimpuesto)
where id_timpu=1
";

$cond = "";
/*
if (isset($_GET["term"])) {
    $SQL .= $cond . " and(p.articulo ilike '%$_GET[term]%' or UPPER(p.codigo)='$_GET[term]' or UPPER(p.cod_barras)='$_GET[term]')";
}

if ($search == 'true') {
    $_GET["searchString"] = str_replace("'", "''", $_GET["searchString"]);
    if ($_GET['searchOper'] == 'cn') {
        $SQL .= $cond = " and $_GET[searchField] ilike '%$_GET[searchString]%'";
    }
    if ($_GET['searchOper'] == 'eq') {
        $SQL .= $cond = " and $_GET[searchField] = '$_GET[searchString]'";
    }
} */
$SQL .= " ORDER BY $sidx $sord offset $start limit $limit";
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
    $response["rows"][$i]["id"] = $rows[$i]["id_taimpuesto"];
    $response["rows"][$i]["cell"] = $rows[$i];
}
echo json_encode($response);
