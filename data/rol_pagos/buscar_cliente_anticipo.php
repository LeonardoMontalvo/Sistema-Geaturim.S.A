<?php

session_start();
include '../../procesos/base.php';
conectarse();
//$texto = $_GET['term'];

$arr_data=array();

$consulta = pg_query("select empleado.id_empleado,anio,mes,total from empleado, anticipos where empleado.id_empleado=anticipos.id_empleado and anticipos.estado='Activo' and empleado.estado='Activo' and empleado.id_empleado = '$_GET[com]' and anticipos.anio='$_GET[anio]' and anticipos.mes='$_GET[mes]' and anticipos.estado='Activo' order by id_anticipos desc limit 1");
while ($row = pg_fetch_row($consulta)) {
  $arr_data[]=$row[3];

 }
echo json_encode($arr_data);
?>
