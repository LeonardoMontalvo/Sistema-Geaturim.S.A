<?php
require_once __DIR__ . '/../../procesos/configuracion.php';
session_start();

$conf = new Configuracion();
$esquema = $_COOKIE["esquema"];
$appFirma = $conf->getParametroEmpresa("app_firma");

$parametros = [
    "formato_imperesion_factura_compra" => $conf->getParametroEmpresa("formato_imperesion_factura_compra"),
    "formato_imperesion_retencion_compra" => $conf->getParametroEmpresa("formato_imperesion_retencion_compra"),
    "agente_reten" => $conf->getParametroEmpresa("agente_reten"),

];

echo json_encode($parametros);
