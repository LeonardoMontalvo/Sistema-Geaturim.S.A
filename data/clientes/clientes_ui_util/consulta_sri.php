<?php
session_start();
session_write_close();


$ruc = $_GET["ruc"];
$ch = curl_init("https://srienlinea.sri.gob.ec/sri-catastro-sujeto-servicio-internet/rest/ConsolidadoContribuyente/obtenerPorNumerosRuc?&ruc=$ruc");
$curlop = [
    /* CURLOPT_RETURNTRANSFER => true, */   // return web page
    CURLOPT_HEADER         => false,  // don't return headers
    CURLOPT_FOLLOWLOCATION => true,   // follow redirects
    CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
    CURLOPT_ENCODING       => "",     // handle compressed
    CURLOPT_USERAGENT      => "test", // name of client
    CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
    CURLOPT_CONNECTTIMEOUT => 5,    // time-out on connect
    CURLOPT_TIMEOUT        => 5,    // time-out on response
];
curl_setopt_array($ch, $curlop);
$res = curl_exec($ch);
curl_close($ch);
