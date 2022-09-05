<?php

include '../../fpdf/rotation.php';
include("../../fpdf/barcode.inc.php");
require_once('../../procesos/base.php');
require_once( '../../procesos/funciones.php');

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

        $this->Ln(7);
        $this->SetX(13);

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
    generarPDF($id);
}

function generarPDF($id) {
    conectarse();
    ///////////////////FACTURA VENTA//////////////////////////
    $consultafv = pg_query("select * from empresa left join factura_venta on empresa.id_empresa  = factura_venta.id_empresa left join clientes on factura_venta.id_cliente=clientes.id_cliente left join tipo_documento on tipo_documento.id_tdocu=clientes.id_tdocu where factura_venta.id_factura_venta=" . $id . " ");
    while ($row = pg_fetch_row($consultafv)) {
        $rucfv = $row[2];
        $numeroAutorizacionfv = $row[35];
        if ($numeroAutorizacionfv == "" || $numeroAutorizacionfv == "undefined") {
            $numeroAutorizacionfv = $row[54];
        } else {
            $numeroAutorizacionfv = $row[35];
        }
        $fechaEmisionfv = $row[30];
        $datefv = new DateTime($fechaEmisionfv);
        $fechaEmisionfv = $datefv->format('d/m/Y');
        $claveAccesofv = $row[54];
        $razonSocialfv = $row[12];
        $nombreComercialfv = $row[16];
        $direcionMatrizfv = $row[7];
        $direccionEstablecimientofv = $row[3];
        $nroContribuyentefv = $row[19];
        $obligadofv = $row[18];
        $contribuyentefv = $row[66];
        $identificacionfv = $row[65];
        $direcionfv = maxCaracter($row[3], 40);
        $telefono_fijofv = $row[4];
        $telefonofv = $row[5];
        $emailfv = $row[9];
        $secuencialfv = "$row[50]" . "-" . "$row[29]";
        $ipfv = $secuencialfv;
        $iparrfv = split("\-", $ipfv);
        $secuencialfv = $iparrfv[2];
        $establecimientofv = $row[22];
        $puntoEmisionfv = $row[23];
        $fechaAutfv = $row[36];
        $codigofv = $row[76];
        $marca_delvehiculofv = $row[58];
        $placanumfv = $row[59];
        $propiedadfv = $row[60];
        $num_reclamofv = $row[61];
        $num_chasisfv = $row[62];
        $direccion_clientefv = maxCaracter($row[68],50);
        $telefono_clientefv = $row[69];
        $num_serie_guiafv = $row[57];
        if ($num_serie_guiafv != "000000000") {
            $num_serie_guiafv = $row[57];
        } else {
            $num_serie_guiafv = "";
        }
        $consulta_ambiente = pg_query("select nombre_ambi from ambiente where id_ambi=2  ");
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
    ////////////////////////////////////////////////GUIA DE REMISION////////////////////////////////////77
    ////////////////////////////////////////////////GUIA DE REMISION////////////////////////////////////77
    ////////////////////////////////////////////////GUIA DE REMISION////////////////////////////////////77
    ////////////////////////////////////////////////GUIA DE REMISION////////////////////////////////////77
    ////////////////////////////////////////////////GUIA DE REMISION////////////////////////////////////77
    $consulta = pg_query("select * from empresa left join factura_venta on empresa.id_empresa  = factura_venta.id_empresa left join clientes on factura_venta.id_cliente=clientes.id_cliente left join tipo_documento on tipo_documento.id_tdocu=clientes.id_tdocu    left join guia_remision on guia_remision.id_factura_venta=factura_venta.id_factura_venta left join transportista on transportista.id_transportista=guia_remision.id_transportista   where factura_venta.id_factura_venta='" . $id . "' ");
    while ($row = pg_fetch_row($consulta)) {
        $ruc = $row[2];
        $numeroAutorizacion = $row[35];
        if ($numeroAutorizacion == "" || $numeroAutorizacion == "undefined") {
            $numeroAutorizacion = $row[54];
        } else {
            $numeroAutorizacion = $row[35];
        }
        $fechaEmision = $row[30];
        $date = new DateTime($fechaEmision);
        $fechaEmision = $date->format('d/m/Y');
        $claveAcceso = $row[57];
        $razonSocial = $row[1];
        $nombreComercial = $row[16];
        $direcionMatriz = $row[7];
        $direccionEstablecimiento = $row[3];
        $nroContribuyente = $row[19];
        $obligado = $row[18];
        $contribuyente = $row[62];
        $identificacion = $row[61];
        $direcion = $row[64];
        $telefono = $row[66];
        $email = $row[69];
        $secuencial = "$row[50]" . "-" . "$row[29]";
        $ip = $secuencial;
        $iparr = split("\-", $ip);
        $establecimiento = $row[22];
        $puntoEmision = $row[23];
        $fechaAut = $row[36];
        $codigo = $row[79];
        $num_serie_gua = $row[57];
        $num_autorizacion_guia = $row[93];
        if ($num_autorizacion_guia == "" || $num_autorizacion_guia == "undefined") {
            $num_autorizacion_guia = $row[92];
        } else {
            $num_autorizacion_guia = $row[93];
        }
        $fecha_autori_guia = $row[88];
        $claveAcceso_guia = $row[92];
        $ruc_transportista = $row[105];
        $nombre_transportista = $row[106];
        $placa = $row[110];
        $fecha_inicio = $row[86];

        $date = new DateTime($fecha_inicio);
        $fecha_inicio = $date->format('d/m/Y');
        $fecha_finsplit = explode("/", $fecha_inicio);
        $fecha_fin = $fecha_finsplit[0] + 1;
        $fecha_fin1 = $fecha_finsplit[1];
        $fecha_fin2 = $fecha_finsplit[2];
        $fecha_fin = $fecha_fin . '/' . $fecha_fin1 . '/' . $fecha_fin2;

        $lugar_destino = $row[68];
        $identificacion_destinatario = $row[65];
        $razon_destinatario = $row[66];
        $id_factura_venta = $row[24];
        $consulta_ambiente = pg_query("select nombre_ambi from ambiente WHERE id_ambi='2'  ");
        while ($row = pg_fetch_row($consulta_ambiente)) {
            $nombre_ambi = $row[0];
        }
        $ambiente = $nombre_ambi;
        $consulta_emision = pg_query("select nombre_temision from tipo_emision WHERE id_temision='1' ");
        while ($row = pg_fetch_row($consulta_emision)) {
            $nombre_emi = $row[0];
        }
        $emision = $nombre_emi;
    }

    $consulta_ambiente = pg_query("select nombre_ambi from ambiente WHERE id_ambi='2'  ");
    while ($row = pg_fetch_row($consulta_ambiente)) {
        $ambiente = $row[0];
    }
    $consulta_emision = pg_query("select nombre_temision from tipo_emision WHERE id_temision='1' ");
    while ($row = pg_fetch_row($consulta_emision)) {
        $emision = $row[0];
    }

    $pdf = new PDF('P', 'mm', 'a4');
    $pdf->AddPage();
    $pdf->SetMargins(10, 0, 0, 0);
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak(true, 10);
    $pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
    $pdf->SetFont('Amble-Regular', '', 9);


    ////////////////////////FACTURA VENTAS///////////////////////////////////////
    ////////////////////////FACTURA VENTAS///////////////////////////////////////
    ////////////////////////FACTURA VENTAS///////////////////////////////////////
    ////////////////////////FACTURA VENTAS///////////////////////////////////////


    $pdf->Text(5, 20, 'RUC:' . $rucfv); // ruc		 	
    $pdf->Text(108, 11, utf8_decode("FACTURA")); // Tipo comprobante
    $pdf->Text(125, 11, 'No. ' . $establecimientofv . '-' . $puntoEmisionfv . '-' . $secuencialfv); // Secuencial
    $pdf->Text(108, 15, utf8_decode('NÚMERO DE AUTORIZACIÓN')); // N° Autorizacion  
    $pdf->Text(108, 18, $numeroAutorizacionfv); // N° Autorización		
    $pdf->Text(108, 22, utf8_decode('FECHA Y HORA DE AUTORIZACIÓN')); // fecha y hora de autorizacion
    $pdf->Text(160, 22, $fechaAutfv); //FECHA
//    $pdf->Text(5, 25, utf8_decode('Teléfono: ' . $ambiente)); // Ambiente
//    $pdf->Text(150, 29, utf8_decode('EMISIÓN: ' . $emision)); // Tipo de emision

    $code_number = $claveAccesofv; // Código de barras		
    new barCodeGenrator($code_number, 1, 'temp.gif', 470, 60, true); /// img codigo barras	
    $pdf->Image('temp.gif', 107, 24, 80, 15);
    $var_datos = -5;

    $pdf->SetFont('Amble-Regular', '', 15);
    $pdf->Text(10, 20 + $var_datos, utf8_decode($razonSocial));

    $pdf->SetFont('Amble-Regular', '', 9);
    $pdf->Text(5, 43 + $var_datos, utf8_decode('Cliente: ' . $contribuyentefv)); // Nombre cliente	
    $pdf->Text(5, 40 + $var_datos, utf8_decode('RUC / CI: ' . $identificacionfv)); // Ruc cliente
    $pdf->Text(108, 50 + $var_datos, utf8_decode('Fecha de Emisión: ' . $fechaEmisionfv)); //fecha de emision cliente
    $pdf->Text(108, 46 + $var_datos, utf8_decode('Guía de Remisión: ' . $num_serie_guiafv)); //guia remision 
    $pdf->Text(5, 46 + $var_datos, utf8_decode('Dirección: ' . $direccion_clientefv)); //guia remision 
    $pdf->Text(5, 49 + $var_datos, utf8_decode('Teléfono: ' . $telefono_clientefv)); //guia remision 


    $pdf->SetY(47);
    $pdf->SetX(5);
    $pdf->multiCell(10, 5, utf8_decode('Cant.'), 1);
    $pdf->SetY(47);
    $pdf->SetX(15);
    $pdf->multiCell(130, 5, utf8_decode('Descripción'), 1);
    $pdf->SetY(47);
    $pdf->SetX(145);
    $pdf->multiCell(20, 5, utf8_decode('Precio U.'), 1);
    $pdf->SetY(47);
    $pdf->SetX(165);
    $pdf->multiCell(15, 5, utf8_decode('Dcto %'), 1);
    $pdf->SetY(47);
    $pdf->SetX(180);
    $pdf->multiCell(15, 5, utf8_decode('Subtotal'), 1);

    $x = 52;
    $y = 1;

    $resultado = pg_query("select P.codigo,P.cod_barras, P.articulo, D.cantidad, D.precio_venta, D.descuento_producto, F.tarifa12 from factura_venta F,detalle_factura_venta D  , productos P where  d.cod_productos =P.cod_productos   and D.id_factura_venta = F.id_factura_venta  AND   F.id_factura_venta = '" . $id . "' order by D.id_detalle_venta");

    while ($row = pg_fetch_row($resultado)) {
        $codigo = utf8_decode($row[0]);

        $codigoAuxiliar = '';
//        $descripcion = utf8_decode($row[2] . " " . $marca_delvehiculofv);
         $descripcion = utf8_decode(maxCaracter($row[2] . " " . $marca_delvehiculofv,75));
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


        $pdf->SetY($x);
        $pdf->SetX(2);
        if (strlen($cantidad) > 10)
            $tam = 3;
        else
            $tam = 3;
        $pdf->multiCell(11, $tam, $cantidad, 0, 'R', 0);

        $pdf->SetY($x);
        $pdf->SetX(15);
        if (strlen($descripcion) > 50)
            $tam = 3;
        else
            $tam = 3;
        $pdf->multiCell(130, $tam, $descripcion, 0);

        $pdf->SetY($x);
        $pdf->SetX(145);
        if (strlen($precio) > 10)
            $tam = 3;
        else
            $tam = 3;
        $pdf->multiCell(20, $tam, $precio, 0, 'R', 0);

        $pdf->SetY($x);
        $pdf->SetX(165);
        if (strlen($descuento) > 15)
            $tam = 3;
        else
            $tam = 3;
        $pdf->multiCell(15, $tam, $descuento, 0, 'R', 0);

        $pdf->SetY($x);
        $pdf->SetX(180);
        if (strlen($total) > 10)
            $tam = 3;
        else
            $tam = 3;
        $pdf->multiCell(15, $tam, $total, 0, 'R', 0);
        $x = $x + 3;
    }
    $var_escala = 140; //INFORMACION ADICIONAL
    // pie de pagina 
    $resultado = pg_query("SELECT F.tarifa12, F.tarifa0, F.tarifa0, F.iva_venta, F.descuento_venta, F.total_venta FROM factura_venta f WHERE id_factura_venta = '" . $id . "'");
    while ($row = pg_fetch_row($resultado)) {
        $subtotal = $row[0];
        $tarifa = $row[0];
        $tarifa0 = $row[2];
        $iva = $row[3];
        $descuento = $row[4];

        $total = number_format($row[5], 2, ',', '.');
    }
    // FORMAS DE PAGO	           	
    $resultado = pg_query("SELECT P.codigo, P.descripcion FROM factura_venta F, forma_pagos P WHERE F.id_forma_pago = P.id_forma_pago AND F.id_factura_venta = '" . $id . "'");
    while ($row = pg_fetch_row($resultado)) {
        $codigovalor = $row[0];
        $valo0 = $row[1];
    }
    $y = 80;
    $x = 20;
    $pdf->SetY($y + 5);
    $pdf->SetX($x);
    $pdf->Text($x + 7, $var_escala + 13, utf8_decode('INFORMACIÓN ADICIONAL:')); //informacion 		
    $pdf->SetY($y + 5);
    $pdf->SetX($x);
    $pdf->multiCell(100, 5 + $var_escala, utf8_decode("Dirección:     " . $direcionfv), 0);
    $pdf->SetY($y + 7);
    $pdf->SetX($x);
    $pdf->multiCell(100, 10 + $var_escala, utf8_decode("Teléfono:       " . $telefonofv . "   /    " . $telefono_fijofv), 0);
    $pdf->SetY($y + 9);
    $pdf->SetX($x);
    $pdf->multiCell(100, 15 + $var_escala, utf8_decode("Email:             " . $emailfv), 0);
    $pdf->SetY($y + 9);
    $pdf->SetX($x);
    $pdf->multiCell(100, 25 + $var_escala, utf8_decode("FORMA DE PAGO:             "), 0);
    $pdf->SetY($y + 9);
    $pdf->SetX($x + 80);
    $pdf->multiCell(100, 25 + $var_escala, utf8_decode("VALOR:             "), 0);
    $pdf->SetY($y + 9);
    $pdf->SetX($x);
    $pdf->multiCell(100, 35 + $var_escala, utf8_decode($codigovalor . '     ' . $valo0 . '          ' . $total), 0);

    $var_escala2 = 10;

    $x1 = 152;
    $y1 = 135;
    $pdf->SetY($y1 + $var_escala2);
    $pdf->SetX($x1);
    $pdf->multiCell(25, 6, utf8_decode("Subtotal 12 %"), 1);
    $pdf->SetY($y1 + $var_escala2);
    $pdf->SetX($x1 + 25);
    $pdf->multiCell(15, 6, number_format($tarifa, 2, '.', ''), 1, 'R', 0);
    $pdf->SetY($y1 + 6 + $var_escala2);
    $pdf->SetX($x1);
    $pdf->multiCell(25, 6, utf8_decode("Subtotal 0 %"), 1);
    $pdf->SetY($y1 + 6 + $var_escala2);
    $pdf->SetX($x1 + 25);
    $pdf->multiCell(15, 6, number_format($tarifa0, 2, '.', ''), 1, 'R', 0);
    $pdf->SetY($y1 + 12 + $var_escala2);
    $pdf->SetX($x1);
    $pdf->multiCell(25, 6, utf8_decode("Descuento"), 1);
    $pdf->SetY($y1 + 12 + $var_escala2);
    $pdf->SetX($x1 + 25);
    $pdf->multiCell(15, 6, number_format($descuento, 2, '.', ''), 1, 'R', 0);
    $pdf->SetY($y1 + 18 + $var_escala2);
    $pdf->SetX($x1);
    $pdf->multiCell(25, 6, utf8_decode("IVA 12 %"), 1);
    $pdf->SetY($y1 + 18 + $var_escala2);
    $pdf->SetX($x1 + 25);
    $pdf->multiCell(15, 6, number_format($iva, 2, '.', ''), 1, 'R', 0);
    $pdf->SetY($y1 + 24 + $var_escala2);
    $pdf->SetX($x1);
    $pdf->multiCell(25, 6, utf8_decode("PROPINA"), 1);
    $pdf->SetY($y1 + 24 + $var_escala2);
    $pdf->SetX($x1 + 25);
    $pdf->multiCell(15, 6, utf8_decode("0.00"), 1, 'R', 0);
    $pdf->SetY($y1 + 30 + $var_escala2);
    $pdf->SetX($x1);
    $pdf->multiCell(25, 6, utf8_decode("TOTAL"), 1);
    $pdf->SetY($y1 + 30 + $var_escala2);
    $pdf->SetX($x1 + 25);
    $pdf->multiCell(15, 6, ($total), 1, 'R', 0);


//        $pdf->Ln(-0);
//        $pdf->SetX(5);
//        $var_escala1 = 5;
//        $pdf->Rect($pdf->GetX(), $pdf->GetY() + $var_escala1, 86, 10, 'D'); ////3 INFO ADICIONAL	   
//        $y = $pdf->GetY();
//        $x = $pdf->GetX();
//        $y1 = $pdf->GetY();
//        $x1 = $pdf->GetX();
//
//        $pdf->Text($x + 1, $y + 3 + $var_escala1, utf8_decode('FORMA DE PAGO')); //informacion 		
//        $pdf->SetY($y + 6);
//        $pdf->SetX($x);
//        $pdf->Text($x + 1, $y + 8 + $var_escala1, utf8_decode($codigovalor . '     ' . $valo0)); //informacion 		
//        $pdf->SetY($y + 6);
//        $pdf->SetX($x);
//        $pdf->Text($x + 70, $y + 3 + $var_escala1, utf8_decode("VALOR:"));
//        $pdf->SetY($y + 10);
//        $pdf->SetX($x);
//        $pdf->Text($x + 74, $y + 8 + $var_escala1, utf8_decode($total)); //informacion 


    $resultado = pg_query("select P.codigo,P.cod_barras, P.articulo, D.cantidad, D.precio_venta, D.descuento_producto, F.tarifa12 from factura_venta F,detalle_factura_venta D  , productos P where  d.cod_productos =P.cod_productos   and D.id_factura_venta = F.id_factura_venta  AND   F.id_factura_venta = '" . $id . "' order by D.id_detalle_venta");

    if ($pdf->getY() <= 228) {
        $pdf->Ln(3);
        $pdf->SetX(152);

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



        $resultado = pg_query("SELECT P.codigo, P.descripcion FROM factura_venta F, forma_pagos P WHERE F.id_forma_pago = P.id_forma_pago AND F.id_factura_venta = '" . $id . "'");
        while ($row = pg_fetch_row($resultado)) {
            $codigovalor = $row[0];
            $valo0 = $row[1];
        }
        $pdf->Ln(-0);
        $pdf->SetX(154);
//    
    }
    $pdf->SetY(185);
    $pdf->SetX(100);
    $pdf->Cell(60, 5, "___________________________", 0, 0, 'C', 0);

    $pdf->SetX(100);
    $pdf->Cell(60, 15, "FIRMA DEL CLIENTE", 0, 0, 'C', 0);


    /////////////////////GUIA DE REMISION////////////////////////////
    /////////////////////GUIA DE REMISION////////////////////////////
    /////////////////////GUIA DE REMISION////////////////////////////
    /////////////////////GUIA DE REMISION////////////////////////////
    /////////////////////GUIA DE REMISION////////////////////////////
    /////////////////////GUIA DE REMISION////////////////////////////

    $variabley = 180;
    $variableyz = 5;
    $pdf->SetY(15 + $variabley);
    $pdf->SetX(2);
    $pdf->Cell(300, 0, utf8_decode(''), 1, 1, 'R', 0);
    $pdf->SetFont('Amble-Regular', '', 11);
    $pdf->Text(4, 15 + $variabley + $variableyz, 'RUC:     ' . $ruc); // ruc	

    $pdf->Text(108, 15 + $variabley + $variableyz, utf8_decode("G   U   I   A     D  E     R    E    M    I    S    I    Ò     N")); // Tipo comprobante
    $pdf->SetFont('Amble-Regular', '', 9);
    $pdf->Text(108, 20 + $variabley + $variableyz, 'No.    ' . $num_serie_gua); // Secuencial
    $pdf->Text(108, 24 + $variabley + $variableyz, utf8_decode('NÚMERO DE AUTORIZACIÓN')); // N° Autorizacion
    $pdf->SetY(25 + $variabley + $variableyz);
    $pdf->SetX(107);
    $pdf->Multicell(100, 5, $num_autorizacion_guia, 0); // N° Autorización		
    if ($fechaAut != '') {
        $pdf->Text(108, 33 + $variabley + $variableyz, utf8_decode('FECHA Y HORA DE AUTORIZACIÓN' . "  " . $fecha_autori_guia)); // fecha y hora de autorizacion
    }

    $code_number1 = $claveAcceso_guia; // Código de barras	

    new barCodeGenrator($code_number1, 1, 'temp1.gif', 470, 60, true); /// img codigo barras	
    $pdf->Image('temp1.gif', 108, 39 + $variabley, 96, 15);


    $pdf->SetY(17 + $variabley + $variableyz);
    $pdf->SetX(3);
    $pdf->multiCell(98, 5, $razonSocial, 0); // Razon Social Empresa	

    $pdf->SetY(21 + $variabley + $variableyz);
    $pdf->SetX(3);
    $pdf->multiCell(98, 5, 'Direccion: ' . $direccionEstablecimiento, 0); // Direccion Establecimiento
    $pdf->SetY(25 + $variabley + $variableyz);
    $pdf->SetX(3);
    $pdf->multiCell(98, 5, 'CLAVE DE ACCESO: ', 0); // Direccion Establecimiento
    $pdf->Text(4, 35 + $variabley + $variableyz, utf8_decode('Obligado a llevar Contabilidad: ' . $obligado)); // Obligado a llevar contabilidad
    $pdf->Text(4, 52 + $variabley, utf8_decode('TRANSPORTISTA: ')); // Obligado a llevar contabilidad
    $pdf->SetY(54 + $variabley);
    $pdf->SetX(10);
    $pdf->Cell(190, 0, utf8_decode(''), 1, 1, 'R', 0);

    $pdf->SetY(54 + $variabley);
    $pdf->SetX(90);
    $pdf->multiCell(150, 6, utf8_decode('Identificaciòn      ' . $ruc_transportista), 0); // Nombre cliente		
    $pdf->Text(5, 58 + $variabley, utf8_decode('RAZÒN SOCIAL/NOMBRES:         ' . $nombre_transportista)); //fecha de emision cliente

    $pdf->Text(160, 58 + $variabley, utf8_decode('Placa:  ' . $placa));
    $pdf->Text(5, 62 + $variabley, utf8_decode('Punto de Partida:                           ' . $direcionMatriz));
    $pdf->Text(120, 62 + $variabley, utf8_decode('Fecha Inicio Transporte:     ' . $fecha_inicio));
    $pdf->Text(120, 65 + $variabley, utf8_decode('Fecha fin Transporte:            ' . $fecha_fin));

    $pdf->SetY(66 + $variabley);
    $pdf->SetX(10);
    $pdf->Cell(190, 0, utf8_decode(''), 1, 1, 'R', 0);

    $pdf->Text(5, 70 + $variabley, utf8_decode(' FACTURA:      ' . $secuencial));
//    $pdf->Text(120, 140, utf8_decode('Fecha de Emisiòn:                     ' . $fechaEmision));
    $pdf->Text(5, 74 + $variabley, utf8_decode('Nùmero de Autorizaciòn:         ' . $numeroAutorizacion));

    $pdf->Text(5, 65 + $variabley, utf8_decode('Motivo de Traslado:                       VENTA DE PRODUCTOS   '));

    $pdf->Text(5, 82 + $variabley, utf8_decode('Destino (Punto de Llegada):          ' . $lugar_destino));
    $pdf->Text(5, 78 + $variabley, utf8_decode('ID/CI/RUC(Destinatario): ' . $identificacion_destinatario));
    $pdf->Text(80, 78 + $variabley, utf8_decode('Razòn Social/Nombres Apellidos:         ' . $razon_destinatario));

    $pdf->Text(60, 70 + $variabley, utf8_decode('Ruta: ' . maxCaracter($direccionEstablecimiento,20) . '-' . $lugar_destino));
    $variableyz = 4;
    // detalles factura
    $pdf->SetFont('Amble-Regular', '', 9);
    $pdf->SetY(90 + $variabley - $variableyz);
    $pdf->SetX(20);
    $pdf->multiCell(20, 5, utf8_decode('Cantidad'), 1);
    $pdf->SetY(90 + $variabley - $variableyz);
    $pdf->SetX(40);
    $pdf->multiCell(90, 5, utf8_decode('Descripciòn'), 1);
    $pdf->SetY(90 + $variabley - $variableyz);
    $pdf->SetX(130);
    $pdf->multiCell(40, 5, utf8_decode('Còdigo Principal'), 1);
    $pdf->SetY(90 + $variabley - $variableyz);
    $pdf->SetX(170);
    $pdf->multiCell(30, 5, utf8_decode('Còdigo Auxiliar'), 1);
    $pdf->SetY(90 + $variabley - $variableyz);
    $pdf->SetX(170);
    $pdf->SetY(90 + $variabley - $variableyz);
    $pdf->SetX(188);
    $x = 95;
    $y = 1 + $variabley;
    $resultado_pc = '0';
    $resultado_p = pg_query("select sum(D.cantidad)  
from detalle_factura_venta D 
where  id_factura_venta = '$id_factura_venta'");
    while ($row = pg_fetch_row($resultado_p)) {
        $resultado_pc = $row[0];
    }
    $resultado = pg_query("select P.codigo,P.cod_barras, P.articulo, D.cantidad  from factura_venta F,detalle_factura_venta D  , productos P where  d.cod_productos =P.cod_productos   and D.id_factura_venta = F.id_factura_venta  AND   F.id_factura_venta = '$id_factura_venta' LIMIT 1");
    while ($row = pg_fetch_row($resultado)) {
        $codigo = maxCaracter(utf8_decode("VA"), 15);
        $codigoAuxiliar = maxCaracter(utf8_decode("VA"), 13);
        $descripcion = utf8_decode("LUBRICANTES FILTROS Y ACCESORIOS");
        $cantidad = $resultado_pc;
        $tarifa12 = 0;
        $tarifa12 = $tarifa12 * $cantidad;
        $Descucaltres = 0;
        $desc = 0;
        $valcien = 100;
        $pdf->SetY($x + $variabley - $variableyz);
        $pdf->SetX(20);
        if (strlen($cantidad) > 10)
            $tam = 3;
        else
            $tam = 6;
        $pdf->multiCell(20, $tam, $cantidad, 1);

        $pdf->SetY($x + $variabley - $variableyz);
        $pdf->SetX(40);
        if (strlen($descripcion) > 50)
            $tam = 3;
        else
            $tam = 6;
        $pdf->multiCell(90, $tam, $descripcion, 1);
        $pdf->SetY($x + $variabley - $variableyz);
        $pdf->SetX(130);
        $pdf->multiCell(40, 6, $codigo, 1);
        $pdf->SetY($x + $variabley - $variableyz);
        $pdf->SetX(170);
        $pdf->multiCell(30, 6, $codigoAuxiliar, 1);
        $pdf->SetY($x + $variabley - $variableyz);
        $pdf->SetX(170);
        $pdf->SetY($x + $variabley - $variableyz);
        $pdf->SetX(188);
        $x = $x + 6;
    }

    if ($pdf->getY() <= 500) {
        
    } else {
        
    }
    if (isset($_GET['id'])) {
        $pdf->Output();
    } else {
        $pdf_file_contents = $pdf->Output("", "S");
        return $pdf_file_contents;
    }
}

?>