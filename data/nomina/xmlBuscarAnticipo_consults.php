<?php

session_start();
include '../../procesos/base.php';
conectarse();

$consulta = pg_query("SELECT decimos.id_empleado FROM decimos, empleado where decimos.id_empleado=empleado.id_empleado and decimos.mes='$_GET[select_mess]' and decimos.id_empleado='$_GET[id_empleados]' and decimos.anio='$_GET[anio]' ");
$row = pg_fetch_row($consulta);
echo $row[0];
?>
