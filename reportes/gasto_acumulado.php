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
        $this->Cell(165, 5, $fecha, 0, 0, 'C', 0);
        $this->Cell(105, 5, "GASTOS", 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(300, 8, $_SESSION['nombre_empresa'], 0, 1, 'C', 0);
        $this->Image('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 80, 7, 15, 15);
        $this->Image('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 210, 7, 15, 15);
        // $this->Cell(180, 5, "PROPIETARIO: " . utf8_decode($_SESSION['propietario']), 0, 1, 'C', 0);
        // $this->Cell(80, 5, "TEL.: " . utf8_decode($_SESSION['telefono']), 0, 0, 'R', 0);
        // $this->Cell(80, 5, "CEL.: " . utf8_decode($_SESSION['celular']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, "DIR.: " . utf8_decode($_SESSION['direccion']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, "SLOGAN.: " . utf8_decode($_SESSION['slogan']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, utf8_decode($_SESSION['pais_ciudad']), 0, 1, 'C', 0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);
        // $this->Line(0, 25, 210, 25);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(310, 5, utf8_decode("REPORTE DE GASTOS GENERAL"), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        $this->Ln(4);
        if ($this->rango) {
            $this->Cell(165, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->Line(2, $this->GetY(), $this->GetCurrentWidth(), $this->GetY());
        $this->Ln(3);
        $this->SetFillColor(255, 255, 225);
        $this->SetLineWidth(0.2);

        $totalw = $this->GetCurrentWidth();
        $colw = $totalw / 13;
        $widths = [
            $colw - 6,
            $colw - 2,
            $colw - 2,
            $colw - 2,
            $colw + 47,
            16,
            16,
            16,
            16,
            16,
            16,
            16,
            16,
            16
        ];
        $this->SetFont('Helvetica', 'B', 8);
        $this->SetWidths($widths);
        $this->setAligns(array_fill(0, 14, "C"));
        $this->SetFillColor(175, 215, 240);
        $this->Row([
            utf8_decode('Num.'),
            utf8_decode('Nro. Factura'),
            utf8_decode('F. Emisión'),
            utf8_decode('F. Registro'),
            utf8_decode('Proveedor'),
            utf8_decode('Sub 0%'),
            utf8_decode('Sub 5%'),
            utf8_decode('Sub 8%'),
            utf8_decode('Sub 15%'),
            utf8_decode('Subtotal'),
            utf8_decode('IVA 5%'),
            utf8_decode('IVA 8%'),
            utf8_decode('IVA 15%'),
            utf8_decode('Total')
        ], 1, "", true);
    }
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDF('L', 'mm', 'a4');
$pdf->SetTitle('Gastos General');
$pdf->SetMargins(2, 0);
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
$cliente = "";
$acumulado = 0;
$tot = 0;

$pdf->SetFont('Helvetica', 'B', 8);
//$pdf->SetX(1);

$totalw = $pdf->GetCurrentWidth();
$colw = $totalw / 13;
$widths = [
    $colw - 6,
    $colw - 2,
    $colw - 2,
    $colw - 2,
    $colw + 47,
    16,
    16,
    16,
    16,
    16,
    16,
    16,
    16,
    16
];

/* $pdf->SetWidths($widths);
$pdf->setAligns(array_fill(0, 14, "C"));
$pdf->SetFillColor(175, 215, 240);
$pdf->Row([
    utf8_decode('Num.'),
    utf8_decode('Nro. Factura'),
    utf8_decode('F. Emisión'),
    utf8_decode('F. Registro'),
    utf8_decode('Proveedor'),
    utf8_decode('Sub 0%'),
    utf8_decode('Sub 5%'),
    utf8_decode('Sub 8%'),
    utf8_decode('Sub 15%'),
    utf8_decode('Subtotal'),
    utf8_decode('IVA 5%'),
    utf8_decode('IVA 8%'),
    utf8_decode('IVA 15%'),
    utf8_decode('Total')
], 1, "", true); */

/* $pdf->Cell(15, 6, utf8_decode('Num.'), 1, 0, 'C', 0);
$pdf->Cell(20, 6, utf8_decode('Nro. Factura'), 1, 0, 'C', 0);
$pdf->Cell(18, 6, utf8_decode('F. Emisión'), 1, 0, 'C', 0);
$pdf->Cell(18, 6, utf8_decode('F. Registro'), 1, 0, 'C', 0);
$pdf->Cell(55, 6, utf8_decode('Proveedor'), 1, 0, 'C', 0);
$pdf->Cell(22, 6, utf8_decode('Subt. IVA 15%'), 1, 0, 'C', 0);
$pdf->Cell(20, 6, utf8_decode('Subt. IVA 0%'), 1, 0, 'C', 0);
$pdf->Cell(15, 6, utf8_decode('IVA'), 1, 0, 'C', 0);
$pdf->Cell(25, 6, utf8_decode('Total'), 1, 1, 'C', 0); */
//$pdf->Cell(55, 6, utf8_decode('Descripción'), 1, 1, 'C', 0);
//$sql = pg_query("select num_factura,fecha_actual,fecha_emision,empresa_pro, total, descripcion from gastos, proveedores where proveedores.id_proveedor=gastos.id_proveedor and fecha_emision $query_fecha '$_GET[fin]' order by fecha_emision");
$sql = pg_query(
    "
select 
num_factura,
fecha_actual,
fecha_emision,
empresa_pro, 
total_compra, 
descripcion,
tarifa12,
tarifa0,
id_gastos,
gastos.iva_compra,
gastos.subtotal    
from 
gastos,
proveedores 
where proveedores.id_proveedor=gastos.id_proveedor 
and gastos.estado='Activo'
and fecha_emision $query_fecha '$_GET[fin]' order by fecha_emision
"
);
$siva12 = 0;
$siva0 = 0;
$iva12 = 0;
$tsubt = 0;
if (pg_num_rows($sql)) {
    while ($row = pg_fetch_row($sql)) {
        $pdf->SetFont('Helvetica', '', 8);

        $tarifasiva = obtenerTarifasImpuestoFactura($row[8]);
        $subtarifas = [];
        $valsiva = [];
        foreach ($tarifasiva as $value) {
            $subtarifas[round($value["tarifa"], 0)] = number_format($value["base_imponible"], 2);
            $valsiva[round($value["tarifa"], 0)] = number_format($value["valor_impuesto"], 2);
            if (empty($totalsubtarifas[round($value["tarifa"], 0)])) {
                $totalsubtarifas[round($value["tarifa"], 0)] = 0;
                $totaltarifas[round($value["tarifa"], 0)] = 0;
            }
            $totalsubtarifas[round($value["tarifa"], 0)] += $value["base_imponible"];
            $totaltarifas[round($value["tarifa"], 0)] += $value["valor_impuesto"];
        }

        if (empty($tarifasiva)) {
            if ($row[1] < '2024-04-01') {
                if (empty($totalsubtarifas[0])) {
                    $totalsubtarifas[0] = 0;
                    $totaltarifas[0] = 0;
                }
                if (empty($totalsubtarifas[12])) {
                    $totalsubtarifas[12] = 0;
                    $totaltarifas[12] = 0;
                }
                $totalsubtarifas[0] += $row[6];
                $totalsubtarifas[12] += $row[7];
                $totaltarifas[12] += $row[8];
                $subtarifas[0] = number_format($row[7], 2);
                $subtarifas[12] = number_format($row[6], 2);
                $valsiva[12] = number_format($row[9], 2);
            } else {
                if (empty($totalsubtarifas[0])) {
                    $totalsubtarifas[0] = 0;
                    $totaltarifas[0] = 0;
                }
                if (empty($totalsubtarifas[12])) {
                    $totalsubtarifas[15] = 0;
                    $totaltarifas[15] = 0;
                }
                $totalsubtarifas[0] += $row[6];
                $totalsubtarifas[15] += $row[7];
                $totaltarifas[15] += $row[8];
                $subtarifas[0] = number_format($row[7], 2);
                $subtarifas[15] = number_format($row[6], 2);
                $valsiva[15] = number_format($row[9], 2);
            }
        }


        $pdf->SetWidths($widths);
        $pdf->setAligns([
            "C",
            "C",
            "C",
            "C",
            "L",
            "R",
            "R",
            "R",
            "R",
            "R",
        ]);
        $pdf->Row([
            $row[8],
            substr($row[0], 8, 30),
            utf8_decode($row[2]),
            utf8_decode($row[1]),
            utf8_decode($row[3]),
            (!empty($subtarifas[0]) ? $subtarifas[0] : "0.00"),
            (!empty($subtarifas[5]) ? $subtarifas[5] : "0.00"),
            (!empty($subtarifas[8]) ? $subtarifas[8] : "0.00"),
            (!empty($subtarifas[15]) ? $subtarifas[15] : "0.00"),
            round($row[10], 2),
            (!empty($valsiva[5]) ? $valsiva[5] : "0.00"),
            (!empty($valsiva[8]) ? $valsiva[8] : "0.00"),
            (!empty($valsiva[15]) ? $valsiva[15] : "0.00"),
            round($row[4], 2)
        ], 1);
        /*  $pdf->SetX(1);
        $pdf->Cell(15, 6, $row[8], 0, 0, 'C', 0);
        $pdf->Cell(20, 6, substr($row[0], 8, 30), 0, 0, 'C', 0);
        $pdf->Cell(18, 6, utf8_decode($row[2]), 0, 0, 'C', 0);
        $pdf->Cell(18, 6, utf8_decode($row[1]), 0, 0, 'C', 0);
        $pdf->Cell(55, 6, maxCaracter(utf8_decode($row[3]), 25), 0, 0, 'L', 0);
        $pdf->Cell(22, 6, number_format($row[6],2,",","."), 0, 0, 'C', 0);
        $pdf->Cell(20, 6, number_format($row[7],2,",","."), 0, 0, 'C', 0);
        $pdf->Cell(15, 6, number_format($row[9],2,",","."), 0, 0, 'C', 0);
        $pdf->Cell(25, 6, utf8_decode($row[4]), 0, 1, 'C', 0); */
        //$pdf->Cell(55, 6, maxCaracter(utf8_decode($row[5]), 30), 0, 1, 'L', 0);
        $acumulado = $acumulado + $row[4];
        $siva12 += $row[6];
        $siva0 += $row[7];
        $iva12 += $row[9];
        $tsubt += $row[10];
    }
    $pdf->SetFont('Helvetica', 'B', 9);
    $pdf->SetWidths($widths);
    $pdf->setAligns([
        "C",
        "C",
        "C",
        "C",
        "R",
        "R",
        "R",
        "R",
        "R",
        "R",
    ]);
    $pdf->Line(2, $pdf->GetY(), $totalw, $pdf->GetY());
    $pdf->Row([
        "",
        "",
        "",
        "",
        "Totales:",
        (!empty($totalsubtarifas[0]) ? number_format($totalsubtarifas[0], 2, ',', '.') : "0.00"),
        (!empty($totalsubtarifas[5]) ? number_format($totalsubtarifas[5], 2, ',', '.') : "0.00"),
        (!empty($totalsubtarifas[8]) ? number_format($totalsubtarifas[8], 2, ',', '.') : "0.00"),
        (!empty($totalsubtarifas[15]) ? number_format($totalsubtarifas[15], 2, ',', '.') : "0.00"),
        (!empty($totaltarifas[5]) ? number_format($totaltarifas[5], 2, ',', '.') : "0.00"),
        (!empty($totaltarifas[8]) ? number_format($totaltarifas[8], 2, ',', '.') : "0.00"),
        (!empty($totaltarifas[15]) ? number_format($totaltarifas[15], 2, ',', '.') : "0.00"),
        number_format(($tsubt), 2, ',', '.'),
        number_format(($acumulado), 2, ',', '.'),
    ]);
    //$pdf->SetX(1);
    /* $pdf->Cell(207, 0, utf8_decode(""), 1, 1, 'R', 0);
    $pdf->Cell(126, 6, utf8_decode("Totales Gastos"), 0, 0, 'R', 0);
    $pdf->Cell(22, 6, maxCaracter((number_format(($siva12), 2, ',', '.')), 20), 0, 0, 'C', 0);
    $pdf->Cell(20, 6, maxCaracter((number_format(($siva0), 2, ',', '.')), 20), 0, 0, 'C', 0);
    $pdf->Cell(15, 6, maxCaracter((number_format(($iva12), 2, ',', '.')), 20), 0, 0, 'C', 0);
    $pdf->Cell(25, 6, maxCaracter((number_format(($acumulado), 2, ',', '.')), 20), 0, 0, 'C', 0); */
}
$pdf->Output();


function obtenerTarifasImpuestoFactura($id)
{
    $sql = "select
    di.cod_impuesto, 
    di.cod_tarifa, 
    di.tarifa, 
    sum(di.valor_impuesto)valor_impuesto, 
    sum(di.base_imponible)base_imponible
    from
    gastos fc
    inner join detalle_gastos dfc
    using(id_gastos)
    inner join detalle_impuesto_producto_gasto di
    using(id_detalle_gastos)
    where id_gastos=$id
    group by di.cod_tarifa, di.cod_impuesto, di.tarifa";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (!empty($rows)) {
        return $rows;
    }
    return [];
}
