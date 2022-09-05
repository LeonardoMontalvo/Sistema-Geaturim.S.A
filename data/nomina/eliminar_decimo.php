<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$data = 0;
$cont = 0;



if ($cont == 0) {
    pg_query("Update decimos Set estado='Pasivo' where id_empleado='$_POST[id_empleado]'  and  anio='$_POST[anio]' and  mes='$_POST[select_mes]'");
    $data = 0;
} else {
    $data = 1;
}

echo $data;
?>