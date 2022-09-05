<?php
include_once __DIR__ . "/../../procesos/base.php";
$conexion = conectarse();

$sql = "select * from manejo_esquemas.esquemas where estado='Activo' order by por_defecto desc, nombre asc";
$res = pg_query($sql);
$rows = pg_fetch_all($res);
if (empty($rows)) {
    $rows = [];
}
echo json_encode($rows);
