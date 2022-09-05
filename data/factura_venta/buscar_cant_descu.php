<?php

session_start();
include '../../procesos/base.php';
conectarse();
$conpunto=1;
$consultapunto=pg_query("select max(id_punto_venta_empresa) from punto_venta_empresa where punto_venta_empresa.id_usuario='$_SESSION[id]'");
while($row=pg_fetch_row($consultapunto))
 {
  $conpunto=$row[0];
 }

$conpuntoresult=1;
$consultapuntoresult=pg_query("select id_punto_venta from punto_venta_empresa where id_punto_venta_empresa='$conpunto'");
while($row=pg_fetch_row($consultapuntoresult))
 {
  $conpuntoresult=$row[0];
 }
$consulta = pg_query("select cantidad_descuento from productos where codigo='$_POST[id]'");
$row = pg_fetch_row($consulta);

echo $row[0];
?>
