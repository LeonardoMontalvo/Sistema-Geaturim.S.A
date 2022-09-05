<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
error_reporting(0);
$data = 0;
$cont = 0;






if ($cont == 0) {
    pg_query("Update rutas Set estado='Pasivo' where id_ruta ='$_POST[id_ruta]'");
       
    $data = 0;
     insert_registro('ELIMINACION RUTA CON ID: ' . $_POST['id_ruta']);
} else {
    $data = 1;
}

echo $data;
?>