<?php
session_start();
include '../../procesos/base.php';
conectarse();

$idusuario = $_POST["id_usuario"];
$idpv = $_POST["id_pv"];

$sql = "
INSERT INTO puntos_venta_usuario(
    id_punto_venta, id_usuario)
VALUES ($idpv,$idusuario);
";

$res = pg_query($sql);
