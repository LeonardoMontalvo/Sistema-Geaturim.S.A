<?php

session_start();
include '../../procesos/base.php';
conectarse();
$conpunto=1;
$consultapunto=pg_query("select max(id_punto_venta_empresa) from punto_venta_empresa where punto_venta_empresa.id_usuario='$_SESSION[id]'");
while($row=pg_fetch_row($consultapunto))
 {
  $conpunto=$row[0];
 }
$conpuntoresult=1;
$consultapuntoresult=pg_query("select id_punto_venta from punto_venta_empresa where id_punto_venta_empresa='$conpunto'");
while($row=pg_fetch_row($consultapuntoresult))
 {
  $conpuntoresult=$row[0];
 }
$consulta = pg_query("select id_gastos, num_factura, comprobante, fecha_actual, hora_actual, fecha_emision, descripcion, subtotal, iva, total, gastos.estado, proveedores.id_proveedor, tipo_documento, identificacion_pro, empresa_pro,deposito,banco,num_cuenta,num_autorizacion, tarifa0,tarifa12,iva_compra,descuento_compra, total_compra from gastos, proveedores where id_gastos='$_GET[com]' and  id_empresa='$conpuntoresult' and proveedores.id_proveedor=gastos.id_proveedor");
while ($row = pg_fetch_row($consulta)) {
    $lista[] = $row[0];
    $lista[] = $row[1];
    $lista[] = $row[2];
    $lista[] = $row[3];
    $lista[] = $row[4];
    $lista[] = $row[5];
    $lista[] = $row[6];
    $lista[] = $row[7];
    $lista[] = $row[8];
    $lista[] = $row[9];
    $lista[] = $row[10];
    $lista[] = $row[11];
    $lista[] = $row[12];
    $lista[] = $row[13];
    $lista[] = $row[14];
    $lista[] = $row[15];
    $lista[] = $row[16];
    $lista[] = $row[17];
    $lista[] = $row[18];
    $lista[] = $row[19];
    $lista[] = $row[20];
    $lista[] = $row[21];
    $lista[] = $row[22];
    $lista[] = $row[23];
  
}
echo $lista = json_encode($lista);
?>
