<?php
session_start();
include '../../procesos/base.php';
$conexion = conectarse();

$consulta1 = pg_query("select nombre_taimpuesto from tarifa_impuesto where id_taimpuesto=2 and id_timpu= 1 order by id_taimpuesto desc");
$res = pg_query($consulta1);
$rows = pg_fetch_all($consulta1);

if (empty($rows)) {
    $rows = [];
}

echo json_encode($rows);
