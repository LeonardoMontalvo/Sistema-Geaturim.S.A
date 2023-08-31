<?php
session_start();
include __DIR__ . '/../admin/FirmaElectronica.php';
include __DIR__ . '/../admin/nusoap.php';
include __DIR__ . '/../phpseclib/Crypt/RSA.php';
include __DIR__ . '/../phpseclib/File/X509.php';
include __DIR__ . '/../phpseclib/Math/BigInteger.php';
include __DIR__ . '/base.php';

$conexion = conectarse();

class UtilXml
{
    public static function obtenerDetallesNotaCreSOAPMessageSRI($xmlfile)
    {
        $autorizacion = $xmlfile->RespuestaAutorizacionComprobante->autorizaciones->autorizacion;
        $comprobante = $autorizacion->comprobante;
        $xml2 = simplexml_load_string($comprobante);
        return self::obtenerInfoXml($xml2, $autorizacion->fechaAutorizacion);
    }

    private static function obtenerInfoXml($xmlobj, $fechaAut)
    {
        $xml2 = $xmlobj;
        $infot = $xml2->infoTributaria;

        if ($infot->codDoc != '04') {
            return -1;
        }

        $infoComp = $xml2->infoNotaCredito;
        $claveAcceso = (string)$infot->claveAcceso;
        $estab = (string)$infot->estab;
        $ptoEmi = (string)$infot->ptoEmi;
        $secuencial = (string)$infot->secuencial;
        $fechaEmision = (string)$infoComp->fechaEmision;
        $ruc = (string)$infot->ruc;
        $razonSocial = (string)$infot->razonSocial;
        $nombreComercial = (string)$infot->nombreComercial;
        $dirMatriz = (string)$infot->dirMatriz;
        $identificacionComprador = (string)$infoComp->identificacionComprador;
        $codDocModificado = (string)$infoComp->codDocModificado;
        $numDocModificado = (string)$infoComp->numDocModificado;
        $detalles = $xml2->detalles;

        if ($codDocModificado != '01') {
            return -2;
        }

        $infoNotaC = [
            "claveAcceso" => $claveAcceso,
            "estab" => $estab,
            "ptoEmi" => $ptoEmi,
            "secuencial" => $secuencial,
            "fechaEmision" => $fechaEmision,
            "ruc" => $ruc,
            "razonSocial" => $razonSocial,
            "nombreComercial" => $nombreComercial,
            "dirMatriz" => $dirMatriz,
            "fechaAutorizacion" => $fechaAut,
            "identificacionComprador" => $identificacionComprador,
            "codDocModificado" => $codDocModificado,
            "numDocModificado" => $numDocModificado
        ];

        return [
            "infoNotaC" => $infoNotaC,
            "detalles" => $detalles
        ];
    }
}

if (isset($_POST["clave"])) {
    $clave = $_POST["clave"];
    $res = consultarComprobante($clave);
    $infoNota = UtilXml::obtenerDetallesNotaCreSOAPMessageSRI($res);
    echo json_encode($infoNota);
}

function consultarComprobante($clave)
{
    //if ($ambiente == '1') {
    //$slAutorWs = "https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl";
    //} else {
    $slAutorWs = "https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl";
    //}
    $olClient = new SoapClient($slAutorWs, array('encoding' => 'UTF-8'));
    $olResp = $olClient->autorizacionComprobante(array('claveAccesoComprobante' => $clave));
    return $olResp;
}
