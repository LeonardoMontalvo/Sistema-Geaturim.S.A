<?php

session_start();
include 'base.php';
conectarse();

$consulta = pg_query("select valor from retencion_iva where id_retencion_iva='$_POST[id]'");
$row = pg_fetch_row($consulta);
echo $row[0];
?>
