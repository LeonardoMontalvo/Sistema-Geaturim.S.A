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
        $this->Cell(105, 5, "TRANSFERENCIAS", 0, 1, 'C', 0);
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
        $this->Cell(210, 5, utf8_decode("REPORTE DE EGRESOS POR TRANSFERENCIA"), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->Ln(3);
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
$pdf->SetTitle('Egresos');
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Amble-Regular', '', 9);

$query_fecha = "";
// RANGO DE FECHAS O FECHA ACTUAL
if ($pdf->rango) {
    $query_fecha = "BETWEEN '$_GET[inicio]' AND";
} else {
    $query_fecha = "=";
}
$total = 0;
$sub = 0;
$repetido = 0;
$contador = 0;
$pv = 0;
$pc = 0;
$util = 0;
$consulta = pg_query("
select 
e.id_egresos, 
e.fecha_actual, 
e.origen, 
e.destino,
e.tarifa0, 
e.tarifa12,
e.iva_egreso, 
e.descuento_egreso, 
e.total_egreso,
pvo.nombre_punto origen,
pvd.nombre_punto destino  
from egresos e
left join punto_venta pvo
on pvo.id_punto_venta = origen
left join punto_venta pvd
on pvd.id_punto_venta = destino
where e.fecha_actual $query_fecha '$_GET[fin]' and e.estado='Activo'");
while ($row = pg_fetch_assoc($consulta)) {
    $repetido = 0;
    $sql1 = pg_query("select d.id_egresos, p.articulo, d.cantidad, d.precio_costo, d.descuento, d.total from detalle_egreso d, productos p where d.id_egresos=$row[id_egresos] and d.cod_productos=p.cod_productos  and d.estado='Activo'");
    if (pg_num_rows($sql1)) {
        if ($repetido == 0) {
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->SetX(3);
            $pdf->SetFillColor(216, 216, 231);
            $pdf->Cell(40, 8, utf8_decode("FECHA: " . $row["fecha_actual"]), 1, 0, 'L', true);
            $pdf->Cell(80, 8, maxCaracter(utf8_decode("ORIGEN: " . $row["origen"]),50), 1, 0, 'L', true);
            $pdf->Cell(80, 8, maxCaracter(utf8_decode("DESTINO: " . $row["destino"]),49), 1, 1, 'L', true);
            $pdf->Ln(1);
            $pdf->SetX(5);
            $pdf->Cell(20, 6, utf8_decode('Cantidad'), 1, 0, 'C', 0);
            $pdf->Cell(105, 6, utf8_decode('Producto'), 1, 0, 'C', 0);
            $pdf->Cell(25, 6, utf8_decode('Precio Costo'), 1, 0, 'C', 0);
            $pdf->Cell(25, 6, utf8_decode('Descuento'), 1, 0, 'C', 0);
            $pdf->Cell(20, 6, utf8_decode('Total'), 1, 1, 'C', 0);
            $repetido = 1;
        }
        while ($row1 = pg_fetch_assoc($sql1)) {
            $pdf->SetFont('Helvetica', '', 9);
            $pv = 0;
            $pc = 0;
            $util = 0;
            $pdf->SetX(5);
            $pdf->Cell(20, 6, $row1["cantidad"], 0, 0, 'C', false);
            $pdf->Cell(105, 6, maxCaracter($row1["articulo"], 50), 0, 0, 'C', false);
            $pdf->Cell(25, 6, utf8_decode($row1["precio_costo"]), 0, 0, 'C', false);
            $pdf->Cell(25, 6, utf8_decode($row1["descuento"]), 0, 0, 'C', false);
            $pdf->Cell(20, 6, number_format($row1["total"], 2, '.', ''), 0, 1, 'C', false);
            $total = $total + $util;
        }
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Ln(5);
        $pdf->Cell(180, 6, utf8_decode('Tarifa 0: '), 0, 0, 'R', 0);
        $pdf->Cell(20, 6, (number_format($row["tarifa0"], 2, ',', '.')), 0, 0, 'C', 0);
        $pdf->Ln(5);
        $pdf->Cell(180, 6, utf8_decode('Tarifa 12:'), 0, 0, 'R', 0);
        $pdf->Cell(20, 6, (number_format($row["tarifa12"], 2, ',', '.')), 0, 0, 'C', 0);
        $pdf->Ln(5);
        $pdf->Cell(180, 6, utf8_decode('Iva: '), 0, 0, 'R', 0);
        $pdf->Cell(20, 6, (number_format($row["iva_egreso"], 2, ',', '.')), 0, 0, 'C', 0);
        $pdf->Ln(5);
        $pdf->Cell(180, 6, utf8_decode('Descuento: '), 0, 0, 'R', 0);
        $pdf->Cell(20, 6, (number_format($row["descuento_egreso"], 2, ',', '.')), 0, 0, 'C', 0);
        $pdf->Ln(5);
        $pdf->Cell(180, 6, utf8_decode('Total: '), 0, 0, 'R', 0);
        $pdf->Cell(20, 6, (number_format($row["total_egreso"], 2, ',', '.')), 0, 0, 'C', 0);
        $total = 0;
    }
    $pdf->Ln(6);
}
$pdf->Output();
