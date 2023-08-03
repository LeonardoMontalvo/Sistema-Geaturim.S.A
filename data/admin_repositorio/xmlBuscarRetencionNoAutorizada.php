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


$sqlres = "SELECT COUNT(*) AS count from factura_venta FV, clientes C 
where C.id_cliente = FV.id_cliente 
and  FV.estado_fac::numeric<>1 
and FV.estado_fac::numeric<>2 and FV.estado='Activo'";

if (!empty($_GET['f1']) && !empty($_GET['f2'])) {
  $sqlres .= " and FV.fecha_actual between '$_GET[f1]' and '$_GET[f2]'";
}
if (!empty($_GET["id"])) {
  $sqlres .= " and C.id_cliente='$_GET[id]'";
}
$result = pg_query($sqlres);
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

$SQL = "SELECT FV.id_factura_venta, FV.fecha_actual, C.nombres_cli, FV.num_autorizacion, FV.total_venta::float, FV.estado_fac,
FV.num_factura FROM factura_venta FV, clientes C where C.id_cliente = FV.id_cliente  
and  FV.estado_fac::numeric<>1 and FV.estado_fac::numeric<>2 and FV.estado='Activo'";
/////por fecha
if (!empty($_GET['f1']) && !empty($_GET['f2'])) {
  $SQL .= " and FV.fecha_actual between '$_GET[f1]' and '$_GET[f2]'";
}
////por cliente
if (!empty($_GET['id'])) {
  $SQL .= " and C.id_cliente='$_GET[id]'";
}
$SQL .= " ORDER BY  $sidx $sord offset $start limit $limit";

/////solo por serie
/*if ($_GET['s1'] != "" && $_GET['s2'] != "") {

  $SQL = "SELECT FV.id_factura_venta, FV.fecha_actual, C.nombres_cli, FV.num_autorizacion, FV.total_venta::float, FV.estado_fac FROM factura_venta FV, clientes C where C.id_cliente = FV.id_cliente  and  FV.estado_fac::numeric<>1 and FV.estado_fac::numeric<>2 and FV.num_factura between '$_GET[s1]' and '$_GET[s2]'   ORDER BY  $sidx $sord offset $start limit $limit";
}*/

$result = pg_query($SQL);
header("Content-type: text/xml;charset=utf-8");
$s = "<?xml version='1.0' encoding='utf-8'?>";
$s .= "<rows>";
$s .= "<page>" . $page . "</page>";
$s .= "<total>" . $total_pages . "</total>";
$s .= "<records>" . $count . "</records>";
while ($row = pg_fetch_row($result)) {
  $nombre_estado = $row[5];
  if ($nombre_estado == 5) {
    $row[5] = "ERROR.P12";
  }
  if ($nombre_estado == 6) {
    $row[5] = "CONTRA.INCO.P12";
  }

  if ($nombre_estado == 2) {

    $row[5] = "AUTORIZADO";
  }
  if ($nombre_estado == 7) {
    $row[5] = "NO AUTORIZADO";
  }
  if ($nombre_estado == 1) {
    $row[5] = "AUTORIZADO ENVIADO";
  }
  if ($nombre_estado == 8) {
    $row[5] = "SIN RESPUESTA DEL SRI";
  }
  if ($nombre_estado == 3) {
    $row[5] = "ERROR CORREO";
  }
  if ($nombre_estado == 0) {
    $row[5] = "NO AUTORIZADO";
  }

  $s .= "<row id='" . $row[0] . "'>";
  $s .= "<cell>" . $row[0] . "</cell>";
  $s .= "<cell>" . $row[1] . "</cell>";
  $s .= "<cell>" . $row[6] . "</cell>";
  $s .= "<cell>" . $row[2] . "</cell>";
  $s .= "<cell>" . $row[3] . "</cell>";
  $s .= "<cell>" . $row[4] . "</cell>";
  $s .= "<cell>" . $row[5] . "</cell>";
  $s .= "<cell></cell>";
  $s .= "<cell></cell>";
  $s .= "</row>";
}
$s .= "</rows>";
echo $s;
