<?php

session_start();
include '../../procesos/base.php';
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
$search = $_GET['_search'];

$idusuario = $_SESSION["id"];


if (!$sidx)
    $sidx = 1;
$result = pg_query("select count(*) from cierre_caja where fecha_cierre is null");
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

$SQL = "
select 
cc.id_cierre_caja,
cc.fecha_actual,
cc.hora_actual,
cc.monto_apertura,
cc.observacion,
u.usuario
from cierre_caja cc
inner join usuario u
using(id_usuario)
where fecha_cierre is null
and cc.estado='Activo'
and cc.id_usuario=$idusuario
ORDER BY  $sidx $sord offset $start limit $limit
";


$result = pg_query($SQL);
header("Content-type: text/xml;charset=utf-8");
$s = "<?xml version='1.0' encoding='utf-8'?>";
$s .= "<rows>";
$s .= "<page>" . $page . "</page>";
$s .= "<total>" . $total_pages . "</total>";
$s .= "<records>" . $count . "</records>";
while ($row = pg_fetch_assoc($result)) {
    $s .= "<row id='" . $row["id_cierre_caja"] . "'>";
    $s .= "<cell>" . $row["id_cierre_caja"] . "</cell>";
    $s .= "<cell>" . $row["usuario"] . "</cell>";
    $s .= "<cell>" . $row["fecha_actual"] . "</cell>";
    $s .= "<cell>" . $row["hora_actual"] . "</cell>";
    $s .= "<cell>" . $row["monto_apertura"] . "</cell>";
    $s .= "<cell>" . $row["observacion"] . "</cell>";
    $s .= "<cell>" . $row["id_cierre_caja"] . "</cell>";

    $s .= "</row>";
}
$s .= "</rows>";
echo $s;
