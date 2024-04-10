<?php
require_once __DIR__ . '/../../procesos/configuracion.php';
session_start();

$conf = new Configuracion();
$esquema = $_COOKIE["esquema"];
$appFirma = $conf->getParametroEmpresa("app_firma");

$parametros = [
    "formato_imperesion_factura" => $conf->getParametroEmpresa("formato_imperesion_factura"),
    "formato_imperesion_nota" => $conf->getParametroEmpresa("formato_imperesion_nota"),
    "autorizar_fac_auto" => $conf->getParametroEmpresa("autorizar_fac_auto"),
];

echo json_encode($parametros);
