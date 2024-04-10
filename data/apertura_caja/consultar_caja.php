<?php

include_once '../../procesos/base.php';
date_default_timezone_set('America/Guayaquil');
require_once __DIR__ . '/../../procesos/configuracion.php';

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
    $conf = new Configuracion();
    $apertura_caja = $conf->getParametroEmpresa("apertura_caja");

    global $fecha, $idusuario, $idpv;
    $sqlac = "select apertura_caja from acciones_usuario where id_usuario=$idusuario";

    $res_ac = 'b';
    $res = pg_query($sqlac);
    while ($row = pg_fetch_row($res)) {

        $res_ac = $row[0];
    }
    //echo 'fff//'.$res_ac;
    if ($res_ac == "t") {


        //        if ($apertura_caja == "1") {

        global $fecha, $idusuario, $idpv;
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
        }
        return 1;
    } else {
        global $fecha, $idusuario, $idpv;
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
            return 1;
        }
        return 1;
    }
}
