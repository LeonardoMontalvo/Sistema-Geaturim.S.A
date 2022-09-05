<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

    // suspender receta
    pg_query("Update recetas Set estado = 'Activo' where id_receta = '$_POST[comprobante]'");
    

$data = 1;

echo $data;
?>
