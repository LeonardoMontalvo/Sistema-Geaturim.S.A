<?php
include_once __DIR__ . '/../../procesos/base.php';
$conexion = conectarse();

$sql = "
update acciones_usuario 
set apertura_caja='$_POST[por_defecto]' 
where id_usuario=$_POST[id];


";
$res = pg_query($sql);
echo 1;
