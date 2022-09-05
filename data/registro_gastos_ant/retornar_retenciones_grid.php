<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['com'];
$arr_data = array();

$consulta = pg_query("select * from retencion_fuente_factura_compra r, detallecomprobanteretencion dcr where r.id_factura ='".$id."' and r.id_retencion_fuente_factura_compra=dcr.id_retencion_fuente_factura_compra and r.id_gastos='10'");
while ($row = pg_fetch_row($consulta)) {
    
    $arr_data[] = $row[18];
    if($row[19]==1){
        $row[19]='RENTA';
    }else{
        $row[19]='IVA';
    }
    $arr_data[] = $row[19];    
    $arr_data[] = $row[20];
    $arr_data[] = $row[21];
    $arr_data[] = $row[8];
    $arr_data[] = $row[9];
   
}
echo json_encode($arr_data);
?>
