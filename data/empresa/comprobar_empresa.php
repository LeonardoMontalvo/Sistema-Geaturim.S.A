<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$data = "";
//////////////////////////  
//guardar cuentas contables/////
$empresa = pg_query("select * from empresa where id_empresa=1");
while ($row = pg_fetch_row($empresa)) {
    $data = $data . $row[0];
    $data = $data . '*' . $row[1];
    $data = $data . '*' . $row[2];
    $data = $data . '*' . $row[3];
    $data = $data . '*' . $row[4];
    $data = $data . '*' . $row[5];
    $data = $data . '*' . $row[6];
    $data = $data . '*' . $row[7];
    $data = $data . '*' . $row[8];
    $data = $data . '*' . $row[9];
    $data = $data . '*' . $row[10];
    $data = $data . '*' . $row[11];
    $data = $data . '*' . $row[12];
    $data = $data . '*' . $row[13];
    $data = $data . '*' . $row[14];
    $data = $data . '*' . $row[15];
    $data = $data . '*' . $row[16];
    $data = $data . '*' . $row[17];
    $data = $data . '*' . $row[18];
    $data = $data . '*' . $row[19];
    $data = $data . '*' . $row[20];
    $data = $data . '*' . $row[21];
    $data = $data . '*' . $row[22];
    $data = $data . '*' . $row[23];
    $data = $data . '*' . $row[24];
}
////////////////////////////////
echo $data;
?>
