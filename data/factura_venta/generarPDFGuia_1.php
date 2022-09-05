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
        $this->Cell(20, 5, 'Generado: ' . $fecha, 0, 0, 'C', 0);
//	        $this->Cell(178, 5, 'MADEHERRAJES 4A', 0,0, 'R', 0);                                                             
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
    generarPDF($id);
}

function generarPDF($id) {
    conectarse();
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
        $consulta_ambiente = pg_query("select nombre_ambi from ambiente WHERE id_ambi='2' ");
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

    $consulta_ambiente = pg_query("select nombre_ambi from ambiente WHERE id_ambi='2' ");
    while ($row = pg_fetch_row($consulta_ambiente)) {
        $ambiente = $row[0];
    }
    $consulta_emision = pg_query("select nombre_temision from tipo_emision WHERE id_temision='1' ");
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
    $pdf->SetFont('Amble-Regular', '', 10);

//		$logo = $imagen;
//		$pdf->Rect(3, 8, 100, 36 ,1, 'D');

    $pdf->Image('../../images/'.$_SESSION["parametros_empresa"]["logo_empresa"], 25, 10, 31); // Img Empresa 
    $pdf->Rect(3, 45, 100, 53, 'D'); // 2 datos personales
    $pdf->Text(108, 15, 'RUC:     ' . $ruc); // ruc		 	
    $pdf->Text(108, 23, utf8_decode("G     U     I     A     D  E     R     E     M     I     S     I     Ò     N")); // Tipo comprobante
    $pdf->Text(108, 29, 'No.    ' . $num_serie_gua); // Secuencial
    $pdf->Text(108, 36, utf8_decode('NÚMERO DE AUTORIZACIÓN')); // N° Autorizacion
    $pdf->SetY(40);
    $pdf->SetX(107);
    $pdf->Multicell(100, 5, $num_autorizacion_guia, 0); // N° Autorización		
    $code_number1 = $num_autorizacion_guia; // Código de barras		
    new barCodeGenrator($code_number1, 1, 'temp1.gif', 470, 60, true); /// img codigo barras	
    $pdf->Image('temp1.gif', 108, 37, 96, 15);

    if ($fechaAut != '') {
        $pdf->Text(108, 55, utf8_decode('FECHA Y HORA DE AUTORIZACIÓN')); // fecha y hora de autorizacion
        $pdf->Text(108, 61, $fecha_autori_guia); // FECHA
    }

    $pdf->Text(108, 68, utf8_decode('AMBIENTE: ' . $ambiente)); // Ambiente
    $pdf->Text(108, 75, utf8_decode('EMISIÓN: ' . $emision)); // Tipo de emision
    $pdf->Text(108, 81, utf8_decode('CLAVE DE ACCESO: ')); // Clave de acceso
    $code_number = $claveAcceso_guia; // Código de barras		
    new barCodeGenrator($code_number, 1, 'temp.gif', 470, 60, true); /// img codigo barras	
    $pdf->Image('temp.gif', 108, 83, 96, 15);

    $pdf->Rect(106, 8, 102, 90, 'D'); //Datos Empresa	 
    $pdf->SetY(46);
    $pdf->SetX(4);
    $pdf->multiCell(98, 5, $razonSocial, 0); // Razon Social Empresa	
    $pdf->SetY(56);
    $pdf->SetX(4);
    $pdf->SetY(66);
    $pdf->SetX(4);
    $pdf->multiCell(98, 5, 'Dir Matriz: ' . $direccionEstablecimiento, 0); // Direccion Matriz	
    $pdf->SetY(76);
    $pdf->SetX(4);
    $pdf->multiCell(98, 5, 'Dir Sucursal: ' . $direccionEstablecimiento, 0); // Direccion Establecimiento	
    $pdf->Text(5, 96, utf8_decode('Obligado a llevar Contabilidad: ' . $obligado)); // Obligado a llevar contabilidad
    $pdf->Rect(3, 101, 205, 30, 'D'); // INFO TRIBUTARIA			     
    $pdf->SetY(101);
    $pdf->SetX(3);
    $pdf->multiCell(130, 6, utf8_decode('Identificaciòn (Transportista)                              ' . $ruc_transportista), 0); // Nombre cliente		
    $pdf->Text(5, 110, utf8_decode('RAZÒN SOCIAL / NOMBRES Y APELLIDOS:        ' . $nombre_transportista)); //fecha de emision cliente

    $pdf->Text(5, 120, utf8_decode('Placa:                                           ' . $placa));
    $pdf->Text(5, 125, utf8_decode('Punto de Partida:                     ' . $direcionMatriz));
    $pdf->Text(5, 130, utf8_decode('Fecha Inicio Transporte:         ' . $fecha_inicio));
    $pdf->Text(100, 130, utf8_decode('Fecha fin Transporte:        ' . $fecha_fin));

    $pdf->Rect(3, 135, 205, 150, 'D'); // INFO TRIBUTARIA	

    $pdf->Text(5, 140, utf8_decode('Comprobante de Venta      FACTURA:              ' . $secuencial));
    $pdf->Text(120, 140, utf8_decode('Fecha de Emisiòn:                     ' . $fechaEmision));
    $pdf->Text(5, 145, utf8_decode('Nùmero de Autorizaciòn:         ' . $numeroAutorizacion));

    $pdf->Text(5, 155, utf8_decode('Motivo de Traslado:                                  VENTA   '));

    $pdf->Text(5, 160, utf8_decode('Destino (Punto de Llegada):                    ' . $lugar_destino));
    $pdf->Text(5, 165, utf8_decode('Identificaciòn Destinatario:                     ' . $identificacion_destinatario));
    $pdf->Text(5, 170, utf8_decode('Razòn Social/Nombres Apellidos:         ' . $razon_destinatario));

    $pdf->Text(5, 175, utf8_decode('Documento Aduanero:        '));
    $pdf->Text(5, 180, utf8_decode('Còdigo Establecimeinto Destino:                     '));
    $pdf->Text(5, 185, utf8_decode('Ruta:  ' . $direccionEstablecimiento . '-' . $lugar_destino));

    // detalles factura
    $pdf->SetFont('Amble-Regular', '', 9);
    $pdf->SetY(193);
    $pdf->SetX(20);
    $pdf->multiCell(20, 5, utf8_decode('Cantidad'), 1);
    $pdf->SetY(193);
    $pdf->SetX(40);
    $pdf->multiCell(90, 5, utf8_decode('Descripciòn'), 1);
    $pdf->SetY(193);
    $pdf->SetX(130);
    $pdf->multiCell(40, 5, utf8_decode('Còdigo Principal'), 1);
    $pdf->SetY(193);
    $pdf->SetX(170);
    $pdf->multiCell(30, 5, utf8_decode('Còdigo Auxiliar'), 1);
    $pdf->SetY(193);
    $pdf->SetX(170);
    $pdf->SetY(193);
    $pdf->SetX(188);
    $x = 198;
    $y = 1;

    $resultado = pg_query("select P.codigo,P.cod_barras, P.articulo, D.cantidad  from factura_venta F,detalle_factura_venta D  , productos P where  d.cod_productos =P.cod_productos   and D.id_factura_venta = F.id_factura_venta  AND   F.id_factura_venta = '$id_factura_venta'");
    while ($row = pg_fetch_row($resultado)) {
        $codigo = maxCaracter(utf8_decode($row[0]), 15);
        $codigoAuxiliar = maxCaracter(utf8_decode($row[1]), 13);
        $descripcion = utf8_decode($row[2]);
        $cantidad = $row[3];
        $tarifa12 = 0;
        $tarifa12 = $tarifa12 * $cantidad;
        $Descucaltres = 0;
        $desc = 0;
        $valcien = 100;
        $pdf->SetY($x);
        $pdf->SetX(20);
        if (strlen($cantidad) > 10)
            $tam = 3;
        else
            $tam = 6;
        $pdf->multiCell(20, $tam, $cantidad, 1);
//			$pdf->SetY($x);
//			$pdf->SetX(23);
//			if(strlen($codigoAuxiliar) > 19)
//				$tam = 5;
//			else
//				$tam = 10;	
//			$pdf->multiCell(20, $tam, $codigoAuxiliar,1);

        $pdf->SetY($x);
        $pdf->SetX(40);
        if (strlen($descripcion) > 50)
            $tam = 3;
        else
            $tam = 6;
        $pdf->multiCell(90, $tam, $descripcion, 1);
        $pdf->SetY($x);
        $pdf->SetX(130);
        $pdf->multiCell(40, 6, $codigo, 1);
        $pdf->SetY($x);
        $pdf->SetX(170);
        $pdf->multiCell(30, 6, $codigoAuxiliar, 1);
        $pdf->SetY($x);
        $pdf->SetX(170);
        $pdf->SetY($x);
        $pdf->SetX(188);
        $x = $x + 6;
    }

    // pie de pagina           	
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