<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id=$_GET['id1'];
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
$consulta=pg_query("select C.id_cliente,C.identificacion, C.nombres_cli, C.direccion_cli, C.celular, C.correo, P.tipo_precio from proforma P, clientes C where P.id_cliente=C.id_cliente and P.estado='Activo'  and  P.id_empresa='$conpuntoresult' and P.id_proforma='" . $id . "'");
while($row=pg_fetch_row($consulta))
 {
  $arr_data[]=$row[0];
  $arr_data[]=$row[1];
  $arr_data[]=$row[2];
  $arr_data[]=$row[3];
  $arr_data[]=$row[4];
  $arr_data[]=$row[5];
  $arr_data[]=$row[6];
 }
echo json_encode($arr_data);
?>
