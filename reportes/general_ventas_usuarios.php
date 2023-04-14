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

    function SetAlings($a)
    {
        $this->aligns = $a;
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
        $this->Cell(210, 8, utf8_decode($_SESSION['nombre_empresa']), 0, 1, 'C', 0);
        $this->Image('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"], 10, 7, 15, 15);
        $this->Image('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"], 180, 7, 15, 15);
        // $this->Cell(190, 5, "PROPIETARIO: " . utf8_decode($_SESSION['propietario']), 0, 1, 'C', 0);
        // $this->Cell(80, 5, "TEL.: " . utf8_decode($_SESSION['telefono']), 0, 0, 'R', 0);
        // $this->Cell(80, 5, "CEL.: " . utf8_decode($_SESSION['celular']), 0, 1, 'C', 0);
        // $this->Cell(190, 5, "DIR.: " . utf8_decode($_SESSION['direccion']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, "SLOGAN.: " . utf8_decode($_SESSION['slogan']), 0, 1, 'C', 0);
        // $this->Cell(190, 5, utf8_decode($_SESSION['pais_ciudad']), 0, 1, 'C', 0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);
        $this->Line(0, 30, 210, 30);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(210, 5, utf8_decode('VENTAS POR USUARIO'), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->usuario = pg_fetch_row(pg_query("SELECT ci_usuario, nombre_usuario FROM usuario WHERE id_usuario={$_GET['id']}"));
        $this->Cell(105, 6, maxCaracter(utf8_decode('RUC/CI:' . $this->usuario[0]), 35), 0, 0, 'C', 0);
        $this->Cell(105, 6, maxCaracter(utf8_decode('NOMBRES:' . $this->usuario[1]), 50), 0, 1, 'C', 0);
        $this->Ln(2);
        $this->SetX(1);
        $this->SetFillColor(175, 215, 240);
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(27, 6, utf8_decode('NÚMERO'), 1, 0, 'C', 1);
        $this->Cell(27, 6, utf8_decode('TIPO'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('FECHA'), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode('PAGO'), 1, 0, 'C', 1);
        $this->Cell(27, 6, utf8_decode('0%'), 1, 0, 'C', 1);
        $this->Cell(27, 6, utf8_decode('12%'), 1, 0, 'C', 1);
        $this->Cell(27, 6, utf8_decode('IVA'), 1, 0, 'C', 1);
        $this->Cell(27, 6, utf8_decode('TOTAL'), 1, 1, 'C', 1);
        $this->Ln(2);
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
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->SetTitle('Ventas ' . $pdf->usuario[1]);
$pdf->AliasNbPages();

$total = 0;
$sub = 0;
$desc = 0;
$ivaT = 0;
$t0 = 0;
$t12 = 0;
$query_fecha = "";
// RANGO DE FECHAS O FECHA ACTUAL
if ($pdf->rango) {
    $query_fecha = "BETWEEN '$_GET[inicio]' AND";
} else {
    $query_fecha = "=";
}
$consulta1 = pg_query(
    "(
        SELECT num_factura AS comprobante, 'FV' AS tipo_doc, fecha_actual, forma_pago, tarifa0, tarifa12, iva_venta, total_venta, estado 
        FROM factura_venta 
        WHERE id_usuario='$_GET[id]' AND fecha_actual $query_fecha '$_GET[fin]' 
        ORDER BY id_factura_venta asc
    ) 
    UNION ALL
    (
        SELECT (concat('0', comprobante)), 'NV' AS tipo_doc, fecha_actual, forma_pago, tarifa0, tarifa12, iva_venta, total_venta, estado 
        FROM facturas_novalidas 
        WHERE id_usuario='$_GET[id]' AND fecha_actual $query_fecha '$_GET[fin]' 
        ORDER BY id_facturas_novalidas asc
    )"
);
while ($row1 = pg_fetch_row($consulta1)) {
    if ($row1[8] == "Activo") {
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetX(1);
        $pdf->Cell(27, 6, utf8_decode($row1[0]), 0, 0, 'C', 0);
        $pdf->Cell(27, 6, utf8_decode($row1[1]), 0, 0, 'C', 0);
        $pdf->Cell(25, 6, utf8_decode(($row1[2])), 0, 0, 'C', 0);
        $pdf->Cell(20, 6, utf8_decode($row1[3]), 0, 0, 'C', 0);
        $pdf->Cell(27, 6, number_format($row1[4], 2, ',', '.'), 0, 0, 'C', 0);
        $pdf->Cell(27, 6, number_format($row1[5], 2, ',', '.'), 0, 0, 'C', 0);
        $pdf->Cell(27, 6, number_format($row1[6], 2, ',', '.'), 0, 0, 'C', 0);
        $pdf->Cell(27, 6, number_format($row1[7], 2, ',', '.'), 0, 0, 'C', 0);
        $t0 += $row1[4];
        $t12 += $row1[5];
        $ivaT += $row1[6];
        $total += $row1[7];
        $pdf->Ln(6);
    } else {
        if ($row1[8] == "Pasivo") {
            $pdf->SetTextColor(208, 17, 52);
            $pdf->SetX(1);
            $pdf->Cell(27, 6, utf8_decode($row1[0]), 0, 0, 'C', 0);
            $pdf->Cell(27, 6, utf8_decode($row1[1]), 0, 0, 'C', 0);
            $pdf->Cell(25, 6, utf8_decode(($row1[2])), 0, 0, 'C', 0);
            $pdf->Cell(20, 6, utf8_decode($row1[3]), 0, 0, 'C', 0);
            $pdf->Cell(27, 6, number_format($row1[4], 2, ',', '.'), 0, 0, 'C', 0);
            $pdf->Cell(27, 6, number_format($row1[5], 2, ',', '.'), 0, 0, 'C', 0);
            $pdf->Cell(27, 6, number_format($row1[6], 2, ',', '.'), 0, 0, 'C', 0);
            $pdf->Cell(27, 6, number_format($row1[7], 2, ',', '.'), 0, 0, 'C', 0);
            $pdf->Ln(6);
        }
    }
}

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetX(1);
$pdf->Cell(207, 0, utf8_decode(""), 1, 1, 'R', 0);
$pdf->Cell(100, 6, utf8_decode("Totales"), 0, 0, 'R', 0);
$pdf->Cell(27, 6, maxCaracter((number_format($t0, 2, ',', '.')), 20), 0, 0, 'C', 0);
$pdf->Cell(27, 6, maxCaracter((number_format($t12, 2, ',', '.')), 20), 0, 0, 'C', 0);
$pdf->Cell(27, 6, maxCaracter((number_format($ivaT, 2, ',', '.')), 20), 0, 0, 'C', 0);
$pdf->Cell(27, 6, maxCaracter((number_format($total, 2, ',', '.')), 20), 0, 0, 'C', 0);
$pdf->Ln(8);
$pdf->Output();
