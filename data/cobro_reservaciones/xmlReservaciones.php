<?php

include '../../procesos/base.php';
error_reporting(0);
$page = $_GET['page'];
$limit = $_GET['rows'];


$result = pg_query("SELECT COUNT(*) AS count FROM reservaciones");


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

$SQL = "select id_reservacion, identificacion, nombres_cli, total, fecha_actual, saldo from reservaciones,clientes,usuario,empresa where reservaciones.id_cliente=clientes.id_cliente and reservaciones.id_usuario=usuario.id_usuario and reservaciones.estado = 'Activo' and reservaciones.id_cliente='$_GET[id_cliente]' offset $start limit $limit";
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
        $s .= "<cell>" . $row[4] . "</cell>";
        $s .= "<cell>" . round($row[3],2) . "</cell>";
        $s .= "<cell>" . round($row[5],2) . "</cell>";
        $s .= "</row>";
    }
    $s .= "</rows>";
echo $s;
?>
