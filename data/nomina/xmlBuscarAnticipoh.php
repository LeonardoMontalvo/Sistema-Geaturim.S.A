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
$result = pg_query("SELECT COUNT(*) AS count FROM horas_extras where mes='$_GET[id_claseh]'");
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

////////////////todo

$SQL = "SELECT horas_extras.id_horas_extras,empleado.id_empleado,fecha_registro,periodo,hora_extra,porsentaje_seleccionado,valor FROM horas_extras, empleado where horas_extras.id_empleado=empleado.id_empleado and horas_extras.mes='$_GET[id_claseh]' and horas_extras.id_empleado='$_GET[id_empleadoh]' and horas_extras.anio='$_GET[anio]' and horas_extras.estado='Activo'";


$result = pg_query($SQL);
header("Content-type: text/xml;charset=utf-8");
$s = "<?xml version='1.0' encoding='utf-8'?>";
$s .= "<rows>";
$s .= "<page>" . $page . "</page>";
$s .= "<total>" . $total_pages . "</total>";
$s .= "<records>" . $count . "</records>";
while ($row = pg_fetch_row($result)) {


    $s .= "<row id='" . $row[0] . "'>";
    $s .= "<cell></cell>";

    $s .= "<cell>" . $row[0] . "</cell>";
    $s .= "<cell>" . $row[1] . "</cell>";
    $s .= "<cell>" . $row[2] . "</cell>";
     $s .= "<cell></cell>";
    $s .= "<cell>" . $row[3] . "</cell>";
    $s .= "<cell>" . $row[4] . "</cell>";
    $s .= "<cell>" . $row[5] . "</cell>";
    $s .= "<cell>" . $row[6] . "</cell>";



    $s .= "</row>";
}
$s .= "</rows>";
echo $s;
?>