<?php
include_once __DIR__ . "/../../procesos/base.php";
$conexion = conectarse();

$sql = "select * from manejo_esquemas.plantillas where estado='Activo' order by id_plantilla asc, nombre asc";
$res = pg_query($sql);
$rows = pg_fetch_all($res);
if (empty($rows)) {
    $rows = [];
}
echo json_encode($rows);
