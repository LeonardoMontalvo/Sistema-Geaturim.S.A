<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id=$_GET['com'];
$arr_data=array();

$consulta=pg_query("select  cargo.id_cargo from empleado,cargo where empleado.id_cargo=cargo.id_cargo and empleado.estado = 'Activo' and empleado.id_empleado = '" . $id . "'");
while($row=pg_fetch_row($consulta))
 {
  $arr_data[]=$row[0];

 }
echo json_encode($arr_data);
?>