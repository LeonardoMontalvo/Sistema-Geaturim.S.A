<?php

session_start();
include '../../procesos/base.php';
conectarse();

$consulta = pg_query("SELECT empleado.id_empleado,empleado.id_empleado,fecha_registro,nombres_empleado, valor, periodo, valor,horas_extras FROM horas_extras, empleado where horas_extras.id_empleado=empleado.id_empleado and horas_extras.mes='$_GET[select_mesh]' and horas_extras.id_empleado='$_GET[id_empleadoh]' and horas_extras.anio='$_GET[anio]' ");
$row = pg_fetch_row($consulta);
echo $row[0];
?>
