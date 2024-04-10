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
        $this->Cell(210, 5, utf8_decode("VENTAS POR CLIENTE"), 0, 1, 'C', 0);
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
$pdf->AddPage();
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AliasNbPages();
$pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
$pdf->SetFont('Amble-Regular', '', 10);
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetX(5);
$pdf->SetFont('Amble-Regular', '', 9);


$pdf = new PDF('P', 'mm', 'a4');
$pdf->SetTitle('Ventas por Beneficiario');
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
$row = pg_fetch_row(pg_query("select * from clientes where id_cliente= '$_GET[id]'"));
$total = 0;
$sql1 = pg_query("select * from factura_venta where fecha_actual $query_fecha '$_GET[fin]' and id_cliente='$row[0]' and estado='Activo' and id_empresa='$conpuntoresult'");
if (pg_num_rows($sql1)) {
    $total = 0;
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->SetFillColor(220, 240, 210);
    $pdf->Cell(105, 8, utf8_decode("RUC/CI:: " . $row[2]), 0, 0, 'L', true);
    $pdf->Cell(105, 8, utf8_decode("CLIENTE: " . $row[3]), 0, 1, 'L', true);
    $pdf->Ln(2);
    while ($row1 = pg_fetch_row($sql1)) {
        $pdf->Cell(30, 6, utf8_decode('Nro Factura:'), 1, 0, 'L', true);
        $pdf->Cell(30, 6, utf8_decode($row1[5]), 1, 0, 'L', true);
        $pdf->Cell(30, 6, utf8_decode('Beneficiario:'), 1, 0, 'L', true);
        $pdf->Cell(60, 6, utf8_decode($row1[29]), 1, 0, 'L', true);
        $pdf->Cell(40, 6, utf8_decode('Total Factura sin iva:'), 1, 0, 'L', true);
        $pdf->Cell(30, 6, number_format($row1[15], 2, '.', ''), 1, 1, 'L', true);
        
        $pdf->Cell(25, 6, utf8_decode('Cod. Producto'), 1, 0, 'C', 0);
        $pdf->Cell(55, 6, utf8_decode('Descripción'), 1, 0, 'C', 0);
        $pdf->Cell(20, 6, utf8_decode('Cantidad'), 1, 0, 'C', 0);
        $pdf->Cell(22, 6, utf8_decode('P. Venta'), 1, 0, 'C', 0);
        $pdf->Cell(22, 6, utf8_decode('T. P. Venta'), 1, 0, 'C', 0);
        $pdf->Cell(22, 6, utf8_decode('P. Compra'), 1, 0, 'C', 0);
        $pdf->Cell(22, 6, utf8_decode('T. P. Compra'), 1, 0, 'C', 0);
        $pdf->Cell(22, 6, utf8_decode('Utilidad'), 1, 1, 'C', 0);

        $sql2 = pg_query(" select cod_barras,articulo,detalle_factura_venta.cantidad,precio_venta,factura_venta.total_venta,precio_compra  from detalle_factura_venta,productos,factura_venta where detalle_factura_venta.cod_productos=productos.cod_productos and detalle_factura_venta.id_factura_venta='$row1[0]' and  factura_venta.id_factura_venta=detalle_factura_venta.id_factura_venta and  factura_venta.id_empresa='$conpuntoresult'");
        if (pg_num_rows($sql2)) {
            $sub = 0;
            while ($row2 = pg_fetch_row($sql2)) {
                $pdf->SetFont('helvetica', '', 9);
                $pdf->Cell(25, 6, maxCaracter($row2[0], 13), 0, 0, 'L', 0);
                $pdf->Cell(55, 6, maxCaracter($row2[1], 50), 0, 0, 'L', 0);
                $pdf->Cell(20, 6, number_format($row2[2], 2, ',', '.'), 0, 0, 'C', 0);
                $pdf->Cell(22, 6, number_format($row2[3], 2, ',', '.'), 0, 0, 'C', 0);
                $pdf->Cell(22, 6, number_format($row2[4], 2, ',', '.'), 0, 0, 'C', 0);
                $pdf->Cell(22, 6, number_format($row2[5], 2, ',', '.'), 0, 0, 'C', 0);
                $pdf->Cell(22, 6, number_format($row2[2] * $row2[5], 2, ',', '.'), 0, 0, 'C', 0);
                $porcent = $row2[2] * $row2[5];
                $pdf->Cell(22, 6, number_format(($row2[4]) - ($porcent), 2, ',', '.'), 0, 1, 'C', 0);
                $sub += ($row2[4] - $porcent);
            }
            $pdf->Ln(2);
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->Cell(187, 6, utf8_decode('Total Factura'), 0, 0, 'R', 0);
            $pdf->Cell(20, 6, (number_format($sub, 2, ',', '.')), 0, 1, 'C', 0);
            $total += $sub;
        }
    }
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(205, 0, utf8_decode(''), 1, 1, 'R', 1);
    $pdf->Cell(187, 6, utf8_decode('Total Venta por cliente'), 0, 0, 'R', 0);
    $pdf->Cell(20, 6, (number_format($total, 2, ',', '.')), 0, 1, 'C', 0);
}
$pdf->Output();
