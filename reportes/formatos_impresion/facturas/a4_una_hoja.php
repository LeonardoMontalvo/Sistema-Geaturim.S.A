<?php

//        include '../fpdf/rotation.php';        
//        include("../fpdf/barcode.inc.php");
//        include '../procesos/base.php';
include __DIR__ . '/../../../fpdf/rotation.php';
include(__DIR__ . "/../../../fpdf/barcode.inc.php");
require_once(__DIR__ . '/../../../procesos/base.php');
require_once __DIR__ . "/../../../procesos/configuracion.php";

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
    function GetMultiCellHeight($w, $h, $txt, $border = null, $align = 'J') {
        // Calculate MultiCell with automatic or explicit line breaks height
        // $border is un-used, but I kept it in the parameters to keep the call
        //   to this function consistent with MultiCell()
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $ns = 0;
        $height = 0;
        while ($i < $nb) {
            // Get next character
            $c = $s[$i];
            if ($c == "\n") {
                // Explicit line break
                if ($this->ws > 0) {
                    $this->ws = 0;
                    $this->_out('0 Tw');
                }
                //Increase Height
                $height += $h;
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $ns = 0;
                continue;
            }
            if ($c == ' ') {
                $sep = $i;
                $ls = $l;
                $ns++;
            }
            $l += $cw[$c];
            if ($l > $wmax) {
                // Automatic line break
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                    if ($this->ws > 0) {
                        $this->ws = 0;
                        $this->_out('0 Tw');
                    }
                    //Increase Height
                    $height += $h;
                } else {
                    if ($align == 'J') {
                        $this->ws = ($ns > 1) ? ($wmax - $ls) / 1000 * $this->FontSize / ($ns - 1) : 0;
                        $this->_out(sprintf('%.3F Tw', $this->ws * $this->k));
                    }
                    //Increase Height
                    $height += $h;
                    $i = $sep + 1;
                }
                $sep = -1;
                $j = $i;
                $l = 0;
                $ns = 0;
            } else
                $i++;
        }
        // Last chunk
        if ($this->ws > 0) {
            $this->ws = 0;
            $this->_out('0 Tw');
        }
        //Increase Height
        $height += $h;

        return $height;
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

 
    $consulta = pg_query("SELECT nombre_empresa, ruc_empresa, direccion_empresa, telefono_empresa, celular_empresa,
        email_empresa, nombre_comercial, obligacion, contribuyente_espe, establecimiento, punto_emision,
        fecha_actual as fecha_emision, num_autorizacion, fecha_autorizacion, num_factura, num_serie, 
        fv.clave, serie_guia_remision, marca_vehiculo, identificacion, nombres_cli, direccion_cli, 
        case when telefono!='' then telefono else celular end as telefono_cli,propietario
        from empresa e left join factura_venta fv using(id_empresa) 
        left join clientes c using(id_cliente) 
        left join tipo_documento td using(id_tdocu) 
        where fv.id_factura_venta='" . $id . "' ");
    
    while ($row = pg_fetch_assoc($consulta)) {
         $razonSocial = $row['nombre_empresa'];
        $ruc = $row['ruc_empresa'];
        $direcion = $row['direccion_empresa'];
        $direccionEstablecimiento = $row['direccion_empresa'];
        $telefono_fijo = $row['telefono_empresa'];
        $telefono = $row['celular_empresa'];
        $email = $row['email_empresa'];
         $nombreComercial = $row['nombre_comercial'];
        $obligado = $row['obligacion'];
        // $nroContribuyente = $row['contribuyente_espe'];
        $establecimiento = $row['establecimiento'];
        $puntoEmision = $row['punto_emision'];
        $fechaEmision = $row['fecha_emision'];
        $date = new DateTime($fechaEmision);
        $fechaEmision = $date->format('d/m/Y');
        $numeroAutorizacion = $row['num_autorizacion'];
        if ($numeroAutorizacion == "" || $numeroAutorizacion == "undefined") {
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
        $direccion_cliente = $row['direccion_cli'];
        $telefono_cliente = $row['telefono_cli'];
        // $codigo = $row['codigo_tdocu'];

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

//			$imagen = $row[13];
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

    $pdf = new PDF('P', 'mm', 'A5');
    $pdf->AddPage();
    $pdf->SetMargins(10, 0, 0, 0);
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak(true, 10);
    $pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
    $pdf->SetFont('Amble-Regular', '', 6);

    //		$logo = $imagen;
   $pdf->Image('../../../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 5, 7, 30); // Img Empresa
    //$pdf->Rect(1, 3, 81, 20 ,1, 'D');
    //		$pdf->Image('C:\xampp\htdocs\syswebfe\images\logo.png',5,10,60); // Img Empresa 
    // $pdf->Image('C:\xampp\htdocs\sysweb\images\logo.png',10,7,50);
    //$pdf->Rect(1, 24, 81, 53 , 'D'); // 2 datos personales
    $pdf->Text(5, 39, 'RUC:' . $ruc); // ruc		 	
    $pdf->Text(70, 11, utf8_decode("FACTURA")); // Tipo comprobante
    //$pdf->Text(5, 33, utf8_decode("SERVIAGRO"));
    $pdf->Text(70, 14, 'No. ' . $establecimiento . '-' . $puntoEmision . '-' . $secuencial); // Secuencial
    $pdf->Text(70, 17, utf8_decode('NÚMERO DE AUTORIZACIÓN')); // N° Autorizacion
    //$pdf->SetY(40);
    //$pdf->SetX(107);	
    $pdf->Text(70, 20, $claveAcceso); // N° Autorización		
    $pdf->Text(70, 23, utf8_decode('FECHA Y HORA DE AUTORIZACIÓN')); // fecha y hora de autorizacion
    $pdf->Text(70, 26, $fechaAut); //FECHA
    $pdf->Text(70, 29, utf8_decode('AMBIENTE: ' . $ambiente)); // Ambiente
    $pdf->Text(100, 29, utf8_decode('EMISIÓN: ' . $emision)); // Tipo de emision
    //		$pdf->Text(91, 5, utf8_decode('CLAVE DE ACCESO: ')); // Clave de acceso
    $code_number = $claveAcceso; // Código de barras		
    new barCodeGenrator($code_number, 1, 'temp.gif', 470, 60, true); /// img codigo barras	
    $pdf->Image('temp.gif', 68, 31, 70, 15);
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////duplicacion/////7///////////////////////////////11111///////////////////////////////////////////////////////////////
    $pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
    $pdf->SetFont('Amble-Regular', '', 6);


    /////////////////////////////////////////////////////////////////1111//////////////////////////////////////////////////////////////
    //$pdf->Rect(87, 3, 102, 90 , 'D'); //Datos Empresa	 
    //$pdf->SetX(50);
    //$pdf->SetY(20);
    $pdf->SetFont('Amble-Regular', '', 5);
//      $pdf->Text(5, 30, $razonSocial); // Razon Social Empresa
    $pdf->Text(5, 36, $razonSocial); // Razon Social Empresa	
    //$pdf->SetY(56);
    //$pdf->SetX(4);	
    //$pdf->SetY(66);	
    //$pdf->SetX(4);
    $pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
    $pdf->SetFont('Amble-Regular', '', 4);
    $pdf->SetY(43);
    $pdf->SetX(4);
    //$pdf->multiCell(55, 2, utf8_decode('Matriz: ' . $direccionEstablecimiento));
    //    $pdf->Text(5, 46, 'Matriz: ' . $direccionEstablecimiento); // Direccion Matriz	
    //$pdf->SetY(76);	
    //$pdf->SetX(4);	
    //		$pdf->Text(5, 45, 'Sucursal: '.$direccionEstablecimiento);// Direccion Establecimiento	
    $pdf->SetFont('Amble-Regular', '', 5);
    $pdf->Text(5, 42, utf8_decode('Obligado a llevar Contabilidad: ' . $obligado)); // Obligado a llevar contabilidad
    $conf = new Configuracion();
    $agente_reten = $conf->getParametroEmpresa("check_agente_reten");
    $val_rimpe = $conf->getParametroEmpresa("val_rimpe");
    if ($agente_reten != "") {
       $pdf->Text(5, 45, utf8_decode('Agente de Retención Mediante Resolución Nro. NAC-DNCRASC20-00000001'));
    }
    if ($val_rimpe != "") {
        $pdf->Text(5, 48, utf8_decode($val_rimpe));
    }
    //$pdf->Text(5, 45, utf8_decode('Contribuyente Rimpe Emprendedor con Calificación Artesanal Nro. 159583 ' ));
    //    $pdf->Text(5, 55, utf8_decode('Agente de Retención Mediante Resolución Nro. NAC-DNCRASC20-00000001')); //fecha de emision cliente
    //    $pdf->Text(5, 57, utf8_decode('Contribuyente Regimen Microempresas')); //obligado
    //    $pdf->Text(5, 56, utf8_decode('Contribuyente Régimen Microempresarial')); //fecha de emision cliente
    //$pdf->Rect(3, 101, 205, 20 , 'D'); // INFO TRIBUTARIA			     
    //$pdf->SetY(101);
    //$pdf->SetX(3);
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////7
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //$pdf->Rect(87, 3, 102, 90 , 'D'); //Datos Empresa	 
    //$pdf->SetX(50);
    //$pdf->SetY(20);

    $pdf->Text(5, 33, utf8_decode($nombreComercial));
    $pdf->Text(5, 52, utf8_decode('Cliente: ' . $contribuyente)); // Nombre cliente	
    $pdf->Text(5, 55, utf8_decode('RUC / CI: ' . $identificacion)); // Ruc cliente
    $pdf->Text(5, 58, utf8_decode('Fecha de Emisión: ' . $fechaEmision)); //fecha de emision cliente
   // $pdf->Text(70, 52, utf8_decode('Guía de Remisión: ' . $num_serie_guia)); //guia remision 
    $pdf->Text(70, 55, utf8_decode('Dirección: ' . $direccion_cliente)); //guia remision 
    $pdf->Text(70, 58, utf8_decode('Teléfono: ' . $telefono_cliente)); //guia remision 

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

    $resultado = pg_query("select P.codigo,P.cod_barras, P.articulo, D.cantidad, D.precio_venta, D.descuento_producto, F.tarifa12,D.detalle_producto
    from factura_venta F,detalle_factura_venta D  , productos P where  d.cod_productos =P.cod_productos   
    and D.id_factura_venta = F.id_factura_venta  
    AND   F.id_factura_venta = '" . $id . "' order by D.id_detalle_venta");

    while ($row = pg_fetch_row($resultado)) {
        $codigo = utf8_decode($row[0]);
        //			$codigoAuxiliar = utf8_decode($row[1]);
        $codigoAuxiliar = '';
        $descripcion = utf8_decode($row[2] . " " . $marca_delvehiculo);
        if (!empty($row[7])) {
            $descripcion .= " -- " . substr(utf8_decode($row[7]), 0, 55);
        }

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

       

        $multicellarr = [
            ['w' => 80, 'h' => 4, 'text' => utf8_decode($descripcion)]
        ];
        $maxheight = obtenerMaxHight($multicellarr, $pdf);

        $pdf->SetX(5);
        $pdf->Cell(10, $maxheight, maxCaracter1(utf8_decode($cantidad), 10), 1, 0, 'L', 0);

        //$pdf->Cell(60, 7, maxCaracter(utf8_decode($row['nombre_propietario']), 34), 1, 0, 'L', 0);
        multiCellRow(94, 4, ($descripcion), $maxheight, $pdf);
        //$pdf->Cell(60, 7, maxCaracter(utf8_decode($row['nombre_empresa']), 35), 1, 0, 'L', 0);
       
        $pdf->Cell(12, $maxheight, maxCaracter1(utf8_decode($precio), 10), 1, 0, 'L', 0);
          $pdf->Cell(10, $maxheight, maxCaracter1(utf8_decode($descuento), 10), 1, 0, 'L', 0);
        $pdf->Cell(15, $maxheight, maxCaracter1(utf8_decode($total), 10), 1, 0, 'L', 0);

        $pdf->Ln($maxheight);
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
        $pdf->Text($x + 1, $y + 4, utf8_decode('INFORMACIÓN ADICIONAL:')); //informacion 		
        $pdf->SetY($y + 5);
        $pdf->SetX($x);
        $pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $pdf->SetFont('Amble-Regular', '', 7);
        $pdf->multiCell(120, 15, utf8_decode("Dirección:        " . $direcion), 0);
        $pdf->SetY($y + 7);
        $pdf->SetX($x);
        $pdf->multiCell(100, 20, utf8_decode("Teléfono:         " . $telefono . "   /    " . $telefono_fijo), 0);
        $pdf->SetY($y + 9);
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
        $pdf->multiCell(15, 6, number_format($tarifa, 2, '.', ''), 1, 'R', 0);
        $pdf->SetY($y1 + 6);
        $pdf->SetX($x1);
        $pdf->multiCell(22, 6, utf8_decode("Subtotal IVA 0 %"), 1);
        $pdf->SetY($y1 + 6);
        $pdf->SetX($x1 + 22);
        $pdf->multiCell(15, 6, number_format($tarifa0, 2, '.', ''), 1, 'R', 0);
        $pdf->SetY($y1 + 12);
        $pdf->SetX($x1);
        $pdf->multiCell(22, 6, utf8_decode("Descuento"), 1);
        $pdf->SetY($y1 + 12);
        $pdf->SetX($x1 + 22);
        $pdf->multiCell(15, 6, number_format($descuento, 2, '.', ''), 1, 'R', 0);
        $pdf->SetY($y1 + 18);
        $pdf->SetX($x1);
        $pdf->multiCell(22, 6, utf8_decode("IVA 12 %"), 1);
        $pdf->SetY($y1 + 18);
        $pdf->SetX($x1 + 22);
        $pdf->multiCell(15, 6, number_format($iva, 2, '.', ''), 1, 'R', 0);
        $pdf->SetY($y1 + 24);
        $pdf->SetX($x1);
        $pdf->multiCell(22, 6, utf8_decode("PROPINA"), 1);
        $pdf->SetY($y1 + 24);
        $pdf->SetX($x1 + 22);
        $pdf->multiCell(15, 6, utf8_decode("0.00"), 1, 'R', 0);
        $pdf->SetY($y1 + 30);
        $pdf->SetX($x1);
        $pdf->multiCell(22, 6, utf8_decode("TOTAL"), 1);
        $pdf->SetY($y1 + 30);
        $pdf->SetX($x1 + 22);
        $pdf->multiCell(15, 6, ($total), 1, 'R', 0);

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


    $resultado = pg_query("select P.codigo,P.cod_barras, P.articulo, D.cantidad, D.precio_venta, D.descuento_producto, F.tarifa12 from factura_venta F,detalle_factura_venta D  , productos P where  d.cod_productos =P.cod_productos   and D.id_factura_venta = F.id_factura_venta  AND   F.id_factura_venta = '" . $id . "' order by D.id_detalle_venta");



    // pie de pagina           	
    if ($pdf->getY() <= 228) {
        $pdf->Ln(3);
        $pdf->SetX(152);


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


        // FORMAS DE PAGO	           	
        $resultado = pg_query("SELECT P.codigo, P.descripcion FROM factura_venta F, forma_pagos P WHERE F.id_forma_pago = P.id_forma_pago AND F.id_factura_venta = '" . $id . "'");
        while ($row = pg_fetch_row($resultado)) {
            $codigovalor = $row[0];
            $valo0 = $row[1];
        }
        $pdf->Ln(-0);
        $pdf->SetX(154);

        //    
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
    
function obtenerMaxHight($multicells, &$pdf) {
    $aux = 0;
    foreach ($multicells as $key => $value) {
        if ($pdf->GetMultiCellHeight($value['w'], $value['h'], $value['text']) > $aux) {
            $aux = $pdf->GetMultiCellHeight($value['w'], $value['h'], $value['text']);
        }
    }
    return $aux;
}

function rellenarCeldasMulticell($w, $h, $text, $maxh, &$pdf) {
    $aux = $pdf->GetMultiCellHeight($w, $h, $text);
    $nls = "";
    if ($maxh > $aux) {
        $res = $maxh - $aux;
        $res = $res / $h;
        for ($i = 1; $i <= $res; $i++) {
            if ($i == 1) {
                $nls .= "\n\n";
            } else {
                $nls .= "\n";
            }
        }
    }
    return $nls;
}

function multiCellRow($w, $h, $text, $maxh, &$pdf) {
    $currentX = $pdf->GetX();
    $pdf->MultiCell($w, $h, $text . rellenarCeldasMulticell($w, $h, $text, $maxh, $pdf), 1, 'L');
    $currentY = $pdf->GetY();
    $pdf->SetXY($currentX + $w, $currentY - $maxh);
}

function maxCaracter1($texto, $cant) {
    $texto = substr($texto, 0, $cant);
    return $texto;
}

