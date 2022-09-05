<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
error_reporting(0);




    // suspender receta
    pg_query("Update productos Set estado = 'Activo' where cod_productos = '$_POST[cod_productos]'");
    

$data = 1;
// Auditoria
insert_registro('ACTIVACION PRODUCTO CON ID: ' . $_POST['cod_productos']);

echo $data;
?>
