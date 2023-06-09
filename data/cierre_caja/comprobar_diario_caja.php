<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$data = "";


date_default_timezone_set('America/Guayaquil');

$fecha = date('Y-m-d H:i:s', time());
$total_diario_caja=0;
$var_total_movi=0;
//echo 'j'."SELECT sum(total_venta::float) FROM factura_venta WHERE fecha_actual BETWEEN '$fecha' AND '$fecha' and forma_pago='Contado' and estado = 'Activo'   and id_empresa='1'  ";
$sql = pg_query("SELECT sum(monto::float) FROM anticipo_clientes WHERE fecha_actual BETWEEN '$fecha' AND '$fecha'  and id_empresa='$_SESSION[PV]' and id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($sql)) {
    $anticipo_clientes = $row[0];
}
$sql = pg_query("SELECT sum(total_venta::float) FROM factura_venta WHERE fecha_actual BETWEEN '$fecha' AND '$fecha'and forma_pago='TCredito' and estado = 'Activo'   and id_empresa='$_SESSION[PV]' and id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($sql)) {
    $pvp1 = $row[0];
}
$sql = pg_query("SELECT sum(total_venta::float) FROM factura_venta WHERE fecha_actual BETWEEN '$fecha' AND '$fecha' and forma_pago='PVP2' and estado = 'Activo'   and id_empresa='$_SESSION[PV]' and id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($sql)) {
    $pvp2 = $row[0];
}

$sql = pg_query("SELECT sum(total_venta::float) 
FROM factura_venta WHERE fecha_actual BETWEEN '$fecha' AND '$fecha'
and forma_pago='Contado' 
and estado = 'Activo'   
and id_empresa='$_SESSION[PV]'");
while ($row = pg_fetch_row($sql)) {
    $contado += $row[0];
}

//$sqlc2 = pg_query("SELECT 
//sum(fpm.valor) 
//FROM factura_venta fv 
//inner join formas_pago_mixto fpm
//on fv.id_factura_venta=fpm.id_factura_venta
//WHERE 
//fv.fecha_actual $query_fecha '$_GET[fin]' 
//and (fv.forma_pago='otros' 
//and fv.estado = 'Activo'
//and fpm.forma_pago='CONTADO')   
//and fpm.tipo_documento='FACTURA'
//and fv.id_empresa='$_GET[id1]'");
//while ($row = pg_fetch_row($sqlc2)) {
//    $contado += $row[0];
//}

$sqlc2 = pg_query("SELECT sum(valor::float) FROM factura_venta fv 
inner join formas_pago_mixto fpm on fv.id_factura_venta=fpm.id_factura_venta
 WHERE fpm.fecha_actual BETWEEN '$fecha' AND '$fecha' and  fpm.forma_pago='CONTADO' and fpm.tipo_documento='FACTURA' and fv.id_empresa='$_SESSION[PV]' and id_usuario='$_SESSION[id]' and fv.estado = 'Activo' ");
while ($row = pg_fetch_row($sqlc2)) {
    $contado_mixto += $row[0];
}

$sql = pg_query("SELECT sum(total_venta::float) FROM factura_venta WHERE fecha_actual BETWEEN '$fecha' AND '$fecha' and forma_pago='Credito' and estado = 'Activo'   and id_empresa='$_SESSION[PV]' and id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($sql)) {
    $credito = $row[0];
}
$sqlc2 = pg_query("SELECT 
sum(valor::float) 
FROM factura_venta fv 
inner join formas_pago_mixto fpm
on fv.id_factura_venta=fpm.id_factura_venta
WHERE 
fv.fecha_actual BETWEEN '$fecha' AND '$fecha'
and (fv.forma_pago='otros' 
and fv.estado = 'Activo'
and fpm.forma_pago='CREDITO')   
and fpm.tipo_documento='FACTURA'
and fv.id_empresa='$_SESSION[PV]' and id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($sqlc2)) {
    $credito += $row[0];
}

$sql = pg_query("SELECT sum(total_venta::float) FROM factura_venta WHERE fecha_actual BETWEEN '$fecha' AND '$fecha' and forma_pago='Cheque' and estado = 'Activo'   and id_empresa='$_SESSION[PV]' and id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($sql)) {
    $cheque = $row[0];
}
$sqlc2 = pg_query("SELECT 
sum(valor::float) 
FROM factura_venta fv 
inner join formas_pago_mixto fpm
on fv.id_factura_venta=fpm.id_factura_venta
WHERE 
fv.fecha_actual BETWEEN '$fecha' AND '$fecha'
and (fv.forma_pago='otros' 
and fv.estado = 'Activo'
and fpm.forma_pago='CHEQUE')   
and fpm.tipo_documento='FACTURA'
and fv.id_empresa='$_SESSION[PV]' and id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($sqlc2)) {
    $cheque += $row[0];
}

$sql = pg_query("SELECT sum(total_venta::float) FROM factura_venta 
WHERE fecha_actual BETWEEN '$fecha' AND '$fecha'
and forma_pago='TCredito' 
and estado = 'Activo'   
and id_empresa='$_SESSION[PV]' and id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($sql)) {
    $tarjetaCredito = $row[0];
}
$sqlc2 = pg_query("SELECT 
sum(valor::float) 
FROM factura_venta fv 
inner join formas_pago_mixto fpm
on fv.id_factura_venta=fpm.id_factura_venta
WHERE 
fv.fecha_actual BETWEEN '$fecha' AND '$fecha'
and (fv.forma_pago='otros' 
and fv.estado = 'Activo'
and fpm.forma_pago='TCREDITO')   
and fpm.tipo_documento='FACTURA'
and fv.id_empresa='$_SESSION[PV]' and id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($sqlc2)) {
    $tarjetaCredito += $row[0];
}

$sql = pg_query("SELECT sum(total_venta::float) 
FROM facturas_novalidas 
WHERE fecha_actual BETWEEN '$fecha' AND '$fecha'
and estado = 'Activo'   
and id_empresa='$_SESSION[PV]' and id_usuario='$_SESSION[id]'
and forma_pago='TCredito'");
while ($row = pg_fetch_row($sql)) {
    $notatarjetaCredito = $row[0];
}
$sqlc2 = pg_query("SELECT 
sum(valor::float) 
FROM facturas_novalidas fv 
inner join formas_pago_mixto fpm
on fv.id_facturas_novalidas=fpm.id_factura_venta
WHERE 
fv.fecha_actual BETWEEN '$fecha' AND '$fecha'
and (fv.forma_pago='otros' 
and fv.estado = 'Activo'
and fpm.forma_pago='TCREDITO')   
and fpm.tipo_documento='NOTA'
and fv.id_empresa='$_SESSION[PV]' and id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($sqlc2)) {
    $notatarjetaCredito += $row[0];
}

$sql = pg_query("SELECT sum(total_venta::float) FROM facturas_novalidas WHERE fecha_actual BETWEEN '$fecha' AND '$fecha' and estado = 'Activo'  and id_empresa='$_SESSION[PV]' and id_usuario='$_SESSION[id]' and forma_pago='Contado'");
while ($row = pg_fetch_row($sql)) {
    $notaVentacont = $row[0];
}
$sqlc2 = pg_query("SELECT 
sum(valor::float) 
FROM facturas_novalidas fv 
inner join formas_pago_mixto fpm
on fv.id_facturas_novalidas=fpm.id_factura_venta
WHERE 
fv.fecha_actual BETWEEN '$fecha' AND '$fecha'
and  fv.estado = 'Activo'
and fpm.forma_pago='CONTADO'  
and fpm.tipo_documento='NOTA'
and fv.id_empresa='$_SESSION[PV]' and id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($sqlc2)) {
    $notaVentacont_mixto += $row[0];
}

$sql = pg_query("SELECT sum(total_venta::float) FROM facturas_novalidas WHERE fecha_actual BETWEEN '$fecha' AND '$fecha' and estado = 'Activo'   and id_empresa='$_SESSION[PV]' and id_usuario='$_SESSION[id]' and forma_pago='Credito'");
while ($row = pg_fetch_row($sql)) {
    $notaVentacredito = $row[0];
}
$sqlc2 = pg_query("SELECT 
sum(valor::float) 
FROM facturas_novalidas fv 
inner join formas_pago_mixto fpm
on fv.id_facturas_novalidas=fpm.id_factura_venta
WHERE 
fv.fecha_actual BETWEEN '$fecha' AND '$fecha'
and (fv.forma_pago='otros' 
and fv.estado = 'Activo'
and fpm.forma_pago='CREDITO')   
and fpm.tipo_documento='NOTA'
and fv.id_empresa='$_SESSION[PV]' and id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($sqlc2)) {
    $notaVentacredito += $row[0];
}

$sql = pg_query("SELECT sum(total_venta::float) FROM factura_venta WHERE fecha_actual BETWEEN '$fecha' AND '$fecha' and forma_pago='Transferencias' and estado = 'Activo'   and id_empresa='$_SESSION[PV]' and id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($sql)) {
    $transferencia = $row[0];
}
$sqlc2 = pg_query("SELECT 
sum(valor::float) 
FROM factura_venta fv 
inner join formas_pago_mixto fpm
on fv.id_factura_venta=fpm.id_factura_venta
WHERE 
fv.fecha_actual BETWEEN '$fecha' AND '$fecha'
and (fv.forma_pago='otros' 
and fv.estado = 'Activo'
and fpm.forma_pago='TRANSFERENCIAS')   
and fpm.tipo_documento='FACTURA'
and fv.id_empresa='$_SESSION[PV]' and id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($sqlc2)) {
    $transferencia += $row[0];
}

$sql = pg_query("SELECT sum(total_venta::float) FROM facturas_novalidas WHERE fecha_actual BETWEEN '$fecha' AND '$fecha' and estado = 'Activo'   and id_empresa='$_SESSION[PV]' and id_usuario='$_SESSION[id]' and forma_pago='Transferencias'");
while ($row = pg_fetch_row($sql)) {
    $notaTransferencia = $row[0];
}
$sqlc2 = pg_query("SELECT 
sum(valor::float) 
FROM facturas_novalidas fv 
inner join formas_pago_mixto fpm
on fv.id_facturas_novalidas=fpm.id_factura_venta
WHERE 
fv.fecha_actual BETWEEN '$fecha' AND '$fecha'
and (fv.forma_pago='otros' 
and fv.estado = 'Activo'
and fpm.forma_pago='TRANSFERENCIAS')   
and fpm.tipo_documento='NOTA'
and fv.id_empresa='$_SESSION[PV]' and id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($sqlc2)) {
    $notaTransferencia += $row[0];
}

$sql = pg_query("SELECT sum(total_venta::float) 
FROM facturas_novalidas WHERE fecha_actual BETWEEN '$fecha' AND '$fecha'
and estado = 'Activo'   and id_empresa='$_SESSION[PV]' and id_usuario='$_SESSION[id]'
and forma_pago='Cheque'");
while ($row = pg_fetch_row($sql)) {
    $notaCheque = $row[0];
}
$sqlc2 = pg_query("SELECT 
sum(valor::float) 
FROM facturas_novalidas fv 
inner join formas_pago_mixto fpm
on fv.id_facturas_novalidas=fpm.id_factura_venta
WHERE 
fv.fecha_actual BETWEEN '$fecha' AND '$fecha'
and (fv.forma_pago='otros' 
and fv.estado = 'Activo'
and fpm.forma_pago='CHEQUE')   
and fpm.tipo_documento='NOTA'
and fv.id_empresa='$_SESSION[PV]' and id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($sqlc2)) {
    $notaCheque += $row[0];
}

$sql = pg_query("SELECT sum(total::float) FROM gastos_internos WHERE f AND echa_actual BETWEEN '$fecha' AND '$fecha'  and id_empresa='$_SESSION[PV]' and id_usuario='$_SESSION[id]' estado='Activo';");
while ($row = pg_fetch_row($sql)) {
    $gastos = $row[0];
}

$sql = pg_query("SELECT sum(valor_pagado::float) FROM pagos_cobrar WHERE fecha_actual BETWEEN '$fecha' AND '$fecha' AND forma_pago='EFECTIVO'  and id_empresa='$_SESSION[PV]' and id_usuario='$_SESSION[id]' AND estado='Activo';");
while ($row = pg_fetch_row($sql)) {
    $cxce = $row[0];
}

$sql = pg_query("SELECT sum(valor_pagado::float) FROM pagos_cobrar WHERE fecha_actual BETWEEN '$fecha' AND '$fecha' AND forma_pago='CHEQUE'  and id_empresa='$_SESSION[PV]' and id_usuario='$_SESSION[id]' AND estado='Activo';");
while ($row = pg_fetch_row($sql)) {
    $cxcc = $row[0];
}

$sql = pg_query("SELECT sum(valor_pagado::float) FROM pagos_cobrar WHERE fecha_actual BETWEEN '$fecha' AND '$fecha' AND forma_pago='TARJETA'   and id_empresa='$_SESSION[PV]' and id_usuario='$_SESSION[id]' AND estado='Activo';");
while ($row = pg_fetch_row($sql)) {
    $cxct = $row[0];
}

$sql = pg_query("SELECT sum(total_venta::float) FROM devolucion_venta WHERE fecha_actual BETWEEN '$fecha' AND '$fecha'  and id_empresa='$_SESSION[PV]' and id_usuario='$_SESSION[id]' and estado='Activo';");
while ($row = pg_fetch_row($sql)) {
    $ncred = $row[0];
}

$total_diario_caja=number_format(($contado+$contado_mixto+  $cxce + $cxcc + $cxct + +$notaVentacont+$notaVentacont_mixto+$anticipo_clientes), 4, ',', '.');
//////////////////////////  
//guardar cuentas contables/////

    $data = $data . $total_diario_caja;
   

////////////////////////////////
echo $data;
?>
