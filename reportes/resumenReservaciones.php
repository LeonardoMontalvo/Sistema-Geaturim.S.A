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
        $this->Cell(105, 5, "RESERVACIONES", 0, 1, 'C', 0);
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
        $this->Cell(210, 5, utf8_decode("RESUMEN DE RESERVACIONES"), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->Ln(3);
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
$pdf->SetTitle('Reservaciones');
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

$consulta1 = pg_query(
    "SELECT c.identificacion,c.nombres_cli,r.fecha_actual,r.hora_actual, r.total, r.id_reservacion from clientes c, reservaciones r 
    WHERE r.id_cliente=c.id_cliente and r.fecha_actual $query_fecha '$_GET[fin]' order by r.id_reservacion asc"
);

if (pg_num_rows($consulta1)) {
    $total = 0;
    $sub = 0;
    $desc = 0;
    $ivaT = 0;
    while ($row1 = pg_fetch_row($consulta1)) {
        $pdf->SetX(1);
        $pdf->SetFillColor(187, 179, 180);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(20, 6, maxCaracter(utf8_decode('RES-' . $row1[5]), 35), 1, 0, 'L', 1);
        $pdf->Cell(40, 6, maxCaracter(utf8_decode('RUC/CI: ' . $row1[0]), 35), 1, 0, 'L', 1);
        $pdf->Cell(85, 6, maxCaracter(utf8_decode($row1[1]), 50), 1, 0, 'L', 1);
        $pdf->Cell(30, 6, utf8_decode('FECHA: ' . $row1[2]), 1, 0, 'L', 1);
        $pdf->Cell(30, 6, utf8_decode('TOTAL: ' . number_format($row1[4], 2, '.', '')), 1, 1, 'L', 1);
        $pdf->Ln(3);
        $consulta = pg_query(
            "SELECT d.cantidad, p.articulo, d.precio_venta, d.total_venta from detalle_reservacion d, productos p 
            WHERE d.id_reservacion='$row1[5]' and d.cod_productos=p.cod_productos"
        );
        if (pg_num_rows($consulta)) {
            while ($row = pg_fetch_row($consulta)) {
                $pdf->SetX(10);
                $pdf->SetFillColor(187, 179, 180);
                $pdf->SetFont('helvetica', 'B', 9);
                $pdf->Cell(20, 6, maxCaracter(utf8_decode('CANT'), 35), 1, 0, 'C', 0);
                $pdf->Cell(100, 6, maxCaracter(utf8_decode('ARTICULO '), 35), 1, 0, 'C', 0);
                $pdf->Cell(30, 6, utf8_decode('PRECIO'), 1, 0, 'C', 0);
                $pdf->Cell(30, 6, utf8_decode('TOTAL'), 1, 1, 'C', 0);
                $pdf->SetX(10);
                $pdf->SetFont('helvetica', '', 9);
                $pdf->Cell(20, 6, maxCaracter(utf8_decode($row[0]), 15), 0, 0, 'C', 0);
                $pdf->Cell(100, 6, maxCaracter(utf8_decode($row[1]), 50), 0, 0, 'C', 0);
                $pdf->Cell(30, 6, number_format($row[2], 2, '.', ''), 0, 0, 'C', 0);
                $pdf->Cell(30, 6, number_format($row[3], 2, '.', ''), 0, 1, 'C', 0);
            }
        }
        $pdf->Ln(6);
    }
}

$pdf->Output();
