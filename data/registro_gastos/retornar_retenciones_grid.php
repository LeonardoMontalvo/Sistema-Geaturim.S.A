<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['com'];
$arr_data = array();

$consulta = pg_query("select base_imponible,dcr.id_trete,porsentaje,valor_retenido,autorizacion,num_serie,r.id_retencion_fuente_factura_compra,estado_reten  from retencion_fuente_factura_compra r, detallecomprobanteretencion dcr where r.id_factura ='".$id."' and r.id_retencion_fuente_factura_compra=dcr.id_retencion_fuente_factura_compra and r.id_gastos=10");
while ($row = pg_fetch_row($consulta)) {
    
    $arr_data[] = $row[0];//base_imponible
    if($row[1]==1){//id_trete
        $row[1]='RENTA';
    }else{
        $row[1]='IVA';
    }
    $arr_data[] = $row[1];  //id_trete  
    $arr_data[] = $row[2];//porsentaje
    $arr_data[] = $row[3];//valor_retenido
    $arr_data[] = $row[4];//autorizacion
    $arr_data[] = $row[5];//num_serie
  
}
echo json_encode($arr_data);
?>
