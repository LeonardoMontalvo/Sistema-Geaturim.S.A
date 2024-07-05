<?php
require_once __DIR__ . '/../../procesos/configuracion.php';
session_start();

$conf = new Configuracion();
$esquema = $_COOKIE["esquema"];
$appFirma = $conf->getParametroEmpresa("app_firma");

$parametros = [
    "formato_imperesion_factura_compra" => get_formato_impresion("formato_imperesion_factura_compra"),
    "formato_imperesion_retencion_compra" => get_formato_impresion("formato_imperesion_retencion_compra"),
    "agente_reten" => $conf->getParametroEmpresa("agente_reten"),
 "check_agente_reten" => $conf->getParametroEmpresa("check_agente_reten"),
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
