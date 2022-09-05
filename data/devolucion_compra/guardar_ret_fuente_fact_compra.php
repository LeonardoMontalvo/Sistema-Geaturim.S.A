<?php

session_start();
include '../../procesos/base.php';
include '../../reportes/reten_elect_gasto.php';
//include '../../reportes/reten_elect_consulta.php';
include '../../firma/firma.php';
include '../../firma/xades.php';
include 'generarPDFRetenGAS.php';
include '../../admin/correo.php';
conectarse();
error_reporting(0);
$datosimprimir=0;
$data=0;
date_default_timezone_set('America/Guayaquil');


//contador factura compra
$cont1=0;
$consulta=pg_query("select max(id_retencion_fuente_factura_compra) from retencion_fuente_factura_compra");
while($row=pg_fetch_row($consulta))
 {
  $cont1=$row[0];
 }
$cont1++;



$datos=0;
$valoreten=0;
$resultreten=0;
$fecha=date('Y-m-d', time());
$hora=date('h:i:s A', time());

  if($data!=2){
    

    pg_query("insert into retencion_fuente_factura_compra values('".$cont1."', '$_POST[id_gastos]', '$_POST[id_retencion_fuente]','$_POST[fecha_actual]','".$hora."','$_POST[valor_factura]','$_POST[iva_factura]','$_POST[valor_retencion]', '$_POST[autorizacion_ret]','$_POST[serie_retencion]','g' ,'$_POST[id_gastos]','','2','')");
  
 $data=1;
  }
  
 echo $data;
 
?>
