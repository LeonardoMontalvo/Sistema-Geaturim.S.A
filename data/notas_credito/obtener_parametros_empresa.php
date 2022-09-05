<?php
require_once __DIR__ . '/../../procesos/configuracion.php';
session_start();

$conf = new Configuracion();
$esquema = $_COOKIE["esquema"];
$appFirma = $conf->getParametroEmpresa("app_firma");

$parametros = [
    "formato_imperesion_nota_credito" => $conf->getParametroEmpresa("formato_imperesion_nota_credito"),
];

echo json_encode($parametros);
