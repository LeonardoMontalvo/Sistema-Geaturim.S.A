<?php
session_start();
include '../../procesos/base.php';
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
$search = $_GET['_search'];

$pv = $_SESSION["PV"];
$inicio = $_GET["inicio"];
$fin = $_GET["fin"];


$tabla = "factura_venta";
$idtabla = "id_factura_venta";

$tipodoc=$_GET["tipo_doc"];

if ($_GET["tipo_doc"] == "nota_venta") {
    $tabla = "facturas_novalidas";
    $idtabla = "id_facturas_novalidas";
}


$total_pages = 0;
$count = 0;
function establecerTotalYRecords(&$page, &$total_pages, &$count, $condicionSqlCount = "")
{
    global $limit, $pv, $inicio, $fin, $tabla;

    $count_sql = "SELECT COUNT(*) AS count 
    from 
    $tabla ro
    inner join clientes c
    on ro.id_cliente=c.id_cliente
    inner join usuario u
    on u.id_usuario=ro.id_usuario
    where ro.id_empresa = $pv
    and ro.estado='Activo'
    and ro.fecha_actual between '$inicio 00:00:00' and '$fin 23:59:59'"
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
ro.$idtabla id_documento, 
ro.comprobante,
c.nombres_cli, 
c.identificacion, 
u.usuario, 
ro.fecha_actual, 
ro.total_venta total, 
'".mb_strtoupper($tipodoc)."' tipo_documento, 
'' 
from $tabla ro 
inner join clientes c on ro.id_cliente=c.id_cliente 
inner join usuario u on u.id_usuario=ro.id_usuario 
where ro.id_empresa = $pv
and ro.estado='Activo'
and ro.fecha_actual between '$inicio 00:00:00' and '$fin 23:59:59'
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
    $response["rows"][$i]["id"] = $rows[$i]["id_documento"];
    $response["rows"][$i]["cell"] = $rows[$i];
}
echo json_encode($response);
