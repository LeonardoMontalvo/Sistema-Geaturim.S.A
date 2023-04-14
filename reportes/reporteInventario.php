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
        $this->Cell(210, 6, utf8_decode("INVENTARIO"), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->Ln(4);
        $this->SetFont('helvetica', 'B', 9);
        $this->SetFillColor(175, 215, 240);
        $this->Cell(20, 6, utf8_decode("ID. INV."), 1, 0, 'C', 1);
        $this->Cell(35, 6, utf8_decode("CODIGO"), 1, 0, 'C', 1);
        $this->Cell(75, 6, utf8_decode("PRODUCTO"), 1, 0, 'C', 1);
        $this->Cell(15, 6, utf8_decode("COSTO"), 1, 0, 'C', 1);
        $this->Cell(15, 6, utf8_decode("P. MINO"), 1, 0, 'C', 1);
        $this->Cell(15, 6, utf8_decode("P. MAYO"), 1, 0, 'C', 1);
        $this->Cell(15, 6, utf8_decode("P. NEGO"), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode("CANT. INV."), 1, 1, 'C', 1);
        $this->Ln(1);
    }
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}


$pdf = new PDF('P', 'mm', 'a4');
$pdf->SetTitle('Inventario');
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->AliasNbPages();
$query_fecha = "";
// RANGO DE FECHAS O FECHA ACTUAL
if ($pdf->rango) {
    $query_fecha = "BETWEEN '$_GET[inicio]' AND";
} else {
    $query_fecha = "=";
}

$consulta = pg_query(
    "SELECT P.codigo, P.articulo, P.precio_compra, P.iva_minorista, P.iva_mayorista, P.iva_negocio, P.stock,
     I.id_inventario, D.disponibles
    FROM inventario I inner join detalle_inventario D using(id_inventario)
    INNER JOIN productos P using(cod_productos) LEFT JOIN detalle_producto_bodega using(cod_productos)
    WHERE I.fecha_actual $query_fecha '$_GET[fin]'
    ORDER BY I.id_inventario, P.articulo asc;"
);

/* var_dump("SELECT P.codigo, P.articulo, P.precio_compra, P.iva_minorista, P.iva_mayorista, P.iva_negocio, P.stock 
FROM inventario I inner join detalle_inventario D using(id_inventario)
INNER JOIN productos P using(cod_productos) LEFT JOIN detalle_producto_bodega using(cod_productos)
WHERE I.fecha_actual $query_fecha '$_GET[fin]'
ORDER BY P.articulo;"); */

if (pg_num_rows($consulta)) {
    while ($row = pg_fetch_row($consulta)) {
        $pdf->SetX(1);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(20, 5, maxCaracter(utf8_decode($row[7]), 30), 0, 0, 'L', 0);
        $pdf->Cell(34, 5, maxCaracter(utf8_decode($row[0]), 30), 0, 0, 'L', 0);
        $pdf->Cell(75, 5, maxCaracter(utf8_decode($row[1]), 50), 0, 0, 'L', 0);
        $pdf->Cell(15, 5, number_format($row[2], 2, ',', '.'), 0, 0, 'R', 0);
        $pdf->Cell(15, 5, number_format($row[3], 2, ',', '.'), 0, 0, 'R', 0);
        $pdf->Cell(15, 5, number_format($row[4], 2, ',', '.'), 0, 0, 'R', 0);
        $pdf->Cell(15, 5, number_format($row[5], 2, ',', '.'), 0, 0, 'R', 0);
        $pdf->Cell(20, 5, number_format($row[8], 2, ',', '.'), 0, 1, 'R', 0);
    }
}

$pdf->Output();
