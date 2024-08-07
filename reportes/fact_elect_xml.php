<?php

function generarXML($id, $codDoc, $ambiente, $emision)
{
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
        $querypv = "select*from punto_venta where id_punto_venta=$row[id_empresa]";
        $respv = pg_query($querypv);
        $rowpv = pg_fetch_assoc($respv);

        $razonSocial = $row['nombre_empresa'];
        $ruc = $row['ruc_empresa'];
        $direccionEstablecimiento = $rowpv['ubicacion'];
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

    $valor_iva = '';
    $resultado_iva = pg_query("SELECT valor
  FROM parametros where descripcion='IVA'");
    while ($row = pg_fetch_row($resultado_iva)) {
        $valor_iva = $row[0] / 100;
        $fecha0 = "2024-04-01";
        if ($fechaEmision < $fecha0) {
            $valor_iva = 0.12;
        }
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
    while ($row = pg_fetch_assoc($resultado)) {
        /* $totalSinImpuestos = $row['tarifa12']; //tarifa12
        if ($totalSinImpuestos == 0) {
            $totalSinImpuestosuno = $row['tarifa0']; //tarifa0
        } else {
            $totalSinImpuestosuno = $row['tarifa12']; //tarifa12
        }
        $tarifa0 = $row['tarifa0']; //tarifa0
        $tarifa12 = $row['tarifa12']; //tarifa12
        if ($tarifa0 != 0 && $tarifa12 != 0) {
            $totalSinImpuestosuno = $tarifa0 + $tarifa12;
        }
        $tarifa = $row['tarifa0']; //tarifa0
        $iva = $row['iva_venta']; //iva_venta */
        $descuento = $row['descuento_venta']; //descuento_venta
        $total = $row['total_venta']; //total_venta */


        //$descu_global = $row['desc_fact']; //39 desc_fact====  40 desc_prod
    }
    //$calculo_porsentaje = ($descuento * 100) / $totalSinImpuestosuno;
    //para saber que porsentaje de descuento

    /* $dt12 = ($tarifa12 * $calculo_porsentaje) / 100;
    $dt0 = ($tarifa0 * $calculo_porsentaje) / 100; */

    $tarifasimpuesto = obtenervaloresTarifasImpuestoFactura($id);
    $totalsinimp = 0;

    foreach ($tarifasimpuesto as $value) {
        $totalsinimp += $value["base_imponible"];
    }


    $s .= "<totalSinImpuestos>" . number_format($totalsinimp, 2, '.', '') . "</totalSinImpuestos>\n";
    $s .= "<totalDescuento>" . number_format($descuento, 2, '.', '') . "</totalDescuento>\n";

    $s .= "<totalConImpuestos>\n";
    /*$s .= "<totalImpuesto>\n";
    $s .= "<codigo>2</codigo>\n";
    $s .= "<codigoPorcentaje>" . getCodigoPorc($fechaEmision). "</codigoPorcentaje>\n";

    if ($tarifa12 != 0) {
        $s .= "<descuentoAdicional>" . number_format($descu_global, 2, '.', '') . "</descuentoAdicional>\n";
    }
    $s .= "<baseImponible>" . number_format($tarifa12, 2, '.', '') . "</baseImponible>\n";
    $s .= "<tarifa>" . getTarifa($fechaEmision). "</tarifa>\n";
    $s .= "<valor>" . number_format($iva, 2, '.', '') . "</valor>\n";
    $s .= "</totalImpuesto>\n";

    $s .= "<totalImpuesto>\n";
    $s .= "<codigo>2</codigo>\n";
    $s .= "<codigoPorcentaje>0</codigoPorcentaje>\n";
    if ($tarifa0 != 0) {
        $s .= "<descuentoAdicional>" . number_format($descu_global, 2, '.', '') . "</descuentoAdicional>\n";
    }
    $s .= "<baseImponible>" . number_format($tarifa0, 2, '.', '') . "</baseImponible>\n";
    $s .= "<tarifa>0.00</tarifa>\n";
    $s .= "<valor>0.00</valor>\n";
    $s .= "</totalImpuesto>\n";
    $s .= "</totalConImpuestos>\n";*/

    foreach ($tarifasimpuesto as $value) {
        $s .= "<totalImpuesto>\n";
        $s .= "<codigo>" . $value["cod_impuesto"] . "</codigo>\n";
        $s .= "<codigoPorcentaje>" . $value["cod_tarifa"] . "</codigoPorcentaje>\n";
        if (!empty($value["descuento_adicional"])) {
            $s .= "<descuentoAdicional>" . number_format($value["descuento_adicional"], 2, '.', '') . "</descuentoAdicional>\n";
        }
        $s .= "<baseImponible>" . number_format($value["base_imponible"], 2, '.', '') . "</baseImponible>\n";
        $s .= "<tarifa>" . $value["tarifa"] . "</tarifa>\n";
        if (empty($value["descuento_adicional"])) {
            $s .= "<valor>" . $value["valor_impuesto"] . "</valor>\n";
        } else {
            $s .= "<valor>" . $value["valor_impuesto_descuento"] . "</valor>\n";
        }
        $s .= "</totalImpuesto>\n";
    }
    $s .= "</totalConImpuestos>\n";

    /*     $s .= "<totalConImpuestos>\n";
    $s .= "<totalImpuesto>\n";
    $s .= "<codigo>2</codigo>\n";
    $s .= "<codigoPorcentaje>" . getCodigoPorc($fechaEmision) . "</codigoPorcentaje>\n";
    $s .= "<baseImponible>" . number_format($tarifa0, 2, '.', '') . "</baseImponible>\n";
    $s .= "<tarifa>0.00</tarifa>\n";
    $s .= "<valor>0.00</valor>\n";
    $s .= "</totalConImpuestos>\n";
 */

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

    /*$resultado = pg_query("select  P.codigo, P.articulo, D.cantidad, D.precio_venta, D.descuento_producto, D.precio_venta,f.tarifa12, (D.cantidad::float*D.precio_venta::float) as tarifa12,((D.cantidad::float*D.precio_venta::float)*" . tarifaPara100($fechaEmision) . ") as iva12, p.iva,D.unidad_medida, D.detalle_producto  from factura_venta F,detalle_factura_venta D  , productos P where  d.cod_productos =P.cod_productos   and D.id_factura_venta = F.id_factura_venta   and F.id_factura_venta = '" . $id . "'");
    // echo '//'."select  P.codigo, P.articulo, D.cantidad, D.precio_venta, D.descuento_producto, D.precio_venta,f.tarifa12, (D.cantidad::float*D.precio_venta::float) as tarifa12,((D.cantidad::float*D.precio_venta::float)*".tarifaPara100($fechaEmision).") as iva12, p.iva,D.unidad_medida, D.detalle_producto  from factura_venta F,detalle_factura_venta D  , productos P where  d.cod_productos =P.cod_productos   and D.id_factura_venta = F.id_factura_venta   and F.id_factura_venta = '" . $id . "'";
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
        $s .= "<precioUnitario>" . number_format($row[3], 4, '.', '') . "</precioUnitario>\n";
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


            $valor_descu = 0;
            $valor_descu = ($row[7] / $valcien) * $desc;
            $result_des_pvp = $row[7] - $valor_descu;
            $res_result = $result_des_pvp * tarifaPara100($fechaEmision); 
            $s .= "<codigo>2</codigo>\n";
            $s .= "<codigoPorcentaje>" . getCodigoPorc($fechaEmision) . "</codigoPorcentaje>\n";

            $s .= "<tarifa>" . getTarifa($fechaEmision) . "</tarifa>\n";
            $s .= "<baseImponible>" . number_format($baseimponible, 2, '.', '') . "</baseImponible>\n";
            $s .= "<valor>" . number_format($res_result, 2, '.', '') . "</valor>\n";
            $s .= "</impuesto>\n";
            $s .= "</impuestos>\n";
            $s .= "</detalle>\n";
        }
    }*/

    $detallesfac = obtenerDetallesFactura($id);
    foreach ($detallesfac as $value) {
        $cantidad = $value["cantidad"];
        if (!empty(floatval($value["cantidad_unidad"]))) {
            $cantidad = $value["cantidad_unidad"];
        }
        $descuento = ($cantidad * $value["precio_venta"]) * ($value["descuento_producto"] / 100);


        $descripcion = $value["articulo"];
        if (!empty($value["unidad_medida"])) {
            $descripcion = $value["articulo"] . "(" . $value["unidad_medida"] . ")";
        }
        if (!empty($value["detalle_producto"])) {
            $descripcion .= " -- " . $value["detalle_producto"];
        }
        $s .= "<detalle>\n";
        $s .= "<codigoPrincipal>" . substr($value["codigo"], 0, 25) . "</codigoPrincipal>\n";
        if($value["tarifa"]=='5'){           
            $s .= "<codigoAuxiliar>F010101</codigoAuxiliar>\n";   
        }    
                
        $s .= "<descripcion>" . substr(htmlspecialchars($descripcion), 0, 300) . "</descripcion>\n";
        $s .= "<cantidad>" . $value["cantidad"] . "</cantidad>\n";
        $s .= "<precioUnitario>" . number_format($value["precio_venta"], 4, '.', '') . "</precioUnitario>\n";
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
    $s .= "<campoAdicional nombre=\"DIRECCION\">" . ' ' . substr($direccioncli, 0, 299) . "</campoAdicional>\n";
    $s .= "<campoAdicional nombre=\"TELEFONO\">" . ' ' . utf8_decode(substr((!empty($celularcli) ? $celularcli : $telefonocli), 0, 299)) . "</campoAdicional>\n";
    $s .= "<campoAdicional nombre=\"EMAIL\">" . ' ' . utf8_decode(substr($corrreocli, 0, 299)) . "</campoAdicional>\n";
    //    $s .= "<campoAdicional nombre=\"Agente de Retención\">NO</campoAdicional>\n";
    $s .= "</infoAdicional>";
    $s .= "\n</factura>";
    return $s;
}

function generarXMLCDATA($data)
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


/////CAMBIOIVA
if (!function_exists('getCodigoPorc')) {
    function getCodigoPorc($fechaEmision)
    {
        $fecha0 = "2024-04-01";
        if ($fechaEmision < $fecha0) {
            return 2;
        }
        return 4;
    }
}

if (!function_exists('getTarifa')) {
    function getTarifa($fechaEmision)
    {
        $fecha0 = "2024-04-01";
        if ($fechaEmision < $fecha0) {
            return 12;
        }
        return 15;
    }
}
if (!function_exists('tarifaPara100')) {
    function tarifaPara100($fechaEmision)
    {
        $tarifa = getTarifa($fechaEmision);
        return $tarifa / 100;
    }
}
//pyssystems
/////

function obtenerDetallesFactura($idfactura)
{
    $sql = "select
    p.codigo,
    p.articulo,
    df.cantidad,
    df.cantidad_unidad,
    df.precio_venta,
    df.descuento_producto,
    df.total_venta,
    df.detalle_producto,
    df.unidad_medida,
    di.cod_impuesto,
    di.cod_tarifa,
    di.valor_impuesto,
    di.tarifa,
    di.base_imponible
    from detalle_factura_venta df
    inner join productos p 
    using(cod_productos)
    left join detalle_impuesto_producto_venta di
    using(id_detalle_venta)
    where id_factura_venta=$idfactura";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}

function obtenerValoresTarifasImpuestoFactura($idfactura)
{
    $sql = "
    with x as(select
    cod_impuesto,cod_tarifa,tarifa, 
    sum(total_venta::numeric)base_imponible, 
    round(sum(valor_impuesto),2)valor_impuesto
    from detalle_factura_venta df
    inner join detalle_impuesto_producto_venta di
    using(id_detalle_venta)
    where id_factura_venta=$idfactura
    group by cod_impuesto,cod_tarifa,tarifa)
    select
    x.cod_impuesto,
    x.cod_tarifa,
    x.tarifa,
    x.base_imponible,
    x.valor_impuesto,
    div.descuento_adicional,
    round(div.valor_impuesto,2) valor_impuesto_descuento
    from x
    inner join detalle_impuesto_factura_venta div 
    on div.cod_tarifa=x.cod_tarifa
    and id_factura_venta=$idfactura
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}

/* function obtenerDetalleImpuestoFactura($idfactura)
{
    $sql = "
    SELECT id_detalle_impuesto_factura_venta, cod_impuesto, cod_tarifa, 
       tarifa, valor_impuesto, base_imponible, descuento_adicional, 
       id_factura_venta
    FROM detalle_impuesto_factura_venta where id_factura_venta=$idfactura;
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}
 */