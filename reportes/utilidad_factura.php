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
        $this->Cell(105, 5, "VENTAS", 0, 1, 'C', 0);
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
        $this->Cell(210, 5, utf8_decode("UTILIDAD POR FACTURA"), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->Ln(3);
        $this->SetX(1);
        $this->SetFont('helvetica', 'B', 9);
        $this->SetFillColor(175, 215, 240);
        $this->Cell(40, 6, utf8_decode('Nro. Factura'), 1, 0, 'C', 1);
        $this->Cell(40, 6, utf8_decode('Factura'), 1, 0, 'C', 1);
        $this->Cell(26, 6, utf8_decode('Total P. Venta'), 1, 0, 'C', 1);
        $this->Cell(26, 6, utf8_decode('Total P. Compra'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('Utilidad'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('Fecha Pago'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('Tipo Pago'), 1, 1, 'C', 1);
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
$pdf->SetTitle('Utilidades Factura');
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

$total = 0;
$sub = 0;
$pv = 0;
$pc = 0;
$util = 0;
$consulta = pg_query("select id_cliente,identificacion,nombres_cli from clientes");
if (pg_num_rows($consulta)) {
    while ($row = pg_fetch_row($consulta)) {
        $sql1 = pg_query("select * from factura_venta where factura_venta.id_empresa='$_GET[id]' and  fecha_actual $query_fecha '$_GET[fin]' and id_cliente='$row[0]' and estado='Activo'");
        if (pg_num_rows($sql1)) {
            $pdf->SetX(0);
            $pdf->SetFillColor(216, 216, 231);
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->Cell(105, 8, utf8_decode("RUC/CI:: " . $row[1]), 0, 0, 'L', true);
            $pdf->Cell(105, 8, utf8_decode("CLIENTE: " . $row[2]), 0, 1, 'L', true);
            $pdf->Ln(2);
            while ($row1 = pg_fetch_row($sql1)) {
                $pv = 0;
                $pc = 0;
                $util = 0;
                $sql2 = pg_query("select * from detalle_factura_venta,productos where detalle_factura_venta.cod_productos=productos.cod_productos and id_factura_venta='$row1[0]'");
                while ($row2 = pg_fetch_row($sql2)) {
                    $pv = $pv + ($row2[6]);
                    $pc = $pc + ($row2[3] * $row2[15]);
                    $util = $util + (($row2[6]) - ($row2[3] * $row2[15]));
                }
                $pdf->SetX(1);
                $pdf->SetFont('helvetica', '', 9);
                $pdf->Cell(40, 6, maxCaracter($row1[5], 30), 0, 0, 'C', false);
                $pdf->Cell(40, 6, utf8_decode("Factura"), 0, 0, 'C', false);
                $pdf->Cell(26, 6, number_format($pv, 2, ',', '.'), 0, 0, 'R', false);
                $pdf->Cell(26, 6, number_format($pc, 2, ',', '.'), 0, 0, 'R', false);
                $pdf->Cell(25, 6, number_format($util, 2, ',', '.'), 0, 0, 'R', false);
                $pdf->Cell(25, 6, utf8_decode($row1[6]), 0, 0, 'C', false);
                $pdf->Cell(25, 6, utf8_decode($row1[10]), 0, 1, 'C', false);
                $sub += $util;
            }
            $pdf->Ln(2);
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->Cell(133, 6, utf8_decode('Total Utilidad por Factura'), 0, 0, 'R', 0);
            $pdf->Cell(25, 6, (number_format($sub, 2, ',', '.')), 0, 1, 'R', 0);
            $total += $sub;
        }
    }
    $pdf->Ln(2);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(133, 6, utf8_decode('Total'), 0, 0, 'R', 0);
    $pdf->Cell(25, 6, (number_format($total, 2, ',', '.')), 0, 1, 'R', 0);
}
$pdf->Output();
