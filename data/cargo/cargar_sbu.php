<?php

session_start();
include '../../procesos/base.php';
conectarse();

$consulta = pg_query("SELECT sbu FROM cargo limit 1");
$row = pg_fetch_row($consulta);
echo $row[0];
?>
