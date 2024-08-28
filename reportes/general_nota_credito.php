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
        $this->Cell($this->GetCurrentWidth(), 8, $_SESSION['nombre_empresa'], 0, 1, 'C', 0);
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
        $this->Cell($this->GetCurrentWidth(), 5, utf8_decode("RESUMEN GENERAL NOTAS DE CREDITO"), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell($this->GetCurrentWidth() / 2, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell($this->GetCurrentWidth() / 2, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell($this->GetCurrentWidth(), 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->Ln(3);
        $this->SetFillColor(175, 215, 240);
        $this->SetFont('helvetica', 'B', 9);
        $GLOBALS["widthstabla"] = [18, 18, 18, 25, 65, 15, 15, 15, 15, 16, 16, 15, 15, 15, 15];
        $this->SetWidths($GLOBALS["widthstabla"]);
        $this->SetAligns([array_fill(0, 15, "C")]);

        $this->Row([
            utf8_decode('Com.'),
            utf8_decode('Fecha'),
            utf8_decode('Factura'),
            utf8_decode('RUC C.'),
            utf8_decode('Nombre C.'),
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
        /* $wcell = $this->GetCurrentWidth() / 12;

        $this->SetFont('helvetica', 'B', 9);
        $this->SetFillColor(175, 215, 240);
        $this->Cell($wcell - 5, 6, utf8_decode('Comprob.'), 1, 0, 'C', 1);
        $this->Cell($wcell - 5, 6, utf8_decode('Nro. Fac.'), 1, 0, 'C', 1);
        $this->Cell($wcell, 6, utf8_decode('RUC. C.'), 1, 0, 'C', 1);
        $this->Cell($wcell + 50, 6, utf8_decode('Nombre. C.'), 1, 0, 'C', 1);
        $this->Cell($wcell - 5, 6, utf8_decode('Fecha'), 1, 0, 'C', 1);
        $this->Cell($wcell - 5, 6, utf8_decode('Subtotal'), 1, 0, 'C', 1);
        $this->Cell($wcell - 5, 6, utf8_decode('Descuento'), 1, 0, 'C', 1);
        $this->Cell($wcell - 5, 6, utf8_decode('Tarifa 0%'), 1, 0, 'C', 1);
        $this->Cell($wcell - 5, 6, utf8_decode('Tarifa IVA'), 1, 0, 'C', 1);
        $this->Cell($wcell - 5, 6, utf8_decode('Iva ..%'), 1, 0, 'C', 1);
        $this->Cell($wcell - 5, 6, utf8_decode('Total'), 1, 0, 'C', 1);
        $this->Cell($wcell - 5, 6, utf8_decode('Tipo Docu.'), 1, 1, 'C', 1); */
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
$pdf->SetTitle('General Notas Credito');
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

$condciente = "";
if (!empty($_GET["id_cliente"])) {
    $condciente = " and dv.id_cliente=" . $_GET["id_cliente"];
}

$consulta1 = pg_query(
    "SELECT 
    dv.fecha_actual, 
    hora_actual, 
    '', 
    tipo_comprobante, 
    tarifa0, 
    tarifa12, 
    iva_venta,
    descuento_venta,
    total_venta,
    identificacion,
    nombres_cli,
    num_serie,
    num_nota_credito,
    id_devolucion_venta
    FROM devolucion_venta dv, clientes c, usuario u 
    WHERE dv.id_cliente=c.id_cliente and u.id_usuario=dv.id_usuario AND dv.id_empresa='$_GET[id]'
    AND dv.fecha_actual $query_fecha '$_GET[fin]'
    $condciente
    ORDER BY dv.id_devolucion_venta asc;"
);
$rows = pg_fetch_all($consulta1);

$totalsubtarifas = [];
$totaltarifas = [];

$pdf->SetFont('helvetica', '', 8);
foreach ($rows as $key => $value) {
    $sub = $sub + ($value["total_venta"] - $value["iva_venta"] + $value["descuento_venta"]);
    $desc = $desc + $value["descuento_venta"];
    $total = $total + $value["total_venta"];

    $tarifasiva = obtenerTarifasImpuestoFactura($value["id_devolucion_venta"]);
    $subtarifas = [];
    $valsiva = [];
    foreach ($tarifasiva as $value1) {
        $subtarifas[round($value1["tarifa"], 0)] = number_format($value1["base_imponible"], 2, ",", ".");
        $valsiva[round($value1["tarifa"], 0)] = number_format($value1["valor_impuesto"], 2, ",", ".");
        if (empty($totalsubtarifas[round($value1["tarifa"], 0)])) {
            $totalsubtarifas[round($value1["tarifa"], 0)] = 0;
            $totaltarifas[round($value1["tarifa"], 0)] = 0;
        }
        $totalsubtarifas[round($value1["tarifa"], 0)] += $value1["base_imponible"];
        $totaltarifas[round($value1["tarifa"], 0)] += $value1["valor_impuesto"];
    }

    $pdf->SetWidths($widthstabla);
    $pdf->SetAligns(["C", "C", "L", "C", "L", "R", "R", "R", "R", "R", "R", "R", "R", "R", "R"]);
    $pdf->Row([
        utf8_decode($value["num_nota_credito"]),
        utf8_decode($value["fecha_actual"]),
        utf8_decode($value["num_serie"]),
        utf8_decode($value["identificacion"]),
        utf8_decode($value["nombres_cli"]),
        utf8_decode(number_format($value["total_venta"] - $value["iva_venta"] + $value["descuento_venta"], 2, ",", ".")),
        utf8_decode(number_format($value["descuento_venta"], 2, ",", ".")),
        (!empty($subtarifas[0]) ? $subtarifas[0] : "0.00"),
        (!empty($subtarifas[5]) ? $subtarifas[5] : "0.00"),
        (!empty($subtarifas[8]) ? $subtarifas[8] : "0.00"),
        (!empty($subtarifas[15]) ? $subtarifas[15] : "0.00"),
        (!empty($valsiva[5]) ? $valsiva[5] : "0.00"),
        (!empty($valsiva[8]) ? $valsiva[8] : "0.00"),
        (!empty($valsiva[15]) ? $valsiva[15] : "0.00"),
        utf8_decode(number_format($value["total_venta"], 2, ",", "."))
    ], 1);
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
/* $wcell = $pdf->GetCurrentWidth() / 12;
if (pg_num_rows($consulta1)) {
    while ($row1 = pg_fetch_row($consulta1)) {
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetX(0);
        $pdf->Cell($wcell - 5, 6, utf8_decode($row1[12]), 0, 0, 'C', 0);
        $pdf->Cell($wcell - 5, 6, utf8_decode($row1[11]), 0, 0, 'C', 0);
        $pdf->Cell($wcell, 6, utf8_decode($row1[9]), 0, 0, 'C', 0);
        $pdf->Cell($wcell + 50, 6, substr(utf8_decode($row1[10]), 0, 40), 0, 0, 'L', 0);
        $pdf->Cell($wcell - 5, 6, utf8_decode($row1[0]), 0, 0, 'C', 0);
        $sub = $sub + ($row1[8] - $row1[6] + $row1[7]);
        $pdf->Cell($wcell - 5, 6, number_format($row1[8] - $row1[6] + $row1[7], 2, '.', ''), 0, 0, 'R', 0);
        $desc = $desc + $row1[7];
        $pdf->Cell($wcell - 5, 6, number_format($row1[7], 2, '.', ''), 0, 0, 'R', 0);
        $pdf->Cell($wcell - 5, 6, number_format($row1[4], 2, '.', ''), 0, 0, 'R', 0);
        $pdf->Cell($wcell - 5, 6, number_format($row1[5], 2, '.', ''), 0, 0, 'R', 0);
        $ivaT = $ivaT + $row1[6];
        $pdf->Cell($wcell - 5, 6, number_format($row1[6], 2, '.', ''), 0, 0, 'R', 0);
        $total = $total + $row1[8];
        $t0 += $row1[4];
        $pdf->Cell($wcell - 5, 6, number_format($row1[8], 2, '.', ''), 0, 0, 'R', 0);
        $pdf->Cell($wcell - 5, 6, $row1[3], 0, 1, 'C', 0);
    }

    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell($pdf->GetCurrentWidth(), 0, utf8_decode(""), 1, 1, 'R', 0);
    $pdf->SetX(1);
    $pdf->Cell(158, 6, utf8_decode("Totales"), 0, 0, 'R', 0);
    $pdf->Cell($wcell - 5, 6, maxCaracter((number_format($sub, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell($wcell - 5, 6, maxCaracter((number_format($desc, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell($wcell - 5, 6, maxCaracter((number_format($t0, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell($wcell - 5, 6, maxCaracter((number_format($sub - $desc, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell($wcell - 5, 6, maxCaracter((number_format($ivaT, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell($wcell - 5, 6, maxCaracter((number_format($total, 2, ',', '.')), 20), 0, 1, 'R', 0);
} */
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
    devolucion_venta fc
    inner join detalle_devolucion_venta dfc
    using(id_devolucion_venta)
    inner join detalle_impuesto_producto_dev_venta di
    using(id_detalle_deventa)
    where id_devolucion_venta=$id
    group by di.cod_tarifa, di.cod_impuesto, di.tarifa";

    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (!empty($rows)) {
        return $rows;
    }
    return [];
}
