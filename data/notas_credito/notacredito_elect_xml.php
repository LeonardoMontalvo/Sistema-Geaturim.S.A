<?php

function generarXMLNOTA($id, $comprobante, $ambiente, $emision) {


    $consulta = pg_query(
            "SELECT nombre_empresa, ruc_empresa, direccion_empresa, nombre_comercial,
        obligacion, establecimiento, punto_emision, fecha_actual as fecha_emision, 
        hora_actual as hora_emision, num_nota_credito, num_nota_serie, num_serie, dv.clave,
        motivo, identificacion, nombres_cli, direccion_cli, codigo_tdocu, correo,
        case when telefono!='' then telefono else celular end as telefono_cli 
        from empresa e inner join devolucion_venta dv using(id_empresa)
        inner join clientes c using(id_cliente) 
        inner join tipo_documento td using(id_tdocu) 
        where dv.id_devolucion_venta='" . $id . "' "
    );
    while ($row = pg_fetch_assoc($consulta)) {
        $razonSocial = $row['nombre_empresa'];
        $ruc = $row['ruc_empresa'];
        $direcionMatriz = $row['direccion_empresa'];
        $direccionEstablecimiento = $row['direccion_empresa'];
        $nombreComercial = $row['nombre_comercial'];
        $obligado = $row['obligacion'];
        // $nroContribuyente = $row[19];
        $establecimiento = $row['establecimiento'];
        $puntoEmision = $row['punto_emision'];
        $fechaEmision = $row['fecha_emision'];
        $date = new DateTime($fechaEmision);
        $fechaEmision = $date->format('d/m/Y ');
        $horaEmision = $row['hora_emision'];
        // $fechaAut = $row[30];
        $secuencial = $row['num_nota_credito'];
        $ip = $secuencial;
        $iparr = split("\-", $ip);
        $secuencialresult = $iparr[2];
        $secuencialfac = "$row[num_nota_serie]" . "-" . "$row[num_serie]";
        // $ipfac = $secuencialfac;
        // $iparrfac = split("\-", $ipfac);
        // $secuencialresultfac = $iparrfac[2];
        $claveAcceso = $row['clave'];
        // $numeroAutorizacion = $row[43];
        $motivo = $row['motivo'];
        $identificacion = $row['identificacion'];
        $contribuyente = $row['nombres_cli'];
        $direcion = $row['direccion_cli'];
        $telefono = $row['telefono_cli'];
        $email = $row['correo'];
        $tipoIdentificacion = $row['codigo_tdocu'];
        $retencion = "No. Resolución: NAC-DNCRASC20-00000001";
    }
    $ceros = 9;
    $temp = '';
    $tam = $ceros - strlen($secuencialresult);
    for ($i = 0; $i < $tam; $i++) {
        $temp = $temp . '0';
    }
    $secuencialresult = $temp . '' . $secuencialresult;
    $s = "";
    $s = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $s .= "<notaCredito id=\"comprobante\" version=\"1.1.0\">\n";
    $s .= "<infoTributaria>\n";
    $s .= "<ambiente>" . $ambiente . "</ambiente>\n";
    $s .= "<tipoEmision>" . $emision . "</tipoEmision>\n";
    $s .= "<razonSocial>" . substr(htmlspecialchars($razonSocial), 0, 300) . "</razonSocial>\n";
    $s .= "<nombreComercial>" . substr(htmlspecialchars($nombreComercial), 0, 300) . "</nombreComercial>\n";
    $s .= "<ruc>" . substr($ruc, 0, 13) . "</ruc>\n";
    $s .= "<claveAcceso>" . substr($claveAcceso, 0, 49) . "</claveAcceso>\n";
    $s .= "<codDoc>" . substr($comprobante, 0, 2) . "</codDoc>\n";
    $s .= "<estab>" . substr($establecimiento, 0, 3) . "</estab>\n";
    $s .= "<ptoEmi>" . substr($puntoEmision, 0, 3) . "</ptoEmi>\n";
    $s .= "<secuencial>" . substr($secuencial, 0, 9) . "</secuencial>\n";
    $s .= "<dirMatriz>" . substr($direcionMatriz, 0, 300) . "</dirMatriz>\n";
    $s .= "<contribuyenteRimpe>".htmlspecialchars("CONTRIBUYENTE RÉGIMEN RIMPE")."</contribuyenteRimpe>\n";
//    $s .= "<agenteRetencion>1</agenteRetencion>\n";
    $s .= "<contribuyenteRimpe>".htmlspecialchars("CONTRIBUYENTE RÉGIMEN RIMPE")."</contribuyenteRimpe>\n";
//    $s .= "<agenteRetencion>1</agenteRetencion>\n";
    $s .= "</infoTributaria>\n";
    $s .= "<infoNotaCredito>\n";
    $s .= "<fechaEmision>" . substr($fechaEmision, 0, 10) . "</fechaEmision>\n";
    $s .= "<dirEstablecimiento>" . substr($direccionEstablecimiento, 0, 300) . "</dirEstablecimiento>\n";
    $s .= "<tipoIdentificacionComprador>" . substr($tipoIdentificacion, 0, 2) . "</tipoIdentificacionComprador>\n";
    $s .= "<razonSocialComprador>" . substr(htmlspecialchars($contribuyente), 0, 300) . "</razonSocialComprador>\n";
    $s .= "<identificacionComprador>" . substr(htmlspecialchars($identificacion), 0, 20) . "</identificacionComprador>\n";
    // if($nroContribuyente != '')
    // $s .= "<contribuyenteEspecial>".substr($nroContribuyente,0,13)."</contribuyenteEspecial>\n";
    $s .= "<obligadoContabilidad>" . $obligado . "</obligadoContabilidad>\n";
//	$s .= "<rise>"."</rise>\n";
    $s .= "<codDocModificado>" . substr('01', 0, 2) . "</codDocModificado>\n";
    $s .= "<numDocModificado>" . $secuencialfac . "</numDocModificado>\n";
    $s .= "<fechaEmisionDocSustento>" . substr($fechaEmision, 0, 10) . "</fechaEmisionDocSustento>\n";
    $consultadetalle = pg_query("SELECT * FROM devolucion_venta WHERE id_devolucion_venta='" . $id . "'");
    $descuento = 0;
    $totalSinImpuestos = 0;
    $total = 0;
    while ($row = pg_fetch_row($consultadetalle)) {
        $sinivaCERO = $row[9];
        $valorsuma = $sinivaCERO + $row[10];

        if ($row[9] != 0) {
            $calculo = $row[9] + $row[11];
            $s .= "<totalSinImpuestos>" . number_format($row[9], 2, '.', '') . "</totalSinImpuestos>\n";
            $s .= "<valorModificacion>" . number_format($calculo, 2, '.', '') . "</valorModificacion>\n";
        } else {
            $calculo = $row[10] + $row[11];
            $siniva = $row[9];
            $s .= "<totalSinImpuestos>" . number_format($row[10], 2, '.', '') . "</totalSinImpuestos>\n";
            $s .= "<valorModificacion>" . number_format($calculo, 2, '.', '') . "</valorModificacion>\n";
        }


        $totalSinImpuestos = $row[10];
        $tarifa = $row[10];
        $iva = $row[11];
        $descuento = $row[12];
        $total = $row[13];
    }


    $s .= "<moneda>" . "DOLAR" . "</moneda>\n";
    $s .= "<totalConImpuestos>\n";

    $s .= "<totalImpuesto>\n";
    $s .= "<codigo>" . '2' . "</codigo>\n";
    $s .= "<codigoPorcentaje>" . '2' . "</codigoPorcentaje>\n";
    $s .= "<baseImponible>" . number_format($totalSinImpuestos, 2, '.', '') . "</baseImponible>\n";
    $s .= "<valor>" . number_format($iva, 2, '.', '') . "</valor>\n";
    $s .= "</totalImpuesto>\n";


    $s .= "<totalImpuesto>\n";
    $s .= "<codigo>" . '2' . "</codigo>\n";
    $s .= "<codigoPorcentaje>" . '0' . "</codigoPorcentaje>\n";
    $s .= "<baseImponible>" . number_format($sinivaCERO, 2, '.', '') . "</baseImponible>\n";
    $s .= "<valor>" . number_format(0, 2, '.', '') . "</valor>\n";
    $s .= "</totalImpuesto>\n";


    $s .= "</totalConImpuestos>\n";
    $s .= "<motivo>NC generada por: $motivo, FECHA/HORA: $fechaEmision/$horaEmision</motivo>\n";
//         $s .= "<motivo>DEVOLUCION</motivo>\n";
    $s .= "</infoNotaCredito>\n";
    $s .= "<detalles>\n";
    $consultaformapago = pg_query("select P.codigo, P.articulo, D.cantidad, D.precio_venta, D.descuento_producto, D.precio_venta,f.tarifa12, (D.cantidad::float*D.precio_venta::float) as tarifa12,((D.cantidad::float*D.precio_venta::float)*0.12) as iva12, p.iva,D.unidad_medida  from devolucion_venta F,detalle_devolucion_venta D  , productos P where  d.cod_productos =P.cod_productos   and D.id_devolucion_venta = F.id_devolucion_venta AND F.id_devolucion_venta='" . $id . "'");
    while ($row = pg_fetch_row($consultaformapago)) {
        $tarifa12 = 0;
        $tarifa12 = $row[3];
        $canti = 0;
        $canti = $row[2];

        $iva_venta = $row[9];
        $tarifa12 = $tarifa12 * $canti;
        $baseimponible = $row[7];
        $desc = 0;

        $desc = $row[4];
        $valcien = 100;
        $Descucaltres = ($tarifa12 / $valcien) * $desc;
        $tarifa12sin = $tarifa12 - $Descucaltres;
        $baseimponible = $baseimponible - $Descucaltres;
        $s .= "<detalle>\n";
        $s .= "<codigoInterno>" . substr($row[0], 0, 25) . "</codigoInterno>\n";
//            $s .= "<codigoAdicional>".substr($row[0],0,25)."</codigoAdicional>\n";
        if ($row[10] != '') {
            $s .= "<descripcion>" . substr(htmlspecialchars($row[1] . "(" . $row[10] . ")"), 0, 300) . "</descripcion>\n";
        } else {
            $s .= "<descripcion>" . substr(htmlspecialchars($row[1]), 0, 300) . "</descripcion>\n";
        }
        $s .= "<cantidad>" . number_format($row[2], 2, '.', '') . "</cantidad>\n";
        $s .= "<precioUnitario>" . number_format($row[3], 2, '.', '') . "</precioUnitario>\n";
        $s .= "<descuento>" . number_format($Descucaltres, 2, '.', '') . "</descuento>\n";
        $s .= "<precioTotalSinImpuesto>" . number_format($tarifa12sin, 2, '.', '') . "</precioTotalSinImpuesto>\n";
        $s .= "<impuestos>\n";

        if ($iva_venta == 'No') {
            $s .= "<impuesto>\n";
            $s .= "<codigo>" . '2' . "</codigo>\n";
            $s .= "<codigoPorcentaje>" . '0' . "</codigoPorcentaje>\n";
            $s .= "<tarifa>" . '0.00' . "</tarifa>\n";
            $s .= "<baseImponible>" . number_format($tarifa12sin, 2, '.', '') . "</baseImponible>\n";
            $s .= "<valor>0.00</valor>\n";
            $s .= "</impuesto>\n";
            $s .= "</impuestos>\n";
            $s .= "</detalle>\n";
        } else {
            $iva = $row[8];
            $s .= "<impuesto>\n";
            $s .= "<codigo>2</codigo>\n";
            $s .= "<codigoPorcentaje>2</codigoPorcentaje>\n";
            $s .= "<tarifa>" . '12' . "</tarifa>\n";
            $s .= "<baseImponible>" . number_format($baseimponible, 2, '.', '') . "</baseImponible>\n";
            $s .= "<valor>" . number_format($iva, 2, '.', '') . "</valor>\n";
            $s .= "</impuesto>\n";
            $s .= "</impuestos>\n";
            $s .= "</detalle>\n";
        }
    }
    $s .= "</detalles>\n";

    $s .= "<infoAdicional>\n";
    $s .= "<campoAdicional nombre=\"DIRECCION\">" . ' ' . substr($direcion, 0, 299) . "</campoAdicional>\n";
    $s .= "<campoAdicional nombre=\"TELEFONO\">" . ' ' . utf8_decode(substr($telefono, 0, 299)) . "</campoAdicional>\n";
    $s .= "<campoAdicional nombre=\"EMAIL\">" . ' ' . utf8_decode(substr($email, 0, 299)) . "</campoAdicional>\n";  
    $s .= "</infoAdicional>";

    $s .= "\n</notaCredito>";
    return $s;
}

function generarXMLCDATANOTA($data) {
    $s = "";
    $s = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $s .= "<autorizacion>\n";
    $s .= "<estado>" . $data->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado . "</estado>\n";
    $s .= "<numeroAutorizacion>" . $data->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion . "</numeroAutorizacion>\n";
    $s .= "<fechaAutorizacion>" . $data->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion . "</fechaAutorizacion>\n";
    $s .= "<comprobante><![CDATA[" . $data->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->comprobante . "]]></comprobante>";
    $s .= "<mensajes/>\n";
    $s .= "</autorizacion>";
    return $s;
}

?>