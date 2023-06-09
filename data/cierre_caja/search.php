<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$codigo_barras=$_GET["codigo_barras"];
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
if($codigo_barras!="")
{
$consulta=pg_query("select * from productos where  estado='Activo' and cod_barras =  '$codigo_barras' ");
while($row=pg_fetch_row($consulta))
 {  
  $consulta1=pg_query("select * from   productos p where p.cod_productos=$row[0]  ");
  $row1=pg_fetch_row($consulta1); 
  $arr_data[]=$row[1];
  $arr_data[]=$row[3];
  $arr_data[]=$row[6];
  $arr_data[]=$row1[13];
  $arr_data[]=$row[9];
  $arr_data[]=$row[22];
  $arr_data[]=$row[23];
  $arr_data[]=$row[0];
  $arr_data[]=$row[41];
  
 }
 
}
echo json_encode($arr_data);
?>