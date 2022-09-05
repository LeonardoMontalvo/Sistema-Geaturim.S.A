<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$data = 0;
$cont = 0;



if ($cont == 0) {
    pg_query("Update cargo Set estado='Pasivo' where id_cargo='$_POST[id_cargo]'");
    $data = 0;
} else {
    $data = 1;
}

echo $data;
?>