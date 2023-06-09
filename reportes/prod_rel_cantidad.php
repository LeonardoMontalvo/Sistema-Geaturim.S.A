<?php
require('../fpdf/fpdf.php');
include '../procesos/base.php';
include '../procesos/funciones.php';
conectarse();
date_default_timezone_set('America/Guayaquil');
session_start();

$idcierrec = $_GET["id"];
$cierrecaja = obtenerCierreCaja($idcierrec);



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
        $this->Cell(105, 5, "PRODUCTOS VENDIDOS", 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 14);
        $this->SetX(0);
        $this->Cell(210, 8, $_SESSION['nombre_empresa'], 0, 1, 'C', 0);
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
        $this->SetX(0);
        $this->Cell(210, 5, utf8_decode("STOCK DE PRODUCTOS AL CIERRE"), 0, 1, 'C', 0);
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

$pdf = new PDF('P', 'mm', 'a4');
$pdf->SetTitle('Stock de productos al cierre');
$pdf->SetMargins(10, 0);
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Amble-Regular', '', 9);

imprimirDatosCaja();
$pdf->Ln(5);
imprirmiObservacionesCaja();
$pdf->Ln(10);
imprirmirProductosNoRelacionados();

$pdf->Output();

function imprimirDatosCaja()
{
    global $pdf, $cierrecaja;
    $totalw = $pdf->GetCurrentWidth();

    if ($cierrecaja[0]["estado"] == 'Pasivo') {
        $pdf->SetFont("helvetica", "B", 11);
        $pdf->Cell($totalw / 2, 5, "(Registro de caja anulado)", 0, 1);
    }
    $pdf->SetFont("helvetica", "", 11);
    $pdf->Cell($totalw / 2, 5, "Usuario Caja: " . $cierrecaja[0]["nombre_usuario"] . " " . $cierrecaja[0]["apellido_usuario"], 0, 1);
    $pdf->Cell($totalw / 2, 5, "Fecha Apertura: " . $cierrecaja[0]["fecha_actual"] . " - " . $cierrecaja[0]["hora_actual"], 0, 1);
    $pdf->Cell($totalw / 2, 5, "Fecha Cierre: " . $cierrecaja[0]["fecha_cierre"] . " - " . $cierrecaja[0]["hora_cierre"], 0, 1);
}

function imprirmiObservacionesCaja()
{
    global $pdf, $cierrecaja;
    $totalw = $pdf->GetCurrentWidth();
    $pdf->SetFont("helvetica", "B", 11);
    $pdf->Cell($totalw / 2, 5, "Observaciones Apetura: ", 0, 1);
    $pdf->SetFont("helvetica", "", 11);
    $pdf->MultiCell($totalw, 5, $cierrecaja[0]["observacion"]);
    $pdf->Ln(5);
    $pdf->SetFont("helvetica", "B", 11);
    $pdf->Cell($totalw / 2, 5, "Observaciones Cierre: ", 0, 1);
    $pdf->SetFont("helvetica", "", 11);
    $pdf->MultiCell($totalw, 5, $cierrecaja[0]["observacion_cierre"]);
}

function imprirmirProductosNoRelacionados()
{
    global $pdf;
    $prodsven = obtenerProductosVendidos2();
    if (empty($prodsven)) {
        return;
    }
    $totalw = $pdf->GetCurrentWidth();
    $w = $totalw / 4;

    $pdf->SetAligns(["C", "C", "C", "C"]);
    $pdf->SetWidths([$w + 50, $w - 10, $w - 20, $w - 20]);
    $pdf->SetFont("helvetica", "B", 9);
    $pdf->Row([
        "PRODUCTO", "CANTIDAD VENDIDA", "TOTAL", "STOCK"
    ], 1);

    $pdf->SetFont('Amble-Regular', '', 10);
    $pdf->SetAligns(["L", "R", "R", "R"]);
    $total = 0;

    foreach ($prodsven as $value2) {
        $stock = obtenerStockProducto($value2["cod_productos"]);
        $pdf->Row([
            utf8_decode($value2["articulo"]),
            $value2["cantidad"],
            number_format($value2["total_venta"], 2, ",", "."),
            $stock
        ]);
        $total += $value2["total_venta"];
    }
    $pdf->Cell($totalw, 5, "", "B", 1);
}

function obtenerProductosVendidos2()
{
    global $cierrecaja;
    $fechaapertura = $cierrecaja[0]["fecha_actual"];
    $fechacierre = $cierrecaja[0]["fecha_cierre"];
    $horaapertura = $cierrecaja[0]["hora_actual"];
    $horacierre = $cierrecaja[0]["hora_cierre"];
    $sql = "
    select 
    x.cod_productos,
    x.cod_barras,
    x.articulo,
    sum(x.cantidad) cantidad,
    sum(x.total_venta) total_venta
    from (
    (select p.cod_productos,
        p.cod_barras,
        p.articulo,
        sum(dfv.cantidad) cantidad,
        sum(dfv.total_venta) total_venta
    from factura_venta fv
        inner join detalle_factura_venta dfv using(id_factura_venta)
        inner join productos p using (cod_productos)

    where 
        p.inventariable = 'Si'
        and fv.id_empresa=" . $cierrecaja[0]["id_empresa"] . "
        and fv.id_usuario = " . $cierrecaja[0]["id_usuario"] . "
        and fv.fecha_actual between '" . $fechaapertura . "' and '" . $fechacierre . "'
        and fv.hora_actual::time between '" . $horaapertura . "' and '" . $horacierre . "'
        and fv.estado='Activo'
    group by 
        p.articulo,
        p.cod_productos,
        p.cod_barras
    order by p.cod_productos asc)
    union all
    (select p.cod_productos,
        p.cod_barras,
        p.articulo,
        sum(dfv.cantidad) cantidad,
        sum(dfv.total_venta) total_venta
    from facturas_novalidas fv
        inner join detalle_facturas_novalidas dfv using(id_facturas_novalidas)
        inner join productos p using (cod_productos)
    where 
        p.inventariable = 'Si'
        and fv.id_empresa=" . $cierrecaja[0]["id_empresa"] . "
        and fv.id_usuario = " . $cierrecaja[0]["id_usuario"] . "
        and fv.fecha_actual between '" . $fechaapertura . "' and '" . $fechacierre . "'
        and fv.hora_actual::time between '" . $horaapertura . "' and '" . $horacierre . "'
        and fv.estado='Activo'
    group by 
        p.articulo,
        p.cod_productos,
        p.cod_barras
    order by p.cod_productos asc)
    )as x
        group by 
        x.cod_productos,
        x.articulo,
        x.cod_productos,
        x.cod_barras;
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}

function obtenerStockProducto($idprod)
{
    global $cierrecaja;
    $cstock = "";
    if (!empty($cierrecaja)) {
        $cstock = $cierrecaja[0]["captura_stock_ciere"];
    }
    $arrcstock = json_decode($cstock, true);
    $stock = array_filter($arrcstock, function ($var) use ($idprod) {
        return $var["cod_productos"] == $idprod;
    });
    if (empty($stock)) {
        return 0;
    }
    return array_pop($stock)["stock"];
}

function obtenerProducto($idprod)
{
    $sql = "select*from productos where cod_productos=$idprod";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows[0];
}

function obtenerCierreCaja($idcierrec)
{
    global $idcierrec;
    $sql = "
    select u.nombre_usuario,u.apellido_usuario, cc.* from cierre_caja cc
    inner join usuario u
    using(id_usuario)
    where id_cierre_caja = $idcierrec
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}
