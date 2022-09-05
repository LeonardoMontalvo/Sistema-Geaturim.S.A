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
        $this->AddFont('helvetica', 'B', 'helveticab.php');
        $this->SetFont('Amble-Regular', '', 10);
        $fecha = date('Y-m-d', time());
        $this->SetX(0);
        $this->SetY(0);
        $this->Cell(105, 5, $fecha, 0, 0, 'C', 0);
        $this->Cell(105, 5, "CONTABILIDAD", 0, 1, 'C', 0);
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
        $this->Line(0, 30, 210, 30);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(210, 5, utf8_decode('ESTADOS DEL CLIENTE'), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->SetFont('helvetica', 'B', 9);
        $row = pg_fetch_row(pg_query("select * from clientes where id_cliente='$_GET[id]' order by id_cliente asc;"));
        $this->Cell(105, 6, maxCaracter(utf8_decode('RUC/CI:' . $row[2]), 35), 0, 0, 'C', 0);
        $this->Cell(105, 6, maxCaracter(utf8_decode('NOMBRES:' . $row[3]), 50), 0, 1, 'C', 0);
        $this->Ln(3);
        $this->SetFont('helvetica', 'B', 8.5);
        $this->SetFillColor(175, 215, 240);
        $this->Cell(30, 6, utf8_decode('DOCUMENTO'), 1, 0, 'C', 1);
        $this->Cell(40, 6, utf8_decode('NÚMERO'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('CREACIÓN'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('VENCIMIENTO'), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode('TOTAL'), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode('ABONOS'), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode('SALDO'), 1, 0, 'C', 1);
        $this->Cell(30, 6, utf8_decode('ESTADO'), 1, 1, 'C', 1);
        $this->SetFillColor(255, 255, 225);
        $this->SetLineWidth(0.2);
        $this->SetX(1);
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
$pdf->SetTitle('Estado de Cuenta');
$pdf->AliasNbPages();

if ($pdf->rango) {
    $query_fecha = "BETWEEN '$_GET[inicio]' AND";
} else {
    $query_fecha = "=";
}
$query_punto="";

if ($_GET['id_empre'] != '0') {
    $query_punto = "AND c.id_empresa='$_GET[id_empre]'";
}
$total = 0;
$abono = 0;
$saldo = 0;

$consulta1 = pg_query(
    "SELECT t.descripcion, num_factura, fecha_emicion, fecha_actual, total, (total::numeric-saldo::numeric), saldo, c.estado 
    from c_cobrarexternas c INNER JOIN tipo_comprobante t ON c.tipo_documento=t.id_tipo_comprobante 
    where id_cliente='$_GET[id]' 
    and fecha_actual $query_fecha '$_GET[fin]' $query_punto
    order by id_c_cobrarexternas asc"
);

$pdf->SetFillColor(187, 179, 180);
$pdf->Ln(1);
if (pg_num_rows($consulta1)) {
    $sub = 0;
    $suba = 0;
    $subs = 0;
    while ($row1 = pg_fetch_row($consulta1)) {
        $pdf->SetX(1);
        $pdf->SetFont('helvetica', '', 8.5);
        $pdf->Cell(29, 6, utf8_decode($row1[0]), 0, 0, 'C', 0);
        $pdf->Cell(40, 6, utf8_decode($row1[1]), 0, 0, 'C', 0);
        $pdf->Cell(25, 6, utf8_decode($row1[2]), 0, 0, 'C', 0);
        $pdf->Cell(25, 6, utf8_decode($row1[3]), 0, 0, 'C', 0);
        $pdf->Cell(20, 6, number_format($row1[4], 2, ',', '.'), 0, 0, 'R', 0);
        $pdf->Cell(20, 6, number_format($row1[5], 2, ',', '.'), 0, 0, 'R', 0);
        $pdf->Cell(20, 6, number_format($row1[6], 2, ',', '.'), 0, 0, 'R', 0);
        $pdf->Cell(30, 6, utf8_decode($row1[7]), 0, 1, 'C', 0);
        $sub += $row1[4];
        $suba += $row1[5];
        $subs += $row1[6];
    }
    $total += $sub;
    $abono += $suba;
    $saldo += $subs;
    $pdf->SetX(0);
    $pdf->SetFont('helvetica', '', 8.5);
    $pdf->Cell(210, 0, utf8_decode(''), 1, 1, 'R', 0);
    $pdf->Cell(120, 6, utf8_decode('Total Externas'), 0, 0, 'R', 0);
    $pdf->Cell(20, 6, number_format($sub, 2, ',', '.'), 0, 0, 'R', 0);
    $pdf->Cell(20, 6, number_format($suba, 2, ',', '.'), 0, 0, 'R', 0);
    $pdf->Cell(20, 6, number_format($subs, 2, ',', '.'), 0, 1, 'R', 0);
}

$consulta2 = pg_query(
    "SELECT tipo_documento, num_serie, fecha_credito, fecha_caducidad, monto_credito, (monto_credito::numeric - saldo::numeric) as abonos, saldo, c.estado 
    FROM pagos_venta c 
    LEFT JOIN factura_venta f USING(id_factura_venta) 
    WHERE c.id_cliente='$_GET[id]'
    AND c.fecha_credito $query_fecha '$_GET[fin]' $query_punto  
    ORDER BY id_pagos_venta;"
);
if (pg_num_rows($consulta2)) {
    $sub = 0;
    $suba = 0;
    $subs = 0;
    while ($row1 = pg_fetch_row($consulta2)) {
        $pdf->SetX(0);
        $pdf->SetFont('helvetica', '', 8.5);
        $pdf->Cell(30, 6, utf8_decode($row1[0]), 0, 0, 'C', 0);
        $pdf->Cell(40, 6, utf8_decode($row1[1]), 0, 0, 'C', 0);
        $pdf->Cell(25, 6, utf8_decode($row1[2]), 0, 0, 'C', 0);
        $pdf->Cell(25, 6, utf8_decode($row1[3]), 0, 0, 'C', 0);
        $pdf->Cell(20, 6, number_format($row1[4], 2, ',', '.'), 0, 0, 'C', 0);
        $pdf->Cell(20, 6, number_format($row1[5], 2, ',', '.'), 0, 0, 'C', 0);
        $pdf->Cell(20, 6, number_format($row1[6], 2, ',', '.'), 0, 0, 'C', 0);
        $pdf->Cell(30, 6, utf8_decode($row1[7]), 0, 1, 'C', 0);
        $sub += $row1[4];
        $suba += $row1[5];
        $subs += $row1[6];
    }
    $total += $sub;
    $abono += $suba;
    $saldo += $subs;
    $pdf->SetX(0);
    $pdf->SetFont('helvetica', '', 8.5);
    $pdf->Cell(210, 0, utf8_decode(''), 1, 1, 'R', 0);
    $pdf->Cell(120, 6, utf8_decode('Total Internas'), 0, 0, 'R', 0);
    $pdf->Cell(20, 6, number_format($sub, 2, ',', '.'), 0, 0, 'C', 0);
    $pdf->Cell(20, 6, number_format($suba, 2, ',', '.'), 0, 0, 'C', 0);
    $pdf->Cell(20, 6, number_format($subs, 2, ',', '.'), 0, 1, 'C', 0);
}
////total final///  
$pdf->SetX(0);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->Cell(210, 0, utf8_decode(''), 1, 1, 'R', 1);
$pdf->Cell(120, 6, utf8_decode("Totales"), 0, 0, 'R', 0);
$pdf->Cell(20, 6, number_format($total, 2, ',', '.'), 0, 0, 'C', 0);
$pdf->Cell(20, 6, number_format($abono, 2, ',', '.'), 0, 0, 'C', 0);
$pdf->Cell(20, 6, number_format($saldo, 2, ',', '.'), 0, 1, 'C', 0);

$pdf->Output();
