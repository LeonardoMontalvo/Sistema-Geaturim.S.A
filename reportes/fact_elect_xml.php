<?php

function generarXML($id, $codDoc, $ambiente, $emision) {

    $consulta = pg_query("SELECT e.id_empresa, nombre_empresa, ruc_empresa, direccion_empresa, telefono_empresa, celular_empresa,
        email_empresa, nombre_comercial, obligacion, contribuyente_espe, establecimiento, punto_emision,
        fecha_actual as fecha_emision, num_autorizacion, fecha_autorizacion, num_factura, num_serie, 
        fv.clave, serie_guia_remision, marca_vehiculo, identificacion, nombres_cli, direccion_cli, correo,
        case when telefono!='' then telefono else celular end as telefono_cli, codigo_tdocu, c.telefono telefono_cli,
        c.celular celular_cli, c.correo correo_cli, direccion_cli
        from empresa e left join factura_venta fv using(id_empresa) 
        left join clientes c using(id_cliente) 
        left join tipo_documento td using(id_tdocu) 
        where fv.id_factura_venta='" . $id . "' ");
    while ($row = pg_fetch_assoc($consulta)) {
        $razonSocial = $row['nombre_empresa'];
        $ruc = $row['ruc_empresa'];
        $direccionEstablecimiento = $row['direccion_empresa'];
        $direcionMatriz = $row['direccion_empresa'];
        $telefono = $row['celular_empresa'];
        $nombreComercial = $row['nombre_comercial'];
        $obligado = $row['obligacion'];
        // $contribuyente = $row['contribuyente_espe'];
        // $nroContribuyente = $row['contribuyente_espe'];
        $direccioncli = $row["direccion_cli"];
        $corrreocli = $row["correo_cli"];
        $telefonocli = $row["telefono_cli"];
        $celularcli = $row["celular_cli"];
        $fechaEmision = $row['fecha_emision'];
        $date = new DateTime($fechaEmision);
        $fechaEmisionfinal = $date->format('d/m/Y');
        $claveAcceso = $row['clave'];
        $identificacion = $row['identificacion'];
        $cliente = $row['nombres_cli'];
        $direcion = $row['direccion_cli'];
        $email = $row['correo'];
        $secuencial = "$row[num_serie]" . "-" . "$row[num_factura]";
        $ip = $secuencial;
        $iparr = split("\-", $ip);
        $secuencialresult = $iparr[2];
        $explnumserie = explode("-", $row["num_serie"]);
        $establecimiento = $explnumserie[0];
        $puntoEmision = $explnumserie[1];

        //TODO borrar comentado
        /* if ($row['id_empresa'] == 1) {
          $establecimiento = "001";
          }
          if ($row['id_empresa'] == 2) {
          $establecimiento = "001";
          }
          if ($row['id_empresa'] == 3) {
          $establecimiento = "001";
          }
          if ($row['id_empresa'] == 4) {
          $establecimiento = "003";
          } */
        // $fechaAut = $row[31];
        $num_serie_guia = $row['serie_guia_remision'];
        // $marca_delvehiculo = $row['marca_vehiculo']
        // $placanum = $row['placa_fac'];
        // $propiedad = $row['propiedad'];
        // $num_reclamo = $row['num_reclamo'];
        // $num_chasis = $row['num_chasis'];
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
    $s .= "<factura id=\"comprobante\" version=\"1.1.0\">\n";
    $s .= "<infoTributaria>\n";
    $s .= "<ambiente>" . $ambiente . "</ambiente>\n";
    $s .= "<tipoEmision>" . $emision . "</tipoEmision>\n";
    $s .= "<razonSocial>" . substr(htmlspecialchars($razonSocial), 0, 300) . "</razonSocial>\n";
    $s .= "<nombreComercial>" . substr(htmlspecialchars($nombreComercial), 0, 300) . "</nombreComercial>\n";
    $s .= "<ruc>" . substr($ruc, 0, 13) . "</ruc>\n";
    $s .= "<claveAcceso>" . substr($claveAcceso, 0, 49) . "</claveAcceso>\n";
    $s .= "<codDoc>" . substr($codDoc, 0, 2) . "</codDoc>\n";
    $s .= "<estab>" . substr($establecimiento, 0, 3) . "</estab>\n";
    $s .= "<ptoEmi>" . substr($puntoEmision, 0, 3) . "</ptoEmi>\n";
    $s .= "<secuencial>" . substr($secuencialresult, 0, 9) . "</secuencial>\n";
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
    $s .= "<infoFactura>\n";
    $s .= "<fechaEmision>" . substr($fechaEmisionfinal, 0, 10) . "</fechaEmision>\n";
    $s .= "<dirEstablecimiento>" . substr($direccionEstablecimiento, 0, 300) . "</dirEstablecimiento>\n";
    //if($contribuyente != '')
    //$s .= "<contribuyenteEspecial>".substr($contribuyente,0,13)."</contribuyenteEspecial>\n";
    $s .= "<obligadoContabilidad>" . $obligado . "</obligadoContabilidad>\n";
    $s .= "<tipoIdentificacionComprador>" . substr($tipoIdentificacion, 0, 2) . "</tipoIdentificacionComprador>\n";
    if ($num_serie_guia != '000000000') {
        $s .= "<guiaRemision>" . substr($num_serie_guia, 0, 20) . "</guiaRemision>\n";
    } else {
        $num_serie_guia = "";
    }

    $s .= "<razonSocialComprador>" . substr(htmlspecialchars($cliente), 0, 300) . "</razonSocialComprador>\n";
    $s .= "<identificacionComprador>" . substr($identificacion, 0, 20) . "</identificacionComprador>\n";
    $s .= "<direccionComprador>" . substr($direcion, 0, 300) . "</direccionComprador>\n";

    $descuento = 0;
    $totalSinImpuestos = 0;
    $total = 0;
    $tarifa0 = 0;
    $tarifa12 = 0;
    $totalSinImpuestos0 = 0;
    $totalSinImpuestos12 = 0;
    $resultado = pg_query("SELECT * FROM factura_venta WHERE id_factura_venta = '" . $id . "'");
    while ($row = pg_fetch_row($resultado)) {
        $totalSinImpuestos = $row[15];
        if ($totalSinImpuestos == 0) {
            $totalSinImpuestosuno = $row[14];
        } else {
            $totalSinImpuestosuno = $row[15];
        }
        $tarifa0 = $row[14];
        $tarifa12 = $row[15];
        if ($tarifa0 != 0 && $tarifa12 != 0) {
            $totalSinImpuestosuno = $tarifa0 + $tarifa12;
        }
        $tarifa = $row[14];
        $iva = $row[16];
        $descuento = $row[17];
        $total = $row[18];
    }
    $calculo_porsentaje = ($descuento * 100) / $totalSinImpuestosuno;
	//para saber que porsentaje de descuento

    $dt12 = ($tarifa12 * $calculo_porsentaje ) / 100;
    $dt0 = ($tarifa0 * $calculo_porsentaje ) / 100;
    
    $s .= "<totalSinImpuestos>" . number_format($totalSinImpuestosuno, 2, '.', '') . "</totalSinImpuestos>\n";
    $s .= "<totalDescuento>" . number_format($descuento, 2, '.', '') . "</totalDescuento>\n";
    
    $s .= "<totalConImpuestos>\n";
    $s .= "<totalImpuesto>\n";
    $s .= "<codigo>2</codigo>\n";
    $s .= "<codigoPorcentaje>2</codigoPorcentaje>\n";
    
            if ($tarifa12 != 0) {
        $s .= "<descuentoAdicional>" . number_format($dt12, 2, '.', '') . "</descuentoAdicional>\n";
    }
    $s .= "<baseImponible>" . number_format($tarifa12, 2, '.', '') . "</baseImponible>\n";
    $s .= "<tarifa>12</tarifa>\n";
    $s .= "<valor>" . number_format($iva, 2, '.', '') . "</valor>\n";
    $s .= "</totalImpuesto>\n";
    
    $s .= "<totalImpuesto>\n";
    $s .= "<codigo>2</codigo>\n";
    $s .= "<codigoPorcentaje>0</codigoPorcentaje>\n";
            if ($tarifa0 != 0) {
        $s .= "<descuentoAdicional>" . number_format($dt0, 2, '.', '') . "</descuentoAdicional>\n";
    }
    $s .= "<baseImponible>" . number_format($tarifa0, 2, '.', '') . "</baseImponible>\n";
    $s .= "<tarifa>0.00</tarifa>\n";
    $s .= "<valor>0.00</valor>\n";
    $s .= "</totalImpuesto>\n";
    $s .= "</totalConImpuestos>\n";
    $s .= "<propina>0.00</propina>\n";
    $s .= "<importeTotal>" . number_format($total, 2, '.', '') . "</importeTotal>\n";
    $s .= "<moneda>DOLAR</moneda>\n";
    $s .= "<pagos>\n";
    $resultado = pg_query("SELECT P.codigo, P.tiempo FROM factura_venta F, forma_pagos P WHERE F.id_forma_pago = P.id_forma_pago AND F.id_factura_venta =  '" . $id . "'");
    while ($row = pg_fetch_row($resultado)) {
        $s .= "<pago>\n";
        $s .= "<formaPago>" . $row[0] . "</formaPago>\n";
        $s .= "<total>" . number_format($total, 2, '.', '') . "</total>\n";
        if ($row[0] != 01) {
            $s .= "<plazo>30</plazo>\n";
        } else {
            $s .= "<plazo>0</plazo>\n";
        }
        $s .= "<unidadTiempo>" . $row[1] . "</unidadTiempo>\n";
        $s .= "</pago>\n";
    }
    $s .= "</pagos>\n";
    $s .= "</infoFactura>\n";
    $s .= "<detalles>\n";

    $resultado = pg_query("select  P.codigo, P.articulo, D.cantidad, D.precio_venta, D.descuento_producto, D.precio_venta,f.tarifa12, (D.cantidad::float*D.precio_venta::float) as tarifa12,((D.cantidad::float*D.precio_venta::float)*0.12) as iva12, p.iva,D.unidad_medida, D.detalle_producto  from factura_venta F,detalle_factura_venta D  , productos P where  d.cod_productos =P.cod_productos   and D.id_factura_venta = F.id_factura_venta   and F.id_factura_venta = '" . $id . "'");
    while ($row = pg_fetch_row($resultado)) {
        $tarifa12 = 0;
        $tarifa12 = $row[3];
        $canti = 0;
        $canti = $row[2];
        $iva_venta = $row[9];
        $tarifa12 = $tarifa12 * $canti;
        $baseimponible = $row[7];
        $Descucaltres = 0;
        $desc = 0;

        $desc = $row[4];
        $valcien = 100;
        $Descucaltres = ($tarifa12 / $valcien) * $desc;
        $tarifa12sin = $tarifa12 - $Descucaltres;
        $baseimponible = $baseimponible - $Descucaltres;
        $s .= "<detalle>\n";
        $s .= "<codigoPrincipal>" . substr($row[0], 0, 25) . "</codigoPrincipal>\n";
        $descripcion = '';
        if ($row[10] != '') {
            //$s .= "<descripcion>" . substr(htmlspecialchars($row[1] . "(" . $row[10] . ")"), 0, 300) . "</descripcion>\n";
            $descripcion = $row[1] . "(" . $row[10] . ")";
        } else {
            //$s .= "<descripcion>" . substr(htmlspecialchars($row[1]), 0, 300) . "</descripcion>\n";
            $descripcion = $row[1];
        }
        if (!empty($row[11])) {
            $descripcion .= " -- " . $row[11];
        }
        $s .= "<descripcion>" . substr(htmlspecialchars($descripcion), 0, 300) . "</descripcion>\n";

        $s .= "<cantidad>" . $row[2] . "</cantidad>\n";
        $s .= "<precioUnitario>" . number_format($row[3], 2, '.', '') . "</precioUnitario>\n";
        $s .= "<descuento>" . number_format($Descucaltres, 2, '.', '') . "</descuento>\n";


        $s .= "<precioTotalSinImpuesto>" . number_format($tarifa12sin, 2, '.', '') . "</precioTotalSinImpuesto>\n";
        $s .= "<impuestos>\n";
        $s .= "<impuesto>\n";
        if ($iva_venta == 'No') {
            $s .= "<codigo>2</codigo>\n";
            $s .= "<codigoPorcentaje>0</codigoPorcentaje>\n";
            $s .= "<tarifa>0.00</tarifa>\n";
            $s .= "<baseImponible>" . number_format($tarifa12sin, 2, '.', '') . "</baseImponible>\n";
            $s .= "<valor>0.00</valor>\n";
            $s .= "</impuesto>\n";
            $s .= "</impuestos>\n";
            $s .= "</detalle>\n";
        } else {
            $iva = $row[8];
            $s .= "<codigo>2</codigo>\n";
            $s .= "<codigoPorcentaje>2</codigoPorcentaje>\n";

            $s .= "<tarifa>12</tarifa>\n";
            $s .= "<baseImponible>" . number_format($baseimponible, 2, '.', '') . "</baseImponible>\n";
            $s .= "<valor>" . number_format($iva, 2, '.', '') . "</valor>\n";
            $s .= "</impuesto>\n";
            $s .= "</impuestos>\n";
            $s .= "</detalle>\n";
        }
    }

    $s .= "</detalles>\n";
    $s .= "<infoAdicional>\n";
    $s .= "<campoAdicional nombre=\"DIRECCION\">" . ' ' . substr($direccioncli, 0, 299) . "</campoAdicional>\n";
    $s .= "<campoAdicional nombre=\"TELEFONO\">" . ' ' . utf8_decode(substr((!empty($celularcli) ? $celularcli : $telefonocli), 0, 299)) . "</campoAdicional>\n";
    $s .= "<campoAdicional nombre=\"EMAIL\">" . ' ' . utf8_decode(substr($corrreocli, 0, 299)) . "</campoAdicional>\n";
//    $s .= "<campoAdicional nombre=\"Agente de Retención\">NO</campoAdicional>\n";
    $s .= "</infoAdicional>";
    $s .= "\n</factura>";
    return $s;
}

function generarXMLCDATA($data) {
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
