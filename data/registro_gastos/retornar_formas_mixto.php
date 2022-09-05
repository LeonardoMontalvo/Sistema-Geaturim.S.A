<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['com'];
$arr_data = array();
$conpuntoresult = $_SESSION['PV'];
$consulta = pg_query("select adelanto,meses,monto_credito,fecha_dias from pagos_venta where id_factura_venta='".$id."'  and id_empresa=$conpuntoresult");
while ($row = pg_fetch_row($consulta)) {
    
    $arr_data[] = $row[0];   
    $arr_data[] = $row[1];    
    $arr_data[] = $row[2];
    $arr_data[] = $row[3];
 
   
}
echo json_encode($arr_data);
?>
