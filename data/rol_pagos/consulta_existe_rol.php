<?php

session_start();
include '../../procesos/base.php';

conectarse();
error_reporting(0);
date_default_timezone_set('America/Guayaquil');

$conpuntoresult = $_SESSION['PV'];

$conpunto = 0;
$consultapunto = pg_query("select * from rol_pagos where mes='$_POST[select_mes]' and anio='$_POST[slct_anio_cf]' and id_empresa='$conpuntoresult'");
while ($row = pg_fetch_row($consultapunto)) {
    $conpunto = $row[0];
}
if ($conpunto == 0) {

    $data = 1; // no hay datos pueden agregar
} else {
    $data = 11; //si hay datos  
}








echo $data;
?>