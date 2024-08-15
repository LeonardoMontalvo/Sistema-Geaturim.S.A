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
        $this->Cell(210, 5, utf8_decode("COBROS REALIZADOS EXTERNOS POR CLIENTE"), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->Ln(3);
       /*  $this->SetFillColor(175, 215, 240);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(30, 6, utf8_decode('Comprobante'), 1, 0, 'C', 1);
        $this->Cell(35, 6, utf8_decode('Tipo Documento'), 1, 0, 'C', 1);
        $this->Cell(40, 6, utf8_decode('Nro Factura'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('Total'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('Pago Abono'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('Saldo'), 1, 0, 'C', 1);
        $this->Cell(30, 6, utf8_decode('Fecha Pago'), 1, 1, 'C', 1);
        $this->SetFillColor(255, 255, 225); */
        $this->SetLineWidth(0.2);
    }
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDF('P', 'mm', 'a4');
$pdf->SetTitle('Cobros Externos');
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

$query_punto = "";
if ($_GET['id_empre'] != '0') {
    $query_punto = "AND id_empresa='$_GET[id_empre]'";
}

$id_usuario = "";

if ($_GET['id'] != '0') {
    $id_usuario = "and id_usuario='$_GET[id]'";
}

$sqlcliente = "";
if (!empty($_GET["id_cliente"])) {
    $sqlcliente = " where id_cliente=" . $_GET["id_cliente"];
} else 

if (!empty($_GET['id_ruta'])) {
    $sqlcliente = " where credito_cupo='" . $_GET['id_ruta'] . "' and id_cliente in(
        select id_cliente from c_cobrarexternas
        )";
} else

if (!empty($_GET['id_vendedor'])) {
    $sqlcliente = " where
    credito_cupo in (select id_ruta from rutas 
    where id_vendedor=" . $_GET['id_vendedor'] . ")
    and id_cliente in(
        select id_cliente from c_cobrarexternas
    )";
} else {
    $sqlcliente = " where id_cliente in(
        select id_cliente from c_cobrarexternas
        )";
}

$sql = "
select*from clientes
$sqlcliente";

$res = pg_query($sql);
$rows = pg_fetch_all($res);

if (!empty($rows)) {
    foreach ($rows as $row) {
        $pdf->SetLineWidth(.5);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetFillColor(158, 158, 158);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(80, 6, utf8_decode("CLIENTE: " . maxCaracter($row["nombres_cli"], 20)), 0, 0, 'L', true);
        $pdf->Cell(50, 6, utf8_decode(strtoupper($row["tipo_documento"]) . ": " . $row["identificacion"]), 0, 0, 'L', true);
        $pdf->Cell(80, 6, utf8_decode(''), 0, 1, 'L', true);
        $pdf->Line($pdf->GetX(), $pdf->GetY(), 210, $pdf->GetY());
        $pdf->SetLineWidth(.02);

        $sql="
        select 
        num_factura num_serie,
        fecha_emicion fecha_emision,
        total monto_credito,
        abreviatura
        from c_cobrarexternas
        inner join tipo_comprobante
        on id_tipo_comprobante=tipo_documento
        where id_cliente='$row[id_cliente]' 
        AND fecha_actual $query_fecha '$_GET[fin]' 
        $query_punto $id_usuario
        order by id_cliente asc
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
                $pdf->Cell(20, 6, utf8_decode("DOC: ".$row1["abreviatura"]), 0, 1, 'L', true);

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

                $sql="SELECT * 
                FROM pagos_cobrar p
                WHERE num_factura='$row1[num_serie]' AND 
                p.fecha_actual $query_fecha '$fin'   
                $query_punto $id_usuario
                ORDER BY comprobante ASC;";
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
