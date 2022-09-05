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

$tquery = "
SELECT COUNT(*) AS count 
from 
registro_equipo P
left join proforma_tecnico PF on
PF.id_registro=P.id_registro
and PF.estado='Activo',
clientes C,
usuario U
where P.id_cliente = C.id_cliente 
and P.id_usuario=U.id_usuario
and P.estado='Activo'
and P.id_empresa=$conpuntoresult
and(PF.id_factura is null and PF.id_facturas_novalidas is null)";
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

$SQL = "
select 
P.id_registro,
C.identificacion,
C.nombres_cli,
P.nro_serie,
P.modelo,
P.fecha_ingreso,
C.id_cliente,
PF.id_proforma, 
P.observaciones, 
P.detalles,TE.descripcion
from 
registro_equipo P
left join proforma_tecnico PF on
PF.id_registro=P.id_registro
and PF.estado='Activo',
clientes C,
usuario U,tipo_equipo TE
where
 P.id_tipo_equipo = TE.id_tipo_equipo 
 and P.id_cliente = C.id_cliente 
and P.id_usuario=U.id_usuario
and P.estado='Activo'
and(PF.id_factura is null and PF.id_facturas_novalidas is null)
and P.id_empresa=1--$conpuntoresult
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
    $s .= "<cell>" . $row[6] . "</cell>";
    $s .= "<cell>" . $row[7] . "</cell>";
    $s .= "<cell>" . $row[8] . "</cell>";
    $s .= "<cell>" . $row[9] . "</cell>";
      $s .= "<cell>" . $row[10] . "</cell>";
    $s .= "</row>";
}
$s .= "</rows>";
echo $s;
