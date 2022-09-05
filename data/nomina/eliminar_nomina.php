<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$data = 0;
$cont = 0;



if ($cont == 0) {
    pg_query("Update empleado Set estado='Pasivo' where id_empleado='$_POST[id_nomina]'");
    $data = 0;
} else {
    $data = 1;
}

echo $data;
?>