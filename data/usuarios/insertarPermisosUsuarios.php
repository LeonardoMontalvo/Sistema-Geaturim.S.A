<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();

pg_query("Update usuario Set permisos='$_POST[permisos]' Where id_usuario='$_POST[id_usuario]'");
// Auditoria
insert_registro('MODIFICACION PERMISOS: ' . $_POST['permisos'] . ' DEL USUARIO: ' . $_POST['id_usuario']);


?>
