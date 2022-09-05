<?php

include '../../procesos/base.php';
require_once __DIR__ . "/UtilJsonFile.php";

$filename = "cuentas.json";

$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
$search = $_GET['_search'];
$count = 0;

$cuentas = [];
UtilJsonFile::cargarJson($cuentas, $filename);

$efectivo = "";

if (!empty($cuentas["efectivo"])) {
    $efectivo = implode("," , $cuentas["efectivo"]);
}

$sqltotal = "
select count(*) from plan_cuentas
where id_plan_cuentas in($efectivo)
";

$SQL = "
select * from plan_cuentas
where id_plan_cuentas in($efectivo)
";

calcularTotalPaginasRegistros($sqltotal);
$SQL .= " ORDER BY $sidx $sord offset $start limit $limit";
$res = pg_query($SQL);
$rows = pg_fetch_all($res);
$response = [];
$response["page"] = $page;
$response["total"] = $total_pages;
$response["records"] = $count;
for ($i = 0; $i < count($rows); $i++) {
    $response["rows"][$i]["id"] = $rows[$i]["id_plan_cuentas"];
    $response["rows"][$i]["cell"] = $rows[$i];
}

echo  json_encode($response);

function calcularTotalPaginasRegistros($sqltotal)
{
    global $count, $total_pages, $limit, $page, $start;
    $result = pg_query($sqltotal);
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
}
