<?php

session_start();
include '../../procesos/base.php';
conectarse();

$consulta = pg_query("select SUM(total_venta::FLOAT) from detalle_factura_venta where  id_factura_venta ='$_POST[id]' and bien_servicio='B'");
$row = pg_fetch_row($consulta);
echo $row[0];
?>
