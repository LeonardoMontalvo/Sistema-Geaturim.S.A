<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

// modificar factura compra
pg_query("Update factura_compra Set  id_proveedor = '$_POST[id_proveedor]', id_usuario = '$_SESSION[id]', fecha_actual = '$_POST[fecha_actual]', hora_actual = '$_POST[hora_actual]', fecha_registro = '$_POST[fecha_registro]'
    , fecha_emision = '$_POST[fecha_emision]', fecha_caducidad = '$_POST[fecha_caducidad]', tipo_comprobante = '$_POST[tipo_comprobante]', num_autorizacion = '$_POST[autorizacion]', fecha_cancelacion = '$_POST[cancelacion]', forma_pago = '$_POST[formas]'
    , tarifa0 = '$_POST[tarifa0]', tarifa12 = '$_POST[tarifa12]', iva_compra = '$_POST[iva]', descuento_compra = '$_POST[desc]', total_compra = '$_POST[tot]' where id_factura_compra = '$_POST[id_factura_compra]'");
// fin

// datos detalle factura
$campo1=$_POST['campo1'];
$campo2=$_POST['campo2'];
$campo3=$_POST['campo3'];
$campo4=$_POST['campo4'];
$campo5=$_POST['campo5'];
// fin 

// agregar detalle_factura_venta
$arreglo1=explode('|', $campo1);
$arreglo2=explode('|', $campo2);
$arreglo3=explode('|', $campo3);
$arreglo4=explode('|', $campo4);
$arreglo5=explode('|', $campo5);
//fin
$canti2=0;
$canti1=0;
// restar stock productos
$consulta=pg_query("select * from detalle_factura_compra where id_factura_compra = '$_POST[id_factura_compra]'");
while($row=pg_fetch_row($consulta))
 {
  $canti1=$row[3];
  $id=$row[2];
  $consulta2=pg_query("select * from productos where cod_productos = '" . $id . "'");
  while($row=pg_fetch_row($consulta2))
   {
    $canti2=$row[13];
   }  
  $cal1=$canti2-$canti1;
  pg_query("Update productos Set stock='" . $cal1 . "' where cod_productos='" . $id . "'");
  // contador kardex
  $cont_k=0;
  $consulta_k=pg_query("select max(id_kardex) from kardex");
  while($row=pg_fetch_row($consulta_k))
   {
    $cont_k=$row[0];
   }
  $cont_k++;
  // fin
  // contador kardex valorizado
  $cont_v=0;
  $consulta_v=pg_query("select max(id_kardex) from kardex_valorizado");
  while($row=pg_fetch_row($consulta_v))
   {
    $cont_v=$row[0];
   }
  $cont_v++;
  // fin
  // consulta kardex valorizado
  $cantidad=0;
  $precio_total=0;
  $consulta2=pg_query("select * from kardex_valorizado where cod_productos =" . $id . " order by id_kardex asc");
  while($row=pg_fetch_row($consulta2))
   {
    $cantidad=$row[11];
    $precio_total=$row[8];
    
    
    $cantidad_entrada=$canti1;
    $precio_unitario_entrada=number_format($row[7], 2, '.', '');
    $precio_total_entrada=number_format($row[7]*$canti1, 2, '.', '');
    
    $cantidad_total=$cantidad-$canti1;
    $precio_total_total=number_format($precio_total+$precio_total_entrada, 2, '.', '');
    $precio_unitario_total=number_format($precio_total_total/$cantidad_total, 2, '.', '');
   }
  //guardar kardex valorizado
  pg_query("insert into kardex_valorizado values('$cont_v','$id','$_POST[fecha_actual]', '" . 'Mod Compra: ' . $_POST['serie'] . "','','" . $cantidad_entrada . "','" . $cantidad . "','" . $precio_unitario_entrada . "','" . $precio_total_entrada . "','','','" . $cantidad_total . "','2')");
  
  // fin  
  // guardar kardex
  pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '" . 'Mod F.C:' . $_POST['serie'] . "' ,'$canti1','$precio_unitario_entrada','$precio_total_entrada','$id','$cal1','1','','')");
  // fin
 }
// fin suma
$nelem=count($arreglo1);
// eliminar detalle productos
pg_query("DELETE FROM  detalle_factura_compra where id_factura_compra = '$_POST[id_factura_compra]'");
// fin  

for($i=0; $i<=$nelem; $i++)
 {
  
  // contador detalle factura compra
  $cont4=0;
  $consulta=pg_query("select max(id_detalle_compra) from detalle_factura_compra");
  while($row=pg_fetch_row($consulta))
   {
    $cont4=$row[0];
   }
  $cont4++;
  // fin
  
  // contador kardex
  $cont_k=0;
  $consulta_k=pg_query("select max(id_kardex) from kardex");
  while($row=pg_fetch_row($consulta_k))
   {
    $cont_k=$row[0];
   }
  $cont_k++;
  // fin
  
  // contador kardex valorizado
  $cont_v=0;
  $consulta_v=pg_query("select max(id_kardex) from kardex_valorizado");
  while($row=pg_fetch_row($consulta_v))
   {
    $cont_v=$row[0];
   }
  $cont_v++;
  // fin
  
  // guardar detalle_factura_compra
  pg_query("insert into detalle_factura_compra values('$cont4','$_POST[id_factura_compra]','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','Activo')");
  // fin 
  
  // modificar productos
  $consulta2=pg_query("select * from productos where cod_productos = '$arreglo1[$i]'");
  while($row=pg_fetch_row($consulta2))
   {
    $stock=$row[13];
   }
  $cal=$stock+$arreglo2[$i];
  
  pg_query("Update productos Set precio_compra='" . $arreglo3[$i] . "', stock='" . $cal . "' where cod_productos='" . $arreglo1[$i] . "'");
  // fin
  
  // consulta kardex valorizado
  $cantidad=0;
  $precio_total=0;
  $consulta2=pg_query("select * from kardex_valorizado where cod_productos = '$arreglo1[$i]' order by id_kardex asc");
  while($row=pg_fetch_row($consulta2))
   {
    $cantidad=$row[11];
    $precio_total=$row[8];
   }
  
  $cantidad_entrada=$arreglo2[$i];
  $precio_unitario_entrada=number_format($arreglo3[$i], 2, '.', '');
  $precio_total_entrada=number_format($arreglo2[$i]*$arreglo3[$i], 2, '.', '');
  
  $cantidad_total=$cantidad+$arreglo2[$i];
  $precio_total_total=number_format($precio_total+$precio_total_entrada, 2, '.', '');
  $precio_unitario_total=number_format($precio_total_total/$cantidad_total, 2, '.', '');
  
  pg_query("insert into kardex_valorizado values('$cont_v','$arreglo1[$i]','$_POST[fecha_actual]', '" . 'NCompra: ' . $_POST['serie'] . "','" . $cantidad_entrada . "','','" . $cantidad . "','" . $precio_unitario_entrada . "','" . $precio_total_entrada . "','','','" . $cantidad_total . "','2')");
  // fin
  
  // guardar kardex
  pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '" . 'N F.C:' . $_POST['serie'] . "' ,'$arreglo2[$i]','$arreglo3[$i]','$arreglo5[$i]','$arreglo1[$i]','$cal','1','','')");
  // fin
 }
$data=$_POST['id_factura_compra'];

echo $data;
?>
