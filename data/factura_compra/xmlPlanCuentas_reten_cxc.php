<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['id'];
$arr_data = array();
$conpuntoresult = $_SESSION['PV'];
$consulta = pg_query("select * from plan_cuentas where cuenta='M'  and  ( descripcion like '%PRO%') and cuenta='M' and codigo_plan like '%2.1.03.01.01%'  ");
while ($row = pg_fetch_row($consulta)) {
    
    $arr_data[] = $row[0];   
    $arr_data[] = $row[1];    
    $arr_data[] = $row[2];
    $arr_data[] = $row[3];
 
   
}
echo json_encode($arr_data);
?>
