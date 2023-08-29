<?php
session_start();
include __DIR__ . '/../admin/FirmaElectronica.php';
include __DIR__ . '/../admin/nusoap.php';
include __DIR__ . '/../phpseclib/Crypt/RSA.php';
include __DIR__ . '/../phpseclib/File/X509.php';
include __DIR__ . '/../phpseclib/Math/BigInteger.php';
include __DIR__ . '/base.php';

$conexion = conectarse();

//var_dump(file_get_contents('php://input'));

class UtilXml
{
    public static function obtenerDetallesFacturaSOAPMessageSRI($xmlfile)
    {
        $autorizacion = $xmlfile->RespuestaAutorizacionComprobante->autorizaciones->autorizacion;
        $comprobante = $autorizacion->comprobante;
        $xml2 = simplexml_load_string($comprobante);
        return self::obtenerInfoXml($xml2);
    }
    private static function obtenerInfoXml($xmlobj)
    {
        $xml2 = $xmlobj;
        $infot = $xml2->infoTributaria;

        if ($infot->codDoc != '01') {
            return -1;
        }

        $infof = $xml2->infoFactura;
        $claveAcceso = (string)$infot->claveAcceso;
        $estab = (string)$infot->estab;
        $ptoEmi = (string)$infot->ptoEmi;
        $secuencial = (string)$infot->secuencial;
        $fechaEmision = (string)$infof->fechaEmision;
        $ruc = (string)$infot->ruc;
        $razonSocial = (string)$infot->razonSocial;
        $nombreComercial = (string)$infot->nombreComercial;
        $dirMatriz = (string)$infot->dirMatriz;
        $totalSinImpuestos = (string)$infof->totalSinImpuestos;
        $totalDescuento = (string)$infof->totalDescuento;
        $tipoIdentificacionComprador = (string)$infof->tipoIdentificacionComprador;
        $razonSocialComprador = (string)$infof->razonSocialComprador;
        $identificacionComprador = (string)$infof->identificacionComprador;
        $direccionComprador = (string)$infof->direccionComprador;
        $importeTotal = (string)$infof->importeTotal;
        $totalConImpuestos = [];
        foreach ($infof->totalConImpuestos->children() as $tImpuesto) {
            array_push($totalConImpuestos, $tImpuesto);
        }

        $detalles = $xml2->detalles;
        $infofac = [
            "claveAcceso" => $claveAcceso,
            "estab" => $estab,
            "ptoEmi" => $ptoEmi,
            "secuencial" => $secuencial,
            "fechaEmision" => $fechaEmision,
            "ruc" => $ruc,
            "razonSocial" => $razonSocial,
            "nombreComercial" => $nombreComercial,
            "dirMatriz" => $dirMatriz,
            "totalSinImpuestos" => $totalSinImpuestos,
            "totalDescuento" => $totalDescuento,
            "totalConImpuestos" => $totalConImpuestos,
            "tipoIdentificacionComprador" => $tipoIdentificacionComprador,
            "razonSocialComprador" => $razonSocialComprador,
            "identificacionComprador" => $identificacionComprador,
            "direccionComprador" => $direccionComprador,
            "importeTotal" => $importeTotal,
        ];
        $productos = array();
        foreach ($detalles->children() as $detalle) {
            $impuestos = [];
            foreach ($detalle->impuestos->children() as $impuesto) {
                array_push($impuestos, $impuesto);
            }
            $infoprod = [
                "codigoPrincipal" => (string)str_replace(" ", "", $detalle->codigoPrincipal),
                "codigoAuxiliar" => (string)$detalle->codigoAuxiliar,
                "descripcion" => (string)$detalle->descripcion,
                "cantidad" => (string)$detalle->cantidad,
                "precioUnitario" => (string)$detalle->precioUnitario,
                "descuento" => (string)$detalle->descuento,
                "precioTotalSinImpuesto" => (string)$detalle->precioTotalSinImpuesto,
                "impuestos" => $impuestos
            ];
            array_push($productos, $infoprod);
        }
        return [
            "infoFac" => $infofac,
            "productos" => $productos
        ];
    }
}

if (isset($_POST["clave"])) {
    $clave = $_POST["clave"];
    $res = consultarComprobante($clave);
    $productos = UtilXml::obtenerDetallesFacturaSOAPMessageSRI($res);
    echo json_encode($productos);
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
