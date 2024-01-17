<?php
session_start();
include '../../procesos/base.php';
conectarse();

$idusuario = $_GET["id_usuario"];

$sql = "select id_punto_venta, nombre_punto from punto_venta pv
inner join puntos_venta_usuario pvu using(id_punto_venta)
where pvu.id_usuario=$idusuario order by nombre_punto asc";
$res = pg_query($sql);
$rows = pg_fetch_all($res);
if (empty($rows)) {
    $rows = [];
}

echo json_encode($rows);
