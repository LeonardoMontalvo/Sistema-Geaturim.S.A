<?php
        
//        include '../fpdf/rotation.php';        
//        include("../fpdf/barcode.inc.php");
//        include '../procesos/base.php';

        include '../../fpdf/rotation.php';        
        include("../../fpdf/barcode.inc.php");
        require_once('../../procesos/base.php');
        //error_reporting(0);
	class PDF extends PDF_Rotate {   
	    var $widths;
	    var $aligns;       
	    function SetWidths($w) {            
	        $this->widths = $w;
	    }

	    function Header() {                         
	        $this->AddFont('Amble-Regular','','Amble-Regular.php');
	        $this->SetFont('Amble-Regular','',10);        
	        $fecha = date('Y-m-d', time());           
	        $this->SetY(1);
//	        $this->Cell(35, 5, 'Generado: '.$fecha, 0,0, 'C', 0);                                                             
//	        $this->Cell(145, 5, 'CUERPO DE BOMBEROS DE SHUSHUFINDI', 0,0, 'R', 0);                                                             
	        $this->Ln(7);
	        $this->SetX(13);
	        // $this->RotatedImage('../../fpdf/logo.fw.png', 50, 150, 100, 80, 45);                            
	        $this->SetX(0);            
		}

	    function Footer() {            
	        $this->SetY(-10);            
	        $this->SetFont('Arial','I',8);            
	        $this->Cell(0,10,'Pag. '.$this->PageNo().'/{nb}',0,0,'C');
	    } 

	   	function RotatedImage($file, $x, $y, $w, $h, $angle) {            
	        $this->Rotate($angle, $x, $y);
	        $this->Image($file, $x, $y, $w, $h);
	        $this->Rotate(0);
	    }      		         
	}

	if(isset($_GET['id'])) {
            
		$id = $_GET['id'];
              
		generarPDFcorreo($id);
	}

	function generarPDFcorreo($id) { 
         conectarse(); 
            
		$consulta = pg_query("select * from empresa left join liquidacion_compra on empresa.id_empresa  = liquidacion_compra.id_empresa left join proveedores on liquidacion_compra.id_proveedor=proveedores.id_proveedor left join tipo_documento on tipo_documento.id_tdocu=proveedores.id_tdocu where liquidacion_compra.id_liquidacion_compra='".$id."' ");	
		while ($row = pg_fetch_row($consulta)) {
			$ruc = $row[2];	                     
			$numeroAutorizacion = $row[35];
                         if($numeroAutorizacion==""){
                            $numeroAutorizacion=$row[51];
                        }else
                        {
                            $numeroAutorizacion=$row[35];
                        }  
			$fechaEmision = $row[30];
                        $date = new DateTime($fechaEmision);
                        $fechaEmision= $date->format('d/m/Y');    
			$claveAcceso = $row[51];
			$razonSocial = $row[1];
			$nombreComercial = $row[16];
			$direcionMatriz = $row[7];
			
			$direccionEstablecimiento = $row[3];
                        
			$nroContribuyente = $row[19];
			$obligado = $row[17];
			$contribuyente = $row[61];
			$identificacion = $row[60];
			$direcion = $row[64];
			$telefono = $row[66];
			$email = $row[70];			
			$secuencial = $row[29];
                        $ip = $secuencial;
                        $iparr = split ("\-", $ip); 
                        $secuencial=$iparr[2];                        
			$establecimiento = $row[21];
			$puntoEmision = $row[22];
			$fechaAut = $row[36];
			$codigo = $row[73];
                        
                        
                        $marca_delvehiculo = $row[55];
			$placanum = $row[56];
			$propiedad = $row[57];
			$num_reclamo = $row[58];
                        $num_chasis = $row[59];
                     
                        
                        
                          $num_serie_guia = $row[54];
                           if($num_serie_guia!="000000000"){
                               $num_serie_guia = $row[54];
                           }else
                           {
                                 $num_serie_guia="";
                           }
                        
                        
                        $consulta_ambiente = pg_query("select nombre_ambi from ambiente where id_ambi='2' ");
		    while ($row = pg_fetch_row($consulta_ambiente)) {
                        $nombre_ambi = $row[0];
                    }
			$ambiente = $nombre_ambi;
                         $consulta_emision = pg_query("select nombre_temision from tipo_emision  ");
		    while ($row = pg_fetch_row($consulta_emision)) {
                        $nombre_emi = $row[0];
                    }
			$emision = $nombre_emi;
                      
			
                   
		}	
                
               

		 $consulta_ambiente = pg_query("select nombre_ambi from ambiente where id_ambi='2' ");
		    while ($row = pg_fetch_row($consulta_ambiente)) {
                        $ambiente = $row[0];
                    }

		$consulta_emision = pg_query("select nombre_temision from tipo_emision  ");
		    while ($row = pg_fetch_row($consulta_emision)) {
                        $emision = $row[0];
                    }

//		$ceros = 9;
//		$temp = '';
//		$tam = $ceros - strlen($secuencial);
//	  	for ($i = 0; $i < $tam; $i++) {                 
//	    	$temp = $temp .'0';        
//	  	}
//	  	$secuencial = $temp .''. $secuencial;

		$pdf = new PDF('P','mm','a4');
		$pdf->AddPage();
		$pdf->SetMargins(10,0,0,0);        
		$pdf->AliasNbPages();
		$pdf->SetAutoPageBreak(true, 10);
		$pdf->AddFont('Amble-Regular','','Amble-Regular.php');
		$pdf->SetFont('Amble-Regular','',10); 

//		$logo = $imagen;
               
//		$pdf->Rect(3, 8, 100, 36 ,1, 'D');
//		$pdf->Image('D:\xampp\htdocs\syswebfeb\images\logo.png',25,5,38); // Img Empresa 
        //        $pdf->Image('C:\xampp\htdocs\syswebcarias\images\logo.png',10,7,80);
		
		$pdf->Rect(8, 45, 97, 53 , 'D'); // 2 datos personales
		$pdf->Text(108, 15, 'RUC:           '. $ruc); // ruc
                $pdf->SetFillColor(170,170,170);
		$pdf->Text(108, 21, utf8_decode("LIQUIDACIÓN DE COMPRA DE BIENES"),1,1, 'L',true);
                $pdf->Text(108, 25, utf8_decode("Y PRESTACIÓN DE SERVICIOS"),1,1, 'L',true);// Tipo comprobante // Tipo comprobante
		$pdf->Text(108, 31, 'No. '. $establecimiento.'-'.$puntoEmision.'-'.$secuencial); // Secuencial
		$pdf->Text(108, 37, utf8_decode('NÚMERO DE AUTORIZACIÓN')); // N° Autorizacion
		$pdf->SetY(40);
		$pdf->SetX(107);	
		
                $code_number_auto = $numeroAutorizacion; // Código de barras		
//	
	new barCodeGenrator($code_number_auto,1,'tempp.gif', 470, 60, true); /// img codigo barras	
		$pdf->Image('tempp.gif',108,38,80,15);                     
                
//		$pdf->Text(108, 57, utf8_decode('FECHA Y HORA DE AUTORIZACIÓN')); // fecha y hora de autorizacion
//		$pdf->Text(108, 62, $fechaAut); // FECHA
                
		$pdf->Text(108, 68, utf8_decode('AMBIENTE: '.$ambiente)); // Ambiente
		$pdf->Text(108, 75, utf8_decode('EMISIÓN: '.$emision)); // Tipo de emision
		$pdf->Text(108, 81, utf8_decode('CLAVE DE ACCESO: ')); // Clave de acceso
		$code_number = $claveAcceso; // Código de barras		
//	
	new barCodeGenrator($code_number,1,'temp.gif', 470, 60, true); /// img codigo barras	
		$pdf->Image('temp.gif',108,83,80,15);                     

		$pdf->Rect(106, 8, 85, 90 , 'D'); //Datos Empresa	 
		$pdf->SetY(46);
		$pdf->SetX(8);
		$pdf->multiCell( 102,5, $razonSocial,0 ); // Razon Social Empresa	
		$pdf->SetY(56);
		$pdf->SetX(8);	
		$pdf->SetY(66);	
		$pdf->SetX(8);	
		$pdf->multiCell( 98, 5, 'Dir Matriz: '.$direccionEstablecimiento,0); // Direccion Matriz	
		$pdf->SetY(82);	
		$pdf->SetX(8);	
		$pdf->multiCell( 98, 5, 'Dir Sucursal: ',0);// Direccion Establecimiento	
		$pdf->Text(8, 96, utf8_decode('Obligado a llevar Contabilidad: '.$obligado)); // Obligado a llevar contabilidad
		
		$pdf->Rect(8, 101, 183, 20 , 'D'); // INFO TRIBUTARIA			     
	 	$pdf->SetY(101);
		$pdf->SetX(8);
		$pdf->multiCell( 130, 6, utf8_decode('Razón Social: '.$contribuyente ),0); // Nombre cliente	
		$pdf->Text(140, 119,  utf8_decode('RUC / CI:   '   .$identificacion)); // Ruc cliente
		$pdf->Text(9, 116, utf8_decode('Fecha de Emisión: '.$fechaEmision)); //fecha de emision cliente
		//$pdf->Text(136, 112, utf8_decode('Guía de Remisión: '.$num_serie_guia)); //guia remision 
                $pdf->Text(9, 120, utf8_decode('Dirección:  '.$direcion ),0 );
                 $pdf->SetFillColor(170,170,170); 
		// detalles factura
	    $pdf->SetFont('Amble-Regular','',8);               
//	    $pdf->SetY(123);
//		$pdf->SetX(8);
//		$pdf->multiCell( 40, 5, utf8_decode('Cod. Principal'),1,1, 'L',true);
		
		$pdf->SetY(123);
		$pdf->SetX(8);
		$pdf->multiCell( 15, 5, utf8_decode('Cantidad'),1,1, 'L',true);
		$pdf->SetY(123);
		$pdf->SetX(23);
		$pdf->multiCell( 120, 5, utf8_decode('Descripción'),1,1, 'L',true);
		$pdf->SetY(123);
		$pdf->SetX(136);
		$pdf->multiCell( 25, 5, utf8_decode('Precio U.'),1,1, 'L',true);
//		$pdf->SetY(123);
//		$pdf->SetX(153);
//		$pdf->multiCell( 18, 5, utf8_decode('Descu.%'),1,1, 'L',true);
		$pdf->SetY(123);
		$pdf->SetX(161);
		$pdf->multiCell( 30, 5, utf8_decode('Total'),1,1, 'L',true);
		
		$x = 128;
		$y = 1;
             
		$resultado = pg_query("select P.codigo,P.cod_barras, D.pendientes, D.cantidad, D.precio_venta, D.descuento_producto, F.tarifa12,p.articulo from factura_venta F,detalle_factura_venta D  , productos P where  d.cod_productos =P.cod_productos   and D.id_factura_venta = F.id_factura_venta  AND   F.id_factura_venta = '".$id."'");
                
		while ($row = pg_fetch_row($resultado)) {
			$codigo = utf8_decode($row[0]);
//			$codigoAuxiliar = utf8_decode($row[1]);
                        $codigoAuxiliar = '';
                        
                        if($row[2]==''){                            
                            $descripcion=utf8_decode($row[7]);                          
                        }
                        else{
                            $descripcion=utf8_decode($row[2]);
                        }
                        
			
			$cantidad = $row[3];
                         $tarifa12=0;
                         $tarifa12 = $row[4];
                         
			$precio = number_format($row[4], 2, '.', '');
			$descuento = $row[5];
                         $tarifa12=$tarifa12*$cantidad;
                             $Descucaltres=0;
                        $desc=0;   
                        $desc = $row[5];
                        $valcien=100;
                        $Descucaltres=($tarifa12/$valcien)*$desc;
                        $tarifa12sin=$tarifa12-$Descucaltres;
			$total = number_format($tarifa12sin, 2, '.', '');

//			$pdf->SetY($x);
//			$pdf->SetX(8);
//				
//			$pdf->multiCell(35, 3, $codigo,1);

//			$pdf->SetY($x);
//			$pdf->SetX(23);
//			if(strlen($codigoAuxiliar) > 19)
//				$tam = 5;
//			else
//				$tam = 10;	
//			$pdf->multiCell(20, $tam, $codigoAuxiliar,1);

			$pdf->SetY($x);
			$pdf->SetX(8);
			if(strlen($cantidad) > 10)
				$tam = 3;
			else
				$tam = 3;	
			$pdf->multiCell(15, $tam, $cantidad,1);

			$pdf->SetY($x);
			$pdf->SetX(23);
			if(strlen($descripcion) > 50)
				$tam = 3;
			else
				$tam = 3;	
			$pdf->multiCell(113, $tam, $descripcion,1);
			
			$pdf->SetY($x);
			$pdf->SetX(136);
			if(strlen($precio) > 10)
				$tam = 3;
			else
				$tam = 3;	
			$pdf->multiCell(25, $tam, $precio,1);

//			$pdf->SetY($x);
//			$pdf->SetX(153);
//			if(strlen($descuento) > 15)
//				$tam = 3;
//			else
//				$tam = 3;	
//			$pdf->multiCell(18, $tam, $descuento,1);

			$pdf->SetY($x);
			$pdf->SetX(161);
			if(strlen($total) > 10)
				$tam = 3;
			else
				$tam = 3;	
			$pdf->multiCell(30, $tam, $total,1);			
			$x = $x + 3;
		}

		// pie de pagina  
                 
		if($pdf->getY() <= 500) {
			$pdf->Ln(5);
			$pdf->SetX(8);		   
		    $pdf->Rect($pdf->GetX(), $pdf->GetY(), 100, 36 , 'D');////3 INFO ADICIONAL	   
			$y =  $pdf->GetY();
			$x =  $pdf->GetX();	
			$y1 =  $pdf->GetY();
			$x1 =  $pdf->GetX();	
			$pdf->Text($x + 5, $y + 5, utf8_decode('INFORMACIÓN ADICIONAL'));//informacion 		
			$pdf->SetY($y + 5);
			$pdf->SetX($x);
			$pdf->multiCell( 100, 5, utf8_decode("Dirección:".$direcion ),0 );
			$pdf->SetY($y + 7);
			$pdf->SetX($x);
			$pdf->multiCell( 100, 10, utf8_decode("Teléfono: ".$telefono ),0 );
			$pdf->SetY($y + 13);
			$pdf->SetX($x);
			$pdf->multiCell( 100, 5, utf8_decode("Email: ".$email ),0 );
                        $pdf->SetY($y + 20);
			$pdf->SetX($x);
//                        $cate=$_GET['catecc'];
//			$pdf->multiCell( 100, 5, utf8_decode("Categoria: ".$cate ),0 );

                        
			$resultado = pg_query("SELECT F.tarifa12, F.tarifa0, F.tarifa0, F.iva_venta, F.descuento_venta, F.total_venta FROM factura_venta f WHERE id_factura_venta = '".$id."'");		
			while ($row = pg_fetch_row($resultado)) {
				$subtotal = $row[0];
				$tarifa = $row[0];
				$tarifa0 = $row[2];
				$iva = $row[3];
				$descuento = $row[4];				
                                $total =  number_format($row[5],2,',','.');  
                               
			}
                        
                        
                        
                        
                        
                        
                        
                       $pdf->SetFillColor(170,170,170); 
			$pdf->Ln(5);
			$pdf->SetX(108);
			$x1 = $x1 + 105;		   
		    $pdf->SetY($y1);
			$pdf->SetX($x1);
			$pdf->multiCell( 45, 6, utf8_decode("Subtotal 12 %"),1,1, 'L',true);	
			$pdf->SetY($y1);
			$pdf->SetX($x1+45);
			$pdf->multiCell(33, 6, number_format($tarifa, 2, '.', ''),1);
			$pdf->SetY($y1 + 6);
			$pdf->SetX($x1);
			$pdf->multiCell(45, 6, utf8_decode("Subtotal no Objeto IVA"),1,1, 'L',true);	
			$pdf->SetY($y1 + 6);
			$pdf->SetX($x1 + 45);
			$pdf->multiCell(33, 6, number_format($tarifa0, 2, '.', ''),1);	
			$pdf->SetY($y1 + 12);
			$pdf->SetX($x1);
			$pdf->multiCell( 45, 6, utf8_decode("Descuento"),1,1, 'L',true);	
			$pdf->SetY($y1 + 12);
			$pdf->SetX($x1 + 45);
			$pdf->multiCell(33, 6, number_format($descuento, 2, '.', ''),1);
			$pdf->SetY($y1 + 18);
			$pdf->SetX($x1);
			$pdf->multiCell(45, 6, utf8_decode("IVA 12 %"),1,1, 'L',true);	
			$pdf->SetY($y1 + 18);
			$pdf->SetX($x1 + 45);
			$pdf->multiCell(33, 6, number_format($iva, 2, '.', ''),1);	
			$pdf->SetY($y1 + 24);
			$pdf->SetX($x1);
			$pdf->multiCell(45, 6, utf8_decode("ICE"),1,1, 'L',true);	
			$pdf->SetY($y1 + 24);
			$pdf->SetX($x1 + 45);
			$pdf->multiCell(33, 6, utf8_decode("0.00"),1);
			$pdf->SetY($y1 + 30);
			$pdf->SetX($x1);
			$pdf->multiCell(45, 6, utf8_decode("VALOR TOTAL"),1,1, 'L',true);
			$pdf->SetY($y1 + 30);
			$pdf->SetX($x1 + 45);
			$pdf->multiCell(33, 6, ($total),1);	
                        
                        $pdf->Ln(5);
			$pdf->SetX(113);		   
		    $pdf->Rect($pdf->GetX(), $pdf->GetY(), 78, 16 , 'D');////3 INFO ADICIONAL	   
			$y =  $pdf->GetY();
			$x =  $pdf->GetX();	
			$y1 =  $pdf->GetY();
			$x1 =  $pdf->GetX();	
			$pdf->Text($x + 1, $y + 5, utf8_decode('VALOR TOTAL SIN SUBSIDIO             0.00'));//informacion 		
			$pdf->SetY($y + 7);
			$pdf->SetX($x);
			$pdf->multiCell( 100, 5, utf8_decode("AHORRO POR SUBSIDIO:" ),0 );
			$pdf->SetY($y + 7);
			$pdf->SetX($x);
			$pdf->multiCell( 100, 10, utf8_decode("(Incluye IVA cuando corresponda)    0.00" ),0 );
			
                        
                        
                        
                        
                        
		 $pdf->Ln(-20);
			// FORMAS DE PAGO	           	
			$pdf->SetX(8);		   	    
			$y =  $pdf->GetY();
			$x =  $pdf->GetX();				
			$pdf->SetY($y + 7);
			$pdf->SetX($x);
                      
			$pdf->multiCell( 80, 6, utf8_decode("FORMAS DE PAGO"),1);
			$pdf->SetY($y + 7);
			$pdf->SetX($x + 80);
			$pdf->multiCell( 20, 6, utf8_decode("VALOR"),1 );
   
			$resultado = pg_query("SELECT P.descripcion FROM factura_venta F, forma_pagos P WHERE F.id_forma_pago = P.id_forma_pago AND F.id_factura_venta = '".$id."'");
			while ($row = pg_fetch_row($resultado)) {
				$pdf->SetY($y + 13);
				$pdf->SetX($x);
				$pdf->multiCell(80, 6, utf8_decode($row[0]),1,1, 'L',true);
				$pdf->SetY($y + 13);
				$pdf->SetX($x + 80);
                                  
				$pdf->multiCell( 20, 6, utf8_decode($total),1,1, 'L',true);
                     
			}
		} else {
			
		}
		if(isset($_GET['id'])) {                
			$pdf->Output();		
		} else {
			$pdf_file_contents = $pdf->Output("","S");		
			return $pdf_file_contents;
		}
              // $pdf->Output();		
	}
       		
?>