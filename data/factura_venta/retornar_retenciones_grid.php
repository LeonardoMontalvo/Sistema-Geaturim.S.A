<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['com'];
$arr_data = array();

$consulta = pg_query(" select base_imponible,id_trete,porsentaje,valor_retenido,num_serie,num_serie  from retencion_fuente_factura_venta r, detallecomprobanteretencion_v dcr where r.id_factura ='".$id."' and r.id_retencion_fuente_factura_venta=dcr.id_retencion_fuente_factura_venta ");
while ($row = pg_fetch_row($consulta)) {
    
    $arr_data[] = $row[0];
    if($row[1]==1){
        $row[1]='RENTA';
    }else{
        $row[1]='IVA';
    }
    $arr_data[] = $row[1];    
    $arr_data[] = $row[2];
    $arr_data[] = $row[3];
    $arr_data[] = $row[4];
    $arr_data[] = $row[5];
   
}
echo json_encode($arr_data);
?>
