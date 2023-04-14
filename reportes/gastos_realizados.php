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
        //Set the array of column widths
        $this->widths = $w;
    }

    function SetAligns($a)
    {
        //Set the array of column alignments
        $this->aligns = $a;
    }

    function Row($data, $border = 0, $style = "", $fill = false)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        $h = 5 * $nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();

            if ($border == 1) {
                //Draw the border
                $this->Rect($x, $y, $w, $h, $style);
            }

            $this->MultiCell($w, 5, $data[$i], 0, $a, $fill);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function CheckPageBreak($h)
    {
        //If the height h would cause an overflow, add a new page immediately
        if ($this->GetY() + $h > $this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w, $txt)
    {
        //Computes the number of lines a MultiCell of width w will take
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
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

    function GetMultiCellHeight($w, $h, $txt, $border = null, $align = 'J')
    {
        // Calculate MultiCell with automatic or explicit line breaks height
        // $border is un-used, but I kept it in the parameters to keep the call
        //   to this function consistent with MultiCell()
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $ns = 0;
        $height = 0;
        while ($i < $nb) {
            // Get next character
            $c = $s[$i];
            if ($c == "\n") {
                // Explicit line break
                if ($this->ws > 0) {
                    $this->ws = 0;
                    $this->_out('0 Tw');
                }
                //Increase Height
                $height += $h;
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $ns = 0;
                continue;
            }
            if ($c == ' ') {
                $sep = $i;
                $ls = $l;
                $ns++;
            }
            $l += $cw[$c];
            if ($l > $wmax) {
                // Automatic line break
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                    if ($this->ws > 0) {
                        $this->ws = 0;
                        $this->_out('0 Tw');
                    }
                    //Increase Height
                    $height += $h;
                } else {
                    if ($align == 'J') {
                        $this->ws = ($ns > 1) ? ($wmax - $ls) / 1000 * $this->FontSize / ($ns - 1) : 0;
                        $this->_out(sprintf('%.3F Tw', $this->ws * $this->k));
                    }
                    //Increase Height
                    $height += $h;
                    $i = $sep + 1;
                }
                $sep = -1;
                $j = $i;
                $l = 0;
                $ns = 0;
            } else
                $i++;
        }
        // Last chunk
        if ($this->ws > 0) {
            $this->ws = 0;
            $this->_out('0 Tw');
        }
        //Increase Height
        $height += $h;

        return $height;
    }

    function GetCurrentWidth()
    {
        return $this->w - ($this->lMargin * 2);
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
        $this->Cell(105, 5, "GASTOS", 0, 1, 'C', 0);
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
        $this->Cell(210, 5, utf8_decode("REPORTE DE GASTOS POR PROVEEDOR"), 0, 1, 'C', 0);
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
$pdf->SetTitle('Gastos por Proveedor');
$pdf->SetMargins(1, 0);
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
$id_fac = 0;
$fecha = 0;
$contador = 0;
$num_fac = 0;
$valor_pagado = 0;
$repetido = 0;
$sql = pg_query("SELECT DISTINCT(id_proveedor) FROM gastos where fecha_actual $query_fecha '$_GET[fin]'");
while ($row = pg_fetch_row($sql)) {

    $id_prov = $row[0];
    $repetido = 0;
    $sql2 = pg_query("SELECT id_gastos,
     num_factura,
     gastos.fecha_actual,
     fecha_emision,
     t.concepto descripcion,
     total,
     identificacion_pro, 
     empresa_pro,
     gastos.comprobante 
     FROM gastos 
     left join transacciones t on
     gastos.id_gastos::text=t.comprobante
     and t.identificador_cli_pro='GAS',
     proveedores 
     where gastos.fecha_actual 
     between '$_GET[inicio]' and '$_GET[fin]' 
     and gastos.id_proveedor='$id_prov' 
     and gastos.id_proveedor=proveedores.id_proveedor 
     and gastos.estado='Activo'
     order by num_factura asc");
    while ($row2 = pg_fetch_row($sql2)) {
        if ($repetido == 0) {
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->SetX(1);
            $pdf->SetFillColor(187, 179, 180);
            $pdf->Cell(70, 6, maxCaracter(utf8_decode('RUC/CI.: ' . $row2[6]), 35), 1, 0, 'L', 1);
            $pdf->Cell(135, 6, maxCaracter(utf8_decode('NOMBRES: ' . $row2[7]), 50), 1, 1, 'L', 1);
            $pdf->Ln(1);

            $totalw=$pdf->GetCurrentWidth();
            $colw=$totalw/6;
            $pdf->SetWidths([
                $colw-15,
                $colw-13,
                $colw-13,
                $colw-13,
                $colw+67,
                $colw-13
            ]);
            $pdf->SetAligns(array_fill(0,7,"C"));
            $pdf->Row([
                utf8_decode('Nro.'),
                utf8_decode('Nro. Factura'),
                utf8_decode('F. Registro'),
                utf8_decode('F. Factura'),
                utf8_decode('Descripción'),
                utf8_decode('Total'),
            ],1);
           /*  $pdf->SetX(1);
            $pdf->Cell(25, 6, utf8_decode('Nro. Factura'), 1, 0, 'C', 0);
            $pdf->Cell(30, 6, utf8_decode('Fecha Registro'), 1, 0, 'C', 0);
            $pdf->Cell(30, 6, utf8_decode('Fecha Factura'), 1, 0, 'C', 0);
            $pdf->Cell(100, 6, utf8_decode('Descripción'), 1, 0, 'C', 0);
            $pdf->Cell(20, 6, utf8_decode('Total'), 1, 1, 'C', 0); */
            $valor_pagado = 0;
            $repetido = 1;
        }

        $pdf->SetFont('Helvetica', '', 9);
       /*  $pdf->SetX(1);
        $pdf->Cell(25, 6, substr($row2[1], 8, 30), 0, 0, 'C', 0);
        $pdf->Cell(30, 6, utf8_decode($row2[2]), 0, 0, 'C', 0);
        $pdf->Cell(30, 6, utf8_decode($row2[3]), 0, 0, 'C', 0);
        $pdf->Cell(100, 6, maxCaracter(utf8_decode($row2[4]), 20), 0, 0, 'L', 0);
        $pdf->Cell(20, 6, utf8_decode($row2[5]), 0, 1, 'C', 0); */
        $pdf->Row([
            utf8_decode($row2[8]),
            substr($row2[1], 8, 30),
            utf8_decode($row2[2]),
            utf8_decode($row2[3]),
            maxCaracter(utf8_decode($row2[4]), 50),
            utf8_decode($row2[5]),
        ]);
        $valor_pagado = $valor_pagado + $row2[5];
    }
    $pdf->SetFont('Helvetica', 'B', 9);
    $pdf->SetX(1);
    $pdf->Cell(207, 0, utf8_decode(""), 1, 1, 'R', 0);
    $pdf->Cell(180, 6, utf8_decode("Valor Total: "), 0, 0, 'R', 0);
    $pdf->Cell(30, 6, maxCaracter((number_format(($valor_pagado), 2, ',', '.')), 20), 0, 1, 'C', 0);
    $pdf->Ln(3);
}
$pdf->Output();
