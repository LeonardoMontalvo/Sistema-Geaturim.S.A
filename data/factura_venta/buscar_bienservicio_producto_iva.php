
<?php

session_start();
include '../../procesos/base.php';
conectarse();

$consulta = pg_query("select SUM(valor_impuesto::FLOAT)
 from detalle_factura_venta,productos ,detalle_impuesto_producto_venta
 where  detalle_factura_venta.cod_productos=productos.cod_productos
 and detalle_impuesto_producto_venta.id_detalle_venta=detalle_factura_venta.id_detalle_venta
  and id_factura_venta ='$_POST[id]'
   and detalle_factura_venta.bien_servicio like '%B%'
    and productos.iva='Si'");
$row = pg_fetch_row($consulta);
echo $row[0];
?>

