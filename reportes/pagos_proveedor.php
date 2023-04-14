<?php

require('../fpdf/fpdf.php');
include '../procesos/base.php';
include '../procesos/funciones.php';
conectarse();
date_default_timezone_set('America/Guayaquil');
session_start();
$query_punto = "";
if ($_GET['id_empre'] != '0') {
    $query_punto = "AND id_empresa='$_GET[id_empre]'";
}

$id_usuario_cp = "";
if ($_GET['id'] != '0') {
    $id_usuario_cp = "and id_usuario='$_GET[id]'";
}


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
        $this->Cell(105, 5, "CARTERA CxP", 0, 1, 'C', 0);
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
        $row = pg_fetch_row(pg_query("SELECT * from proveedores WHERE id_proveedor=$_GET[id_pro] order by id_proveedor asc;"));
        $this->Cell(210, 5, utf8_decode("PAGOS A: " . maxCaracter($row[2], 30)), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->Ln(3);
        $this->SetFillColor(187, 179, 180);
        $this->SetFont('Helvetica', 'B', 9);
        $this->Cell(70, 6, maxCaracter(utf8_decode('RUC/CI:' . $row[2]), 35), 0, 0, 'C', 1);
        $this->Cell(140, 6, maxCaracter(utf8_decode('NOMBRES:' .maxCaracter($row[3], 30)), 50), 0, 1, 'C', 1);
        $this->SetFillColor(175, 215, 240);
        $this->Cell(20, 6, utf8_decode('Comprob.'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('Tipo Doc.'), 1, 0, 'C', 1);
        $this->Cell(40, 6, utf8_decode('Nro Factura'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('Total'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('Fecha Pago'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('Forma Pago'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('Valor Pago'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('Saldo'), 1, 1, 'C', 1);
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
$pdf->SetTitle('Pagos Proveedor');
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
$cuenta;
$tipo;
if ($_GET['tipo'] == 'Externos') {
    $cuenta = "EXTERNA";
} else {
    $cuenta = "INTERNA";
}
$sql = pg_query(
    "SELECT comprobante, tipo_factura, num_factura, total_factura, fecha_actual, forma_pago, valor_pagado, saldo_factura  
    FROM pagos_pagar WHERE id_proveedor='$_GET[id_pro]' and estado='Activo' and tipo_pago='$cuenta'
    AND fecha_actual $query_fecha '$_GET[fin]' $query_punto $id_usuario_cp;"
);
if (pg_num_rows($sql)) {
    $sub = 0;
    $suba = 0;
    $subs = 0;
    while ($row = pg_fetch_row($sql)) {
        $pdf->SetX(1);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(19, 6, utf8_decode($row[0]), 0, 0, 'C', 0);
        $pdf->Cell(25, 6, utf8_decode($row[1]), 0, 0, 'C', 0);
        $pdf->Cell(40, 6, utf8_decode($row[2]), 0, 0, 'C', 0);
        $pdf->Cell(25, 6, number_format($row[3], 2, '.', ','), 0, 0, 'R', 0);
        $pdf->Cell(25, 6, utf8_decode($row[4]), 0, 0, 'C', 0);
        $pdf->Cell(25, 6, utf8_decode($row[5]), 0, 0, 'C', 0);
        $pdf->Cell(25, 6, number_format($row[6], 2, ',', '.'), 0, 0, 'R', 0);
        $pdf->Cell(25, 6, number_format($row[7], 2, ',', '.'), 0, 1, 'R', 0);
        $sub += $row[3];
        $suba += $row[6];
        $subs += $row[7];
    }
    $pdf->SetFont('Helvetica', 'B', 9);
    $pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
    $pdf->SetX(1);
    $pdf->Cell(84, 6, utf8_decode("Totales:"), 0, 0, 'R', 0);
    $pdf->Cell(25, 6, maxCaracter((number_format($sub, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(75, 6, maxCaracter((number_format($suba, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(25, 6, maxCaracter((number_format($subs, 2, ',', '.')), 20), 0, 1, 'R', 0);
}
$pdf->Output();