<?php

//        include '../fpdf/rotation.php';        
//        include("../fpdf/barcode.inc.php");
//        include '../procesos/base.php';
include '../../fpdf/rotation.php';
include("../../fpdf/barcode.inc.php");
require_once('../../procesos/base.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//error_reporting(0);
class PDF extends PDF_Rotate {

    var $widths;
    var $aligns;

    function SetWidths($w) {
        $this->widths = $w;
    }

    function Header() {
        $this->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->SetFont('Amble-Regular', '', 10);
        $fecha = date('Y-m-d', time());
        $this->SetY(1);
        $this->Cell(20, 5, 'Generado: ' . $fecha, 0, 0, 'C', 0);
//	        $this->Cell(178, 5, 'SUPERMERCADO SUPER FIESTA', 0,0, 'R', 0);                                                             
        $this->Ln(7);
        $this->SetX(13);
        // $this->RotatedImage('../../fpdf/logo.fw.png', 50, 150, 100, 80, 45);                            
        $this->SetX(0);
    }

    function Footer() {
        $this->SetY(-10);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    function RotatedImage($file, $x, $y, $w, $h, $angle) {
        $this->Rotate($angle, $x, $y);
        $this->Image($file, $x, $y, $w, $h);
        $this->Rotate(0);
    }

}

if (isset($_GET['id'])) {

    $id = $_GET['id'];

    generarPDFcorreo($id);
}

function generarPDFcorreo($id) {
    conectarse();

    $consulta = pg_query("SELECT nombre_empresa, ruc_empresa, direccion_empresa, celular_empresa, email_empresa, 
        nombre_comercial, obligacion, contribuyente_espe, establecimiento, punto_emision,
        fecha_actual as fecha_emision, num_autorizacion, fecha_autorizacion, num_factura, num_serie, 
        fv.clave, serie_guia_remision, marca_vehiculo, identificacion, nombres_cli
        from empresa e left join factura_venta fv using(id_empresa) 
        left join clientes c using(id_cliente) 
        left join tipo_documento td using(id_tdocu) 
        where fv.id_factura_venta='" . $id . "' ");
    while ($row = pg_fetch_assoc($consulta)) {
       $razonSocial = $row['nombre_empresa'];
        $ruc = $row['ruc_empresa'];
        $direcion = $row['direccion_empresa'];
        $direccionEstablecimiento = $row['direccion_empresa'];
        $telefono = $row['celular_empresa'];
        $email = $row['email_empresa'];
        // $nombreComercial = $row['nombre_comercial'];
        $obligado = $row['obligacion'];
        // $nroContribuyente = $row['contribuyente_espe'];
        $establecimiento = $row['establecimiento'];
        $puntoEmision = $row['punto_emision'];
        $fechaEmision = $row['fecha_emision'];
        $date = new DateTime($fechaEmision);
        $fechaEmision = $date->format('d/m/Y');
        $numeroAutorizacion = $row['num_autorizacion'];
        if ($numeroAutorizacion == "") {
            $numeroAutorizacion = $row['clave'];
        } else {
            $numeroAutorizacion = $row['num_autorizacion'];
        }
        $fechaAut = $row['fecha_autorizacion'];
        $secuencial = "$row[num_serie]" . "-" . "$row[num_factura]";
        $ip = $secuencial;
        $iparr = split("\-", $ip);
        $secuencial = $iparr[2];
        $claveAcceso = $row['clave'];
        $num_serie_guia = $row['serie_guia_remision'];
        if ($num_serie_guia != "000000000") {
            $num_serie_guia = $row['serie_guia_remision'];
        } else {
            $num_serie_guia = "";
        }
        $marca_delvehiculo = $row['marca_vehiculo'];
        // $placanum = $row['placa_fac'];
        // $propiedad = $row['propiedad'];
        // $num_reclamo = $row['num_reclamo'];
        // $num_chasis = $row['num_chasis'];
        $identificacion = $row['identificacion'];
        $contribuyente = $row['nombres_cli'];
        // $codigo = $row['codigo_tdocu'];


        $consulta_ambiente = pg_query("select nombre_ambi from ambiente  where id_ambi=2 ");
        while ($row = pg_fetch_row($consulta_ambiente)) {
            $nombre_ambi = $row[0];
        }
        $ambiente = $nombre_ambi;
        $consulta_emision = pg_query("select nombre_temision from tipo_emision where id_temision=1 ");
        while ($row = pg_fetch_row($consulta_emision)) {
            $nombre_emi = $row[0];
        }
        $emision = $nombre_emi;
    }



    $consulta_ambiente = pg_query("select nombre_ambi from ambiente where id_ambi=2  ");
    while ($row = pg_fetch_row($consulta_ambiente)) {
        $ambiente = $row[0];
    }

    $consulta_emision = pg_query("select nombre_temision from tipo_emision where id_temision=1  ");
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

    $pdf = new PDF('P', 'mm', 'a4');
    $pdf->AddPage();
    $pdf->SetMargins(10, 0, 0, 0);
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak(true, 10);
    $pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
    $pdf->SetFont('Amble-Regular', '', 9);

//		$logo = $imagen;
    $pdf->Image('../../images/'.$_SESSION["parametros_empresa"]["logo_empresa"], 30, 9, 35); // Img Empresa
//		$pdf->Rect(3, 8, 100, 36 ,1, 'D');
//		$pdf->Image('C:\xampp\htdocs\syswebfe\images\logo.png',5,10,100); // Img Empresa 
    // $pdf->Image('C:\xampp\htdocs\sysweb\images\logo.png',10,7,80);

    $pdf->Rect(3, 45, 100, 53, 'D'); // 2 datos personales
    $pdf->Text(108, 15, 'RUC:' . $ruc); // ruc		 	
    $pdf->Text(108, 23, utf8_decode("F  A  C  T  U  R  A")); // Tipo comprobante
    $pdf->Text(108, 31, 'No. ' . $establecimiento . '-' . $puntoEmision . '-' . $secuencial); // Secuencial
    $pdf->Text(108, 39, utf8_decode('NÚMERO DE AUTORIZACIÓN')); // N° Autorizacion
    $pdf->SetY(40);
    $pdf->SetX(107);
    $pdf->Multicell(100, 5, $numeroAutorizacion, 0); // N° Autorización	

    if ($fechaAut != '') {
        $pdf->Text(108, 55, utf8_decode('FECHA Y HORA DE AUTORIZACIÓN')); // fecha y hora de autorizacion
        $pdf->Text(108, 61, $fechaAut); // FECHA
    }




    $pdf->Text(108, 68, utf8_decode('AMBIENTE: ' . $ambiente)); // Ambiente
    $pdf->Text(108, 75, utf8_decode('EMISIÓN: ' . $emision)); // Tipo de emision
//		$pdf->Text(108, 81, utf8_decode('CLAVE DE ACCESO: ')); // Clave de acceso
    $code_number = $claveAcceso; // Código de barras		
    new barCodeGenrator($code_number, 1, 'temp.gif', 470, 60, true); /// img codigo barras	
    $pdf->Image('temp.gif', 108, 83, 96, 15);

    $pdf->Rect(106, 8, 102, 90, 'D'); //Datos Empresa	 
    $pdf->SetY(46);
    $pdf->SetX(4);
    $pdf->multiCell(98, 5, $razonSocial, 0); // Razon Social Empresa	
    $pdf->SetY(58);
    $pdf->SetX(4);
    $pdf->SetY(50);
    $pdf->SetX(4);
    $pdf->multiCell(98, 5, 'Dir Matriz: ' . $direccionEstablecimiento."  "."Telf: $telefono", 0); // Direccion Matriz	
    $pdf->SetY(70);
    $pdf->SetX(4);
    $pdf->multiCell(98, 5, 'Dir Sucursal: ' . $direccionEstablecimiento, 0); // Direccion Establecimiento	
    $pdf->Text(5, 96, utf8_decode('Obligado a llevar Contabilidad: ' . $obligado)); // Obligado a llevar contabilidad
    $pdf->SetFont('Amble-Regular', '', 8);
    $pdf->Text(5, 89, utf8_decode('Agente de Retención Mediante Resolución Nro. NAC-DNCRASC20-00000001')); //fecha de emision cliente
    $pdf->Text(5, 92, utf8_decode('Contribuyente Regimen RIMPE')); //obligado

    $pdf->Rect(3, 101, 205, 20, 'D'); // INFO TRIBUTARIA			     
    $pdf->SetY(101);
    $pdf->SetX(3);
    $pdf->multiCell(130, 6, utf8_decode('Razón Social / Nombres y Apellidos: ' . $contribuyente), 0); // Nombre cliente	
    $pdf->Text(135, 105, utf8_decode('RUC / CI:   ' . $identificacion)); // Ruc cliente
    $pdf->Text(5, 117, utf8_decode('Fecha de Emisión: ' . $fechaEmision)); //fecha de emision cliente
    $pdf->Text(136, 117, utf8_decode('Guía de Remisión: ' . $num_serie_guia)); //guia remision 
    // detalles factura
    $pdf->SetFont('Amble-Regular', '', 8);
   // $pdf->SetY(123);
  //  $pdf->SetX(3);
  //  $pdf->multiCell(40, 5, utf8_decode('Cod. Principal'), 1);

    $pdf->SetY(123);
    $pdf->SetX(3);
    $pdf->multiCell(15, 5, utf8_decode('Cantidad'), 1);
    $pdf->SetY(123);
    $pdf->SetX(18);
    $pdf->multiCell(135, 5, utf8_decode('Descripción'), 1);
    $pdf->SetY(123);
    $pdf->SetX(153);
    $pdf->multiCell(17, 5, utf8_decode('Precio U.'), 1);
    $pdf->SetY(123);
    $pdf->SetX(170);
    $pdf->multiCell(18, 5, utf8_decode('Descu.%'), 1);
    $pdf->SetY(123);
    $pdf->SetX(188);
    $pdf->multiCell(20, 5, utf8_decode('Total'), 1);

    $x = 128;
    $y = 1;

    $resultado = pg_query("select P.codigo,P.cod_barras, P.articulo, D.cantidad, D.precio_venta, D.descuento_producto, F.tarifa12 from factura_venta F,detalle_factura_venta D  , productos P where  d.cod_productos =P.cod_productos   and D.id_factura_venta = F.id_factura_venta  AND   F.id_factura_venta = '" . $id . "'");

    while ($row = pg_fetch_row($resultado)) {
        $codigo = utf8_decode($row[0]);
//			$codigoAuxiliar = utf8_decode($row[1]);
        $codigoAuxiliar = '';
//        $descripcion = utf8_decode($row[2]);
          $descripcion = utf8_decode($row[2]." ".$marca_delvehiculo);
        $cantidad = $row[3];
        $tarifa12 = 0;
        $tarifa12 = $row[4];

        $precio = number_format($row[4], 2, '.', '');
        $descuento = $row[5];
        $tarifa12 = $tarifa12 * $cantidad;
        $Descucaltres = 0;
        $desc = 0;
        $desc = $row[5];
        $valcien = 100;
        $Descucaltres = ($tarifa12 / $valcien) * $desc;
        $tarifa12sin = $tarifa12 - $Descucaltres;
        $total = number_format($tarifa12sin, 2, '.', '');

      //  $pdf->SetY($x);
     //   $pdf->SetX(3);

      //  $pdf->multiCell(40, 3, $codigo, 1);

//			$pdf->SetY($x);
//			$pdf->SetX(23);
//			if(strlen($codigoAuxiliar) > 19)
//				$tam = 5;
//			else
//				$tam = 10;	
//			$pdf->multiCell(20, $tam, $codigoAuxiliar,1);

        $pdf->SetY($x);
        $pdf->SetX(3);
        if (strlen($cantidad) > 10)
            $tam = 3;
        else
            $tam = 3;
        $pdf->multiCell(15, $tam, $cantidad, 1,'R',0);

        $pdf->SetY($x);
        $pdf->SetX(18);
        if (strlen($descripcion) > 50)
            $tam = 3;
        else
            $tam = 3;
        $pdf->multiCell(135, $tam, $descripcion, 1);

        $pdf->SetY($x);
        $pdf->SetX(153);
        if (strlen($precio) > 10)
            $tam = 3;
        else
            $tam = 3;
        $pdf->multiCell(17, $tam, $precio, 1,'R',0);

        $pdf->SetY($x);
        $pdf->SetX(170);
        if (strlen($descuento) > 15)
            $tam = 3;
        else
            $tam = 3;
        $pdf->multiCell(18, $tam, $descuento, 1,'R',0);

        $pdf->SetY($x);
        $pdf->SetX(188);
        if (strlen($total) > 10)
            $tam = 3;
        else
            $tam = 3;
        $pdf->multiCell(20, $tam, $total, 1,'R',0);
        $x = $x + 3;
    }

    // pie de pagina           	
    if ($pdf->getY() <= 500) {
        $pdf->Ln(5);
        $pdf->SetX(3);
        $pdf->Rect($pdf->GetX(), $pdf->GetY(), 100, 40, 'D'); ////3 INFO ADICIONAL	   
        $y = $pdf->GetY();
        $x = $pdf->GetX();
        $y1 = $pdf->GetY();
        $x1 = $pdf->GetX();
        $pdf->Text($x + 5, $y + 5, utf8_decode('INFORMACIÓN ADICIONAL')); //informacion 		
        $pdf->SetY($y + 5);
        $pdf->SetX($x);
        $pdf->multiCell(100, 5, utf8_decode("Dirección:" . $direcion), 0);
        $pdf->SetY($y + 13);
        $pdf->SetX($x);
        $pdf->multiCell(100, 10, utf8_decode("Teléfono: " . $telefono), 0);
        $pdf->SetY($y + 20);
        $pdf->SetX($x);
        $pdf->multiCell(100, 5, utf8_decode("Email: " . $email), 0);
//                        if($marca_delvehiculo!=""||$placanum!=""||$propiedad!=""||$num_reclamo!=""||$num_chasis!=""){
//                        $pdf->SetY($y + 14);
//			$pdf->SetX($x);
//			$pdf->multiCell(100, 11, utf8_decode("Marca del Vehiculo:      ".$marca_delvehiculo ),0 );
//                        $pdf->SetY($y + 17);
//			$pdf->SetX($x);
//			$pdf->multiCell(100, 11, utf8_decode("Placa:                                ".$placanum ),0 );
//                         $pdf->SetY($y + 20);
//			$pdf->SetX($x);
//			$pdf->multiCell(100, 11, utf8_decode("Propiedad de:                 ".$propiedad ),0 );
//                          $pdf->SetY($y + 23);
//			$pdf->SetX($x);
//			$pdf->multiCell(100, 11, utf8_decode("Num Reclamo:                 ".$num_reclamo ),0 );
//                         $pdf->SetY($y + 26);
//			$pdf->SetX($x);
//			$pdf->multiCell(100, 11, utf8_decode("Num Chasis:                    ".$num_chasis ),0 );
//                        }

        $resultado = pg_query("SELECT F.tarifa12, F.tarifa0, F.tarifa0, F.iva_venta, F.descuento_venta, F.total_venta FROM factura_venta f WHERE id_factura_venta = '" . $id . "'");
        while ($row = pg_fetch_row($resultado)) {
            $subtotal = $row[0];
            $tarifa = $row[0];
            $tarifa0 = $row[2];
            $iva = $row[3];
            $descuento = $row[4];
            $total = number_format($row[5], 2, ',', '.');
        }

        $pdf->Ln(5);
        $pdf->SetX(108);
        $x1 = $x1 + 105;
        $pdf->SetY($y1);
        $pdf->SetX($x1);
        $pdf->multiCell(62, 6, utf8_decode("Subtotal 12 %"), 1);
        $pdf->SetY($y1);
        $pdf->SetX($x1 + 62);
        $pdf->multiCell(38, 6, number_format($tarifa, 2, '.', ''), 1,'R',0);
        $pdf->SetY($y1 + 6);
        $pdf->SetX($x1);
        $pdf->multiCell(62, 6, utf8_decode("Subtotal IVA 0 %"), 1);
        $pdf->SetY($y1 + 6);
        $pdf->SetX($x1 + 62);
        $pdf->multiCell(38, 6, number_format($tarifa0, 2, '.', ''), 1,'R',0);
        $pdf->SetY($y1 + 12);
        $pdf->SetX($x1);
        $pdf->multiCell(62, 6, utf8_decode("Descuento"), 1);
        $pdf->SetY($y1 + 12);
        $pdf->SetX($x1 + 62);
        $pdf->multiCell(38, 6, number_format($descuento, 2, '.', ''), 1,'R',0);
        $pdf->SetY($y1 + 18);
        $pdf->SetX($x1);
        $pdf->multiCell(62, 6, utf8_decode("IVA 12 %"), 1);
        $pdf->SetY($y1 + 18);
        $pdf->SetX($x1 + 62);
        $pdf->multiCell(38, 6, number_format($iva, 2, '.', ''), 1,'R',0);
        $pdf->SetY($y1 + 24);
        $pdf->SetX($x1);
        $pdf->multiCell(62, 6, utf8_decode("PROPINA"), 1);
        $pdf->SetY($y1 + 24);
        $pdf->SetX($x1 + 62);
        $pdf->multiCell(38, 6, utf8_decode("0.00"), 1,'R',0);
        $pdf->SetY($y1 + 30);
        $pdf->SetX($x1);
        $pdf->multiCell(62, 6, utf8_decode("TOTAL"), 1);
        $pdf->SetY($y1 + 30);
        $pdf->SetX($x1 + 62);
        $pdf->multiCell(38, 6, ($total), 1,'R',0);

        // FORMAS DE PAGO	           	
        $pdf->SetX(3);
        $y = $pdf->GetY();
        $x = $pdf->GetX();
        $pdf->SetY($y + 7);
        $pdf->SetX($x);
        $pdf->multiCell(80, 6, utf8_decode("FORMAS DE PAGO"), 1);
        $pdf->SetY($y + 7);
        $pdf->SetX($x + 80);
        $pdf->multiCell(20, 6, utf8_decode("VALOR"), 1);

        $resultado = pg_query("SELECT P.descripcion FROM factura_venta F, forma_pagos P WHERE F.id_forma_pago = P.id_forma_pago AND F.id_factura_venta = '" . $id . "'");
        while ($row = pg_fetch_row($resultado)) {
            $pdf->SetY($y + 13);
            $pdf->SetX($x);
            $pdf->multiCell(80, 6, utf8_decode($row[0]), 1);
            $pdf->SetY($y + 13);
            $pdf->SetX($x + 80);
            $pdf->multiCell(20, 6, utf8_decode($total), 1);
        }
    } else {
        // $pdf->AddPage();
        // $pdf->Ln(5);
        // $pdf->SetX(3);		   
        //    $pdf->Rect($pdf->GetX(), $pdf->GetY(), 100, 40 , 'D');////3 INFO ADICIONAL	   
        // $y =  $pdf->GetY();
        // $x =  $pdf->GetX();	
        // $y1 =  $pdf->GetY();
        // $x1 =  $pdf->GetX();	
        // $pdf->Text($x + 5, $y + 5, utf8_decode('INFORMACIÓN ADICIONAL'));//informacion 		
        // $pdf->SetY($y + 7);
        // $pdf->SetX($x);
        // $pdf->multiCell( 100, 5, utf8_decode("Dirección:".$direcion ),0 );
        // $pdf->SetY($y + 17);
        // $pdf->SetX($x);
        // $pdf->multiCell( 100, 10, utf8_decode("Teléfono: ".$telefono ),0 );
        // $pdf->SetY($y + 29);
        // $pdf->SetX($x);
        // $pdf->multiCell( 100, 5, utf8_decode("Email: ".$email ),0 );		
        // ///////TOTALES////
        // $sql = "select 
        // 	DIP.idImpuesto,
        //     TI.codigo,
        //     TI.nombre,
        //     TTI.nombre,
        //     TTI.codigo impuestoCodigo,
        //     sum(DF.cantidad)cantidad,
        //     sum(DF.valorProducto)valor,
        //     sum(DIP.valor)valorImpuesto				    
        // from factura F 
        // inner join detallefactura DF on F.id = DF.idFactura
        // inner join producto P on P.id = DF.idProducto
        // inner join detalleimpuestoproducto DIP on DF.id = DIP.idDetalleFactura
        // inner join tarifaimpuesto TI on TI.id = DIP.idImpuesto
        // inner join tipoimpuesto TTI on TTI.id = TI.idImpuesto
        // where F.id = '".$id."'
        // group by DIP.idImpuesto";
        // //echo $sql;
        // $sql = $class->consulta($sql);
        // $subtotal12 = 0;
        // $subtotal0 = 0;
        // $iva12 = 0;
        // while ($row = $class->fetch_array($sql)) {
        // 	$iva12 = $iva12 + $row[7];
        // 	if($row[2] == 12){
        // 		$subtotal12 = $subtotal12 + ($row[6]);
        // 	}else{
        // 		$subtotal0 = $subtotal0 + ($row[6]);
        // 	}
        // }
        // $pdf->Ln(5);
        // $pdf->SetX(108);
        // $x1 = $x1 + 105;		   
        //    $pdf->SetY($y1);
        // $pdf->SetX($x1);
        // $pdf->multiCell( 62, 6, utf8_decode("Subtotal 12 %"),1 );	
        // $pdf->SetY($y1);
        // $pdf->SetX($x1+62);
        // $pdf->multiCell( 38, 6, number_format($subtotal12, 2, '.', ''),1 );
        // $pdf->SetY($y1 + 6);
        // $pdf->SetX($x1);
        // $pdf->multiCell( 62, 6, utf8_decode("Subtotal IVA 0 %"),1 );	
        // $pdf->SetY($y1 + 6);
        // $pdf->SetX($x1 + 62);
        // $pdf->multiCell( 38, 6, number_format($subtotal0, 2, '.', ''),1 );	
        // $pdf->SetY($y1 + 12);
        // $pdf->SetX($x1);
        // $pdf->multiCell( 62, 6, utf8_decode("Descuento"),1 );	
        // $pdf->SetY($y1 + 12);
        // $pdf->SetX($x1 + 62);
        // $pdf->multiCell( 38, 6, utf8_decode("0.00"),1 );
        // $pdf->SetY($y1 + 18);
        // $pdf->SetX($x1);
        // $pdf->multiCell( 62, 6, utf8_decode("IVA 12 %"),1 );	
        // $pdf->SetY($y1 + 18);
        // $pdf->SetX($x1 + 62);
        // $pdf->multiCell( 38, 6, number_format($iva12, 2, '.', ''),1 );	
        // $pdf->SetY($y1 + 24);
        // $pdf->SetX($x1);
        // $pdf->multiCell( 62, 6, utf8_decode("PROPINA"),1 );	
        // $pdf->SetY($y1 + 24);
        // $pdf->SetX($x1 + 62);
        // $pdf->multiCell( 38, 6, utf8_decode("0.00"),1 );
        // $pdf->SetY($y1 + 30);
        // $pdf->SetX($x1);
        // $pdf->multiCell( 62, 6, utf8_decode("TOTAL"),1 );	
        // $pdf->SetY($y1 + 30);
        // $pdf->SetX($x1 + 62);
        // $pdf->multiCell( 38, 6, number_format($subtotal0 + $subtotal12 + $iva12, 2, '.', ''),1 );	
        // /////////////////FORMAS DE PAGO//////////	           	
        // $pdf->SetX(3);		   	    
        // $y =  $pdf->GetY();
        // $x =  $pdf->GetX();				
        // $pdf->SetY($y + 7);
        // $pdf->SetX($x);
        // $pdf->multiCell( 80, 6, utf8_decode("FORMAS DE PAGO"),1 );
        // $pdf->SetY($y + 7);
        // $pdf->SetX($x + 80);
        // $pdf->multiCell( 20, 6, utf8_decode("VALOR"),1 );
        // $sql = "SELECT FP.nombre, FPF.valor FROM factura F inner join formapagofactura FPF on  FPF.idFactura = F.id inner join formapago FP on FPF.idFormaPago = Fp.id where F.id = '".$id."'";		        
        //       $sql = $class->consulta($sql);
        // while ($row = $class->fetch_array($sql)) {
        // 	$pdf->SetY($y + 13);
        // 	$pdf->SetX($x);
        // 	$pdf->multiCell( 80, 6, utf8_decode($row[0]),1 );
        // 	$pdf->SetY($y + 13);
        // 	$pdf->SetX($x + 80);
        // 	$pdf->multiCell( 20, 6, utf8_decode($row[1]),1 );
        // }
    }
    if (isset($_GET['id'])) {
        $pdf->Output();
    } else {
        $pdf_file_contents = $pdf->Output("", "S");
        return $pdf_file_contents;
    }
    // $pdf->Output();		
}

?>