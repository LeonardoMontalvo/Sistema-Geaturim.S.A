<?php
include_once __DIR__ . '/../../procesos/base.php';
$conexion = conectarse();

$sql = "
update manejo_esquemas.esquemas 
set color='$_POST[color]' 
where id_esquema=$_POST[id];
";
$res = pg_query($sql);
echo 1;
