<?php
session_start();
include __DIR__ . "/../../procesos/base.php";
$conexion = conectarse();

$sql = "select tg.id_tipo_gasto, tg.nombre,cg.nombre clasificacion from 
clasificacion_gastos_personales cg
inner join tipos_gastos_personales tg
using(id_clasificacion)
order by cg.nombre asc, tg.nombre asc";
$res = pg_query($sql);
$rows = pg_fetch_all($res);
if (empty($rows)) {
    echo json_encode([]);
} else {
    $groups = [];
    foreach ($rows as $value) {
        $groups[$value["clasificacion"]][] = $value;
    }
    echo json_encode($groups);
}
