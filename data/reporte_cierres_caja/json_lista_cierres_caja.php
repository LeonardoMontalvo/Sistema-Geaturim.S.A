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
    global $limit;

    $count_sql = "
    select 
    	count(*)
    from cierre_caja cc
    inner join usuario u
    using(id_usuario)
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
select 
cc.id_cierre_caja,
cc.fecha_actual,
cc.hora_actual,
cc.monto_apertura,
u.usuario,
cc.fecha_cierre,
cc.hora_cierre,
cc.total_valor_ingresado,
cc.estado
from cierre_caja cc
inner join usuario u
using(id_usuario)
where cc.id_empresa=$pv
";

$cond = "";
/* if ($search == 'true') {
    if ($_GET['searchOper'] == 'cn') {
        $SQL .= $cond = " where $_GET[searchField] ilike '%$_GET[searchString]%'";
    }
} */
if (!empty($_GET["id_usuario"])) {
    $SQL .= " and id_usuario=$_GET[id_usuario]";
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
    $response["rows"][$i]["id"] = $rows[$i]["id_cierre_caja"];
    $response["rows"][$i]["cell"] = $rows[$i];
}
echo json_encode($response);
