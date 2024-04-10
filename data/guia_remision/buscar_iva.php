<?php

session_start();
include '../../procesos/base.php';
conectarse();

$consulta = pg_query("select valor from parametros where descripcion='IVA'");
$row = pg_fetch_row($consulta);
echo $row[0];
?>
