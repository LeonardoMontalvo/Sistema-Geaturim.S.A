<?php
include_once __DIR__ . "/../../procesos/base.php";
$conexion = conectarse();

$texto=$_GET["term"];

$sql = "select nombre_usuario||' '||apellido_usuario nombre, id_usuario, ci_usuario from public.usuario
where id_cargo_usuario=1 and 
(nombre_usuario||' '||apellido_usuario ilike '%$texto%' or ci_usuario like '$texto%')
and id_usuario<>1";
$res=pg_query($sql);
$rows=pg_fetch_all($res);

echo json_encode($rows);
