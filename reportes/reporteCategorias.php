<?php

require('../fpdf/fpdf.php');
include '../procesos/base.php';
include '../procesos/funciones.php';
require_once '../procesos/Categorias/CategoriasDAO.php';
conectarse();
date_default_timezone_set('America/Guayaquil');
session_start();
$consulta = "";

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
        $categoria = new CategoriasDAO();
        $this->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->SetFont('Amble-Regular', '', 10);
        $fecha = date('Y-m-d', time());
        $this->SetX(0);
        $this->SetY(0);
        $this->Cell(105, 5, $fecha, 0, 0, 'C', 0);
        $this->Cell(105, 5, "PRODUCTOS", 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(210, 8, utf8_decode($_SESSION['nombre_empresa']), 0, 1, 'C', 0);
        $this->Image('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"], 10, 7, 15, 15);
        $this->Image('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"], 180, 7, 15, 15);
        // $this->SetFont('Amble-Regular', '', 10);
        // $this->Cell(190, 5, "PROPIETARIO: " . utf8_decode($_SESSION['propietario']), 0, 1, 'C', 0);
        // $this->Cell(80, 5, "TEL.: " . utf8_decode($_SESSION['telefono']), 0, 0, 'R', 0);
        // $this->Cell(80, 5, "CEL.: " . utf8_decode($_SESSION['celular']), 0, 1, 'C', 0);
        // $this->Cell(190, 5, "DIR.: " . utf8_decode($_SESSION['direccion']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, "SLOGAN.: " . utf8_decode($_SESSION['slogan']), 0, 1, 'C', 0);
        // $this->Cell(190, 5, utf8_decode($_SESSION['pais_ciudad']), 0, 1, 'C', 0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);
        $this->Line(0, 25, 210, 25);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(210, 6, utf8_decode("LISTA DE PRODUCTOS POR CATEGORÍAS"), 0, 1, 'C', 0);
        $this->SetFont('helvetica', 'B', 10);
        if (empty($_GET['id'])) {
            $this->Cell(210, 7, utf8_decode("CATEGORÍA: SIN CATEGORIA"), 0, 1, 'C', 0);
        }else{
            $this->Cell(210, 7, utf8_decode("CATEGORÍA: " . $categoria->obtenerCategoria($_GET['id'])), 0, 1, 'C', 0);
        }
        $this->Ln(1);
        $this->SetFont('helvetica', 'B', 9);
        $this->SetFillColor(175, 215, 240);
        $this->Cell(40, 6, utf8_decode("CODIGO"), 1, 0, 'C', 1);
        $this->Cell(90, 6, utf8_decode("PRODUCTO"), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode("P. MINO"), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode("P. MAYO"), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode("P. NEGO"), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode("STOCK"), 1, 1, 'C', 1);
        $this->Ln(1);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$compra = 0;
$min = 0;
$may = 0;
$neg = 0;

$pdf = new PDF('P', 'mm', 'a4');
$pdf->SetTitle('Categorias');
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->AliasNbPages();

$sql = "select codigo, articulo, iva_minorista, iva_mayorista, iva_negocio, stock, precio_compra from productos where id_categoria='$_GET[id]'";
if (empty($_GET["id"])) {
    $sql = "select codigo, articulo, iva_minorista, iva_mayorista, iva_negocio, stock, precio_compra from productos where id_categoria is null";
}
$consulta = pg_query($sql);

if (pg_num_rows($consulta)) {
    while ($row = pg_fetch_row($consulta)) {
        $pdf->SetX(1);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(39, 5, utf8_decode($row[0]), 0, 0, 'L', 0);
        $pdf->Cell(90, 5, maxCaracter(utf8_decode($row[1]), 50), 0, 0, 'L', 0);
        $pdf->Cell(20, 5, number_format($row[2], 2, ',', '.'), 0, 0, 'R', 0);
        $pdf->Cell(20, 5, number_format($row[3], 2, ',', '.'), 0, 0, 'R', 0);
        $pdf->Cell(20, 5, number_format($row[4], 2, ',', '.'), 0, 0, 'R', 0);
        $pdf->Cell(20, 5, number_format($row[5], 2, ',', '.'), 0, 1, 'R', 0);
        $compra += ($row[4] * $row[5]);
        $min += ($row[4] * $row[2]);
        $may += ($row[4] * $row[3]);
        $neg += ($row[4] * $row[3]);
    }
    $pdf->SetX(1);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(129, 5, utf8_decode("Totales"), 0, 0, 'R', 0);
    $pdf->Cell(20, 5, number_format($compra, 2, ',', '.'), 0, 0, 'R', 0);
    $pdf->Cell(20, 5, number_format($min, 2, ',', '.'), 0, 0, 'R', 0);
    $pdf->Cell(20, 5, number_format($may, 2, ',', '.'), 0, 0, 'R', 0);
    $pdf->Cell(20, 5, number_format($neg, 2, ',', '.'), 0, 1, 'R', 0);
}
$pdf->Output();
