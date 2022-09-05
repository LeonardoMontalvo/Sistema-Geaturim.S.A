


<?php

session_start();
include '../../procesos/base.php';
conectarse();

$consulta = pg_query("select SUM(total_compra::FLOAT) from detalle_factura_compra,productos where  detalle_factura_compra.cod_productos=productos.cod_productos and id_factura_compra ='$_POST[id]' and bien_servicio like '%S%' and productos.iva='Si'");

$row = pg_fetch_row($consulta);
echo $row[0];
?>

