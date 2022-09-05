<?php

session_start();
include '../../procesos/base.php';
conectarse();
$data = "";
$consulta = pg_query("SELECT total,id_horas_extras,fecha_registro,nombres_empleado, valor, periodo, valor,id_horas_extras,total FROM horas_extras, empleado where horas_extras.id_empleado=empleado.id_empleado and horas_extras.mes='$_GET[select_mesh]' and horas_extras.id_empleado='$_GET[id_empleadoh]' and horas_extras.anio='$_GET[anio]' and horas_extras.estado='Activo' limit 1");
while ($row = pg_fetch_row($consulta)) {
    $data = $data . $row[0];
    $data = $data . '*' . $row[1];
   
}
echo $data;
?>
