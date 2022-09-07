<?php
include_once __DIR__ . "/../../procesos/configuracion.php";
$config = new Configuracion();
$esquema = $_GET["esquema"];
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$baseurl = explode("data/", $actual_link)[0];
$urlesquema = $baseurl . "data/" . $config->getPrefijoUrlEsquema() . "$esquema";
echo $urlesquema;
