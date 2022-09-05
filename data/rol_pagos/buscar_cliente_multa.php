<?php

session_start();
include '../../procesos/base.php';
conectarse();
//$texto = $_GET['term'];

$arr_data=array();

$consulta = pg_query("select empleado.id_empleado,anio,mes,total from empleado, multas where empleado.id_empleado=multas.id_empleado and multas.estado='Activo' and empleado.estado='Activo' and empleado.id_empleado = '$_GET[com]' and multas.anio='$_GET[anio]' and multas.mes='$_GET[mes]' and multas.estado='Activo'");
while ($row = pg_fetch_row($consulta)) {
  $arr_data[]=$row[3];

 }
echo json_encode($arr_data);
?>
