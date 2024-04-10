<?php

//require('../fpdf/fpdf.php');
include '../fpdf/rotation.php';
include '../procesos/base.php';
include '../procesos/funciones.php';

conectarse();
date_default_timezone_set('America/Guayaquil');
session_start();

class PDF extends PDF_Rotate {

    var $widths;
    var $aligns;

    function SetWidths($w) {
        $this->widths = $w;
    }

    function RotatedText($x, $y, $txt, $angle) {
        //Text rotated around its origin
        $this->Rotate($angle, $x, $y);
        $this->Text($x, $y, $txt);
        $this->Rotate(0);
    }

    function RotatedImage($file, $x, $y, $w, $h, $angle) {
        //Image rotated around its upper-left corner
        $this->Rotate($angle, $x, $y);
        $this->Image($file, $x, $y, $w, $h);
        $this->Rotate(0);
    }

    function CheckPageBreak($h) {
        //If the height h would cause an overflow, add a new page immediately
        if ($this->GetY(300) + $h > $this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function Header() {
        $this->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->SetFont('Amble-Regular', '', 10);
        $fecha = date('Y-m-d', time());
        $this->SetX(1);
        $this->SetY(5);
//        $this->Cell(20, 5, $fecha, 0, 0, 'C', 0);
//        $this->Cell(150, 5, "CLIENTE", 0, 1, 'R', 0);
        $this->SetFont('Arial', 'B', 13);
        $this->Cell(152, 8, $_SESSION['empresa'], 0, 1, 'C', 0);
        $this->Image('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"], 7, 8, 32, 25);
        $this->SetFont('Amble-Regular', '', 10);
//        $this->Cell(150, 5, "PROPIETARIO: " . utf8_decode($_SESSION['propietario']), 0, 1, 'C', 0);
        $this->Cell(70, 5, "TEL.: " . utf8_decode($_SESSION['telefono']), 0, 0, 'R', 0);
        $this->Cell(60, 5, "CEL.: " . utf8_decode($_SESSION['celular']), 0, 1, 'C', 0);
          $this->SetFont('Amble-Regular', '', 8);
        $this->Cell(160, 5, utf8_decode($_SESSION['direccion']), 0, 1, 'C', 0);
                $this->SetFont('Amble-Regular', '', 10);
//        $this->Cell(140, 5, "SLOGAN.: " . utf8_decode($_SESSION['slogan']), 0, 1, 'C', 0);
        $this->Cell(140, 5, utf8_decode($_SESSION['pais_ciudad']), 0, 1, 'C', 0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.5);


        $this->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->SetFont('Amble-Regular', '', 10);
        $fecha = date('Y-m-d', time());
        $this->SetX(1);
        $this->SetY(5);
//        $this->Cell(20, 5, $fecha, 0, 0, 'C', 0);
//        $this->Cell(150, 5, "CLIENTE", 0, 1, 'R', 0);
        $this->SetFont('Arial', 'B', 13);
        $this->Cell(450, 8, $_SESSION['empresa'], 0, 1, 'C', 0);
        $this->Image('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"], 155, 8, 32, 25);
        $this->SetFont('Amble-Regular', '', 10);
//        $this->Cell(450, 5, "PROPIETARIO: " . utf8_decode($_SESSION['propietario']), 0, 1, 'C', 0);
        $this->Cell(220, 5, "TEL.: " . utf8_decode($_SESSION['telefono']), 0, 0, 'R', 0);
        $this->Cell(60, 5, "CEL.: " . utf8_decode($_SESSION['celular']), 0, 1, 'C', 0);
              $this->SetFont('Amble-Regular', '', 8);
        $this->Cell(450, 5, utf8_decode($_SESSION['direccion']), 0, 1, 'C', 0);
              $this->SetFont('Amble-Regular', '', 10);
//        $this->Cell(450, 5, "SLOGAN.: " . utf8_decode($_SESSION['slogan']), 0, 1, 'C', 0);
        $this->Cell(450, 5, utf8_decode($_SESSION['pais_ciudad']), 0, 1, 'C', 0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.5);
    }

    function Row($data) {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        $h = 10 * $nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY(100);
            //Draw the border
            //$this->Rect($x,$y,$w,$h);

            $this->MultiCell($w, 10, $data[$i], 0, $a, false);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function NbLines($w, $txt) {
        //Computes the number of lines a MultiCell of width w will take
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 2000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }

    function Footer() {
        $this->SetY(-45);
        $this->SetFont('Arial', 'I', 8);
//        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

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

$sql = pg_query("select id_facturas_novalidas, id_facturas_novalidas,fecha_actual, tarifa0,tarifa12,iva_venta,descuento_venta,total_venta,clientes.id_cliente,identificacion,nombres_cli,direccion_cli,telefono,facturas_novalidas.estado from facturas_novalidas,clientes where id_facturas_novalidas = '" . $_GET['id'] . "' and facturas_novalidas.id_cliente = clientes.id_cliente");
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
}
/////////header   
$pdf->SetFont('Arial', 'B', 10);
$pdf->Text(113, 21, maxCaracter(utf8_decode($nro_fac), 20), 1, 0, 'L', 0);
$pdf->Text(265, 21, maxCaracter(utf8_decode($nro_fac), 20), 1, 0, 'L', 0);
/////////medio
$pdf->SetFont('Amble-Regular', '', 9);
$pdf->Text(30, 48, "CLIENTE: " . maxCaracter(utf8_decode($cliente), 80), 1, 0, 'L', 0); /////cliente
$pdf->Text(30, 55, "DIRECCION: " . maxCaracter(utf8_decode($direccion), 35), 1, 0, 'L', 0); ////direccion
$pdf->Text(113, 55, "TEL.: " . maxCaracter(utf8_decode($telefono), 20), 1, 0, 'L', 0); ////telefono
$pdf->Text(30, 62, "RUC/CI: " . maxCaracter(utf8_decode($ci_ruc), 20), 1, 0, 'L', 0); ////ruc ci
$pdf->Text(113, 62, "FECHA: " . maxCaracter(utf8_decode($fecha), 20), 1, 0, 'L', 0); /////fecha

$pdf->Text(180, 48, "CLIENTE: " . maxCaracter(utf8_decode($cliente), 80), 1, 0, 'L', 0); /////cliente
$pdf->Text(180, 55, "DIRECCION: " . maxCaracter(utf8_decode($direccion), 35), 1, 0, 'L', 0); ////direccion
$pdf->Text(258, 55, "TEL.: " . maxCaracter(utf8_decode($telefono), 20), 1, 0, 'L', 0); ////telefono
$pdf->Text(180, 62, "RUC/CI: " . maxCaracter(utf8_decode($ci_ruc), 20), 1, 0, 'L', 0); ////ruc ci
$pdf->Text(258, 62, "FECHA: " . maxCaracter(utf8_decode($fecha), 20), 1, 0, 'L', 0); /////fecha

if ($estado == 'Pasivo') {
    $pdf->SetTextColor(249, 33, 33);
    $pdf->RotatedImage('../images/circle.png', 110, 42, 30, 10, 45);
    $pdf->RotatedText(120, 41, 'ANULADO!', 45);

    $pdf->RotatedImage('../images/circle.png', 260, 42, 30, 10, 45);
    $pdf->RotatedText(269, 41, 'ANULADO!', 45);
}
////////detalles
$pdf->SetY(65);
$pdf->SetX(10);
$pdf->SetWidths(array(15, 70, 23, 15));
$sql = pg_query("select cantidad,articulo,precio_venta,total_venta from  detalle_facturas_novalidas,productos where id_facturas_novalidas = '" . $_GET['id'] . "' and detalle_facturas_novalidas.cod_productos = productos.cod_productos and productos.incluye_iva= 'Si'");

$yy = 76;
$calculoIVA = pg_query("select valor from parametros where descripcion='IVA'");

while ($rowi = pg_fetch_row($calculoIVA)) {
    $iva_base = $rowi[0];
}
$iva_base = ($iva_base / 100) + 1;
$pdf->SetTextColor(0, 0, 0);
$pdf->Row(array("Cant", utf8_decode("Descripción"), "Pre. Uni", "Pre. Tot"));
while ($row = pg_fetch_row($sql)) {
    $total_si = 0;
    $total_sit = 0;
    $total_si = $row[3] / $iva_base;
    $total_sit = $total_si / $row[0];
    $total_si = truncateFloat($total_si, 2);
    $total_sit = truncateFloat($total_sit, 2);

    $pdf->Text(15, $yy, maxCaracter(utf8_decode($row[0]), 3), 0, 1, 'L', 0);
    $pdf->Text(165, $yy, maxCaracter(utf8_decode($row[0]), 3), 0, 1, 'L', 0);

    $array = ceil_caracter($row[1], 35);
    if (sizeof($array) > 1) {
        $zz = $yy;
        for ($i = 0; $i < sizeof($array); $i++) {
            $pdf->Text(25, $zz, utf8_decode($array[$i]), 0, 0, 'J', 0);
            $pdf->Text(180, $zz, utf8_decode($array[$i]), 0, 0, 'J', 0);
            $zz = $zz + 3;
        }
        $yy = $yy + 5;
    } else {
        $pdf->Text(25, $yy, maxCaracter(utf8_decode($row[1]), 30), 0, 0, 'L', 0);
        $pdf->Text(180, $yy, maxCaracter(utf8_decode($row[1]), 30), 0, 0, 'L', 0);
    }

    $pdf->Text(95, $yy, maxCaracter(number_format($total_sit, 2, ',', '.'), 6), 0, 0, 'L', 0);
    $pdf->Text(245, $yy, maxCaracter(number_format($total_si, 2, ',', '.'), 6), 0, 0, 'L', 0);

    $pdf->Text(120, $yy, maxCaracter(number_format($total_si, 2, ',', '.'), 6), 0, 0, 'L', 0);
    $pdf->Text(270, $yy, maxCaracter(number_format($total_si, 2, ',', '.'), 6), 0, 0, 'L', 0);
    $yy = $yy + 5;
}
$pdf->SetY(65);
$pdf->SetX(161);
$pdf->SetWidths(array(15, 70, 23, 15));
$sql = pg_query("select cantidad,articulo,precio_venta,total_venta from  detalle_facturas_novalidas,productos where id_facturas_novalidas = '" . $_GET['id'] . "' and detalle_facturas_novalidas.cod_productos = productos.cod_productos and productos.incluye_iva= 'No'");
$pdf->SetTextColor(0, 0, 0);
$pdf->Row(array("Cant", utf8_decode("Descripción"), "Pre. Uni", "Pre. Tot"));
while ($row = pg_fetch_row($sql)) {
    $temp_1 = number_format($row[3], 2, ',', '.');
    $pdf->Text(15, $yy, maxCaracter(utf8_decode($row[0]), 3), 0, 1, 'L', 0);
    $pdf->Text(165, $yy, maxCaracter(utf8_decode($row[0]), 3), 0, 1, 'L', 0);

    $array = ceil_caracter($row[1], 35);
    if (sizeof($array) > 1) {
        $zz = $yy;
        for ($i = 0; $i < sizeof($array); $i++) {
            $pdf->Text(25, $zz, utf8_decode($array[$i]), 0, 0, 'J', 0);
            $pdf->Text(180, $zz, utf8_decode($array[$i]), 0, 0, 'J', 0);
            $zz = $zz + 3;
        }
        $yy = $yy + 5;
    } else {
        $pdf->Text(25, $yy, maxCaracter(utf8_decode($row[1]), 30), 0, 0, 'L', 0);
        $pdf->Text(180, $yy, maxCaracter(utf8_decode($row[1]), 30), 0, 0, 'L', 0);
    }


    $pdf->Text(95, $yy, maxCaracter(utf8_decode($row[2]), 6), 0, 0, 'L', 0);
    $pdf->Text(247, $yy, maxCaracter(utf8_decode($row[2]), 6), 0, 0, 'L', 0);

    $pdf->Text(120, $yy, maxCaracter($temp_1, 6), 0, 0, 'L', 0);
    $pdf->Text(270, $yy, maxCaracter($temp_1, 6), 0, 0, 'L', 0);
    $yy = $yy + 5;
}
/////////pie        
$subtotal = truncateFloat($iva12, 2);
$descuento_venta = truncateFloat($descuento_venta, 2);
$iva_venta = truncateFloat($iva_venta, 2);
$iva0 = truncateFloat($iva0, 2);
$total_venta = truncateFloat($total_venta, 2);

$pdf->Text(100, 173, "Tarifa 15%", 0, 1, 'L', 0);
$pdf->Text(120, 173, maxCaracter($subtotal, 5), 0, 1, 'L', 0);
$pdf->Text(100, 179, "Tarifa 0%", 0, 1, 'L', 0);
$pdf->Text(120, 179, maxCaracter($iva0, 5), 0, 1, 'L', 0);
$pdf->Text(100, 185, "Iva%", 0, 1, 'L', 0);
$pdf->Text(120, 185, maxCaracter($iva_venta, 5), 0, 1, 'L', 0);
//$pdf->Text(98, 186, '12', 0, 1, 'L', 0);
$pdf->Text(100, 191, "Des", 0, 1, 'L', 0);
$pdf->Text(120, 191, maxCaracter($descuento_venta, 5), 0, 1, 'L', 0);
$pdf->Text(100, 197, "Total", 0, 1, 'L', 0);
$pdf->Text(120, 197, maxCaracter($total_venta, 10), 0, 1, 'L', 0);


$pdf->Text(255, 173, "Tarifa 15%", 0, 1, 'L', 0);
$pdf->Text(275, 173, maxCaracter($subtotal, 5), 0, 1, 'L', 0);
$pdf->Text(255, 179, "Tarifa 0%", 0, 1, 'L', 0);
$pdf->Text(275, 179, maxCaracter($iva0, 5), 0, 1, 'L', 0);
$pdf->Text(255, 185, "Iva%", 0, 1, 'L', 0);
$pdf->Text(275, 185, maxCaracter($iva_venta, 5), 0, 1, 'L', 0);
//$pdf->Text(249, 186, '12', 0, 1, 'L', 0);
$pdf->Text(255, 191, "Des", 0, 1, 'L', 0);
$pdf->Text(275, 191, maxCaracter($descuento_venta, 5), 0, 1, 'L', 0);
$pdf->Text(255, 197, "Total", 0, 1, 'L', 0);
$pdf->Text(275, 197, maxCaracter($total_venta, 10), 0, 1, 'L', 0);


$pdf->Output();
?>