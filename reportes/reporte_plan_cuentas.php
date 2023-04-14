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
    function SetWidths($w)
    {
        $this->widths = $w;
    }

    function Header()
    {
        $this->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->SetFont('Amble-Regular', '', 10);
        $fecha = date('Y-m-d', time());
        $this->SetX(0);
        $this->SetY(0);
        $this->Cell(105, 5, $fecha, 0, 0, 'C', 0);
        $this->Cell(105, 5, "CONTABILIDAD", 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(210, 8, utf8_decode($_SESSION['nombre_empresa']), 0, 1, 'C', 0);
        $this->Image('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"], 10, 7, 15, 15);
        $this->Image('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"], 180, 7, 15, 15);
        // $this->Cell(180, 5, "PROPIETARIO: " . utf8_decode($_SESSION['propietario']), 0, 1, 'C', 0);
        // $this->Cell(80, 5, "TEL.: " . utf8_decode($_SESSION['telefono']), 0, 0, 'R', 0);
        // $this->Cell(80, 5, "CEL.: " . utf8_decode($_SESSION['celular']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, "DIR.: " . utf8_decode($_SESSION['direccion']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, "SLOGAN.: " . utf8_decode($_SESSION['slogan']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, utf8_decode($_SESSION['pais_ciudad']), 0, 1, 'C', 0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);
        $this->Line(0, 25, 210, 25);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(210, 5, utf8_decode("PLAN DE CUENTAS"), 0, 1, 'C', 0);
        $this->Ln(8);
        $this->SetX(1);
        $this->SetFont('helvetica', 'B', 10);
        $this->SetFillColor(175, 215, 240);
        $this->Cell(30, 6, utf8_decode('TIPO'), 1, 0, 'C', 1);
        $this->Cell(50, 6, utf8_decode('CÓDIGO'), 1, 0, 'C', 1);
        $this->Cell(128, 6, utf8_decode('NOMBRE'), 1, 1, 'C', 1);
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
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->SetTitle('Plan de Cuentas');
$pdf->AliasNbPages();
$pdf->SetFont('helvetica', '', 9);
// filtro tipo
$query_tipo= "";
if ($_GET['tipo'] != '0'){
    $query_tipo = "WHERE cuenta='$_GET[tipo]'";
}
$sql = pg_query("SELECT cuenta, codigo_plan, descripcion FROM plan_cuentas $query_tipo ORDER BY codigo_plan");
while ($row = pg_fetch_row($sql)) {
    $pdf->SetX(1);
    $pdf->Cell(30, 6, utf8_decode($row[0]), 0, 0, 'C', 0);
    $pdf->Cell(50, 6, utf8_decode($row[1]), 0, 0, 'L', 0);
    $pdf->Cell(128, 6, utf8_decode($row[2]), 0, 1, 'L', 0);
}

$pdf->Output();
