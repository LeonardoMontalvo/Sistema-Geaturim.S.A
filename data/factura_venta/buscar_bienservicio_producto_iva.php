
<?php

session_start();
include '../../procesos/base.php';
conectarse();

$consulta = pg_query("select SUM(total_venta::FLOAT) from detalle_factura_venta,productos where  detalle_factura_venta.cod_productos=productos.cod_productos and id_factura_venta ='$_POST[id]' and bien_servicio like '%B%' and productos.iva='Si'");
$row = pg_fetch_row($consulta);
echo $row[0];
?>

