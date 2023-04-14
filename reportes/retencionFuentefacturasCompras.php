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
        $this->Cell(210, 5, utf8_decode("RESUMEN DE RETENCIONES EN LA FUENTE DE FACTURAS COMPRAS"), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->Ln(3);
        $this->SetFont('helvetica', 'B', 9);
        $this->SetFillColor(175, 215, 240);
        $this->Cell(32, 6, utf8_decode('Nro. Factura'), 1, 0, 'C', 1);
        $this->Cell(50, 6, utf8_decode('Detalle Retención'), 1, 0, 'C', 1);
        $this->Cell(40, 6, utf8_decode('Proveedor'), 1, 0, 'C', 1);
        $this->Cell(22, 6, utf8_decode('Fecha'), 1, 0, 'C', 1);
        $this->Cell(22, 6, utf8_decode('Subtotal'), 1, 0, 'C', 1);
        $this->Cell(22, 6, utf8_decode('Porcentaje'), 1, 0, 'C', 1);
        $this->Cell(22, 6, utf8_decode('Retención'), 1, 1, 'C', 1);
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
$pdf->SetTitle('Retencion Fuente');
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
    "SELECT fc.num_serie, rf.descripcion, p.empresa_pro, rfc.fecha, rfc.valor_compra, rf.valor, rfc.valor_retencion 
    FROM factura_compra fc, retencion_fuentes rf, retencion_fuente_factura_compra rfc, proveedores p 
    WHERE fc.id_proveedor=p.id_proveedor and fc.id_factura_compra=rfc.id_factura and rfc.id_retencion_fuente=rf.id_retencion_fuentes 
    AND rfc.fecha $query_fecha '$_GET[fin]' order by rfc.id_retencion_fuente_factura_compra"
);

if (pg_num_rows($consulta1)) {
    $total = 0;
    $base = 0;
    $ret = 0;
    while ($row1 = pg_fetch_row($consulta1)) {
        $pdf->SetX(1);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(31, 6, utf8_decode($row1[0]), 0, 0, 'L', 0);
        $pdf->Cell(50, 6, maxCaracter(utf8_decode(substr($row1[1], 0, 50)), 22), 0, 0, 'L', 0);
        $pdf->Cell(40, 6, utf8_decode($row1[2]), 0, 0, 'C', 0);
        $pdf->Cell(22, 6, utf8_decode($row1[3]), 0, 0, 'C', 0);
        $pdf->Cell(22, 6, number_format($row1[4], 2, ',', '.'), 0, 0, 'R', 0);
        $pdf->Cell(22, 6, number_format($row1[5], 2, ',', '.'), 0, 0, 'R', 0);
        $pdf->Cell(22, 6, number_format($row1[6], 2, ',', '.'), 0, 1, 'R', 0);
        $total += $row1[3];
        $base += $row1[4];
        $ret += $row1[5];
    }
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
    $pdf->Cell(144, 6, utf8_decode("Totales"), 0, 0, 'R', 0);
    $pdf->Cell(22, 6, maxCaracter((number_format($total, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(22, 6, maxCaracter((number_format($base, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(22, 6, maxCaracter((number_format($ret, 2, ',', '.')), 20), 0, 1, 'R', 0);
}
$pdf->Output();
