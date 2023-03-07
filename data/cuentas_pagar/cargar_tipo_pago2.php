<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

//////////////////consulta 1/////////////////
$consulta = pg_query("
select * from factura_compra F, pagos_compra P ,formas_pago_mixto_c fpm  
where f.id_factura_compra=fpm.id_factura_compra and   fpm.forma_pago='CREDITO' and f.id_empresa='$_SESSION[PV]'
 and F.id_proveedor='$_GET[cod]' and F.id_factura_compra = P.id_factura_compra and (P.estado='Activo' or P.estado='Cancelado') and comprao_gasto='C'");
if (pg_num_rows($consulta) > 0) {
    echo "<option id=INTERNA value=INTERNA >INTERNA</option>";
}
$consulta = pg_query("
select * from gastos F, pagos_compra P ,formas_pago_mixto_g fpm  
where f.id_gastos=fpm.id_gastos and   fpm.forma_pago='CREDITO' and F.id_empresa='$_SESSION[PV]'
 and F.id_proveedor='$_GET[cod]' and F.id_gastos = P.id_factura_compra and (P.estado='Activo' or P.estado='Cancelado') and comprao_gasto='G'");
if (pg_num_rows($consulta) > 0) {
    echo "<option id=INTERNA value=INTERNA >INTERNA</option>";
}
$consulta=pg_query("
select * from 
devolucion_venta F, 
pagos_compra P ,
formas_pago_mixto_nv fpm  
where f.id_devolucion_venta=fpm.id_devolucion_venta 
and fpm.forma_pago='CXP' and F.id_empresa='$_SESSION[PV]'
and F.id_cliente='12' 
and F.id_devolucion_venta = P.id_factura_compra 
and P.estado='Activo' 
and P.tipo_documento='NOTA_CREDITO'
");
if (pg_num_rows($consulta) > 0) {
    echo "<option id=INTERNA value=INTERNA >INTERNA</option>";
}
//////////////////////////////////////////
//////////////////consulta 2/////////////////
$consulta2 = pg_query("select * from c_pagarexternas where id_proveedor = '$_GET[cod]' and estado='Activo' and id_empresa='$_SESSION[PV]'");
if (pg_num_rows($consulta2) > 0) {
    echo "<option id=EXTERNA value=EXTERNA >EXTERNA</option>";
}
//////////////////////////////////////////
?>