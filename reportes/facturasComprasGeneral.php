<?php

require('../fpdf/fpdf.php');
include '../procesos/base.php';
include '../procesos/funciones.php';
conectarse();
date_default_timezone_set('America/Guayaquil');
session_start();

$widthstabla = [];

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
        $pagew = $this->GetCurrentWidth();
        $this->rango = false;
        if ($_GET['inicio'] != '') {
            $this->rango = true;
        }
        $this->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->SetFont('Amble-Regular', '', 10);
        $fecha = date('Y-m-d', time());
        /* $this->SetX(0);
        $this->SetY(0); */
        $this->Cell($pagew / 2, 5, $fecha, 0, 0, 'C', 0);
        $this->Cell($pagew / 2, 5, "COMPRAS", 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell($pagew, 8, utf8_decode($_SESSION['nombre_empresa']), 0, 1, 'C', 0);
        $this->Image('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 10, 7, 15, 15);
        $this->Image('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], $pagew - 20, 7, 15, 15);
        // $this->SetFont('Amble-Regular', '', 10);
        // $this->Cell(190, 5, "PROPIETARIO: " . utf8_decode($_SESSION['propietario']), 0, 1, 'C', 0);
        // $this->Cell(80, 5, "TEL.: " . utf8_decode($_SESSION['telefono']), 0, 0, 'R', 0);
        // $this->Cell(80, 5, "CEL.: " . utf8_decode($_SESSION['celular']), 0, 1, 'C', 0);
        // $this->Cell(190, 5, "DIR.: " . utf8_decode($_SESSION['direccion']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, "SLOGAN.: " . utf8_decode($_SESSION['slogan']), 0, 1, 'C', 0);
        // $this->Cell(190, 5, utf8_decode($_SESSION['pais_ciudad']), 0, 1, 'C', 0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);
        $this->Line($this->lMargin, 25, $pagew, 25);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell($pagew, 5, utf8_decode("RESUMEN DE FACTURAS COMPRAS"), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell($pagew / 2, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell($pagew / 2, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->Ln(3);
        $this->SetFont('helvetica', 'B', 9);
        $this->SetFillColor(175, 215, 240);
        $GLOBALS["widthstabla"] = [15, 25, 46, 20, 35, 15, 15, 15, 15, 16, 16, 15, 15, 15, 15];
        $this->SetWidths($GLOBALS["widthstabla"]);
        $this->SetAligns([array_fill(0, 15, "C")]);

        $this->Row([
            utf8_decode('Com.'),
            utf8_decode('Identificación'),
            utf8_decode('Proveedor'),
            utf8_decode('Fecha Emi.'),
            utf8_decode('Nro Factura'),
            utf8_decode('Subtotal'),
            utf8_decode('Dsco'),
            utf8_decode('Sub 0%'),
            utf8_decode('Sub 5%'),
            utf8_decode('Sub 8%'),
            utf8_decode('Sub 15%'),
            utf8_decode('IVA 5%'),
            utf8_decode('IVA 8%'),
            utf8_decode('IVA 15%'),
            utf8_decode('Total')
        ], 1, "", true);

        /*$this->Cell(10, 6, utf8_decode('Comp.'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('Identificación'), 1, 0, 'C', 1);
        $this->Cell(90, 6, utf8_decode('Proveedor'), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode('Fecha Emi.'), 1, 0, 'C', 1);
        $this->Cell(35, 6, utf8_decode('Nro Factura'), 1, 0, 'C', 1);
        $this->Cell(15, 6, utf8_decode('Subtotal'), 1, 0, 'C', 1);
        $this->Cell(15, 6, utf8_decode('Dsco'), 1, 0, 'C', 1);
        $this->Cell(15, 6, utf8_decode('0%'), 1, 0, 'C', 1);
        $this->Cell(15, 6, utf8_decode('15%'), 1, 0, 'C', 1);
        $this->Cell(15, 6, utf8_decode('IVA'), 1, 0, 'C', 1);
        $this->Cell(15, 6, utf8_decode('Total'), 1, 1, 'C', 1);*/
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

$condprov = "";
if (!empty($_GET["id_proveedor"])) {
    $condprov = " and factura_compra.id_proveedor=" . $_GET["id_proveedor"];
}

$pdf = new PDF('L', 'mm', 'a4');
$pdf->SetTitle('Facturas Detalladas');
$pdf->SetMargins(2, 0);
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
$totalsubtarifas = [];
$totaltarifas = [];
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
            $sub = $sub + ($row1[10] - $row1[8] + $row1[9]);
            $t0 = $t0 + $row1[6];
            $t12 = $t12 + $row1[7];
            $desc = $desc + $row1[9];
            $ivaT = $ivaT + $row1[8];
            $total = $total + $row1[10];

            $pdf->SetFont('helvetica', '', 8);

            $tarifasiva = obtenerTarifasImpuestoFactura($row1[14]);
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
                if ($row1[1] < '2024-04-01') {
                    if (empty($totalsubtarifas[0])) {
                        $totalsubtarifas[0] = 0;
                        $totaltarifas[0] = 0;
                    }
                    if (empty($totalsubtarifas[12])) {
                        $totalsubtarifas[12] = 0;
                        $totaltarifas[12] = 0;
                    }
                    $totalsubtarifas[0] += $row1[6];
                    $totalsubtarifas[12] += $row1[7];
                    $totaltarifas[12] += $row1[8];
                    $subtarifas[0] = number_format($row1[6], 2);
                    $subtarifas[12] = number_format($row1[7], 2);
                    $valsiva[12] = number_format($row1[8], 2);
                } else {
                    if (empty($totalsubtarifas[0])) {
                        $totalsubtarifas[0] = 0;
                        $totaltarifas[0] = 0;
                    }
                    if (empty($totalsubtarifas[12])) {
                        $totalsubtarifas[15] = 0;
                        $totaltarifas[15] = 0;
                    }
                    $totalsubtarifas[0] += $row1[6];
                    $totalsubtarifas[15] += $row1[7];
                    $totaltarifas[15] += $row1[8];
                    $subtarifas[0] = number_format($row1[6], 2);
                    $subtarifas[15] = number_format($row1[7], 2);
                    $valsiva[15] = number_format($row1[8], 2);
                }
            }

            $pdf->SetWidths($widthstabla);
            $pdf->SetAligns(["C", "C", "L", "C", "C", "R", "R", "R", "R", "R", "R", "R", "R", "R", "R"]);
            $pdf->Row([
                utf8_decode($row1[15]),
                utf8_decode($row1[12]),
                utf8_decode(substr($row1[11], 0, 40)),
                utf8_decode($row1[1]),
                utf8_decode($row1[0]),
                utf8_decode(truncateFloat(round($row1[10] - $row1[8] + $row1[9], 2, PHP_ROUND_HALF_EVEN), 2)),
                utf8_decode(truncateFloat(round($row1[9], 2, PHP_ROUND_HALF_EVEN), 2)),
                (!empty($subtarifas[0]) ? $subtarifas[0] : "0.00"),
                (!empty($subtarifas[5]) ? $subtarifas[5] : "0.00"),
                (!empty($subtarifas[8]) ? $subtarifas[8] : "0.00"),
                (!empty($subtarifas[15]) ? $subtarifas[15] : "0.00"),
                (!empty($valsiva[5]) ? $valsiva[5] : "0.00"),
                (!empty($valsiva[8]) ? $valsiva[8] : "0.00"),
                (!empty($valsiva[15]) ? $valsiva[15] : "0.00"),
                utf8_decode(truncateFloat(round($row1[10], 2, PHP_ROUND_HALF_EVEN), 2))
            ], 1);
        }
    }
}
$pdf->SetFont('helvetica', 'B', 8);
$pdf->SetWidths([141, 15, 15, 15, 15, 16, 16, 15, 15, 15, 15]);
$pdf->SetAligns(["R", "R", "R", "R", "R", "R", "R", "R", "R", "R", "R"]);
$pdf->Row([
    "TOTALES:",
    number_format($sub, 2, ',', '.'),
    number_format($desc, 2, ',', '.'),
    (!empty($totalsubtarifas[0]) ? number_format($totalsubtarifas[0], 2, ',', '.') : "0.00"),
    (!empty($totalsubtarifas[5]) ? number_format($totalsubtarifas[5], 2, ',', '.') : "0.00"),
    (!empty($totalsubtarifas[8]) ? number_format($totalsubtarifas[8], 2, ',', '.') : "0.00"),
    (!empty($totalsubtarifas[15]) ? number_format($totalsubtarifas[15], 2, ',', '.') : "0.00"),
    (!empty($totaltarifas[5]) ? number_format($totaltarifas[5], 2, ',', '.') : "0.00"),
    (!empty($totaltarifas[8]) ? number_format($totaltarifas[8], 2, ',', '.') : "0.00"),
    (!empty($totaltarifas[15]) ? number_format($totaltarifas[15], 2, ',', '.') : "0.00"),
    number_format($total, 2, ',', '.')
]);

//    }
/*$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetX(1);
$pdf->Ln(8);
foreach ($totalsubtarifas as $key => $value) {
    $pdf->Cell(250, 6, utf8_decode("Tarifa $key"), 0, 0, 'R', 0);
    $pdf->Cell(20, 6, maxCaracter((number_format($value, 2, ',', '.')), 20), 0, 1, 'R', 0);
}
$pdf->Cell(250, 6, utf8_decode("Subtotal"), 0, 0, 'R', 0);
$pdf->Cell(20, 6, maxCaracter((number_format($sub, 2, ',', '.')), 20), 0, 1, 'R', 0);
$pdf->Cell(250, 6, utf8_decode("Descuento"), 0, 0, 'R', 0);
$pdf->Cell(20, 6, maxCaracter((number_format($desc, 2, ',', '.')), 20), 0, 1, 'R', 0);
$pdf->Cell(250, 6, utf8_decode("Iva Total"), 0, 0, 'R', 0);
$pdf->Cell(20, 6, maxCaracter((number_format($ivaT, 2, ',', '.')), 20), 0, 1, 'R', 0);
$pdf->Cell(250, 6, utf8_decode("Total"), 0, 0, 'R', 0);
$pdf->Cell(20, 6, maxCaracter((number_format($total, 2, ',', '.')), 20), 0, 1, 'R', 0);*/ 
//}
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
    factura_compra fc
    inner join detalle_factura_compra dfc
    using(id_factura_compra)
    inner join detalle_impuesto_producto_compra di
    using(id_detalle_compra)
    where id_factura_compra=$id
    group by di.cod_tarifa, di.cod_impuesto, di.tarifa";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (!empty($rows)) {
        return $rows;
    }
    return [];
}
