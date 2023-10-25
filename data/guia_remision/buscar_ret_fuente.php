<?php

session_start();
include '../../procesos/base.php';
conectarse();

$consulta = pg_query("select valor_r from retencion_fuentes_r where id_retencion_fuentes_r='$_POST[id]'");
$row = pg_fetch_row($consulta);
echo $row[0];
?>
