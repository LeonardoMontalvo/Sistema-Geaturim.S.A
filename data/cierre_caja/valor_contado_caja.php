<?php


$contado_mixto = 0;
$notaVentacont_mixto = 0;
$total = 0;
$contado = 0;
$anticipo_clientes = 0;
$credito = 0;
$cheque = 0;
$gastos = 0;
$gastos2 = 0;
$notaVentacont = 0;
$notaVentacredito = 0;
$notatarjetaCredito = 0;
$tarjetaCredito = 0;
$transferencia = 0;
$notaTransferencia = 0;
$cxce = 0;
$cxcc = 0;
$cxct = 0;
$ncred = 0;
$cxctrans_f = 0;
$cxctrans_nv = 0;


$sql = pg_query("SELECT sum(monto::float) FROM anticipo_clientes WHERE fecha_actual $query_fecha '$_GET[fin]'   and id_empresa='$_GET[id1]' and id_usuario='$_GET[id]'");
while ($row = pg_fetch_row($sql)) {
    $anticipo_clientes = $row[0];
}


$sql = pg_query("SELECT sum(total_venta::float) 
FROM factura_venta WHERE fecha_actual $query_fecha '$_GET[fin]' 
and forma_pago='Contado' 
and estado = 'Activo'   
and id_empresa='$_GET[id1]' and id_usuario='$_GET[id]'");
while ($row = pg_fetch_row($sql)) {
    $contado += $row[0];
}
$sqlc2 = pg_query("SELECT sum(valor::float) FROM factura_venta fv 
inner join formas_pago_mixto fpm on fv.id_factura_venta=fpm.id_factura_venta
 WHERE fpm.fecha_actual $query_fecha '$_GET[fin]' and  fpm.forma_pago='CONTADO' and fpm.tipo_documento='FACTURA' and fv.id_empresa='$_GET[id1]' and fv.estado = 'Activo' and  fv.id_usuario='$_GET[id]'");
while ($row = pg_fetch_row($sqlc2)) {
    $contado_mixto += $row[0];
}

$sql = pg_query("SELECT sum(total_venta::float) FROM facturas_novalidas WHERE fecha_actual $query_fecha '$_GET[fin]' and estado = 'Activo'  and id_empresa='$_GET[id1]' and forma_pago='Contado'  and id_usuario='$_GET[id]'");
while ($row = pg_fetch_row($sql)) {
    $notaVentacont = $row[0];
}
$sqlc2 = pg_query("SELECT 
sum(valor::float) 
FROM facturas_novalidas fv 
inner join formas_pago_mixto fpm
on fv.id_facturas_novalidas=fpm.id_factura_venta
WHERE 
fv.fecha_actual $query_fecha '$_GET[fin]' 
and  fv.estado = 'Activo'
and fpm.forma_pago='CONTADO'  
and fpm.tipo_documento='NOTA'
and fv.id_empresa='$_GET[id1]' and fv.id_usuario='$_GET[id]'");
while ($row = pg_fetch_row($sqlc2)) {
    $notaVentacont_mixto += $row[0];
}