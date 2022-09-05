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

$consulta=pg_query("select F.forma_pago, P.adelanto, P.meses, P.monto_credito from pagos_venta P, factura_venta F where F.id_empresa=1 and F.forma_pago='Credito' and F.id_factura_venta=P.id_factura_venta  and  F.id_empresa='$conpuntoresult' and P.id_factura_venta='" . $id . "'");
while($row=pg_fetch_row($consulta))
 {
  $arr_data[]=$row[0];
  $arr_data[]=$row[1];
  $arr_data[]=$row[2];
  $arr_data[]=$row[3];
 }
echo json_encode($arr_data);
?>