<?php
require('../fpdf/fpdf.php');
include '../procesos/base.php';
include '../procesos/funciones.php';
conectarse();
date_default_timezone_set('America/Guayaquil');
session_start();
class PDF extends FPDF
{
    var $widths;
    var $aligns;
    var $temp1;
    var $temp2;
    var $temp3;
    var $temp4;
    var $temp5;
    function SetWidths($w)
    {
        $this->widths = $w;
    }
    function Header()
    {
        $this->rango = false;
        if ($_GET['inicio'] != '') {
            $this->rango = true;
        }
        $this->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->SetFont('Amble-Regular', '', 10);
        $fecha = date('Y-m-d', time());
        $this->SetX(1);
        $this->SetY(1);
        $this->Cell(20, 5, $fecha, 0, 0, 'C', 0);
        $this->Cell(170, 5, "TESORERIA", 0, 1, 'R', 0);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(190, 8, utf8_decode($_SESSION['nombre_empresa']), 0, 1, 'C', 0);
        $this->Image('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"], 5, 5, 25, 15);
        $this->SetFont('Amble-Regular', '', 10);
        // $this->Cell(190, 5, "PROPIETARIO: ".utf8_decode($_SESSION['propietario']),0,1, 'C',0);                                
        // $this->Cell(80, 5, "TEL.: ".utf8_decode($_SESSION['telefono']),0,0, 'R',0);                                
        // $this->Cell(80, 5, "CEL.: ".utf8_decode($_SESSION['celular']),0,1, 'C',0);                                
        // $this->Cell(190, 5, "DIR.: ".utf8_decode($_SESSION['direccion']),0,1, 'C',0);                                
        // $this->Cell(190, 5, "SLOGAN.: ".utf8_decode($_SESSION['slogan']),0,1, 'C',0);                                
        // $this->Cell(190, 5, utf8_decode( $_SESSION['pais_ciudad']),0,1, 'C',0);                                                                                        
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);
        $this->SetFillColor(120, 120, 120);
        $this->Line(0, 25, 210, 25);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(190, 5, utf8_decode('ESTADO DE PÉRDIDAS Y GANANCIAS'), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(100, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(100, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(190, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->Ln(2);
        $this->SetX(5);
        $this->SetFillColor(175, 215, 240);
        $this->SetFont('helvetica', 'B', 10);
        $this->Cell(50, 6, utf8_decode('CODIGO'), 1, 0, 'C', 1);
        $this->Cell(70, 6, utf8_decode('CUENTA'), 1, 0, 'C', 1);
        $this->Cell(40, 6, utf8_decode('MES ACTUAL'), 1, 0, 'C', 1);
        $this->Cell(40, 6, utf8_decode('ACUMULADO'), 1, 1, 'C', 1);
        $this->SetFillColor(255, 255, 225);
        $this->SetLineWidth(0.2);
    }
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}
$pdf = new PDF('P', 'mm', 'a4');
$pdf->AddPage();
$pdf->SetTitle('Estado de Perdidas y Ganancias');
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AliasNbPages();

$pdf->SetX(0);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->Cell(210, 0, utf8_decode(''), 1, 1, 'R', 1);
$pdf->Cell(162, 6, utf8_decode('Utilidad Bruta:'), 0, 0, 'R', 0);
// $pdf->Cell(20, 6, (number_format($total_debe, 2, ',', '.')), 0, 0, 'C', 0);
// $pdf->Cell(20, 6, (number_format($total_haber, 2, ',', '.')), 0, 1, 'C', 0);

$pdf->Ln(20);
$pdf->SetX(7);
$pdf->Cell(40, 0, utf8_decode('x'), 1, 1, 'R', 1);
$pdf->SetX(44);
$pdf->Cell(5, 0, "", 0, 0, 'R', 0);
$pdf->SetX(57);
$pdf->Cell(40, 0, utf8_decode('x'), 1, 1, 'R', 1);
$pdf->SetX(94);
$pdf->Cell(5, 0, "", 0, 0, 'R', 0);
$pdf->SetX(107);
$pdf->Cell(40, 0, utf8_decode('x'), 1, 1, 'R', 1);
$pdf->SetX(144);
$pdf->Cell(5, 0, "", 0, 0, 'R', 0);
$pdf->SetX(157);
$pdf->Cell(40, 0, utf8_decode('x'), 1, 1, 'R', 1);
$pdf->Ln(4);
$pdf->SetX(7);
$pdf->Cell(40, 0, utf8_decode('Elaborado por: ' . $_SESSION['user']), 0, 0, 'C', 0);
$pdf->SetX(44);
$pdf->SetX(57);
$pdf->Cell(40, 0, utf8_decode('Aprobado'), 0, 0, 'C', 0);
$pdf->SetX(94);
$pdf->SetX(107);
$pdf->Cell(40, 0, utf8_decode('Contabilidad'), 0, 0, 'C', 0);
$pdf->SetX(144);
$pdf->SetX(157);
$pdf->Cell(40, 0, utf8_decode('Recibí Conforme'), 0, 1, 'C', 0);
$pdf->Ln(3);
$pdf->SetX(157);
$pdf->Cell(40, 0, utf8_decode('C.I.:'), 0, 1, 'L', 0);

$pdf->Output();
