<?php

session_start();
include '../../procesos/base.php';
conectarse();
$pv=$_SESSION["PV"];
error_reporting(0);
$data = "";
//////////////////////////  
//guardar cuentas contables/////
$empresa = pg_query("select id_parametro,valor from parametros where descripcion='IVA'");
while ($row = pg_fetch_row($empresa)) {
    $data = $data . $row[0];
    $data = $data . '*' . $row[1];
   
}
////////////////////////////////
echo $data;
?>
