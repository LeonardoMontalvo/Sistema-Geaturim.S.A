<?php

/* include('../../../dist/fpdf/rotation.php');
  include('../../../dist/fpdf/barcode.inc.php');
  include_once('../../../admin/class.php'); */
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
        //$this->SetX(1);
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
    generarPDFReten($id);
}/* else{
  return generarPDF($id);
  } */

function generarPDFReten($id) {
    conectarse();
    $consulta = pg_query( "SELECT nombre_empresa, ruc_empresa, direccion_empresa, obligacion, establecimiento,
          punto_emision, g.id_gastos, comprobante, fecha_emision, fecha as fecha_aut,
          g.num_serie, rffc.clave, rffc.num_autorizacion, identificacion_pro, empresa_pro, direccion_pro,
          correo, case when telefono!='' then telefono else celular end as telefono_pro,rffc.num_serie as num_serie_reten
          from empresa e inner join gastos g using(id_empresa)
          inner join retencion_fuente_factura_compra rffc on rffc.id_factura=g.id_gastos 
          left join proveedores p using(id_proveedor) 
          inner join tipo_documento td using(id_tdocu) 
          where rffc.id_factura='" . $id . "'");

       while ($row = pg_fetch_assoc($consulta)) {
        $razonSocial = $row['nombre_empresa'];
        $ruc = $row['ruc_empresa'];
        $direccionEstablecimiento = $row['direccion_empresa'];
        $direcionMatriz = $row['direccion_empresa'];
        // $nombreComercial = $row[17];
        $obligado = $row['obligacion'];
        // $nroContribuyente = $row[19];
        $establecimiento = $row['establecimiento'];
        $puntoEmision = $row['punto_emision'];
        $id_fact = $row['id_gastos'];
        $tipoDocumento = $row['comprobante'];
        $fechaEmision = $row['fecha_emision'];
        $ip = $fechaEmision;
        $fechasepar = split("\-", $ip);
        $mes = $fechasepar[1];
        $anio = $fechasepar[0];
        $periodo_fiscal = "$mes" . "/" . "$anio";
        $fechaAut = $row['fecha_aut'];
        $secuencial = $row['num_serie'];
		 $secuencial3= $row['num_serie'];
          $secuencial2 = $row['num_serie_reten'];
        $ip = $secuencial;
        $iparr = split("\-", $ip);
        $secuencial = $iparr[2];
        $claveAcceso = $row['clave'];
        $numeroAutorizacion = $row['num_autorizacion'];
        if ($numeroAutorizacion == "") {
            $numeroAutorizacion = $row['clave'];
        } else {
            $numeroAutorizacion = $row['num_autorizacion'];
        }
        $identificacion = $row['identificacion_pro'];
        $contribuyente = $row['empresa_pro'];
        $direcion = $row['direccion_pro'];
        $telefono = $row['telefono_pro'];
        $email = $row['correo'];
    }

    $consulta_ambiente = pg_query("select nombre_ambi from ambiente where id_ambi=2  ");
    while ($row = pg_fetch_row($consulta_ambiente)) {
        $nombre_ambi = $row[0];
    }

    $ambiente = $nombre_ambi;
    $consulta_emision = pg_query("select nombre_temision from tipo_emision   ");
    while ($row = pg_fetch_row($consulta_emision)) {
        $nombre_emi = $row[0];
    }
    $emision = $nombre_emi;
    $consulta_ambiente = pg_query("select nombre_ambi from ambiente  where id_ambi=2 ");
    while ($row = pg_fetch_row($consulta_ambiente)) {
        $ambiente = $row[0];
    }

    $consulta_emision = pg_query("select nombre_temision from tipo_emision where id_temision=1  ");
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

    $pdf = new PDF('P', 'mm', 'a4');
    $pdf->AddPage();
    $pdf->SetMargins(10, 0, 0, 0);
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak(true, 10);
    $pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
    $pdf->SetFont('Amble-Regular', '', 10);


     $pdf->Image('../../images/'.$_SESSION["parametros_empresa"]["logo_empresa"], 20, 10, 20); // Img 		
    $pdf->Rect(3, 45, 100, 53, 'D'); //2 datos personales
    $pdf->Text(108, 15, 'R U C :' . $ruc); //ruc		 	
    $pdf->Text(108, 23, utf8_decode("COMPROBANTE DE RETENCIÓN")); //tipo comprobante
    $pdf->Text(108, 31, 'No. '. $secuencial2); //tipo comprobante
    $pdf->Text(108, 39, utf8_decode('NÚMERO DE AUTORIZACIÓN')); //nro autorizacion TEXT
    $pdf->SetY(40);
    $pdf->SetX(107);
    $pdf->Multicell(100, 5, $numeroAutorizacion, 0); //nro autorizacion		
    if ($fechaAut != '') {
        $pdf->Text(108, 55, utf8_decode('FECHA Y HORA DE AUTORIZACIÓN')); //fecha y hora de autorizacion
        $pdf->Text(108, 61, $fechaAut); //FECHA
    }
    $pdf->Text(108, 68, utf8_decode('AMBIENTE: ' . $ambiente)); //ambiente
    $pdf->Text(108, 75, utf8_decode('EMISIÓN: ' . $emision)); //tipo de emision
    $pdf->Text(108, 81, utf8_decode('CLAVE DE ACCESO: ')); //clave de acceso
    $code_number = $claveAcceso; //////cpdigo de barras		
    new barCodeGenrator($code_number, 1, 'temp.gif', 470, 60, true); ///img codigo barras	
    $pdf->Image('temp.gif', 108, 83, 96, 15);

    $pdf->Rect(106, 8, 102, 90, 'D'); //3 DATOS EMPRESA	 
    $pdf->SetY(46);
    $pdf->SetX(4);
    $pdf->multiCell(98, 5, utf8_decode($razonSocial), 0); //NOMBRE proveedor	
    //$pdf->SetY(56);
    //$pdf->SetX(4);	
    //$pdf->multiCell( 98,5, $nombreComercial ,0 );//NOMBRE proveedor	
    $pdf->SetY(55);
    $pdf->SetX(4);
    $pdf->multiCell(98, 5, utf8_decode('Dir Matriz: ' . $direcionMatriz), 0); //	 direccion	
    $pdf->SetY(65);
    $pdf->SetX(4);
    $pdf->multiCell(98, 5, utf8_decode('Dir Sucursal: ' . $direccionEstablecimiento), 0); //	 direccion	
    //$pdf->Text(5, 90, utf8_decode('Contribuyente Especial Resolución Nro: '.$nroContribuyente));//contribuyente
    $pdf->Text(5, 96, utf8_decode('Obligado a llevar Contabilidad: ' . $obligado)); //obligado
        $pdf->Text(5, 82, utf8_decode('REGIMEN GENERAL')); //obligado
    $pdf->SetY(84);
    $pdf->SetX(4);
    $pdf->multiCell(80, 3, utf8_decode('Agente de Retención Mediante Resolución Nro. NAC-DNCRASC20-00000001')); //fecha de emision cliente

    $pdf->Rect(3, 101, 205, 20, 'D'); ////4 INFO TRIBUTARIA			     
    $pdf->SetY(101);
    $pdf->SetX(3);
    $pdf->multiCell(130, 6, utf8_decode('Razón Social / Nombres y Apellidos: ' . $contribuyente), 0); //NOMBRE cliente	
    $pdf->Text(135, 105, utf8_decode('RUC / CI: ' . $identificacion)); //ruc cliente
    $pdf->Text(5, 117, utf8_decode('Fecha de Emisión: ' . $fechaEmision)); //fecha de emision cliente
    $pdf->Text(136, 117, utf8_decode('Guía de Remisión: ')); //guia remision 
    //////////////////detalles factura/////////////
    $pdf->SetFont('Amble-Regular', '', 9);
    $pdf->SetY(123);
    $pdf->SetX(3);
    $pdf->multiCell(50, 10, utf8_decode('Comprobante'), 1);
    $pdf->SetY(123);
    $pdf->SetX(53);
    $pdf->multiCell(32, 10, utf8_decode('Número'), 1);
    $pdf->SetY(123);
    $pdf->SetX(85);
    $pdf->multiCell(20, 5, utf8_decode('Fecha Emisión'), 1);
    $pdf->SetY(123);
    $pdf->SetX(105);
    $pdf->multiCell(15, 5, utf8_decode('Ejercicio Fiscal'), 1);
    $pdf->SetY(123);
    $pdf->SetX(120);
    $pdf->multiCell(28, 5, utf8_decode('Base Imponible para la Retención'), 1);
    $pdf->SetY(123);
    $pdf->SetX(148);
    $pdf->multiCell(20, 10, utf8_decode('Impuesto'), 1);
    $pdf->SetY(123);
    $pdf->SetX(168);
    $pdf->multiCell(20, 5, utf8_decode('Porcentaje Retención'), 1);
    $pdf->SetY(123);
    $pdf->SetX(188);
    $pdf->multiCell(20, 5, utf8_decode('Valor Retenido'), 1);

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
       inner join retencion_fuentes TR on CD.id_retencion_fuentes = TR.id_retencion_fuentes where CR.id_factura = $id_fact and CR.id_gastos='10'");

    $x = 133;
    $y = 3;
    while ($row = pg_fetch_row($consultaretencion)) {
        $pdf->SetY($x);
        $pdf->SetX(3);
        $comprobante = utf8_decode($secuencial3);
        if (strlen($comprobante) > 25)
            $tam = 5;
        else
            $tam = 10;
        $pdf->multiCell(50, $tam, "FACTURA", 1);

        $pdf->SetY($x);
        $pdf->SetX(53);
        $numero = utf8_decode($comprobante);
        if (strlen($numero) > 19)
            $tam = 5;
        else
            $tam = 10;
        $pdf->multiCell(32, $tam, $numero, 1);

        $pdf->SetY($x);
        $pdf->SetX(85);
        $fechaEmision = utf8_decode($fechaEmision);
        if (strlen($fechaEmision) > 10)
            $tam = 5;
        else
            $tam = 10;
        $pdf->multiCell(20, $tam, $fechaEmision, 1);

        $pdf->SetY($x);
        $pdf->SetX(105);
        $ejercicioFiscal = $periodo_fiscal;
        if (strlen($ejercicioFiscal) > 10)
            $tam = 5;
        else
            $tam = 10;
        $pdf->multiCell(15, $tam, $ejercicioFiscal, 1);

        $pdf->SetY($x);
        $pdf->SetX(120);
        $baseImponible = number_format($row[0], 2, '.', '');

        $baseImponible = utf8_decode($baseImponible);
        if (strlen($baseImponible) > 19)
            $tam = 5;
        else
            $tam = 10;
        $pdf->multiCell(28, $tam, $baseImponible, 1);

        $pdf->SetY($x);
        $pdf->SetX(148);
        $impuesto = utf8_decode($row[1]);
        if (strlen($impuesto) > 15)
            $tam = 5;
        else
            $tam = 10;
        $pdf->multiCell(20, $tam, $impuesto, 1);

        $pdf->SetY($x);
        $pdf->SetX(168);
        $porcentaje = utf8_decode($row[2]);
        if (strlen($porcentaje) > 10)
            $tam = 5;
        else
            $tam = 10;
        $pdf->multiCell(20, $tam, $porcentaje, 1);

        $pdf->SetY($x);
        $pdf->SetX(188);
        $valorRetenido = number_format(utf8_decode($row[3]), 2, '.', '');
        if (strlen($valorRetenido) > 15)
            $tam = 5;
        else
            $tam = 10;
        $pdf->multiCell(20, $tam, $valorRetenido, 1);

        $x = $x + 10;
    }
    /////////////////pie de pagina//////////	           	
    $pdf->Ln(5);
    $pdf->SetX(3);
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), 100, 55, 'D'); ////3 INFO ADICIONAL
    $y = $pdf->GetY();
    $x = $pdf->GetX();
    $pdf->Text($x + 5, $y + 5, utf8_decode('INFORMACIÓN ADICIONAL')); //informacion 		
    $pdf->SetY($y + 7);
    $pdf->SetX($x);
    $pdf->multiCell(100, 5, utf8_decode("Dirección:" . $direcion), 0);
    $pdf->SetY($y + 17);
    $pdf->SetX($x);
    $pdf->multiCell(100, 5, utf8_decode("Teléfono: " . $telefono), 0);
    $pdf->SetY($y + 29);
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