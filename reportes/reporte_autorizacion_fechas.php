<?php
require('../fpdf/fpdf.php');
include '../procesos/base.php';
include '../procesos/funciones.php';
conectarse();
date_default_timezone_set('America/Guayaquil');
$fecha = date('Y-m-d', time());
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
        $this->Cell(210, 5, utf8_decode("NÚMEROS DE AUTORIZACIÓN CERCANOS A CADUCAR"), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->Ln(3);
        $this->SetX(5);
        $this->SetFont('helvetica', 'B', 9);
        $this->SetFillColor(175, 215, 240);
        $this->Cell(40, 6, utf8_decode("Nro Factura"), 1, 0, 'C', 1);
        $this->Cell(35, 6, utf8_decode("Tipo Documento"), 1, 0, 'C', 1);
        $this->Cell(50, 6, utf8_decode("Nro. Autorización"), 1, 0, 'C', 1);
        $this->Cell(40, 6, utf8_decode("Fecha Autorización"), 1, 0, 'C', 1);
        $this->Cell(35, 6, utf8_decode("Fecha caducidad"), 1, 1, 'C', 1);
        $this->Ln(1);
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
$pdf->SetTitle('Autorizaciones Fechas');
$pdf->AliasNbPages();

$consulta = pg_query("select id_cliente,identificacion,nombres_cli,telefono,direccion_cli from clientes");
if (pg_num_rows($consulta)) {
    $query_fecha = "";
    // RANGO DE FECHAS O FECHA ACTUAL
    if ($pdf->rango) {
        $query_fecha = "BETWEEN '$_GET[inicio]' AND";
    } else {
        $query_fecha = "=";
    }
    while ($row = pg_fetch_row($consulta)) {
        $sql1 = pg_query(
            "SELECT id_factura_venta,num_factura,num_autorizacion,fecha_autorizacion,fecha_caducidad 
            FROM factura_venta where id_cliente='$row[0]' and estado='Activo' and fecha_caducidad $query_fecha '$_GET[fin]'"
        );
        if (pg_num_rows($sql1)) {
            $pdf->SetX(0);
            $pdf->SetFillColor(220, 240, 210);
            $pdf->SetFont('helvetica', 'B', 8.5);
            $pdf->Cell(40, 6, utf8_decode('RUC/CI: ' . $row[1]), 0, 0, 'C', 1);
            $pdf->Cell(65, 6, utf8_decode('NOMBRE: ' . maxCaracter($row[2], 25)), 0, 0, 'C', 1);
            $pdf->Cell(40, 6, utf8_decode('TELF.: ' . $row[3]), 0, 0, 'C', 1);
            $pdf->Cell(65, 6, utf8_decode('DIR.:  ' . maxCaracter($row[4], 25)), 0, 1, 'C', 1);
            $pdf->Ln(1);
            while ($row1 = pg_fetch_row($sql1)) {
                $pdf->SetX(5);
                $pdf->SetFont('helvetica', '', 9);
                $pdf->Cell(40, 6, utf8_decode($row1[1]), 0, 0, 'C', 0);
                $pdf->Cell(35, 6, utf8_decode('Factura'), 0, 0, 'C', 0);
                $pdf->Cell(50, 6, utf8_decode($row1[2]), 0, 0, 'C', 0);
                $pdf->Cell(40, 6, utf8_decode($row1[3]), 0, 0, 'C', 0);
                $pdf->Cell(35, 6, utf8_decode($row1[4]), 0, 1, 'C', 0);
            }
        }
    }
}
$pdf->Output();
