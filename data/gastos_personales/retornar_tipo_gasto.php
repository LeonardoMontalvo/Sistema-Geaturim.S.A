<?php
session_start();
include '../../procesos/base.php';
$conexion = conectarse();

$sql = "select id_clasificacion, nombre from clasificacion_gastos_personales   order by nombre asc";
$res = pg_query($sql);
$rows = pg_fetch_all($res);
if (empty($rows)) {
    $rows = [];
}

echo json_encode($rows);
