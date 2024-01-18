<?php

require('../fpdf/fpdf.php');
include '../procesos/base.php';
include '../procesos/funciones.php';
conectarse();
date_default_timezone_set('America/Guayaquil');
session_start();

class PDF extends FPDF {

    var $widths;
    var $aligns;

    function SetWidths($w) {
        $this->widths = $w;
    }

    function Header() {
//        $this->rango = false;
//        if ($_GET['inicio'] != '') {
//            $this->rango = true;
//        }
        $this->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->AddFont('helvetica', 'B', 'helveticab.php');
        $this->SetFont('Amble-Regular', '', 10);
        $fecha = date('Y-m-d', time());
        $this->SetX(0);
        $this->SetY(0);
        $this->Cell(105, 5, $fecha, 0, 0, 'C', 0);
        $this->Cell(105, 5, "CARTERA CxP", 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 14);
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
        $this->Cell(210, 5, utf8_decode("RECIBO DE ANTICIPO CLIENTE"), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
//        if ($this->rango) {
//            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
//            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
//        } else {
//            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
//        }
        $this->Ln(15);
        $this->SetFillColor(255, 255, 225);
        $this->SetLineWidth(0.2);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

}

$pdf = new PDF('P', 'mm', 'a4');
$pdf->SetMargins(0, 0, 0, 0);
//$pdf->SetTitle('Cuentas Pagar ' . $_GET['tipo']);
$pdf->AddPage();
$pdf->AliasNbPages();

// RANGO DE FECHAS O FECHA ACTUAL
//$query_fecha = "=";
//if ($pdf->rango) {
//    $query_fecha = "BETWEEN '$_GET[inicio]' AND";
//}
//$sql = pg_query("SELECT id_proveedor, tipo_documento, identificacion_pro, empresa_pro, representante_legal FROM proveedores WHERE estado='Activo';");
//if (pg_num_rows($sql)) {
$totala = 0;
$total = 0;
$saldo = 0;
$abonos = 0;
//EXTERNAS E INTERNAS
//    if ($_GET['tipo'] == 'Externas') {
//        while ($row = pg_fetch_row($sql)) {
//            $sql1 = pg_query(
//                "SELECT cp.num_factura, abreviatura, fecha_actual, fecha_emicion, 
//                (fecha_emicion::date - fecha_actual::date), '0', '0', total, 
//                (total::numeric - saldo::numeric) as abonos, saldo, cp.estado
//                FROM c_pagarexternas cp LEFT JOIN tipo_comprobante tc on cp.tipo_documento=tc.id_tipo_comprobante 
//                WHERE id_proveedor='$row[0]'AND cp.fecha_actual $query_fecha '$_GET[fin]' ORDER BY id_c_pagarexternas;"
//            );
//            if (pg_num_rows($sql1)) {
//                $sub = 0;
//                $subta = 0;
//                $subto = 0;
//                $subabo = 0;
//                $subsa = 0;
//                $pdf->SetFillColor(216, 216, 231);
//                $pdf->SetFont('helvetica', 'B', 9);
//                $pdf->Cell(50, 6, utf8_decode(strtoupper($row[1]) . ": " . $row[2]), 0, 0, 'L', true);
//                $pdf->Cell(80, 6, utf8_decode("PROVEEDOR: " . maxCaracter($row[3], 20)), 0, 0, 'L', true);
//                $pdf->Cell(80, 6, utf8_decode("REPRESENTANTE: " . maxCaracter($row[4], 20)), 0, 1, 'L', true);
//                $pdf->Ln(1);
//                $pdf->SetFillColor(175, 215, 240);
//                $pdf->Cell(30, 6, utf8_decode('N° DOCUMENTO'), 1, 0, 'C', 1);
//                $pdf->Cell(22, 6, utf8_decode('REGISTRO'), 1, 0, 'C', 1);
//                $pdf->Cell(25, 6, utf8_decode('VENCIMIENTO'), 1, 0, 'C', 1);
//                $pdf->Cell(15, 6, utf8_decode('DIAS'), 1, 0, 'C', 1);
//                $pdf->Cell(15, 6, utf8_decode('TIPO'), 1, 0, 'C', 1);
//                $pdf->Cell(25, 6, utf8_decode('ADELANTO'), 1, 0, 'C', 1);
//                $pdf->Cell(26, 6, utf8_decode('TOTAL'), 1, 0, 'C', 1);
//                $pdf->Cell(26, 6, utf8_decode('ABONOS'), 1, 0, 'C', 1);
//                $pdf->Cell(26, 6, utf8_decode('SALDO'), 1, 1, 'C', 1);
//                while ($row1 = pg_fetch_row($sql1)) {
//                    $pdf->SetFont('helvetica', '', 8.5);
//                    $pdf->Cell(30, 6, utf8_decode($row1[0]), 0, 0, 'C', 0);
//                    $pdf->Cell(22, 6, utf8_decode($row1[2]), 0, 0, 'C', 0);
//                    $pdf->Cell(25, 6, utf8_decode($row1[3]), 0, 0, 'C', 0);
//                    $pdf->Cell(15, 6, utf8_decode($row1[4]), 0, 0, 'C', 0);
//                    $pdf->Cell(15, 6, ($row1[1]), 0, 0, 'C', 0);
//                    $pdf->Cell(25, 6, number_format($row1[5], 2, ',', '.'), 0, 0, 'R', 0);
//                    $pdf->Cell(26, 6, number_format($row1[7], 2, ',', '.'), 0, 0, 'R', 0);
//                    $pdf->Cell(26, 6, number_format($row1[8], 2, ',', '.'), 0, 0, 'R', 0);
//                    $pdf->Cell(26, 6, number_format($row1[9], 2, ',', '.'), 0, 1, 'R', 0);
//                    $subta += $row1[5];
//                    $subto += $row1[7];
//                    $subabo += $row1[8];
//                    $subsa += $row1[9];
//                }
//                $pdf->SetFont('helvetica', 'B', 9);
//                $pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
//                $pdf->Cell(106, 6, utf8_decode("Total Proveedor:"), 0, 0, 'R', 0);
//                $pdf->Cell(26, 6, maxCaracter((number_format($subta, 2, ',', '.')), 20), 0, 0, 'R', 0);
//                $pdf->Cell(26, 6, maxCaracter((number_format($subto, 2, ',', '.')), 20), 0, 0, 'R', 0);
//                $pdf->Cell(26, 6, maxCaracter((number_format($subabo, 2, ',', '.')), 20), 0, 0, 'R', 0);
//                $pdf->Cell(26, 6, maxCaracter((number_format($subsa, 2, ',', '.')), 20), 0, 1, 'R', 0);
//                $pdf->Ln(2);
//                $totala += $subta;
//                $total += $subto;
//                $abonos += $subabo;
//                $saldo += $subsa;
//            }
//        }
//    } else {
//        while ($row = pg_fetch_row($sql)) {
$sub = 0;
$subta = 0;
$subto = 0;
$subabo = 0;
$subsa = 0;
/* $sql1 = pg_query(
  "SELECT num_serie, tipo_documento, fecha_credito, adelanto, meses, monto_credito, (monto_credito::numeric - saldo::numeric) as abonos, saldo, cp.estado
  FROM pagos_compra cp
  LEFT JOIN factura_compra fc on cp.id_factura_compra=fc.id_factura_compra
  WHERE cp.id_proveedor='$row[0]'
  AND cp.fecha_credito $query_fecha '$_GET[fin]'
  ORDER BY id_pagos_compra;"
  ); */
$sql1 = pg_query(
        "select  CC.id_anticipo_clientes,CC.comprobante, CC.fecha_actual, CC.monto,C.tipo_documento,C.identificacion,C.nombres_cli from anticipo_clientes CC, clientes C where CC.id_clientes = C.id_cliente and CC.id_anticipo_clientes='$_GET[id]'"
);
while ($row1 = pg_fetch_row($sql1)) {
    $pdf->SetFillColor(216, 216, 231);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(70, 6, utf8_decode(strtoupper($row1[4]) . ": " . $row1[5]), 0, 0, 'L', true);
    $pdf->Cell(150, 6, utf8_decode("CLIENTE: " . maxCaracter($row1[6], 20)), 0, 1, 'L', true);
}
$pdf->Ln(1);
$pdf->SetFillColor(175, 215, 240);
$pdf->Cell(40, 6, utf8_decode('N°'), 1, 0, 'C', 1);
$pdf->Cell(30, 6, utf8_decode('N° ANTICIPO'), 1, 0, 'C', 1);
$pdf->Cell(35, 6, utf8_decode('FECHA ANTICIPO'), 1, 0, 'C', 1);
$pdf->Cell(35, 6, utf8_decode('FORMA PAGO'), 1, 0, 'C', 1);
$pdf->Cell(20, 6, utf8_decode('ANTICIPO'), 1, 1, 'C', 1);
$sql12 = pg_query(
        "select  CC.id_anticipo_clientes,CC.comprobante, CC.fecha_actual, CC.monto,C.tipo_documento,C.identificacion,C.nombres_cli,cc.forma_pago from anticipo_clientes CC, clientes C where CC.id_clientes = C.id_cliente and CC.id_anticipo_clientes='$_GET[id]'"
);
while ($row12 = pg_fetch_row($sql12)) {
    $pdf->SetFont('helvetica', '', 8.5);
    $pdf->Cell(40, 6, utf8_decode($row12[0]), 0, 0, 'C', 0);
    $pdf->Cell(30, 6, utf8_decode($row12[1]), 0, 0, 'C', 0);
    $pdf->Cell(35, 6, utf8_decode($row12[2]), 0, 0, 'C', 0);
    $pdf->Cell(35, 6, utf8_decode($row12[7]), 0, 0, 'C', 0);
    $pdf->Cell(20, 6, utf8_decode($row12[3]), 0, 0, 'C', 0);

    $subta += $row12[3];
}
$pdf->Ln(5);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
$pdf->Cell(106, 6, utf8_decode("Total Anticipo:"), 0, 0, 'R', 0);

$pdf->Cell(26, 6, maxCaracter((number_format($subta, 2, ',', '.')), 20), 0, 1, 'R', 0);

$totala += $subta;
$total += $subto;
$abonos += $subabo;
$saldo += $subsa;
//            }
//        }
//    }
//    $pdf->SetFont('helvetica', 'B', 9);
//    $pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
//    $pdf->Cell(106, 6, utf8_decode("Total:"), 0, 0, 'R', 0);
//    $pdf->Cell(26, 6, maxCaracter((number_format($totala, 2, ',', '.')), 20), 0, 0, 'R', 0);
//    $pdf->Cell(26, 6, maxCaracter((number_format($total, 2, ',', '.')), 20), 0, 0, 'R', 0);
//    $pdf->Cell(26, 6, maxCaracter((number_format($abonos, 2, ',', '.')), 20), 0, 0, 'R', 0);
//    $pdf->Cell(26, 6, maxCaracter((number_format($saldo, 2, ',', '.')), 20), 0, 1, 'R', 0);
//}
$pdf->Ln(20);
$pdf->SetX(7);
$pdf->Cell(5, 0, "", 0, 0, 'R', 0);
$pdf->SetX(7);
$pdf->Cell(40, 0, utf8_decode(''), 1, 1, 'R', 1);
$pdf->Ln(4);
$pdf->SetX(7);
$pdf->Cell(40, 0, utf8_decode('Recibí Conforme:'), 0, 0, 'C', 0);




$pdf->Output();
