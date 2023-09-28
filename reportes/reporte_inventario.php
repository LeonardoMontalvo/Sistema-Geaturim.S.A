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
        $this->SetX(1);
        $this->SetY(1);
        $this->Cell(20, 5, $fecha, 0, 0, 'C', 0);
        $this->Cell(150, 5, "CLIENTE", 0, 1, 'R', 0);
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(190, 8, "EMPRESA: " . $_SESSION['nombre_empresa'], 0, 1, 'C', 0);
        $this->Image('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 5, 8, 45, 14);
        $this->SetFont('Amble-Regular', '', 10);
        $this->Cell(180, 5, "PROPIETARIO: " . utf8_decode($_SESSION['propietario']), 0, 1, 'C', 0);
        $this->Cell(80, 5, "TEL.: " . utf8_decode($_SESSION['telefono']), 0, 0, 'R', 0);
        $this->Cell(80, 5, "CEL.: " . utf8_decode($_SESSION['celular']), 0, 1, 'C', 0);
        $this->Cell(180, 5, "DIR.: " . utf8_decode($_SESSION['direccion']), 0, 1, 'C', 0);
        $this->Cell(180, 5, "SLOGAN.: " . utf8_decode($_SESSION['slogan']), 0, 1, 'C', 0);
        $this->Cell(180, 5, utf8_decode($_SESSION['pais_ciudad']), 0, 1, 'C', 0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);
        $this->Line(1, 46, 270, 46);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(190, 5, utf8_decode("INVENTARIO "), 0, 1, 'C', 0);
        $this->SetFont('Amble-Regular', '', 10);
        $this->Ln(3);
        $this->SetFillColor(255, 255, 225);
        $this->SetLineWidth(0.2);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

}

$pdf = new PDF('l', 'mm', 'a4');
$pdf->AddPage();
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AliasNbPages();
$pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
$pdf->SetFont('Amble-Regular', '', 10);
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetX(5);
$pdf->SetFont('Amble-Regular', '', 9);
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
$sql = pg_query("select  I.comprobante, I.fecha_actual, I.hora_actual, U.nombre_usuario, U.apellido_usuario  from inventario I, usuario U where I.id_usuario = U.id_usuario and I.comprobante='$_GET[id]' and I.id_empresa=$conpuntoresult");
while ($row = pg_fetch_row($sql)) {
    $pdf->SetX(1);
    $pdf->Cell(50, 6, utf8_decode('Comprobante: '), 0, 0, 'C', 0);
    $pdf->Cell(55, 6, utf8_decode($row[0]), 0, 0, 'L', 0);
    $pdf->Cell(50, 6, utf8_decode('Fecha: '), 0, 0, 'C', 0);
    $pdf->Cell(50, 6, utf8_decode($row[1]), 0, 1, 'L', 0);
    $pdf->SetX(1);
    $pdf->Cell(50, 6, utf8_decode('Hora:'), 0, 0, 'C', 0);
    $pdf->Cell(55, 6, utf8_decode($row[2]), 0, 0, 'L', 0);
    $pdf->Cell(50, 6, utf8_decode('Usuario:'), 0, 0, 'C', 0);
    $pdf->Cell(50, 6, utf8_decode($row[3]), 0, 1, 'L', 0);
    $pdf->Ln(3);
}
$sql2 = pg_query("select D.cod_productos, P.codigo, P.articulo, D.p_costo, D.p_venta, D.disponibles, D.existencia, D.diferencia,D.unidad_medida from inventario I, detalle_inventario D, productos P where D.cod_productos = P.cod_productos and I.id_inventario = D.id_inventario and D.id_inventario='$_GET[id]' and I.id_empresa=$conpuntoresult");
$pdf->SetX(1);
$pdf->Cell(30, 6, utf8_decode('Código'), 1, 0, 'C', 0);
$pdf->Cell(65, 6, utf8_decode('Producto'), 1, 0, 'C', 0);
$pdf->Cell(25, 6, utf8_decode('Precio Costo'), 1, 0, 'C', 0);
$pdf->Cell(25, 6, utf8_decode('Precio Venta'), 1, 0, 'C', 0);
$pdf->Cell(20, 6, utf8_decode('Stock Act.'), 1, 0, 'C', 0);
$pdf->Cell(25, 6, utf8_decode('Stok Ant.'), 1, 0, 'C', 0);
$pdf->Cell(25, 6, utf8_decode('Stock Sobra.'), 1, 0, 'C', 0);
$pdf->Cell(25, 6, utf8_decode('Stock Falta.'), 1, 0, 'C', 0);
$pdf->Cell(25, 6, utf8_decode('V.Sob.'), 1, 0, 'C', 0);
$pdf->Cell(25, 6, utf8_decode('V.Fal.'), 1, 1, 'C', 0);
$total1 = 0;
$total2 = 0;
$tot_sobrante = 0;
$tot_faltante = 0;
$val_sobrante=0;
$val_faltante=0;
while ($row = pg_fetch_row($sql2)) {
    $pdf->SetX(1);
    $total1 = $total1 + ($row[3] * $row[5]);
    $total2 = $total2 + ($row[4] * $row[5]);
    $pdf->Cell(30, 6, maxCaracter(utf8_decode($row[1]), 15), 0, 0, 'C', 0);
    $pdf->Cell(65, 6, maxCaracter(utf8_decode($row[2] . "(" . $row[8] . ")"), 30), 0, 0, 'L', 0);
    $pdf->Cell(25, 6, utf8_decode($row[3]), 0, 0, 'C', 0);
    $pdf->Cell(25, 6, utf8_decode($row[4]), 0, 0, 'C', 0);
    $pdf->Cell(20, 6, utf8_decode($row[5]), 0, 0, 'C', 0); //actual    canti
    $pdf->Cell(25, 6, utf8_decode($row[6]), 0, 0, 'C', 0); //*ant    stok
    if (stristr($row[7], '-') === FALSE) {
        $pdf->Cell(25, 6, utf8_decode($row[7]), 0, 0, 'C', 0); //stok sobrante
        $pdf->Cell(25, 6, utf8_decode(""), 0, 0, 'C', 0);
        $pdf->Cell(25, 6, utf8_decode(($row[5] - $row[6]) * $row[3]), 0, 0, 'C', 0);
        $val_sobrante = $val_sobrante + (($row[5] - $row[6]) * $row[3]);
        $pdf->Cell(25, 6, utf8_decode(""), 0, 1, 'C', 0);
        $tot_sobrante = $tot_sobrante + $row[7];
    } else {
        $pdf->Cell(25, 6, utf8_decode(""), 0, 0, 'C', 0);
        $pdf->Cell(25, 6, utf8_decode($row[7]), 0, 0, 'C', 0); //stok faltante

        $pdf->Cell(25, 6, utf8_decode(""), 0, 0, 'C', 0);
        $pdf->Cell(25, 6, utf8_decode(($row[5] - $row[6]) * $row[3]), 0, 1, 'C', 0);
        $val_faltante = $val_faltante + (($row[5] - $row[6]) * $row[3]);
        $tot_faltante = $tot_faltante + $row[7];
    }
}
$pdf->SetX(1);
$pdf->Cell(290, 0, utf8_decode(''), 1, 1, 'R', 1);
$pdf->Cell(97, 6, utf8_decode('Totales:'), 0, 0, 'R', 0);
$pdf->Cell(27, 6, (number_format($total1, 2, ',', '.')), 0, 0, 'C', 0);
$pdf->Cell(65, 6, (number_format($total2, 2, ',', '.')), 0, 0, 'l', 0);
$pdf->Cell(25, 6, (number_format($tot_sobrante, 2, ',', '.')), 0, 0, 'C', 0);
$pdf->Cell(25, 6, (number_format($tot_faltante, 2, ',', '.')), 0, 0, 'C', 0);
$pdf->Cell(25, 6, (number_format($val_sobrante, 2, ',', '.')), 0, 0, 'C', 0);
$pdf->Cell(25, 6, (number_format($val_faltante, 2, ',', '.')), 0, 1, 'C', 0);

$pdf->Output();
?>
