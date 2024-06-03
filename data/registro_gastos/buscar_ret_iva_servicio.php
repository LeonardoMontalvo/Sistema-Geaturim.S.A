


<?php

session_start();
include '../../procesos/base.php';
conectarse();
$consulta = pg_query("select SUM(valor_impuesto::FLOAT) 
from detalle_gastos,detalle_impuesto_producto_gasto
where detalle_impuesto_producto_gasto.id_detalle_gastos=detalle_gastos.id_detalle_gastos
and  id_gastos ='$_POST[id]' 
and bien_servicio like '%S%' 
and tipo_iva='Si'");



$row = pg_fetch_row($consulta);
echo $row[0];
?>

