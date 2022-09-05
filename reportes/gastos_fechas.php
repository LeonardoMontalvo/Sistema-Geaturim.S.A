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
        $this->rango = false;
        if ($_GET['inicio'] != '') {
            $this->rango = true;
        }
        $this->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->AddFont('helvetica', 'B', 'helveticab.php');
        $this->SetFont('Amble-Regular', '', 10);
        $fecha = date('Y-m-d', time());
        $this->SetX(0);
        $this->SetY(0);
        $this->Cell(105, 5, $fecha, 0, 0, 'C', 0);
        $this->Cell(105, 5, "GASTOS", 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(210, 8, $_SESSION['nombre_empresa'], 0, 1, 'C', 0);
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
        $this->Cell(210, 5, utf8_decode("GASTOS INTERNOS"), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->Ln(3);
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
$pdf->SetTitle('Gastos Internos');
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Amble-Regular', '', 9);

$query_fecha = "";
// RANGO DE FECHAS O FECHA ACTUAL
if ($pdf->rango) {
    $query_fecha = "BETWEEN '$_GET[inicio]' AND";
} else {
    $query_fecha = "=";
}
$sql = pg_query(
    "SELECT * from gastos_internos g,usuario u,proveedores p where g.id_usuario=u.id_usuario 
    AND g.id_proveedor=p.id_proveedor and g.fecha_actual $query_fecha '$_GET[fin]'"
);
if (pg_num_rows($sql)) {
    $total = 0;
    $pdf->SetX(1);
    $pdf->Cell(25, 6, utf8_decode('Comprobante'), 1, 0, 'C', 0);
    $pdf->Cell(35, 6, utf8_decode('Nro. Factura'), 1, 0, 'C', 0);
    $pdf->Cell(35, 6, utf8_decode('Documento'), 1, 0, 'C', 0);
    $pdf->Cell(30, 6, utf8_decode('Proveedor'), 1, 0, 'C', 0);
    $pdf->Cell(25, 6, utf8_decode('Fecha'), 1, 0, 'C', 0);
    $pdf->Cell(35, 6, utf8_decode('Descripción'), 1, 0, 'C', 0);
    $pdf->Cell(20, 6, utf8_decode('Total'), 1, 1, 'C', 0);
    while ($row = pg_fetch_row($sql)) {
        $pdf->SetX(1);
        $pdf->Cell(25, 6, utf8_decode($row[3]), 0, 0, 'C', 0);
        $pdf->Cell(35, 6, utf8_decode($row[6]), 0, 0, 'C', 0);
        $pdf->Cell(35, 6, utf8_decode($row[23]), 0, 0, 'C', 0);
        $pdf->Cell(30, 6, utf8_decode($row[24]), 0, 0, 'C', 0);
        $pdf->Cell(25, 6, utf8_decode($row[4]), 0, 0, 'C', 0);
        $pdf->Cell(35, 6, maxCaracter(utf8_decode($row[7]), 20), 0, 0, 'L', 0);
        $pdf->Cell(20, 6, utf8_decode($row[8]), 0, 1, 'C', 0);
        $total = $total + $row[8];
    }
    $pdf->SetX(1);
    $pdf->Cell(207, 0, utf8_decode(""), 1, 1, 'R', 0);
    $pdf->Cell(180, 6, utf8_decode("Total"), 0, 0, 'R', 0);
    $pdf->Cell(30, 6, maxCaracter((number_format(($total), 2, ',', '.')), 20), 0, 1, 'C', 0);
    $pdf->Ln(3);
}

$pdf->Output();
