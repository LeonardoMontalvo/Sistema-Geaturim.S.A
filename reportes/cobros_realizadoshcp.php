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
    //////
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
        $this->Cell(105, 5, "COMPRAS", 0, 1, 'C', 0);
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
        $this->Cell(210, 5, utf8_decode("HISTORIAL DE COMPRAS DE PRODUCTOS"), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->Ln(3);
        $row = pg_fetch_row(pg_query("select P.ARTICULO FROM productos p where P.cod_productos=$_GET[id] "));
        $this->SetFillColor(220, 240, 210);
        $this->Cell(210, 6, (('NOMBRE PRODUCTO:     ' . $row[0])), 0, 1, 'C', 1);
        $this->Ln(1);
        $this->SetFont('helvetica', 'B', 9);
        $this->SetFillColor(175, 215, 240);
        $this->Cell(25, 6, utf8_decode('Fecha'), 1, 0, 'C', 1);
        $this->Cell(70, 6, utf8_decode('Proveedor'), 1, 0, 'C', 1);
        $this->Cell(32, 6, utf8_decode('Identificacion'), 1, 0, 'C', 1);
        $this->Cell(32, 6, utf8_decode('Num Factura'), 1, 0, 'C', 1);
        $this->Cell(17, 6, utf8_decode('Cantidad'), 1, 0, 'C', 1);
        $this->Cell(17, 6, utf8_decode('p. Compra'), 1, 0, 'C', 1);
        $this->Cell(17, 6, utf8_decode('p. Total'), 1, 1, 'C', 1);
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
$pdf->SetTitle('Facturas Detalladas');
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->AliasNbPages();

$num_fact = 0;
$sub = 0;
$saldo = 0;

$query_fecha = "";
// RANGO DE FECHAS O FECHA ACTUAL
if ($pdf->rango) {
    $query_fecha = "BETWEEN '$_GET[inicio]' AND";
} else {
    $query_fecha = "=";
}

$sql1 = pg_query(
    "SELECT fc.fecha_actual,c.empresa_pro,c.identificacion_pro ,fc.num_serie,dfc.cantidad,dfc.precio_compra,dfc.total_compra,p.articulo 
    FROM factura_compra fc, detalle_factura_compra dfc, proveedores c, productos p 
    where fc.id_factura_compra=dfc.id_factura_compra and fc.id_proveedor=c.id_proveedor and dfc.cod_productos=p.cod_productos 
    and dfc.cod_productos= '$_GET[id]'  and   fc.fecha_actual $query_fecha '$_GET[fin]' order by fc.fecha_actual;"
);
if (pg_num_rows($sql1)) {
    while ($row2 = pg_fetch_row($sql1)) {
        $pdf->SetX(1);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(24, 6, utf8_decode($row2[0]), 0, 0, 'C', 0);
        $pdf->Cell(70, 6, utf8_decode(maxCaracter($row2[1],30)), 0, 0, 'C', 0);
        $pdf->Cell(32, 6, $row2[2], 0, 0, 'C', 0);
        $pdf->Cell(32, 6, $row2[3], 0, 0, 'C', 0);
        $pdf->Cell(17, 6, number_format($row2[4], 2, ',', '.'), 0, 0, 'C', 0);
        $pdf->Cell(17, 6, number_format($row2[5], 2, ',', '.'), 0, 0, 'C', 0);
        $pdf->Cell(17, 6, number_format($row2[6], 2, ',', '.'), 0, 1, 'C', 0);
        $sub += $row2[5];
        $saldo += $row2[6];
    }
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
    $pdf->SetX(1);
    $pdf->Cell(170, 6, utf8_decode("Totales"), 0, 0, 'R', 0);
    $pdf->Cell(18, 6, maxCaracter((number_format($sub, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(18, 6, maxCaracter((number_format($saldo, 2, ',', '.')), 20), 0, 1, 'R', 0);
}

$pdf->Output();
