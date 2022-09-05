<?php
//require('../fpdf/fpdf.php');
include '../fpdf/rotation.php';
include '../procesos/base.php';
include '../procesos/funciones.php';

conectarse();
date_default_timezone_set('America/Guayaquil');
session_start();
class PDF extends PDF_Rotate
{
    var $widths;
    var $aligns;
    function SetWidths($w)
    {
        $this->widths = $w;
    }
    function RotatedText($x, $y, $txt, $angle)
    {
        //Text rotated around its origin
        $this->Rotate($angle, $x, $y);
        $this->Text($x, $y, $txt);
        $this->Rotate(0);
    }

    function RotatedImage($file, $x, $y, $w, $h, $angle)
    {
        //Image rotated around its upper-left corner
        $this->Rotate($angle, $x, $y);
        $this->Image($file, $x, $y, $w, $h);
        $this->Rotate(0);
    }
}
$pdf = new PDF('L', 'mm', 'a4');
$pdf->AddPage();
$pdf->SetMargins(30, 25, 30);
$pdf->AliasNbPages();
$pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
$pdf->SetX(5);
$pdf->SetFont('Amble-Regular', '', 9);

$sql = pg_query("select id_devolucion_venta, num_serie,fecha_actual, tarifa0,tarifa12,iva_venta,descuento_venta,total_venta,clientes.id_cliente,identificacion,nombres_cli,direccion_cli,telefono,devolucion_venta.estado,ciudad from devolucion_venta,clientes where id_devolucion_venta = '" . $_GET['id'] . "' and devolucion_venta.id_cliente = clientes.id_cliente");
while ($row = pg_fetch_row($sql)) {
    $id_cliente = $row[8];
    $cliente = $row[10];
    $ci_ruc = $row[9];
    $direccion = $row[11];
    $telefono = $row[12];
    $fecha = $row[2];
    $nro_fac = substr($row[1], 8);
    $iva0 = $row[3];
    $iva12 = $row[4];
    $iva_venta = $row[5];
    $descuento_venta = $row[6];
    $total_venta = $row[7];
    $estado = $row[13];
    $ciudad = $row[14];

    $num_serie = $row[1];
}
/////////header   
//$pdf->SetFont('Arial','B',10);        
//$pdf->Text(113, 21, maxCaracter(utf8_decode($nro_fac),20),1,0, 'L',0);
//$pdf->Text(265, 21, maxCaracter(utf8_decode($nro_fac),20),1,0, 'L',0);
/////////medio
$pdf->SetFont('Amble-Regular', '', 10);
$pdf->Text(4, 51, maxCaracter(utf8_decode($cliente), 80), 1, 0, 'L', 0); /////cliente
$pdf->Text(4, 57, maxCaracter(utf8_decode($ci_ruc), 20), 1, 0, 'L', 0); ////ruc ci
$pdf->Text(4, 63, maxCaracter(utf8_decode($direccion), 50), 1, 0, 'L', 0); ////direccion
$pdf->Text(50, 70, maxCaracter(utf8_decode($ciudad), 50), 1, 0, 'L', 0); ////ciudad

$pdf->Text(10, 70, maxCaracter(utf8_decode($telefono), 20), 1, 0, 'L', 0); ////telefono
$pdf->Text(103, 51, maxCaracter(utf8_decode($fecha), 20), 1, 0, 'L', 0); /////fecha

$pdf->Text(165, 51, maxCaracter(utf8_decode($cliente), 80), 1, 0, 'L', 0); /////cliente
$pdf->Text(165, 57, maxCaracter(utf8_decode($ci_ruc), 20), 1, 0, 'L', 0); ////ruc ci
$pdf->Text(165, 63, maxCaracter(utf8_decode($direccion), 50), 1, 0, 'L', 0); ////direccion
$pdf->Text(210, 70, maxCaracter(utf8_decode($ciudad), 50), 1, 0, 'L', 0); ////ciudad

$pdf->Text(170, 70, maxCaracter(utf8_decode($telefono), 20), 1, 0, 'L', 0); ////telefono
$pdf->Text(260, 51, maxCaracter(utf8_decode($fecha), 20), 1, 0, 'L', 0); /////fecha

if ($estado == 'Pasivo') {
    $pdf->SetTextColor(249, 33, 33);
    $pdf->RotatedImage('../images/circle.png', 110, 42, 30, 10, 45);
    $pdf->RotatedText(120, 41, 'ANULADO!', 45);

    $pdf->RotatedImage('../images/circle.png', 260, 42, 30, 10, 45);
    $pdf->RotatedText(269, 41, 'ANULADO!', 45);
}
////////detalles

$sql = pg_query("select cantidad,articulo,precio_venta,total_venta from  detalle_devolucion_venta,productos where id_devolucion_venta = '" . $_GET['id'] . "' and detalle_devolucion_venta.cod_productos = productos.cod_productos");

$yy = 82;
$pdf->SetTextColor(0, 0, 0);
while ($row = pg_fetch_row($sql)) {
    $pdf->Text(0, $yy, maxCaracter(utf8_decode($row[0]), 3), 0, 1, 'L', 0);
    $pdf->Text(10, $yy, maxCaracter(utf8_decode($row[1]), 35), 0, 0, 'L', 0);

    $pdf->Text(85, $yy, maxCaracter(number_format(utf8_decode($row[2]) / 1.12, 3, ',', '.'), 6), 0, 0, 'L', 0);
    $pdf->Text(115, $yy, maxCaracter(number_format(utf8_decode($row[3]) / 1.12, 3, ',', '.'), 6), 0, 0, 'L', 0);

    $pdf->Text(155, $yy, maxCaracter(utf8_decode($row[0]), 3), 0, 1, 'L', 0);
    $pdf->Text(165, $yy, maxCaracter(utf8_decode($row[1]), 35), 0, 0, 'L', 0);

    $pdf->Text(242, $yy, maxCaracter(number_format(utf8_decode($row[2]) / 1.12, 3, ',', '.'), 6), 0, 0, 'L', 0);
    $pdf->Text(272, $yy, maxCaracter(number_format(utf8_decode($row[3]) / 1.12, 3, ',', '.'), 6), 0, 0, 'L', 0);
    $yy = $yy + 5;
}
/////////pie
$subtotal = $iva12 + $iva0;

$subtotal = round($subtotal, 2);
$descuento_venta = truncateFloat($descuento_venta, 2);
$iva_venta = round($iva_venta, 2);
$iva0 = round($iva0, 2);
$total_venta = round($total_venta, 2);


$result1 = substr("$total_venta", -1, 1);
$result2 = substr("$total_venta", -2, 1);
$result3 = substr("$total_venta", -3, 1);
$result4 = substr("$total_venta", -4, 1);
$result5 = substr("$total_venta", -5, 1);

if ($result1 == "." || $result2 == "." || $result3 == "." || $result4 == "." || $result5 == ".") {
    $pdf->Text(115, 208, maxCaracter($total_venta, 10), 0, 1, 'L', 0);
    $pdf->Text(271, 208, maxCaracter($total_venta, 10), 0, 1, 'L', 0);
} else {

    $total_ventacero = $total_venta . ".00";
    $pdf->Text(115, 208, maxCaracter($total_ventacero, 10), 0, 1, 'L', 0);
    $pdf->Text(271, 208, maxCaracter($total_ventacero, 10), 0, 1, 'L', 0);
}

$pdf->Text(115, 176, maxCaracter($subtotal, 8), 0, 1, 'L', 0);
$pdf->Text(115, 184, maxCaracter(utf8_decode($descuento_venta), 8), 0, 1, 'L', 0);
$pdf->Text(115, 193, maxCaracter(utf8_decode($iva0), 8), 0, 1, 'L', 0);
$pdf->Text(115, 199, maxCaracter(utf8_decode($iva_venta), 8), 0, 1, 'L', 0);


$pdf->Text(271, 176, maxCaracter($subtotal, 8), 2, 1, 'L', 2);
$pdf->Text(271, 184, maxCaracter(utf8_decode($descuento_venta), 8), 0, 1, 'L', 0);
$pdf->Text(271, 193, maxCaracter(utf8_decode($iva0), 8), 0, 1, 'L', 0);
$pdf->Text(271, 199, maxCaracter(utf8_decode($iva_venta), 8), 0, 1, 'L', 0);


$pdf->Output();
