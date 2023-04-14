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
        $this->Cell(210, 5, utf8_decode("RESUMEN CUENTAS POR CADUCIDAD"), 0, 1, 'C', 0);
        $this->SetFont('Amble-Regular', '', 10);
        $this->Ln(8);
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
$pdf->SetTitle('Caducidad ' . $_GET['tipo']);
$pdf->AliasNbPages();
$pdf->SetFont('Amble-Regular', '', 9);

$tot30 = 0;
$tot60 = 0;
$tot90 = 0;
$tot120 = 0;
$totmas = 0;
$total = 0;

$pdf->SetX(1);
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(216, 216, 231);
$proveedor = pg_fetch_assoc(pg_query("SELECT id_proveedor, identificacion_pro, empresa_pro FROM proveedores WHERE id_proveedor=$_GET[id]"));
$pdf->Cell(208, 8, utf8_decode("PROVEEDOR:  $proveedor[empresa_pro]  RUC:  $proveedor[identificacion_pro]"), 1, 0, 'C', true);
$pdf->Ln(9); 
$pdf->SetX(1);
$pdf->Cell(27, 6, utf8_decode('Comprobante'), 1, 0, 'C', 0);
$pdf->Cell(30, 6, utf8_decode('Fecha Emision'), 1, 0, 'C', 0);
$pdf->Cell(30, 6, utf8_decode('Fecha Vence'), 1, 0, 'C', 0);
$pdf->Cell(20, 6, utf8_decode('1-30'), 1, 0, 'C', 0);
$pdf->Cell(20, 6, utf8_decode('31-60'), 1, 0, 'C', 0);
$pdf->Cell(20, 6, utf8_decode('61-90'), 1, 0, 'C', 0);
$pdf->Cell(20, 6, utf8_decode('91-120'), 1, 0, 'C', 0);
$pdf->Cell(20, 6, utf8_decode('más de 120'), 1, 0, 'C', 0);
$pdf->Cell(21, 6, utf8_decode('Saldo'), 1, 1, 'C', 0);
$pdf->SetFont('Amble-Regular', '', 9);
$pdf->SetX(1);

//CUENTAS EXTERNAS E INTERNAS
if ($_GET['tipo'] == 'Externas') {
    //Tipo de consulta Cancelados, Caducados, Entre fechas
    $query = pg_query(
        "SELECT id_c_pagarexternas, comprobante, fecha_actual, fecha_emicion, 
        case when (fecha_emicion::date - fecha_actual::date)>=0 
        and (fecha_emicion::date - fecha_actual::date)<=30 then saldo else '' end,
        case when (fecha_emicion::date - fecha_actual::date)>30 
        and (fecha_emicion::date - fecha_actual::date)<=60 then saldo else '' end, 
        case when (fecha_emicion::date - fecha_actual::date)>60 
        and (fecha_emicion::date - fecha_actual::date)<=90 then saldo else '' end,
        case when (fecha_emicion::date - fecha_actual::date)>90 
        and (fecha_emicion::date - fecha_actual::date)<=120 then saldo else '' end, 
        case when (fecha_emicion::date - fecha_actual::date)>120 then saldo else '' end, saldo
        FROM c_pagarexternas inner join proveedores p using(id_proveedor)
        where p.id_proveedor=$proveedor[id_proveedor] and  c_pagarexternas.id_empresa='$_SESSION[PV]' order by id_c_pagarexternas"
    );

    if (pg_num_rows($query)) {
        $sub = 0;
        while ($row = pg_fetch_row($query)) {
            $pdf->SetX(1);
            $pdf->Cell(27, 6, utf8_decode($row[1]), 0, 0, 'C', 0);
            $pdf->Cell(30, 6, maxCaracter(utf8_decode($row[2]), 30), 0, 0, 'C', 0);
            $pdf->Cell(30, 6, maxCaracter(utf8_decode($row[3]), 30), 0, 0, 'C', 0);
            $pdf->Cell(20, 6, utf8_decode(number_format($row[4], 2, ',', '.')), 0, 0, 'R', 0);
            $pdf->Cell(20, 6, utf8_decode(number_format($row[5], 2, ',', '.')), 0, 0, 'R', 0);
            $pdf->Cell(20, 6, utf8_decode(number_format($row[6], 2, ',', '.')), 0, 0, 'R', 0);
            $pdf->Cell(20, 6, utf8_decode(number_format($row[7], 2, ',', '.')), 0, 0, 'R', 0);
            $pdf->Cell(20, 6, utf8_decode(number_format($row[8], 2, ',', '.')), 0, 1, 'R', 0);
            $pdf->Cell(21, 6, utf8_decode(number_format($row[9], 2, ',', '.')), 0, 1, 'R', 0);
            $tot30 += $row[4];
            $tot60 += $row[5];
            $tot90 += $row[6];
            $tot120 += $row[7];
            $totmas += $row[8];
            $total += $row[9];
        }
    }
} else {
    $query = pg_query(
        "SELECT id_pagos_compra, num_serie, pc.fecha_credito, pc.fecha_dias, 
        case when (fecha_dias::date - fecha_credito::date)>=0 
        and (fecha_dias::date - fecha_credito::date)<=30 then saldo else 0 end,
        case when (fecha_dias::date - fecha_credito::date)>30 
        and (fecha_dias::date - fecha_credito::date)<=60 then saldo else 0 end, 
        case when (fecha_dias::date - fecha_credito::date)>60 
        and (fecha_dias::date - fecha_credito::date)<=90 then saldo else 0 end,
        case when (fecha_dias::date - fecha_credito::date)>90 
        and (fecha_dias::date - fecha_credito::date)<=120 then saldo else 0 end, 
        case when (fecha_dias::date - fecha_credito::date)>120 then saldo else 0 end, saldo
        FROM pagos_compra pc inner join proveedores p using(id_proveedor)
        inner join factura_compra fc using(id_factura_compra) 
        where c.id_proveedor=$proveedor[id_proveedor]  fc.id_empresa='$_SESSION[PV]' order by pc.id_pagos_compra"
    );
    if (pg_num_rows($query)) {
        $sub = 0;
        while ($row = pg_fetch_row($query)) {
            $pdf->SetX(1);
            $pdf->Cell(27, 6, utf8_decode($row[1]), 0, 0, 'C', 0);
            $pdf->Cell(30, 6, maxCaracter(utf8_decode($row[2]), 30), 0, 0, 'C', 0);
            $pdf->Cell(30, 6, maxCaracter(utf8_decode($row[3]), 30), 0, 0, 'C', 0);
            $pdf->Cell(20, 6, utf8_decode($row[4]), 0, 0, 'R', 0);
            $pdf->Cell(20, 6, utf8_decode($row[5]), 0, 0, 'R', 0);
            $pdf->Cell(20, 6, utf8_decode($row[6]), 0, 0, 'R', 0);
            $pdf->Cell(20, 6, utf8_decode($row[7]), 0, 0, 'R', 0);
            $pdf->Cell(20, 6, utf8_decode($row[8]), 0, 0, 'R', 0);
            $pdf->Cell(21, 6, utf8_decode(number_format($row[9], 2, ',', '.')), 0, 1, 'R', 0);
            $tot30 += $row[4];
            $tot60 += $row[5];
            $tot90 += $row[6];
            $tot120 += $row[7];
            $totmas += $row[8];
            $total += $row[9];
        }
    }
}

$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
$pdf->Cell(88, 6, utf8_decode("Total Saldos:"), 0, 0, 'R', 0);
$pdf->Cell(20, 6, maxCaracter((number_format($tot30, 2, ',', '.')), 10), 0, 0, 'R', 0);
$pdf->Cell(20, 6, maxCaracter((number_format($tot60, 2, ',', '.')), 10), 0, 0, 'R', 0);
$pdf->Cell(20, 6, maxCaracter((number_format($tot90, 2, ',', '.')), 10), 0, 0, 'R', 0);
$pdf->Cell(20, 6, maxCaracter((number_format($tot120, 2, ',', '.')), 10), 0, 0, 'R', 0);
$pdf->Cell(20, 6, maxCaracter((number_format($totmas, 2, ',', '.')), 10), 0, 0, 'R', 0);
$pdf->Cell(21, 6, maxCaracter((number_format($total, 2, ',', '.')), 10), 0, 1, 'R', 0);
$pdf->Output();
