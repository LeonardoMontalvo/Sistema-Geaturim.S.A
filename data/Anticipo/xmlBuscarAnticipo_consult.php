<?php

session_start();
include '../../procesos/base.php';
conectarse();

$consulta = pg_query("SELECT empleado.id_empleado,empleado.id_empleado,fecha_anticipo,nombres_empleado, valor, detalle, valor,id_anticipos FROM anticipos, empleado where anticipos.id_empleado=empleado.id_empleado and anticipos.mes='$_GET[select_mes]' and empleado.id_empleado='$_GET[id_empleado]' and empleado.estado='Activo' and anticipos.anio='$_GET[anio]'  ");
$row = pg_fetch_row($consulta);
echo $row[0];
?>
