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
        $this->Cell(210, 5, utf8_decode("UTILIDAD VENTAS"), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->Ln(3);
      /*   $this->SetFont('helvetica', 'B', 9);
        $this->SetFillColor(175, 215, 240);
        $this->Cell(24, 6, utf8_decode('Cod. Producto'), 1, 0, 'C', 1);
        $this->Cell(60, 6, utf8_decode('Descripción'), 1, 0, 'C', 1);
        $this->Cell(18, 6, utf8_decode('Cantidad'), 1, 0, 'C', 1);
        $this->Cell(18, 6, utf8_decode('Venta'), 1, 0, 'C', 1);
        $this->Cell(18, 6, utf8_decode('Total'), 1, 0, 'C', 1);
        $this->Cell(18, 6, utf8_decode('Costo'), 1, 0, 'C', 1);
        $this->Cell(18, 6, utf8_decode('Total'), 1, 0, 'C', 1);
        $this->Cell(18, 6, utf8_decode('Utilidad'), 1, 0, 'C', 1);
        $this->Cell(18, 6, utf8_decode('%'), 1, 1, 'C', 1);
        $this->SetFillColor(255, 255, 225);
        $this->SetLineWidth(0.2); */
    }
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDF('P', 'mm', 'a4');
$pdf->SetTitle('Utilidades');
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

$sql1 = pg_query(
    "SELECT id_factura_venta, identificacion, nombres_cli, num_factura, total_venta from factura_venta fv, clientes c where fecha_actual $query_fecha '$_GET[fin]' 
    and fv.id_cliente=c.id_cliente and fv.estado='Activo'"
);
if (pg_num_rows($sql1)) {
    $total = 0;
    while ($row1 = pg_fetch_row($sql1)) {
        $pdf->SetFont('helvetica', 'B', 8.5);
        $pdf->SetFillColor(220, 240, 210);
        $pdf->Cell(50, 6, utf8_decode("RUC/CI: " . $row1[1]), 0, 0, 'L', 1);
        $pdf->Cell(70, 6, utf8_decode("CLIENTE: " . $row1[2]), 0, 0, 'L', 1);
        $pdf->Cell(50, 6, utf8_decode('Nro Factura: ' . $row1[3]), 0, 0, 'L', 1);
        $pdf->Cell(40, 6, utf8_decode('Total Factura: ' . number_format($row1[4], 2, ',', '.')), 0, 1, 'R', 1);

        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetFillColor(175, 215, 240);
        $pdf->Cell(24, 6, utf8_decode('Cod. Producto'), 1, 0, 'C', 1);
        $pdf->Cell(60, 6, utf8_decode('Descripción'), 1, 0, 'C', 1);
        $pdf->Cell(18, 6, utf8_decode('Cantidad'), 1, 0, 'C', 1);
        $pdf->Cell(18, 6, utf8_decode('Venta'), 1, 0, 'C', 1);
        $pdf->Cell(18, 6, utf8_decode('Total'), 1, 0, 'C', 1);
        $pdf->Cell(18, 6, utf8_decode('Costo'), 1, 0, 'C', 1);
        $pdf->Cell(18, 6, utf8_decode('Total'), 1, 0, 'C', 1);
        $pdf->Cell(18, 6, utf8_decode('Utilidad'), 1, 0, 'C', 1);
        $pdf->Cell(18, 6, utf8_decode('%'), 1, 1, 'C', 1);
        $pdf->SetFillColor(255, 255, 225);
        $pdf->SetLineWidth(0.2);

        $sql2 = pg_query(
            "SELECT codigo, articulo, cantidad, precio_venta, total_venta, precio_compra
            from detalle_factura_venta d, productos p where d.cod_productos=p.cod_productos and id_factura_venta='$row1[0]'"
        );
        if (pg_num_rows($sql2)) {
            $sub = 0;
            $porcent = 0;
            while ($row2 = pg_fetch_row($sql2)) {
                $pdf->SetX(1);
                $pdf->SetFont('helvetica', '', 9);
                $pdf->Cell(23, 6, maxCaracter($row2[0], 13), 0, 0, 'L', 0);
                $pdf->Cell(60, 6, maxCaracter($row2[1], 30), 0, 0, 'L', 0);
                $pdf->Cell(18, 6, number_format($row2[2], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(18, 6, number_format($row2[3], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(18, 6, number_format($row2[4], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(18, 6, number_format($row2[5], 2, ',', '.'), 0, 0, 'R', 0);
                $porcent = $row2[2] * $row2[5];
                $pdf->Cell(18, 6, number_format($porcent, 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(18, 6, number_format(($row2[4]) - ($porcent), 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(18, 6, number_format(((($row2[4] - $porcent) / ($porcent)) * 100), 0, ',', '.') . addslashes('%'), 0, 1, 'C', 0);
                $sub += ($row2[4] - $porcent);
            }
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->Cell(210, 0, utf8_decode(''), 1, 1, 'R', 1);
            $pdf->Cell(172, 6, utf8_decode('Utilidad por factura'), 0, 0, 'R', 0);
            $pdf->Cell(20, 6, (number_format($sub, 2, ',', '.')), 0, 1, 'R', 0);
            $total += $sub;
        }
        $pdf->Ln(3);
    }
    $pdf->SetFont('helvetica', 'B', 9.5);
    $pdf->Cell(210, 0, utf8_decode(''), 1, 1, 'R', 1);
    $pdf->SetX(1);
    $pdf->Cell(172, 6, utf8_decode('Total Utilidades'), 0, 0, 'R', 0);
    $pdf->Cell(20, 6, (number_format($total, 2, ',', '.')), 0, 1, 'R', 0);
}
$pdf->Output();
