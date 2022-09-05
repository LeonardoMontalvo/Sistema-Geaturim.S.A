<?php

session_start();
include '../../procesos/base.php';
conectarse();

$consulta = pg_query("select cantidad_descuento from productos where codigo='$_POST[id]'");
$row = pg_fetch_row($consulta);

echo $row[0];
?>
