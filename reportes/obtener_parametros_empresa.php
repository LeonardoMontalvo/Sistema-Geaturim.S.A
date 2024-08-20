<?php
//require_once __DIR__ . '../procesos/configuracion.php';
include '../procesos/configuracion.php';
session_start();

$conf = new Configuracion();
$esquema = $_COOKIE["esquema"];
$appFirma = $conf->getParametroEmpresa("app_firma");

$parametros = [
    "formato_imperesion_diario_caja" =>  get_formato_impresion("formato_imperesion_diario_caja"),
  
];

function get_formato_impresion($nombreparam)
{
    global $conf;
    $val = $conf->getParametroPuntoVenta($_SESSION["PV"], $nombreparam);
    if (empty($val)) {
        $val = $conf->getParametroEmpresa($nombreparam);
    }
    return $val;
}

echo json_encode($parametros);
