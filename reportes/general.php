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
        $this->Cell($this->GetCurrentWidth() / 2, 5, $fecha, 0, 0, 'C', 0);
        $this->Cell($this->GetCurrentWidth() / 2, 5, "VENTAS", 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell($this->GetCurrentWidth(), 8, utf8_decode($_SESSION['nombre_empresa']), 0, 1, 'C', 0);
        $this->Image('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 10, 7, 15, 15);
        $this->Image('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], $this->GetCurrentWidth() - 30, 7, 15, 15);
        // $this->Cell(180, 5, "PROPIETARIO: " . utf8_decode($_SESSION['propietario']), 0, 1, 'C', 0);
        // $this->Cell(80, 5, "TEL.: " . utf8_decode($_SESSION['telefono']), 0, 0, 'R', 0);
        // $this->Cell(80, 5, "CEL.: " . utf8_decode($_SESSION['celular']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, "DIR.: " . utf8_decode($_SESSION['direccion']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, "SLOGAN.: " . utf8_decode($_SESSION['slogan']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, utf8_decode($_SESSION['pais_ciudad']), 0, 1, 'C', 0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);
        $this->Line(0, 25, $this->GetCurrentWidth(), 25);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell($this->GetCurrentWidth(), 5, utf8_decode("RESUMEN GENERAL DE FACTURAS"), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell($this->GetCurrentWidth() / 2, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell($this->GetCurrentWidth() / 2, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell($this->GetCurrentWidth(), 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->Ln(3);

        $wcell = $this->GetCurrentWidth() / 13;

        $this->SetFont('helvetica', 'B', 9);
        $this->SetFillColor(175, 215, 240);
        $this->Cell($wcell - 5, 6, utf8_decode('Compr.'), 1, 0, 'C', 1);
        $this->Cell($wcell - 5, 6, utf8_decode('Fecha'), 1, 0, 'C', 1);
        $this->Cell($wcell, 6, utf8_decode('Nro Factura'), 1, 0, 'C', 1);
        $this->Cell($wcell + 5, 6, utf8_decode('RUC. C.'), 1, 0, 'C', 1);
        $this->Cell($wcell + 40, 6, utf8_decode('Nombre C.'), 1, 0, 'C', 1);
        $this->Cell($wcell - 5, 6, utf8_decode('Descuento'), 1, 0, 'C', 1);
        $this->Cell($wcell - 5, 6, utf8_decode('Subtotal'), 1, 0, 'C', 1);
        $this->Cell($wcell - 5, 6, utf8_decode('0%'), 1, 0, 'C', 1);
        $this->Cell($wcell - 5, 6, utf8_decode('12%'), 1, 0, 'C', 1);
        $this->Cell($wcell - 5, 6, utf8_decode('IVA'), 1, 0, 'C', 1);
        $this->Cell($wcell - 5, 6, utf8_decode('Total'), 1, 0, 'C', 1);
        $this->Cell($wcell, 6, utf8_decode('Fecha Pago'), 1, 0, 'C', 1);
        //$this->Cell(20, 6, utf8_decode('Tipo Pago'), 1, 1, 'C', 1);
        $this->Cell($wcell - 6, 6, utf8_decode('Costo V.'), 1, 1, 'C', 1);
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

$pdf = new PDF('L', 'mm', 'a4');
$pdf->SetTitle('General Factura Venta');
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->AliasNbPages();

$total = 0;
$sub = 0;
$desc = 0;
$ivaT = 0;
$t0 = 0;
$t12 = 0;
$totalcv = 0;
$query_fecha = "";
// RANGO DE FECHAS O FECHA ACTUAL
if ($pdf->rango) {
    $query_fecha = "BETWEEN '$_GET[inicio]' AND";
} else {
    $query_fecha = "=";
}
$condciente = "";
if (!empty($_GET["id_cliente"])) {
    $condciente = " and fv.id_cliente=" . $_GET["id_cliente"];
}

$consulta1 = pg_query(
    "SELECT 
     num_factura,
     fv.fecha_actual,
     hora_actual,
     fecha_cancelacion,
     tipo_precio,
     forma_pago,
     tarifa0,
     tarifa12,
    iva_venta,
    descuento_venta,
     total_venta,
     identificacion,
     nombres_cli,
     nombre_empresa,
     id_factura_venta,
     fv.estado,
     fv.desc_fact 
    FROM factura_venta fv,
     clientes c,
    empresa e,
    usuario u where fv.id_cliente=c.id_cliente  and fv.id_empresa='$_GET[id]' 
    AND u.id_usuario=fv.id_usuario and fv.fecha_actual $query_fecha '$_GET[fin]' 
    and e.id_empresa='$_GET[id]' 
    $condciente
    order by fv.id_factura_venta asc"
);

$wcell = $pdf->GetCurrentWidth() / 13;

if (pg_num_rows($consulta1)) {
    while ($row1 = pg_fetch_assoc($consulta1)) {
        if ($row1["estado"] == "Activo") {

            $subt = $row1["tarifa0"] + $row1["tarifa12"];
            $subt0 = $row1["tarifa0"];
            $subt12 = $row1["tarifa12"];

            $prcdescfact = ($row1["desc_fact"] * 100) / ($row1["tarifa0"] + $row1["tarifa12"]);

            if (!empty($prcdescfact)) {
                $subt = $subt * (1 - ($prcdescfact / 100));
                $subt0 = $subt0 * (1 - ($prcdescfact / 100));
                $subt12 = $subt12 * (1 - ($prcdescfact / 100));
            }

            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetX(1);
            $pdf->Cell($wcell - 5, 6, utf8_decode($row1["id_factura_venta"]), 0, 0, 'C', 0);
            $pdf->Cell($wcell - 5, 6, utf8_decode($row1["fecha_actual"]), 0, 0, 'C', 0);
            $pdf->Cell($wcell, 6, utf8_decode($row1["num_factura"]), 0, 0, 'L', 0);
            $pdf->Cell($wcell + 5, 6, utf8_decode($row1["identificacion"]), 0, 0, 'L', 0);
            $pdf->Cell($wcell + 40, 6, substr(utf8_decode(substr($row1["nombres_cli"], 0, 40)), 0, 30), 0, 0, 'L', 0);
            $pdf->Cell($wcell - 5, 6, utf8_decode(truncateFloat(round($row1["descuento_venta"], 2, PHP_ROUND_HALF_EVEN), 2)), 0, 0, 'R', 0);
            //$pdf->Cell($wcell - 5, 6, utf8_decode(truncateFloat(round($prcdescfact, 2, PHP_ROUND_HALF_EVEN), 2)), 0, 0, 'R', 0);
            $pdf->Cell($wcell - 5, 6, utf8_decode(truncateFloat(round($subt, 2, PHP_ROUND_HALF_EVEN), 2)), 0, 0, 'R', 0);
            $pdf->Cell($wcell - 5, 6, utf8_decode(truncateFloat(round($subt0, 2, PHP_ROUND_HALF_EVEN), 2)), 0, 0, 'R', 0);
            $pdf->Cell($wcell - 5, 6, utf8_decode(truncateFloat(round($subt12, 2, PHP_ROUND_HALF_EVEN), 2)), 0, 0, 'R', 0);
            $pdf->Cell($wcell - 5, 6, utf8_decode(truncateFloat(round($row1["iva_venta"], 2, PHP_ROUND_HALF_EVEN), 2)), 0, 0, 'R', 0);
            $pdf->Cell($wcell - 5, 6, utf8_decode(truncateFloat(round($row1["total_venta"], 2, PHP_ROUND_HALF_EVEN), 2)), 0, 0, 'R', 0);
            $pdf->Cell($wcell, 6, $row1["fecha_cancelacion"], 0, 0, 'C', 0);
            //$pdf->Cell(20, 6, $row1[5], 0, 1, 'C', 0);
            $pdf->Cell($wcell - 6, 6, obtenerCostoVenta($row1["id_factura_venta"]), 0, 1, 'R', 0);

            $desc = $desc + $row1["descuento_venta"];
            $sub += $subt;
            $ivaT = $ivaT + $row1["iva_venta"];
            $total = $total + $row1["total_venta"];
            $t0 +=$subt0;
            $t12 +=$subt12;

            $totalcv += obtenerCostoVenta($row1["id_factura_venta"]);
        } else {
            if ($row1["estado"] == "Pasivo") {
                $pdf->SetTextColor(208, 17, 52);
                $pdf->SetFont('helvetica', '', 9);
                $pdf->SetX(1);
                $pdf->Cell($wcell - 5, 6, utf8_decode($row1["id_factura_venta"]), 0, 0, 'C', 0);
                $pdf->Cell($wcell - 5, 6, utf8_decode($row1["fecha_actual"]), 0, 0, 'C', 0);
                $pdf->Cell($wcell, 6, utf8_decode($row1["num_factura"]), 0, 0, 'L', 0);
                $pdf->Cell($wcell + 5, 6, utf8_decode($row1["identificacion"]), 0, 0, 'L', 0);
                $pdf->Cell($wcell + 40, 6, substr(utf8_decode(substr($row1["nombres_cli"], 0, 40)), 0, 30), 0, 0, 'L', 0);
                $pdf->Cell($wcell - 5, 6, utf8_decode(truncateFloat(round($row1["descuento_venta"], 2, PHP_ROUND_HALF_EVEN), 2)), 0, 0, 'R', 0);
                $pdf->Cell($wcell - 5, 6, utf8_decode(truncateFloat(round($row1["tarifa0"] + $row1["tarifa12"], 2, PHP_ROUND_HALF_EVEN), 2)), 0, 0, 'R', 0);
                $pdf->Cell($wcell - 5, 6, utf8_decode(truncateFloat(round($row1["tarifa0"], 2, PHP_ROUND_HALF_EVEN), 2)), 0, 0, 'R', 0);
                $pdf->Cell($wcell - 5, 6, utf8_decode(truncateFloat(round($row1["tarifa12"], 2, PHP_ROUND_HALF_EVEN), 2)), 0, 0, 'R', 0);
                $pdf->Cell($wcell - 5, 6, utf8_decode(truncateFloat(round($row1["iva_venta"], 2, PHP_ROUND_HALF_EVEN), 2)), 0, 0, 'R', 0);
                $pdf->Cell($wcell - 5, 6, utf8_decode(truncateFloat(round($row1["total_venta"], 2, PHP_ROUND_HALF_EVEN), 2)), 0, 0, 'R', 0);
                $pdf->Cell($wcell, 6, $row1["fecha_cancelacion"], 0, 0, 'C', 0);
                //$pdf->Cell(20, 6, $row1[5], 0, 1, 'C', 0);
                $pdf->Cell($wcell - 6, 6, obtenerCostoVenta($row1["id_factura_venta"]), 0, 1, 'R', 0);
            }
        }
    }

    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell($pdf->GetCurrentWidth(), 0, utf8_decode(""), 1, 1, 'R', 0);
    $pdf->Cell(150, 6, utf8_decode("Totales"), 0, 0, 'R', 0);
    $pdf->Cell($wcell - 5, 6, "", 0, 0, 'R', 0);
    $pdf->Cell($wcell - 5, 6, maxCaracter((number_format($sub, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell($wcell - 5, 6, maxCaracter((number_format($t0, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell($wcell - 5, 6, maxCaracter((number_format($t12, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell($wcell - 5, 6, maxCaracter((number_format($ivaT, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell($wcell - 5, 6, maxCaracter((number_format($total, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(($wcell) * 2 - 5, 6, maxCaracter((number_format($totalcv, 2, ',', '.')), 20), 0, 1, 'R', 0);
}
$pdf->Output();


function obtenerCostoVenta($idfacturaventa)
{
    $sql = "select round(sum(costo_prom_unitario::numeric*salida::numeric),2) from kardex_valorizado
    where compra_venta='V'
    and comprobante::integer=$idfacturaventa";
    $res = pg_query($sql);
    $row = pg_fetch_row($res);
    if (empty($row)) {
        return 0;
    }
    switch ($idfacturaventa) {
        case 659:
            return $row[0] - 15;
        case 612:
            return $row[0] - 15;
        case 437:
            return $row[0] - 9.01;
        case 139:
            return $row[0] - 0.2;
    }
    return $row[0];
}
