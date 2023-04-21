<?php

session_start();
include '../../procesos/base.php';
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
$search = $_GET['_search'];

$conpunto = 1;
$consultapunto = pg_query("select max(id_punto_venta_empresa) from punto_venta_empresa where punto_venta_empresa.id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($consultapunto)) {
    $conpunto = $row[0];
}

$conpuntoresult = 1;
$consultapuntoresult = pg_query("select id_punto_venta from punto_venta_empresa where id_punto_venta_empresa='$conpunto'");
while ($row = pg_fetch_row($consultapuntoresult)) {
    $conpuntoresult = $row[0];
}

$total_pages = 0;
$count = 0;
function establecerTotalYRecords(&$total_pages, &$count, $condicionSqlCount = "")
{
    global $limit, $conpuntoresult;

    $count_sql = "SELECT COUNT(*) AS count 
    from ingresos where id_empresa='$conpuntoresult'
    and estado='Activo'"
        . $condicionSqlCount;

    $res = pg_query($count_sql);
    $count = pg_fetch_row($res)[0];

    if ($count > 0 && $limit > 0) {
        $total_pages = ceil($count / $limit);
    } else {
        $total_pages = 0;
    }
}

if (!$sidx)
    $sidx = 1;

if ($page > $total_pages)
    $page = $total_pages;
$start = $limit * $page - $limit;

if ($start < 0)
    $start = 0;

$SQL = "
select 
I.id_ingresos, 
pvo.nombre_punto, 
pvd.nombre_punto,
U.nombre_usuario, 
U.apellido_usuario,
I.fecha_actual
from ingresos I
left join punto_venta pvo
on pvo.id_punto_venta=I.origen
left join punto_venta pvd
on pvd.id_punto_venta=I.destino, 
usuario U
where  I.id_usuario=U.id_usuario  
and I.id_empresa=$conpuntoresult
and I.estado='Activo'
";
$cond = "";

if ($search == 'true') {
    if ($_GET['searchOper'] == 'eq') {
        $SQL .= $cond = " and $_GET[searchField] = '$_GET[searchString]'";
    }
    if ($_GET['searchOper'] == 'ne') {
        $SQL .= $cond = " and $_GET[searchField] != '$_GET[searchString]'";
    }
    if ($_GET['searchOper'] == 'bw') {
        $SQL .= $cond = " and $_GET[searchField] like '$_GET[searchString]%'";
    }
    if ($_GET['searchOper'] == 'bn') {
        $SQL .= $cond = " and $_GET[searchField] not like '$_GET[searchString]%'";
    }
    if ($_GET['searchOper'] == 'ew') {
        $SQL .= $cond = " and $_GET[searchField] like '%$_GET[searchString]'";
    }
    if ($_GET['searchOper'] == 'en') {
        $SQL .= $cond = " and $_GET[searchField] not like '%$_GET[searchString]'";
    }
    if ($_GET['searchOper'] == 'cn') {
        $SQL .= $cond = " and $_GET[searchField] like '%$_GET[searchString]%'";
    }
    if ($_GET['searchOper'] == 'nc') {
        $SQL .= $cond = " and $_GET[searchField] not like '%$_GET[searchString]%'";
    }
    if ($_GET['searchOper'] == 'in') {
        $SQL .= $cond = " and $_GET[searchField] like '%$_GET[searchString]%'";
    }
    if ($_GET['searchOper'] == 'ni') {
        $SQL .= $cond = " and $_GET[searchField] not like '%$_GET[searchString]%'";
    }
}
$SQL .= "ORDER BY $sidx $sord offset $start limit $limit";
establecerTotalYRecords($total_pages, $count, $cond);

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
    $s .= "<cell>" . $row[5] . "</cell>";
    $s .= "<cell>" . $row[1] . "</cell>";
    $s .= "<cell>" . $row[2] . "</cell>";
    $s .= "<cell>" . $row[3] . "</cell>";
    $s .= "<cell>" . $row[4] . "</cell>";
    $s .= "</row>";
}
$s .= "</rows>";
echo $s;
