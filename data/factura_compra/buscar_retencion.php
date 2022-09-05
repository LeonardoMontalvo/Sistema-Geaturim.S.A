<?php

session_start();
include '../../procesos/base.php';
conectarse();

$consulta = pg_query("select dcr.valor_retenido,dcr.id_retencion_fuentes from detallecomprobanteretencion dcr, retencion_fuente_factura_compra rff ,factura_compra fc where 
            dcr.id_retencion_fuente_factura_compra=rff.id_retencion_fuente_factura_compra
            and rff.id_factura=fc.id_factura_compra and fc.id_factura_compra=$_POST[comprobante] and rff.id_gastos=1 ");
$row = pg_fetch_row($consulta);
echo $row[0];
?>
