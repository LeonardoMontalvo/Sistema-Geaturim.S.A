<?php

session_start();
//error_reporting(0);

include '../../procesos/base.php';
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
$search = $_GET['_search'];
date_default_timezone_set('America/Guayaquil');


if (!$sidx)
    $sidx = 1;
////////////////todo
$sub='';
$now = time();
$num = date("w");
$WeekMon = mktime(0, 0, 0, date("m", $now), date("d", $now) - $sub, date("Y", $now));    //monday week begin calculation
$todayh = getdate($WeekMon); //monday week begin reconver
$d = $todayh['mday'];
$m = $todayh['mon'];
$y = $todayh['year'];
$fecha = "$d/$m/$y";
//echo "SELECT COUNT(*) AS count FROM cierre_caja where fecha_actual='$fecha' and id_usuario='$_SESSION[id]'";

$result = pg_query("SELECT COUNT(*) AS count FROM cierre_caja where fecha_actual='$fecha' and id_usuario='$_SESSION[id]'");
$row = pg_fetch_row($result);


$count = $row[0];
if ($count > 0 && $limit > 0) {
    $total_pages = ceil($count / $limit);
} else {
    $total_pages = 0;
}
if ($page > $total_pages)
    $page = $total_pages;
$start = $limit * $page - $limit;
if ($start < 0)
    $start = 0;


//echo "SELECT id_cierre_caja,denominacion.id_denominacion,denominacion, cantidad, cierre_caja.valor
//FROM cierre_caja, denominacion
// where cierre_caja.id_denominacion=denominacion.id_denominacion and fecha_actual='$fecha' and id_usuario='$_SESSION[id]' 
//";

$SQL = "SELECT id_cierre_caja,denominacion.id_denominacion,denominacion, cantidad, cierre_caja.valor
FROM cierre_caja, denominacion
 where cierre_caja.id_denominacion=denominacion.id_denominacion and fecha_actual='$fecha' and id_usuario='$_SESSION[id]' and cierre_caja.estado='Activo'
order by

  $sidx $sord offset $start limit $limit";

$datos_consulta="";
$result = pg_query($SQL);
while ($row1 = pg_fetch_row($result)) {
    $datos_consulta=$row1[0];
}

if($datos_consulta!=""){
    
$result = pg_query($SQL);

header("Content-type: text/xml;charset=utf-8");
$s = "<?xml version='1.0' encoding='utf-8'?>";
$s .= "<rows>";
$s .= "<page>" . $page . "</page>";
$s .= "<total>" . $total_pages . "</total>";
$s .= "<records>" . $count . "</records>";
while ($row = pg_fetch_row($result)) {

if($row[0]!=""){
   $s .= "<row id='" . $row[0] . "'>";
    $s .= "<cell></cell>";

    $s .= "<cell>" . $row[0] . "</cell>";
    $s .= "<cell>" . $row[1] . "</cell>";
    $s .= "<cell>" . $row[2] . "</cell>";
    $s .= "<cell>" . $row[3] . "</cell>";
    $s .= "<cell>" . $row[4] . "</cell>";  
}
    
 


    $s .= "</row>";
}
$s .= "</rows>";
}
echo $s;
?>