<?php

session_start();
include '../../procesos/base.php';
conectarse();
$data = "";
$consulta = pg_query("SELECT total,id_multa,fecha_registro,nombres_empleado, valor, nombre_multa, valor,id_multa,total,multas.estado FROM multas, empleado where multas.id_empleado=empleado.id_empleado and multas.mes='$_GET[select_mesm]' and multas.id_empleado='$_GET[id_empleadom]' and multas.anio='$_GET[anio]'  limit 1");
while ($row = pg_fetch_row($consulta)) {
    $data = $data . $row[0];
    $data = $data . '*' . $row[1];
    $data = $data . '*' . $row[9];
}
echo $data;
?>
