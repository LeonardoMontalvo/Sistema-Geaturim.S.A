<?php
session_start();
include '../../procesos/base.php';
conectarse();

$idusuario = $_POST["id_usuario"];
$idpv = $_POST["id_pv"];

$sql = "
delete from puntos_venta_usuario where id_punto_venta=$idpv and id_usuario=$idusuario;
";
var_dump($sql);
$res = pg_query($sql);
