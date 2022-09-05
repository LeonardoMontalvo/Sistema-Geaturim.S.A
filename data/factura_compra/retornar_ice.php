<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['com'];
$arr_data = array();

$consulta = pg_query("


SELECT ice, irbp
  FROM ice_factura_compra where id_factura_compra ='".$id."'
");
while ($row = pg_fetch_row($consulta)) {
    
   
    $arr_data[] = $row[0];    
    $arr_data[] = $row[1];
  
   
}
echo json_encode($arr_data);
?>
