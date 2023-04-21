<?php
require('../fpdf/fpdf.php');
include '../procesos/base.php';
include '../procesos/funciones.php';
conectarse();
date_default_timezone_set('America/Guayaquil');
session_start();

$idcargousuario = getIdCargoUsuario();
function getIdCargoUsuario()
{
    $idusuario = $_SESSION["id"];
    $sql = "
    select id_cargo_usuario from usuario
    where id_usuario=$idusuario
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_assoc($res);
    if (empty($rows)) {
        return 0;
    }
    return $rows["id_cargo_usuario"];
}


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
        $this->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->AddFont('helvetica', 'B', 'helveticab.php');
        $this->SetFont('Amble-Regular', '', 10);
        $fecha = date('Y-m-d', time());
        $this->SetX(0);
        $this->SetY(0);
        $this->Cell(105, 5, $fecha, 0, 0, 'C', 0);
        $this->Cell(105, 5, "TRANSFERENCIAS", 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(210, 8, utf8_decode($_SESSION['nombre_empresa']), 0, 1, 'C', 0);
        $this->Image('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 10, 7, 15, 15);
        $this->Image('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 180, 7, 15, 15);
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
        $this->Cell(210, 5, utf8_decode("REPORTE DE TRANSFERENCIA"), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        $this->Ln(10);
    }
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

function obtenerDatosTransferencia($idtransferencia)
{
    $sql = "
    select
    tb.*,
    tb.fecha_creacion::date,
    tb.fecha_modificacion::date,
    uo.nombre_usuario usuario_origen,
    ud.nombre_usuario usuario_destino,
    origen.nombre_punto origen,
    destino.nombre_punto destino
    from transferencias_bodega tb
    inner join usuario uo
    on uo.id_usuario=tb.id_usuario_origen
    left join usuario ud
    on ud.id_usuario=tb.id_usuario_destino
    left join punto_venta origen
    on origen.id_punto_venta=tb.id_bodega_origen
    left join punto_venta destino
    on destino.id_punto_venta=tb.id_bodega_destino
    where tb.id_transferencia_bodega=$idtransferencia
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (!$rows) {
        return [];
    }

    return $rows[0];
}

function obtenerDetallesEgreso($idegreso)
{
    $sql = "
    select
    round(de.cantidad,2)cantidad,
    round(de.precio_costo,2)precio_costo,
    round(de.descuento,2)descuento,
    round(de.total,2)total,
    p.articulo
    from detalle_egreso de
    inner join productos p
    on p.cod_productos=de.cod_productos
    where de.id_egresos=$idegreso
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (!$rows) {
        return [];
    }
    return $rows;
}

function obtenerEgreso($idegreso)
{
    $sql = "
    select
    round(descuento_egreso,2) descuento_egreso, 
    round(total_egreso,2) total_egreso,
    round(tarifa0,2) tarifa0,
    round(tarifa12,2) tarifa12,
    round(iva_egreso,2) iva_egreso,
    round(descuento_egreso,2) descuento_egreso,
    round(total_egreso,2) total_egreso
    from egresos
    where id_egresos=$idegreso
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (!$rows) {
        return [];
    }
    return $rows[0];
}

$transferencia = obtenerDatosTransferencia($_GET["id"]);
$egreso = obtenerEgreso($transferencia["id_egreso"]);
$detallese = obtenerDetallesEgreso($transferencia["id_egreso"]);

$pdf = new PDF('P', 'mm', 'a4');
$pdf->SetTitle('Transferencia');
$pdf->SetMargins(10, 0);
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Amble-Regular', '', 9);

$totalw = $pdf->GetCurrentWidth();

$pdf->SetFont("Arial", "b", 10);
$pdf->Cell($totalw - 20, 4, utf8_decode("Transferencia Nº: "), 0, 0, "R");
$pdf->Cell(20, 4, utf8_decode($transferencia["comprobante"]), 0, 1, "L");
$pdf->Ln(5);
$pdf->Cell(40, 4, utf8_decode("Fecha Transferencia:"), 0, 0, "R");
$pdf->SetFont("Arial", "", 10);
$pdf->Cell($totalw - 40, 4, utf8_decode(date("Y-m-d", strtotime($transferencia["fecha_creacion"]))), 0, 1);
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(40, 4, utf8_decode("P. V. Origen:"), 0, 0, "R");
$pdf->SetFont("Arial", "", 10);
$pdf->Cell($totalw - 40, 4, utf8_decode($transferencia["origen"]), 0, 1);
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(40, 4, utf8_decode("P. V. Destino:"), 0, 0, "R");
$pdf->SetFont("Arial", "", 10);
$pdf->Cell($totalw - 40, 4, utf8_decode($transferencia["destino"]), 0, 1);
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(40, 4, utf8_decode("Transferido por:"), 0, 0, "R");
$pdf->SetFont("Arial", "", 10);
$pdf->Cell($totalw - 40, 4, utf8_decode($transferencia["usuario_origen"]), 0, 1);
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(40, 4, utf8_decode("Fecha Confirmación:"), 0, 0, "R");
$pdf->SetFont("Arial", "", 10);
$pdf->Cell($totalw - 40, 4, utf8_decode($transferencia["fecha_modificacion"]), 0, 1);
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(40, 4, utf8_decode("Confirmado por:"), 0, 0, "R");
$pdf->SetFont("Arial", "", 10);
$pdf->Cell($totalw - 40, 4, utf8_decode($transferencia["usuario_destino"]), 0, 1);
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(40, 4, utf8_decode("Estado:"), 0, 0, "R");
$pdf->SetFont("Arial", "", 10);
if ($transferencia["estado"] == "Pasivo") {
    $pdf->Cell($totalw - 40, 4, utf8_decode("ANULADO"), 0, 1);
} else {
    $pdf->Cell($totalw - 40, 4, utf8_decode(mb_strtoupper($transferencia["estado_transferencia"])), 0, 1);
}

$pdf->Ln(5);

$colw = $totalw / 5;
$pdf->SetWidths([
    $colw - 20,
    $colw + 60,
    $colw - 10,
    $colw - 15,
    $colw - 15
]);
$pdf->SetAligns(array_fill(0, 6, 'C'));
$pdf->SetFont("Arial", "B", 10);
$pdf->Row([
    "Cantidad",
    "Producto",
    "Precio Costo",
    "Descuento",
    "Total"
], 1);
$pdf->SetFont("Arial", "", 9);
foreach ($detallese as $value) {
    $precioc = $value["precio_costo"];
    $totalc = $value["total"];
    if ($idcargousuario != 1) {
        $precioc = 0;
        $totalc = 0;
    }
    $pdf->Row([
        $value["cantidad"],
        $value["articulo"],
        $precioc,
        $value["descuento"],
        $totalc
    ], 1);
}

if ($idcargousuario == 1) {
    $pdf->Ln(5);

    $pdf->SetFont("Arial", "b", 10);
    $pdf->Cell($totalw - 15, 4, utf8_decode("Total0: "), 0, 0, "R");
    $pdf->Cell(15, 4, $egreso["tarifa0"], 0, 1, "R");
    $pdf->Cell($totalw - 15, 4, utf8_decode("Tota l2: "), 0, 0, "R");
    $pdf->Cell(15, 4, $egreso["tarifa12"], 0, 1, "R");
    //$pdf->Cell($totalw - 15, 4, utf8_decode("Iva: "), 0, 0, "R");
    //$pdf->Cell(15, 4, $egreso["iva_egreso"], 0, 1, "R");
    //$pdf->Cell($totalw - 15, 4, utf8_decode("Descuento: "), 0, 0, "R");
    //$pdf->Cell(15, 4, $egreso["descuento_egreso"], 0, 1, "R");
    $pdf->Cell($totalw - 15, 4, utf8_decode("Total: "), 0, 0, "R");
    $pdf->Cell(15, 4, $egreso["total_egreso"], 0, 1, "R");
}
$pdf->Output();
