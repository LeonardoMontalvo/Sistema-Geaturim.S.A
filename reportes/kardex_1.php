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
        $this->Image('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"], 5, 8, 45, 30);
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
        $this->Cell(7, 5, utf8_decode("Comp"), 1, 0, 'C', 0);
        //  $this->Cell(53, 5, utf8_decode("Transacción"),1,0, 'C',0);
        $this->Cell(40, 5, utf8_decode("COD BARRAS"), 1, 0, 'C', 0);
        $this->Cell(115, 5, utf8_decode("NOMBRE"), 1, 0, 'C', 0);
        $this->Cell(20, 5, utf8_decode("Fecha"), 1, 0, 'C', 0);
//            $this->Cell(12, 5, utf8_decode("SUMA"),1,0, 'C',0);
        $this->Cell(25, 5, utf8_decode("Stock"), 1, 0, 'C', 0);
        $this->Cell(25, 5, utf8_decode("P.U"), 1, 0, 'C', 0);
        $this->Cell(25, 5, utf8_decode("TOTAL"), 1, 0, 'C', 0);
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
    $sql = pg_query("SELECT DISTINCT ON (k.cod_productos) K.comprobante,k.fecha_kardex, K.saldo ,p.articulo,k.cod_productos,  p.cod_barras,P.precio_compra
from kardex k
inner JOIN productos p on k.cod_productos = p.cod_productos
where  k.fecha_kardex between '$_GET[inicio]' and '$_GET[fin]'  and id_empresa=1 and p.estado='Activo'  order by k.cod_productos,k.id_kardex desc");
    $totalstock = 0;
    $totalpu = 0;
    $totalt = 0;

    while ($row = pg_fetch_row($sql)) {
        $totalstock = $totalstock + $row[2];
        $totalpu = $totalpu + $row[6];
        $totalt = $totalt + ($row[2] * $row[6]);

        $pdf->SetX(1);
        $pdf->Cell(7, 5, maxCaracter(utf8_decode($row[0]), 30), 0, 0, 'C', 0);
        $pdf->Cell(40, 5, maxCaracter(utf8_decode($row[5]), 20), 0, 0, 'L', 0);
        $pdf->Cell(100, 5, maxCaracter(utf8_decode($row[3]), 100), 0, 0, 'L', 0);
        $pdf->Cell(30, 5, maxCaracter(utf8_decode($row[1]), 20), 0, 0, 'C', 0);

        $pdf->Cell(25, 5, maxCaracter(utf8_decode($row[2]), 20), 0, 0, 'R', 0);
        
        $pdf->Cell(25, 5, number_format($row[6],2, ',', '.'), 0, 0, 'R', 0);
        $pdf->Cell(25, 5, number_format($row[2] * $row[6],2, ',', '.'), 0, 0, 'R', 0);

        $pdf->Ln(5);
    }
} else {
    $totalstock = 0;
    $totalpu = 0;
    $totalt = 0;
    $sql = pg_query("
SELECT DISTINCT ON (k.cod_productos) K.comprobante,k.fecha_kardex, K.saldo ,p.articulo,k.cod_productos,  p.cod_barras,P.precio_compra
from kardex k
inner JOIN productos p on k.cod_productos = p.cod_productos
where  k.fecha_kardex between '$_GET[inicio]' and '$_GET[fin]' and k.cod_productos = '$_GET[id]'  and id_empresa=$conpuntoresult  order by k.cod_productos,k.id_kardex desc");
    while ($row = pg_fetch_row($sql)) {
        $totalstock = $totalstock + $row[2];
        $totalpu = $totalpu + $row[6];
        $totalt = $totalt + ($row[2] * $row[6]);
        $pdf->SetX(1);
        $pdf->Cell(7, 5, maxCaracter(utf8_decode($row[0]), 30), 0, 0, 'C', 0);
        $pdf->Cell(40, 5, maxCaracter(utf8_decode($row[5]), 20), 0, 0, 'L', 0);
        $pdf->Cell(100, 5, maxCaracter(utf8_decode($row[3]), 100), 0, 0, 'L', 0);
        $pdf->Cell(30, 5, maxCaracter(utf8_decode($row[1]), 20), 0, 0, 'C', 0);
        $pdf->Cell(25, 5, maxCaracter(utf8_decode($row[2]), 20), 0, 0, 'l', 0);
        $pdf->Cell(25, 5, maxCaracter(utf8_decode($row[6]), 20), 0, 0, 'l', 0);
        $pdf->Cell(25, 5, maxCaracter(utf8_decode($row[2] * $row[6]), 20), 0, 0, 'l', 0);

        $pdf->Ln(5);
    }
}
$pdf->SetX(2);
$pdf->Cell(305, 0, utf8_decode(''), 1, 1, 'R', 1);
$pdf->Cell(173, 6, utf8_decode('Totales:'), 0, 0, 'R', 0);
$pdf->Cell(25, 6, (number_format($totalstock, 2, ',', '.')), 0, 0, 'C', 0);
$pdf->Cell(25, 6, (number_format($totalpu, 2, ',', '.')), 0, 0, 'C', 0);
$pdf->Cell(25, 6, (number_format($totalt-2.90, 2, ',', '.')), 0, 1, 'C', 0);
$pdf->Output();
?>
