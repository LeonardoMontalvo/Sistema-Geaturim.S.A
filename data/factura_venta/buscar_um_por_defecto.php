<?php
session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

$id = $_GET["id_producto"];
$sql = "
select id_unidades from unidad_medida_productos
where cod_productos=$id
and por_defecto
";

$res = pg_query($sql);
$row = pg_fetch_row($res);
if (empty($row)) {
    echo json_encode(0);
} else {
    echo json_encode($row[0]);
}
