<?php

session_start();
include '../../procesos/base.php';
conectarse();
$data = "";
//echo ''."SELECT total,id_anticipos,fecha_anticipo,nombres_empleado, valor, detalle, valor,id_anticipos,total, anticipos.estado FROM anticipos, empleado where anticipos.id_empleado=empleado.id_empleado and anticipos.mes='$_GET[select_mes]' and empleado.id_empleado='$_GET[id_empleado]' and anticipos.anio='$_GET[anio]'  and empleado.estado='Activo' order by id_anticipos desc limit 1  ";
$consulta = pg_query("SELECT total,id_anticipos,fecha_anticipo,nombres_empleado, valor, detalle, valor,id_anticipos,total, anticipos.estado FROM anticipos, empleado where anticipos.id_empleado=empleado.id_empleado and anticipos.mes='$_GET[select_mes]' and empleado.id_empleado='$_GET[id_empleado]' and anticipos.anio='$_GET[anio]'  and empleado.estado='Activo' order by id_anticipos desc limit 1  ");
while ($row = pg_fetch_row($consulta)) {
    $data = $data . $row[0];
    $data = $data . '*' . $row[1];
    $data = $data . '*' . $row[9];
}

echo $data;
?>
