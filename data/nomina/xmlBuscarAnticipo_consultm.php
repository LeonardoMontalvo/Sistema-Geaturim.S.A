<?php

session_start();
include '../../procesos/base.php';
conectarse();

$consulta = pg_query("
SELECT empleado.id_empleado,empleado.id_empleado,fecha_registro,nombres_empleado, valor, nombre_multa, valor,id_multa FROM multas, empleado where multas.id_empleado=empleado.id_empleado and multas.mes='$_GET[select_mesm]' and multas.id_empleado='$_GET[id_empleadom]' and multas.anio='$_GET[anio]' ");
$row = pg_fetch_row($consulta);
echo $row[0];
?>
