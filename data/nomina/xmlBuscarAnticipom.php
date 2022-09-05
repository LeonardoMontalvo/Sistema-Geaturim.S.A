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
$result = pg_query("SELECT COUNT(*) AS count FROM multas where mes='$_GET[id_clasem]'");
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

$SQL = "SELECT empleado.id_empleado,empleado.id_empleado,fecha_registro,nombre_multa,valor,nombres_empleado,valor,id_multa,total FROM multas, empleado where multas.id_empleado=empleado.id_empleado and multas.mes='$_GET[id_clasem]' and multas.id_empleado='$_GET[id_empleadom]' and multas.anio='$_GET[anio]' and multas.estado='Activo'";


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
    $s .= "<cell>" . $row[3] . "</cell>";
    $s .= "<cell>" . $row[4] . "</cell>";
    $s .= "<cell>" . $row[8] . "</cell>";
    $s .= "<cell>" . $row[6] . "</cell>";



    $s .= "</row>";
}
$s .= "</rows>";
echo $s;
?>