<?php

session_start();
include '../../procesos/base.php';
conectarse();
$data = "";
$consulta = pg_query("SELECT anticipos.estado FROM anticipos, empleado where anticipos.id_empleado=empleado.id_empleado and anticipos.mes='$_GET[select_mes]' and empleado.id_empleado='$_GET[id_empleado]' and anticipos.anio='$_GET[anio]'  and empleado.estado='Activo' ");
while ($row = pg_fetch_row($consulta)) {
   
    $data = $data . '*' . $row[0];
  
}
////////////////////////////////
echo $data;
?>
