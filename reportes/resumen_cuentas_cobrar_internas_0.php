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
        $this->rango = false;
        if ($_GET['inicio'] != '') {
            $this->rango = true;
        }
        $this->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->AddFont('helvetica', 'B', 'helveticab.php');
        $this->SetFont('Amble-Regular', '', 10);
        $this->fecha = date('Y-m-d', time());
        $this->SetX(0);
        $this->SetY(0);
        $this->Cell(105, 5, $this->fecha, 0, 0, 'C', 0);
        $this->Cell(105, 5, "CARTERA CxC", 0, 1, 'C', 0);
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
        $this->Line(0, 25, 210, 25);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(210, 5, utf8_decode("RESUMEN CUENTAS INTERNAS"), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->Ln(3);
        /* $this->SetFillColor(175, 215, 240);
        $this->Cell(25, 6, utf8_decode('No Factura'), 1, 0, 'C', 1);
        $this->Cell(22, 6, utf8_decode('Emisión'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('Vencimiento'), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode('Días Plazo'), 1, 0, 'C', 1);
        $this->Cell(21, 6, utf8_decode('Días Vence'), 1, 0, 'C', 1);
        $this->Cell(22, 6, utf8_decode('Adelanto'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('Total Venta'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('Abonos'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('Saldo'), 1, 1, 'C', 1);
        $this->SetFillColor(255, 255, 225); */
        $this->SetLineWidth(0.2);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

}

$pdf = new PDF('P', 'mm', 'a4');
$pdf->SetTitle('Cuentas por Cobrar');
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Amble-Regular', '', 9);

$consulta = pg_query(
        'SELECT id_cliente, identificacion, nombres_cli from clientes order by id_cliente asc;'
);

if (pg_num_rows($consulta)) {
    $adelantos = 0;
    $totalf = 0;
    $totala = 0;
    $saldos = 0;

    // RANGO DE FECHAS O FECHA ACTUAL
    $query_fecha = "=";
    if ($pdf->rango) {
        $query_fecha = "BETWEEN '$_GET[inicio]' AND";
    }
    $query_punto = "";
    if ($_GET['id_empre'] != '0') {
        $query_punto = "AND fv.id_empresa='$_GET[id_empre]'";
    }

    $id_usuario_fv = "";
    if ($_GET['id'] != '0') {
        $id_usuario_fv = "and fv.id_usuario='$_GET[id]'";
    }
    while ($row = pg_fetch_assoc($consulta)) {
        // $sql1 = pg_query(
        //     "SELECT DISTINCT ON (num_factura) sum (total_factura)FROM pagos_cobrar
        //     WHERE pagos_cobrar.id_cliente=$row[id_cliente] AND fecha_actual  $query_fecha '$_GET[fin]' 
        //     AND pagos_cobrar.estado='Activo' and pagos_cobrar.id_usuario='$_GET[id]'  GROUP BY pagos_cobrar.id_pagos_cobrar;"
        // );
        // $subt = 0;
        // while ($row2 = pg_fetch_row($sql1)) {
        //     $subt += $row2[0];
        // }
        $sql = pg_query(
                "SELECT num_factura, fecha_actual, fecha_dias, (fecha_dias::date - fecha_actual::date) as dias, (fecha_dias::date - '$pdf->fecha'::date) as vence, 
            adelanto, monto_credito, pv.saldo, (monto_credito::numeric-saldo::numeric) as abonos , pv.tipo_documento
            FROM factura_venta fv inner join clientes c using(id_cliente) inner join pagos_venta pv using(id_factura_venta)
            where c.id_cliente=$row[id_cliente] AND fv.fecha_actual $query_fecha '$_GET[fin]' 
            $id_usuario_fv    $query_punto order by num_factura;"
        );

        if (pg_num_rows($sql)) {
            $suba = 0;
            $subtf = 0;
            $subta = 0;
            $subs = 0;
            $pdf->SetFillColor(220, 240, 210);
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->Cell(75, 6, maxCaracter(utf8_decode('RUC/CI:' . $row['identificacion']), 35), 0, 0, 'C', 1);
            $pdf->Cell(135, 6, maxCaracter(utf8_decode('NOMBRES:' . $row['nombres_cli']), 50), 0, 1, 'C', 1);
            $pdf->Ln(1);
            $pdf->SetFillColor(175, 215, 240);
            $pdf->Cell(30, 6, utf8_decode('N° DOCUMENTO'), 1, 0, 'C', 1);
            $pdf->Cell(22, 6, utf8_decode('EMISIÓN'), 1, 0, 'C', 1);
            $pdf->Cell(25, 6, utf8_decode('VENCIMIENTO'), 1, 0, 'C', 1);
            $pdf->Cell(30, 6, utf8_decode('CADUCA (DÍAS)'), 1, 0, 'C', 1);
            //$pdf->Cell(15, 6, utf8_decode('DIAS'), 1, 0, 'C', 1);
            $pdf->Cell(25, 6, utf8_decode('TIPO DOC.'), 1, 0, 'C', 1);
            //$pdf->Cell(25, 6, utf8_decode('ADELANTO'), 1, 0, 'C', 1);
            $pdf->Cell(26, 6, utf8_decode('TOTAL'), 1, 0, 'C', 1);
            $pdf->Cell(26, 6, utf8_decode('ABONOS'), 1, 0, 'C', 1);
            $pdf->Cell(26, 6, utf8_decode('SALDO'), 1, 1, 'C', 1);

            while ($row = pg_fetch_assoc($sql)) {
                $pdf->SetFont('Helvetica', '', 9);
                $pdf->Cell(30, 6, utf8_decode($row['num_factura']), 0, 0, 'C', 0);
                $pdf->Cell(22, 6, utf8_decode($row['fecha_actual']), 0, 0, 'C', 0);
                $pdf->Cell(25, 6, utf8_decode($row['fecha_dias']), 0, 0, 'C', 0);
                //$pdf->Cell(20, 6, utf8_decode($row['dias']), 0, 0, 'C', 0);
                $pdf->Cell(30, 6, utf8_decode($row['vence']), 0, 0, 'C', 0);
                $pdf->Cell(26, 6, utf8_decode($row['tipo_documento']), 0, 0, 'C', 0);
                //$pdf->Cell(26, 6, utf8_decode(number_format($row['adelanto'], 2, ',', '.')), 0, 0, 'R', 0);
                $pdf->Cell(26, 6, utf8_decode(number_format($row['monto_credito'], 2, ',', '.')), 0, 0, 'R', 0);
                $pdf->Cell(26, 6, utf8_decode(number_format($row['abonos'], 2, ',', '.')), 0, 0, 'R', 0);
                $pdf->Cell(26, 6, utf8_decode(number_format($row['saldo'], 2, ',', '.')), 0, 1, 'R', 0);
                $suba += $row['adelanto'];
                $subtf += $row['monto_credito'];
                $subta += $row['abonos'];
                $subs += $row['saldo'];
            }
            $pdf->Cell(300, 0, utf8_decode(""), 1, 1, 'R', 0);
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->Cell(136, 6, utf8_decode("Total Cliente:"), 0, 0, 'R', 0);
            //$pdf->Cell(22, 6, maxCaracter((number_format($suba, 2, ',', '.')), 20), 0, 0, 'R', 0);
            $pdf->Cell(25, 6, maxCaracter((number_format($subtf, 2, ',', '.')), 20), 0, 0, 'R', 0);
            $pdf->Cell(25, 6, maxCaracter((number_format($subta, 2, ',', '.')), 20), 0, 0, 'R', 0);
            $pdf->Cell(25, 6, maxCaracter((number_format($subs, 2, ',', '.')), 20), 0, 1, 'R', 0);
            $adelantos += $suba;
            $totalf += $subtf;
            $totala += $subta;
            $saldos += $subs;
        }
    }
    $pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
    $pdf->SetFont('Helvetica', 'B', 9.5);
    $pdf->Cell(136, 6, utf8_decode("Totales:"), 0, 0, 'R', 0);
    //$pdf->Cell(22, 6, maxCaracter((number_format($adelantos, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(25, 6, maxCaracter((number_format($totalf, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(25, 6, maxCaracter((number_format($totala, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(25, 6, maxCaracter((number_format($saldos, 2, ',', '.')), 20), 0, 1, 'R', 0);
}
$pdf->Output();
