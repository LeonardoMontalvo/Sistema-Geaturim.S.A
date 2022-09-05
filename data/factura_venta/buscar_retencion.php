<?php

session_start();
include '../../procesos/base.php';
conectarse();

$consulta = pg_query("select dcr.valor_retenido,dcr.id_retencion_fuentes from detallecomprobanteretencion_v dcr, retencion_fuente_factura_venta rff ,factura_venta fc where 
            dcr.id_retencion_fuente_factura_venta=rff.id_retencion_fuente_factura_venta
            and rff.id_factura=fc.id_factura_venta and fc.id_factura_venta='$_POST[comprobante]' ");
$row = pg_fetch_row($consulta);
echo $row[0];
?>
