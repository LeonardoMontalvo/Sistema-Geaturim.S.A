<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

//////////////////consulta 1/////////////////
$consulta = pg_query("select F.id_factura_venta from factura_venta F, 
                    pagos_venta P,formas_pago_mixto fpm 
                    where  f.id_factura_venta=fpm.id_factura_venta 
                    and (fpm.forma_pago='CREDITO' 
                    or fpm.forma_pago='CPOSFECHADO') 
                    and F.id_cliente='$_GET[cod]' 
                    and F.id_factura_venta = P.id_factura_venta 
                    and fpm.estado='Activo' 
                    and (P.estado='Activo' or P.estado='Cancelado') 
                    and P.id_empresa='$_SESSION[PV]' limit 1");

$consultanv=pg_query("select F.id_facturas_novalidas from facturas_novalidas F, 
                    pagos_venta P,formas_pago_mixto fpm 
                    where  f.id_facturas_novalidas=fpm.id_factura_venta 
                    and (fpm.forma_pago='CREDITO' 
                    or fpm.forma_pago='CPOSFECHADO') 
                    and F.id_cliente='$_GET[cod]' 
                    and F.id_facturas_novalidas = P.id_factura_venta 
                    and fpm.estado='Activo' 
                    and (P.estado='Activo' or P.estado='Cancelado') 
                    and P.id_empresa='$_SESSION[PV]' limit 1");               
                    
if (pg_num_rows($consulta) > 0||pg_num_rows($consultanv) > 0) {
    echo "<option id=INTERNA value=INTERNA >INTERNA</option>";
}
//////////////////////////////////////////
//
//////////////////consulta 2/////////////////
$consulta2 = pg_query("select * from c_cobrarexternas where id_cliente='$_GET[cod]' and estado='Activo' and id_empresa='$_SESSION[PV]'");
if (pg_num_rows($consulta2) > 0) {
    echo "<option id=EXTERNA value=EXTERNA >EXTERNA</option>";
}
//////////////////////////////////////////
?>