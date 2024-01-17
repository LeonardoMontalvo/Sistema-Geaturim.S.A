<?php

function generarXMLGUIA($id, $codDoc, $ambiente, $emision) {


    $consulta = pg_query("select e.id_empresa, nombre_empresa, ruc_empresa, direccion_empresa, telefono_empresa, celular_empresa,
        email_empresa, nombre_comercial, obligacion, contribuyente_espe, establecimiento, punto_emision,
        fecha_actual as fecha_emision, num_autorizacion, fecha_autorizacion, num_serie, num_serie, 
        fv.clave, num_serie, c.identificacion, nombres_cli, direccion_cli, correo,
        case when c.telefono!='' then c.telefono else c.celular end as telefono_cli, codigo_tdocu, c.telefono telefono_cli,
        c.celular celular_cli, c.correo correo_cli, direccion_cli ,motivo,num_placa,id_transportista,num_guia_remision,nombres_trans,
        fecha_inicio,fv.id_guia_remision
        from empresa e inner join guia_remision fv using(id_empresa) 
left join clientes c using(id_cliente) 
inner join tipo_documento using(id_tdocu) 
inner join transportista using(id_transportista) 
    where fv.id_guia_remision =  '" . $id . "' ");
    while ($row = pg_fetch_assoc($consulta)) {
        $querypv = "select*from punto_venta where id_punto_venta=$row[id_empresa]";
        $respv = pg_query($querypv);
        $rowpv = pg_fetch_assoc($respv);

        $ruc = $row['ruc_empresa'];
        $fechaEmision = $row['fecha_actual'];
        $date = new DateTime($fechaEmision);
        $fechaEmisionfinal = $date->format('d/m/Y');
        $claveAcceso = $row['clave'];
        $razonSocial = $row['nombre_empresa'];
        $nombreComercial = $row['nombre_comercial'];
        $direcionMatriz = $row['direccion_empresa'];
       
        $direccionEstablecimiento = $rowpv['ubicacion'];
       
        // $nroContribuyente = $row[19];
        $obligado = $row['obligacion'];
//        $contribuyente = $row[19];
        $identificacion = $row['identificacion'];
        $cliente = $row['nombres_cli'];
        $direcion = $row['direccion_cli'];
        $telefono = $row["telefono_cli"];
        $email = $row['correo'];
        $secuencial = "$row[num_serie]" . "-" . "$row[num_guia_remision]";
        $ip = $secuencial;
        $iparr = split("\-", $ip);
        $secuencialresult = $iparr[2];

        $explnumserie = explode("-", $row["num_serie"]);

        $establecimiento = $explnumserie[0];
        $puntoEmision = $explnumserie[1];


//        $fechaAut = $row[30];
        $tipoIdentificacion = $row['codigo_tdocu'];
        $idFactt = $row['id_guia_remision'];
        $razontransp = $row['nombres_trans'];
        $tipoidenttransp = $row['nombres_trans'];
        $id_trans = $row['id_transportista'];
        $fecha_ini = $row['fecha_inicio'];
        $date = new DateTime($fecha_ini);

        $fecha_ini = $date->format('d/m/Y');
        $fecha_fin = $row['fecha_fin'];
        $date = new DateTime($fecha_fin);
        $fecha_fin = $date->format('d/m/Y');
        $placa = $row['num_placa'];
        $indenDestina = $row['nombres_trans'];
        $motivot = $row['motivo'];
    }
    $consultaTrans = pg_query("select * from transportista,tipo_documento where transportista.id_tdocu =tipo_documento.id_tdocu and transportista.id_transportista= '$id_trans' ");
    while ($row = pg_fetch_row($consultaTrans)) {
        $tipo_docu = $row[10];
        $identificacion_trans = $row[1];
    }

    $s = "";
    $s = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $s .= "<guiaRemision id=\"comprobante\" version=\"1.1.0\">\n";
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
    $s .= "<infoGuiaRemision>\n";
    $s .= "<dirEstablecimiento>" . substr($direccionEstablecimiento, 0, 300) . "</dirEstablecimiento>\n";
    $s .= "<dirPartida>" . $direccionEstablecimiento . "</dirPartida>\n";
    $s .= "<razonSocialTransportista>" . $razontransp . "</razonSocialTransportista>\n";
    $s .= "<tipoIdentificacionTransportista>" . $tipo_docu . "</tipoIdentificacionTransportista>\n";
    $s .= "<rucTransportista>" . substr($identificacion_trans, 0, 20) . "</rucTransportista>\n";
    $s .= "<fechaIniTransporte>" . substr($fecha_ini, 0, 300) . "</fechaIniTransporte>\n";
    $s .= "<fechaFinTransporte>" . substr($fecha_fin, 0, 300) . "</fechaFinTransporte>\n";
    $s .= "<placa>" . substr($placa, 0, 300) . "</placa>\n";

    $s .= "</infoGuiaRemision>\n";
    $s .= "<destinatarios>\n";
    $s .= "<destinatario>\n";
    $s .= "<identificacionDestinatario>" . $identificacion . "</identificacionDestinatario>\n";
    $s .= "<razonSocialDestinatario>" . $cliente . "</razonSocialDestinatario>\n";
    $s .= "<dirDestinatario>" . substr($direcion, 0, 20) . "</dirDestinatario>\n";
    $s .= "<motivoTraslado>" . substr($motivot, 0, 300) . "</motivoTraslado>\n";
    $s .= "<detalles>\n";

    $resultado = pg_query("select  P.codigo, P.articulo, D.cantidad 
 from detalle_guia_remision D  , productos P where  d.cod_productos =P.cod_productos and
    D.id_guia_remision = '" . $idFactt . "'");
    while ($row = pg_fetch_row($resultado)) {
        $s .= "<detalle>\n";

        $s .= "<codigoInterno>" . substr($row[0], 0, 25) . "</codigoInterno>\n";
        $s .= "<descripcion>" . substr($row[1], 0, 300) . "</descripcion>\n";
        $s .= "<cantidad>" . $row[2] . "</cantidad>\n";
        $s .= "</detalle>\n";
    }

    $s .= "</detalles>\n";
    $s .= "</destinatario>\n";
    $s .= "</destinatarios>\n";
    $s .= "<infoAdicional>\n";
    $s .= "<campoAdicional nombre=\"DIRECCION\">" . ' ' . substr($direcion, 0, 299) . "</campoAdicional>\n";
    $s .= "<campoAdicional nombre=\"TELEFONO\">" . ' ' . utf8_decode(substr($telefono, 0, 299)) . "</campoAdicional>\n";
    $s .= "<campoAdicional nombre=\"EMAIL\">" . ' ' . utf8_decode(substr($email, 0, 299)) . "</campoAdicional>\n";

    $s .= "</infoAdicional>";
    $s .= "\n</guiaRemision>";
    return $s;
}

function generarXMLCDATAGUIA($data) {
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