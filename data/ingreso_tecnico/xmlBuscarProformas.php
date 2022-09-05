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

$tquery = "SELECT COUNT(*) AS count 
from proforma_tecnico P , clientes C, usuario U 
where P.id_cliente=C.id_cliente 
and P.id_usuario=U.id_usuario
and P.estado='Activo'";
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

$SQL = "select P.id_proforma,
 C.identificacion,
 C.nombres_cli,
 P.total_proforma,
 P.fecha_actual,
 P.id_registro,P.id_factura,P.id_facturas_novalidas,P.estado_entrega
 from 
 proforma_tecnico P,
 clientes C, usuario U 
 where P.id_cliente = C.id_cliente 
 and P.id_usuario=U.id_usuario
 and P.estado='Activo'";

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
        $tquery .= "  and $_GET[searchField] ilike '%$_GET[searchString]%' ";
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
    if($row[6]!='')
    {
       $id_fact='1';
    }else
    {
         $id_fact='0' ;
    }
      if($row[7]!='')
    {
       $id_nota='1';
    }else
    {
         $id_nota='0' ;
    }
     if($row[8]!='')
    {
       $tipo_entre='1';
    }else
    {
         $tipo_entre='0' ;
    }
    
    $s .= "<row id='" . $row[0] . "'>";
    $s .= "<cell>" . $row[0] . "</cell>";
    $s .= "<cell>" . $row[1] . "</cell>";
    $s .= "<cell>" . $row[2] . "</cell>";
    $s .= "<cell>" . $row[3] . "</cell>";
    $s .= "<cell>" . $row[4] . "</cell>";
    $s .= "<cell>" . $row[5] . "</cell>";
      $s .= "<cell>" . $id_fact . "</cell>";
       $s .= "<cell>" . $id_nota . "</cell>";
        $s .= "<cell>" . $tipo_entre . "</cell>";
    $s .= "</row>";
}
$s .= "</rows>";
echo $s;
