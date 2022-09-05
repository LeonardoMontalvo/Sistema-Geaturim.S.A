<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
  // datos detalle factura
    $campo1 = $_POST['campo1'];
    $campo2 = $_POST['campo2'];
    $campo3 = $_POST['campo3'];
    $campo4 = $_POST['campo4'];
    $campo5 = $_POST['campo5'];
    // fin 

    // agregar detalle_factura_venta
    $arreglo1 = explode('|', $campo1);
    $arreglo2 = explode('|', $campo2);
    $arreglo3 = explode('|', $campo3);
    $arreglo4 = explode('|', $campo4);
    $arreglo5 = explode('|', $campo5);
     $nelem = count($arreglo1);
    //fin
    // modificar factura compra
      $cal = $_POST[valor]; 
    $valor = number_format($cal, 2, '.', '');
    $cal = $_POST[iva]; 
    $iva = number_format($cal, 2, '.', '');
    $cal = $_POST[subtotal]; 
    $subtotal = number_format($cal, 2, '.', '');
          for ($i = 0; $i <= $nelem; $i++) {
    pg_query("Update gastos Set  id_proveedor = '$_POST[id_proveedor]', num_factura = '$_POST[factura]' , subtotal = '$subtotal', iva = '$iva', total = '$valor'
    , fecha_emision = '$_POST[fecha_emision]' , descripcion = '$_POST[descripcion]' , banco = '$_POST[banco]' , num_autorizacion = '$_POST[autorizacion]'
    , tarifa0 = '$_POST[tarifa0]', tarifa12 = '$_POST[tarifa12]', iva_compra = '$_POST[iva]', descuento_compra = '$_POST[desc]', total_compra = '$valor'
    , cod_productos = '" . $arreglo1[$i] . "', cantidad = '" . $arreglo2[$i] . "', precio_comprat = '" . $arreglo3[$i] . "', descuento_comprat = '" . $arreglo4[$i] . "' , total_comprat = '" . $arreglo5[$i] . "' where id_gastos = '$_POST[comprobante]'");
    // fin
 $data = $_POST['comprobante'];
 
          }

   

echo $data;
?>
