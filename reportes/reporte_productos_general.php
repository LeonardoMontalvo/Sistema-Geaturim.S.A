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
        $this->Cell(105, 5, "PRODUCTOS", 0, 1, 'C', 0);
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
        $this->Cell(210, 5, utf8_decode("PRODUCTOS GENERAL"), 0, 1, 'C', 0);
        $this->Ln(7);
        $this->SetX(0);
        $this->SetFont('helvetica', 'B', 9);
        $this->SetFillColor(175, 215, 240);
        $this->Cell(40, 6, utf8_decode("CODIGO"), 1, 0, 'C', 1);
        $this->Cell(40, 6, utf8_decode("BARRAS"), 1, 0, 'C', 1);
        $this->Cell(70, 6, utf8_decode("PRODUCTO"), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode("P. COSTO"), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode("P. MINO"), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode("STOCK"), 1, 1, 'C', 1);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDF('P', 'mm', 'a4');
$pdf->SetTitle('Productos');
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->AliasNbPages();

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

$consulta = pg_query("select p.codigo,p.cod_barras,p.articulo,p.iva_minorista,p.iva_mayorista,dpb.stock from   productos p left join detalle_producto_bodega dpb on p.cod_productos=dpb.cod_productos WHERE  dpb.id_bodega=$conpuntoresult   and p.estado = 'Activo' order by p.articulo asc ");
if (pg_num_rows($consulta)) {
    while ($row = pg_fetch_row($consulta)) {
        $pdf->SetX(1);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(39, 5, maxCaracter(utf8_decode($row[0]), 20), 0, 0, 'L', 0);
        $pdf->Cell(40, 5, maxCaracter(utf8_decode($row[1]), 20), 0, 0, 'L', 0);
        $pdf->Cell(70, 5, maxCaracter(utf8_decode($row[2]), 20), 0, 0, 'L', 0);
        $pdf->Cell(20, 5, number_format($row[3]), 0, 0, 'R', 0);
        $pdf->Cell(20, 5, number_format($row[4]), 0, 0, 'R', 0);
        $pdf->Cell(20, 5, number_format($row[5]), 0, 0, 'R', 0);
        $pdf->Ln(5);
    }
}
$pdf->Output();
