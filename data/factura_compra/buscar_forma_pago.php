<?php

session_start();
include '../../procesos/base.php';
conectarse();
$data="Ninguno";
$cod=$_POST['id'];

$consulta = pg_query("SELECT descripcion from forma_pagos where codigo='".$cod."'");
$row = pg_fetch_row($consulta);
$data=$row[0];

echo $data;
?>
