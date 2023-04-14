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
        $pro = pg_fetch_row(pg_query("select articulo from productos where cod_productos='$_GET[cod]'"));
        $this->Cell(210, 5, utf8_decode("RESERVACIONES DEL PRODUCTO: " . maxCaracter($pro[0], 20)), 0, 1, 'C', 0);
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
$pdf->SetTitle('Reservaciones por Producto');
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->AliasNbPages();

$total = 0;
$sub = 0;
$desc = 0;
$ivaT = 0;
$query_fecha = "";
// RANGO DE FECHAS O FECHA ACTUAL
if ($pdf->rango) {
    $query_fecha = "BETWEEN '$_GET[inicio]' AND";
} else {
    $query_fecha = "=";
}
$consulta = pg_query(
    "SELECT d.id_reservacion, d.cantidad from detalle_reservacion d, reservaciones r 
    WHERE d.cod_productos='$_GET[cod]' and d.id_reservacion=r.id_reservacion and r.fecha_actual $query_fecha '$_GET[fin]' order by r.id_reservacion asc"
);
while ($row = pg_fetch_row($consulta)) {
    $total = 0;
    $sub = 0;
    $desc = 0;
    $ivaT = 0;
    $consulta1 = pg_query(
        "SELECT c.identificacion,c.nombres_cli,r.fecha_actual,r.hora_actual from clientes c, reservaciones r 
        WHERE r.id_cliente=c.id_cliente and r.id_reservacion='$row[0]'"
    );
    if (pg_num_rows($consulta1)) {
        while ($row1 = pg_fetch_row($consulta1)) {
            $pdf->SetX(1);
            $pdf->SetFillColor(187, 179, 180);
            $pdf->Cell(30, 6, maxCaracter(utf8_decode('RUC/CI'), 35), 1, 0, 'C', 0);
            $pdf->Cell(80, 6, maxCaracter(utf8_decode('NOMBRES'), 50), 1, 0, 'C', 0);
            $pdf->Cell(22, 6, utf8_decode('RES.'), 1, 0, 'C', 0);
            $pdf->Cell(30, 6, utf8_decode('FECHA'), 1, 0, 'C', 0);
            $pdf->Cell(25, 6, utf8_decode('HORA'), 1, 0, 'C', 0);
            $pdf->Cell(20, 6, utf8_decode('CANTIDAD'), 1, 1, 'C', 0);
            $pdf->SetX(1);
            $pdf->Cell(30, 6, maxCaracter(utf8_decode($row1[0]), 35), 0, 0, 'L', 0);
            $pdf->Cell(80, 6, maxCaracter(utf8_decode($row1[1]), 50), 0, 0, 'L', 0);
            $pdf->Cell(22, 6, utf8_decode($row[0]), 0, 0, 'C', 0);
            $pdf->Cell(30, 6, utf8_decode($row1[2]), 0, 0, 'C', 0);
            $pdf->Cell(25, 6, utf8_decode($row1[3]), 0, 0, 'C', 0);
            $pdf->Cell(20, 6, utf8_decode($row[1]), 0, 1, 'C', 0);
            $pdf->Ln(6);
        }
    }
}

$pdf->Output();
