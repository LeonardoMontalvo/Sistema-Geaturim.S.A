<?php

session_start();
include 'base.php';
conectarse();

$consulta = pg_query("select valor_r from retencion_iva_r where id_retencion_iva_r='$_POST[id]'");
$row = pg_fetch_row($consulta);
echo $row[0];
?>
