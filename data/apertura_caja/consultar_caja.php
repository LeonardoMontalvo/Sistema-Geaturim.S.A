<?php
include_once '../../procesos/base.php';
date_default_timezone_set('America/Guayaquil');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!function_exists('conectarse')) {
    conectarse();
}

error_reporting(0);

$fecha = date("Y-m-d");
$hora = date("H:i:s");
$idusuario = $_SESSION["id"];
$idpv = $_SESSION["PV"];

$consulta = $_GET["consulta"];

if (isset($consulta)) {
    switch ($consulta) {
        case "esta_caja_abierta":
            echo json_encode(cajaAbiertaDiaActual());
            break;
            /* case "caja_cerrada":
            break; */
    }
}

function cajaAbiertaDiaActual()
{
    /* global $fecha, $idusuario, $idpv;
    $sql = "
    select*from cierre_caja where 
    fecha_actual is not null
    and fecha_actual between '$fecha' and '$fecha'
    and fecha_cierre is null
    and id_usuario=$idusuario
    and id_empresa=$idpv;
    ";
    $res = pg_query($sql);
    if (pg_num_rows($res) <= 0) {
        return 0;
    } */
    return 1;
}
