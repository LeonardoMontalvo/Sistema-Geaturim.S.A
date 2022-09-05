<?php

session_start();
include '../../procesos/base.php';
conectarse();
$data = 0;

$consulta = pg_query("select * from seguridad where clave='$_POST[clave]'");
while ($row = pg_fetch_row($consulta)) {
    $data = 1;
}
echo $data;
