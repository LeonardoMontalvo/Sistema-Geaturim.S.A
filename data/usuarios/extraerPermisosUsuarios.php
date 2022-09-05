<?php

session_start();
include '../../procesos/base.php';
conectarse();

$resultado=pg_query("select permisos from usuario where id_usuario='$_POST[id_usuario]'");
$row = pg_fetch_row($resultado);
echo $row[0];
?>
