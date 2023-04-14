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
        $this->Cell(105, 5, "CONTABILIDAD", 0, 1, 'C', 0);
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
        $this->Line(0, 30, 210, 30);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(210, 5, utf8_decode("GIRAS PROVEEDORES"), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->Ln(3);
        $this->SetFont('helvetica', 'B', 9);
        $this->SetFillColor(175, 215, 240);
        $this->Cell(15, 6, utf8_decode('TIPO'), 1, 0, 'C', 1);
        $this->Cell(30, 6, utf8_decode('COMPROBANTE'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('EMISIÓN'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('VENCIMIENTO'), 1, 0, 'C', 1);
        $this->Cell(15, 6, utf8_decode('DIAS'), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode('FACTURAS'), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode('FUENTE'), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode('IVA'), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode('ABONO'), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode('SALDO'), 1, 1, 'C', 1);
        $this->Ln(1);
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
$query_punto="";

if ($_GET['id_empre'] != '0') {
    $query_punto = "AND f.id_empresa='$_GET[id_empre]'";
}
$pdf = new PDF('P', 'mm', 'a4');
$pdf->SetTitle('Giras');
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->AliasNbPages();

$sql = pg_query("SELECT id_proveedor, tipo_documento, identificacion_pro, empresa_pro, representante_legal FROM proveedores WHERE estado='Activo';");
if (pg_num_rows($sql)) {
    $total = 0;
    $iva = 0;
    $fuente = 0;
    $abono = 0;
    $saldo = 0;
    $query_fecha = "";
    // RANGO DE FECHAS O FECHA ACTUAL
    if ($pdf->rango) {
        $query_fecha = "BETWEEN '$_GET[inicio]' AND";
    } else {
        $query_fecha = "=";
    }
    while ($row = pg_fetch_row($sql)) {
        $sub = 0;
        $suba = 0;
        $subf = 0;
        $subi = 0;
        $subs = 0;
        $pdf->SetFillColor(220, 240, 210);
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(70, 8, utf8_decode(strtoupper($row[1]) . ": " . $row[2]), 0, 0, 'L', true);
        $pdf->Cell(70, 8, utf8_decode("PROVEEDOR: " . $row[3]), 0, 0, 'L', true);
        $pdf->Cell(70, 8, utf8_decode("REPRESENTANTE: " . $row[4]), 0, 1, 'L', true);

        // CONTADO
        $sql1 = pg_query(
            "SELECT DISTINCT ON(id_factura_compra) 'CA', f.num_serie, fecha_emision, fecha_caducidad, fecha_caducidad::date-fecha_emision::date, 
            0, rf.valor_retencion, ri.valor_retencion, total_compra, total_compra FROM factura_compra f 
            LEFT JOIN retencion_fuente_factura_compra rf ON rf.id_factura=f.id_factura_compra 
            LEFT JOIN retencion_iva_factura_compra ri ON ri.id_factura=f.id_factura_compra
            WHERE f.id_proveedor='$row[0]' AND f.estado='Activo' AND forma_pago='Contado'
            AND f.fecha_registro $query_fecha '$_GET[fin]' $query_punto
            ORDER BY id_factura_compra;"
        );
        if (pg_num_rows($sql1)) {
            while ($row1 = pg_fetch_row($sql1)) {
                $pdf->SetFont('helvetica', '', 8);
                $pdf->SetX(1);
                $pdf->Cell(14, 6, utf8_decode($row1[0]), 0, 0, 'C', 0);
                $pdf->Cell(30, 6, utf8_decode($row1[1]), 0, 0, 'C', 0);
                $pdf->Cell(25, 6, utf8_decode($row1[2]), 0, 0, 'C', 0);
                $pdf->Cell(25, 6, utf8_decode($row1[3]), 0, 0, 'C', 0);
                $pdf->Cell(15, 6, number_format($row1[4]), 0, 0, 'C', 0);
                $pdf->Cell(20, 6, number_format($row1[5], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(20, 6, number_format($row1[6], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(20, 6, number_format($row1[7], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(20, 6, number_format($row1[8], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(20, 6, number_format($row1[9], 2, ',', '.'), 0, 1, 'R', 0);
                $sub += $row1[5];
                $subf += $row1[6];
                $subi += $row1[7];
                $suba += $row1[8];
                $subs += $row1[9];
            }
        }
        $query_puntocp="";

if ($_GET['id_empre'] != '0') {
    $query_puntocp = "AND cp.id_empresa='$_GET[id_empre]'";
}
        // EXTERNAS
        $sql1 = pg_query(
            "SELECT 'EXT', num_factura, fecha_emicion, fecha_actual, fecha_actual::date-fecha_emicion::date, 
            ((total::numeric - saldo::numeric) - total::numeric), rf.valor_retencion, ri.valor_retencion, 
            (total::numeric - saldo::numeric), (((total::numeric - saldo::numeric) - total::numeric) - (total::numeric - saldo::numeric))
            FROM c_pagarexternas cp LEFT JOIN retencion_fuente_factura_compra rf ON rf.num_serie=cp.num_factura
            LEFT JOIN retencion_iva_factura_compra ri ON ri.num_serie=cp.num_factura
            WHERE id_proveedor='$row[0]' AND cp.estado='Activo'
            AND cp.fecha_actual $query_fecha '$_GET[fin]' $query_puntocp
            ORDER BY id_c_pagarexternas;"
        );
        if (pg_num_rows($sql1)) {
            while ($row1 = pg_fetch_row($sql1)) {
                $pdf->SetFont('helvetica', '', 8);
                $pdf->SetX(1);
                $pdf->Cell(14, 6, utf8_decode($row1[0]), 0, 0, 'C', 0);
                $pdf->Cell(30, 6, utf8_decode($row1[1]), 0, 0, 'C', 0);
                $pdf->Cell(25, 6, utf8_decode($row1[2]), 0, 0, 'C', 0);
                $pdf->Cell(25, 6, utf8_decode($row1[3]), 0, 0, 'C', 0);
                $pdf->Cell(15, 6, number_format($row1[4]), 0, 0, 'C', 0);
                $pdf->Cell(20, 6, number_format($row1[5], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(20, 6, number_format($row1[6], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(20, 6, number_format($row1[7], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(20, 6, number_format($row1[8], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(20, 6, number_format($row1[9], 2, ',', '.'), 0, 1, 'R', 0);
                $sub += $row1[5];
                $subf += $row1[6];
                $subi += $row1[7];
                $suba += $row1[8];
                $subs += $row1[9];
            }
        }
        $query_punto="";

if ($_GET['id_empre'] != '0') {
    $query_punto = "AND f.id_empresa='$_GET[id_empre]'";
}
        // INTERNAS
        $sql1 = pg_query(
            "SELECT DISTINCT ON(id_pagos_compra) 'FC', f.num_serie, fecha_emision, fecha_caducidad, fecha_caducidad::date-fecha_emision::date, 
            ((monto_credito::numeric - saldo::numeric)-monto_credito::numeric), rf.valor_retencion, ri.valor_retencion, 
            (monto_credito::numeric - saldo::numeric), (((monto_credito::numeric - saldo::numeric)-monto_credito::numeric)-(monto_credito::numeric - saldo::numeric))
            FROM pagos_compra cp LEFT JOIN factura_compra f USING (id_factura_compra) 
            LEFT JOIN retencion_fuente_factura_compra rf ON rf.id_factura=cp.id_factura_compra 
            LEFT JOIN retencion_iva_factura_compra ri ON ri.id_factura=cp.id_factura_compra
            WHERE f.id_proveedor='$row[0]' AND f.estado='Activo'
            AND f.fecha_registro $query_fecha '$_GET[fin]' $query_punto
            ORDER BY id_pagos_compra;"
        );
        if (pg_num_rows($sql1)) {
            while ($row1 = pg_fetch_row($sql1)) {
                $pdf->SetFont('helvetica', '', 8);
                $pdf->SetX(1);
                $pdf->Cell(14, 6, utf8_decode($row1[0]), 0, 0, 'C', 0);
                $pdf->Cell(30, 6, utf8_decode($row1[1]), 0, 0, 'C', 0);
                $pdf->Cell(25, 6, utf8_decode($row1[2]), 0, 0, 'C', 0);
                $pdf->Cell(25, 6, utf8_decode($row1[3]), 0, 0, 'C', 0);
                $pdf->Cell(15, 6, number_format($row1[4]), 0, 0, 'C', 0);
                $pdf->Cell(20, 6, number_format($row1[5], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(20, 6, number_format($row1[6], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(20, 6, number_format($row1[7], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(20, 6, number_format($row1[8], 2, ',', '.'), 0, 0, 'R', 0);
                $pdf->Cell(20, 6, number_format($row1[9], 2, ',', '.'), 0, 1, 'R', 0);
                $sub += $row1[5];
                $subf += $row1[6];
                $subi += $row1[7];
                $suba += $row1[8];
                $subs += $row1[9];
            }
        }
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
        $pdf->Cell(110, 6, utf8_decode("Total Proveedor"), 0, 0, 'R', 0);
        $pdf->Cell(20, 6, number_format($sub, 2, ',', '.'), 0, 0, 'R', 0);
        $pdf->Cell(20, 6, number_format($subf, 2, ',', '.'), 0, 0, 'R', 0);
        $pdf->Cell(20, 6, number_format($subi, 2, ',', '.'), 0, 0, 'R', 0);
        $pdf->Cell(20, 6, number_format($suba, 2, ',', '.'), 0, 0, 'R', 0);
        $pdf->Cell(20, 6, number_format($subs, 2, ',', '.'), 0, 1, 'R', 0);
        $pdf->Ln(3);
        $total += $sub;
        $iva += $subi;
        $fuente += $subf;
        $abono += $suba;
        $saldo += $subs;
    }
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
    $pdf->Cell(110, 6, utf8_decode("Total"), 0, 0, 'R', 0);
    $pdf->Cell(20, 6, number_format($total, 2, ',', '.'), 0, 0, 'R', 0);
    $pdf->Cell(20, 6, number_format($fuente, 2, ',', '.'), 0, 0, 'R', 0);
    $pdf->Cell(20, 6, number_format($iva, 2, ',', '.'), 0, 0, 'R', 0);
    $pdf->Cell(20, 6, number_format($abono, 2, ',', '.'), 0, 0, 'R', 0);
    $pdf->Cell(20, 6, number_format($saldo, 2, ',', '.'), 0, 1, 'R', 0);
    $pdf->Ln(3);
}
$pdf->Output();
