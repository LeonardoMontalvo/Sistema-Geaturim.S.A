<?php

function generarXMLNOTA($id, $comprobante, $ambiente, $emision)
{
    $consulta = pg_query(
        "SELECT e.id_empresa, nombre_empresa, ruc_empresa, direccion_empresa, nombre_comercial,
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
        $querypv = "select*from punto_venta where id_punto_venta=$row[id_empresa]";
        $respv = pg_query($querypv);
        $rowpv = pg_fetch_assoc($respv);


        $razonSocial = $row['nombre_empresa'];
        $ruc = $row['ruc_empresa'];
        $direcionMatriz = $row['direccion_empresa'];
        $direccionEstablecimiento = $rowpv['ubicacion'];
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
    $fechaFacutura = getFechaFactura($secuencialfac);
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
    $conf = new Configuracion();
    $check_agente_reten = $conf->getParametroEmpresa("check_agente_reten");
    $agente_reten = $conf->getParametroEmpresa("agente_reten");
    $val_rimpe = $conf->getParametroEmpresa("val_rimpe");
    if ($check_agente_reten != "") {

        $s .= "<agenteRetencion>$agente_reten</agenteRetencion>\n";
    }
    if ($val_rimpe != "" && $val_rimpe != "REGIMEN GENERAL") {
        $s .= "<contribuyenteRimpe>" . htmlspecialchars($val_rimpe) . "</contribuyenteRimpe>\n";
    }
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
    $s .= "<fechaEmisionDocSustento>" . (new DateTime($fechaFacutura))->format('d/m/Y') . "</fechaEmisionDocSustento>\n";
    
    $consultadetalle = pg_query("SELECT * FROM devolucion_venta WHERE id_devolucion_venta='" . $id . "'");
    $descuento = 0;
    $totalSinImpuestos = 0;
    $total = 0;
    while ($row = pg_fetch_row($consultadetalle)) {
        $sinivaCERO = $row[9];
        $valorsuma = $sinivaCERO + $row[10];

        $totalSinImpuestos = $valorsuma;
        $tarifa = $row[10];
        $iva = $row[11];
        $descuento = $row[12];
        $total = $row[13];
    }

    $tarifasimpuesto = obtenervaloresTarifasImpuestoNCredito($id);
    $totalsinimp = 0;
    foreach ($tarifasimpuesto as $value) {
        $totalsinimp += $value["base_imponible"];
    }

    $s .= "<totalSinImpuestos>" . number_format($totalsinimp, 2, '.', '') . "</totalSinImpuestos>\n";
    $s .= "<valorModificacion>" . number_format($total, 2, '.', '') . "</valorModificacion>\n";

    $s .= "<moneda>" . "DOLAR" . "</moneda>\n";
    $s .= "<totalConImpuestos>\n";

    foreach ($tarifasimpuesto as $value) {
        $s .= "<totalImpuesto>\n";
        $s .= "<codigo>" . $value["cod_impuesto"] . "</codigo>\n";
        $s .= "<codigoPorcentaje>" . $value["cod_tarifa"] . "</codigoPorcentaje>\n";
        $s .= "<baseImponible>" . number_format($value["base_imponible"], 2, '.', '') . "</baseImponible>\n";
        $s .= "<valor>" . $value["valor_impuesto"] . "</valor>\n";
        $s .= "</totalImpuesto>\n";
    }

    $s .= "</totalConImpuestos>\n";
    $s .= "<motivo>NC generada por: $motivo, FECHA/HORA: $fechaEmision/$horaEmision</motivo>\n";
    //         $s .= "<motivo>DEVOLUCION</motivo>\n";
    $s .= "</infoNotaCredito>\n";
    $s .= "<detalles>\n";

    $detallesfac = obtenerDetallesNCredito($id);
    foreach ($detallesfac as $value) {

        $descripcion = $value["articulo"];
        if (!empty($value["unidad_medida"])) {
            $descripcion = $value["articulo"] . "(" . $value["unidad_medida"] . ")";
        }

        $cantidad = $value["cantidad"];
        if (!empty(floatval($value["cantidad_unidad"]))) {
            $cantidad = $value["cantidad_unidad"];
        }

        $descuento = ($cantidad * $value["precio_venta"]) * ($value["descuento_producto"] / 100);

        $s .= "<detalle>\n";
        $s .= "<codigoInterno>" . substr($value["codigo"], 0, 25) . "</codigoInterno>\n";
        $s .= "<descripcion>" . substr(htmlspecialchars($descripcion), 0, 300) . "</descripcion>\n";
        $s .= "<cantidad>" . number_format($cantidad, 2, '.', '') . "</cantidad>\n";
        $s .= "<precioUnitario>" . number_format($value["precio_venta"], 2, '.', '') . "</precioUnitario>\n";
        $s .= "<descuento>" . number_format($descuento, 2, '.', '') . "</descuento>\n";
        $s .= "<precioTotalSinImpuesto>" . number_format($value["total_venta"], 2, '.', '') . "</precioTotalSinImpuesto>\n";
        $s .= "<impuestos>\n";
        $s .= "<impuesto>\n";
        $s .= "<codigo>$value[cod_impuesto]</codigo>\n";
        $s .= "<codigoPorcentaje>$value[cod_tarifa]</codigoPorcentaje>\n";
        $s .= "<tarifa>" . number_format($value["tarifa"], 2, '.', '') . "</tarifa>\n";
        $s .= "<baseImponible>" . number_format($value["base_imponible"], 2, '.', '') . "</baseImponible>\n";
        $s .= "<valor>" . number_format($value["valor_impuesto"], 2, '.', '') . "</valor>\n";
        $s .= "</impuesto>\n";
        $s .= "</impuestos>\n";
        $s .= "</detalle>\n";
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

function generarXMLCDATANOTA($data)
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


function getFechaFactura($numfac)
{
    $sql = "select fecha_actual from factura_venta where num_factura = '$numfac' and estado='Activo'";
    $res = pg_query($sql);
    $row = pg_fetch_row($res);
    return $row[0];
}

function obtenerDetallesNCredito($iddevolucion)
{
    $sql = "
        select 
        p.codigo,
        p.articulo,
        dv.cantidad,
        dv.cantidad_unidad,
        dv.precio_venta,
        dv.descuento_producto,
        dv.total_venta,
        dv.unidad_medida,
        di.cod_impuesto,
        di.cod_tarifa,
        di.valor_impuesto,
        di.tarifa,
        di.base_imponible
        from detalle_devolucion_venta dv
        inner join productos p using(cod_productos)
        left join detalle_impuesto_producto_dev_venta di
        using(id_detalle_deventa)
        where id_devolucion_venta=$iddevolucion";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}

function obtenervaloresTarifasImpuestoNCredito($iddevolucion)
{
    $sql = "
        select
        cod_impuesto,cod_tarifa,tarifa, 
        sum(total_venta::numeric)base_imponible, 
        round(sum(valor_impuesto),2)valor_impuesto
        from detalle_devolucion_venta df
        inner join detalle_impuesto_producto_dev_venta di
        using(id_detalle_deventa)
        where id_devolucion_venta=$iddevolucion
        group by cod_impuesto,cod_tarifa,tarifa
        ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}
