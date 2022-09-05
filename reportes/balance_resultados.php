<?php
require('../fpdf/fpdf.php');
include '../procesos/base.php';
include '../procesos/funciones.php';
conectarse();
date_default_timezone_set('America/Guayaquil');
session_start();

//setlocale(LC_ALL, "es_ES");
setlocale(LC_ALL,"es_ES@euro","es_ES","esp","es","esm","esn","spanish","spanish-modern","es-ES","es_LA","es-LA");

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
        $this->Cell(105, 5, "BALANCES", 0, 1, 'C', 0);
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
        $this->Line(0, 25, 210, 25);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(210, 5, utf8_decode("BALANCE DE RESULTADOS"), 0, 1, 'C', 0);

        $this->SetFont('Arial', 'B', 10);
        $this->Ln(1);
        $fechastr = mb_strtoupper(strftime("%d de %B de %Y", strtotime($_GET['fin'])));
        if ($this->rango) {
            //$this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            //$this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
            $this->Cell(210, 5, utf8_decode("AL ") . $fechastr, 0, 1, 'C', 0);
        } else {
            //$this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
            $this->Cell(210, 5, utf8_decode("AL ") . $fechastr, 0, 1, 'C', 0);
        }

        $this->Ln(3);
        $this->SetX(2);
        $this->SetFont('Helvetica', 'B', 9);
        $this->SetFillColor(175, 215, 240);
        $this->Cell(30, 6, utf8_decode('Código'), 1, 0, 'C', 1);
        $this->Cell(125, 6, utf8_decode('Cta. Contable'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('Deudor'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('Acreedor'), 1, 1, 'C', 1);
        $this->Ln(1);
        $this->SetFillColor(255, 255, 225);
        $this->SetLineWidth(0.2);
    }

    function GetCurrentWidth()
    {
        return $this->w - ($this->lMargin * 2);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDF('P', 'mm', 'a4');
$pdf->SetTitle('Balance de Resultados');
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Amble-Regular', '', 9);

$total = 0;
$sub = 0;
$contador = 0;
$debe = 0;
$haber = 0;
$saldo = 0;
$tot_debe = 0;
$tot_haber = 0;

$query_fecha = "";
// RANGO DE FECHAS O FECHA ACTUAL
if ($pdf->rango) {
    $query_fecha = "BETWEEN '$_GET[inicio]' AND";
} else {
    $query_fecha = "=";
}
$sumad = 0;
$sumah = 0;
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

$tdebito = 0;
$tcredito = 0;

$pdf->SetFont('Helvetica', 'B', 9);
$pdf->SetX(2);
$pdf->Cell(30, 6, "4.", 0, 0, 'L', false);
$pdf->Cell(125, 6, "INGRESOS", 0, 0, 'L', false);
$pdf->Ln(5);

mostrarValCuentas($_GET["inicio"], $_GET["fin"], 4, $conpuntoresult);

$pdf->SetFont('Helvetica', 'B', 9);
$pdf->SetX(2);
$pdf->Cell(30, 6, "5.", 0, 0, 'L', false);
$pdf->Cell(125, 6, "COSTOS Y GASTOS", 0, 0, 'L', false);
$pdf->Ln(5);

mostrarValCuentas($_GET["inicio"], $_GET["fin"], 5, $conpuntoresult);

/* $pdf->SetFont('Helvetica', 'B', 9);
$pdf->SetX(2);
$pdf->Cell(30, 6, "6", 0, 0, 'L', false);
$pdf->Cell(125, 6, "GASTOS", 0, 0, 'L', false);
$pdf->Ln(5);

mostrarValCuentas($_GET["inicio"], $_GET["fin"], 6, $conpuntoresult); */


$pdf->SetFont('Helvetica', 'B', 9);
$pdf->Ln(2);
$pdf->Cell(210, 0, utf8_decode(''), 1, 1, 'R', 0);
$pdf->Cell(182, 6, (number_format($tdebito, 2, ',', '.')), 0, 0, 'R', 0);
$pdf->Cell(25, 6, (number_format($tcredito, 2, ',', '.')), 0, 0, 'R', 0);
$pdf->Ln(5);
$pdf->Cell(210, 0, utf8_decode(''), 1, 1, 'R', 0);

$diferencia = $tcredito - $tdebito;
if ($diferencia >= 0) {
    $pdf->SetX(2);
    $pdf->Cell(30, 6, "3.6.01.03", 0, 0, 'L', false);
    $pdf->Cell(125, 6, "UTILIDAD EJERCICIO", 0, 0, 'L', false);
    $pdf->Cell(60, 6, (number_format($diferencia, 2, ',', '.')), 0, 0, 'C', 0);
    $pdf->Ln(5);
} else {
    $pdf->SetX(2);
    $pdf->Cell(30, 6, "3.6.01.03", 0, 0, 'L', false);
    $pdf->Cell(125, 6, utf8_decode("UTILIDAD EJERCICIO (PÉRDIDA)"), 0, 0, 'L', false);
    $pdf->SetTextColor(255, 0, 0);
    $pdf->Cell(60, 6, (number_format($diferencia, 2, ',', '.')), 0, 0, 'C', 0);
    $pdf->Ln(5);
}

/*$pdf->Ln(10);
//$pdf->SetFont('Arial','',9);
$sql = pg_query("select id_plan_cuentas, 
codigo_plan, 
descripcion from 
plan_cuentas where cuenta='M' and
estado='Activo' and
(codigo_plan like'3%' or codigo_plan like '4%' 
or codigo_plan like '5%' 
or codigo_plan='1.1.3.1.01.' ) 
order by codigo_plan");

while ($row = pg_fetch_row($sql)) {
    $sql1 = pg_query("select t.fecha_actual,
     t.concepto,
     dt.debito,
     dt.credito from transacciones t,
     detalle_transaccion dt where dt.id_transacciones=t.id_transacciones and
    t.fecha_actual $query_fecha '$_GET[fin]' and
    t.estado='Activo' and
    dt.id_plan_cuentas=$row[0] 
    and t.id_empresa='$conpuntoresult' order by t.fecha_actual");
    $debe = 0;
    $haber = 0;

    while ($row1 = pg_fetch_row($sql1)) {
        $debe = $debe + $row1[2];
        $haber = $haber + $row1[3];
        $sumad = $sumad + $debe;
        $sumah = $sumah + $haber;
    }
    $tot_debe = $tot_debe + $debe;
    $tot_haber = $tot_haber + $haber;
    if ($debe != 0 || $haber != 0) {
        $pdf->SetX(2);
        $pdf->Cell(30, 6, utf8_decode($row[1]), 0, 0, 'L', false);
        $pdf->SetFont('Amble-Regular', '', 8);
        $pdf->Cell(125, 6, utf8_decode($row[2]), 0, 0, 'L', false);
        $pdf->SetFont('Amble-Regular', '', 9);
        $pdf->Cell(25, 6, number_format($debe, 2, '.', ''), 0, 0, 'R', false);
        $pdf->Cell(25, 6, number_format($haber, 2, '.', ''), 0, 0, 'R', false);
        $pdf->Ln(5);
    }
}

$pdf->SetFont('Helvetica', 'B', 9);
$pdf->Ln(2);
$pdf->Cell(210, 0, utf8_decode(''), 1, 1, 'R', 0);
$pdf->Cell(182, 6, (number_format($tot_debe, 2, ',', '.')), 0, 0, 'R', 0);
$pdf->Cell(25, 6, (number_format($tot_haber, 2, ',', '.')), 0, 0, 'R', 0);
$pdf->Ln(5);
$pdf->Cell(210, 0, utf8_decode(''), 1, 1, 'R', 0);*/



/* if (($tot_debe - $tot_haber) > 0) {
    $pdf->Cell(170, 6, utf8_decode('Utilidad:'), 0, 0, 'R', 0);
    $pdf->Cell(25, 6, (number_format($tot_debe - $tot_haber, 2, ',', '.')), 0, 0, 'R', 0);
} else {
    $pdf->Cell(170, 6, utf8_decode('Pérdida:'), 0, 0, 'R', 0);
    $pdf->Cell(25, 6, (number_format($tot_haber - $tot_debe, 2, ',', '.')), 0, 0, 'R', 0);
} */

$pdf->Output();


function obtenerValCuentas($finicio, $ffin, $codcuenta, $idpv)
{
    $sql = "
    select 
    dt.id_plan_cuentas,
    plc.codigo_plan,
    plc.descripcion,
    sum(round(dt.debito,2))debito,
    sum(round(dt.credito,2))credito
    from transacciones t,
    detalle_transaccion dt 
    inner join plan_cuentas plc
    on plc.id_plan_cuentas=dt.id_plan_cuentas
    where dt.id_transacciones=t.id_transacciones 
    and t.fecha_registro BETWEEN '$finicio' and '$ffin' 
    and t.estado='Activo' 
    and dt.id_plan_cuentas in (
    select id_plan_cuentas from 
    plan_cuentas 
    where 
    cuenta='M' and
    estado='Activo' 
    and codigo_plan like '$codcuenta%' 
    order by codigo_plan asc
    ) 
    and t.id_empresa='$idpv' 
    group by dt.id_plan_cuentas,
    plc.descripcion,
    plc.codigo_plan
    order by plc.codigo_plan asc;
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (!$rows) {
        return [];
    }
    return $rows;
}

function mostrarValCuentas($finicio, $ffin, $codcuenta, $idpv)
{
    global $pdf, $tdebito, $tcredito;

    $pdf->SetFont('Amble-Regular', '', 8);

    if (empty($finicio)) {
        $regs = obtenerValCuentas($ffin, $ffin, $codcuenta, $idpv);
    } else {
        $regs = obtenerValCuentas($finicio, $ffin, $codcuenta, $idpv);
    }

    foreach ($regs as $value) {
        $pdf->SetX(2);
        $pdf->Cell(30, 6, $value["codigo_plan"], 0, 0, 'L', false);
        $pdf->Cell(125, 6, $value["descripcion"], 0, 0, 'L', false);
        $pdf->Cell(25, 6, number_format($value["debito"], 2, '.', ''), 0, 0, 'R', false);
        $pdf->Cell(25, 6, number_format($value["credito"], 2, '.', ''), 0, 0, 'R', false);
        $pdf->Ln(5);
        $tdebito += $value["debito"];
        $tcredito += $value["credito"];
    }
}
