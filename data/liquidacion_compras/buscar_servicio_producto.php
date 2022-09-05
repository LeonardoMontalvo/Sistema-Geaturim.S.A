<?php

session_start();
include '../../procesos/base.php';
conectarse();

$consulta = pg_query("select SUM(total_venta::FLOAT) from detalle_liquidacion_compra where  id_liquidacion_compra ='$_POST[id]' and bien_servicio='S'");
$row = pg_fetch_row($consulta);
echo $row[0];
?>
