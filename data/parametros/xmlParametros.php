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
$result = pg_query("SELECT COUNT(*) AS count FROM parametros");
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
if ($search == 'false') {
    $SQL = "SELECT id_parametro, p.descripcion, valor, 
concat(deb.codigo_plan, '--', deb.descripcion) as cuenta_debito, 
concat(cred.codigo_plan, '--', cred.descripcion) as cuenta_credito 
from parametros p left join plan_cuentas deb on p.cuenta_debito::int=deb.id_plan_cuentas
left join plan_cuentas cred on p.cuenta_credito::int=cred.id_plan_cuentas   ORDER BY $sidx $sord offset $start limit $limit";
} else {
    if ($_GET['searchOper'] == 'eq') {
        $SQL = "SELECT id_parametro, p.descripcion, valor, 
concat(deb.codigo_plan, '--', deb.descripcion) as cuenta_debito, 
concat(cred.codigo_plan, '--', cred.descripcion) as cuenta_credito 
from parametros p left join plan_cuentas deb on p.cuenta_debito::int=deb.id_plan_cuentas
left join plan_cuentas cred on p.cuenta_credito::int=cred.id_plan_cuentas where $_GET[searchField] = '$_GET[searchString]'  ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'ne') {
        $SQL = "SELECT id_parametro, p.descripcion, valor, 
concat(deb.codigo_plan, '--', deb.descripcion) as cuenta_debito, 
concat(cred.codigo_plan, '--', cred.descripcion) as cuenta_credito 
from parametros p left join plan_cuentas deb on p.cuenta_debito::int=deb.id_plan_cuentas
left join plan_cuentas cred on p.cuenta_credito::int=cred.id_plan_cuentas where $_GET[searchField] != '$_GET[searchString]'  ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'bw') {
        $SQL = "SELECT id_parametro, p.descripcion, valor, 
concat(deb.codigo_plan, '--', deb.descripcion) as cuenta_debito, 
concat(cred.codigo_plan, '--', cred.descripcion) as cuenta_credito 
from parametros p left join plan_cuentas deb on p.cuenta_debito::int=deb.id_plan_cuentas
left join plan_cuentas cred on p.cuenta_credito::int=cred.id_plan_cuentas where $_GET[searchField] like '$_GET[searchString]%'  ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'bn') {
        $SQL = "SELECT id_parametro, p.descripcion, valor, 
concat(deb.codigo_plan, '--', deb.descripcion) as cuenta_debito, 
concat(cred.codigo_plan, '--', cred.descripcion) as cuenta_credito 
from parametros p left join plan_cuentas deb on p.cuenta_debito::int=deb.id_plan_cuentas
left join plan_cuentas cred on p.cuenta_credito::int=cred.id_plan_cuentas where $_GET[searchField] not like '$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'ew') {
        $SQL = "SELECT id_parametro, p.descripcion, valor, 
concat(deb.codigo_plan, '--', deb.descripcion) as cuenta_debito, 
concat(cred.codigo_plan, '--', cred.descripcion) as cuenta_credito 
from parametros p left join plan_cuentas deb on p.cuenta_debito::int=deb.id_plan_cuentas
left join plan_cuentas cred on p.cuenta_credito::int=cred.id_plan_cuentas where $_GET[searchField] like '%$_GET[searchString]'   ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'en') {
        $SQL = "SELECT id_parametro, p.descripcion, valor, 
concat(deb.codigo_plan, '--', deb.descripcion) as cuenta_debito, 
concat(cred.codigo_plan, '--', cred.descripcion) as cuenta_credito 
from parametros p left join plan_cuentas deb on p.cuenta_debito::int=deb.id_plan_cuentas
left join plan_cuentas cred on p.cuenta_credito::int=cred.id_plan_cuentas where $_GET[searchField] not like '%$_GET[searchString]'   ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'cn') {
        $SQL = "SELECT id_parametro, p.descripcion, valor, 
concat(deb.codigo_plan, '--', deb.descripcion) as cuenta_debito, 
concat(cred.codigo_plan, '--', cred.descripcion) as cuenta_credito 
from parametros p left join plan_cuentas deb on p.cuenta_debito::int=deb.id_plan_cuentas
left join plan_cuentas cred on p.cuenta_credito::int=cred.id_plan_cuentas where $_GET[searchField] like '%$_GET[searchString]%'   ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'nc') {
        $SQL = "SELECT id_parametro, p.descripcion, valor, 
concat(deb.codigo_plan, '--', deb.descripcion) as cuenta_debito, 
concat(cred.codigo_plan, '--', cred.descripcion) as cuenta_credito 
from parametros p left join plan_cuentas deb on p.cuenta_debito::int=deb.id_plan_cuentas
left join plan_cuentas cred on p.cuenta_credito::int=cred.id_plan_cuentas where $_GET[searchField] not like '%$_GET[searchString]%'  ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'in') {
        $SQL = "SELECT id_parametro, p.descripcion, valor, 
concat(deb.codigo_plan, '--', deb.descripcion) as cuenta_debito, 
concat(cred.codigo_plan, '--', cred.descripcion) as cuenta_credito 
from parametros p left join plan_cuentas deb on p.cuenta_debito::int=deb.id_plan_cuentas
left join plan_cuentas cred on p.cuenta_credito::int=cred.id_plan_cuentas where $_GET[searchField] like '%$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'ni') {
        $SQL = "SELECT id_parametro, p.descripcion, valor, 
concat(deb.codigo_plan, '--', deb.descripcion) as cuenta_debito, 
concat(cred.codigo_plan, '--', cred.descripcion) as cuenta_credito 
from parametros p left join plan_cuentas deb on p.cuenta_debito::int=deb.id_plan_cuentas
left join plan_cuentas cred on p.cuenta_credito::int=cred.id_plan_cuentas where $_GET[searchField] not like '%$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
    }
    //echo $SQL;
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
    $s .= "</row>";
}
$s .= "</rows>";
echo $s;
