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
$result = pg_query("SELECT COUNT(*) AS count from cierre_caja");
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
    $SQL = "
    select id_cierre_caja,comprobante, 
    usuario,cierre_caja.fecha_cierre, cierre_caja.hora_cierre, 
    total_valor_ingresado,totales_dierio_caja, observacion_cierre
    FROM cierre_caja
    inner join usuario
    on cierre_caja.id_usuario=usuario.id_usuario
    where 
    cierre_caja.estado='Activo'
    and cierre_caja.fecha_cierre is not null 
    and cierre_caja.id_usuario=$idusuario
    order by fecha_cierre desc, hora_cierre desc;";
} else {
}
$result = pg_query($SQL);
header("Content-type: text/xml;charset=utf-8");
$s = "<?xml version='1.0' encoding='utf-8'?>";
$s .= "<rows>";
$s .= "<page>" . $page . "</page>";
$s .= "<total>" . $total_pages . "</total>";
$s .= "<records>" . $count . "</records>";
while ($row = pg_fetch_assoc($result)) {
    $s .= "<row id='" . $row['id_cierre_caja'] . "'>";
    $s .= "<cell>" . $row['id_cierre_caja'] . "</cell>";
    $s .= "<cell>" . $row['comprobante'] . "</cell>";
    $s .= "<cell>" . $row['usuario'] . "</cell>";
    $s .= "<cell>" . $row['fecha_cierre'] . "</cell>";
    $s .= "<cell>" . $row['hora_cierre'] . "</cell>";
    $s .= "<cell>" . $row['total_valor_ingresado'] . "</cell>";
    $s .= "<cell>" . $row['totales_dierio_caja'] . "</cell>";

    $s .= "<cell>" . $row['observacion_cierre'] . "</cell>";
    $s .= "</row>";
}
$s .= "</rows>";
echo $s;
