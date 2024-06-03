
<?php

session_start();
include '../../procesos/base.php';
conectarse();

$consulta = pg_query("select SUM(valor_impuesto::FLOAT) 
from detalle_factura_compra,productos ,detalle_impuesto_producto_compra
where  detalle_factura_compra.cod_productos=productos.cod_productos 
and detalle_impuesto_producto_compra.id_detalle_compra=detalle_factura_compra.id_detalle_compra
and id_factura_compra ='$_POST[id]' 
and detalle_factura_compra.bien_servicio like '%B%' 
and productos.iva='Si'");
$row = pg_fetch_row($consulta);
echo $row[0];
?>

