<?php
require('../fpdf/fpdf.php');
include '../procesos/base.php';
include '../procesos/funciones.php';
session_start();
conectarse();

class PDF extends FPDF
{

    var $widths;
    var $aligns;

    // Page header
    function Header() {}

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

    function Row($data, $border = 0, $style = "", $fill = false, $border_cell = 0, $heightl = 5)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        $h = $heightl * $nb;
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

            $this->MultiCell($w, $heightl, $data[$i], $border_cell, $a, $fill);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function CheckPageBreak($h)
    {
        //If the height h would cause an overflow, add a new page immediately
        if ($this->GetY() + $h > $this->PageBreakTrigger) {
            $this->AddPage($this->CurOrientation);
        }
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
}


$pdf = new PDF('P', 'mm', array(70, 600));
date_default_timezone_set('America/Guayaquil');

$fecha = date('Y-m-d H:i:s', time());
$cierre = obtenerCierre($_GET["id"]);

$pdf->AddPage();
$pdf->setTitle('Cierre de Caja');

$pdf->SetMargins(2, 0);
$pdf->Ln(0);
$cw = $pdf->GetCurrentWidth();

$pdf->SetY(5);
$cw = $pdf->GetCurrentWidth();
$pdf->SetFont('Arial', 'B', 9);
if ($cierre["estado"] == 'Pasivo') {
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell($cw, 4, utf8_decode("ANULADO"), 0, 1, "C");
    $pdf->Ln(3);
    $pdf->SetFont('Arial', 'B', 9);
}
$pdf->MultiCell($cw, 4, utf8_decode($_SESSION["nombre_empresa"]), 0, "C");
$pdf->Ln(2);
$pdf->Cell($cw, 4, utf8_decode("CIERRE DE CAJA"), 0, 1, "C");
$pdf->SetFont('Arial', '', 9);
$pdf->Cell($cw, 2, utf8_decode("-------------------------------------------------------------------"), 0, 1, "C");
$pdf->Ln(2);

$pdf->Cell($cw, 4, utf8_decode("Fecha de cierre: $cierre[fecha_cierre]"), 0, 1, "L");
$pdf->Cell($cw, 4, utf8_decode("Hora de cierre: $cierre[hora_cierre]"), 0, 1, "L");
$pdf->Cell($cw, 4, utf8_decode("Usuario de cierre: $cierre[usuario]"), 0, 1, "L");
$pdf->Cell($cw, 2, utf8_decode("-------------------------------------------------------------------"), 0, 1, "C");
$pdf->Ln(2);

$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell($cw / 2, 4, "MONTO DE APERTURA:", 0, 0, "L");
$pdf->Cell($cw / 2, 4, "$$cierre[monto_apertura]", 0, 1, "R");
$pdf->SetFont('Arial', '', 9);
$pdf->Cell($cw, 2, utf8_decode("-------------------------------------------------------------------"), 0, 1, "C");
$pdf->Ln(2);

$w = $cw / 3;
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell($cw, 4, utf8_decode("DESGLOSE DE CIERRE DE CAJA:"), 0, 1, "L");
$pdf->Ln(2);

$pdf->SetWidths([$w + 20, $w - 10, $w - 10]);
$pdf->SetAligns(["C", "C", "C"]);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array(utf8_decode("DENOMINACIÓN"), utf8_decode("CANT"), "VALOR"));
$pdf->SetFont('Arial', '', 8);
$pdf->SetAligns(["L", "C", "R"]);
$pdf->Row(array(utf8_decode($cierre["denominacion_cien"]), $cierre["cantidad_cien"], $cierre["total_cantidad_cien"]), 0, "", false, 0, 4);
$pdf->Row(array(utf8_decode($cierre["denominacion_cincuenta"]), $cierre["cantidad_cincuenta"], $cierre["total_cantidad_cincuenta"]), 0, "", false, 0, 4);
$pdf->Row(array(utf8_decode($cierre["denominacion_veinte"]), $cierre["cantidad_veinte"], $cierre["total_cantidad_veinte"]), 0, "", false, 0, 4);
$pdf->Row(array(utf8_decode($cierre["denominacion_diez"]), $cierre["cantidad_diez"], $cierre["total_cantidad_diez"]), 0, "", false, 0, 4);
$pdf->Row(array(utf8_decode($cierre["denominacion_cinco"]), $cierre["cantidad_cinco"], $cierre["total_cantidad_cinco"]), 0, "", false, 0, 4);
$pdf->Row(array(utf8_decode($cierre["denominacion_uno"]), $cierre["cantidad_uno"], $cierre["total_cantidad_uno"]), 0, "", false, 0, 4);

$pdf->Row(array(utf8_decode($cierre["denominacion_cero_cincuenta"]), $cierre["cantidad_cero_cincuenta"], $cierre["total_cantidad_cero_cincuenta"]), 0, "", false, 0, 4);
$pdf->Row(array(utf8_decode($cierre["denominacion_cero_veinticinco"]), $cierre["cantidad_cero_veinticinco"], $cierre["total_cantidad_cero_veinticinco"]), 0, "", false, 0, 4);
$pdf->Row(array(utf8_decode($cierre["denominacion_cero_diez"]), $cierre["cantidad_cero_diez"], $cierre["total_cantidad_cero_diez"]), 0, "", false, 0, 4);
$pdf->Row(array(utf8_decode($cierre["denominacion_cero_cinco"]), $cierre["cantidad_cero_cinco"], $cierre["total_cantidad_cero_cinco"]), 0, "", false, 0, 4);
$pdf->Row(array(utf8_decode($cierre["denominacion_cero_uno"]), $cierre["cantidad_cero_uno"], $cierre["total_cantidad_cero_uno"]), 0, "", false, 0, 4);
$pdf->Ln(2);
$total =
    $cierre["total_cantidad_cien"] +
    $cierre["total_cantidad_cincuenta"] +
    $cierre["total_cantidad_veinte"] +
    $cierre["total_cantidad_diez"] +
    $cierre["total_cantidad_cinco"] +
    $cierre["total_cantidad_uno"] +
    $cierre["total_cantidad_cero_cincuenta"] +
    $cierre["total_cantidad_cero_veinticinco"] +
    $cierre["total_cantidad_cero_diez"] +
    $cierre["total_cantidad_cero_cinco"] +
    $cierre["total_cantidad_cero_uno"];

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(($w * 2) + 2, 5, "TOTAL:", "T");
$pdf->Cell($w - 2, 5, "$" . $total, "T", 1, "R");
$pdf->Cell($cw, 2, utf8_decode("-------------------------------------------------------------------"), 0, 1, "C");
$pdf->Ln(2);

$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell($cw, 4, utf8_decode("VALORES INFORMADOS:"), 0, 1, "L");
$pdf->Ln(2);
$totalentregar = /* $cierre["monto_apertura"] + */ $total;
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(($w * 2) + 2, 4, "TOTAL EFECTIVO:", 0, 0, "L");
$pdf->Cell($w - 2, 5, "$" . $totalentregar, 0, 1, "R");
$pdf->Cell(($w * 2) + 2, 4, "TOTAL VALOR TRANSFERENCIA:", 0, 0, "L");
$pdf->Cell($w - 2, 5, "$" . $cierre["valor_transferencia"], 0, 1, "R");
$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(($w * 2) + 2, 4, "TOTAL:", 0, 0, "L");
$pdf->Cell($w - 2, 5, "$" . ($totalentregar + $cierre["valor_transferencia"]), 0, 1, "R");

$pdf->Cell($cw, 2, utf8_decode("-------------------------------------------------------------------"), 0, 1, "C");
$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell($cw, 4, "VALORES SISTEMA: ", 0, 1, "L");
$pdf->Ln(1);
$pdf->SetFont('Arial', '', 9);
$vtcredito = obtenerValoresTcredito(
    $cierre['fecha_actual'],
    $cierre['fecha_cierre'],
    $cierre['id_empresa'],
    $cierre['id_usuario'],
    $cierre['hora_actual'],
    $cierre['hora_cierre']
);
$vtrandferencia = obtenerValoresTransferencia(
    $cierre['fecha_actual'],
    $cierre['fecha_cierre'],
    $cierre['id_empresa'],
    $cierre['id_usuario'],
    $cierre['hora_actual'],
    $cierre['hora_cierre']
);
$vefectivo = obtenerValoresEfectivo(
    $cierre['fecha_actual'],
    $cierre['fecha_cierre'],
    $cierre['id_empresa'],
    $cierre['id_usuario'],
    $cierre['hora_actual'],
    $cierre['hora_cierre']
);
$vgastosi = obtenerValoresGastosI(
    $cierre['fecha_actual'],
    $cierre['fecha_cierre'],
    $cierre['id_empresa'],
    $cierre['id_usuario'],
    $cierre['hora_actual'],
    $cierre['hora_cierre']
);

if (empty($vgastosi)) {
    $vgastosi = 0;
}
$w = $cw / 2;
$pdf->SetWidths([$w + 10, $w - 10]);
$pdf->SetAligns(["L", "R"]);

$pdf->Row([
    utf8_decode("+ TARJETA CRÉD/DÉB: "),
    "$" . $vtcredito
]);
$pdf->Row([
    utf8_decode("+ TRANSFERENCIAS: "),
    "$" . $vtrandferencia
]);

$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetWidths([$w + 12, $w - 12]);
$pdf->Row([
    utf8_decode("TOTAL: "),
    "$" . ($vtrandferencia + $vtcredito)
], 0, "", false, 0, 3);

$pdf->Ln(2);
$pdf->SetFont('Arial', '', 9);
$pdf->Cell($cw, 2, utf8_decode("-------------------------------------------------------------------"), 0, 1, "C");
$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell($cw, 4, "ARQUEO DE CAJA: ", 0, 1, "L");
$pdf->Ln(2);

$pdf->SetFont('Arial', '', 9);
$w = $cw / 2;
$pdf->SetWidths([$w + 15, $w - 15]);
$pdf->SetAligns(["L", "R"]);
$pdf->Row([
    utf8_decode("+ APERTURA CAJA: "),
    "$" . $cierre["monto_apertura"]
]);
$pdf->Row([
    utf8_decode("+ EFECTIVO SISTEMA: "),
    "$" . round($vefectivo, 2)
]);
$pdf->Row([
    utf8_decode("- GASTOS INTERNOS: "),
    "$" . round($vgastosi, 2)
]);
$pdf->Row([
    utf8_decode("- EFECTIVO INFORMADO: "),
    "$" . $totalentregar
]);
$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetWidths([$w + 12, $w - 12]);
$pdf->Row([
    utf8_decode("DIFERENCIA: "),
    "$" . round($totalentregar - ($cierre["monto_apertura"] + $vefectivo - $vgastosi), 2)
], 0, "", false, 0, 3);

$prodven = obtenerProductosVendidos();

$pdf->Ln(2);
$pdf->SetFont('Arial', '', 9);
$pdf->Cell($cw, 2, utf8_decode("-------------------------------------------------------------------"), 0, 1, "C");
$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell($cw, 4, "PRODUCTOS VENDIDOS: ", 0, 1, "L");
$pdf->Ln(3);
imprirmirProductosVendidos();

$pdf->SetFont('Arial', '', 9);
$pdf->Cell($cw, 2, utf8_decode("-------------------------------------------------------------------"), 0, 1, "C");
$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell($cw, 4, "OBSERVACIONES DE CIERRE: ", 0, 1, "L");
$pdf->Ln(2);
$pdf->SetFont('Arial', '', 9);
$pdf->MultiCell($cw, 4, trim($cierre["observacion_cierre"]));

/*$pdf->Cell($cw, 2, utf8_decode("-------------------------------------------------------------------"), 0, 1, "C");
$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell($cw, 4, "RESUMEN SISTEMA: ", 0, 1, "L");
$pdf->Ln(1);
$pdf->SetFont('Arial', '', 9);
$vtcredito = obtenerValoresTcredito(
    $cierre['fecha_actual'],
    $cierre['fecha_cierre'],
    $cierre['id_empresa'],
    $cierre['id_usuario'],
    $cierre['hora_actual'],
    $cierre['hora_cierre']
);
$vtrandferencia = obtenerValoresTransferencia(
    $cierre['fecha_actual'],
    $cierre['fecha_cierre'],
    $cierre['id_empresa'],
    $cierre['id_usuario'],
    $cierre['hora_actual'],
    $cierre['hora_cierre']
);
$vefectivo = obtenerValoresEfectivo(
    $cierre['fecha_actual'],
    $cierre['fecha_cierre'],
    $cierre['id_empresa'],
    $cierre['id_usuario'],
    $cierre['hora_actual'],
    $cierre['hora_cierre']
);
$vgastosi = obtenerValoresGastosI(
    $cierre['fecha_actual'],
    $cierre['fecha_cierre'],
    $cierre['id_empresa'],
    $cierre['id_usuario'],
    $cierre['hora_actual'],
    $cierre['hora_cierre']
);
if (empty($vgastosi)) {
    $vgastosi = 0;
}
$w = $cw / 2;
$pdf->SetWidths([$w + 10, $w - 10]);
$pdf->SetAligns(["L", "R"]);
$pdf->Row([
    utf8_decode("+ EFECTIVO: "),
    "$" . $vefectivo
]);
$pdf->Row([
    utf8_decode("+ CRÉDITO: "),
    "$" . $vtcredito
]);
$pdf->Row([
    utf8_decode("+ TRANSFERENCIAS: "),
    "$" . $vtrandferencia
]);
$pdf->Row([
    utf8_decode("- GASTOS INTERNOS: "),
    "$" . $vgastosi
]);
$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetWidths([$w + 12, $w - 12]);
$pdf->Row([
    utf8_decode("TOTAL: "),
    "$" . ($vtrandferencia + $vtcredito + $vefectivo - $vgastosi)
], 0, "", false, 0, 3);

$pdf->Ln(2);
$pdf->SetFont('Arial', '', 9);
$pdf->Cell($cw, 2, utf8_decode("-------------------------------------------------------------------"), 0, 1, "C");
$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell($cw, 4, "ARQUEO DE CAJA: ", 0, 1, "L");
$pdf->Ln(2);

$pdf->SetFont('Arial', '', 9);
$w = $cw / 2;
$pdf->SetWidths([$w + 15, $w - 15]);
$pdf->SetAligns(["L", "R"]);
$pdf->Row([
    utf8_decode("+ APERTURA CAJA: "),
    "$" . $cierre["monto_apertura"]
]);
$pdf->Row([
    utf8_decode("+ EFECTIVO SISTEMA: "),
    "$" . $vefectivo
]);
$pdf->Row([
    utf8_decode("- GASTOS INTERNOS: "),
    "$" . $vgastosi
]);
$pdf->Row([
    utf8_decode("- EFECTIVO INFORMADO: "),
    "$" . $totalentregar
]);
$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetWidths([$w + 12, $w - 12]);
$pdf->Row([
    utf8_decode("DIFERENCIA: "),
    "$" . ($totalentregar-($cierre["monto_apertura"] + $vefectivo - $vgastosi))
], 0, "", false, 0, 3);*/

$pdf->Output();

function obtenerCierre($id)
{
    $sql = "
    select
    cc.*,
    u.usuario
    from cierre_caja cc 
    inner join usuario u
    using(id_usuario)
    where id_cierre_caja=$id";
    $res = pg_query($sql);
    return pg_fetch_assoc($res);
}

function obtenerValoresTcredito($fechai, $fechaf, $idpv, $idusuario, $horai, $horaf)
{
    $tarjetaCredito = 0;
    $sql = pg_query("SELECT sum(total_venta::float) FROM factura_venta 
    WHERE 
    (fecha_actual between '$fechai' and '$fechaf'
    and TO_TIMESTAMP(hora_actual, 'HH12:MI:SS AM')::TIME between '$horai' and '$horaf')
    and forma_pago='TCredito' 
    and estado = 'Activo'   
    and id_empresa='$idpv' and id_usuario='$idusuario'");
    while ($row = pg_fetch_row($sql)) {
        $tarjetaCredito = $row[0];
    }
    $sqlc2 = pg_query("SELECT 
    sum(valor::float) 
    FROM factura_venta fv 
    inner join formas_pago_mixto fpm
    on fv.id_factura_venta=fpm.id_factura_venta
    WHERE 
    (fv.fecha_actual between '$fechai' and '$fechaf'
    and TO_TIMESTAMP(fv.hora_actual, 'HH12:MI:SS AM')::TIME between '$horai' and '$horaf')
    and (fv.forma_pago='otros' 
    and fv.estado = 'Activo'
    and fpm.forma_pago='TCREDITO')   
    and fpm.tipo_documento='FACTURA'
    and fv.id_empresa='$idpv' and id_usuario='$idusuario'");
    while ($row = pg_fetch_row($sqlc2)) {
        $tarjetaCredito += $row[0];
    }

    $sql = pg_query("SELECT sum(total_venta::float) 
    FROM facturas_novalidas 
    WHERE 
    (fecha_actual between '$fechai' and '$fechaf'
    and TO_TIMESTAMP(hora_actual, 'HH12:MI:SS AM')::TIME between '$horai' and '$horaf')
    and estado = 'Activo'   
    and id_empresa='$idpv' 
    and forma_pago='TCredito' and id_usuario='$idusuario' ");
    while ($row = pg_fetch_row($sql)) {
        $notatarjetaCredito = $row[0];
    }
    $sqlc2 = pg_query("SELECT 
    sum(valor::float) 
    FROM facturas_novalidas fv 
    inner join formas_pago_mixto fpm
    on fv.id_facturas_novalidas=fpm.id_factura_venta
    WHERE 
    (fv.fecha_actual between '$fechai' and '$fechaf'
    and TO_TIMESTAMP(fv.hora_actual, 'HH12:MI:SS AM')::TIME between '$horai' and '$horaf')
    and (fv.forma_pago='otros' 
    and fv.estado = 'Activo'
    and fpm.forma_pago='TCREDITO')   
    and fpm.tipo_documento='NOTA'
    and fv.id_empresa='$idpv' and fv.id_usuario='$idusuario'");
    while ($row = pg_fetch_row($sqlc2)) {
        $notatarjetaCredito += $row[0];
    }

    return $tarjetaCredito + $notatarjetaCredito;
}

function obtenerValoresTransferencia($fechai, $fechaf, $idpv, $idusuario, $horai, $horaf)
{

    $sql = pg_query("SELECT sum(total_venta::float) 
    FROM factura_venta WHERE 
    (fecha_actual between '$fechai' and '$fechaf'
    and TO_TIMESTAMP(hora_actual, 'HH12:MI:SS AM')::TIME between '$horai' and '$horaf')
    and forma_pago='Transferencias' 
    and estado = 'Activo'   
    and id_empresa='$idpv'  
    and id_usuario='$idusuario'");

    while ($row = pg_fetch_row($sql)) {
        $transferencia = $row[0];
    }
    $sqlc2 = pg_query("SELECT 
    sum(valor::float) 
    FROM factura_venta fv 
    inner join formas_pago_mixto fpm
    on fv.id_factura_venta=fpm.id_factura_venta
    WHERE 
    (fv.fecha_actual between '$fechai' and '$fechaf'
    and TO_TIMESTAMP(fv.hora_actual, 'HH12:MI:SS AM')::TIME between '$horai' and '$horaf')
    and (fv.forma_pago='otros' 
    and fv.estado = 'Activo'
    and fpm.forma_pago='TRANSFERENCIAS')   
    and fpm.tipo_documento='FACTURA'
    and fv.id_empresa='$idpv' and fv.id_usuario='$idusuario'");
    while ($row = pg_fetch_row($sqlc2)) {
        $transferencia += $row[0];
    }

    $sql = pg_query("SELECT sum(total_venta::float) 
    FROM facturas_novalidas WHERE 
    (fecha_actual between '$fechai' and '$fechaf'
    and TO_TIMESTAMP(hora_actual, 'HH12:MI:SS AM')::TIME between '$horai' and '$horaf')
    and estado = 'Activo'   and id_empresa='$idpv' 
    and forma_pago='Transferencias'  and id_usuario='$idusuario'");
    while ($row = pg_fetch_row($sql)) {
        $notaTransferencia = $row[0];
    }
    $sqlc2 = pg_query("SELECT 
    sum(valor::float) 
    FROM facturas_novalidas fv 
    inner join formas_pago_mixto fpm
    on fv.id_facturas_novalidas=fpm.id_factura_venta
    WHERE 
    (fv.fecha_actual between '$fechai' and '$fechaf'
    and TO_TIMESTAMP(fv.hora_actual, 'HH12:MI:SS AM')::TIME between '$horai' and '$horaf')
    and (fv.forma_pago='otros' 
    and fv.estado = 'Activo'
    and fpm.forma_pago='TRANSFERENCIAS')   
    and fpm.tipo_documento='NOTA'
    and fv.id_empresa='$idpv' and fv.id_usuario='$idusuario'");
    while ($row = pg_fetch_row($sqlc2)) {
        $notaTransferencia += $row[0];
    }

    return $transferencia + $notaTransferencia;
}

function obtenerValoresEfectivo($fechai, $fechaf, $idpv, $idusuario, $horai, $horaf)
{
    $contado = 0;
    $contado_mixto = 0;
    $notaVentacont = 0;
    $notaVentacont_mixto = 0;

    $sql = pg_query("SELECT sum(total_venta::float) 
    FROM factura_venta WHERE 
    (fecha_actual between '$fechai' and '$fechaf'
    and TO_TIMESTAMP(hora_actual, 'HH12:MI:SS AM')::TIME between '$horai' and '$horaf')
    and forma_pago='Contado' 
    and estado = 'Activo'   
    and id_empresa='$idpv' and id_usuario='$idusuario'");
    while ($row = pg_fetch_row($sql)) {
        $contado += $row[0];
    }
    $sqlc2 = pg_query("SELECT sum(valor::float) FROM factura_venta fv 
    inner join formas_pago_mixto fpm on fv.id_factura_venta=fpm.id_factura_venta
    WHERE 
    (fv.fecha_actual between '$fechai' and '$fechaf'
    and TO_TIMESTAMP(fv.hora_actual, 'HH12:MI:SS AM')::TIME between '$horai' and '$horaf')
    and  fpm.forma_pago='CONTADO' and fpm.tipo_documento='FACTURA' 
    and fv.id_empresa='$idpv' and fv.estado = 'Activo' 
    and  fv.id_usuario='$idusuario'");
    while ($row = pg_fetch_row($sqlc2)) {
        $contado_mixto += $row[0];
    }
    $sql = pg_query("SELECT sum(total_venta::float) 
    FROM facturas_novalidas
    WHERE (fecha_actual between '$fechai' and '$fechaf'
    and TO_TIMESTAMP(hora_actual, 'HH12:MI:SS AM')::TIME between '$horai' and '$horaf') 
    and estado = 'Activo'  and id_empresa='$idpv' and forma_pago='Contado'  and id_usuario='$idusuario'");
    while ($row = pg_fetch_row($sql)) {
        $notaVentacont = $row[0];
    }
    $sqlc2 = pg_query("SELECT 
    sum(valor::float) 
    FROM facturas_novalidas fv 
    inner join formas_pago_mixto fpm
    on fv.id_facturas_novalidas=fpm.id_factura_venta
    WHERE 
    (fv.fecha_actual between '$fechai' and '$fechaf'
    and TO_TIMESTAMP(fv.hora_actual, 'HH12:MI:SS AM')::TIME between '$horai' and '$horaf')
    and  fv.estado = 'Activo'
    and fpm.forma_pago='CONTADO'  
    and fpm.tipo_documento='NOTA'
    and fv.id_empresa='$idpv' and fv.id_usuario='$idusuario'");
    while ($row = pg_fetch_row($sqlc2)) {
        $notaVentacont_mixto += $row[0];
    }

    return $contado + $contado_mixto + $notaVentacont + $notaVentacont_mixto;
}

function imprirmirProductosVendidos()
{
    global $pdf;
    $prodsven = obtenerProductosVendidos();
    if (empty($prodsven)) {
        return;
    }
    $totalw = $pdf->GetCurrentWidth();
    $w = $totalw / 4;

    $pdf->SetAligns(["C", "C", "C", "C"]);
    $pdf->SetWidths([$w - 8, $w + 22, $w - 6, $w - 8]);
    $total = 0;

    $pdf->SetFont("Arial", "B", 6);
    $pdf->Row([
        "CANT",
        "PROD",
        "TOTAL",
        "STK"
    ]);
    $pdf->SetAligns(["R", "L", "R", "R"]);
    foreach ($prodsven as $value2) {
        $stock = obtenerStockProducto($value2["cod_productos"]);
        $stock = round($stock, 2);
        if ($value2["inventariable"] == "No") {
            $stock = "--";
        }
        $pdf->SetFont("Arial", "", 6);
        $pdf->Row([
            round($value2["cantidad"], 2),
            substr($value2["articulo"], 0, 40),
            number_format($value2["total_venta"], 2, ",", ""),
            round($stock, 2)
        ], 0, "", false, 0, 3);
        $total += $value2["total_venta"];
    }
    /*  $pdf->SetFont("Arial", "B", 9);
    $pdf->Cell($totalw / 2, 4, "TOTAL VENDIDO:", "T", 0, "L");
    $pdf->Cell($totalw / 2, 4, round($total, 2), "T", 1, "R"); */
}

function obtenerProductosVendidos()
{
    global $cierre;
    $fechaapertura = $cierre["fecha_actual"];
    $fechacierre = $cierre["fecha_cierre"];
    $horaapertura = $cierre["hora_actual"];
    $horacierre = $cierre["hora_cierre"];
    $sql = "
    select 
    x.inventariable,
    x.cod_productos,
    x.cod_barras,
    x.articulo,
    sum(x.cantidad) cantidad,
    sum(x.total_venta) total_venta
    from (
    (select p.cod_productos,
        p.inventariable,
        p.cod_barras,
        p.articulo,
        sum(dfv.cantidad) cantidad,
        sum(dfv.total_venta+div.valor_impuesto) total_venta
    from factura_venta fv
        inner join detalle_factura_venta dfv using(id_factura_venta)
        inner join detalle_impuesto_producto_venta div using(id_detalle_venta)
        inner join productos p using (cod_productos)

    where 
        fv.id_empresa=" . $cierre["id_empresa"] . "
        and fv.id_usuario = " . $cierre["id_usuario"] . "
        and fv.fecha_actual between '" . $fechaapertura . "' and '" . $fechacierre . "'
        and fv.hora_actual::time between '" . $horaapertura . "' and '" . $horacierre . "'
        and fv.estado='Activo'
    group by 
        p.articulo,
        p.cod_productos,
        p.cod_barras,
        p.inventariable
    order by p.cod_productos asc)
    union all
    (select p.cod_productos,
        p.inventariable,
        p.cod_barras,
        p.articulo,
        sum(dfv.cantidad) cantidad,
        sum(dfv.total_venta+div.valor_impuesto) total_venta
    from facturas_novalidas fv
        inner join detalle_facturas_novalidas dfv using(id_facturas_novalidas)
        inner join detalle_impuesto_producto_notaventa div using(id_detalle_facturas_novalidas)
        inner join productos p using (cod_productos)
    where 
        fv.id_empresa=" . $cierre["id_empresa"] . "
        and fv.id_usuario = " . $cierre["id_usuario"] . "
        and fv.fecha_actual between '" . $fechaapertura . "' and '" . $fechacierre . "'
        and fv.hora_actual::time between '" . $horaapertura . "' and '" . $horacierre . "'
        and fv.estado='Activo'
    group by 
        p.articulo,
        p.cod_productos,
        p.cod_barras,
        p.inventariable
    order by p.cod_productos asc)
    )as x
        group by 
        x.cod_productos,
        x.articulo,
        x.cod_productos,
        x.cod_barras,
        x.inventariable;
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
    global $cierre;
    $cstock = "";
    if (!empty($cierre)) {
        $cstock = $cierre["captura_stock_ciere"];
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

function obtenerValoresGastosI($fechai, $fechaf, $idpv, $idusuario, $horai, $horaf)
{
    $sql = "
    select
    sum(total)
    from gastos_internos
    where
    (fecha_actual between '$fechai' and '$fechaf' 
        and TO_TIMESTAMP(hora_actual, 'HH12:MI:SS AM')::TIME between '$horai' and '$horaf' )
    and id_usuario=$idusuario 
    and estado='Activo'
    and id_empresa=$idpv
    ";
    $res = pg_query($sql);
    $row = pg_fetch_row($res);
    if (empty($row)) {
        return 0;
    }
    return $row[0];
}
