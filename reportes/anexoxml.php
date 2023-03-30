<?php
session_start();
include '../procesos/base.php';
include '../procesos/funciones.php';
conectarse();
error_reporting(0);

$esquema = $_COOKIE["esquema"];

$anioDec = $_GET['anio'];
$mesDec = $_GET['mes'];
$nombreArchivo = $_GET['nombrearchivo'];

$xml = new DomDocument('1.0', 'UTF-8');
$xml->xmlStandalone = false;
$root = $xml->createElement('iva');
$root = $xml->appendChild($root);
$id = 0;
$razon = 0;
$ruc = 0;
$empresa = pg_query("select id_empresa, ruc_empresa, nombre_empresa from empresa");
while ($fila = pg_fetch_row($empresa)) {
    $id = $fila[0];
    $ruc = $fila[1];
    $razon = $fila[2];
}
//total ventas
$t = 0;
$tt = 0;

//echo ''. "SELECT tarifa12,tarifa0 from factura_venta where estado='Activo' and fecha_actual::text like '%" . $anioDec . "-" . $mesDec . "-%'";
$sqlfactura = "SELECT tarifa12,tarifa0 from factura_venta where estado='Activo' and fecha_actual::text like '%" . $anioDec . "-" . $mesDec . "-%'";
$facturaVenta = pg_query($sqlfactura);
while ($f = pg_fetch_row($facturaVenta)) {
    $t = $t + $f[0];
    $tt = $tt + $f[1];
}
$tt = $t + $tt; //TOTAL_VENTAS

//
//total notas de credito
$tnc = 0;
$ttnc = 0;
$sqlfacturanc = "SELECT tarifa12,tarifa0 from devolucion_venta where  ( estado='Activo'  or  estado='2')  and fecha_actual::text like '%" . $anioDec . "-" . $mesDec . "-%'";
$facturaVentanc = pg_query($sqlfacturanc);
while ($fnc = pg_fetch_row($facturaVentanc)) {
    $tnc = $tnc + $fnc[0];
    $ttnc = $ttnc + $fnc[1];
}
$ttnc = $tnc + $ttnc; //TOTAL_VENTAS
//echo '$tt'.$ttnc;

//DATOS DE LA EMPRESA
$TipoIDInformanteElement = $xml->createElement('TipoIDInformante', 'R');
$TipoIDInformanteElement = $root->appendChild($TipoIDInformanteElement);
$IdInformanteElement = $xml->createElement('IdInformante', $ruc);
$IdInformanteElement = $root->appendChild($IdInformanteElement);
$razonSocialElement = $xml->createElement('razonSocial', htmlspecialchars("DISTRIBUIDORA DEL CAMPO DISCAMPO CIA LTDA"));
$razonSocialElement = $root->appendChild($razonSocialElement);
$AnioElement = $xml->createElement('Anio', $anioDec);
$AnioElement = $root->appendChild($AnioElement);
$MesElement = $xml->createElement('Mes', $mesDec);
$MesElement = $root->appendChild($MesElement);
$numEstabRucElement = $xml->createElement('numEstabRuc', '001');
$numEstabRucElement = $root->appendChild($numEstabRucElement);
$totalVentasElement = $xml->createElement('totalVentas', number_format(round(floatval($tt)-floatval($ttnc), 2), 2, '.', ''));
$totalVentasElement = $root->appendChild($totalVentasElement);
$codigoOperativoElement = $xml->createElement('codigoOperativo', 'IVA');
$codigoOperativoElement = $root->appendChild($codigoOperativoElement);
$channelElement = $xml->createElement('compras');
$channelElement = $root->appendChild($channelElement);
header('Content-Type: text/xml');
//header('Content-Disposition: attachment; filename='.$nombreArchivo.'.xml');
//$capturaNombre = $_POST['capturaNombre'];
//$capturaApellido = $_POST['capturaApellido'];
//////////////////COMPRAS/////////////////////////
//////////////////COMPRAS/////////////////////////
//////////////////COMPRAS/////////////////////////
//////////////////COMPRAS/////////////////////////
//////////////////COMPRAS/////////////////////////
//////////////////COMPRAS/////////////////////////

$sql = "SELECT p.tipo_documento, p.identificacion_pro, fc.tipo_comprobante, fc.fecha_emision, fc.num_serie,  fc.fecha_emision,
         fc.num_autorizacion, fc.tarifa0, fc.tarifa12, fc.iva_compra, fc.id_factura_compra, fc.pago_ats
        FROM proveedores p, factura_compra fc
        WHERE p.id_proveedor=fc.id_proveedor and fc.estado='Activo' and fecha_emision::text like '%" . $anioDec . "-" . $mesDec . "-%' ORDER BY fc.id_factura_compra";

$result = pg_query($sql);

//if ($result) {
//if (pg_fetch_row($result) > 0) {
while ($row = pg_fetch_row($result)) {

    $itemElement = $xml->createElement('detalleCompras');
    $itemElement = $channelElement->appendChild($itemElement);

    $codSustentoElement = $xml->createElement('codSustento', '01');
    $codSustentoElement = $itemElement->appendChild($codSustentoElement);

    if ($row[0] == 'Ruc') {
        $tipoDocumento = '01';
    } else if ($row[0] == 'Cedula') {
        $tipoDocumento = '02';
    } else if ($row[0] == 'Pasaporte') {
        $tipoDocumento = '03';
    }

    $tpIdProvElement = $xml->createElement('tpIdProv', $tipoDocumento);
    $tpIdProvElement = $itemElement->appendChild($tpIdProvElement);

    $idProv = $row[1];

    $idProvElement = $xml->createElement('idProv', $idProv);
    $idProvElement = $itemElement->appendChild($idProvElement);

    if ($row[2] == 'FACTURA') {
        $tipoComprobante = '01';
    } else if ($row[2] == 'NOTA VENTA') {
        $tipoComprobante = '02';
    }

    $tipoComprobanteElement = $xml->createElement('tipoComprobante', $tipoComprobante);
    $tipoComprobanteElement = $itemElement->appendChild($tipoComprobanteElement);

    $parteRelElement = $xml->createElement('parteRel', 'NO');
    $parteRelElement = $itemElement->appendChild($parteRelElement);

    $vec = split('-', $row[3]);
    $fechaRegistro = $vec[2] . "/" . $vec[1] . "/" . $vec[0];

    $fechaRegistroElement = $xml->createElement('fechaRegistro', $fechaRegistro);
    $fechaRegistroElement = $itemElement->appendChild($fechaRegistroElement);

    $establecimiento = substr($row[4], 0, 3);

    $establecimientoElement = $xml->createElement('establecimiento', $establecimiento);
    $establecimientoElement = $itemElement->appendChild($establecimientoElement);

    $puntoEmision = substr($row[4], 4, 3);

    $puntoEmisionElement = $xml->createElement('puntoEmision', $puntoEmision);
    $puntoEmisionElement = $itemElement->appendChild($puntoEmisionElement);

    $secuencial = substr($row[4], 8, 9);

    $secuencialElement = $xml->createElement('secuencial', $secuencial);
    $secuencialElement = $itemElement->appendChild($secuencialElement);

    $vec = split('-', $row[5]);
    $fechaEmision = $vec[2] . "/" . $vec[1] . "/" . $vec[0];

    $fechaEmisionElement = $xml->createElement('fechaEmision', $fechaEmision);
    $fechaEmisionElement = $itemElement->appendChild($fechaEmisionElement);

    //$autorizacion = $row[6];
    $autorizacion = maxCaracter($row[6], 49);

    $autorizacionElement = $xml->createElement('autorizacion', $autorizacion);
    $autorizacionElement = $itemElement->appendChild($autorizacionElement);
    if ($row[9] != 0) {
        $baseNoGraIva = number_format(round($row[7], 2), 2, '.', '');

        $baseNoGraIvaElement = $xml->createElement('baseNoGraIva', $baseNoGraIva);
        $baseNoGraIvaElement = $itemElement->appendChild($baseNoGraIvaElement);

        $baseImponibleElement = $xml->createElement('baseImponible', '0.00');
        $baseImponibleElement = $itemElement->appendChild($baseImponibleElement);
    } else {
        $baseImpGrav = number_format(round($row[7], 2), 2, '.', '');
        $baseNoGraIvaElement = $xml->createElement('baseNoGraIva', '0.00');
        $baseNoGraIvaElement = $itemElement->appendChild($baseNoGraIvaElement);

        $baseImponibleElement = $xml->createElement('baseImponible', $baseImpGrav);
        $baseImponibleElement = $itemElement->appendChild($baseImponibleElement);
    }
    $baseImpGrav = number_format(round($row[8], 2), 2, '.', '');

    $baseImpGravElement = $xml->createElement('baseImpGrav', $baseImpGrav);
    $baseImpGravElement = $itemElement->appendChild($baseImpGravElement);

    $baseImpExeElement = $xml->createElement('baseImpExe', '0.00');
    $baseImpExeElement = $itemElement->appendChild($baseImpExeElement);

    $montoIceElement = $xml->createElement('montoIce', '0.00');
    $montoIceElement = $itemElement->appendChild($montoIceElement);

    $montoIva = number_format(round($row[9], 2), 2, '.', '');

    $montoIvaElement = $xml->createElement('montoIva', $montoIva);
    $montoIvaElement = $itemElement->appendChild($montoIvaElement);

    $valRetBien10Element = $xml->createElement('valRetBien10', '0.00');
    $valRetBien10Element = $itemElement->appendChild($valRetBien10Element);

    $valRetServ20Element = $xml->createElement('valRetServ20', '0.00');
    $valRetServ20Element = $itemElement->appendChild($valRetServ20Element);

    //RETENCION IVA

    $sql1 = "select rf.valor_retencion, i.valor
            FROM retencion_iva_factura_compra rf, retencion_iva i
            WHERE rf.id_factura='" . $row[10] . "' and rf.id_retencion_iva=i.id_retencion_iva and id_gastos='1'";

    $retencion = pg_query($sql1);
    $sema = 0;
    //if($retencion){
    //if (pg_fetch_row($retencion) > 0) {
    while ($dato = pg_fetch_row($retencion)) {


        if ($dato[1] == 50) {

            $valorRetBienesElement = $xml->createElement('valorRetBienes', number_format(round($dato[0], 2), 2, '.', ''));
            $valorRetBienesElement = $itemElement->appendChild($valorRetBienesElement);

            $valRetServ100Element = $xml->createElement('valRetServ50', '0.00');
            $valRetServ100Element = $itemElement->appendChild($valRetServ100Element);

            $valorRetServiciosElement = $xml->createElement('valorRetServicios', '0.00');
            $valorRetServiciosElement = $itemElement->appendChild($valorRetServiciosElement);

            $valRetServ100Element = $xml->createElement('valRetServ100', '0.00');
            $valRetServ100Element = $itemElement->appendChild($valRetServ100Element);

            $sema = 1;
        } else if ($dato[1] == 30) {




            $valorRetBienesElement = $xml->createElement('valorRetBienes', number_format(round($dato[0], 2), 2, '.', ''));
            $valorRetBienesElement = $itemElement->appendChild($valorRetBienesElement);

            $valRetServ100Element = $xml->createElement('valRetServ50', '0.00');
            $valRetServ100Element = $itemElement->appendChild($valRetServ100Element);

            $valorRetServiciosElement = $xml->createElement('valorRetServicios', '0.00');
            $valorRetServiciosElement = $itemElement->appendChild($valorRetServiciosElement);

            $valRetServ100Element = $xml->createElement('valRetServ100', '0.00');
            $valRetServ100Element = $itemElement->appendChild($valRetServ100Element);

            $sema = 1;
        } else if ($dato[1] == 70) {

            $valorRetBienesElement = $xml->createElement('valorRetBienes', '0.00');
            $valorRetBienesElement = $itemElement->appendChild($valorRetBienesElement);

            $valRetServ100Element = $xml->createElement('valRetServ50', '0.00');
            $valRetServ100Element = $itemElement->appendChild($valRetServ100Element);


            $valorRetServiciosElement = $xml->createElement('valorRetServicios', number_format(round($dato[0], 2), 2, '.', ''));
            $valorRetServiciosElement = $itemElement->appendChild($valorRetServiciosElement);

            $valRetServ100Element = $xml->createElement('valRetServ100', '0.00');
            $valRetServ100Element = $itemElement->appendChild($valRetServ100Element);

            $sema = 1;
        } else if ($dato[1] == 100) {

            $valorRetBienesElement = $xml->createElement('valorRetBienes', '0.00');
            $valorRetBienesElement = $itemElement->appendChild($valorRetBienesElement);


            $valRetServ100Element = $xml->createElement('valRetServ50', '0.00');
            $valRetServ100Element = $itemElement->appendChild($valRetServ100Element);

            $valorRetServiciosElement = $xml->createElement('valorRetServicios', '0.00');
            $valorRetServiciosElement = $itemElement->appendChild($valorRetServiciosElement);

            $valRetServ100Element = $xml->createElement('valRetServ100', number_format(round($dato[0], 2), 2, '.', ''));
            $valRetServ100Element = $itemElement->appendChild($valRetServ100Element);

            $sema = 1;
        }
    }
    //}
    //}
    if ($sema == 0) {
        $valorRetBienesElement = $xml->createElement('valorRetBienes', '0.00');
        $valorRetBienesElement = $itemElement->appendChild($valorRetBienesElement);

        $valRetServ100Element = $xml->createElement('valRetServ50', '0.00');
        $valRetServ100Element = $itemElement->appendChild($valRetServ100Element);

        $valorRetServiciosElement = $xml->createElement('valorRetServicios', '0.00');
        $valorRetServiciosElement = $itemElement->appendChild($valorRetServiciosElement);

        $valRetServ100Element = $xml->createElement('valRetServ100', '0.00');
        $valRetServ100Element = $itemElement->appendChild($valRetServ100Element);
    }

    $totbasesImpReembElement = $xml->createElement('totbasesImpReemb', '0.00');
    $totbasesImpReembElement = $itemElement->appendChild($totbasesImpReembElement);

    //PAGOS EN EL EXTERIOR
    $pagoExteriorElement = $xml->createElement('pagoExterior');
    $pagoExteriorElement = $itemElement->appendChild($pagoExteriorElement);

    $pagoLocExtElement = $xml->createElement('pagoLocExt', '01');
    $pagoLocExtElement = $pagoExteriorElement->appendChild($pagoLocExtElement);


//         $tipoRegiElement = $xml->createElement('tipoRegi', '03');
//         $tipoRegiElement = $pagoExteriorElement->appendChild($tipoRegiElement);

    $paisEfecPagoElement = $xml->createElement('paisEfecPago', 'NA');
    $paisEfecPagoElement = $pagoExteriorElement->appendChild($paisEfecPagoElement);

    $aplicConvDobTribElement = $xml->createElement('aplicConvDobTrib', 'NA');
    $aplicConvDobTribElement = $pagoExteriorElement->appendChild($aplicConvDobTribElement);

    $pagExtSujRetNorLegElement = $xml->createElement('pagExtSujRetNorLeg', 'NA');
    $pagExtSujRetNorLegElement = $pagoExteriorElement->appendChild($pagExtSujRetNorLegElement);

//         $pagoRegFisElement = $xml->createElement('pagoRegFis', 'NO');
//         $pagoRegFisElement = $pagoExteriorElement->appendChild($pagoRegFisElement);
    //FORMAS DE PAGO
    if (($row[7] + $row[8] + $row[9]) >= 1000) {
        $formasDePagoElement = $xml->createElement('formasDePago');
        $formasDePagoElement = $itemElement->appendChild($formasDePagoElement);

        $vec1 = explode('*', $row[11]);

        $num = count($vec1);
//              print_r($num);
        $ind = 0;
        while ($ind < $num) {
            $formaPagoElement = $xml->createElement('formaPago', $vec1[$ind]);
            $formaPagoElement = $formasDePagoElement->appendChild($formaPagoElement);
            $ind++;
        }
    }

    //RETENCION EN LA FUENTE

    $sql2 = "select f.codigo_formulario, rf.valor_compra, f.valor, dcr.valor_retenido, rf.num_serie, rf.num_autorizacion, rf.fecha 
            FROM retencion_fuente_factura_compra rf, retencion_fuentes f, detallecomprobanteretencion dcr
            WHERE rf.id_factura='" . $row[10] . "' and rf.id_retencion_fuente=f.id_retencion_fuentes and rf.id_gastos='1' AND  dcr.id_retencion_fuente_factura_compra=rf.id_retencion_fuente_factura_compra and  dcr.id_trete=1";

    $fuente = pg_query($sql2);
    $airElement = $xml->createElement('air');
    $airElement = $itemElement->appendChild($airElement);
    while ($fila = pg_fetch_row($fuente)) {


        $detalleAirElement = $xml->createElement('detalleAir');
        $detalleAirElement = $airElement->appendChild($detalleAirElement);

        $codRetAir = $fila[0];

        $codRetAirElement = $xml->createElement('codRetAir', $codRetAir);
        $codRetAirElement = $detalleAirElement->appendChild($codRetAirElement);

        $baseImpAir = number_format(round($fila[1], 2), 2, '.', '');

        $baseImpAirElement = $xml->createElement('baseImpAir', $baseImpAir);
        $baseImpAirElement = $detalleAirElement->appendChild($baseImpAirElement);

        $porcentajeAir = $fila[2];

        $porcentajeAirElement = $xml->createElement('porcentajeAir', $porcentajeAir);
        $porcentajeAirElement = $detalleAirElement->appendChild($porcentajeAirElement);

        $valRetAir = number_format(round($fila[3], 2), 2, '.', '');

        $valRetAirElement = $xml->createElement('valRetAir', $valRetAir);
        $valRetAirElement = $detalleAirElement->appendChild($valRetAirElement);
    }
    $sql22 = "select f.codigo_formulario, rf.valor_compra, f.valor, rf.valor_retencion, rf.num_serie, rf.num_autorizacion, rf.fecha  
            FROM retencion_fuente_factura_compra rf, retencion_fuentes f
            WHERE rf.id_factura='" . $row[10] . "' and rf.id_retencion_fuente=f.id_retencion_fuentes and rf.id_gastos='1'  and rf.num_autorizacion!='' LIMIT 1";

    $fuenteE = pg_query($sql22);

    while ($fila = pg_fetch_row($fuenteE)) {


        $estabRetencion1 = substr($fila[4], 0, 3);

        $estabRetencion1Element = $xml->createElement('estabRetencion1', $estabRetencion1);
        $estabRetencion1Element = $itemElement->appendChild($estabRetencion1Element);

        $ptoEmiRetencion1 = substr($fila[4], 4, 3);

        $ptoEmiRetencion1Element = $xml->createElement('ptoEmiRetencion1', $ptoEmiRetencion1);
        $ptoEmiRetencion1Element = $itemElement->appendChild($ptoEmiRetencion1Element);

        $secRetencion1 = substr($fila[4], 8, 9);

        $secRetencion1Element = $xml->createElement('secRetencion1', $secRetencion1);
        $secRetencion1Element = $itemElement->appendChild($secRetencion1Element);

        $autRetencion1 = maxCaracter($fila[5], 49);

        $autRetencion1Element = $xml->createElement('autRetencion1', $autRetencion1);
        $autRetencion1Element = $itemElement->appendChild($autRetencion1Element);

        $vec = split('T', $fila[6]);
        $fechaEmiRet1 = $vec[0];

        $vec = split('-', $fechaEmiRet1);
        $fechaEmiRet1 = $vec[2] . "/" . $vec[1] . "/" . $vec[0];

        $fechaEmiRet1Element = $xml->createElement('fechaEmiRet1', $fechaEmiRet1);
        $fechaEmiRet1Element = $itemElement->appendChild($fechaEmiRet1Element);
    }
}




//////////////////NOTAS DE CREDITO COMPRAS/////////////////////////
//////////////////NOTAS DE CREDITO COMPRAS/////////////////////////
//////////////////NOTAS DE CREDITO COMPRAS/////////////////////////
//////////////////NOTAS DE CREDITO COMPRAS/////////////////////////
//////////////////NOTAS DE CREDITO COMPRAS/////////////////////////
//////////////////NOTAS DE CREDITO COMPRAS/////////////////////////

$sql = "SELECT p.tipo_documento, p.identificacion_pro, fc.tipo_comprobante, fc.num_autorizacion_sri, fc.num_nota_credito,  fc.num_autorizacion_sri,
         fc.motivo, fc.tarifa0, fc.tarifa12, fc.iva_compra, fc.id_devolucion_compra,fc.id_devolucion_compra
        FROM proveedores p, devolucion_compra fc
        WHERE p.id_proveedor=fc.id_proveedor and fc.estado='Activo' and num_autorizacion_sri::text like '%" . $anioDec . "-" . $mesDec . "-%' ORDER BY fc.id_devolucion_compra
        ";

$result = pg_query($sql);

//if ($result) {
//if (pg_fetch_row($result) > 0) {
while ($row = pg_fetch_row($result)) {

    $itemElement = $xml->createElement('detalleCompras');
    $itemElement = $channelElement->appendChild($itemElement);

    $codSustentoElement = $xml->createElement('codSustento', '01');
    $codSustentoElement = $itemElement->appendChild($codSustentoElement);

    if ($row[0] == 'Ruc') {
        $tipoDocumento = '01';
    } else if ($row[0] == 'Cedula') {
        $tipoDocumento = '02';
    } else if ($row[0] == 'Pasaporte') {
        $tipoDocumento = '03';
    }

    $tpIdProvElement = $xml->createElement('tpIdProv', $tipoDocumento);
    $tpIdProvElement = $itemElement->appendChild($tpIdProvElement);

    $idProv = $row[1];

    $idProvElement = $xml->createElement('idProv', $idProv);
    $idProvElement = $itemElement->appendChild($idProvElement);

    if ($row[2] == 'FACTURA') {
        $tipoComprobante = '04';
    } else if ($row[2] == 'NOTA VENTA') {
        $tipoComprobante = '02';
    }

    $tipoComprobanteElement = $xml->createElement('tipoComprobante', $tipoComprobante);
    $tipoComprobanteElement = $itemElement->appendChild($tipoComprobanteElement);

    $parteRelElement = $xml->createElement('parteRel', 'NO');
    $parteRelElement = $itemElement->appendChild($parteRelElement);

    $vec = split('-', $row[3]);
    $fechaRegistro = $vec[2] . "/" . $vec[1] . "/" . $vec[0];

    $fechaRegistroElement = $xml->createElement('fechaRegistro', $fechaRegistro);
    $fechaRegistroElement = $itemElement->appendChild($fechaRegistroElement);

    $establecimiento = substr($row[4], 0, 3);

    $establecimientoElement = $xml->createElement('establecimiento', $establecimiento);
    $establecimientoElement = $itemElement->appendChild($establecimientoElement);

    $puntoEmision = substr($row[4], 4, 3);

    $puntoEmisionElement = $xml->createElement('puntoEmision', $puntoEmision);
    $puntoEmisionElement = $itemElement->appendChild($puntoEmisionElement);

    $secuencial = substr($row[4], 8, 9);

    $secuencialElement = $xml->createElement('secuencial', $secuencial);
    $secuencialElement = $itemElement->appendChild($secuencialElement);

    $vec = split('-', $row[5]);
    $fechaEmision = $vec[2] . "/" . $vec[1] . "/" . $vec[0];

    $fechaEmisionElement = $xml->createElement('fechaEmision', $fechaEmision);
    $fechaEmisionElement = $itemElement->appendChild($fechaEmisionElement);

    //$autorizacion = $row[6];
    $autorizacion = maxCaracter($row[6], 49);

    $autorizacionElement = $xml->createElement('autorizacion', $autorizacion);
    $autorizacionElement = $itemElement->appendChild($autorizacionElement);
    if ($row[9] != 0) {
        $baseNoGraIva = number_format(round($row[7], 2), 2, '.', '');

        $baseNoGraIvaElement = $xml->createElement('baseNoGraIva', $baseNoGraIva);
        $baseNoGraIvaElement = $itemElement->appendChild($baseNoGraIvaElement);

        $baseImponibleElement = $xml->createElement('baseImponible', '0.00');
        $baseImponibleElement = $itemElement->appendChild($baseImponibleElement);
    } else {
        $baseImpGrav = number_format(round($row[7], 2), 2, '.', '');
        $baseNoGraIvaElement = $xml->createElement('baseNoGraIva', '0.00');
        $baseNoGraIvaElement = $itemElement->appendChild($baseNoGraIvaElement);

        $baseImponibleElement = $xml->createElement('baseImponible', $baseImpGrav);
        $baseImponibleElement = $itemElement->appendChild($baseImponibleElement);
    }
    $baseImpGrav = number_format(round($row[8], 2), 2, '.', '');

    $baseImpGravElement = $xml->createElement('baseImpGrav', $baseImpGrav);
    $baseImpGravElement = $itemElement->appendChild($baseImpGravElement);

    $baseImpExeElement = $xml->createElement('baseImpExe', '0.00');
    $baseImpExeElement = $itemElement->appendChild($baseImpExeElement);

    $montoIceElement = $xml->createElement('montoIce', '0.00');
    $montoIceElement = $itemElement->appendChild($montoIceElement);

    $montoIva = number_format(round($row[9], 2), 2, '.', '');

    $montoIvaElement = $xml->createElement('montoIva', $montoIva);
    $montoIvaElement = $itemElement->appendChild($montoIvaElement);

    $valRetBien10Element = $xml->createElement('valRetBien10', '0.00');
    $valRetBien10Element = $itemElement->appendChild($valRetBien10Element);

    $valRetServ20Element = $xml->createElement('valRetServ20', '0.00');
    $valRetServ20Element = $itemElement->appendChild($valRetServ20Element);

    //RETENCION IVA

    $sql1 = "select rf.valor_retencion, i.valor
            FROM retencion_iva_factura_compra rf, retencion_iva i
            WHERE rf.id_factura='" . $row[10] . "' and rf.id_retencion_iva=i.id_retencion_iva";

    $retencion = pg_query($sql1);
    $sema = 0;
    //if($retencion){
    //if (pg_fetch_row($retencion) > 0) {
    while ($dato = pg_fetch_row($retencion)) {


        if ($dato[1] == 50) {

            $valorRetBienesElement = $xml->createElement('valorRetBienes', number_format(round($dato[0], 2), 2, '.', ''));
            $valorRetBienesElement = $itemElement->appendChild($valorRetBienesElement);

            $valRetServ100Element = $xml->createElement('valRetServ50', '0.00');
            $valRetServ100Element = $itemElement->appendChild($valRetServ100Element);

            $valorRetServiciosElement = $xml->createElement('valorRetServicios', '0.00');
            $valorRetServiciosElement = $itemElement->appendChild($valorRetServiciosElement);

            $valRetServ100Element = $xml->createElement('valRetServ100', '0.00');
            $valRetServ100Element = $itemElement->appendChild($valRetServ100Element);

            $sema = 1;
        } else if ($dato[1] == 30) {




            $valorRetBienesElement = $xml->createElement('valorRetBienes', number_format(round($dato[0], 2), 2, '.', ''));
            $valorRetBienesElement = $itemElement->appendChild($valorRetBienesElement);

            $valRetServ100Element = $xml->createElement('valRetServ50', '0.00');
            $valRetServ100Element = $itemElement->appendChild($valRetServ100Element);

            $valorRetServiciosElement = $xml->createElement('valorRetServicios', '0.00');
            $valorRetServiciosElement = $itemElement->appendChild($valorRetServiciosElement);

            $valRetServ100Element = $xml->createElement('valRetServ100', '0.00');
            $valRetServ100Element = $itemElement->appendChild($valRetServ100Element);

            $sema = 1;
        } else if ($dato[1] == 70) {

            $valorRetBienesElement = $xml->createElement('valorRetBienes', '0.00');
            $valorRetBienesElement = $itemElement->appendChild($valorRetBienesElement);

            $valRetServ100Element = $xml->createElement('valRetServ50', '0.00');
            $valRetServ100Element = $itemElement->appendChild($valRetServ100Element);


            $valorRetServiciosElement = $xml->createElement('valorRetServicios', number_format(round($dato[0], 2), 2, '.', ''));
            $valorRetServiciosElement = $itemElement->appendChild($valorRetServiciosElement);

            $valRetServ100Element = $xml->createElement('valRetServ100', '0.00');
            $valRetServ100Element = $itemElement->appendChild($valRetServ100Element);

            $sema = 1;
        } else if ($dato[1] == 100) {

            $valorRetBienesElement = $xml->createElement('valorRetBienes', '0.00');
            $valorRetBienesElement = $itemElement->appendChild($valorRetBienesElement);


            $valRetServ100Element = $xml->createElement('valRetServ50', '0.00');
            $valRetServ100Element = $itemElement->appendChild($valRetServ100Element);

            $valorRetServiciosElement = $xml->createElement('valorRetServicios', '0.00');
            $valorRetServiciosElement = $itemElement->appendChild($valorRetServiciosElement);

            $valRetServ100Element = $xml->createElement('valRetServ100', number_format(round($dato[0], 2), 2, '.', ''));
            $valRetServ100Element = $itemElement->appendChild($valRetServ100Element);

            $sema = 1;
        }
    }
    //}
    //}
    if ($sema == 0) {
        $valorRetBienesElement = $xml->createElement('valorRetBienes', '0.00');
        $valorRetBienesElement = $itemElement->appendChild($valorRetBienesElement);

        $valRetServ100Element = $xml->createElement('valRetServ50', '0.00');
        $valRetServ100Element = $itemElement->appendChild($valRetServ100Element);

        $valorRetServiciosElement = $xml->createElement('valorRetServicios', '0.00');
        $valorRetServiciosElement = $itemElement->appendChild($valorRetServiciosElement);

        $valRetServ100Element = $xml->createElement('valRetServ100', '0.00');
        $valRetServ100Element = $itemElement->appendChild($valRetServ100Element);
    }

    $totbasesImpReembElement = $xml->createElement('totbasesImpReemb', '0.00');
    $totbasesImpReembElement = $itemElement->appendChild($totbasesImpReembElement);

    //PAGOS EN EL EXTERIOR
    $pagoExteriorElement = $xml->createElement('pagoExterior');
    $pagoExteriorElement = $itemElement->appendChild($pagoExteriorElement);

    $pagoLocExtElement = $xml->createElement('pagoLocExt', '01');
    $pagoLocExtElement = $pagoExteriorElement->appendChild($pagoLocExtElement);


//         $tipoRegiElement = $xml->createElement('tipoRegi', '03');
//         $tipoRegiElement = $pagoExteriorElement->appendChild($tipoRegiElement);

    $paisEfecPagoElement = $xml->createElement('paisEfecPago', 'NA');
    $paisEfecPagoElement = $pagoExteriorElement->appendChild($paisEfecPagoElement);

    $aplicConvDobTribElement = $xml->createElement('aplicConvDobTrib', 'NA');
    $aplicConvDobTribElement = $pagoExteriorElement->appendChild($aplicConvDobTribElement);

    $pagExtSujRetNorLegElement = $xml->createElement('pagExtSujRetNorLeg', 'NA');
    $pagExtSujRetNorLegElement = $pagoExteriorElement->appendChild($pagExtSujRetNorLegElement);

//         $pagoRegFisElement = $xml->createElement('pagoRegFis', 'NO');
//         $pagoRegFisElement = $pagoExteriorElement->appendChild($pagoRegFisElement);
    //FORMAS DE PAGO
    if (($row[7] + $row[8] + $row[9]) >= 1000) {
        $formasDePagoElement = $xml->createElement('formasDePago');
        $formasDePagoElement = $itemElement->appendChild($formasDePagoElement);

        $vec1 = explode('*', $row[11]);

        $num = count($vec1);
//              print_r($num);
        $ind = 0;
        while ($ind < $num) {
            $formaPagoElement = $xml->createElement('formaPago', $vec1[$ind]);
            $formaPagoElement = $formasDePagoElement->appendChild($formaPagoElement);
            $ind++;
        }
    }


    $sql22 = "select id_devolucion_compra,id_proveedor, fecha_actual,iva_compra,num_serie,num_autorizacion,tarifa0 from devolucion_compra where id_devolucion_compra='" . $row[11] . "'";

    $fuenteE = pg_query($sql22);

    while ($fila = pg_fetch_row($fuenteE)) {


        $estabRetencion1 = substr($fila[4], 0, 3);

        $estabRetencion1Element = $xml->createElement('docModificado', '01');
        $estabRetencion1Element = $itemElement->appendChild($estabRetencion1Element);


        $estabRetencion1Element = $xml->createElement('estabModificado', $estabRetencion1);
        $estabRetencion1Element = $itemElement->appendChild($estabRetencion1Element);

        $ptoEmiRetencion1 = substr($fila[4], 4, 3);

        $ptoEmiRetencion1Element = $xml->createElement('ptoEmiModificado', $ptoEmiRetencion1);
        $ptoEmiRetencion1Element = $itemElement->appendChild($ptoEmiRetencion1Element);

        $secRetencion1 = substr($fila[4], 8, 9);

        $secRetencion1Element = $xml->createElement('secModificado', $secRetencion1);
        $secRetencion1Element = $itemElement->appendChild($secRetencion1Element);

        $autRetencion1 = maxCaracter($fila[5], 49);

        $autRetencion1Element = $xml->createElement('autModificado', $autRetencion1);
        $autRetencion1Element = $itemElement->appendChild($autRetencion1Element);
    }
}

////////GASTOS////////////////////
////////GASTOS////////////////////
////////GASTOS////////////////////
////////GASTOS////////////////////
////////GASTOS////////////////////
////////GASTOS////////////////////////////GASTOS////////////////////
////////GASTOS////////////////////
////////GASTOS////////////////////
////////GASTOS////////////////////
////////GASTOS////////////////////


$sql = "SELECT p.tipo_documento, p.identificacion_pro, fc.tipo_comprobante, fc.fecha_emision, fc.num_factura,  fc.fecha_emision,
        fc.num_autorizacion, fc.tarifa0, fc.tarifa12, fc.iva_compra, fc.id_gastos, fc.pago_ats
        FROM proveedores p, gastos fc
        WHERE p.id_proveedor=fc.id_proveedor and fc.estado='Activo' and fecha_emision::text like  '%" . $anioDec . "-" . $mesDec . "-%' ORDER BY fc.id_gastos";

$result = pg_query($sql);

//if ($result) {
//if (pg_fetch_row($result) > 0) {
while ($row = pg_fetch_row($result)) {

    $itemElement = $xml->createElement('detalleCompras');
    $itemElement = $channelElement->appendChild($itemElement);

    $codSustentoElement = $xml->createElement('codSustento', '01');
    $codSustentoElement = $itemElement->appendChild($codSustentoElement);

    if ($row[0] == 'Ruc') {
        $tipoDocumento = '01';
    } else if ($row[0] == 'Cedula') {
        $tipoDocumento = '02';
    } else if ($row[0] == 'Pasaporte') {
        $tipoDocumento = '03';
    }

    $tpIdProvElement = $xml->createElement('tpIdProv', $tipoDocumento);
    $tpIdProvElement = $itemElement->appendChild($tpIdProvElement);

    $idProv = $row[1];

    $idProvElement = $xml->createElement('idProv', $idProv);
    $idProvElement = $itemElement->appendChild($idProvElement);

    if ($row[2] == 'FACTURA') {
        $tipoComprobante = '01';
    } else if ($row[2] == 'NOTA VENTA') {
        $tipoComprobante = '02';
    }

    $tipoComprobanteElement = $xml->createElement('tipoComprobante', $tipoComprobante);
    $tipoComprobanteElement = $itemElement->appendChild($tipoComprobanteElement);

    $parteRelElement = $xml->createElement('parteRel', 'NO');
    $parteRelElement = $itemElement->appendChild($parteRelElement);

    $vec = split('-', $row[3]);
    $fechaRegistro = $vec[2] . "/" . $vec[1] . "/" . $vec[0];

    $fechaRegistroElement = $xml->createElement('fechaRegistro', $fechaRegistro);
    $fechaRegistroElement = $itemElement->appendChild($fechaRegistroElement);

    $establecimiento = substr($row[4], 0, 3);

    $establecimientoElement = $xml->createElement('establecimiento', $establecimiento);
    $establecimientoElement = $itemElement->appendChild($establecimientoElement);

    $puntoEmision = substr($row[4], 4, 3);

    $puntoEmisionElement = $xml->createElement('puntoEmision', $puntoEmision);
    $puntoEmisionElement = $itemElement->appendChild($puntoEmisionElement);

    $secuencial = substr($row[4], 8, 9);

    $secuencialElement = $xml->createElement('secuencial', $secuencial);
    $secuencialElement = $itemElement->appendChild($secuencialElement);

    $vec = split('-', $row[5]);
    $fechaEmision = $vec[2] . "/" . $vec[1] . "/" . $vec[0];

    $fechaEmisionElement = $xml->createElement('fechaEmision', $fechaEmision);
    $fechaEmisionElement = $itemElement->appendChild($fechaEmisionElement);


    $autorizacion = maxCaracter($row[6], 49);

    $autorizacionElement = $xml->createElement('autorizacion', $autorizacion);
    $autorizacionElement = $itemElement->appendChild($autorizacionElement);
    if ($row[9] != 0) {
        $baseNoGraIva = number_format(round($row[7], 2), 2, '.', '');

        $baseNoGraIvaElement = $xml->createElement('baseNoGraIva', $baseNoGraIva);
        $baseNoGraIvaElement = $itemElement->appendChild($baseNoGraIvaElement);

        $baseImponibleElement = $xml->createElement('baseImponible', '0.00');
        $baseImponibleElement = $itemElement->appendChild($baseImponibleElement);
    } else {
        $baseImpGrav = number_format(round($row[7], 2), 2, '.', '');
        $baseNoGraIvaElement = $xml->createElement('baseNoGraIva', '0.00');
        $baseNoGraIvaElement = $itemElement->appendChild($baseNoGraIvaElement);

        $baseImponibleElement = $xml->createElement('baseImponible', $baseImpGrav);
        $baseImponibleElement = $itemElement->appendChild($baseImponibleElement);
    }
    $baseImpGrav = number_format(round($row[8], 2), 2, '.', '');

    $baseImpGravElement = $xml->createElement('baseImpGrav', $baseImpGrav);
    $baseImpGravElement = $itemElement->appendChild($baseImpGravElement);

    $baseImpExeElement = $xml->createElement('baseImpExe', '0.00');
    $baseImpExeElement = $itemElement->appendChild($baseImpExeElement);

    $montoIceElement = $xml->createElement('montoIce', '0.00');
    $montoIceElement = $itemElement->appendChild($montoIceElement);

    $montoIva = number_format(round($row[9], 2), 2, '.', '');

    $montoIvaElement = $xml->createElement('montoIva', $montoIva);
    $montoIvaElement = $itemElement->appendChild($montoIvaElement);

    $valRetBien10Element = $xml->createElement('valRetBien10', '0.00');
    $valRetBien10Element = $itemElement->appendChild($valRetBien10Element);

    $valRetServ20Element = $xml->createElement('valRetServ20', '0.00');
    $valRetServ20Element = $itemElement->appendChild($valRetServ20Element);

    //RETENCION IVA

    $sql1 = "select rf.valor_retencion, i.valor
            FROM retencion_iva_factura_compra rf, retencion_iva i
            WHERE rf.id_factura='" . $row[10] . "' and rf.id_retencion_iva=i.id_retencion_iva and id_gastos='10'";

    $retencion = pg_query($sql1);
    $sema = 0;
    //if($retencion){
    //if (pg_fetch_row($retencion) > 0) {
    while ($dato = pg_fetch_row($retencion)) {


        if ($dato[1] == 50) {

            $valorRetBienesElement = $xml->createElement('valorRetBienes', number_format(round($dato[0], 2), 2, '.', ''));
            $valorRetBienesElement = $itemElement->appendChild($valorRetBienesElement);

            $valRetServ100Element = $xml->createElement('valRetServ50', '0.00');
            $valRetServ100Element = $itemElement->appendChild($valRetServ100Element);

            $valorRetServiciosElement = $xml->createElement('valorRetServicios', '0.00');
            $valorRetServiciosElement = $itemElement->appendChild($valorRetServiciosElement);

            $valRetServ100Element = $xml->createElement('valRetServ100', '0.00');
            $valRetServ100Element = $itemElement->appendChild($valRetServ100Element);

            $sema = 1;
        } else if ($dato[1] == 30) {


            $valorRetBienesElement = $xml->createElement('valorRetBienes', number_format(round($dato[0], 2), 2, '.', ''));
            $valorRetBienesElement = $itemElement->appendChild($valorRetBienesElement);

            $valRetServ100Element = $xml->createElement('valRetServ50', '0.00');
            $valRetServ100Element = $itemElement->appendChild($valRetServ100Element);

            $valorRetServiciosElement = $xml->createElement('valorRetServicios', '0.00');
            $valorRetServiciosElement = $itemElement->appendChild($valorRetServiciosElement);

            $valRetServ100Element = $xml->createElement('valRetServ100', '0.00');
            $valRetServ100Element = $itemElement->appendChild($valRetServ100Element);

            $sema = 1;
        } else if ($dato[1] == 70) {

            $valorRetBienesElement = $xml->createElement('valorRetBienes', '0.00');
            $valorRetBienesElement = $itemElement->appendChild($valorRetBienesElement);

            $valRetServ100Element = $xml->createElement('valRetServ50', '0.00');
            $valRetServ100Element = $itemElement->appendChild($valRetServ100Element);


            $valorRetServiciosElement = $xml->createElement('valorRetServicios', number_format(round($dato[0], 2), 2, '.', ''));
            $valorRetServiciosElement = $itemElement->appendChild($valorRetServiciosElement);

            $valRetServ100Element = $xml->createElement('valRetServ100', '0.00');
            $valRetServ100Element = $itemElement->appendChild($valRetServ100Element);

            $sema = 1;
        } else if ($dato[1] == 100) {

            $valorRetBienesElement = $xml->createElement('valorRetBienes', '0.00');
            $valorRetBienesElement = $itemElement->appendChild($valorRetBienesElement);


            $valRetServ100Element = $xml->createElement('valRetServ50', '0.00');
            $valRetServ100Element = $itemElement->appendChild($valRetServ100Element);

            $valorRetServiciosElement = $xml->createElement('valorRetServicios', '0.00');
            $valorRetServiciosElement = $itemElement->appendChild($valorRetServiciosElement);

            $valRetServ100Element = $xml->createElement('valRetServ100', number_format(round($dato[0], 2), 2, '.', ''));
            $valRetServ100Element = $itemElement->appendChild($valRetServ100Element);

            $sema = 1;
        }
    }
    //}
    //}
    if ($sema == 0) {
        $valorRetBienesElement = $xml->createElement('valorRetBienes', '0.00');
        $valorRetBienesElement = $itemElement->appendChild($valorRetBienesElement);

        $valRetServ100Element = $xml->createElement('valRetServ50', '0.00');
        $valRetServ100Element = $itemElement->appendChild($valRetServ100Element);

        $valorRetServiciosElement = $xml->createElement('valorRetServicios', '0.00');
        $valorRetServiciosElement = $itemElement->appendChild($valorRetServiciosElement);

        $valRetServ100Element = $xml->createElement('valRetServ100', '0.00');
        $valRetServ100Element = $itemElement->appendChild($valRetServ100Element);
    }

    $totbasesImpReembElement = $xml->createElement('totbasesImpReemb', '0.00');
    $totbasesImpReembElement = $itemElement->appendChild($totbasesImpReembElement);

    //PAGOS EN EL EXTERIOR
    $pagoExteriorElement = $xml->createElement('pagoExterior');
    $pagoExteriorElement = $itemElement->appendChild($pagoExteriorElement);

    $pagoLocExtElement = $xml->createElement('pagoLocExt', '01');
    $pagoLocExtElement = $pagoExteriorElement->appendChild($pagoLocExtElement);


//         $tipoRegiElement = $xml->createElement('tipoRegi', '03');
//         $tipoRegiElement = $pagoExteriorElement->appendChild($tipoRegiElement);

    $paisEfecPagoElement = $xml->createElement('paisEfecPago', 'NA');
    $paisEfecPagoElement = $pagoExteriorElement->appendChild($paisEfecPagoElement);

    $aplicConvDobTribElement = $xml->createElement('aplicConvDobTrib', 'NA');
    $aplicConvDobTribElement = $pagoExteriorElement->appendChild($aplicConvDobTribElement);

    $pagExtSujRetNorLegElement = $xml->createElement('pagExtSujRetNorLeg', 'NA');
    $pagExtSujRetNorLegElement = $pagoExteriorElement->appendChild($pagExtSujRetNorLegElement);

//         $pagoRegFisElement = $xml->createElement('pagoRegFis', 'NO');
//         $pagoRegFisElement = $pagoExteriorElement->appendChild($pagoRegFisElement);
    //FORMAS DE PAGO
    if (($row[7] + $row[8] + $row[9]) >= 1000) {
        $formasDePagoElement = $xml->createElement('formasDePago');
        $formasDePagoElement = $itemElement->appendChild($formasDePagoElement);

        $vec1 = explode('*', $row[11]);
        $num = count($vec1);
        $ind = 0;
        while ($ind < $num) {
            $formaPagoElement = $xml->createElement('formaPago', $vec1[$ind]);
            $formaPagoElement = $formasDePagoElement->appendChild($formaPagoElement);
            $ind++;
        }
    }

    //RETENCION EN LA FUENTE

    $sql2 = "   select f.codigo_formulario, rf.valor_compra, f.valor, dcr.valor_retenido, rf.num_serie, rf.num_autorizacion, rf.fecha 
            FROM retencion_fuente_factura_compra rf, retencion_fuentes f, detallecomprobanteretencion dcr
            WHERE rf.id_factura='" . $row[10] . "' and rf.id_retencion_fuente=f.id_retencion_fuentes and rf.id_gastos='10' AND  dcr.id_retencion_fuente_factura_compra=rf.id_retencion_fuente_factura_compra and  dcr.id_trete=1";

    $fuente = pg_query($sql2);
    while ($fila = pg_fetch_row($fuente)) {
        $airElement = $xml->createElement('air');
        $airElement = $itemElement->appendChild($airElement);

        $detalleAirElement = $xml->createElement('detalleAir');
        $detalleAirElement = $airElement->appendChild($detalleAirElement);

        $codRetAir = $fila[0];

        $codRetAirElement = $xml->createElement('codRetAir', $codRetAir);
        $codRetAirElement = $detalleAirElement->appendChild($codRetAirElement);

        $baseImpAir = number_format(round($fila[1], 2), 2, '.', '');

        $baseImpAirElement = $xml->createElement('baseImpAir', $baseImpAir);
        $baseImpAirElement = $detalleAirElement->appendChild($baseImpAirElement);

        $porcentajeAir = $fila[2];

        $porcentajeAirElement = $xml->createElement('porcentajeAir', $porcentajeAir);
        $porcentajeAirElement = $detalleAirElement->appendChild($porcentajeAirElement);

        $valRetAir = number_format(round($fila[3], 2), 2, '.', '');

        $valRetAirElement = $xml->createElement('valRetAir', $valRetAir);
        $valRetAirElement = $detalleAirElement->appendChild($valRetAirElement);
    }
    $sql22 = "select f.codigo_formulario, rf.valor_compra, f.valor, dcr.valor_retenido, rf.num_serie, rf.num_autorizacion, rf.fecha 
            FROM retencion_fuente_factura_compra rf, retencion_fuentes f
            WHERE rf.id_factura='" . $row[10] . "' and rf.id_retencion_fuente=f.id_retencion_fuentes  and rf.id_gastos='10'  and rf.num_autorizacion!='' LIMIT 1";

    $fuenteE = pg_query($sql22);

    while ($fila = pg_fetch_row($fuenteE)) {


        $estabRetencion1 = substr($fila[4], 0, 3);
        $estabRetencion1Element = $xml->createElement('estabRetencion1', $estabRetencion1);
        $estabRetencion1Element = $itemElement->appendChild($estabRetencion1Element);
        $ptoEmiRetencion1 = substr($fila[4], 4, 3);
        $ptoEmiRetencion1Element = $xml->createElement('ptoEmiRetencion1', $ptoEmiRetencion1);
        $ptoEmiRetencion1Element = $itemElement->appendChild($ptoEmiRetencion1Element);
        $secRetencion1 = substr($fila[4], 8, 9);
        $secRetencion1Element = $xml->createElement('secRetencion1', $secRetencion1);
        $secRetencion1Element = $itemElement->appendChild($secRetencion1Element);
        $autRetencion1 = maxCaracter($fila[5], 49);
        $autRetencion1Element = $xml->createElement('autRetencion1', $autRetencion1);
        $autRetencion1Element = $itemElement->appendChild($autRetencion1Element);
        $vec = split('T', $fila[6]);
        $fechaEmiRet1 = $vec[0];
        $vec = split('-', $fechaEmiRet1);
        $fechaEmiRet1 = $vec[2] . "/" . $vec[1] . "/" . $vec[0];
        $fechaEmiRet1Element = $xml->createElement('fechaEmiRet1', $fechaEmiRet1);
        $fechaEmiRet1Element = $itemElement->appendChild($fechaEmiRet1Element);
    }
}





// Cierre del Bucle While
/* } // Cierre del if que verifica si ha encontrado información
  else {
  $messageElement = $xml->createElement('message', 'Información no encontrada.');
  $messageElement = $channelElement->appendChild($messageElement);
  } // Cierre del else
  } // Cierre del if que verifica si ha ocurrido algún error en la base de datos
  else {
  $messageElement = $xml->createElement('message', pg_last_error());
  $messageElement = $channelElement->appendChild($messageElement);
  } // Cierre del else */

$channelElement = $xml->createElement('ventas');
$channelElement = $root->appendChild($channelElement);
       

$sqlcliente = "SELECT DISTINCT on (identificacion) identificacion,  id_tdocu, identificacion, nombres_cli from clientes where estado='Activo' ";

$clientes = pg_query($sqlcliente);
$conf = 0;
$basenoiva = 0;
$baseimp = 0;
$monIva = 0;
$retFuente = 0;
$retIva = 0;
$total = 0;

//if($clientes){
//if (pg_fetch_row($clientes)>0) {
while ($cli = pg_fetch_row($clientes)) {
    if ($cli[1] == "1" && $cli[2] == "9999999999999") {
        $codigo = '07';
    } else if ($cli[1] == "1") {
        $codigo = '04';
    } else if ($cli[1] == "2") {
        $codigo = '05';
    } else if ($cli[1] == "3") {
        $codigo = '06';
    }

    $id = $cli[2];
//   echo ''. "SELECT tarifa0, tarifa12, iva_venta, id_factura_venta from factura_venta,clientes where factura_venta.id_cliente=clientes.id_cliente and clientes.identificacion='$id'  and factura_venta.estado='Activo' and fecha_actual::text like '%" . $anioDec . "-" . $mesDec . "-%'  ORDER BY id_factura_venta";
    
    $sqlfactura = "SELECT tarifa0, tarifa12, iva_venta, id_factura_venta from factura_venta,clientes where factura_venta.id_cliente=clientes.id_cliente and clientes.identificacion='$id'  and factura_venta.estado='Activo' and fecha_actual::text like '%" . $anioDec . "-" . $mesDec . "-%'  ORDER BY id_factura_venta";
    $conf = 0;
    $basenoiva = 0;
    $baseimp = 0;
    $monIva = 0;
    $retFuente = 0;
    $retIva = 0;
    $facturas = pg_query($sqlfactura);

    while ($fac = pg_fetch_row($facturas)) {
        $conf = $conf + 1;
        $basenoiva = $basenoiva + $fac[0];
        $baseimp = $baseimp + $fac[1];
        $monIva = $monIva + $fac[2];

        $sqliva = "select valor_retencion from retencion_iva_factura_venta where id_factura='" . $fac[3] . "'";
        $sqliva_var = pg_query($sqliva);
        while ($riva = pg_fetch_row($sqliva_var)) {
            $retIva = $retIva + $riva[0];
        }




        $sqlfuente = "select valor_retencion from retencion_fuente_factura_venta where id_factura='" . $fac[3] . "'";
        $sqlfuente_var = pg_query($sqlfuente);
        while ($rfuente = pg_fetch_row($sqlfuente_var)) {
            $retFuente = $retFuente + $rfuente[0];
        }
    }
    $total = $total + $baseimp;

    if ($conf > 0) {

        $itemElement = $xml->createElement('detalleVentas');
        $itemElement = $channelElement->appendChild($itemElement);

        $tpIdClienteElement = $xml->createElement('tpIdCliente', $codigo);
        $tpIdClienteElement = $itemElement->appendChild($tpIdClienteElement);

        $idClienteElement = $xml->createElement('idCliente', $cli[2]);
        $idClienteElement = $itemElement->appendChild($idClienteElement);

        if ($codigo != '07') {
            $parteRelVtasElement = $xml->createElement('parteRelVtas', 'NO');
            $parteRelVtasElement = $itemElement->appendChild($parteRelVtasElement);
        }

        $tipoComprobanteElement = $xml->createElement('tipoComprobante', '18');
        $tipoComprobanteElement = $itemElement->appendChild($tipoComprobanteElement);


        $tipoEmisionElement = $xml->createElement('tipoEmision', 'F');
        $tipoEmisionElement = $itemElement->appendChild($tipoEmisionElement);

        $numeroComprobantesElement = $xml->createElement('numeroComprobantes', $conf);
        $numeroComprobantesElement = $itemElement->appendChild($numeroComprobantesElement);

        $baseNoGraIvaElement = $xml->createElement('baseNoGraIva', '0.00');
        $baseNoGraIvaElement = $itemElement->appendChild($baseNoGraIvaElement);

        $baseImponibleElement = $xml->createElement('baseImponible', number_format(round($basenoiva, 2), 2, '.', ''));
        $baseImponibleElement = $itemElement->appendChild($baseImponibleElement);

        $baseImpGravElement = $xml->createElement('baseImpGrav', number_format(round($baseimp, 2), 2, '.', ''));
        $baseImpGravElement = $itemElement->appendChild($baseImpGravElement);

        $montoIvaElement = $xml->createElement('montoIva', number_format(round($monIva, 2), 2, '.', ''));
        $montoIvaElement = $itemElement->appendChild($montoIvaElement);

        $montoIceElement = $xml->createElement('montoIce', '0.00');
        $montoIceElement = $itemElement->appendChild($montoIceElement);

        $valorRetIvaElement = $xml->createElement('valorRetIva', number_format(round($retIva, 2), 2, '.', ''));
        $valorRetIvaElement = $itemElement->appendChild($valorRetIvaElement);

        $valorRetRentaElement = $xml->createElement('valorRetRenta', number_format(round($retFuente, 2), 2, '.', ''));
        $valorRetRentaElement = $itemElement->appendChild($valorRetRentaElement);


        $formaPagoElement = $xml->createElement('formasDePago');
        $formaPagoElement = $itemElement->appendChild($formaPagoElement);


        $codEstabElement = $xml->createElement('formaPago', '01');
        $codEstabElement = $formaPagoElement->appendChild($codEstabElement);
    }
}


////////////////////NOTA DE CREDITO VENTA///////////////////////
////////////////////NOTA DE CREDITO VENTA///////////////////////
////////////////////NOTA DE CREDITO VENTA///////////////////////
////////////////////NOTA DE CREDITO VENTA///////////////////////
////////////////////NOTA DE CREDITO VENTA///////////////////////
////////////////////NOTA DE CREDITO VENTA///////////////////////
////////////////////NOTA DE CREDITO VENTA///////////////////////


$sqlcliente = "SELECT DISTINCT on (identificacion) identificacion,  id_tdocu, identificacion, nombres_cli from clientes where estado='Activo' ";

$clientes = pg_query($sqlcliente);
$conf = 0;
$basenoiva = 0;
$baseimp = 0;
$monIva = 0;
$retFuente = 0;
$retIva = 0;
$total = 0;

//if($clientes){
//if (pg_fetch_row($clientes)>0) {
while ($cli = pg_fetch_row($clientes)) {
    if ($cli[1] == "1" && $cli[2] == "9999999999999") {
        $codigo = '07';
    } else if ($cli[1] == "1") {
        $codigo = '04';
    } else if ($cli[1] == "2") {
        $codigo = '05';
    } else if ($cli[1] == "3") {
        $codigo = '06';
    }

    $id = $cli[2];
//  echo ''."SELECT tarifa0, tarifa12, iva_venta, id_devolucion_venta from devolucion_venta where  id_cliente=$id and ( estado='Activo'  or  estado='2') and fecha_actual::text like '%" . $anioDec . "-" . $mesDec . "-%'   ORDER BY id_devolucion_venta";
    $sqlfactura = "SELECT tarifa0, tarifa12, iva_venta, id_devolucion_venta from devolucion_venta,clientes where  devolucion_venta.id_cliente=clientes.id_cliente and clientes.identificacion='$id' and ( devolucion_venta.estado='Activo'  or  devolucion_venta.estado='2') and fecha_actual::text like'%" . $anioDec . "-" . $mesDec . "-%'   ORDER BY id_devolucion_venta";
    $conf = 0;
    $basenoiva = 0;
    $baseimp = 0;
    $monIva = 0;
    $retFuente = 0;
    $retIva = 0;
    $facturas = pg_query($sqlfactura);

    while ($fac = pg_fetch_row($facturas)) {
        $conf = $conf + 1;
        $basenoiva = $basenoiva + $fac[0];
        $baseimp = $baseimp + $fac[1];
        $monIva = $monIva + $fac[2];

        $sqliva = "select valor_retencion from retencion_iva_factura_venta where id_factura='" . $fac[3] . "'";
        $sqliva_var = pg_query($sqliva);
        while ($riva = pg_fetch_row($sqliva_var)) {
            $retIva = $retIva + $riva[0];
        }




        $sqlfuente = "select valor_retencion from retencion_fuente_factura_venta where id_factura='" . $fac[3] . "'";
        $sqlfuente_var = pg_query($sqlfuente);
        while ($rfuente = pg_fetch_row($sqlfuente_var)) {
            $retFuente = $retFuente + $rfuente[0];
        }
    }
    $total = $total + $baseimp;

    if ($conf > 0) {

        $itemElement = $xml->createElement('detalleVentas');
        $itemElement = $channelElement->appendChild($itemElement);

        $tpIdClienteElement = $xml->createElement('tpIdCliente', $codigo);
        $tpIdClienteElement = $itemElement->appendChild($tpIdClienteElement);

        $idClienteElement = $xml->createElement('idCliente', $cli[2]);
        $idClienteElement = $itemElement->appendChild($idClienteElement);

        if ($codigo != '07') {
            $parteRelVtasElement = $xml->createElement('parteRelVtas', 'NO');
            $parteRelVtasElement = $itemElement->appendChild($parteRelVtasElement);
        }

        $tipoComprobanteElement = $xml->createElement('tipoComprobante', '04');
        $tipoComprobanteElement = $itemElement->appendChild($tipoComprobanteElement);


        $tipoEmisionElement = $xml->createElement('tipoEmision', 'F');
        $tipoEmisionElement = $itemElement->appendChild($tipoEmisionElement);

        $numeroComprobantesElement = $xml->createElement('numeroComprobantes', $conf);
        $numeroComprobantesElement = $itemElement->appendChild($numeroComprobantesElement);

        $baseNoGraIvaElement = $xml->createElement('baseNoGraIva', '0.00');
        $baseNoGraIvaElement = $itemElement->appendChild($baseNoGraIvaElement);

        $baseImponibleElement = $xml->createElement('baseImponible', number_format(round($basenoiva, 2), 2, '.', ''));
        $baseImponibleElement = $itemElement->appendChild($baseImponibleElement);

        $baseImpGravElement = $xml->createElement('baseImpGrav', number_format(round($baseimp, 2), 2, '.', ''));
        $baseImpGravElement = $itemElement->appendChild($baseImpGravElement);

        $montoIvaElement = $xml->createElement('montoIva', number_format(round($monIva, 2), 2, '.', ''));
        $montoIvaElement = $itemElement->appendChild($montoIvaElement);

        $montoIceElement = $xml->createElement('montoIce', '0.00');
        $montoIceElement = $itemElement->appendChild($montoIceElement);

        $valorRetIvaElement = $xml->createElement('valorRetIva', number_format(round('0.00', 2), 2, '.', ''));
        $valorRetIvaElement = $itemElement->appendChild($valorRetIvaElement);

        $valorRetRentaElement = $xml->createElement('valorRetRenta', number_format(round('0.00', 2), 2, '.', ''));
        $valorRetRentaElement = $itemElement->appendChild($valorRetRentaElement);


    
    }
}

//}
//}
$itemElement = $xml->createElement('ventasEstablecimiento');
$itemElement = $root->appendChild($itemElement);

$ventaEstElement = $xml->createElement('ventaEst');
$ventaEstElement = $itemElement->appendChild($ventaEstElement);

$codEstabElement = $xml->createElement('codEstab', '001');
$codEstabElement = $ventaEstElement->appendChild($codEstabElement);

$ventasEstabElement = $xml->createElement('ventasEstab', number_format(round(floatval($tt)-floatval($ttnc), 2), 2, '.', ''));
$ventasEstabElement = $ventaEstElement->appendChild($ventasEstabElement);
$montoIva = number_format(round($row[9], 2), 2, '.', '');
$ivaCompElement = $xml->createElement('ivaComp', number_format(round($montoIva, 2), 2, '.', ''));
$ivaCompElement = $ventaEstElement->appendChild($ivaCompElement);

////////FACTURAS ANULADAS/////////

$anulada = "select num_factura, id_factura_venta from factura_venta where estado='Pasivo' and fecha_actual::text like '%" . $anioDec . "-" . $mesDec . "-%' order by id_factura_venta";
$fac_fin = '000000000';
$fac_ini = '000000000';
$fac_an = pg_query($anulada);
if ($fac_an) {
    $i = 0;
    $vector;
    while ($row = pg_fetch_row($fac_an)) {
        $vector[$i] = substr($row[0], -9);
        $i += 1;
    }
    $x = count($vector);
    $sqlauto = "select num_autorizacion from autorizacion_venta ";
//   $sqlauto="select num_autorizacion from autorizacion_venta where factura_inicio <= '".$vector[0]."' and factura_fin >= '".$vector[0]."'";

    $auto = pg_query($sqlauto);
    while ($a = pg_fetch_row($auto)) {
        $autori1 = $a[0];
    }
    if ($autori1 != "") {
        $anuladosElement = $xml->createElement('anulados');
        $anuladosElement = $root->appendChild($anuladosElement);
    }
    for ($i = 0; $i < $x; $i++) {
        $fac_ini = $vector[$i];
        $fac_fin = $vector[$i];

        //$aaa = $xml->createElement('inicio', $fac_ini);
        //$aaa = $anuladosElement->appendChild($aaa);
        $dato = $vector[$i] + 0;
        for ($j = $i; $j < $x; $j++) {
            $sqlauto = "select num_autorizacion from autorizacion_venta where factura_inicio <= '" . $vector[$j] . "' and factura_fin >= '" . $vector[$j] . "'";

            $auto = pg_query($sqlauto);
            while ($b = pg_fetch_row($auto)) {
                $autori2 = $b[0];
            }
            //$aaa = $xml->createElement('dato', $dato);
            //$aaa = $anuladosElement->appendChild($aaa);
            if ($dato == ($fac_ini + 0)) {
                $fac_fin = $vector[$j];
                //$aaa = $xml->createElement('acumular', $dato);
                //$aaa = $anuladosElement->appendChild($aaa);
                $dato += 1;
            } else if ($autori2 != $autori1) {

                $detalleAnuladosElement = $xml->createElement('detalleAnulados');
                $detalleAnuladosElement = $anuladosElement->appendChild($detalleAnuladosElement);

                $tipoComprobanteElement = $xml->createElement('tipoComprobante', '01');
                $tipoComprobanteElement = $detalleAnuladosElement->appendChild($tipoComprobanteElement);

                $establecimientoElement = $xml->createElement('establecimiento', '001');
                $establecimientoElement = $detalleAnuladosElement->appendChild($establecimientoElement);

                $puntoEmisionElement = $xml->createElement('puntoEmision', '001');
                $puntoEmisionElement = $detalleAnuladosElement->appendChild($puntoEmisionElement);

                $secuencialInicioElement = $xml->createElement('secuencialInicio', $fac_ini);
                $secuencialInicioElement = $detalleAnuladosElement->appendChild($secuencialInicioElement);

                $secuencialFinElement = $xml->createElement('secuencialFin', $fac_fin);
                $secuencialFinElement = $detalleAnuladosElement->appendChild($secuencialFinElement);


                ///////AUTORIZACION FACTURAS VENTA//////

                $autorizacionElement = $xml->createElement('autorizacion', $autori1);
                $autorizacionElement = $detalleAnuladosElement->appendChild($autorizacionElement);

                $i = $j - 1;
                $j = $x;
                $autori1 = $autori2;
            } else if ($dato == ($vector[$j] + 0)) {
                //$aaa = $xml->createElement('fin', $dato);
                //$aaa = $anuladosElement->appendChild($aaa);
                $fac_fin = $vector[$j];
                $dato += 1;
            } else {
                /* $aaa = $xml->createElement('aaa', $autori1);
                  $aaa = $anuladosElement->appendChild($aaa);

                  $bbb = $xml->createElement('bbb', $autori2);
                  $bbb = $anuladosElement->appendChild($bbb); */

                $detalleAnuladosElement = $xml->createElement('detalleAnulados');
                $detalleAnuladosElement = $anuladosElement->appendChild($detalleAnuladosElement);

                $tipoComprobanteElement = $xml->createElement('tipoComprobante', '01');
                $tipoComprobanteElement = $detalleAnuladosElement->appendChild($tipoComprobanteElement);

                $establecimientoElement = $xml->createElement('establecimiento', '001');
                $establecimientoElement = $detalleAnuladosElement->appendChild($establecimientoElement);

                $puntoEmisionElement = $xml->createElement('puntoEmision', '001');
                $puntoEmisionElement = $detalleAnuladosElement->appendChild($puntoEmisionElement);

                $secuencialInicioElement = $xml->createElement('secuencialInicio', $fac_ini);
                $secuencialInicioElement = $detalleAnuladosElement->appendChild($secuencialInicioElement);

                $secuencialFinElement = $xml->createElement('secuencialFin', $fac_fin);
                $secuencialFinElement = $detalleAnuladosElement->appendChild($secuencialFinElement);

                ///////AUTORIZACION FACTURAS VENTA//////
                //$sqlauto="select num_autorizacion from autorizacion_venta where factura_inicio <= '".$fac_ini."' and factura_fin >= '".$fac_ini."'";
                $sqlauto = "select num_autorizacion from autorizacion_venta ";
                $auto = pg_query($sqlauto);
                while ($a = pg_fetch_row($auto)) {
                    $autorizacionElement = $xml->createElement('autorizacion', $a[0]);
                    $autorizacionElement = $detalleAnuladosElement->appendChild($autorizacionElement);
                }

                $i = $j - 1;
                $j = $x;
            }
        }
    }
    if ($fac_ini != '000000000' || $fac_fin != '000000000') {
        $detalleAnuladosElement = $xml->createElement('detalleAnulados');
        $detalleAnuladosElement = $anuladosElement->appendChild($detalleAnuladosElement);

        $tipoComprobanteElement = $xml->createElement('tipoComprobante', '01');
        $tipoComprobanteElement = $detalleAnuladosElement->appendChild($tipoComprobanteElement);

        $establecimientoElement = $xml->createElement('establecimiento', '001');
        $establecimientoElement = $detalleAnuladosElement->appendChild($establecimientoElement);

        $puntoEmisionElement = $xml->createElement('puntoEmision', '001');
        $puntoEmisionElement = $detalleAnuladosElement->appendChild($puntoEmisionElement);

        $secuencialInicioElement = $xml->createElement('secuencialInicio', $fac_ini);
        $secuencialInicioElement = $detalleAnuladosElement->appendChild($secuencialInicioElement);

        $secuencialFinElement = $xml->createElement('secuencialFin', $fac_fin);
        $secuencialFinElement = $detalleAnuladosElement->appendChild($secuencialFinElement);


        ///////AUTORIZACION FACTURAS VENTA//////
        $sqlauto = "select num_autorizacion from autorizacion_venta ";
        //   $sqlauto="select num_autorizacion from autorizacion_venta where factura_inicio <= '".$fac_ini."' and factura_fin >= '".$fac_ini."'";
        $auto = pg_query($sqlauto);
        while ($a = pg_fetch_row($auto)) {
            $autorizacionElement = $xml->createElement('autorizacion', $a[0]);
            $autorizacionElement = $detalleAnuladosElement->appendChild($autorizacionElement);
        }
        $i = $j - 1;
        $j = $x;
        $autori1 = $autori2;
    }
//   ///////AUTORIZACION FACTURAS VENTA//////
//  $sqlauto="select num_autorizacion from autorizacion_venta ";
//           //   $sqlauto="select num_autorizacion from autorizacion_venta where factura_inicio <= '".$fac_ini."' and factura_fin >= '".$fac_ini."'";
//   $auto=pg_query($sqlauto);
//   while($a=pg_fetch_row($auto)){
//      $autorizacionElement = $xml->createElement('autorizacion', $a[0]);
//      $autorizacionElement = $detalleAnuladosElement->appendChild($autorizacionElement);
//   }
    $xml->formatOutput = true;
    $el_xml = $xml->saveXML();
    $xml->save('../atsxml/'.$esquema .'/'. $nombreArchivo . '.xml');
}


//francis 30032023


////Actualizare
echo $xml->saveXML();
exit();
?>