<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id=$_GET['com'];
$arr_data=array();
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
$consulta=pg_query("select D.cod_productos, P.codigo, P.articulo, D.cantidad, D.precio_venta, D.descuento_producto, D.total_venta, P.iva, D.pendientes, P.incluye_iva from factura_venta F, detalle_factura_venta D, productos P where D.cod_productos = P.cod_productos and F.id_factura_venta = D.id_factura_venta  and  F.id_empresa='$conpuntoresult' and F.id_factura_venta='" . $id . "'");
while($row=pg_fetch_row($consulta))
 {
  $arr_data[]=$row[0];
  $arr_data[]=$row[1];
  $arr_data[]=$row[2];
  $arr_data[]=$row[3];
  $arr_data[]=$row[4];
  $arr_data[]=$row[5];
  $arr_data[]=$row[6];
  $arr_data[]=$row[7];
  $arr_data[]=$row[8];
  $arr_data[]=$row[9];
 }
echo json_encode($arr_data);
?>