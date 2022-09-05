<?php

session_start();
include '../../procesos/base.php';
conectarse();

$consulta = pg_query("select valor from retencion_fuentes where id_retencion_fuentes='$_POST[id]'");
$row = pg_fetch_row($consulta);
echo $row[0];
?>
