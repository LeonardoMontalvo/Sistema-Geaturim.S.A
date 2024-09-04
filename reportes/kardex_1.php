<?php

require('../fpdf/fpdf.php');
include '../procesos/base.php';
include '../procesos/funciones.php';
conectarse();
date_default_timezone_set('America/Guayaquil');
session_start();

class PDF extends FPDF {

    var $widths;
    var $aligns;

    function SetWidths($w) {
        $this->widths = $w;
    }

    function Header() {
        $this->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->SetFont('Amble-Regular', '', 10);
        $fecha = date('Y-m-d', time());
        $this->SetX(10);
        $this->SetY(1);
        $this->Cell(20, 5, $fecha, 0, 0, 'C', 0);
        $this->Cell(170, 5, "CLIENTE", 0, 1, 'R', 0);
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(190, 8, "EMPRESA: " . $_SESSION['empresa'], 0, 1, 'C', 0);
        $this->Image('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 5, 8, 45, 14);
        $this->SetFont('Amble-Regular', '', 10);
        $this->Cell(190, 5, "PROPIETARIO: " . utf8_decode($_SESSION['propietario']), 0, 1, 'C', 0);
        $this->Cell(80, 5, "TEL.: " . utf8_decode($_SESSION['telefono']), 0, 0, 'R', 0);
        $this->Cell(80, 5, "CEL.: " . utf8_decode($_SESSION['celular']), 0, 1, 'C', 0);
        $this->Cell(225, 5, "DIR.: " . utf8_decode($_SESSION['direccion']), 0, 1, 'C', 0);
        $this->Cell(180, 5, "SLOGAN.: " . utf8_decode($_SESSION['slogan']), 0, 1, 'C', 0);
        $this->Cell(180, 5, utf8_decode($_SESSION['pais_ciudad']), 0, 1, 'C', 0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);

        $this->SetFillColor(120, 120, 120);
        $this->Line(1, 60, 800, 60);
        $this->Line(1, 45, 800, 45);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(190, 5, utf8_decode("INVENTARIO INICIAL PRODUCTOS"), 0, 1, 'C', 0);
        $this->SetFont('Amble-Regular', '', 10);
        $this->Ln(2);
        $this->SetFillColor(255, 255, 225);

        if ($_GET['id'] == "") {
            $this->SetX(1);
            $this->Cell(85, 6, utf8_decode('Desde el: ' . $_GET['inicio']), 0, 0, 'C', 1);
            $this->Cell(120, 6, utf8_decode('Hasta el: ' . $_GET['fin']), 0, 1, 'C', 1);
            $this->SetX(1);
            $this->Cell(85, 6, utf8_decode('Código: '), 0, 0, 'C', 1);
            $this->Cell(120, 6, utf8_decode('Descripción: '), 0, 1, 'C', 1);
        } else {

            $this->SetLineWidth(0.2);
        }

        $this->Ln(5);
        $this->SetX(1);
        $this->SetFont('Amble-Regular', '', 10);
        $this->Cell(7, 5, utf8_decode("N."), 1, 0, 'C', 0);
        $this->Cell(8, 5, utf8_decode("Tipo"), 1, 0, 'C', 0);
        $this->Cell(40, 5, utf8_decode("Cod Barras"), 1, 0, 'C', 0);
        $this->Cell(100, 5, utf8_decode("Nombres"), 1, 0, 'C', 0);
        $this->Cell(20, 5, utf8_decode("Fecha"), 1, 0, 'C', 0);
//            $this->Cell(12, 5, utf8_decode("SUMA"),1,0, 'C',0);
        $this->Cell(25, 5, utf8_decode("Stock"), 1, 0, 'C', 0);
        $this->Cell(25, 5, utf8_decode("Pre.U sin IVA"), 1, 0, 'L', 0);
        $this->Cell(20, 5, utf8_decode("Iva"), 1, 0, 'C', 0);
        $this->Cell(30, 5, utf8_decode("Pre.U con IVA"), 1, 0, 'L', 0);
        $this->Cell(25, 5, utf8_decode("Total"), 1, 0, 'C', 0);
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

}

$conpunto = 1;
$consultapunto = pg_query("select max(id_punto_venta_empresa) from punto_venta_empresa where punto_venta_empresa.id_usuario='$_SESSION[id]'");
// datos detalle factura
//   print_r("entro");

while ($row = pg_fetch_row($consultapunto)) {
    $conpunto = $row[0];
}
$conpuntoresult = 1;
$consultapuntoresult = pg_query("select id_punto_venta from punto_venta_empresa where id_punto_venta_empresa='$conpunto'");
while ($row = pg_fetch_row($consultapuntoresult)) {
    $conpuntoresult = $row[0];
}
$pdf = new PDF('L', 'mm', 'a4');
$pdf->AddPage();
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AliasNbPages();
$pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
$pdf->SetFont('Amble-Regular', '', 10);
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetX(5);
$pdf->SetFont('Amble-Regular', '', 9);

if ($_GET['id'] == "") {
    $sql = pg_query("SELECT DISTINCT ON (k.cod_productos) K.comprobante,
        k.fecha_kardex, 
        K.saldo ,
        p.articulo,
        k.cod_productos,
        p.cod_barras,
        P.precio_compra,
        P.iva,
         k.compra_venta,
        ti.valor
from kardex k
inner JOIN productos p on k.cod_productos = p.cod_productos
LEFT JOIN tarifa_impuesto ti on ti.id_taimpuesto = P.id_taimpuesto 
where  k.fecha_kardex between '$_GET[inicio]' and '$_GET[fin]' "
            . " and id_empresa=1 Activo "
            . "order by k.cod_productos,k.id_kardex desc");
    $totalstock = 0;
    $totalpu = 0;
    $totalt = 0;
    $result = 0;
    $result0 = 0;
    $result1 = 0;
    $total_result1 = 0;
    $total_parcial = 0;
    $total_result0 = 0;
    $total_totales = 0;
    while ($row = pg_fetch_row($sql)) {
        $totalstock = $totalstock + $row[2];
        $totalpu = $totalpu + $row[6];
        $totalt = $totalt + ($row[2] * $row[6]);

        $pdf->SetX(1);
        $pdf->Cell(7, 5, maxCaracter(utf8_decode($row[0]), 30), 0, 0, 'C', 0);
        $pdf->Cell(8, 5, maxCaracter(utf8_decode($row[8]), 20), 0, 0, 'L', 0);
        $pdf->Cell(40, 5, maxCaracter(utf8_decode($row[5]), 20), 0, 0, 'L', 0);
        $pdf->Cell(92, 5, maxCaracter(utf8_decode($row[3]), 100), 0, 0, 'L', 0);
        $pdf->Cell(30, 5, maxCaracter(utf8_decode($row[1]), 20), 0, 0, 'C', 0);
        $pdf->Cell(25, 5, maxCaracter(utf8_decode($row[2]), 20), 0, 0, 'l', 0);
        $pdf->Cell(25, 5, maxCaracter(utf8_decode(round($row[6], 4)), 20), 0, 0, 'l', 0);

        if ($row[7] == 'Si') {
            $result0 = $row[6] * (($row[9] / 100));

            $precio_unitario_con_iva = $row[6] * (1 + ($row[9] / 100));

            $total_stoc_preuconiva = $precio_unitario_con_iva * $row[2];
//              $total_stoc_preuconiva=$result0*$row[2];
        } else {
            $result0 = 0;
            $precio_unitario_con_iva = $row[6];
            $total_stoc_preuconiva = $precio_unitario_con_iva * $row[2];
//              $total_stoc_preuconiva=$result0*$row[2];
        }
        $total_parcial = $result1 + $row[6];
        $total_result0 = $total_result0 + $result0;
        $total_totales = $total_totales + $total_stoc_preuconiva;
        $total_result1 = $total_result1 + $precio_unitario_con_iva;
        $pdf->Cell(25, 5, maxCaracter(utf8_decode(round($result0, 4)), 20), 0, 0, 'l', 0);
        $pdf->Cell(25, 5, maxCaracter(utf8_decode(round($precio_unitario_con_iva, 4)), 20), 0, 0, 'l', 0);
        $pdf->Cell(25, 5, maxCaracter(utf8_decode(round($total_stoc_preuconiva, 2)), 20), 0, 0, 'l', 0);

        $pdf->Ln(5);
    }
} else {
    $sql = pg_query("
SELECT DISTINCT ON (k.cod_productos) K.comprobante,k.fecha_kardex, K.saldo ,p.articulo,k.cod_productos,  p.cod_barras
from kardex k
inner JOIN productos p on k.cod_productos = p.cod_productos
where  k.fecha_kardex between '$_GET[inicio]' and '$_GET[fin]' and k.cod_productos = '$_GET[id]'  and id_empresa=$conpuntoresult  order by k.cod_productos,k.id_kardex desc");
    while ($row = pg_fetch_row($sql)) {

        $pdf->SetX(1);
        $pdf->Cell(7, 5, maxCaracter(utf8_decode($row[0]), 30), 0, 0, 'C', 0);
        $pdf->Cell(40, 5, maxCaracter(utf8_decode($row[5]), 20), 0, 0, 'L', 0);
        $pdf->Cell(160, 5, maxCaracter(utf8_decode($row[3]), 90), 0, 0, 'L', 0);
        $pdf->Cell(30, 5, maxCaracter(utf8_decode($row[1]), 20), 0, 0, 'C', 0);
        $pdf->Cell(25, 5, maxCaracter(utf8_decode($row[2]), 20), 0, 0, 'l', 0);

        $pdf->Ln(5);
    }
}
$pdf->SetX(2);
$pdf->Cell(305, 0, utf8_decode(''), 1, 1, 'R', 1);
$pdf->Cell(168, 6, utf8_decode('Totales:'), 0, 0, 'R', 0);
$pdf->Cell(25, 6, (number_format($totalstock, 2, ',', '.')), 0, 0, 'C', 0);
$pdf->Cell(25, 6, (number_format($totalpu, 2, ',', '.')), 0, 0, 'C', 0);
$pdf->Cell(25, 6, (number_format(round($total_result0, 2), 2, ',', '.')), 0, 0, 'C', 0);
$pdf->Cell(25, 6, (number_format(round($total_result1, 2), 3, ',', '.')), 0, 0, 'C', 0);
$pdf->Cell(25, 6, (number_format(round($total_totales, 2), 2, ',', '.')), 0, 1, 'C', 0);
$pdf->Output();
?>
