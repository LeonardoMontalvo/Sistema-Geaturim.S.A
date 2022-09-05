<?php

session_start();
include '../../procesos/base.php';
conectarse();
//$texto = $_GET['term'];

$arr_data=array();

$consulta = pg_query("select empleado.id_empleado,anio,mes,total from empleado, horas_extras where empleado.id_empleado=horas_extras.id_empleado and horas_extras.estado='Activo' and empleado.estado='Activo' and empleado.id_empleado = '$_GET[com]' and horas_extras.anio='$_GET[anio]' and horas_extras.mes='$_GET[mes]'");
while ($row = pg_fetch_row($consulta)) {
  $arr_data[]=$row[3];

 }
echo json_encode($arr_data);
?>
