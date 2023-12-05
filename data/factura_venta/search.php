<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
//$codigo_barras=$_GET["codigo_barras"];

$codigo_barras=strtoupper($_GET["codigo_barras"]);
$codigo=$codigo_barras;//$codigo=strtoupper($_GET["cod"]);
$precio=$_GET["precio"];
$unidad_medida = $_GET["unidad_medida"];



$arr_data=array();

$conpunto=1;
$consultapunto=pg_query("select max(id_punto_venta_empresa) from punto_venta_empresa where punto_venta_empresa.id_usuario='$_SESSION[id]'");
while($row=pg_fetch_row($consultapunto)){
  $conpunto=$row[0];
 }
$conpuntoresult=1;
$consultapuntoresult=pg_query("select id_punto_venta from punto_venta_empresa where id_punto_venta_empresa='$conpunto'");
while($row=pg_fetch_row($consultapuntoresult)){
  $conpuntoresult=$row[0];
 }
 
  
 
 
 
 
 
 
if($codigo_barras!="") {
    
  /////////////////////////////////////////////////////////////////////

    if ($unidad_medida != "0") {
        
           $consulta=pg_query("select * from productos where (cod_barras = '$codigo_barras'  or codigo='$codigo')    and estado = 'Activo'");
 
  while($row= pg_fetch_assoc($consulta)) {
        
    $consulta1 = pg_query("
  select p.cod_productos,p.iva,cantidad, pvpmino, pvpmayo, pvpnego,precio_compra,codigo,articulo,iva,series,p.cod_productos,cantidad_descuento,inventariable,incluye_iva,precio_compra from productos p left join unidad_medida_productos ump on p.cod_productos=ump.cod_productos 
left join unidades_medida um on um.id_unidades=ump.id_unidades 
  where p.cod_productos=$row[cod_productos] and um.id_unidades='$unidad_medida' and um.estado='Activo' and ump.estado='Activo'
");
    
//    echo ''."
//  select p.cod_productos,p.iva,cantidad, pvpmino, pvpmayo, pvpnego,precio_compra,codigo,articulo,iva,series,p.cod_productos,cantidad_descuento,inventariable,incluye_iva,precio_compra from productos p left join unidad_medida_productos ump on p.cod_productos=ump.cod_productos 
//left join unidades_medida um on um.id_unidades=ump.id_unidades 
//  where p.cod_productos=$row[cod_productos] and um.id_unidades='$unidad_medida' and um.estado='Activo' and ump.estado='Activo'
//";
    $row1 = pg_fetch_assoc($consulta1);
       $consulta12=pg_query("select dpb.stock from productos p left join detalle_producto_bodega dpb on p.cod_productos=dpb.cod_productos "
            . "where p.cod_productos=$row[cod_productos] and dpb.id_bodega=$conpuntoresult");
    $row2= pg_fetch_assoc($consulta12);

   if($precio=="MINORISTA") {
       $arr_data[]=strtoupper($row['codigo']);
      $arr_data[]=$row['articulo'];
      $arr_data[]=$row1['pvpmino'];
      $arr_data[]=$row2['stock'];
      $arr_data[]=$row['iva'];
      $arr_data[]=$row['series'];
      $arr_data[]=$row['cod_productos'];
      $arr_data[]=$row['cantidad_descuento'];
      $arr_data[]=$row['inventariable'];
      $arr_data[]=$row['incluye_iva'];
      $arr_data[]= $row['precio_compra'];
      $arr_data[]= $row1['cantidad'];
     } else {
      if($precio=="MAYORISTA") {
        $arr_data[]=strtoupper($row['cod_barras']);
        $arr_data[]=$row['articulo'];
        $arr_data[]=$row1['pvpmayo'];
        $arr_data[]=$row2['stock'];
        $arr_data[]=$row['iva'];
        $arr_data[]=$row['series'];
        $arr_data[]=$row['cod_productos'];
        $arr_data[]=$row['cantidad_descuento'];
        $arr_data[]=$row['inventariable'];
        $arr_data[]=$row['incluye_iva'];
           $arr_data[]= $row['precio_compra'];
            $arr_data[]= $row1['cantidad'];
       }  else  {
        if($precio=="NEGOCIO") {
          $arr_data[]=strtoupper($row['cod_barras']);
          $arr_data[]=$row['articulo'];
          $arr_data[]=$row1['pvpnego'];
          $arr_data[]=$row2['stock'];
          $arr_data[]=$row['iva'];
          $arr_data[]=$row['series'];
          $arr_data[]=$row['cod_productos'];
          $arr_data[]=$row['cantidad_descuento'];
          $arr_data[]=$row['inventariable'];
          $arr_data[]=$row['incluye_iva'];
             $arr_data[]= $row['precio_compra'];
              $arr_data[]= $row1['cantidad'];
         }
       }
     }
    
    
    
  }
    
    
    
}else{
    
    
    
    
    
    
    
    
    
    
    
    
    
    //////////////////////////////////////////////////////////////////////
    
    $consulta=pg_query("select * from productos where (cod_barras = '$codigo_barras'  or codigo='$codigo')    and estado = 'Activo'");
 
  while($row= pg_fetch_assoc($consulta)) {
 
    $consulta1=pg_query("select dpb.stock from productos p left join detalle_producto_bodega dpb on p.cod_productos=dpb.cod_productos "
            . "where p.cod_productos=$row[cod_productos] and dpb.id_bodega=$conpuntoresult");
    $row1= pg_fetch_assoc($consulta1);
    if($precio=="MINORISTA") {
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
      $arr_data[]= $row['precio_compra'];
     } else {
      if($precio=="MAYORISTA") {
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
           $arr_data[]= $row['precio_compra'];
       }  else  {
        if($precio=="NEGOCIO") {
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
             $arr_data[]= $row['precio_compra'];
         }
       }
     }
   }
}
   
   
 }
 
 
 
 
echo json_encode($arr_data);
?>