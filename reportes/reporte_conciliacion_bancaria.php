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
        $this->Cell(105, 6, $fecha, 0, 0, 'C', 0);
        $this->Cell(105, 6, "BANCOS", 0, 1, 'C', 0);    
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(210, 8, $_SESSION['nombre_empresa'], 0, 1, 'C', 0);
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
        $this->Cell(210, 5, utf8_decode("CONCILIACION BANCARIA"), 0, 1, 'C', 0);
        $this->SetFont('helvetica', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        // $this->Ln(3);
        // $this->SetFont('helvetica', 'B', 8.5);
        // $this->SetFillColor(175, 215, 240);
        // $this->SetX(0);
        // $this->Cell(10, 6, utf8_decode('TIPO'), 1, 0, 'C', 1);
        // $this->Cell(27.5, 6, utf8_decode('COMPROBANTE'), 1, 0, 'C', 1);
        // $this->Cell(22.5, 6, utf8_decode('FECHA'), 1, 0, 'C', 1);
        // $this->Cell(50, 6, utf8_decode('BENEFICIARIO'), 1, 0, 'C', 1);
        // $this->Cell(85, 6, utf8_decode('DETALLE'), 1, 0, 'C', 1);
        // $this->Cell(15, 6, utf8_decode('VALOR'), 1, 1, 'C', 1);
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
$pdf->SetTitle('Conciliacion');
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->AliasNbPages();

$query_fecha = "";
$query = "";
// RANGO DE FECHAS O FECHA ACTUAL
if ($pdf->rango) {
    $query_fecha = "BETWEEN '$_GET[inicio]' AND";
} else {
    $query_fecha = "=";
}
if ($_GET['cuenta'] != '0') {
    $query = pg_query(
        "SELECT id_cuenta_banco, codigo_plan, descripcion, numero_cuenta FROM cuentas_bancos b 
        INNER JOIN plan_cuentas c USING(id_plan_cuentas) 
        WHERE id_cuenta_banco=$_GET[cuenta];"
    );
} else {
    $query = pg_query(
        "SELECT id_cuenta_banco, codigo_plan, descripcion, numero_cuenta FROM cuentas_bancos b 
        INNER JOIN plan_cuentas c USING(id_plan_cuentas);"
    );
}
if (pg_num_rows($query)) {
    $total = 0;
    while ($row = pg_fetch_row($query)) {
        $pdf->SetX(0);
        $pdf->SetFillColor(220, 240, 210);
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(70, 7, utf8_decode($row[1]), 0, 0, 'R', true);
        $pdf->Cell(70, 7, utf8_decode($row[2]), 0, 0, 'C', true);
        $pdf->Cell(70, 7, utf8_decode($row[3]), 0, 1, 'L', true);
        $query = pg_query(
            "SELECT * FROM conciliacion_bancaria
            WHERE id_cuenta_banco=$row[0]
            AND fecha_actual $query_fecha '$_GET[fin]';"
        );
        if (pg_num_rows($query)) {
            while ($row = pg_fetch_row($query)) {
                $pdf->SetFillColor(220, 240, 210);
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->Cell(105, 8, utf8_decode("SALDO SEGUN LIBROS: " . number_format($row[4], 2, ',', '.')), 0, 0, 'C', true);
                // DETALLES
                // $query = pg_query(
                //     "SELECT tipo, comprobante, fecha, beneficiario, detalle, 1;"
                // );
                // if (pg_num_rows($query)) {
                //     $sub = 0;
                //     while ($row = pg_fetch_row($query)) {
                //         $pdf->SetFont('helvetica', '', 8);
                //         $pdf->Cell(10, 6, utf8_decode('TIPO' . $row[0]), 1, 0, 'C', 1);
                //         $pdf->Cell(27.5, 6, utf8_decode('COMPROBANTE' . $row[1]), 1, 0, 'C', 1);
                //         $pdf->Cell(22.5, 6, utf8_decode('FECHA' . $row[2]), 1, 0, 'C', 1);
                //         $pdf->Cell(50, 6, utf8_decode('BENEFICIARIO' . $row[3]), 1, 0, 'L', 1);
                //         $pdf->Cell(85, 6, utf8_decode('DETALLE' . $row[4]), 1, 0, 'L', 1);
                //         $pdf->Cell(15, 6, number_format($row[5], 2, ',', '.'), 1, 1, 'C', 1);
                //         $sub += $row[5];
                //     }
                //     $total += $sub;
                //     $pdf->SetFont('helvetica', 'B', 8);
                //     $pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
                //     $pdf->Cell(185, 6, utf8_decode("Total Cuenta"), 0, 0, 'R', 0);
                //     $pdf->Cell(15, 6, number_format($sub, 2, ',', '.'), 0, 0, 'C', 0);
                // }
                $pdf->SetX(5);
                $pdf->Cell(200, 6, utf8_decode('(+) DÉPOSITOS EN TRÁNSITO'), 1, 1, 'L', 0);
                $pdf->Ln(2);
                $depos = explode('**', $row[6]);
                $val_depos = explode('**', $row[7]);
                for ($i = 0; $i < count($depos); $i++) {
                    $pdf->SetX(10);
                    $pdf->Cell(145, 6, utf8_decode($depos[$i]), 0, 0, 'L', 0);
                    $pdf->Cell(25, 6, utf8_decode($val_depos[$i]), 0, 0, 'C', 0);
                    $pdf->Ln(5);
                }
                $pdf->Ln(2);
                $pdf->SetX(5);
                $pdf->Cell(200, 6, utf8_decode('(-) CH/. GIRADOS Y NO COBRADOS'), 1, 1, 'L', 0);
                $pdf->Ln(2);
                $cheques = explode('**', $row[8]);
                $val_cheques = explode('**', $row[9]);
                for ($i = 0; $i < count($cheques); $i++) {
                    $pdf->SetX(10);
                    $pdf->Cell(145, 6, utf8_decode($cheques[$i]), 0, 0, 'L', 0);
                    $pdf->Cell(25, 6, utf8_decode($val_cheques[$i]), 0, 0, 'C', 0);
                    $pdf->Ln(5);
                }
                $pdf->Ln(2);
                $pdf->SetX(5);
                $pdf->Cell(200, 6, utf8_decode('(+/-) OTROS'), 1, 1, 'L', 0);
                $pdf->Ln(2);
                $otros = explode('**', $row[10]);
                $val_otros = explode('**', $row[11]);
                for ($i = 0; $i < count($otros); $i++) {
                    $pdf->SetX(10);
                    $pdf->Cell(145, 6, utf8_decode($otros[$i]), 0, 0, 'L', 0);
                    $pdf->Cell(25, 6, utf8_decode($val_otros[$i]), 0, 0, 'C', 0);
                    $pdf->Ln(5);
                }
                $pdf->Ln(2);
                $pdf->SetX(5);
                $pdf->Cell(200, 6, utf8_decode('(+) VALORES ACRÉDITADOS'), 1, 1, 'L', 0);
                $pdf->Ln(2);
                $acred = explode('**', $row[12]);
                $val_acred = explode('**', $row[13]);
                for ($i = 0; $i < count($acred); $i++) {
                    $pdf->SetX(10);
                    $pdf->Cell(145, 6, utf8_decode($acred[$i]), 0, 0, 'L', 0);
                    $pdf->Cell(25, 6, utf8_decode(""), 0, 0, 'C', 0);
                    $pdf->Cell(25, 6, utf8_decode($val_acred[$i]), 0, 0, 'C', 0);
                    $pdf->Ln(5);
                }
                $pdf->Ln(2);
                $pdf->SetX(5);
                $pdf->Cell(200, 6, utf8_decode('(-) VALORES DEBITADOS'), 1, 1, 'L', 0);
                $pdf->Ln(2);
                $debi = explode('**', $row[14]);
                $val_debi = explode('**', $row[15]);
                for ($i = 0; $i < count($debi); $i++) {
                    $pdf->SetX(10);
                    $pdf->Cell(145, 6, utf8_decode($debi[$i]), 0, 0, 'L', 0);
                    $pdf->Cell(25, 6, utf8_decode(""), 0, 0, 'C', 0);
                    $pdf->Cell(25, 6, utf8_decode($val_debi[$i]), 0, 0, 'C', 0);
                    $pdf->Ln(5);
                }
                $pdf->Ln(2);
                $pdf->Cell(205, 0, utf8_decode(''), 1, 1, 'R', 0);
                $pdf->Cell(155, 6, utf8_decode('SALDOS:'), 0, 0, 'R', 0);
                $pdf->Cell(25, 6, (number_format($row[16], 2, ',', '.')), 0, 0, 'C', 0);
                $pdf->Cell(25, 6, (number_format($row[17], 2, ',', '.')), 0, 0, 'C', 0);

                $pdf->SetFillColor(220, 240, 210);
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->Cell(105, 8, utf8_decode("SALDO SEGUN BANCOS: " . number_format($row[5], 2, ',', '.')), 0, 1, 'C', true);
            }
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
            $pdf->Cell(185, 6, utf8_decode("Total"), 0, 0, 'R', 0);
            $pdf->Cell(15, 6, number_format($total, 2, ',', '.'), 0, 0, 'C', 0);
        }
    }
}
$pdf->Output();
