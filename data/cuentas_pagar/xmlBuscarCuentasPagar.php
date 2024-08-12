<?php

session_start();
include '../../procesos/base.php';
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
$search = $_GET['_search'];
error_reporting(0);
//$SQL = "SELECT PA.id_cuentas_pagar, P.identificacion_pro, P.empresa_pro, PA.num_factura, PA.valor_pagado, PA.fecha_factura FROM pagos_pagar PA, proveedores P, usuario U where PA.id_proveedor = P.id_proveedor and PA.id_usuario = U.id_usuario";
$SQL = "
    select
    pp.comprobante,
    p.identificacion_pro,
    p.empresa_pro,
    sum(pp.valor_pagado)valor_pagado,
    pp.fecha_actual,
    pp.hora_actual,
    u.nombre_usuario,
    u.apellido_usuario,
    pp.id_proveedor,
    p.tipo_documento
    from pagos_pagar pp
    inner join proveedores p using(id_proveedor) 
    inner join usuario u using(id_usuario)
    where pp.estado<>'Anulado'
    ";
$SQLCNT = "
    select count(*)
    from pagos_pagar pp
    where pp.estado<>'Anulado'
    ";
if ($search == 'false') {
    //$SQL = " ";
} else {
    if ($_GET['searchOper'] == 'eq') {
        $SQL .= " and $_GET[searchField] = '$_GET[searchString]'";
        $SQLCNT .= " and $_GET[searchField] = '$_GET[searchString]'";
    }
    if ($_GET['searchOper'] == 'ne') {
        $SQL .= " and $_GET[searchField] != '$_GET[searchString]'";
        $SQLCNT .= " and $_GET[searchField] != '$_GET[searchString]'";
    }
    if ($_GET['searchOper'] == 'bw') {
        $SQL .= " and $_GET[searchField] like '$_GET[searchString]%'";
        $SQLCNT .= " and $_GET[searchField] like '$_GET[searchString]%'";
    }
    if ($_GET['searchOper'] == 'bn') {
        $SQL .= " and $_GET[searchField] not like '$_GET[searchString]%'";
        $SQLCNT .= " and $_GET[searchField] not like '$_GET[searchString]%'";
    }
    if ($_GET['searchOper'] == 'ew') {
        $SQL .= " and $_GET[searchField] like '%$_GET[searchString]'";
        $SQLCNT .= " and $_GET[searchField] like '%$_GET[searchString]'";
    }
    if ($_GET['searchOper'] == 'en') {
        $SQL .= " and $_GET[searchField] not like '%$_GET[searchString]'";
        $SQLCNT .= " and $_GET[searchField] not like '%$_GET[searchString]'";
    }
    if ($_GET['searchOper'] == 'cn') {
        $SQL .= " and $_GET[searchField] like '%$_GET[searchString]%'";
        $SQLCNT .= " and $_GET[searchField] like '%$_GET[searchString]%'";
    }
    if ($_GET['searchOper'] == 'nc') {
        $SQL .= " and $_GET[searchField] not like '%$_GET[searchString]%'";
        $SQLCNT .= " and $_GET[searchField] not like '%$_GET[searchString]%'";
    }
    if ($_GET['searchOper'] == 'in') {
        $SQL .= " and $_GET[searchField] like '%$_GET[searchString]%'";
        $SQLCNT .= " and $_GET[searchField] like '%$_GET[searchString]%'";
    }
    if ($_GET['searchOper'] == 'ni') {
        $SQL .= " and $_GET[searchField] not like '%$_GET[searchString]%'";
        $SQLCNT .= " and $_GET[searchField] not like '%$_GET[searchString]%'";
    }
    //echo $SQL;
}

$start = $limit * $page - $limit;
if ($start < 0)
    $start = 0;

$SQL .= " group by pp.comprobante,
pp.id_proveedor,
p.identificacion_pro,
p.empresa_pro,
pp.fecha_actual,
pp.hora_actual,
u.nombre_usuario,
u.apellido_usuario,
p.tipo_documento ORDER BY $sidx $sord offset $start limit $limit";
$SQLCNT .= "group by pp.comprobante";

$result = pg_query($SQL);

if (!$sidx)
    $sidx = 1;
$resultcnt = pg_query($SQLCNT);
$row = pg_fetch_row($resultcnt);
$count = $row[0];
if ($count > 0 && $limit > 0) {
    $total_pages = ceil($count / $limit);
} else {
    $total_pages = 0;
}

if ($page > $total_pages)
    $page = $total_pages;


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
    $s .= "<cell>" . $row[6] . "</cell>";
    $s .= "<cell>" . $row[7] . "</cell>";
    $s .= "<cell>" . $row[8] . "</cell>";
    $s .= "<cell>" . $row[9] . "</cell>";
    $s .= "</row>";
}
$s .= "</rows>";
echo $s;
