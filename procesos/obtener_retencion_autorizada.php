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
    public static function obtenerDetallesRetencionSOAPMessageSRI($xmlfile)
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

        if ($infot->codDoc != '07') {
            return -1;
        }

        $infoComp = $xml2->infoCompRetencion;
        $claveAcceso = (string)$infot->claveAcceso;
        $estab = (string)$infot->estab;
        $ptoEmi = (string)$infot->ptoEmi;
        $secuencial = (string)$infot->secuencial;
        $fechaEmision = (string)$infoComp->fechaEmision;
        $ruc = (string)$infot->ruc;
        $razonSocial = (string)$infot->razonSocial;
        $nombreComercial = (string)$infot->nombreComercial;
        $dirMatriz = (string)$infot->dirMatriz;
        $identificacionSujetoRetenido = (string)$infoComp->identificacionSujetoRetenido;
        $infoRet = [
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
            "identificacionSujetoRetenido" => $identificacionSujetoRetenido
        ];
        $impuestosrt = $xml2->impuestos;
        $impuestos = [];
        if (count($impuestosrt) > 0) {
            foreach ($impuestosrt->children() as $impuesto) {
                array_push($impuestos, $impuesto);
            }
        }
        $docsSustento = [];
        if (empty($impuestos)) {
            $docsSustentort = $xml2->docsSustento;
            $docsSustento = [];
            foreach ($docsSustentort->children() as $doc) {
                $docSustento = [
                    "codDocSustento" => (string)$doc->codDocSustento,
                    "codSustento" => (string)$doc->codSustento,
                    "fechaEmisionDocSustento" => (string)$doc->fechaEmisionDocSustento,
                    "fechaRegistroContable" => (string)$doc->fechaRegistroContable,
                    "importeTotal" => (string)$doc->importeTotal,
                    "impuestosDocSustento" => (string)$doc->impuestosDocSustento,
                    "numAutDocSustento" => (string)$doc->numAutDocSustento,
                    "numDocSustento" => (string)$doc->numDocSustento,
                    "pagoLocExt" => (string)$doc->pagoLocExt,
                    "pagos" => (string)$doc->pagos,
                ];
                $retencionesrt = $xml2->docsSustento;
                $retenciones = [];
                foreach ($doc->retenciones->children() as $ret) {
                    array_push($retenciones, $ret);
                }
                $docSustento["retenciones"] = $retenciones;
                array_push($docsSustento, $docSustento);
            }
        }

        return [
            "infoRet" => $infoRet,
            "impuestos" => $impuestos,
            "docsSustento" => $docsSustento
        ];
    }
}

if (isset($_POST["clave"])) {
    $clave = $_POST["clave"];
    $res = consultarComprobante($clave);
    $inforet = UtilXml::obtenerDetallesRetencionSOAPMessageSRI($res);
    echo json_encode($inforet);
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
