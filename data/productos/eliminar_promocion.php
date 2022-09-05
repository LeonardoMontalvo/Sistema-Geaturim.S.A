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
    pg_query("Update promociones Set estado='Pasivo' where id_promociones ='$_POST[id_promociones_modulo]'");
       
    $data = 0;
     // Auditoria
    insert_registro('ELIMINACION : ' . $_POST['id_promociones_modulo']);
} else {
    $data = 1;
}

echo $data;
?>