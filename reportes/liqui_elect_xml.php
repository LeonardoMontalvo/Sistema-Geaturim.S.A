<?php
	function generarXMLLIQUI($id,$codDoc,$ambiente,$emision) {
	
	$consulta = pg_query("select * from empresa left join liquidacion_compra on empresa.id_empresa  = liquidacion_compra.id_empresa left join proveedores on liquidacion_compra.id_proveedor=proveedores.id_proveedor left join tipo_documento on tipo_documento.id_tdocu=proveedores.id_tdocu where liquidacion_compra.id_liquidacion_compra='".$id."' ");
		    while ($row = pg_fetch_row($consulta)) {
			$ruc = $row[2];                          
			
			$fechaEmision = $row[30];
                        $date = new DateTime($fechaEmision);
                        $fechaEmisionfinal= $date->format('d/m/Y');                        
			$claveAcceso = $row[51];
			$razonSocial = $row[1];
			$nombreComercial = $row[16];
			$direcionMatriz = $row[7];
			$direccionEstablecimiento = $row[3];
			$nroContribuyente = $row[19];
			$obligado = $row[17];
			$contribuyente = $row[18];
			$identificacion = $row[60];
                        $cliente = $row[61];
			$direcion = $row[64];
			$telefono = $row[66];
			$email = $row[71];			
			$secuencial = $row[29];
                        $ip = $secuencial;
                        $iparr = split ("\-", $ip); 
                        $secuencialresult=$iparr[2];
			$establecimiento = $row[21];
			$puntoEmision = $row[22];
			$fechaAut = $row[30];
			$tipoIdentificacion = $row[81];
                        $num_serie_guia = $row[52];
                        
                        
                        $marca_delvehiculo = $row[53];
			$placanum = $row[54];
			$propiedad = $row[55];
			$num_reclamo = $row[56];
                        $num_chasis = $row[57];
                        
		}	
               
                
		$ceros = 9;
		$temp = '';
		$tam = $ceros - strlen($secuencialresult);
      	for ($i = 0; $i < $tam; $i++) {                 
        	$temp = $temp .'0';        
      	}
      	$secuencialresult = $temp .''. $secuencialresult;
      	$s = "";
		$s = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$s .= "<liquidacionCompra id=\"comprobante\" version=\"1.1.0\">\n";		
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
			$s .= "<infoLiquidacionCompra>\n";
				$s .= "<fechaEmision>".substr($fechaEmisionfinal,0,10)."</fechaEmision>\n";
				$s .= "<dirEstablecimiento>".substr($direccionEstablecimiento,0,300)."</dirEstablecimiento>\n";
				//if($contribuyente != '')
				//$s .= "<contribuyenteEspecial>".substr($contribuyente,0,13)."</contribuyenteEspecial>\n";
				$s .= "<obligadoContabilidad>".$obligado."</obligadoContabilidad>\n";
				$s .= "<tipoIdentificacionProveedor>".substr($tipoIdentificacion,0,2)."</tipoIdentificacionProveedor>\n";								                                                                                                                           
                                $s .= "<razonSocialProveedor>".substr($cliente,0,300)."</razonSocialProveedor>\n";
				$s .= "<identificacionProveedor>".substr($identificacion,0,20)."</identificacionProveedor>\n";
				$s .= "<direccionProveedor>".substr($direcion,0,300)."</direccionProveedor>\n";

				$descuento = 0;
				$totalSinImpuestos = 0;
				$total = 0;
                                
                                $tarifa0=0;
                                $tarifa12=0;
                                
                                
                                
				$resultado = pg_query("SELECT * FROM liquidacion_compra WHERE id_liquidacion_compra = '".$id."'");
				while ($row = pg_fetch_row($resultado)) {
                                    
					 $totalSinImpuestos = $row[15];
                                         if($totalSinImpuestos==0){
                                             
                                         $totalSinImpuestosuno=$row[14];
                                         }else{ 
                                         $totalSinImpuestosuno=$row[15];
                                         }
                                         $tarifa0=$row[14];
                                         $tarifa12=$row[15];
                                         if($tarifa0!=0&&$tarifa12!=0){
                                         $totalSinImpuestosuno= $tarifa0+$tarifa12;
                                          }
                                         
					$tarifa = $row[14];
					$iva = $row[16];
					$descuento = $row[17];
					$total = $row[18];	
				}

				$s .= "<totalSinImpuestos>".number_format($totalSinImpuestosuno, 2, '.', '')."</totalSinImpuestos>\n";
				$s .= "<totalDescuento>".number_format($descuento, 2, '.', '')."</totalDescuento>\n";
                                $s .= "<codDocReembolso>00</codDocReembolso>\n";
                                $s .= "<totalComprobantesReembolso>".number_format($totalSinImpuestosuno, 2, '.', '')."</totalComprobantesReembolso>\n";
                                 $s .= "<totalBaseImponibleReembolso>".number_format($totalSinImpuestosuno, 2, '.', '')."</totalBaseImponibleReembolso>\n";
                                   $s .= "<totalImpuestoReembolso>".number_format($totalSinImpuestosuno, 2, '.', '')."</totalImpuestoReembolso>\n";
				$s .= "<totalConImpuestos>\n";

				$s .= "<totalImpuesto>\n";				    	
				$s .= "<codigo>2</codigo>\n";
			    $s .= "<codigoPorcentaje>2</codigoPorcentaje>\n";
                            if($totalSinImpuestos==0){
                                 $s .= "<baseImponible>".number_format($totalSinImpuestos, 2, '.', '')."</baseImponible>\n";
                                
                            }else{
                                 $s .= "<baseImponible>".number_format($totalSinImpuestos, 2, '.', '')."</baseImponible>\n";
                            }
			    $s .= "<tarifa>12</tarifa>\n";
			    $s .= "<valor>".number_format($iva, 2, '.', '')."</valor>\n";
			    $s .= "</totalImpuesto>\n";                            		  
			    $s .= "</totalConImpuestos>\n";
//				$s .= "<propina>0.00</propina>\n";
			    $s .= "<importeTotal>".number_format($total, 2, '.', '')."</importeTotal>\n";
			    $s .= "<moneda>DOLAR</moneda>\n";
			    $s .= "<pagos>\n";
			    $resultado 	 = pg_query("SELECT P.codigo, P.tiempo FROM liquidacion_compra F, forma_pagos P WHERE F.id_forma_pago = P.id_forma_pago AND F.id_liquidacion_compra =  '".$id."'");
			    while ($row = pg_fetch_row($resultado)) {
			    	$s .= "<pago>\n";
		            $s .= "<formaPago>".$row[0]."</formaPago>\n";
		            $s .= "<total>".number_format($total, 2, '.', '')."</total>\n";
                            if($row[0]!=01 ){
		            $s .= "<plazo>30</plazo>\n";
                            }
                            else{
                                $s .= "<plazo>0</plazo>\n"; 
                            }
		            $s .= "<unidadTiempo>".$row[1]."</unidadTiempo>\n";
		        	$s .= "</pago>\n";	
			    }
		        $s .= "</pagos>\n";
		        
			$s .= "</infoLiquidacionCompra>\n";
			$s .= "<detalles>\n";
                                
                                 
				$resultado = pg_query("select  P.codigo, P.articulo, D.cantidad, D.precio_venta, D.descuento_producto, D.precio_venta,f.tarifa12, (D.cantidad::float*D.precio_venta::float) as tarifa12,((D.cantidad::float*D.precio_venta::float)*0.12) as iva12, p.iva  from liquidacion_compra F,detalle_liquidacion_compra D  , productos P where  d.cod_productos =P.cod_productos   and D.id_liquidacion_compra = F.id_liquidacion_compra   and F.id_liquidacion_compra = '".$id."'");
				while ($row = pg_fetch_row($resultado)) {
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
					$s .= "<detalle>\n";
				    $s .= "<codigoPrincipal>".substr($row[0],0,25)."</codigoPrincipal>\n";
				    $s .= "<descripcion>".substr($row[1],0,300)."</descripcion>\n";
				    $s .= "<cantidad>".$row[2]."</cantidad>\n";
				    $s .= "<precioUnitario>".number_format($row[3], 2, '.', '')."</precioUnitario>\n";
				    $s .= "<descuento>".number_format($Descucaltres, 2, '.', '')."</descuento>\n";                                                                                                   
				    $s .= "<precioTotalSinImpuesto>".number_format($tarifa12sin, 2, '.', '')."</precioTotalSinImpuesto>\n";
				    $s .= "<impuestos>\n";				    				   
			    	$s .= "<impuesto>\n";
                           if($iva_venta=='No'){
				    $s .= "<codigo>2</codigo>\n";
				    $s .= "<codigoPorcentaje>0</codigoPorcentaje>\n";
                                   
				    $s .= "<tarifa>0.00</tarifa>\n";
				    $s .= "<baseImponible>".number_format($tarifa12sin, 2, '.', '')."</baseImponible>\n";
				    $s .= "<valor>0.00</valor>\n";
				    $s .= "</impuesto>\n";					    			    			    
				    $s .= "</impuestos>\n";
				    $s .= "</detalle>\n";
                           }else {
                                    $iva=$row[8];
                                $s .= "<codigo>2</codigo>\n";
				    $s .= "<codigoPorcentaje>2</codigoPorcentaje>\n";
                                   
				    $s .= "<tarifa>12</tarifa>\n";
				    $s .= "<baseImponible>".number_format($baseimponible, 2, '.', '')."</baseImponible>\n";
				     $s .= "<valor>".number_format($iva, 2, '.', '')."</valor>\n";
				    $s .= "</impuesto>\n";					    			    			    
				    $s .= "</impuestos>\n";
				    $s .= "</detalle>\n";
                           }
				}

		  	$s .= "</detalles>\n";				
			$s .= "<infoAdicional>\n";
                        
				$s .= "<campoAdicional nombre=\"DIRECCION\">".' '.substr($direcion,0,299)."</campoAdicional>\n";									
				$s .= "<campoAdicional nombre=\"TELEFONO\">".' '.utf8_decode(substr($telefono,0,299))."</campoAdicional>\n";	
				$s .= "<campoAdicional nombre=\"EMAIL\">".' '.utf8_decode(substr($email,0,299))."</campoAdicional>\n";
                                
//                                $s .= "<campoAdicional nombre=\"MARCA VEHICULO\">".' '.utf8_decode(substr($marca_delvehiculo,0,299))."</campoAdicional>\n";
//                                $s .= "<campoAdicional nombre=\"PLACA\">".' '.utf8_decode(substr($placanum,0,299))."</campoAdicional>\n";
//                                $s .= "<campoAdicional nombre=\"PROPIEDAD\">".' '.utf8_decode(substr($propiedad,0,299))."</campoAdicional>\n";
//                                $s .= "<campoAdicional nombre=\"NUM RECLAMO\">".' '.utf8_decode(substr($num_reclamo,0,299))."</campoAdicional>\n";
//				 $s .= "<campoAdicional nombre=\"NUM CHASIS\">".' '.utf8_decode(substr($num_chasis,0,299))."</campoAdicional>\n";
			$s .= "</infoAdicional>";	
		$s .="\n</liquidacionCompra>";
		return $s;
	}
	function generarXMLCDATALIQUI($data) {				
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