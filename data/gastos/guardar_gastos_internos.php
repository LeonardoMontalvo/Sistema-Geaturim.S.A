<?php

session_start();
include '../../procesos/base.php';
require_once '../centro_costos/guardar_detalles.php';
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
/////////////////contador cuentas x cobrar externas/////////////
$cont1 = 0;
$consulta = pg_query("select max(id_gastos) from gastos_internos");
while ($row = pg_fetch_row($consulta)) {
  $cont1 = $row[0];
}
$cont1++;
//////////////////////////  
//
//guardar detalle_factura/////
$total = $_POST['total'];
$format_numero = number_format($total, 2, '.', '');
$res = pg_query("insert into gastos_internos values('$cont1', '$_SESSION[id]' , '$_POST[id_proveedor]','$_POST[comprobante]' ,'$_POST[fecha_actual]' ,'$_POST[hora_actual]' ,'$_POST[num_factura]','$_POST[descripcion]','$format_numero','Activo','$conpuntoresult')");
if (!empty($res) && !empty($_POST['id_centro_costo'])) {
  guardarDetalleCentroCosto($cont1, $_POST['id_centro_costo'], "gastos_internos");
}
////////////////////////////////

$data = 1;
echo $data;
