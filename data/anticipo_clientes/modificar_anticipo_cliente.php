<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
error_reporting(0);
$conpunto = 1;
$consultapunto = pg_query("select max(id_punto_venta_empresa) from punto_venta_empresa where punto_venta_empresa.id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($consultapunto)) {
    $conpunto = $row[0];
}

$conpuntoresult = 1;
$consultapuntoresult = pg_query("select id_punto_venta from punto_venta_empresa where id_punto_venta_empresa='$conpunto'");
while ($row = pg_fetch_row($consultapuntoresult)) {
    $conpuntoresult = $row[0];
}

//guardar detalle_factura/////
$total = $_POST['monto'];
$format_numero = number_format($total, 2, '.', '');

//echo '::',"Update anticipo_clientes Set id_clientes='".strtoupper($_POST['id_cliente'])."', id_usuario='$_SESSION[PV]', id_cuenta='$_POST[idCuenta]', fecha_actual='".strtoupper($_POST['fecha_actual'])."', hora_actual='".strtoupper($_POST['hora_actual'])."', monto='$total', forma_pago='".strtoupper($_POST['formaspago_mixto'])."', observacion='".strtoupper($_POST['comentario'])."' where id_anticipo_clientes='$_POST[comprobante]'";

$cont = 0;
$consulta=pg_query("SELECT id_anticipo_clientes FROM anticipo_clientes where   anticipo_clientes.estado = 'Facturado' and id_anticipo_clientes ='$_POST[comprobante]'");
 while ($row = pg_fetch_row($consulta)) {
  $cont=$row[0];  
}

if($cont == 0){
    pg_query("Update anticipo_clientes Set id_clientes='".strtoupper($_POST['id_cliente'])."', id_usuario='$_SESSION[PV]', id_cuenta='$_POST[idCuenta]', fecha_actual='".strtoupper($_POST['fecha_actual'])."', hora_actual='".strtoupper($_POST['hora_actual'])."', monto='$total', forma_pago='$_POST[formaspago_mixto]', observacion='".strtoupper($_POST['comentario'])."' where id_anticipo_clientes='$_POST[comprobante]'");       
    $data = 0;
} else {
    $data = 1;
}


 

// Auditoria
insert_registro('MODIFCAR ANTICIPO CLIENTES CON ID: ' . $cont . ', DEL CLIENTE CON ID: ' . $_POST['id_cliente'] . ', CON UN TOTAL DE: ' . $total);

echo $data;




