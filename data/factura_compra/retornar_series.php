<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['com'];
$arr_data = array();

$consulta = pg_query("select * from series_compra sr, factura_compra fc where fc.id_factura_compra ='".$id."' and sr.id_factura_compra=fc.id_factura_compra and sr.observacion='ACT'");
while ($row = pg_fetch_row($consulta)) {
    
    $arr_data[] = $row[3];
 
}
echo json_encode($arr_data);
?>
