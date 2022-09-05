<?php

/* include('../../../dist/fpdf/rotation.php');
  include('../../../dist/fpdf/barcode.inc.php');
  include_once('../../../admin/class.php'); */
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
        $this->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->SetFont('Amble-Regular', '', 10);
        $fecha = date('Y-m-d', time());
        //$this->SetX(1);
        $this->SetY(1);
        //    $this->Cell(20, 5, 'Generado: '.$fecha, 0,0, 'C', 0);                                                             
        //    $this->Cell(115, 5, 'ALMACEN CASA VIVA', 0,0, 'R', 0);                                                             
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
    generarPDFReten($id);
}/* else{
  return generarPDF($id);
  } */

function generarPDFReten($id) {
    conectarse();
    $consulta = pg_query("select * from empresa left join liquidacion_compra on empresa.id_empresa  = liquidacion_compra.id_empresa left join retencion_fuente_factura_compra on retencion_fuente_factura_compra.id_factura=liquidacion_compra.id_liquidacion_compra left join proveedores on liquidacion_compra.id_proveedor=proveedores.id_proveedor left join tipo_documento on tipo_documento.id_tdocu=proveedores.id_tdocu where retencion_fuente_factura_compra.id_retencion_fuente_factura_compra ='" . $id . "' and retencion_fuente_factura_compra.id_gastos='20'");

    while ($row = pg_fetch_row($consulta)) {
        $ruc = $row[2];

        $numeroAutorizacion = $row[72];


        if ($numeroAutorizacion == "") {
            $numeroAutorizacion = $row[70];
        } else {
            $numeroAutorizacion = $row[72];
        }
        $fecha_registro_retencion = $row[30];
        $fechaEmision = $row[30];
        $ip = $fechaEmision;
        $fechasepar = split("\-", $ip);

        $mes = $fechasepar[1];
        $anio = $fechasepar[0];

        $periodo_fiscal = "$mes" . "/" . "$anio";
        $claveAcceso = $row[70];
        $razonSocial = $row[1];
        $nombreComercial = $row[16];
        $direcionMatriz = $row[7];
        $direccionEstablecimiento = $row[3];
        $nroContribuyente = $row[19];
        $obligado = $row[17];
        $contribuyente = $row[76];
        $identificacion = $row[75];
        $direcion = $row[79];
        $telefono = $row[80];
        $email = $row[86];
        $tipoDocumento = $row[29];
        $secuencial = $row[67];
        $ip = $secuencial;
        $iparr = split("\-", $ip);
        $secuencial = $iparr[2];
        $establecimiento = $row[21];
        $puntoEmision = $row[22];
        $fechaAut = $row[51];
        $id_fact = $row[24];
    }

    $consulta_ambiente = pg_query("select nombre_ambi from ambiente where id_ambi='2'  ");
    while ($row = pg_fetch_row($consulta_ambiente)) {
        $nombre_ambi = $row[0];
    }

    $ambiente = $nombre_ambi;
    $consulta_emision = pg_query("select nombre_temision from tipo_emision  ");
    while ($row = pg_fetch_row($consulta_emision)) {
        $nombre_emi = $row[0];
    }
    $emision = $nombre_emi;
    $consulta_ambiente = pg_query("select nombre_ambi from ambiente where id_ambi='2' ");
    while ($row = pg_fetch_row($consulta_ambiente)) {
        $ambiente = $row[0];
    }

    $consulta_emision = pg_query("select nombre_temision from tipo_emision  ");
    while ($row = pg_fetch_row($consulta_emision)) {
        $emision = $row[0];
    }


    $ceros = 9;
    $temp = '';
    $tam = $ceros - strlen($secuencial);
    for ($i = 0; $i < $tam; $i++) {
        $temp = $temp . '0';
    }
    $secuencial = $temp . '' . $secuencial;

    $pdf = new PDF('P', 'mm', 'a5');
    $pdf->AddPage();
    $pdf->SetMargins(10, 0, 0, 0);
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak(true, 10);
    $pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
    $pdf->SetFont('Amble-Regular', '', 7);

    //$pdf->Rect(3, 8, 100, 36 ,1, 'D');//1 empresa imagen
//		$pdf->Image('D:\xampp\htdocs\syswebfeb\images\logo.png',16,6,24); // Img Empresa 
    //      $pdf->Image('C:\xampp\htdocs\syswebcarias\images\logo.png',5,10,60);
    $pdf->Image('../../images/logo.png', 16, 6, 24); // Img Empresa
    $pdf->Rect(3, 30, 62, 53, 'D'); //2 datos personales
    $pdf->Text(70, 15, 'R U C :' . $ruc); //ruc		 	
    $pdf->Text(70, 20, utf8_decode("COMPROBANTE DE RETENCIÓN")); //tipo comprobante
    $pdf->Text(70, 28, 'No. ' . $establecimiento . '-' . $puntoEmision . '-' . $secuencial); //tipo comprobante
    $pdf->Text(70, 34, utf8_decode('NÚMERO DE AUTORIZACIÓN')); //nro autorizacion TEXT
    $pdf->SetY(35);
    $pdf->SetX(69);
    $pdf->Multicell(100, 5, $numeroAutorizacion, 0); //nro autorizacion		
    if ($fechaAut != '') {
        $pdf->Text(70, 45, utf8_decode('FECHA Y HORA DE AUTORIZACIÓN')); //fecha y hora de autorizacion
        $pdf->Text(70, 50, $fechaAut); //FECHA
    }


    $pdf->Text(70, 55, utf8_decode('AMBIENTE: ' . $ambiente)); //ambiente
    $pdf->Text(70, 60, utf8_decode('EMISIÓN: ' . $emision)); //tipo de emision
    $pdf->Text(70, 65, utf8_decode('CLAVE DE ACCESO: ')); //clave de acceso
    $code_number = $claveAcceso; //////cpdigo de barras		
    new barCodeGenrator($code_number, 1, 'temp.gif', 480, 60, true); ///img codigo barras	
    $pdf->Image('temp.gif', 68, 65, 80, 15);

    $pdf->Rect(67, 8, 79, 75, 'D'); //3 DATOS EMPRESA	 
    $pdf->SetY(46);
    $pdf->SetX(4);
    $pdf->multiCell(98, 5, $razonSocial, 0); //NOMBRE proveedor	
    //$pdf->SetY(56);
    //$pdf->SetX(4);	
    //$pdf->multiCell( 98,5, $nombreComercial ,0 );//NOMBRE proveedor	
    $pdf->SetY(50);
    $pdf->SetX(4);
    $pdf->multiCell(98, 5, 'Dir Matriz: ' . $direcionMatriz, 0); //	 direccion	
    $pdf->SetY(60);
    $pdf->SetX(4);
    $pdf->multiCell(60, 5, 'Dir Sucursal: ' . $direccionEstablecimiento, 0); //	 direccion	
    //$pdf->Text(5, 90, utf8_decode('Contribuyente Especial Resolución Nro: '.$nroContribuyente));//contribuyente
    $pdf->Text(5, 80, utf8_decode('Obligado a llevar Contabilidad: ' . $obligado)); //obligado
    $pdf->Rect(3, 85, 143, 20, 'D'); ////4 INFO TRIBUTARIA			     
    $pdf->SetY(85);
    $pdf->SetX(3);
    $pdf->multiCell(130, 6, utf8_decode('Razón Social: ' . $contribuyente), 0); //NOMBRE cliente	
    $pdf->Text(108, 90, utf8_decode('RUC / CI: ' . $identificacion)); //ruc cliente
    $pdf->Text(5, 98, utf8_decode('Fecha de Emisión: ' . $fecha_registro_retencion)); //fecha de emision cliente
    $pdf->Text(108, 98, utf8_decode('Guía de Remisión: ')); //guia remision 
    //////////////////detalles factura/////////////
    $pdf->SetFont('Amble-Regular', '', 7);
    $pdf->SetY(108);
    $pdf->SetX(3);
    $pdf->multiCell(18, 10, utf8_decode('Comprobante'), 1);
    $pdf->SetY(108);
    $pdf->SetX(21);
    $pdf->multiCell(28, 10, utf8_decode('Número'), 1);
    $pdf->SetY(108);
    $pdf->SetX(49);
    $pdf->multiCell(20, 10, utf8_decode('Fecha Emisión'), 1);
    $pdf->SetY(108);
    $pdf->SetX(69);
    $pdf->multiCell(15, 5, utf8_decode('Ejercicio Fiscal'), 1);
    $pdf->SetY(108);
    $pdf->SetX(84);
    $pdf->multiCell(22, 10, utf8_decode('Base Imponible'), 1);
    $pdf->SetY(108);
    $pdf->SetX(106);
    $pdf->multiCell(14, 10, utf8_decode('Impuesto'), 1);
    $pdf->SetY(108);
    $pdf->SetX(120);
    $pdf->multiCell(17, 10, utf8_decode('% Retención'), 1);
    $pdf->SetY(108);
    $pdf->SetX(137);
    $pdf->multiCell(10, 10, utf8_decode('V. Ret.'), 1);

    ////DETALLES COMPROBANTE////


    $consultaretencion = pg_query("select 
       CD.base_imponible, 
       R.nombre_trete, 
       CD.porsentaje, 
       CD.valor_retenido,
       R.codigo_trete, 
       TR.codigo_formulario 
       from retencion_fuente_factura_compra CR 
       inner join detallecomprobanteretencion CD on CR.id_retencion_fuente_factura_compra = CD.id_retencion_fuente_factura_compra 
       inner join tipo_retencion R on CD.id_trete = R.id_trete 
       inner join retencion_fuentes TR on CD.id_retencion_fuentes = TR.id_retencion_fuentes where CR.id_factura = $id_fact");

    $x = 118;
    $y = 3;
    while ($row = pg_fetch_row($consultaretencion)) {
        $pdf->SetY($x);
        $pdf->SetX(3);
        $comprobante = utf8_decode($tipoDocumento);
        if (strlen($comprobante) > 25)
            $tam = 5;
        else
            $tam = 10;
        $pdf->multiCell(18, $tam, "FACTURA", 1);

        $pdf->SetY($x);
        $pdf->SetX(21);
        $numero = utf8_decode($comprobante);
        if (strlen($numero) > 19)
            $tam = 5;
        else
            $tam = 10;
        $pdf->multiCell(28, $tam, $numero, 1);

        $pdf->SetY($x);
        $pdf->SetX(49);
        $fechaEmision = utf8_decode($fechaEmision);
        if (strlen($fechaEmision) > 10)
            $tam = 5;
        else
            $tam = 10;
        $pdf->multiCell(20, $tam, $fechaEmision, 1);

        $pdf->SetY($x);
        $pdf->SetX(69);
        $ejercicioFiscal = $periodo_fiscal;
        if (strlen($ejercicioFiscal) > 10)
            $tam = 5;
        else
            $tam = 10;
        $pdf->multiCell(15, $tam, $ejercicioFiscal, 1);

        $pdf->SetY($x);
        $pdf->SetX(84);
        $baseImponible = number_format($row[0], 2, '.', '');

        $baseImponible = utf8_decode($baseImponible);
        if (strlen($baseImponible) > 19)
            $tam = 5;
        else
            $tam = 10;
        $pdf->multiCell(22, $tam, $baseImponible, 1);

        $pdf->SetY($x);
        $pdf->SetX(106);
        $impuesto = utf8_decode($row[1]);
        if (strlen($impuesto) > 15)
            $tam = 5;
        else
            $tam = 10;
        $pdf->multiCell(14, $tam, $impuesto, 1);

        $pdf->SetY($x);
        $pdf->SetX(120);
        $porcentaje = utf8_decode($row[2]);
        if (strlen($porcentaje) > 10)
            $tam = 5;
        else
            $tam = 10;
        $pdf->multiCell(17, $tam, $porcentaje, 1);

        $pdf->SetY($x);
        $pdf->SetX(137);
        $valorRetenido = number_format(utf8_decode($row[3]), 2, '.', '');
        if (strlen($valorRetenido) > 15)
            $tam = 5;
        else
            $tam = 10;
        $pdf->multiCell(10, $tam, $valorRetenido, 1);

        $x = $x + 10;
    }
    /////////////////pie de pagina//////////	           	
    $pdf->Ln(5);
    $pdf->SetX(3);
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), 70, 25, 'D'); ////3 INFO ADICIONAL
    $y = $pdf->GetY();
    $x = $pdf->GetX();
    $pdf->Text($x + 5, $y + 5, utf8_decode('INFORMACIÓN ADICIONAL')); //informacion 		
    $pdf->SetY($y + 5);
    $pdf->SetX($x);
    $pdf->multiCell(100, 5, utf8_decode("Dirección:" . $direcion), 0);
    $pdf->SetY($y + 10);
    $pdf->SetX($x);
    $pdf->multiCell(100, 5, utf8_decode("Teléfono: " . $telefono), 0);
    $pdf->SetY($y + 15);
    $pdf->SetX($x);
    $pdf->multiCell(100, 5, utf8_decode("Email: " . $email), 0);
    if (isset($_GET['id'])) {
        $pdf->Output();
    } else {
        $pdf_file_contents = $pdf->Output("", "S");
        return $pdf_file_contents;
    }
}

?>