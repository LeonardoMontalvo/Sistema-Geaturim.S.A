<?php

//        include '../fpdf/rotation.php';        
//        include("../fpdf/barcode.inc.php");
//        include '../procesos/base.php';
include __DIR__.'/../../../fpdf/rotation.php';
include( __DIR__."/../../../fpdf/barcode.inc.php");
require_once(__DIR__.'/../../../procesos/base.php');

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
        $this->SetFont('Amble-Regular', '', 7);
        $fecha = date('Y-m-d', time());
        $this->SetY(2);
//        $this->Cell(15, 4, 'Generado: ' . $fecha, 0, 0, 'C', 0);
        $this->SetFont('Amble-Regular', '', 4);
//        $this->Cell(122, 4, 'TRANSPORTE CARGA NACIONAL E INTERNACIONAL TRANSPOTESALTAMIRANO S.A.', 0, 0, 'R', 0);
        $this->Ln(7);
        $this->SetX(13);
        // $this->RotatedImage('../../fpdf/logo.fw.png', 50, 150, 100, 80, 45);                            
        $this->SetX(0);
    }

    function Footer() {
        $this->SetY(-10);
        $this->SetFont('Arial', 'I', 7);
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

    generarPDF($id);
}

function generarPDF($id) {
    conectarse();

    $consulta = pg_query("select * from empresa left join factura_venta on empresa.id_empresa  = factura_venta.id_empresa left join clientes on factura_venta.id_cliente=clientes.id_cliente left join tipo_documento on tipo_documento.id_tdocu=clientes.id_tdocu where factura_venta.id_factura_venta='" . $id . "' ");
    while ($row = pg_fetch_row($consulta)) {
        $ruc = $row[2];
        $numeroAutorizacion = $row[35];
        if ($numeroAutorizacion == "") {
            $numeroAutorizacion = $row[54];
        } else {
            $numeroAutorizacion = $row[35];
        }
        $fechaEmision = $row[30];
        $date = new DateTime($fechaEmision);
        $fechaEmision = $date->format('d/m/Y');
        $claveAcceso = $row[54];
        $razonSocial = $row[12];
        $nombreComercial = $row[16];
        $direcionMatriz = $row[7];
        $direccionEstablecimiento = $row[3];
        $nroContribuyente = $row[19];
        $obligado = $row[18];
        $contribuyente = $row[66];
        $identificacion = $row[65];
        $direcion = $row[3];
        $telefono_fijo = $row[4];
        $telefono = $row[5];
        $email = $row[9];
        $secuencial = "$row[50]" . "-" . "$row[29]";
        $ip = $secuencial;
        $iparr = split("\-", $ip);
        $secuencial = $iparr[2];
        $establecimiento = $row[22];
        $puntoEmision = $row[23];
        $fechaAut = $row[36];
        $codigo = $row[76];


        $marca_delvehiculo = $row[58];
        $placanum = $row[59];
        $propiedad = $row[60];
        $num_reclamo = $row[61];
        $num_chasis = $row[62];
        $direccion_cliente = $row[68];
        $telefono_cliente = $row[69];
        $num_serie_guia = $row[57];
        if ($num_serie_guia != "000000000") {
            $num_serie_guia = $row[57];
        } else {
            $num_serie_guia = "";
        }


        $consulta_ambiente = pg_query("select nombre_ambi from ambiente where estado_ambi='Activo'  ");
        while ($row = pg_fetch_row($consulta_ambiente)) {
            $nombre_ambi = $row[0];
        }
        $ambiente = $nombre_ambi;
        $consulta_emision = pg_query("select nombre_temision from tipo_emision where id_temision=1  ");
        while ($row = pg_fetch_row($consulta_emision)) {
            $nombre_emi = $row[0];
        }
        $emision = $nombre_emi;


    }

    $consulta_emision = pg_query("select nombre_temision from tipo_emision where id_temision=1 ");
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

    $pdf = new PDF('L', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->SetMargins(10, 0, 0, 0);
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak(true, 10);
    $pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
    $pdf->SetFont('Amble-Regular', '', 6);

//		$logo = $imagen;
    $pdf->Image('../../../images/'.$_SESSION["parametros_empresa"]["logo_empresa"], 3, 5, 40); // Img Empresa
    //$pdf->Rect(1, 3, 81, 20 ,1, 'D');
//		$pdf->Image('C:\xampp\htdocs\syswebfe\images\logo.png',5,10,60); // Img Empresa 
    // $pdf->Image('C:\xampp\htdocs\sysweb\images\logo.png',10,7,50);
    //$pdf->Rect(1, 24, 81, 53 , 'D'); // 2 datos personales
    $pdf->Text(5, 39, 'RUC:' . $ruc); // ruc		 	
    $pdf->Text(5, 33, utf8_decode("FACTURA")); // Tipo comprobante
    $pdf->Text(76, 26, 'No. ' . $establecimiento . '-' . $puntoEmision . '-' . $secuencial); // Secuencial
    $pdf->Text(76, 29, utf8_decode('NÚMERO DE AUTORIZACIÓN')); // N° Autorizacion
    //$pdf->SetY(40);
    //$pdf->SetX(107);	
    $pdf->Text(76, 32, $numeroAutorizacion); // N° Autorización		
    $pdf->Text(76, 35, utf8_decode('FECHA Y HORA DE AUTORIZACIÓN')); // fecha y hora de autorizacion
    $pdf->Text(76, 38, $fechaAut); //FECHA
    $pdf->Text(76, 41, utf8_decode('AMBIENTE: ' . $ambiente)); // Ambiente
    $pdf->Text(76, 44, utf8_decode('EMISIÓN: ' . $emision)); // Tipo de emision
//		$pdf->Text(91, 5, utf8_decode('CLAVE DE ACCESO: ')); // Clave de acceso
    $code_number = $claveAcceso; // Código de barras		
    new barCodeGenrator($code_number, 1, 'temp.gif', 470, 60, true); /// img codigo barras	
    $pdf->Image('temp.gif', 68, 9, 70, 15);
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////duplicacion/////7///////////////////////////////11111///////////////////////////////////////////////////////////////
    $pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
    $pdf->SetFont('Amble-Regular', '', 6);

//		$logo = $imagen;
    $pdf->Image('../../../images/'.$_SESSION["parametros_empresa"]["logo_empresa"], 147, 5, 40); // Img Empresa
    //$pdf->Rect(1, 3, 81, 20 ,1, 'D');
//		$pdf->Image('C:\xampp\htdocs\syswebfe\images\logo.png',5,10,60); // Img Empresa 
    // $pdf->Image('C:\xampp\htdocs\sysweb\images\logo.png',10,7,50);
    //$pdf->Rect(1, 24, 81, 53 , 'D'); // 2 datos personales
    $pdf->Text(154, 39, 'RUC:' . $ruc); // ruc		 	
    $pdf->Text(154, 33, utf8_decode("FACTURA")); // Tipo comprobante
    $pdf->Text(224, 26, 'No. ' . $establecimiento . '-' . $puntoEmision . '-' . $secuencial); // Secuencial
    $pdf->Text(224, 29, utf8_decode('NÚMERO DE AUTORIZACIÓN')); // N° Autorizacion
    //$pdf->SetY(40);
    //$pdf->SetX(107);	
    $pdf->Text(224, 32, $numeroAutorizacion); // N° Autorización		
    $pdf->Text(224, 35, utf8_decode('FECHA Y HORA DE AUTORIZACIÓN')); // fecha y hora de autorizacion
    $pdf->Text(224, 38, $fechaAut); //FECHA
    $pdf->Text(224, 41, utf8_decode('AMBIENTE: ' . $ambiente)); // Ambiente
    $pdf->Text(224, 44, utf8_decode('EMISIÓN: ' . $emision)); // Tipo de emision
//		$pdf->Text(91, 5, utf8_decode('CLAVE DE ACCESO: ')); // Clave de acceso
    $code_number = $claveAcceso; // Código de barras		
    new barCodeGenrator($code_number, 1, 'temp.gif', 470, 60, true); /// img codigo barras	
    $pdf->Image('temp.gif', 212, 9, 80, 15);

    /////////////////////////////////////////////////////////////////1111//////////////////////////////////////////////////////////////
    //$pdf->Rect(87, 3, 102, 90 , 'D'); //Datos Empresa	 
    //$pdf->SetX(50);
    //$pdf->SetY(20);
    $pdf->SetFont('Amble-Regular', '', 5);
    $pdf->Text(5, 36, $razonSocial); // Razon Social Empresa	
    //$pdf->SetY(56);
    //$pdf->SetX(4);	
    //$pdf->SetY(66);	
    //$pdf->SetX(4);
    $pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
    $pdf->SetFont('Amble-Regular', '', 4);
    $pdf->SetY(43);
    $pdf->SetX(4);
    $pdf->multiCell(55, 2, utf8_decode('Matriz: ' . $direccionEstablecimiento));
//    $pdf->Text(5, 46, 'Matriz: ' . $direccionEstablecimiento); // Direccion Matriz	
    //$pdf->SetY(76);	
    //$pdf->SetX(4);	
//		$pdf->Text(5, 45, 'Sucursal: '.$direccionEstablecimiento);// Direccion Establecimiento	
    $pdf->SetFont('Amble-Regular', '', 5);
    $pdf->Text(5, 42, utf8_decode('Obligado a llevar Contabilidad: ' . $obligado)); // Obligado a llevar contabilidad
    $pdf->Text(5, 55, utf8_decode('Agente de Retención Mediante Resolución Nro. NAC-DNCRASC20-00000001')); //fecha de emision cliente
//    $pdf->Text(5, 57, utf8_decode('Contribuyente Regimen Microempresas')); //obligado
//    $pdf->Text(5, 56, utf8_decode('Contribuyente Régimen Microempresarial')); //fecha de emision cliente
    //$pdf->Rect(3, 101, 205, 20 , 'D'); // INFO TRIBUTARIA			     
    //$pdf->SetY(101);
    //$pdf->SetX(3);
    $pdf->Text(5, 50, utf8_decode('Cliente: ' . $contribuyente)); // Nombre cliente	
    $pdf->Text(76, 49, utf8_decode('RUC / CI: ' . $identificacion)); // Ruc cliente
    $pdf->Text(5, 53, utf8_decode('Fecha de Emisión: ' . $fechaEmision)); //fecha de emision cliente
    $pdf->Text(76, 51, utf8_decode('Guía de Remisión: ' . $num_serie_guia)); //guia remision 
    $pdf->Text(76, 54, utf8_decode('Dirección: ' . $direccion_cliente)); //guia remision 
    $pdf->Text(76, 57, utf8_decode('Teléfono: ' . $telefono_cliente)); //guia remision 
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////7
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //$pdf->Rect(87, 3, 102, 90 , 'D'); //Datos Empresa	 
    //$pdf->SetX(50);
    //$pdf->SetY(20);
    $pdf->SetFont('Amble-Regular', '', 5);
    $pdf->Text(154, 36, $razonSocial); // Razon Social Empresa	
    //$pdf->SetY(56);
    //$pdf->SetX(4);	
    //$pdf->SetY(66);	
    //$pdf->SetX(4);
    $pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
    $pdf->SetFont('Amble-Regular', '', 4);
    $pdf->SetY(43);
    $pdf->SetX(153);
    $pdf->multiCell(55, 2, utf8_decode('Matriz: ' . $direccionEstablecimiento));
//    $pdf->Text(5, 46, 'Matriz: ' . $direccionEstablecimiento); // Direccion Matriz	
    //$pdf->SetY(76);	
    //$pdf->SetX(4);	
//		$pdf->Text(5, 45, 'Sucursal: '.$direccionEstablecimiento);// Direccion Establecimiento	
    $pdf->SetFont('Amble-Regular', '', 5);
    $pdf->Text(154, 42, utf8_decode('Obligado a llevar Contabilidad: ' . $obligado)); // Obligado a llevar contabilidad
    $pdf->Text(154, 55, utf8_decode('Agente de Retención Mediante Resolución Nro. NAC-DNCRASC20-00000001')); //fecha de emision cliente
//    $pdf->Text(5, 57, utf8_decode('Contribuyente Regimen Microempresas')); //obligado
//    $pdf->Text(5, 56, utf8_decode('Contribuyente Régimen Microempresarial')); //fecha de emision cliente
    //$pdf->Rect(3, 101, 205, 20 , 'D'); // INFO TRIBUTARIA			     
    //$pdf->SetY(101);
    //$pdf->SetX(3);
    $pdf->Text(154, 50, utf8_decode('Cliente: ' . $contribuyente)); // Nombre cliente	
    $pdf->Text(224, 49, utf8_decode('RUC / CI: ' . $identificacion)); // Ruc cliente
    $pdf->Text(224, 53, utf8_decode('Fecha de Emisión: ' . $fechaEmision)); //fecha de emision cliente
    $pdf->Text(224, 51, utf8_decode('Guía de Remisión: ' . $num_serie_guia)); //guia remision 
    $pdf->Text(224, 55, utf8_decode('Dirección: ' . $direccion_cliente)); //guia remision 
    $pdf->Text(224, 57, utf8_decode('Teléfono: ' . $telefono_cliente)); //guia remision 
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //
    // detalles factura

    $pdf->SetFont('Amble-Regular', '', 7);
   // $pdf->SetY(60);
    //$pdf->SetX(5);
   // $pdf->multiCell(30, 5, utf8_decode('Cod. Principal'), 1);
    //$pdf->SetY(64);
    //$pdf->SetX(21);
    //$pdf->multiCell(20, 5, utf8_decode('Cod. Auxiliar'),1 );
    $pdf->SetY(60);
    $pdf->SetX(5);
    $pdf->multiCell(10, 5, utf8_decode('Cant.'), 1);
    $pdf->SetY(60);
    $pdf->SetX(15);
    $pdf->multiCell(94, 5, utf8_decode('Descripción'), 1);
    $pdf->SetY(60);
    $pdf->SetX(109);
    $pdf->multiCell(12, 5, utf8_decode('Precio U.'), 1);
    $pdf->SetY(60);
    $pdf->SetX(121);
    $pdf->multiCell(10, 5, utf8_decode('Dcto %'), 1);
    $pdf->SetY(60);
    $pdf->SetX(131);
    $pdf->multiCell(15, 5, utf8_decode('Subtotal'), 1);

    $x = 65;
    $y = 1;

    $resultado = pg_query("select P.codigo,P.cod_barras, P.articulo, D.cantidad, D.precio_venta, D.descuento_producto, F.tarifa12 from factura_venta F,detalle_factura_venta D  , productos P where  d.cod_productos =P.cod_productos   and D.id_factura_venta = F.id_factura_venta  AND   F.id_factura_venta = '" . $id . "' order by D.id_detalle_venta");

    while ($row = pg_fetch_row($resultado)) {
        $codigo = utf8_decode($row[0]);
//			$codigoAuxiliar = utf8_decode($row[1]);
        $codigoAuxiliar = '';
        $descripcion = utf8_decode($row[2]);
        $cantidad = $row[3];
        $tarifa12 = 0;
        $tarifa12 = $row[4];

        $precio = number_format($row[4], 2, '.', '');
        ;
        $descuento = $row[5];
        $tarifa12 = $tarifa12 * $cantidad;
        $Descucaltres = 0;
        $desc = 0;
        $desc = $row[5];
        $valcien = 100;
        $Descucaltres = ($tarifa12 / $valcien) * $desc;
        $tarifa12sin = $tarifa12 - $Descucaltres;
        $total = number_format($tarifa12sin, 2, '.', '');

     //   $pdf->SetY($x);
      //  $pdf->SetX(5);

     //   $pdf->multiCell(30, 3, substr($codigo, 0, 18), 1);

        //$pdf->SetY($x);
        //$pdf->SetX(23);
        //if(strlen($codigoAuxiliar) > 19)
        //	$tam = 5;
        //else
        //	$tam = 10;	
        //$pdf->multiCell(20, $tam, $codigoAuxiliar,1);

        $pdf->SetY($x);
        $pdf->SetX(5);
        if (strlen($cantidad) > 10)
            $tam = 3;
        else
            $tam = 3;
        $pdf->multiCell(10, $tam, $cantidad, 1);

        $pdf->SetY($x);
        $pdf->SetX(15);
        if (strlen($descripcion) > 50)
            $tam = 3;
        else
            $tam = 3;
        $pdf->multiCell(94, $tam, $descripcion, 1);

        $pdf->SetY($x);
        $pdf->SetX(109);
        if (strlen($precio) > 10)
            $tam = 3;
        else
            $tam = 3;
        $pdf->multiCell(12, $tam, $precio, 1);

        $pdf->SetY($x);
        $pdf->SetX(121);
        if (strlen($descuento) > 15)
            $tam = 3;
        else
            $tam = 3;
        $pdf->multiCell(10, $tam, $descuento, 1);

        $pdf->SetY($x);
        $pdf->SetX(131);
        if (strlen($total) > 10)
            $tam = 3;
        else
            $tam = 3;
        $pdf->multiCell(15, $tam, $total, 1);
        $x = $x + 3;
    }

    // pie de pagina           	
    if ($pdf->getY() <= 228) {
        $pdf->Ln(3);
        $pdf->SetX(4);
        $pdf->Rect($pdf->GetX(), $pdf->GetY(), 86, 34, 'D'); ////3 INFO ADICIONAL	   
        $y = $pdf->GetY();
        $x = $pdf->GetX();
        $y1 = $pdf->GetY();
        $x1 = $pdf->GetX();
        $pdf->Text($x + 1, $y + 4, utf8_decode('INFORMACIÓN ADICIONAL')); //informacion 		
        $pdf->SetY($y + 5);
        $pdf->SetX($x);
        $pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $pdf->SetFont('Amble-Regular', '', 7);
        $pdf->multiCell(80, 5, utf8_decode("Dirección: " . $direcion), 0);
        $pdf->SetY($y + 11);
        $pdf->SetX($x);
        $pdf->multiCell(100, 15, utf8_decode("Teléfono:         " . $telefono . "   /    " . $telefono_fijo), 0);
        $pdf->SetY($y + 11);
        $pdf->SetX($x);
        $pdf->multiCell(100, 25, utf8_decode("Email:                " . $email), 0);
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

        $pdf->Ln(1);
        $pdf->SetX(105);
        $x1 = $x1 + 104;
        $pdf->SetY($y1);
        $pdf->SetX($x1);
        $pdf->multiCell(22, 6, utf8_decode("Subtotal 12 %"), 1);
        $pdf->SetY($y1);
        $pdf->SetX($x1 + 22);
        $pdf->multiCell(15, 6, number_format($tarifa, 2, '.', ''), 1);
        $pdf->SetY($y1 + 6);
        $pdf->SetX($x1);
        $pdf->multiCell(22, 6, utf8_decode("Subtotal IVA 0 %"), 1);
        $pdf->SetY($y1 + 6);
        $pdf->SetX($x1 + 22);
        $pdf->multiCell(15, 6, number_format($tarifa0, 2, '.', ''), 1);
        $pdf->SetY($y1 + 12);
        $pdf->SetX($x1);
        $pdf->multiCell(22, 6, utf8_decode("Descuento"), 1);
        $pdf->SetY($y1 + 12);
        $pdf->SetX($x1 + 22);
        $pdf->multiCell(15, 6, number_format($descuento, 2, '.', ''), 1);
        $pdf->SetY($y1 + 18);
        $pdf->SetX($x1);
        $pdf->multiCell(22, 6, utf8_decode("IVA 12 %"), 1);
        $pdf->SetY($y1 + 18);
        $pdf->SetX($x1 + 22);
        $pdf->multiCell(15, 6, number_format($iva, 2, '.', ''), 1);
        $pdf->SetY($y1 + 24);
        $pdf->SetX($x1);
        $pdf->multiCell(22, 6, utf8_decode("PROPINA"), 1);
        $pdf->SetY($y1 + 24);
        $pdf->SetX($x1 + 22);
        $pdf->multiCell(15, 6, utf8_decode("0.00"), 1);
        $pdf->SetY($y1 + 30);
        $pdf->SetX($x1);
        $pdf->multiCell(22, 6, utf8_decode("TOTAL"), 1);
        $pdf->SetY($y1 + 30);
        $pdf->SetX($x1 + 22);
        $pdf->multiCell(15, 6, ($total), 1);

        // FORMAS DE PAGO	           	
        $resultado = pg_query("SELECT P.codigo, P.descripcion FROM factura_venta F, forma_pagos P WHERE F.id_forma_pago = P.id_forma_pago AND F.id_factura_venta = '" . $id . "'");
        while ($row = pg_fetch_row($resultado)) {
            $codigovalor = $row[0];
            $valo0 = $row[1];
        }
        $pdf->Ln(-0);
        $pdf->SetX(5);

        $pdf->Rect($pdf->GetX(), $pdf->GetY(), 86, 10, 'D'); ////3 INFO ADICIONAL	   
        $y = $pdf->GetY();
        $x = $pdf->GetX();
        $y1 = $pdf->GetY();
        $x1 = $pdf->GetX();

        $pdf->Text($x + 1, $y + 3, utf8_decode('FORMA DE PAGO')); //informacion 		
        $pdf->SetY($y + 6);
        $pdf->SetX($x);
        $pdf->Text($x + 1, $y + 8, utf8_decode($codigovalor . '  ' . $valo0)); //informacion 		
        $pdf->SetY($y + 6);
        $pdf->SetX($x);
        $pdf->Text($x + 70, $y + 3, utf8_decode("VALOR:"));
        $pdf->SetY($y + 10);
        $pdf->SetX($x);
        $pdf->Text($x + 70, $y + 8, utf8_decode($total)); //informacion 
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //
    // detalles factura

    $pdf->SetFont('Amble-Regular', '', 7);
   // $pdf->SetY(60);
   // $pdf->SetX(155);
   // $pdf->multiCell(30, 5, utf8_decode('Cod. Principal'), 1);
    //$pdf->SetY(64);
    //$pdf->SetX(21);
    //$pdf->multiCell(20, 5, utf8_decode('Cod. Auxiliar'),1 );
    $pdf->SetY(60);
    $pdf->SetX(152);
    $pdf->multiCell(10, 5, utf8_decode('Cant.'), 1);
    $pdf->SetY(60);
    $pdf->SetX(162);
    $pdf->multiCell(94, 5, utf8_decode('Descripción'), 1);
    $pdf->SetY(60);
    $pdf->SetX(256);
    $pdf->multiCell(12, 5, utf8_decode('Precio U.'), 1);
    $pdf->SetY(60);
    $pdf->SetX(268);
    $pdf->multiCell(10, 5, utf8_decode('Dcto %'), 1);
    $pdf->SetY(60);
    $pdf->SetX(278);
    $pdf->multiCell(15, 5, utf8_decode('Subtotal'), 1);
    $x = 65;
    $y = 1;

    $resultado = pg_query("select P.codigo,P.cod_barras, P.articulo, D.cantidad, D.precio_venta, D.descuento_producto, F.tarifa12 from factura_venta F,detalle_factura_venta D  , productos P where  d.cod_productos =P.cod_productos   and D.id_factura_venta = F.id_factura_venta  AND   F.id_factura_venta = '" . $id . "' order by D.id_detalle_venta");

    while ($row = pg_fetch_row($resultado)) {
        $codigo = utf8_decode($row[0]);
//			$codigoAuxiliar = utf8_decode($row[1]);
        $codigoAuxiliar = '';
        $descripcion = utf8_decode($row[2]);
        $cantidad = $row[3];
        $tarifa12 = 0;
        $tarifa12 = $row[4];

        $precio = number_format($row[4], 2, '.', '');
        ;
        $descuento = $row[5];
        $tarifa12 = $tarifa12 * $cantidad;
        $Descucaltres = 0;
        $desc = 0;
        $desc = $row[5];
        $valcien = 100;
        $Descucaltres = ($tarifa12 / $valcien) * $desc;
        $tarifa12sin = $tarifa12 - $Descucaltres;
        $total = number_format($tarifa12sin, 2, '.', '');

       // $pdf->SetY($x);
      //  $pdf->SetX(155);

      //  $pdf->multiCell(30, 3, substr($codigo, 0, 18), 1);

        //$pdf->SetY($x);
        //$pdf->SetX(23);
        //if(strlen($codigoAuxiliar) > 19)
        //	$tam = 5;
        //else
        //	$tam = 10;	
        //$pdf->multiCell(20, $tam, $codigoAuxiliar,1);

        $pdf->SetY($x);
        $pdf->SetX(152);
        if (strlen($cantidad) > 10)
            $tam = 3;
        else
            $tam = 3;
        $pdf->multiCell(10, $tam, $cantidad, 1);

        $pdf->SetY($x);
        $pdf->SetX(162);
        if (strlen($descripcion) > 50)
            $tam = 3;
        else
            $tam = 3;
        $pdf->multiCell(94, $tam, $descripcion, 1);

        $pdf->SetY($x);
        $pdf->SetX(256);
        if (strlen($precio) > 10)
            $tam = 3;
        else
            $tam = 3;
        $pdf->multiCell(12, $tam, $precio, 1);

        $pdf->SetY($x);
        $pdf->SetX(268);
        if (strlen($descuento) > 15)
            $tam = 3;
        else
            $tam = 3;
        $pdf->multiCell(10, $tam, $descuento, 1);

        $pdf->SetY($x);
        $pdf->SetX(278);
        if (strlen($total) > 10)
            $tam = 3;
        else
            $tam = 3;
        $pdf->multiCell(15, $tam, $total, 1);
        $x = $x + 3;
    }

    // pie de pagina           	
    if ($pdf->getY() <= 228) {
        $pdf->Ln(3);
        $pdf->SetX(152);
        $pdf->Rect($pdf->GetX(), $pdf->GetY(), 86, 34, 'D'); ////3 INFO ADICIONAL	   
        $y = $pdf->GetY();
        $x = $pdf->GetX();
        $y1 = $pdf->GetY();
        $x1 = $pdf->GetX();
        $pdf->Text($x + 1, $y + 4, utf8_decode('INFORMACIÓN ADICIONAL')); //informacion 		
        $pdf->SetY($y + 5);
        $pdf->SetX($x);
        $pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $pdf->SetFont('Amble-Regular', '', 7);
        $pdf->multiCell(80, 5, utf8_decode("Dirección: " . $direcion), 0);
        $pdf->SetY($y + 11);
        $pdf->SetX($x);
        $pdf->multiCell(100, 15, utf8_decode("Teléfono:         " . $telefono . "   /    " . $telefono_fijo), 0);
        $pdf->SetY($y + 11);
        $pdf->SetX($x);
        $pdf->multiCell(100, 25, utf8_decode("Email:                " . $email), 0);
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

        $pdf->Ln(1);
        $pdf->SetX(105);
        $x1 = $x1 + 104;
        $pdf->SetY($y1);
        $pdf->SetX($x1);
        $pdf->multiCell(22, 6, utf8_decode("Subtotal 12 %"), 1);
        $pdf->SetY($y1);
        $pdf->SetX($x1 + 22);
        $pdf->multiCell(15, 6, number_format($tarifa, 2, '.', ''), 1);
        $pdf->SetY($y1 + 6);
        $pdf->SetX($x1);
        $pdf->multiCell(22, 6, utf8_decode("Subtotal IVA 0 %"), 1);
        $pdf->SetY($y1 + 6);
        $pdf->SetX($x1 + 22);
        $pdf->multiCell(15, 6, number_format($tarifa0, 2, '.', ''), 1);
        $pdf->SetY($y1 + 12);
        $pdf->SetX($x1);
        $pdf->multiCell(22, 6, utf8_decode("Descuento"), 1);
        $pdf->SetY($y1 + 12);
        $pdf->SetX($x1 + 22);
        $pdf->multiCell(15, 6, number_format($descuento, 2, '.', ''), 1);
        $pdf->SetY($y1 + 18);
        $pdf->SetX($x1);
        $pdf->multiCell(22, 6, utf8_decode("IVA 12 %"), 1);
        $pdf->SetY($y1 + 18);
        $pdf->SetX($x1 + 22);
        $pdf->multiCell(15, 6, number_format($iva, 2, '.', ''), 1);
        $pdf->SetY($y1 + 24);
        $pdf->SetX($x1);
        $pdf->multiCell(22, 6, utf8_decode("PROPINA"), 1);
        $pdf->SetY($y1 + 24);
        $pdf->SetX($x1 + 22);
        $pdf->multiCell(15, 6, utf8_decode("0.00"), 1);
        $pdf->SetY($y1 + 30);
        $pdf->SetX($x1);
        $pdf->multiCell(22, 6, utf8_decode("TOTAL"), 1);
        $pdf->SetY($y1 + 30);
        $pdf->SetX($x1 + 22);
        $pdf->multiCell(15, 6, ($total), 1);

        // FORMAS DE PAGO	           	
        $resultado = pg_query("SELECT P.codigo, P.descripcion FROM factura_venta F, forma_pagos P WHERE F.id_forma_pago = P.id_forma_pago AND F.id_factura_venta = '" . $id . "'");
        while ($row = pg_fetch_row($resultado)) {
            $codigovalor = $row[0];
            $valo0 = $row[1];
        }
        $pdf->Ln(-0);
        $pdf->SetX(154);

        $pdf->Rect($pdf->GetX(), $pdf->GetY(), 86, 10, 'D'); ////3 INFO ADICIONAL	   
        $y = $pdf->GetY();
        $x = $pdf->GetX();
        $y1 = $pdf->GetY();
        $x1 = $pdf->GetX();

        $pdf->Text($x + 1, $y + 3, utf8_decode('FORMA DE PAGO')); //informacion 		
        $pdf->SetY($y + 6);
        $pdf->SetX($x);
        $pdf->Text($x + 1, $y + 8, utf8_decode($codigovalor . '  ' . $valo0)); //informacion 		
        $pdf->SetY($y + 6);
        $pdf->SetX($x);
        $pdf->Text($x + 70, $y + 3, utf8_decode("VALOR:"));
        $pdf->SetY($y + 10);
        $pdf->SetX($x);
        $pdf->Text($x + 70, $y + 8, utf8_decode($total)); //informacion 
    }
//		$pdf->SetY(190);
//        $pdf->SetX(3);
//        $pdf->Cell(60, 5, "__________________________________________",0,0, 'C',0);    
//        $pdf->Cell(82, 5, "__________________________________________",0,1, 'C',0);    
//        $pdf->SetX(3);
//        $pdf->Cell(60, 5, "ENTREGE CONFORME",0,0, 'C',0);    
//        $pdf->Cell(82, 5, "RECIBI CONFORME",0,1, 'C',0);
    if (isset($_GET['id'])) {
        $pdf->Output();
    } else {
        $pdf_file_contents = $pdf->Output("", "S");
        return $pdf_file_contents;
    }
    // $pdf->Output();		
}
