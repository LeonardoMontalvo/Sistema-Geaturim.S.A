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
        $this->rango = false;
        if ($_GET['inicio'] != '') {
            $this->rango = true;
        }
        $this->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->SetFont('Amble-Regular', '', 10);
        $fecha = date('Y-m-d', time());
        $this->SetX(0);
        $this->SetY(0);
        $this->Cell(105, 5, $fecha, 0, 0, 'C', 0);
        $this->Cell(105, 5, "COMPRAS", 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(210, 8, utf8_decode($_SESSION['nombre_empresa']), 0, 1, 'C', 0);
        $this->Image('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 10, 7, 15, 15);
        $this->Image('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 180, 7, 15, 15);
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
        $this->Cell(210, 5, utf8_decode("RESUMEN DE FACTURAS COMPRAS"), 0, 1, 'C', 0);
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
        $this->Cell(10, 6, utf8_decode('Comp.'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('Identificación'), 1, 0, 'C', 1);
        $this->Cell(90, 6, utf8_decode('Proveedor'), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode('Fecha Emi.'), 1, 0, 'C', 1);
        $this->Cell(35, 6, utf8_decode('Nro Factura'), 1, 0, 'C', 1);
        $this->Cell(15, 6, utf8_decode('Subtotal'), 1, 0, 'C', 1);
        $this->Cell(15, 6, utf8_decode('Dsco'), 1, 0, 'C', 1);
        $this->Cell(15, 6, utf8_decode('0%'), 1, 0, 'C', 1);
        $this->Cell(15, 6, utf8_decode('12%'), 1, 0, 'C', 1);
        $this->Cell(15, 6, utf8_decode('IVA'), 1, 0, 'C', 1);
        $this->Cell(15, 6, utf8_decode('Total'), 1, 1, 'C', 1);
        $this->SetFillColor(255, 255, 225);
        $this->SetLineWidth(0.2);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

}

$condprov = "";
if (!empty($_GET["id_proveedor"])) {
    $condprov = " and factura_compra.id_proveedor=" . $_GET["id_proveedor"];
}

$pdf = new PDF('L', 'mm', 'a4');
$pdf->SetTitle('Facturas Detalladas');
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->AliasNbPages();
$t0 = 0;
$t12 = 0;
$total = 0;
$repetido = 0;
$sub = 0;
$t0 = 0;
$t12 = 0;
$desc = 0;
$ivaT = 0;
$consulta = pg_query('select * from proveedores order by id_proveedor asc');
if (pg_num_rows($consulta)) {
//    while ($row = pg_fetch_row($consulta)) {
        $query_fecha = "";
        // RANGO DE FECHAS O FECHA ACTUAL
        if ($pdf->rango) {
            $query_fecha = "BETWEEN '$_GET[inicio]' AND";
        } else {
            $query_fecha = "=";
        }
        $consulta1 = pg_query(
                "SELECT 
            num_serie,
            fecha_emision,
            hora_actual,
            fecha_cancelacion,
            num_autorizacion,
            factura_compra.forma_pago,
            tarifa0,
            tarifa12,
            iva_compra,
            descuento_compra,
            total_compra,
            empresa_pro,
            identificacion_pro,
            representante_legal,
            id_factura_compra,
            comprobante
            
            FROM factura_compra,proveedores 
            where factura_compra.id_proveedor=proveedores.id_proveedor 
            and tipo_comprobante='FACTURA' 
     
            and factura_compra.estado='Activo' 
            and fecha_emision $query_fecha '$_GET[fin]' 
            $condprov
             order by factura_compra.fecha_emision,factura_compra.comprobante
            asc"
        );
        if (pg_num_rows($consulta1)) {
            while ($row1 = pg_fetch_row($consulta1)) {
                $pdf->SetX(1);
                $pdf->SetFont('helvetica', '', 9);
                $pdf->SetX(1);
            $pdf->SetFont('helvetica', '', 9);
            $pdf->Cell(10, 6, utf8_decode($row1[15]), 0, 0, 'C', 0);

            $pdf->Cell(25, 6, utf8_decode($row1[12]), 0, 0, 'C', 0);
            $pdf->Cell(90, 6, utf8_decode(substr($row1[11], 0, 40)), 0, 0, 'L', 0);

            $pdf->Cell(20, 6, utf8_decode($row1[1]), 0, 0, 'C', 0);
            $pdf->Cell(35, 6, utf8_decode($row1[0]), 0, 0, 'C', 0);
                $sub = $sub + ($row1[10] - $row1[8] + $row1[9]);
                $t0 = $t0 + $row1[6];
                $t12 = $t12 + $row1[7];
                $pdf->Cell(15, 6, utf8_decode(truncateFloat(round($row1[10] - $row1[8] + $row1[9], 2, PHP_ROUND_HALF_EVEN), 2)), 0, 0, 'R', 0);
                $desc = $desc + $row1[9];
                $pdf->Cell(15, 6, utf8_decode(truncateFloat(round($row1[9], 2, PHP_ROUND_HALF_EVEN), 2)), 0, 0, 'R', 0);
                $pdf->Cell(15, 6, utf8_decode(truncateFloat(round($row1[6], 2, PHP_ROUND_HALF_EVEN), 2)), 0, 0, 'R', 0);
                $pdf->Cell(15, 6, utf8_decode(truncateFloat(round($row1[7], 2, PHP_ROUND_HALF_EVEN), 2)), 0, 0, 'R', 0);
                $ivaT = $ivaT + $row1[8];
                $pdf->Cell(15, 6, utf8_decode(truncateFloat(round($row1[8], 2, PHP_ROUND_HALF_EVEN), 2)), 0, 0, 'R', 0);
                $total = $total + $row1[10];
                $pdf->Cell(15, 6, utf8_decode(truncateFloat(round($row1[10], 2, PHP_ROUND_HALF_EVEN), 2)), 0, 1, 'R', 0);
            }
        }
    }
//    }
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->SetX(1);
    $pdf->Ln(8);
    $pdf->Cell(250, 6, utf8_decode("Tarifa 0"), 0, 0, 'R', 0);
    $pdf->Cell(20, 6, maxCaracter((number_format($t0, 2, ',', '.')), 20), 0, 1, 'R', 0);
    $pdf->Cell(250, 6, utf8_decode("Tarifa 12"), 0, 0, 'R', 0);
    $pdf->Cell(20, 6, maxCaracter((number_format($t12, 2, ',', '.')), 20), 0, 1, 'R', 0);
    $pdf->Cell(250, 6, utf8_decode("Subtotal"), 0, 0, 'R', 0);
    $pdf->Cell(20, 6, maxCaracter((number_format($sub, 2, ',', '.')), 20), 0, 1, 'R', 0);
    $pdf->Cell(250, 6, utf8_decode("Descuento"), 0, 0, 'R', 0);
    $pdf->Cell(20, 6, maxCaracter((number_format($desc, 2, ',', '.')), 20), 0, 1, 'R', 0);
    $pdf->Cell(250, 6, utf8_decode("Iva Total"), 0, 0, 'R', 0);
    $pdf->Cell(20, 6, maxCaracter((number_format($ivaT, 2, ',', '.')), 20), 0, 1, 'R', 0);
    $pdf->Cell(250, 6, utf8_decode("Total"), 0, 0, 'R', 0);
    $pdf->Cell(20, 6, maxCaracter((number_format($total, 2, ',', '.')), 20), 0, 1, 'R', 0);
//}
$pdf->Output();
