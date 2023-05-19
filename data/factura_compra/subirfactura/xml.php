<?php
session_start();
include __DIR__ . '/../../../admin/FirmaElectronica.php';
include __DIR__ . '/../../../admin/nusoap.php';
include __DIR__ . '/../../../phpseclib/Crypt/RSA.php';
include __DIR__ . '/../../../phpseclib/File/X509.php';
include __DIR__ . '/../../../phpseclib/Math/BigInteger.php';
include __DIR__ . '/../../../procesos/base.php';

$conexion = conectarse();

//var_dump(file_get_contents('php://input'));

class UtilXml
{
    public static function obtenerDetallesFactura($xmlfile)
    {
        $xml = simplexml_load_file($xmlfile);
        if ($xml->count() == 0) {
            return false;
        }
        $comprobante = $xml->xpath("comprobante")[0];
        $xml2 = simplexml_load_string($comprobante);
        return self::obtenerInfoXml($xml2);
    }
    public static function obtenerDetallesFacturaSOAPMessage($xmlfile)
    {
        $xml = simplexml_load_file($xmlfile);
        $comprobante = $xml
            ->children('soap', true)
            ->Body
            ->children('ns2', true)
            ->autorizacionComprobanteResponse
            ->children()
            ->RespuestaAutorizacionComprobante
            ->xpath("autorizaciones/autorizacion/comprobante");

        $xml2 = simplexml_load_string($comprobante[0]);

        return self::obtenerInfoXml($xml2);
    }
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
            "dirMatriz" => $dirMatriz
        ];
        $productos = array();
        foreach ($detalles->children() as $detalle) {
            $impuestos = [];
            foreach ($detalle->impuestos->children() as $impuesto) {
                $infoimpuesto = [
                    "codigo" => (string)$impuesto->codigo,
                    "codigoPorcentaje" => (string)$impuesto->codigoPorcentaje,
                    "tarifa" => (string)$impuesto->tarifa,
                    "baseImponible" => (string)$impuesto->baseImponible,
                    "valor" => (string)$impuesto->valor,
                ];
                array_push($impuestos, $infoimpuesto);
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

if (!empty($_FILES["file"])) {
    $productos = UtilXml::obtenerDetallesFactura($_FILES["file"]["tmp_name"]);
    if (!$productos) {
        $productos = UtilXml::obtenerDetallesFacturaSOAPMessage($_FILES["file"]["tmp_name"]);
    }
    echo json_encode($productos);
} else if (isset($_POST["clave"])) {
    $clave = $_POST["clave"];
    $res = consultarComprobante($clave);
    $productos = UtilXml::obtenerDetallesFacturaSOAPMessageSRI($res);
    echo json_encode($productos);
}

function obtenerAmbiente()
{
    global $conexion;
    $consulta_ambiente = pg_query($conexion, "select codigo_ambi from ambiente where estado_ambi = 'Activo' ");
    while ($row = pg_fetch_row($consulta_ambiente)) {
        $ambiente = $row[0];
    }
    return $ambiente;
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
