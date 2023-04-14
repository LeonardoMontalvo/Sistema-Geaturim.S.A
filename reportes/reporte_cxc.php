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
    var $nroRecibo;

    function SetWidths($w) {
        $this->widths = $w;
    }

    function SetNroRecibo($nr) {
        $this->nroRecibo = $nr;
    }

    function Header() {
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
        $this->Image('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 10, 7, 15, 15);
        $this->Image('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 180, 7, 15, 15);
        $this->SetFont('Arial', '', 10);
        $this->Cell(210, 5, "PROPIETARIO: " . utf8_decode($_SESSION['propietario']), 0, 1, 'C', 0);
        $this->Cell(105, 5, "TEL.: " . utf8_decode($_SESSION['telefono']), 0, 0, 'C', 0);
        $this->Cell(105, 5, "CEL.: " . utf8_decode($_SESSION['celular']), 0, 1, 'C', 0);
        $this->Cell(210, 5, "DIR.: " . utf8_decode($_SESSION['direccion']), 0, 1, 'C', 0);
        $this->Cell(210, 5, "SLOGAN.: " . utf8_decode($_SESSION['slogan']), 0, 1, 'C', 0);
        $this->Cell(210, 5, utf8_decode($_SESSION['pais_ciudad']), 0, 1, 'C', 0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);
        $this->Line(0, 48, 210, 48);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(210, 5, utf8_decode("COMPROBANTE DE INGRESO"), 0, 1, 'C', 0);
        $this->Cell(210, 5, utf8_decode("COMPROBANTE Nº: " . str_pad($this->nroRecibo, 8, '0', STR_PAD_LEFT)), 0, 1, 'R', 0);
        $this->SetFont('Amble-Regular', '', 10);
        $this->Ln(3);
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
$pdf->SetNroRecibo($_GET["comprobante"]);
$pdf->SetTitle('Recibo Cobro');
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Amble-Regular', '', 9);

$saldo = 0;
$valorPago = 0;
$total = 0;
$sub = 0;
$desc = 0;
$ivaT = 0;
$repetido = 0;
if ($_GET['tipo_pago'] == "EXTERNA") {

    $sql = pg_query(
            "SELECT 'EXT->'||comprobante, T.descripcion, num_factura, total, total::numeric-saldo::numeric, saldo, fecha_actual 
        from c_cobrarexternas C left join tipo_comprobante T on C.tipo_documento=T.id_tipo_comprobante,clientes,empresa  
        where C.id_cliente=clientes.id_cliente and C.id_empresa=empresa.id_empresa and C.num_factura='$_GET[id]'"
    );
    while ($row = pg_fetch_row($sql)) {
        // $pdf->SetX(1);
        // $pdf->SetFillColor(187, 179, 180);
        // $pdf->Cell(50, 6, maxCaracter(utf8_decode(strtoupper($row[17]) . ': ' . $row[18]), 35), 1, 0, 'L', 1);
        // $pdf->Cell(80, 6, maxCaracter(utf8_decode('NOMBRES:' . $row[19]), 35), 1, 0, 'L', 1);
        // $pdf->Cell(75, 6, maxCaracter(utf8_decode('SECCIÓN:' . $row[20]), 50), 1, 1, 'L', 1);
        // $pdf->Ln(3);

        $pdf->SetX(1);
        $pdf->Cell(30, 6, utf8_decode('Comprobante'), 1, 0, 'C', 0);
        $pdf->Cell(30, 6, utf8_decode('Tipo Documento'), 1, 0, 'C', 0);
        $pdf->Cell(50, 6, utf8_decode('Nro. Factura'), 1, 0, 'C', 0);
        $pdf->Cell(25, 6, utf8_decode('Total'), 1, 0, 'C', 0);
        $pdf->Cell(25, 6, utf8_decode('Pago Abono'), 1, 0, 'C', 0);
        $pdf->Cell(20, 6, utf8_decode('Saldo'), 1, 0, 'C', 0);
        $pdf->Cell(25, 6, utf8_decode('Fecha Pago'), 1, 1, 'C', 0);
        $pdf->Cell(30, 6, utf8_decode($row[0]), 0, 0, 'C', 0);
        $pdf->Cell(30, 6, utf8_decode($row[1]), 0, 0, 'C', 0);
        $pdf->Cell(50, 6, utf8_decode($row[2]), 0, 0, 'C', 0);
        $pdf->Cell(25, 6, number_format($row[3], 2, '.', ''), 0, 0, 'C', 0);
        $pdf->Cell(25, 6, utf8_decode($row[4]), 0, 0, 'C', 0);
        $pdf->Cell(20, 6, utf8_decode($row[5]), 0, 0, 'C', 0);
        $pdf->Cell(25, 6, utf8_decode($row[6]), 0, 1, 'C', 0);
        $saldo = $row[3];
    }
    $pdf->Ln(2);
    $pdf->Cell(205, 0, utf8_decode(''), 1, 1, 'R', 0);
    $pdf->Cell(187, 6, utf8_decode('Total Saldo'), 0, 0, 'R', 0);
    $pdf->Cell(20, 6, (number_format($saldo, 2, ',', '.')), 0, 0, 'C', 0);
} else {
    $sql = pg_query("select * from pagos_cobrar where comprobante='$_GET[comprobante]'");

    while ($row = pg_fetch_row($sql)) {
        if ($row[9] == 'Nota') {
            $saldo_t = 0;
            $id_f = 0;
            $sql = pg_query("select * from facturas_novalidas,clientes,empresa where facturas_novalidas.id_cliente=clientes.id_cliente and facturas_novalidas.id_empresa=empresa.id_empresa and facturas_novalidas.comprobante='$_GET[id]';");
            while ($row = pg_fetch_row($sql)) {
                // $pdf->SetX(1);
                // $pdf->SetFillColor(187, 179, 180);
                // $pdf->Cell(200, 6, maxCaracter(utf8_decode(strtoupper($row[17]) . ': ' . $row[18]), 80), 1, 1, 'L', 1);
                $pdf->Ln(3);
                $pdf->SetX(1);
                $pdf->Cell(22, 6, utf8_decode('Comprobante'), 1, 0, 'C', 0);
                $pdf->Cell(26, 6, utf8_decode('Tipo Documento'), 1, 0, 'C', 0);
                $pdf->Cell(50, 6, utf8_decode('Nro. Factura'), 1, 0, 'C', 0);
                $pdf->Cell(20, 6, utf8_decode('Total'), 1, 0, 'C', 0);
                $pdf->Cell(20, 6, utf8_decode('Abono'), 1, 0, 'C', 0);
                $pdf->Cell(20, 6, utf8_decode('Valor Pago'), 1, 0, 'C', 0);
                $pdf->Cell(20, 6, utf8_decode('Saldo'), 1, 0, 'C', 0);
                $pdf->Cell(20, 6, utf8_decode('Fecha Pago'), 1, 1, 'C', 0);
                $id_f = $row[0];
                $repetido = 1;
                $contador = 1;
            }
        } else {
            //CORREGIDO VARIAS FACTURAS
            $saldo_t = 0;
            $id_f = 0;
            $sql = pg_query("select * from factura_venta,clientes,empresa where factura_venta.id_cliente=clientes.id_cliente and factura_venta.id_empresa=empresa.id_empresa and num_factura='$_GET[id]';");
            while ($row = pg_fetch_row($sql)) {
                $pdf->SetX(1);
                $pdf->SetFillColor(187, 179, 180);
                $pdf->Cell(50, 6, maxCaracter(utf8_decode(strtoupper($row[40]) . ': ' . $row[41]), 35), 1, 0, 'L', 1);
                $pdf->Cell(148, 6, maxCaracter(utf8_decode('NOMBRE: ' . $row[42]), 35), 1, 1, 'L', 1);
                $pdf->Ln(3);
                $pdf->SetX(1);
                $pdf->Cell(22, 6, utf8_decode('Comprobante'), 1, 0, 'C', 0);
                $pdf->Cell(26, 6, utf8_decode('Tipo Documento'), 1, 0, 'C', 0);
                $pdf->Cell(50, 6, utf8_decode('Nro. Factura'), 1, 0, 'C', 0);
                $pdf->Cell(20, 6, utf8_decode('Total'), 1, 0, 'C', 0);
                $pdf->Cell(20, 6, utf8_decode('Abono'), 1, 0, 'C', 0);
                $pdf->Cell(20, 6, utf8_decode('Valor Pago'), 1, 0, 'C', 0);
                $pdf->Cell(20, 6, utf8_decode('Saldo'), 1, 0, 'C', 0);
                $pdf->Cell(20, 6, utf8_decode('Fecha Pago'), 1, 1, 'C', 0);
                $id_f = $row[0];
                $repetido = 1;
                $contador = 1;
            }
        }
    }

    $fec = "";
    $sql = pg_query("select fecha_actual from pagos_cobrar where comprobante='$_GET[comprobante]'");
    while ($row = pg_fetch_row($sql)) {
        $fec = $row[0];
    }
    $sql = pg_query("select * from pagos_cobrar where comprobante='$_GET[comprobante]'");
    $meses = 0;
    $des = "";
    $id_pv = 0;
    $fac = "";
    $adel = "";
    $tipo_nota = "";

    while ($row = pg_fetch_row($sql)) {
        $meses = $row[6];
        $id_pv = $row[0];

        if ($row[9] == 'Nota') {
            $sql2 = pg_query("select id_facturas_novalidas from facturas_novalidas where comprobante='$row[3]';");
            while ($row2 = pg_fetch_row($sql2)) {
                $fac = $row2[0];
            }
            /*  $sql3 = pg_query("select adelanto from pagos_venta where id_factura_venta='$fac';");
              while ($row3 = pg_fetch_row($sql3)) {
              $adel = $row3[0];
              } */
            $sql2 = pg_query("select id_factura_venta from factura_venta where num_factura='$row[8]';");
            while ($row2 = pg_fetch_row($sql2)) {
                $fac = $row2[0];
            }
            /*  $sql3 = pg_query("select adelanto from pagos_venta where id_factura_venta='$fac';");
              while ($row3 = pg_fetch_row($sql3)) {
              $adel = $row3[0];
              } */
        }
        $pdf->Cell(22, 6, utf8_decode($row[3]), 0, 0, 'C', 0);
        $pdf->Cell(26, 6, utf8_decode($row[9]), 0, 0, 'C', 0);
        $pdf->Cell(50, 6, utf8_decode($row[8]), 0, 0, 'C', 0);
        $pdf->Cell(20, 6, utf8_decode($row[11]), 0, 0, 'C', 0);
        $pdf->Cell(20, 6, utf8_decode($adel), 0, 0, 'C', 0);
        $pdf->Cell(20, 6, utf8_decode($row[12]), 0, 0, 'C', 0);
        $pdf->Cell(20, 6, utf8_decode($row[13]), 0, 0, 'C', 0);
        $pdf->Cell(20, 6, utf8_decode($row[4]), 0, 1, 'C', 0);
        $valorPago = $valorPago + number_format($row[12], 2, '.', '');
    }
    $pdf->Ln(2);
    $pdf->Cell(205, 0, utf8_decode(''), 1, 1, 'R', 0);
    $pdf->Cell(138, 6, utf8_decode('Total Ingreso'), 0, 0, 'R', 0);
    $pdf->Cell(20, 6, (number_format($valorPago, 2, ',', '.')), 0, 0, 'C', 0);
}

$pdf->Ln(20);
$pdf->SetFont('Arial', 'b', 9);
$pdf->Cell(5, 06, utf8_decode(""), 0, 0, 'C');
$pdf->Cell(95, 06, utf8_decode("Recibí conforme"), "T", 0, 'C');
$pdf->Cell(10, 06, utf8_decode(""), 0, 0, 'C');
$pdf->Cell(95, 06, utf8_decode("Entregé conforme"), "T", 0, 'C');
$pdf->Cell(5, 06, utf8_decode(""), 0, 1, 'C');

$pdf->Output();
