<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id=$_GET['id2'];
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
 $consulta=pg_query("select P.cod_productos, P.codigo, P.articulo, dpb.stock, D.cantidad, D.precio_venta, D.descuento_venta, D.total_venta, P.iva, P.incluye_iva, P.inventariable from productos P, detalle_proforma D, proforma PR, detalle_producto_bodega dpb where d.cod_productos=dpb.cod_productos and P.cod_productos = D.cod_productos and PR.id_proforma = D.id_proforma and PR.estado ='Activo'  and D.estado= 'Activo'  and  PR.id_empresa='$conpuntoresult' and D.id_proforma='" . $id . "'");

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
  $arr_data[]=$row[10];
 }
echo json_encode($arr_data);
?>
