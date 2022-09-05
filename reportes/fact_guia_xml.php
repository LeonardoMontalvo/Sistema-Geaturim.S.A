<?php
	function generarXMLGUIA($id,$codDoc,$ambiente,$emision) {
            
//            echo 'GENERAR GUIA'."select * from empresa inner join factura_venta on empresa.id_empresa  = factura_venta.id_empresa inner join guia_remision on guia_remision.id_factura_venta=factura_venta.id_factura_venta left join clientes on factura_venta.id_cliente=clientes.id_cliente inner join tipo_documento on tipo_documento.id_tdocu=clientes.id_tdocu  inner join transportista on transportista.id_transportista=guia_remision.id_transportista where guia_remision.id_factura_venta=factura_venta.id_factura_venta AND guia_remision.id_guia_remision = '".$id."' ";
	$consulta = pg_query("select * from empresa inner join factura_venta on empresa.id_empresa  = factura_venta.id_empresa inner join guia_remision on guia_remision.id_factura_venta=factura_venta.id_factura_venta left join clientes on factura_venta.id_cliente=clientes.id_cliente inner join tipo_documento on tipo_documento.id_tdocu=clientes.id_tdocu  inner join transportista on transportista.id_transportista=guia_remision.id_transportista where guia_remision.id_factura_venta=factura_venta.id_factura_venta AND guia_remision.id_guia_remision = '".$id."' ");
		    while ($row = pg_fetch_row($consulta)) {
			$ruc = $row[2];                             
			$fechaEmision = $row[30];
                        $date = new DateTime($fechaEmision);
                        $fechaEmisionfinal= $date->format('d/m/Y');                        
			$claveAcceso = $row[72];
			$razonSocial = $row[1];
			$nombreComercial = $row[17];
			$direcionMatriz = $row[7];
			$direccionEstablecimiento = $row[3];
			$nroContribuyente = $row[19];
			$obligado = $row[18];
			$contribuyente = $row[19];
			$identificacion = $row[86];
                        $cliente = $row[87];
			$direcion = $row[89];
			$telefono = $row[90];
			$email = $row[94];			
			$secuencial =  "$row[70]" . "-" . "$row[83]";                     
                        $ip = $secuencial;
                        $iparr = split ("\-", $ip); 
                        $secuencialresult=$iparr[2];
                         if ($row[0] == 1) {
                        $establecimiento = "001";
                        }
                        if ($row[0] == 2) {
                            $establecimiento = "002";
                        }
                        if ($row[0] == 3) {
                            $establecimiento = "001";
                        }
                        if ($row[0] == 4) {
                            $establecimiento = "003";
                        }
			
			$puntoEmision = $row[23];
			$fechaAut = $row[30];
			$tipoIdentificacion = $row[102];
                        $idFactt = $row[24];
                        $razontransp = $row[106];
                        $tipoidenttransp = $row[105];
                        $id_trans = $row[104];
                        $fecha_ini = $row[66];
                        $date = new DateTime($fecha_ini);
                        $fecha_ini= $date->format('d/m/Y');    
                        $fecha_fin = $row[67];                     
                        $date = new DateTime($fecha_fin);
                        $fecha_fin= $date->format('d/m/Y');     
                        $placa = $row[110];                      
                        $indenDestina = $row[110];
                        $motivot = $row[76];
		}
                $consultaTrans = pg_query("select * from transportista,tipo_documento where transportista.id_tdocu =tipo_documento.id_tdocu and transportista.id_transportista= '$id_trans' ");
		    while ($row = pg_fetch_row($consultaTrans)) {
                          $tipo_docu = $row[10];
                          $identificacion_trans=$row[1];
                    }
		
      	        $s = "";
		$s = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$s .= "<guiaRemision id=\"comprobante\" version=\"1.1.0\">\n";		
			$s .= "<infoTributaria>\n";
				$s .= "<ambiente>".$ambiente."</ambiente>\n";
				$s .= "<tipoEmision>".$emision."</tipoEmision>\n";
				$s .= "<razonSocial>".substr($razonSocial, 0,300) ."</razonSocial>\n";
				$s .= "<nombreComercial>".substr($nombreComercial, 0,300)."</nombreComercial>\n";
				$s .= "<ruc>".substr($ruc,0,13)."</ruc>\n";
				$s .= "<claveAcceso>".substr($claveAcceso,0,49)."</claveAcceso>\n";
				$s .= "<codDoc>".substr($codDoc,0,2)."</codDoc>\n";
				$s .= "<estab>".substr($establecimiento,0,3)."</estab>\n";
				$s .= "<ptoEmi>".substr($puntoEmision,0,3)."</ptoEmi>\n";
				$s .= "<secuencial>".substr($secuencialresult,0,9)."</secuencial>\n";
				$s .= "<dirMatriz>".substr($direcionMatriz,0,300)."</dirMatriz>\n";
			$s .= "</infoTributaria>\n";
			$s .= "<infoGuiaRemision>\n";				
				$s .= "<dirEstablecimiento>".substr($direccionEstablecimiento,0,300)."</dirEstablecimiento>\n";				
				$s .= "<dirPartida>".$direccionEstablecimiento."</dirPartida>\n";
				$s .= "<razonSocialTransportista>".$razontransp."</razonSocialTransportista>\n";				
				$s .= "<tipoIdentificacionTransportista>".$tipo_docu."</tipoIdentificacionTransportista>\n";
				$s .= "<rucTransportista>".substr($identificacion_trans,0,20)."</rucTransportista>\n";
				$s .= "<fechaIniTransporte>".substr($fecha_ini,0,300)."</fechaIniTransporte>\n";
                                $s .= "<fechaFinTransporte>".substr($fecha_fin,0,300)."</fechaFinTransporte>\n";
                                $s .= "<placa>".substr($placa,0,300)."</placa>\n";

			$s .= "</infoGuiaRemision>\n";
			$s .= "<destinatarios>\n";
                          $s .= "<destinatario>\n";                       
                                $s .= "<identificacionDestinatario>".$identificacion."</identificacionDestinatario>\n";				
				$s .= "<razonSocialDestinatario>".$cliente."</razonSocialDestinatario>\n";
				$s .= "<dirDestinatario>".substr($direcion,0,20)."</dirDestinatario>\n";
				$s .= "<motivoTraslado>".substr($motivot,0,300)."</motivoTraslado>\n";
                           $s .= "<detalles>\n";					
                                                 
				$resultado = pg_query("select  P.codigo, P.articulo, D.cantidad, D.precio_venta, D.descuento_producto, D.precio_venta,f.tarifa12, (D.cantidad::float*D.precio_venta::float) as tarifa12,((D.cantidad::float*D.precio_venta::float)*0.12) as iva12, p.iva  from factura_venta F,detalle_factura_venta D  , productos P where  d.cod_productos =P.cod_productos   and D.id_factura_venta = F.id_factura_venta   and F.id_factura_venta = '".$idFactt."'");
				while ($row = pg_fetch_row($resultado)) {
                                    $s .= "<detalle>\n";
                                $tarifa12=0;
                                    $tarifa12 = $row[3];
                                    $canti=0;
                                    $canti = $row[2];
                                    $iva_venta=$row[9];
                                    $tarifa12=$tarifa12*$canti;
                                    $baseimponible=$row[7];
                                    $Descucaltres=0;
                                    $desc=0;
                                    $desc = $row[4];
                                    $valcien=100;
                                    $Descucaltres=($tarifa12/$valcien)*$desc;
                                    $tarifa12sin=$tarifa12-$Descucaltres;
                                    $baseimponible=$baseimponible-$Descucaltres;
				    $s .= "<codigoInterno>".substr($row[0],0,25)."</codigoInterno>\n";
				    $s .= "<descripcion>".substr($row[1],0,300)."</descripcion>\n";
				    $s .= "<cantidad>".$row[2]."</cantidad>\n";
                                    $s .= "</detalle>\n";
                                
				}
                       
		  	$s .= "</detalles>\n";                               
                        $s .= "</destinatario>\n";	
		  	$s .= "</destinatarios>\n";				
			$s .= "<infoAdicional>\n";				
				$s .= "<campoAdicional nombre=\"DIRECCION\">".' '.substr($direcion,0,299)."</campoAdicional>\n";									
				$s .= "<campoAdicional nombre=\"TELEFONO\">".' '.utf8_decode(substr($telefono,0,299))."</campoAdicional>\n";	
				$s .= "<campoAdicional nombre=\"EMAIL\">".' '.utf8_decode(substr($email,0,299))."</campoAdicional>\n";
				
			$s .= "</infoAdicional>";	
		$s .="\n</guiaRemision>";
		return $s;
	}
	function generarXMLCDATAGUIA($data) {				
      	$s = "";
		$s = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$s .= "<autorizacion>\n";
			$s .= "<estado>".$data->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado."</estado>\n";
			$s .= "<numeroAutorizacion>".$data->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion."</numeroAutorizacion>\n";
			$s .= "<fechaAutorizacion>".$data->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion."</fechaAutorizacion>\n";
			$s .= "<comprobante><![CDATA[".$data->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->comprobante."]]></comprobante>";
		 	$s .= "<mensajes/>\n";
		$s .= "</autorizacion>";
		return $s;
	}
?>