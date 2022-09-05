<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

/////////////////contador cuentas x cobrar externas/////////////
$cont = 0;
$consulta = pg_query("select max(id_c_cobrarexternas) from c_cobrarexternas");
while ($row = pg_fetch_row($consulta)) {
    $cont = $row[0];
}
$cont++;
//////////////////////////  

//guardar detalle_factura/////
$total = $_POST['total'];
$format_numero = number_format($total, 2, '.', '');

//	 echo '<br>GUARDAR FACTURA VENTA: <br>' . "insert into c_cobrarexternas values('$cont', '$_POST[id_cliente]', '$_SESSION[PV]', '$_SESSION[id]' ,'$_POST[comprobante]' ,'$_POST[fecha_actual]' ,'$_POST[hora_actual]' ,'$_POST[num_factura]','$total','Activo','$format_numero','$_POST[tipo_documento]','$_POST[fecha_emision]','$_POST[fecha_vencimiento]')";//////////////////////////
//	 
pg_query("insert into c_cobrarexternas values('$cont', '$_POST[id_cliente]', '$_SESSION[PV]', '$_SESSION[id]' ,'$_POST[comprobante]' ,'$_POST[fecha_actual]' ,'$_POST[hora_actual]' ,'$_POST[num_factura]','$total','Activo','$format_numero','$_POST[tipo_documento]','$_POST[fecha_emision]','$_POST[fecha_vencimiento]')");

////////////////////////////////

$data = 1;
echo $data;
