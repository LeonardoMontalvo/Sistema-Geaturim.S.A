<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
//$codigo_barras=$_GET["codigo_barras"];

$codigo_barras=strtoupper($_GET["codigo_barras"]);
$codigo=strtoupper($_GET["cod"]);
$precio=$_GET["precio"];
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
  $consulta=pg_query("select * from productos where (cod_barras = '$codigo_barras'  or codigo='$codigo')    and estado = 'Activo'");
  while($row= pg_fetch_assoc($consulta))
   {
    
    $consulta1=pg_query("select dpb.stock from productos p left join detalle_producto_bodega dpb on p.cod_productos=dpb.cod_productos "
            . "where p.cod_productos=$row[cod_productos] and dpb.id_bodega=$conpuntoresult");
    $row1= pg_fetch_assoc($consulta1);
    if($precio=="MINORISTA")
     {
           $arr_data[]=strtoupper($row['codigo']);
      $arr_data[]=$row['articulo'];
      $arr_data[]=$row['iva_minorista'];
      $arr_data[]=$row1['stock'];
      $arr_data[]=$row['iva'];
      $arr_data[]=$row['series'];
      $arr_data[]=$row['cod_productos'];
      $arr_data[]=$row['cantidad_descuento'];
      $arr_data[]=$row['inventariable'];
      $arr_data[]=$row['incluye_iva'];
     }
    else
     {
      if($precio=="MAYORISTA")
       {
        $arr_data[]=strtoupper($row['cod_barras']);
        $arr_data[]=$row['articulo'];
        $arr_data[]=$row['iva_mayorista'];
        $arr_data[]=$row1['stock'];
        $arr_data[]=$row['iva'];
        $arr_data[]=$row['series'];
        $arr_data[]=$row['cod_productos'];
        $arr_data[]=$row['cantidad_descuento'];
        $arr_data[]=$row['inventariable'];
        $arr_data[]=$row['incluye_iva'];
       }
      else
       {
        if($precio=="NEGOCIO")
         {
          $arr_data[]=strtoupper($row['cod_barras']);
          $arr_data[]=$row['articulo'];
          $arr_data[]=$row['iva_negocio'];
          $arr_data[]=$row1['stock'];
          $arr_data[]=$row['iva'];
          $arr_data[]=$row['series'];
          $arr_data[]=$row['cod_productos'];
          $arr_data[]=$row['cantidad_descuento'];
          $arr_data[]=$row['inventariable'];
          $arr_data[]=$row['incluye_iva'];
         }
       }
     }
   }
 }
echo json_encode($arr_data);
?>