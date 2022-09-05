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
        $this->Cell(210, 8, $_SESSION['nombre_empresa'], 0, 1, 'C', 0);
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
        $this->Cell(210, 5, utf8_decode("TRANSACCIONES DE LA CUENTA CONTABLE"), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->mostrarCuenta();
        $this->Ln(8);
        $this->SetFillColor(175, 215, 240);
        $this->SetX(1);
        $this->Cell(25, 6, utf8_decode('Fecha'), 1, 0, 'C', 1);
        $this->Cell(15, 6, utf8_decode('Nro.D.'), 1, 0, 'C', 1);
        $this->Cell(118, 6, utf8_decode('Concepto'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('Debe'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('Haber'), 1, 1, 'C', 1);
        $this->SetFillColor(255, 255, 225);
        $this->SetLineWidth(0.2);
    }
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
    function mostrarCuenta(){
        $sql="
        select
        *
        from plan_cuentas
        where id_plan_cuentas=$_GET[cod]
        ";
        $res=pg_query($sql);
        $rows=pg_fetch_all($res);
        $this->Cell(210,6,utf8_decode("MAYOR DE ".$rows[0]["descripcion"]),0,0,"C");
    }
}

$pdf = new PDF('P', 'mm', 'a4');
$pdf->SetTitle('Transacciones por Cuenta');
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Amble-Regular', '', 9);

$query_fecha = "";
// RANGO DE FECHAS O FECHA ACTUAL
if ($pdf->rango) {
    $query_fecha = "BETWEEN '$_GET[inicio]' AND";
} else {
    $query_fecha = "=";
}
$query_punto="";

if ($_GET['id_empre'] != '0') {
    $query_punto = "AND t.id_empresa='$_GET[id_empre]'";
}
$conpunto = 1;
$consultapunto = pg_query("select max(id_punto_venta_empresa) from punto_venta_empresa where punto_venta_empresa.id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($consultapunto)) {
    $conpunto = $row[0];
}
$conpuntoresult = 1;
$consultapuntoresult = pg_query("select id_punto_venta from punto_venta_empresa where id_punto_venta_empresa='$conpunto'");
while ($row = pg_fetch_row($consultapuntoresult)) {
    $conpuntoresult = $row[0];
}
//$pdf->SetFont('Arial','',9);
$sql1 = pg_query(
    "SELECT 
    t.fecha_registro, 
    t.concepto, 
    dt.debito, 
    dt.credito ,
    t.id_transacciones 
    from transacciones t, 
    detalle_transaccion dt 
    WHERE dt.id_transacciones=t.id_transacciones 
    and t.fecha_registro $query_fecha '$_GET[fin]' 
    and t.estado='Activo' 
    and dt.id_plan_cuentas='$_GET[cod]'  $query_punto order by t.fecha_registro asc, t.id_transacciones"
);
if (pg_num_rows($sql1)) {
    $total = 0;
    $sub = 0;
    $debe = 0;
    $haber = 0;
    $saldo = 0;
    while ($row1 = pg_fetch_row($sql1)) {
        $debe = $debe + $row1[2];
        $haber = $haber + $row1[3];
        $pdf->SetX(1);
        $pdf->Cell(25, 6, utf8_decode($row1[0]), 0, 0, 'C', false);
        $pdf->Cell(15, 6, utf8_decode($row1[4]), 0, 0, 'C', false);
        $pdf->SetFont('Amble-Regular', '', 8);
        $pdf->Cell(118, 6, utf8_decode(maxCaracter($row1[1], 75)), 0, 0, 'L', false);
        $pdf->SetFont('Amble-Regular', '', 9);
        $pdf->Cell(25, 6, number_format($row1[2], 2, ',', '.'), 0, 0, 'R', false);
        $pdf->Cell(25, 6, number_format($row1[3], 2, ',', '.'), 0, 1, 'R', false);
    }
    $pdf->Ln(2);
    $pdf->Cell(210, 0, utf8_decode(''), 1, 1, 'R', 0);
    $pdf->Cell(159, 6, utf8_decode('Total:'), 0, 0, 'R', 0);
    $pdf->Cell(25, 6, (number_format($debe, 2, ',', '.')), 0, 0, 'R', 0);
    $pdf->Cell(25, 6, (number_format($haber, 2, ',', '.')), 0, 1, 'R', 0);
}
$pdf->Output();
