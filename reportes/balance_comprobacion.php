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
        $this->Cell(105, 5, "BALANCES", 0, 1, 'C', 0);
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
        $this->Cell(210, 5, utf8_decode("BALANCE DE COMPROBACION"), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->Ln(3);
        $this->SetX(2);
        $this->SetFont('Helvetica', 'B', 9);
        $this->SetFillColor(175, 215, 240);
        $this->Cell(106, 6, utf8_decode(''), 0, 0, 'C', 0);
        $this->Cell(50, 6, utf8_decode('Sumas'), 1, 0, 'C', 1);
        $this->Cell(50, 6, utf8_decode('Saldos'), 1, 1, 'C', 1);
        $this->SetX(2);
        $this->Cell(30, 6, utf8_decode('Código'), 1, 0, 'C', 1);
        $this->Cell(76, 6, utf8_decode('Cta. Contable'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('Debe'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('Haber'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('Deudor'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('Acreedor'), 1, 1, 'C', 1);
        $this->Ln(1);
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
$pdf->SetTitle('Balance de Comprobacion');
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

$total = 0;
$sub = 0;
$contador = 0;
$debe = 0;
$haber = 0;
$saldo = 0;
$tot_debe = 0;
$tot_haber = 0;

$tot_Deudor = 0;
$tot_Acreedor = 0;
$conpunto = 1;
$consultapunto = pg_query("select max(id_punto_venta_empresa) from punto_venta_empresa where punto_venta_empresa.id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($consultapunto)) {
    $conpunto = $row[0];
}
$conpuntoresult = 1;
$consultapuntoresult = pg_query("select id_punto_venta from punto_venta_empresa where id_punto_venta_empresa='$conpunto'");
while ($row = pg_fetch_row($consultapuntoresult)) {
    $conpuntoresult = $row[0];
}
//$pdf->SetFont('Arial','',9);
$sql = pg_query("select id_plan_cuentas, codigo_plan, descripcion from plan_cuentas where cuenta='M' and estado='Activo' order by codigo_plan");
while ($row = pg_fetch_row($sql)) {
    $sql1 = pg_query("select t.fecha_registro, t.concepto, round(dt.debito,2) debito, round(dt.credito,2) from transacciones t, detalle_transaccion dt where dt.id_transacciones=t.id_transacciones and t.fecha_registro $query_fecha '$_GET[fin]' and t.estado='Activo' and dt.id_plan_cuentas=$row[0] and t.id_empresa='$conpuntoresult' order by t.fecha_registro");
    $debe = 0;
    $haber = 0;

    while ($row1 = pg_fetch_row($sql1)) {
        $debe = $debe + $row1[2];
        $haber = $haber + $row1[3];
    }
    $tot_debe = $tot_debe + $debe;
    $tot_haber = $tot_haber + $haber;


    if ($debe != 0 || $haber != 0) {
        $pdf->SetX(1);
        $pdf->Cell(30, 6, utf8_decode($row[1]), 0, 0, 'L', false);
        $pdf->SetFont('Amble-Regular', '', 8);
        $pdf->Cell(76, 6, utf8_decode($row[2]), 0, 0, 'L', false);
        $pdf->SetFont('Amble-Regular', '', 9);
        $pdf->Cell(25, 6, number_format($debe, 2, '.', ''), 0, 0, 'R', false);
        $pdf->Cell(25, 6, number_format($haber, 2, '.', ''), 0, 0, 'R', false);
        if ($debe > $haber) {
            $saldoA = $debe - $haber;
            $saldoB = 0.00;
            $pdf->Cell(25, 6, number_format($saldoA, 2, '.', ''), 0, 0, 'R', false);
            $pdf->Cell(25, 6, number_format($saldoB, 2, '.', ''), 0, 0, 'R', false);
            $tot_Deudor = $tot_Deudor + $saldoA;
            $tot_Acreedor = $tot_Acreedor + $saldoB;
        } elseif ($debe < $haber) {
            $saldoB = $haber - $debe;
            $saldoA = 0.00;
            $pdf->Cell(25, 6, number_format($saldoA, 2, '.', ''), 0, 0, 'R', false);
            $pdf->Cell(25, 6, number_format($saldoB, 2, '.', ''), 0, 0, 'R', false);
            $tot_Deudor = $tot_Deudor + $saldoA;
            $tot_Acreedor = $tot_Acreedor + $saldoB;
        } elseif ($debe == $haber) {
            $saldoB = $debe - $haber;
            $saldoA = $debe - $haber;
            $pdf->Cell(25, 6, number_format($saldoA, 2, '.', ''), 0, 0, 'R', false);
            $pdf->Cell(25, 6, number_format($saldoB, 2, '.', ''), 0, 0, 'R', false);
            $tot_Deudor = $tot_Deudor + $saldoA;
            $tot_Acreedor = $tot_Acreedor + $saldoB;
        }


        $pdf->Ln(5);
    }
}
$pdf->Ln(2);
$pdf->SetFont('Helvetica', 'B', 9);
$pdf->Cell(210, 0, utf8_decode(''), 1, 1, 'R', 0);
$pdf->Cell(107, 6, utf8_decode('Total:'), 0, 0, 'R', 0);
$pdf->Cell(25, 6, (number_format($tot_debe, 2, ',', '.')), 0, 0, 'R', 0);
$pdf->Cell(25, 6, (number_format($tot_haber, 2, ',', '.')), 0, 0, 'R', 0);
$pdf->Cell(25, 6, (number_format($tot_Deudor, 2, ',', '.')), 0, 0, 'R', 0);
$pdf->Cell(25, 6, (number_format($tot_Acreedor, 2, ',', '.')), 0, 0, 'R', 0);
$pdf->Output();
