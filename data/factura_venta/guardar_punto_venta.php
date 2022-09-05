<?php

//

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$cont = 0;
$repe = 0;

$now = time();
$num = date("w");
if ($num == 0) {
    $sub = 6;
} else {
    $sub = ($num - 1);
}
$WeekMon = mktime(0, 0, 0, date("m", $now), date("d", $now) - $sub, date("Y", $now));    //monday week begin calculation
$todayh = getdate($WeekMon); //monday week begin reconvert

$d = $todayh[mday];
$m = $todayh[mon];
$y = $todayh[year];
$fecha = "$d/$m/$y"; //getdate converted day
/////////////////////////////////////////////////// 


$cont1 = 0;
$consulta = pg_query("select max(id_punto_venta_empresa) from punto_venta_empresa");
while ($row = pg_fetch_row($consulta)) {
    $cont1 = $row[0];
}
$cont1++;

pg_query("insert into punto_venta_empresa values('$cont1','$_GET[id]','$_SESSION[id]','Inactivo','$fecha')");

pg_query("update punto_venta set estado='Inactivo', fecha_actual_punto='$fecha' , id_usuario = '$_SESSION[id]' where id_punto_venta=" . $_GET['id']);

$data = 1;

echo $data;
?>