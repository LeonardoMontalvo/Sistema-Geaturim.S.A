<?php

session_start();
include '../../procesos/base.php';
conectarse();

$consulta = pg_query("SELECT valor
  FROM parametros_iess where id_parametro_iess='1'
");
$row = pg_fetch_row($consulta);
echo $row[0];
?>
