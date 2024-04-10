<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id=$_GET['com'];
$arr_data=array();
$tipo=$_GET['tipo_precio'];

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

$consulta=pg_query("select  DISTINCT ON (p.cod_productos) * from productos P     LEFT join punto_venta PV on P.id_bodega = PV.id_punto_venta   LEFT join plan_cuentas PC on PC.id_plan_cuentas=P.id_plan_cuentas     LEFT join proveedores PR on p.proveedor=pr.id_proveedor LEFT join detalle_producto_bodega  dpb on p.cod_productos=dpb.cod_productos   LEFT join  generico g on P.caracteristicas::int = g.id_generico    LEFT join categoria c on P.categoria::int = c.id_categoria  LEFT join marcas m on P.marca::int = m.id_marca LEFT join aplicacion a on P.observaciones::int = a.id_aplicacion where  p.cod_productos= '" . $id . "' and dpb.id_bodega=$conpuntoresult and P.estado = 'Activo'  ");
while($row=pg_fetch_row($consulta))
 {
  
 if($tipo=="MINORISTA")
   {
  
    
       $arr_data[]=$row[0];
       $arr_data[]=$row[2];
       $arr_data[]=$row[9];
       $arr_data[]=$row[19];
       $arr_data[]=$row[72];
       $arr_data[]=$row[4];
       $arr_data[]=$row[5];
       $arr_data[]=$row[1];
       $arr_data[]=$row[19];
       $arr_data[]=$row[21];
       $arr_data[]=$row[26];
       $arr_data[]=$row[3];
      

   }
  else
   {
    if($tipo=="MAYORISTA")
     {
       $arr_data[]=$row[1];
       $arr_data[]=$row[2];
       $arr_data[]=$row[10];
       $arr_data[]=$row[19];
       $arr_data[]=$row[72];
       $arr_data[]=$row[4];
       $arr_data[]=$row[5];
       $arr_data[]=$row[0];
       $arr_data[]=$row[19];
       $arr_data[]=$row[21];
       $arr_data[]=$row[26];
       $arr_data[]=$row[3];
     }
    else
     {
      if($tipo=="NEGOCIO")
       {
         $arr_data[]=$row[1];
       $arr_data[]=$row[2];
       $arr_data[]=$row[27];
       $arr_data[]=$row[19];
       $arr_data[]=$row[72];
       $arr_data[]=$row[4];
       $arr_data[]=$row[5];
       $arr_data[]=$row[0];
       $arr_data[]=$row[19];
       $arr_data[]=$row[21];
       $arr_data[]=$row[26];
       $arr_data[]=$row[3];
       
       }
     }
   }
  

 }
echo json_encode($arr_data);
?>