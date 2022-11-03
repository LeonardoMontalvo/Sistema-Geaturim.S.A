<?php

function generarXMLRET($id, $codDoc, $ambiente, $emision)
{
    $consulta = pg_query(
        "SELECT nombre_empresa, ruc_empresa, direccion_empresa, nombre_comercial,
        obligacion, establecimiento, punto_emision, id_factura_compra, fecha_emision,
        fc.num_serie as sec_doc, rffc.num_serie, rffc.clave, identificacion_pro, 
        empresa_pro, direccion_pro, correo, codigo_tdocu, 
        case when telefono!='' then telefono else celular end as telefono_pro
        from empresa e left join factura_compra fc using (id_empresa)
        left join retencion_fuente_factura_compra rffc on rffc.id_factura=fc.id_factura_compra 
        left join proveedores p using (id_proveedor) 
        left join tipo_documento using (id_tdocu) 
        where rffc.id_retencion_fuente_factura_compra='" . $id . "'"
    );

    while ($row = pg_fetch_assoc($consulta)) {
        $razonSocial = $row['nombre_empresa'];
        $ruc = $row['ruc_empresa'];
        $direccionEstablecimiento = $row['direccion_empresa'];
        $direcionMatriz = $row['direccion_empresa'];
        $nombreComercial = $row['nombre_comercial'];
        $obligado = $row['obligacion'];
        // $nroContribuyente = $row[19];
        /* $establecimiento = $row['establecimiento'];
        $puntoEmision = $row['punto_emision']; */
        $id_fact = $row['id_factura_compra'];
        // $fecha_retencion = $row[29];
        // $date_retencion = new DateTime($fecha_retencion);
        // $fecharetencionfinal = $date_retencion->format('d/m/Y');
        // $fechaAut = $row['fecha_aut'];
        $fechaEmision = $row['fecha_emision'];
        $ip = $fechaEmision;
        $fechasepar = split("\-", $ip);
        $mes = $fechasepar[1];
        $anio = $fechasepar[0];
        $periodo_fiscal = "$mes" . "/" . "$anio";
        $date = new DateTime($fechaEmision);
        $fechaEmisionfinal = $date->format('d/m/Y');
        $secuencialdoc = $row['sec_doc'];
        //$ip = $secuencial;
        $iparrdoc = explode("-", $secuencialdoc);
        $secuencialresultdoc = $iparrdoc[2];
        $secuencial1doc = $iparrdoc[0];
        $secuencial2doc = $iparrdoc[1];
        $secuencialresultuni = "$secuencial1doc" . "$secuencial2doc" . "$secuencialresultdoc";
        //$secuencialresultuni="123123123123123";
        $secuencial = $row['num_serie'];
        //$ip = $secuencial;
        $iparr = explode("-", $secuencial);
        $establecimiento = $iparr[0];
        $puntoEmision = $iparr[1];
        $secuencialresult = $iparr[2];
        // $secuencial1 = $iparr[0];
        // $secuencial2 = $iparr[1];
        $claveAcceso = $row['clave'];
        $identificacion = $row['identificacion_pro'];
        $contribuyente = $row['empresa_pro'];
        $direcion = $row['direccion_pro'];
        $telefono = $row['telefono_pro'];
        $email = $row['correo'];
        $tipoDocumento = '01';
        $tipoIdentificacion = $row['codigo_tdocu'];
        // $ejercicioFiscal = 01 / 2014;
        $retencion = "No. Resolución: NAC-DNCRASC20-00000001";
    }
    $ceros = 9;
    $temp = '';
    $tam = $ceros - strlen($secuencial);
    for ($i = 0; $i < $tam; $i++) {
        $temp = $temp . '0';
    }
    $secuencial = $temp . '' . $secuencial;
    $s = "";
    $s = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $s .= "<comprobanteRetencion id=\"comprobante\" version=\"1.0.0\">\n";
    $s .= "<infoTributaria>\n";
    $s .= "<ambiente>" . $ambiente . "</ambiente>\n";
    $s .= "<tipoEmision>" . $emision . "</tipoEmision>\n";
    $s .= "<razonSocial>" . substr($razonSocial, 0, 300) . "</razonSocial>\n";
    $s .= "<nombreComercial>" . substr($nombreComercial, 0, 300) . "</nombreComercial>\n";
    $s .= "<ruc>" . substr($ruc, 0, 13) . "</ruc>\n";
    $s .= "<claveAcceso>" . substr($claveAcceso, 0, 49) . "</claveAcceso>\n";
    $s .= "<codDoc>" . substr($codDoc, 0, 2) . "</codDoc>\n";
    $s .= "<estab>" . substr($establecimiento, 0, 3) . "</estab>\n";
    $s .= "<ptoEmi>" . substr($puntoEmision, 0, 3) . "</ptoEmi>\n";
    $s .= "<secuencial>" . substr($secuencialresult, 0, 9) . "</secuencial>\n";
    $s .= "<dirMatriz>" . substr($direcionMatriz, 0, 300) . "</dirMatriz>\n";
    $s .= "</infoTributaria>\n";
    $s .= "<infoCompRetencion>\n";
    $s .= "<fechaEmision>" . substr($fechaEmisionfinal, 0, 10) . "</fechaEmision>\n";
    $s .= "<dirEstablecimiento>" . substr($direccionEstablecimiento, 0, 300) . "</dirEstablecimiento>\n";
    //if($nroContribuyente != '')
    //  $s .= "<contribuyenteEspecial>".substr($nroContribuyente,0,13)."</contribuyenteEspecial>\n";
    $s .= "<obligadoContabilidad>" . $obligado . "</obligadoContabilidad>\n";
    $s .= "<tipoIdentificacionSujetoRetenido>" . substr($tipoIdentificacion, 0, 2) . "</tipoIdentificacionSujetoRetenido>\n";
    $s .= "<razonSocialSujetoRetenido>" . substr($contribuyente, 0, 300) . "</razonSocialSujetoRetenido>\n";
    $s .= "<identificacionSujetoRetenido>" . substr($identificacion, 0, 30) . "</identificacionSujetoRetenido>\n";
    $s .= "<periodoFiscal>" . $periodo_fiscal . "</periodoFiscal>\n";
    $s .= "</infoCompRetencion>\n";
    $s .= "<impuestos>\n";
    $consultaretencion = pg_query("select 
       CD.base_imponible, 
       R.nombre_trete, 
       CD.porsentaje, 
       CD.valor_retenido,
       R.codigo_trete, 
       TR.codigo_formulario
       from retencion_fuente_factura_compra CR 
       inner join detallecomprobanteretencion CD on CR.id_retencion_fuente_factura_compra = CD.id_retencion_fuente_factura_compra 
       inner join tipo_retencion R on CD.id_trete = R.id_trete 
       inner join retencion_fuentes TR on CD.id_retencion_fuentes = TR.id_retencion_fuentes where CR.id_factura= $id_fact   and CR.id_gastos='1'");
    //echo $sql;
    $totalSinImpuestos = 0;
    $valporcentiva = 0;
    while ($rowre = pg_fetch_row($consultaretencion)) {
        $totalSinImpuestos = $rowre[1];

        $s .= "<impuesto>\n";
        $s .= "<codigo>" . $rowre[4] . "</codigo>\n";
        if ($totalSinImpuestos == 'IVA') {
            if ($rowre[2] == '10') {
                $valporcentiva = 9;
            }
            if ($rowre[2] == '20') {
                $valporcentiva = 10;
            }
            if ($rowre[2] == '30') {
                $valporcentiva = 1;
            }
            if ($rowre[2] == '50') {
                $valporcentiva = 11;
            }
            if ($rowre[2] == '70') {
                $valporcentiva = 2;
            }
            if ($rowre[2] == '100') {
                $valporcentiva = 3;
            }


            $s .= "<codigoRetencion>" . $valporcentiva . "</codigoRetencion>\n";
        } else {
            $s .= "<codigoRetencion>" . $rowre[5] . "</codigoRetencion>\n";
        }

        $s .= "<baseImponible>" . number_format($rowre[0], 2, '.', '') . "</baseImponible>\n";

        $s .= "<porcentajeRetener>" . number_format($rowre[2], 2, '.', '') . "</porcentajeRetener>\n";
        $s .= "<valorRetenido>" . number_format($rowre[3], 2, '.', '') . "</valorRetenido>\n";
        $s .= "<codDocSustento>" . substr($tipoDocumento, 0, 2) . "</codDocSustento>\n";
        $s .= "<numDocSustento>" . substr($secuencialresultuni, 0, 15) . "</numDocSustento>\n";
        $s .= "<fechaEmisionDocSustento>" . substr($fechaEmisionfinal, 0, 10) . "</fechaEmisionDocSustento>\n";
        $s .= "</impuesto>\n";
    }


    $s .= "</impuestos>\n";
    $s .= "<infoAdicional>\n";
    $s .= "<campoAdicional nombre=\"DIRECCION\">" . ' ' . substr($direcion, 0, 299) . "</campoAdicional>\n";
    $s .= "<campoAdicional nombre=\"TELEFONO\">" . ' ' . utf8_decode(substr($telefono, 0, 299)) . "</campoAdicional>\n";
    $s .= "<campoAdicional nombre=\"EMAIL\">" . ' ' . utf8_decode(substr($email, 0, 299)) . "</campoAdicional>\n";
    $s .= "<campoAdicional nombre=\"Agente de Retención\">" . ' ' . substr(htmlspecialchars($retencion), 0, 299) . "</campoAdicional>\n";
    $s .= "<campoAdicional nombre=\"NOMBRE\">Contribuyente Regimen Microempresas</campoAdicional>\n";
    $s .= "</infoAdicional>";
    $s .= "\n</comprobanteRetencion>";
    return $s;
}

function generarXMLCDATAFAC($data)
{
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
