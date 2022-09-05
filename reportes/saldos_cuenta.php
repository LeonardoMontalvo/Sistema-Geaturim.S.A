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
        $this->Cell(105, 5, "CUENTAS POR " . strtoupper($_GET['id']), 0, 1, 'C', 0);
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
        $this->Cell(210, 5, utf8_decode('SALDOS POR ' . strtoupper($_GET['id'])), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->Ln(4);
        $this->SetX(1);
        $this->SetFont('helvetica', 'B', 8.5);
        $this->SetFillColor(175, 215, 240);
        $this->Cell(30, 6, utf8_decode('CODIGO'), 1, 0, 'C', 1);
        $this->Cell(28, 6, utf8_decode('CUENTA'), 1, 0, 'C', 1);
        $this->Cell(70, 6, utf8_decode('NOMBRE'), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode('SALDO ANT.'), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode('DEBE'), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode('HABER'), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode('SALDO'), 1, 1, 'C', 1);
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
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->SetTitle('Saldos por ' . ucfirst($_GET['id']));
$pdf->AliasNbPages();

$query_fecha = "";
// RANGO DE FECHAS O FECHA ACTUAL
if ($pdf->rango) {
    $query_fecha = "BETWEEN '$_GET[inicio]' AND";
} else {
    $query_fecha = "=";
}
$query_id = "";
if ($_GET['id'] == "cobrar") {
    $query_id = "'VEN')";
} else {
    $query_id = "'COM' OR t.identificador_cli_pro='GAS')";
}
$query = pg_query(
    "SELECT t.comprobante, c.codigo_plan, t.concepto, t.total_debe, t.total_haber, t.saldo 
    FROM plan_cuentas c
    INNER JOIN detalle_transaccion dt USING(id_plan_cuentas)
    INNER JOIN transacciones t USING(id_transacciones)
    WHERE t.estado='Activo' AND t.saldo!=0
    AND (t.identificador_cli_pro=$query_id
    AND t.fecha_registro $query_fecha '$_GET[fin]'
    ORDER BY t.comprobante ASC;"
);
if (pg_num_rows($query)) {
    $total_debe = 0;
    $total_haber = 0;
    $total_saldo_a = 0;
    $total_saldo = 0;
    while ($row = pg_fetch_row($query)) {
        $pdf->SetX(0);
        $pdf->SetFillColor(220, 240, 210);
        $pdf->SetFont('helvetica', 'B', 8);
        $concepto = explode(",", $row[2]);
        $comprobante = explode(":", $concepto[2]);
        $id = explode(":", $concepto[1]);
        if ($_GET['id'] == "cobrar") {
            $nombre = pg_fetch_row(pg_query("SELECT nombres_cli FROM clientes WHERE identificacion=$id[1]::text;"));
        } else {
            $nombre = pg_fetch_row(pg_query("SELECT empresa_pro FROM proveedores WHERE identificacion_pro=$id[1]::text;"));
        }
        $saldo_a = ($row[4] + $row[5]) - $row[3];
        $total_debe += $row[3];
        $total_haber += $row[4];
        $total_saldo += $row[5];
        $total_saldo_a += $saldo_a;
        $pdf->SetX(2);
        $pdf->SetFont('helvetica', '', 7.5);
        $pdf->Cell(28, 6, utf8_decode($comprobante[1]), 0, 0, 'C', 0);
        $pdf->Cell(28, 6, utf8_decode($row[1]), 0, 0, 'C', 0);
        $pdf->Cell(70, 6, maxCaracter(utf8_decode($nombre[0]), 37), 0, 0, 'L', 0);
        $pdf->Cell(20, 6, number_format($saldo_a, 2, ',', '.'), 0, 0, 'R', 0);
        $pdf->Cell(20, 6, number_format($row[3], 2, ',', '.'), 0, 0, 'R', 0);
        $pdf->Cell(20, 6, number_format($row[4], 2, ',', '.'), 0, 0, 'R', 0);
        $pdf->Cell(20, 6, number_format($row[5], 2, ',', '.'), 0, 1, 'R', 0);
    }
    $pdf->SetFont('helvetica', 'B', 8.5);
    $pdf->Cell(210, 0, utf8_decode(''), 1, 1, 'R', 1);
    $pdf->Cell(128, 6, utf8_decode('Totales:'), 0, 0, 'R', 0);
    $pdf->Cell(20, 6, (number_format($total_saldo_a, 2, ',', '.')), 0, 0, 'R', 0);
    $pdf->Cell(20, 6, (number_format($total_debe, 2, ',', '.')), 0, 0, 'R', 0);
    $pdf->Cell(20, 6, (number_format($total_haber, 2, ',', '.')), 0, 0, 'R', 0);
    $pdf->Cell(20, 6, (number_format($total_saldo, 2, ',', '.')), 0, 1, 'R', 0);

    $pdf->Ln(20);
    $pdf->SetX(7);
    $pdf->Cell(40, 0, utf8_decode('x'), 1, 1, 'R', 1);
    $pdf->SetX(44);
    $pdf->Cell(5, 0, "", 0, 0, 'R', 0);
    $pdf->SetX(57);
    $pdf->Cell(40, 0, utf8_decode('x'), 1, 1, 'R', 1);
    $pdf->SetX(94);
    $pdf->Cell(5, 0, "", 0, 0, 'R', 0);
    $pdf->SetX(107);
    $pdf->Cell(40, 0, utf8_decode('x'), 1, 1, 'R', 1);
    $pdf->SetX(144);
    $pdf->Cell(5, 0, "", 0, 0, 'R', 0);
    $pdf->SetX(157);
    $pdf->Cell(40, 0, utf8_decode('x'), 1, 1, 'R', 1);
    $pdf->Ln(4);
    $pdf->SetX(7);
    $pdf->Cell(40, 0, utf8_decode('Elaborado por: ' . $_SESSION['user']), 0, 0, 'C', 0);
    $pdf->SetX(44);
    $pdf->SetX(57);
    $pdf->Cell(40, 0, utf8_decode('Aprobado'), 0, 0, 'C', 0);
    $pdf->SetX(94);
    $pdf->SetX(107);
    $pdf->Cell(40, 0, utf8_decode('Contabilidad'), 0, 0, 'C', 0);
    $pdf->SetX(144);
    $pdf->SetX(157);
    $pdf->Cell(40, 0, utf8_decode('Recibí Conforme'), 0, 1, 'C', 0);
    $pdf->Ln(3);
    $pdf->SetX(157);
    $pdf->Cell(40, 0, utf8_decode('C.I.:'), 0, 1, 'L', 0);
}
$pdf->Output();
