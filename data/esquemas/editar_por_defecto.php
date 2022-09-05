<?php
include_once __DIR__ . '/../../procesos/base.php';
$conexion = conectarse();

$sql = "
update manejo_esquemas.esquemas 
set por_defecto='$_POST[por_defecto]' 
where id_esquema=$_POST[id];

update manejo_esquemas.esquemas 
set por_defecto='f' 
where id_esquema<>$_POST[id];
";
$res = pg_query($sql);
echo 1;
