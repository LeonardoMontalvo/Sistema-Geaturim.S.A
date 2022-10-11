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
        $this->Cell(210, 8, $_SESSION['nombre_empresa'], 0, 1, 'C', 0);
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
        $this->Cell(210, 5, utf8_decode("RESUMEN GENERAL NOTAS DE VENTA"), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->Ln(3);
        $this->SetX(1);
        $this->SetFont('helvetica', 'B', 9);
        $this->SetFillColor(175, 215, 240);
        $this->Cell(26, 6, utf8_decode('Comprobante'), 1, 0, 'C', 1);
        $this->Cell(26, 6, utf8_decode('Fecha'), 1, 0, 'C', 1);
        $this->Cell(22, 6, utf8_decode('Subtotal'), 1, 0, 'C', 1);
        $this->Cell(22, 6, utf8_decode('Descuento'), 1, 0, 'C', 1);
        $this->Cell(22, 6, utf8_decode('Tarifa 0%'), 1, 0, 'C', 1);
        $this->Cell(22, 6, utf8_decode('Tarifa IVA'), 1, 0, 'C', 1);
        $this->Cell(22, 6, utf8_decode('Iva ..%'), 1, 0, 'C', 1);
        $this->Cell(22, 6, utf8_decode('Total'), 1, 0, 'C', 1);
        $this->Cell(24, 6, utf8_decode('Tipo Pago'), 1, 1, 'C', 1);
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
$pdf->SetTitle('General Nota Venta');
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->AliasNbPages();

$total = 0;
$sub = 0;
$desc = 0;
$ivaT = 0;
$t0 = 0;
$query_fecha = "";
// RANGO DE FECHAS O FECHA ACTUAL
if ($pdf->rango) {
    $query_fecha = "BETWEEN '$_GET[inicio]' AND";
} else {
    $query_fecha = "=";
}

$consulta1 = pg_query(
    "SELECT nv.fecha_actual, hora_actual, tipo_precio, forma_pago, tarifa0, tarifa12, iva_venta, descuento_venta,
    total_venta, identificacion,nombres_cli,id_facturas_novalidas,nv.estado 
    FROM facturas_novalidas nv, clientes c,usuario u where nv.id_cliente=c.id_cliente and u.id_usuario=nv.id_usuario 
    AND nv.fecha_actual $query_fecha '$_GET[fin]' order by nv.id_facturas_novalidas asc"
);

if (pg_num_rows($consulta1)) {
    while ($row1 = pg_fetch_row($consulta1)) {
        if ($row1[12] == "Activo") {
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetX(1);
            $pdf->Cell(26, 6, utf8_decode($row1[11]), 0, 0, 'C', 0);
            $pdf->Cell(26, 6, utf8_decode($row1[0]), 0, 0, 'C', 0);
            $sub = $sub + ($row1[8] - $row1[6] + $row1[7]);
            $pdf->Cell(22, 6, number_format($row1[8] - $row1[6] + $row1[7], 2, ',', '.'), 0, 0, 'R', 0);
            $desc = $desc + $row1[7];
            $pdf->Cell(22, 6, number_format($row1[7], 2, ',', '.'), 0, 0, 'R', 0);
            $pdf->Cell(22, 6, number_format($row1[4], 2, ',', '.'), 0, 0, 'R', 0);
            $pdf->Cell(22, 6, number_format($row1[5], 2, ',', '.'), 0, 0, 'R', 0);
            $ivaT = $ivaT + $row1[6];
            $pdf->Cell(22, 6, number_format($row1[6], 2, ',', '.'), 0, 0, 'R', 0);
            $total = $total + $row1[8];
            $t0 = $row1[4];
            $pdf->Cell(22, 6, number_format($row1[8], 2, ',', '.'), 0, 0, 'R', 0);
            $pdf->Cell(24, 6, $row1[3], 0, 1, 'C', 0);
        } else {
            if ($row1[12] == "Pasivo") {
                $pdf->SetTextColor(208, 17, 52);
                $pdf->SetFont('helvetica', '', 9);
                $pdf->SetX(1);
                $pdf->Cell(26, 6, utf8_decode($row1[11]), 0, 0, 'C', 0);
                $pdf->Cell(26, 6, utf8_decode($row1[0]), 0, 0, 'C', 0);
                $pdf->Cell(22, 6, number_format(($row1[8] - $row1[6] + $row1[7]), 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(22, 6, number_format($row1[7], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(22, 6, number_format($row1[4], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(22, 6, number_format($row1[5], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(22, 6, number_format($row1[6], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(22, 6, number_format($row1[8], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(24, 6, $row1[3], 0, 1, 'C', 0);
            }
        }
    }

    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
    $pdf->SetX(1);
    $pdf->Cell(52, 6, utf8_decode("Totales"), 0, 0, 'R', 0);
    $pdf->Cell(22, 6, maxCaracter((number_format($sub, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(22, 6, maxCaracter((number_format($desc, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(22, 6, maxCaracter((number_format($t0, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(22, 6, maxCaracter((number_format($sub - $desc, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(22, 6, maxCaracter((number_format($ivaT, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(22, 6, maxCaracter((number_format($total, 2, ',', '.')), 20), 0, 1, 'R', 0);
}
$pdf->Output();
