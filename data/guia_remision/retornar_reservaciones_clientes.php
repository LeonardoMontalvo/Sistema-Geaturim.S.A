<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['id1'];
$arr_data = array();

$consulta = pg_query("select 
C.id_cliente,C.identificacion, C.nombres_cli, C.direccion_cli, C.celular, C.correo  
from reservaciones R, clientes C 
where R.id_cliente=C.id_cliente 
and R.estado='Activo' 
and R.id_reservacion='" . $id . "'");
while ($row = pg_fetch_row($consulta)) {
    $arr_data[] = $row[0];
    $arr_data[] = $row[1];
    $arr_data[] = $row[2];
    $arr_data[] = $row[3];
    $arr_data[] = $row[4];
    $arr_data[] = $row[5];
}
echo json_encode($arr_data);
?>
