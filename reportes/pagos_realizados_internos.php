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

    //////
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
        $this->Cell(210, 5, utf8_decode("PAGOS REALIZADOS INTERNOS"), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->Ln(2);
        /*  $this->SetX(12);
        $this->SetFillColor(175, 215, 240);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(25, 6, utf8_decode('Comprobante'), 1, 0, 'C', 1);
        $this->Cell(50, 6, utf8_decode('Fecha y Hora de Pago'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('Total'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('Valor Pago'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('Saldo'), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode('Tipo Pago'), 1, 0, 'C', 1);
        $this->Cell(10, 6, utf8_decode('C/G'), 1, 1, 'C', 1); */
        $this->SetFont('Amble-Regular', '', 10);
        $this->Ln(2);
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
$query_punto = "";
if ($_GET['id_empre'] != '0') {
    $query_punto = "AND fc.id_empresa='$_GET[id_empre]'";
}

$id_usuario_cp = "";
if ($_GET['id'] != '0') {
    $id_usuario_cp = "and pc.id_usuario='$_GET[id]'";
}




$pdf = new PDF('P', 'mm', 'a4');
$pdf->SetTitle('Pagos Internos');
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Amble-Regular', '', 9);

$inicio = $_GET['inicio'];
$fin = $_GET['fin'];
$query_fecha = "";
// RANGO DE FECHAS O FECHA ACTUAL
if ($pdf->rango) {
    $query_fecha = "BETWEEN '$inicio' AND";
} else {
    $query_fecha = "=";
}

$sqlproveedor="";
if(!empty($_GET["id_proveedor"])){
    $sqlproveedor=" where id_proveedor=".$_GET["id_proveedor"];
}else{
    $sqlproveedor=" where id_proveedor in(
        select id_proveedor from pagos_compra
        )";
}

$sql = "
select*from proveedores
$sqlproveedor";

$res = pg_query($sql);
$rows = pg_fetch_all($res);

if (!empty($rows)) {
    foreach ($rows as $row) {
        $pdf->SetLineWidth(.5);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetFillColor(158, 158, 158);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(80, 6, utf8_decode("PROVEEDOR: " . maxCaracter($row["empresa_pro"], 20)), 0, 0, 'L', true);
        $pdf->Cell(50, 6, utf8_decode(strtoupper($row["tipo_documento"]) . ": " . $row["identificacion_pro"]), 0, 0, 'L', true);
        $pdf->Cell(80, 6, utf8_decode("REPRESENTANTE: " . maxCaracter($row["representante_legal"], 20)), 0, 1, 'L', true);
        $pdf->Line($pdf->GetX(), $pdf->GetY(), 210, $pdf->GetY());
        $pdf->SetLineWidth(.02);
        $sql = "(
            select
            fc.id_factura_compra,
            fc.num_serie,
            fc.fecha_emision,
            pc.monto_credito,
            'C'::text tipo
            from factura_compra fc
            inner join pagos_compra pc
            on fc.id_factura_compra=pc.id_factura_compra
            where pc.id_proveedor=$row[id_proveedor] and pc.comprao_gasto='C'
            AND fc.fecha_emision $query_fecha '$_GET[fin]' 
            $query_punto $id_usuario_cp
            )
            union all
            (
            select
            fc.id_gastos,
            fc.num_serie,
            fc.fecha_emision,
            pc.monto_credito,
            'G'::text tipo
            from gastos fc
            inner join pagos_compra pc
            on fc.id_gastos=pc.id_factura_compra
            where pc.id_proveedor=$row[id_proveedor] and pc.comprao_gasto='G'
            AND fc.fecha_emision $query_fecha '$_GET[fin]' 
            $query_punto $id_usuario_cp
            )
            order by fecha_emision asc;
        ";
        $res = pg_query($sql);
        $rows1 = pg_fetch_all($res);
        $pdf->Ln(1);
        if (!empty($rows1)) {
            foreach ($rows1 as $row1) {
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->SetFillColor(255, 255, 255);
                $pdf->Cell(5, 6, utf8_decode(''), 0, 0, 'C', 1);
                $pdf->SetFillColor(189, 189, 189);
                $pdf->Cell(65, 6, utf8_decode("Nº DOCUMENTO: " . $row1["num_serie"]), 0, 0, 'L', true);
                $pdf->Cell(60, 6, utf8_decode("FECHA DE EMISIÓN: " . $row1["fecha_emision"]), 0, 0, 'L', true);
                $pdf->Cell(55, 6, utf8_decode("MONTO CRÉDITO: " . number_format($row1["monto_credito"],2,",",".")), 0, 0, 'L', true);
                if ($row1["tipo"] == "C") {
                    $pdf->Cell(20, 6, utf8_decode("COMPRA"), 0, 1, 'L', true);
                } elseif ($row1["tipo"] == "G") {
                    $pdf->Cell(20, 6, utf8_decode("GASTO"), 0, 1, 'L', true);
                }
                $pdf->SetFillColor(255, 255, 255);
                $pdf->Cell(5, 6, utf8_decode(''), 0, 0, 'C', 1);

                $pdf->Ln(1);
                $pdf->SetFillColor(255, 255, 255);
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(5, 4, utf8_decode(''), 0, 0, 'C', 1);
                $pdf->SetFillColor(175, 215, 240);
                $pdf->Cell(37, 4, utf8_decode('COMPROBANTE'), 1, 0, 'C', 1);
                $pdf->Cell(42, 4, utf8_decode('FECHA PAGO'), 1, 0, 'C', 1);
                //$pdf->Cell(42, 4, utf8_decode('TOTAL'), 1, 0, 'C', 1);
                $pdf->Cell(42, 4, utf8_decode('VALOR PAGO'), 1, 0, 'C', 1);
                $pdf->Cell(42, 4, utf8_decode('SALDO'), 1, 0, 'C', 1);
                $pdf->Cell(37, 4, utf8_decode('FORMA PAGO'), 1, 0, 'C', 1);
                $pdf->SetFillColor(255, 255, 255);
                $pdf->Cell(5, 4, utf8_decode(''), 0, 1, 'C', 1);

                $sql = "
                select*from pagos_pagar
                where id_proveedor=$row[id_proveedor]
                and id_factura_compra=$row1[id_factura_compra]
                and comprao_gasto='$row1[tipo]'";
                $res = pg_query($sql);
                $rows2 = pg_fetch_all($res);
                $pdf->Ln(2);
                $tvalorp=0;
                $tsaldo=0;
                //$total=0;
                if (!empty($rows2)) {
                    foreach ($rows2 as $row2) {
                        $pdf->SetFont('Arial', '', 9);
                        $pdf->SetFillColor(255, 255, 255);
                        $pdf->Cell(5, 6, utf8_decode(''), 0, 0, 'C', 1);
                        $pdf->Cell(37, 6, utf8_decode($row2["comprobante"]), 0, 0, 'C', 1);
                        $pdf->Cell(42, 6, utf8_decode($row2["fecha_actual"]), 0, 0, 'C', 1);
                        //$pdf->Cell(42, 6, utf8_decode($row2["total_factura"]), 0, 0, 'R', 1);
                        $pdf->Cell(42, 6, number_format($row2["valor_pagado"],2,",","."), 0, 0, 'R', 1);
                        $pdf->Cell(42, 6, number_format($row2["saldo_factura"],2,",","."), 0, 0, 'R', 1);
                        $pdf->Cell(37, 6, utf8_decode($row2["forma_pago"]), 0, 0, 'C', 1);
                        $pdf->Cell(5, 6, utf8_decode(''), 0, 1, 'C', 1);
                        $tvalorp+=$row2["valor_pagado"];
                        $tsaldo=$row2["saldo_factura"];
                        //$total=$row2["total_factura"];
                    }
                } else {
                    $pdf->SetFont('Arial', '', 9);
                    $pdf->SetFillColor(255, 255, 255);
                    $pdf->Cell(210, 6, utf8_decode("No tiene pagos registrados"), 0, 1, 'C', 1);
                }
                $pdf->Line($pdf->GetX(), $pdf->GetY(), 210, $pdf->GetY());
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Ln(1);
                $pdf->Cell(84, 6, utf8_decode("TOTAL:"), 0, 0, 'R', 1);
                //$pdf->Cell(35, 6, number_format($total,2,",","."), 0, 0, 'R', 1);
                $pdf->Cell(42, 6, number_format($tvalorp,2,",","."), 0, 0, 'R', 1);
                $pdf->Cell(42, 6, number_format($tsaldo,2,",","."), 0, 1, 'R', 1);
                //$pdf->Ln(1);
            }
        }else {
            $pdf->SetFont('Arial', '', 9);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Cell(210, 6, utf8_decode("No tiene documentos registrados"), 0, 1, 'C', 1);
        }
        $pdf->Ln(4);
    }
}
$pdf->Output();
