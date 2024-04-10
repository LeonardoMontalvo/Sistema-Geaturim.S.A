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

if (!$sidx)
    $sidx = 1;

$tquery = "
select
count(*)
from registro_equipo r,
proforma_tecnico pt,
clientes c
where r.id_registro=pt.id_registro
and r.id_cliente=c.id_cliente
and r.estado='Activo'
and r.id_empresa=$conpuntoresult
and(pt.id_factura is null and pt.id_facturas_novalidas is null)
";

$result = pg_query($tquery);
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

$SQL = "select
r.id_registro,
pt.id_proforma,
c.identificacion,
c.nombres_cli,
pt.total_proforma,
r.fecha_ingreso
from registro_equipo r,
proforma_tecnico pt,
clientes c
where r.id_registro=pt.id_registro
and r.id_cliente=c.id_cliente
and r.estado='Activo'
and r.id_empresa=$conpuntoresult
and(pt.id_factura is null and pt.id_facturas_novalidas is null)
";

if ($search == 'false') {
    $SQL .= " ORDER BY $sidx $sord offset $start limit $limit";
} else {
    if ($_GET['searchOper'] == 'eq') {
        $tquery .= " and $_GET[searchField] = '$_GET[searchString]'";
        $res = pg_query($tquery);
        while ($row = pg_fetch_row($res)) {
            $count = $row[0];
            $total_pages = ceil($count / $limit);
        }

        $SQL .= " and $_GET[searchField] = '$_GET[searchString]' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'cn') {
        $tquery .= " and $_GET[searchField] ilike '%$_GET[searchString]%'";
        $res = pg_query($tquery);
        while ($row = pg_fetch_row($res)) {
            $count = $row[0];
            $total_pages = ceil($count / $limit);
        }

        $SQL .= " and $_GET[searchField] ilike '%$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
    }
}

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
        $s .= "<cell>" . $row[1] . "</cell>";
        $s .= "<cell>" . $row[2] . "</cell>";
        $s .= "<cell>" . $row[3] . "</cell>";
        $s .= "<cell>" . $row[4] . "</cell>";
        $s .= "<cell>" . $row[5] . "</cell>";
        $s .= "</row>";
    }
    $s .= "</rows>";
echo $s;