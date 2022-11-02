<?php

session_start();
include '../../procesos/base.php';
conectarse();
$texto=$_GET['term'];

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
$consulta=pg_query("select F.id_factura_venta, F.num_factura, f.num_serie  from factura_venta F, clientes C where C.id_cliente = F.id_cliente and F.id_cliente = '$_GET[id]' and F.num_factura like '%$texto%' and F.id_empresa='$conpuntoresult' and F.estado='Activo'");
while($row=pg_fetch_row($consulta))
 {
  $data[]=array(
    'value'=>$row[1],
    'id_factura_venta'=>$row[0],
    'num_serie'=>$row[2]
  );
 }
echo $data=json_encode($data);
?>