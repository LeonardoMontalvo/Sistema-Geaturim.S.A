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
        $this->SetFont('Amble-Regular', '', 10);
        $fecha = date('Y-m-d', time());
        $this->SetX(0);
        $this->SetY(0);
        $this->Cell(105, 5, $fecha, 0, 0, 'C', 0);
        $this->Cell(105, 5, "COMPRAS", 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(210, 8, utf8_decode($_SESSION['nombre_empresa']), 0, 1, 'C', 0);
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
        $this->Cell(210, 5, utf8_decode("FACTURA COMPRA"), 0, 1, 'C', 0);
        $this->SetFont('Amble-Regular', '', 10);
        $this->Ln(9);
        $this->SetFillColor(220, 240, 210);
        $row = pg_fetch_row(
            pg_query(
                "SELECT id_factura_compra,comprobante,fecha_actual,hora_actual,num_serie,num_autorizacion,fecha_cancelacion,empresa_pro,representante_legal,factura_compra.forma_pago 
                FROM factura_compra,proveedores where factura_compra.id_proveedor=proveedores.id_proveedor and id_factura_compra='$_GET[id]';"
            )
        );
        $this->Cell(90, 6, utf8_decode('COMPROBANTE: ' . $row[1]), 0, 0, 'L', 1);
        $this->Cell(120, 6, utf8_decode('FECHA: ' . $row[2]), 0, 1, 'L', 1);
        $this->Cell(90, 6, utf8_decode('HORA: ' . $row[3]), 0, 0, 'L', 1);
        $this->Cell(120, 6, utf8_decode('NRO. SERIE: ' . $row[4]), 0, 1, 'L', 1);
        $this->Cell(210, 6, utf8_decode('NRO AUTORIZACIÓN: ' . $row[5]), 0, 1, 'L', 1);
        $this->Cell(90, 6, utf8_decode('FORMA PAGO: ' . $row[9]), 0, 0, 'L', 1);
        $this->Cell(120, 6, utf8_decode('EMPRESA: ' . $row[7]), 0, 1, 'L', 1);
        $this->Cell(210, 6, utf8_decode('FECHA CANCELACIÓN: ' . $row[6]), 0, 1, 'L', 1);
        $this->Cell(210, 6, utf8_decode('REPRESENTANTE: ' . $row[8]), 0, 1, 'L', 1);
        $this->SetLineWidth(0.2);
        $this->Ln(2);
        $this->SetX(5);
        $this->SetFont('helvetica', 'B', 10);
        $this->SetFillColor(175, 215, 240);
        $this->Cell(30, 6, utf8_decode("CANTIDAD"), 1, 0, 'C', 1);
        $this->Cell(110, 6, utf8_decode("DESCRIPCIÓN"), 1, 0, 'C', 1);
        $this->Cell(30, 6, utf8_decode("V. UNITARIO"), 1, 0, 'C', 1);
        $this->Cell(30, 6, utf8_decode("V. TOTAL"), 1, 1, 'C', 1);
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
$pdf->SetTitle('Comprobante Compra');
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->AliasNbPages();

$total = 0;
$calculoIVA = pg_query("select valor from parametros where descripcion='IVA'");
while ($rowi = pg_fetch_row($calculoIVA)) {
    $iva_base = $rowi[0];
}
$iva_base = ($iva_base / 100) + 1;
$sql = pg_query("select detalle_factura_compra.cantidad,productos.articulo,detalle_factura_compra.precio_compra,detalle_factura_compra.total_compra from factura_compra,detalle_factura_compra,productos where factura_compra.id_factura_compra=detalle_factura_compra.id_factura_compra and detalle_factura_compra.cod_productos=productos.cod_productos and detalle_factura_compra.id_factura_compra='$_GET[id]' and productos.incluye_iva = 'No'");
while ($row = pg_fetch_row($sql)) {
    $pdf->SetX(5);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(29, 5, maxCaracter(utf8_decode($row[0]), 20), 0, 0, 'C', 0);
    $pdf->Cell(110, 5, maxCaracter(utf8_decode($row[1]), 80), 0, 0, 'L', 0);
    $pdf->Cell(30, 5, number_format($row[2], 2, ',', '.'), 0, 0, 'R', 0);
    $pdf->Cell(30, 5, number_format($row[3], 2, ',', '.'), 0, 1, 'R', 0);
}
$sql = pg_query("select detalle_factura_compra.cantidad,productos.articulo,detalle_factura_compra.precio_compra,detalle_factura_compra.total_compra from factura_compra,detalle_factura_compra,productos where factura_compra.id_factura_compra=detalle_factura_compra.id_factura_compra and detalle_factura_compra.cod_productos=productos.cod_productos and detalle_factura_compra.id_factura_compra='$_GET[id]' and productos.incluye_iva = 'Si'");
while ($row = pg_fetch_row($sql)) {
    $total_si = 0;
    $total_sit = 0;
    $total_si = $row[3] / $iva_base;
    $total_sit = $total_si / $row[0];
    $total_si = truncateFloat($total_si, 2);
    $total_sit = truncateFloat($total_sit, 2);
    $pdf->SetX(5);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(29, 5, maxCaracter(utf8_decode($row[0]), 20), 0, 0, 'C', 0);
    $pdf->Cell(110, 5, maxCaracter(utf8_decode($row[1]), 80), 0, 0, 'L', 0);
    $pdf->Cell(30, 5, number_format($total_sit, 2, ',', '.'), 0, 0, 'R', 0);
    $pdf->Cell(30, 5, number_format($total_si, 2, ',', '.'), 0, 1, 'R', 0);
}
$pdf->SetX(5);
$pdf->Ln(5);
$ice = 0;
$irbp = 0;
$sql = pg_query("SELECT ice, irbp
  FROM ice_factura_compra where id_factura_compra ='$_GET[id]'
");
while ($row = pg_fetch_row($sql)) {
    $ice = $row[0];
    $irbp = $row[1];
}

$sql = pg_query("select factura_compra.descuento_compra,factura_compra.tarifa0,factura_compra.tarifa12,factura_compra.iva_compra,factura_compra.total_compra from factura_compra,detalle_factura_compra,productos where factura_compra.id_factura_compra=detalle_factura_compra.id_factura_compra and detalle_factura_compra.cod_productos=productos.cod_productos and detalle_factura_compra.id_factura_compra='$_GET[id]' LIMIT 1");
while ($row = pg_fetch_row($sql)) {
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(170, 6, utf8_decode("Descuento"), 0, 0, 'R', 0);
    $pdf->Cell(35, 6, number_format(round($row[0], 2), 2, ',', '.'), 0, 1, 'R', 0);
    $pdf->Cell(170, 6, utf8_decode("Tarifa 0"), 0, 0, 'R', 0);
    $pdf->Cell(35, 6, number_format(round($row[1], 2), 2, ',', '.'), 0, 1, 'R', 0);
    $pdf->Cell(170, 6, utf8_decode("Tarifa IVA"), 0, 0, 'R', 0);
    $pdf->Cell(35, 6, number_format(round($row[2], 2), 2, ',', '.'), 0, 1, 'R', 0);
    $pdf->Cell(170, 6, utf8_decode("Iva ...%"), 0, 0, 'R', 0);
    $pdf->Cell(35, 6, number_format(round($row[3], 2), 2, ',', '.'), 0, 1, 'R', 0);
    
    $pdf->Cell(170, 6, utf8_decode("I.C.E:"), 0, 0, 'R', 0);
    $pdf->Cell(35, 6, number_format(round($ice, 2), 2, ',', '.'), 0, 1, 'R', 0);

    $pdf->Cell(170, 6, utf8_decode("I.R.B.P:"), 0, 0, 'R', 0);
    $pdf->Cell(35, 6, number_format(round($irbp, 2), 2, ',', '.'), 0, 1, 'R', 0);

    $pdf->Cell(170, 6, utf8_decode("Total"), 0, 0, 'R', 0);
    $pdf->Cell(35, 6, number_format(round($row[4]+$irbp+$ice, 2), 2, ',', '.'), 0, 1, 'R', 0);
}
//////////
$sql = pg_query("select * from series_compra,factura_compra,productos where factura_compra.id_factura_compra=series_compra.id_factura_compra and productos.cod_productos=series_compra.cod_productos and series_compra.id_factura_compra='$_GET[id]'");
if (pg_num_rows($sql)) {
    $pdf->AddPage();
    $pdf->Cell(205, 7, utf8_decode("NÚMEROS DE SERIE"), 0, 1, 'C', 0);
    $pdf->Cell(50, 5, utf8_decode("Cod. Producto"), 1, 0, 'C', 0);
    $pdf->Cell(95, 5, utf8_decode("Descripción"), 1, 0, 'C', 0);
    $pdf->Cell(30, 5, utf8_decode("Nro. Serie"), 1, 0, 'C', 0);
    $pdf->Cell(30, 5, utf8_decode("Nro. Factura"), 1, 1, 'C', 0);
    while ($row = pg_fetch_row($sql)) {
        $pdf->Cell(50, 6, maxCaracter(utf8_decode($row[28]), 20), 0, 0, 'C', 0);
        $pdf->Cell(95, 6, maxCaracter(utf8_decode($row[30]), 60), 0, 0, 'C', 0);
        $pdf->Cell(30, 6, maxCaracter(utf8_decode($row[3]), 20), 0, 0, 'C', 0);
        $pdf->Cell(30, 6, maxCaracter(utf8_decode($row[17]), 20), 0, 1, 'C', 0);
    }
}
$pdf->Output();
