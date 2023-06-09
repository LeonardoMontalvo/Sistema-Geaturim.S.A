<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
////////////////todo
date_default_timezone_set('America/Guayaquil');
$now = time();
$num = date("w");
$WeekMon = mktime(0, 0, 0, date("m", $now), date("d", $now) - $sub, date("Y", $now));    //monday week begin calculation
$todayh = getdate($WeekMon); //monday week begin reconver
$d = $todayh['mday'];
$m = $todayh['mon'];
$y = $todayh['year'];
$fecha = "$d/$m/$y";
$consulta = pg_query("SELECT comprobante,denominacion.id_denominacion,denominacion, cantidad, cierre_caja.valor
FROM cierre_caja, denominacion
 where cierre_caja.id_denominacion=denominacion.id_denominacion and fecha_actual='$fecha' and id_usuario='$_SESSION[id]' and cierre_caja.estado='Activo'
 order by cierre_caja.id_denominacion desc");
$row = pg_fetch_row($consulta);
echo $row[0];
?>
