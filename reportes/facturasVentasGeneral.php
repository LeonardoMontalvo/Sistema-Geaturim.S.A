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
        $this->Cell(105, 5, "VENTAS", 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(300, 8, $_SESSION['nombre_empresa'], 0, 1, 'C', 0);
        $this->Image('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"], 80, 7, 15, 15);
        $this->Image('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"], 210, 7, 15, 15);
        // $this->Cell(180, 5, "PROPIETARIO: " . utf8_decode($_SESSION['propietario']), 0, 1, 'C', 0);
        // $this->Cell(80, 5, "TEL.: " . utf8_decode($_SESSION['telefono']), 0, 0, 'R', 0);
        // $this->Cell(80, 5, "CEL.: " . utf8_decode($_SESSION['celular']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, "DIR.: " . utf8_decode($_SESSION['direccion']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, "SLOGAN.: " . utf8_decode($_SESSION['slogan']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, utf8_decode($_SESSION['pais_ciudad']), 0, 1, 'C', 0);
        
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(310, 5, utf8_decode("RESUMEN DE FACTURAS VENTAS"), 0, 1, 'C', 0);
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
        $this->SetFont('helvetica', 'B', 9);
        $this->SetFillColor(175, 215, 240);
        $totalw = $this->GetCurrentWidth();
        $colw = $totalw / 9;

        $this->SetWidths([
            $colw - 10,
            $colw - 4,
            $colw + 74,
            $colw - 10,
            $colw - 10,
            $colw - 10,
            $colw - 10,
            $colw - 10,
            $colw - 10
        ]);
        $this->SetAligns(array_fill(0, 10, "C"));
        $this->Row([
            utf8_decode('Fecha'),
            utf8_decode('Identificación'),
            utf8_decode('Clientes'),
            utf8_decode('N. Factura'),
            utf8_decode('Subtotal'),
            utf8_decode('0%'),
            utf8_decode('15%'),
            utf8_decode('IVA'),
            utf8_decode('Total')
        ], 1, "", true);
        /* $this->Cell(26, 6, utf8_decode('Identificación'), 1, 0, 'C', 1);
        $this->Cell(38, 6, utf8_decode('Clientes'), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode('Fecha'), 1, 0, 'C', 1);
        $this->Cell(30, 6, utf8_decode('Nro Factura'), 1, 0, 'C', 1);
        $this->Cell(16, 6, utf8_decode('Subtotal'), 1, 0, 'C', 1);
        $this->Cell(16, 6, utf8_decode('Descto'), 1, 0, 'C', 1);
        $this->Cell(16, 6, utf8_decode('0%'), 1, 0, 'C', 1);
        $this->Cell(16, 6, utf8_decode('15%'), 1, 0, 'C', 1);
        $this->Cell(16, 6, utf8_decode('IVA'), 1, 0, 'C', 1);
        $this->Cell(16, 6, utf8_decode('Total'), 1, 1, 'C', 1); */
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
$pdf->SetTitle('Facturas Detalladas');
$pdf->SetMargins(2, 0);
$pdf->AddPage();
$pdf->AliasNbPages();

$totalw = $pdf->GetCurrentWidth();
$colw = $totalw / 9;
$widths = [
    $colw - 10,
    $colw - 4,
    $colw + 74,
    $colw - 10,
    $colw - 10,
    $colw - 10,
    $colw - 10,
    $colw - 10,
    $colw - 10,
];

$total = 0;
$sub = 0;
$desc = 0;
$ivaT = 0;
$t0 = 0;
$t12 = 0;
$query_fecha = "";
// RANGO DE FECHAS O FECHA ACTUAL
if ($pdf->rango) {
    $query_fecha = "BETWEEN '$_GET[inicio]' AND";
} else {
    $query_fecha = "=";
}

/* $consulta1 = pg_query(
    "SELECT num_factura, fv.fecha_actual, hora_actual, fecha_cancelacion, tipo_precio, forma_pago, tarifa0, tarifa12, 
    iva_venta, descuento_venta, total_venta, identificacion, nombres_cli, nombre_empresa, id_factura_venta, fv.estado 
    FROM factura_venta fv, clientes c,empresa e,usuario u where fv.id_cliente=c.id_cliente and u.id_usuario=fv.id_usuario 
    and fv.id_empresa='$_GET[id]' and fv.fecha_actual $query_fecha '$_GET[fin]' and e.id_empresa='$_GET[id]' order by fv.id_factura_venta asc"
); */
$consulta1=pg_query(
    "SELECT num_factura,
    fv.fecha_actual,
    hora_actual,
    fecha_cancelacion,
    tipo_precio,
    forma_pago, 
    round(tarifa0,2)tarifa0, 
    round(tarifa12,2)tarifa12, 
    round(iva_venta,2)iva_venta,
    descuento_venta,
    round(fv.total_venta,2)total_venta,
    identificacion,
    nombres_cli,
    nombre_empresa,
    fv.id_factura_venta, fv.estado
    FROM 
    factura_venta fv,

    empresa e,
    clientes c,
    usuario u  where fv.id_cliente=c.id_cliente and u.id_usuario=fv.id_usuario 

    and fv.id_empresa='$_GET[id]' and fv.fecha_cancelacion $query_fecha '$_GET[fin]' 
    and e.id_empresa='$_GET[id]' order by fv.fecha_cancelacion asc, fv.num_factura asc"
);
if (pg_num_rows($consulta1)) {
    while ($row1 = pg_fetch_row($consulta1)) {
        if ($row1[15] == "Activo") {
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('helvetica', '', 9);

            $pdf->SetWidths($widths);
            $pdf->SetAligns([
                "L",
                "L",
                "L",
                "L",
                "R",
                "R",
                "R",
                "R",
                "R",
            ]);
            $pdf->Row([
                utf8_decode($row1[3]),
                utf8_decode($row1[11]),
                utf8_decode(substr($row1[12], 0, 33)),
                utf8_decode($row1[0]),
                number_format(($row1[10] - $row1[8] + $row1[9]),2,".",""),
                utf8_decode($row1[6]),
                utf8_decode($row1[7]),
                utf8_decode($row1[8]),
                utf8_decode($row1[10])
            ]);
            $sub = $sub + ($row1[10] - $row1[8] + $row1[9]);
            $ivaT = $ivaT + $row1[8];
            $total = $total + $row1[10];
            $t0 = $t0 + $row1[6];
            $t12 = $t12 + $row1[7];
            /* $pdf->SetX(1);
            $pdf->Cell(25, 6, utf8_decode($row1[11]), 0, 0, 'L', 0);
            $pdf->Cell(38, 6, utf8_decode(substr($row1[12], 0, 19)), 0, 0, 'L', 0);
            $pdf->Cell(20, 6, utf8_decode($row1[1]), 0, 0, 'C', 0);
            $pdf->Cell(30, 6, utf8_decode($row1[0]), 0, 0, 'C', 0);
            $sub = $sub + ($row1[10] - $row1[8] + $row1[9]);
            $pdf->Cell(16, 6, utf8_decode(truncateFloat(round($row1[10] - $row1[8] + $row1[9], 2, PHP_ROUND_HALF_EVEN), 2)), 0, 0, 'R', 0);
            $desc = $desc + $row1[9];
            $pdf->Cell(16, 6, utf8_decode(truncateFloat(round($row1[9], 2, PHP_ROUND_HALF_EVEN), 2)), 0, 0, 'R', 0);
            $pdf->Cell(16, 6, utf8_decode(truncateFloat(round($row1[6], 2, PHP_ROUND_HALF_EVEN), 2)), 0, 0, 'R', 0);
            $pdf->Cell(16, 6, utf8_decode(truncateFloat(round($row1[7], 2, PHP_ROUND_HALF_EVEN), 2)), 0, 0, 'R', 0);
            $ivaT = $ivaT + $row1[8];
            $pdf->Cell(16, 6, utf8_decode(truncateFloat(round($row1[8], 2, PHP_ROUND_HALF_EVEN), 2)), 0, 0, 'R', 0);
            $total = $total + $row1[10];
            $t0 = $t0 + $row1[6];
            $t12 = $t12 + $row1[7];
            $pdf->Cell(16, 6, utf8_decode(truncateFloat(round($row1[10], 2, PHP_ROUND_HALF_EVEN), 2)), 0, 1, 'R', 0); */
        } else {
            if ($row1[15] == "Pasivo") {
                $pdf->SetTextColor(208, 17, 52);
                $pdf->SetFont('helvetica', '', 9);

                $pdf->SetWidths($widths);
                $pdf->SetAligns([
                    "L",
                    "L",
                    "L",
                    "L",
                    "R",
                    "R",
                    "R",
                    "R",
                    "R",
                ]);
                $pdf->Row([
                    utf8_decode($row1[3]),
                    utf8_decode($row1[11]),
                    utf8_decode(substr($row1[12], 0, 33)),
                    utf8_decode($row1[0]),
                    number_format(($row1[10] - $row1[8] + $row1[9]),2,".",""),
                    utf8_decode($row1[6]),
                    utf8_decode($row1[7]),
                    utf8_decode($row1[8]),
                    utf8_decode($row1[10])
                ]);
                /*  $pdf->SetX(1);
                $pdf->Cell(25, 6, utf8_decode($row1[11]), 0, 0, 'L', 0);
                $pdf->Cell(38, 6, utf8_decode(substr($row1[12], 0, 19)), 0, 0, 'L', 0);
                $pdf->Cell(20, 6, utf8_decode($row1[1]), 0, 0, 'C', 0);
                $pdf->Cell(30, 6, utf8_decode(($row1[0])), 0, 0, 'C', 0);
                $pdf->Cell(16, 6, utf8_decode(truncateFloat(round($row1[10] - $row1[8] + $row1[9], 2, PHP_ROUND_HALF_EVEN), 2)), 0, 0, 'R', 0);
                $pdf->Cell(16, 6, utf8_decode(truncateFloat(round($row1[9], 2, PHP_ROUND_HALF_EVEN), 2)), 0, 0, 'R', 0);
                $pdf->Cell(16, 6, utf8_decode(truncateFloat(round($row1[6], 2, PHP_ROUND_HALF_EVEN), 2)), 0, 0, 'R', 0);
                $pdf->Cell(16, 6, utf8_decode(truncateFloat(round($row1[7], 2, PHP_ROUND_HALF_EVEN), 2)), 0, 0, 'R', 0);
                $pdf->Cell(16, 6, utf8_decode(truncateFloat(round($row1[8], 2, PHP_ROUND_HALF_EVEN), 2)), 0, 0, 'R', 0);
                $pdf->Cell(16, 6, utf8_decode(truncateFloat(round($row1[10], 2, PHP_ROUND_HALF_EVEN), 2)), 0, 1, 'R', 0); */
            }
        }
    }
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('helvetica', 'B', 9);

    $totalw = $pdf->GetCurrentWidth();
    $colw = $totalw / 9;

    $pdf->Line(2, $pdf->GetY(), $totalw, $pdf->GetY());

    $pdf->SetWidths($widths);
    $pdf->SetAligns([
        "L",
        "L",
        "L",
        "L",
        "R",
        "R",
        "R",
        "R",
        "R"
    ]);
    $pdf->Row([
        "",
        "",
        "",
        "TOTALES:",
        utf8_decode($sub),
        utf8_decode($t0),
        utf8_decode($t12),
        utf8_decode($ivaT),
        utf8_decode($total)
    ]);
    /*     $pdf->Cell(230, 0, utf8_decode(""), 1, 1, 'R', 0);
    $pdf->Cell(114, 6, utf8_decode("Totales"), 0, 0, 'R', 0);
    $pdf->Cell(16, 6, maxCaracter((number_format($sub, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(16, 6, maxCaracter((number_format($desc, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(16, 6, maxCaracter((number_format($t0, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(16, 6, maxCaracter((number_format($t12, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(16, 6, maxCaracter((number_format($ivaT, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(16, 6, maxCaracter((number_format($total, 2, ',', '.')), 20), 0, 1, 'R', 0); */
}
$pdf->Output();
