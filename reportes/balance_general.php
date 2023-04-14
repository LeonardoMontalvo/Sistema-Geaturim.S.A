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
        $this->Cell(105, 5, "BALANCES", 0, 1, 'C', 0);
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
        $this->Cell(210, 5, utf8_decode("ESTADO DE SITUACIÓN FINACIERA"), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->Ln(3);
        $this->SetX(5);
        $this->SetFont('Helvetica', 'B', 9);
        $this->SetFillColor(175, 215, 240);
        $this->Cell(125, 6, utf8_decode('Cta. Contable'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('Debe'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('Haber'), 1, 1, 'C', 1);
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

$pdf = new PDF('P', 'mm', 'a4');
$pdf->SetTitle('ESTADO DE SITUACIÓN FINACIERA');
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
$total = 0;
$sub = 0;
$contador = 0;
$debe = 0;
$haber = 0;
$saldo = 0;
$tot_debe = 0;
$tot_haber = 0;
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
$sql = pg_query("select id_plan_cuentas, codigo_plan, descripcion from plan_cuentas where cuenta='M' and estado='Activo' and codigo_plan like '1%' order by codigo_plan");
$pdf->SetX(5);
//$pdf->SetFillColor(216, 216, 231);
$pdf->Cell(125, 6, utf8_decode("ACTIVOS "), 0, 0, 'L', 0);
$pdf->Ln(5);

$tactivos = 0;
mostrarValCuentas1($_GET["inicio"], $_GET["fin"], 1, $conpuntoresult, $tactivos);

/* while ($row = pg_fetch_row($sql)) {
    $sql1 = pg_query("select t.fecha_actual,
    t.concepto,
    dt.debito,
    dt.credito from transacciones t,
    detalle_transaccion dt where 
    dt.id_transacciones=t.id_transacciones and
    t.fecha_actual $query_fecha '$_GET[fin]' and
    t.estado='Activo' and
    dt.id_plan_cuentas=$row[0] and
    t.id_empresa='$conpuntoresult' order by t.fecha_actual");
    $debe = 0;
    $haber = 0;
    while ($row1 = pg_fetch_row($sql1)) {
        $debe = $debe + $row1[2];
        $haber = $haber + $row1[3];
    }
    $tot_debe = $tot_debe + $debe;
    $tot_haber = $tot_haber + $haber;
    if ($debe != 0 || $haber != 0) {
        $pdf->SetX(5);
        $pdf->Cell(125, 6, utf8_decode($row[2]), 0, 0, 'L', false);
        $pdf->SetFont('Amble-Regular', '', 9);
        $pdf->Cell(25, 6, number_format($debe, 2, '.', ''), 0, 0, 'R', false);
        $pdf->Cell(25, 6, utf8_decode(''), 0, 0, 'C', false);
        $pdf->Ln(5);
    }
} */
$tot_act = $tot_debe;
$pdf->SetX(5);
$pdf->Cell(125, 6, utf8_decode("TOTAL ACTIVOS: "), 0, 0, 'L', 0);
$pdf->Cell(25, 6, (number_format($tactivos, 2, ',', '.')), 0, 0, 'R', 0);
$pdf->Ln(7);
$pdf->Cell(205, 0, utf8_decode(''), 1, 1, 'R', 0);
$tot_debe = 0;
$tot_haber = 0;
$sql = pg_query("select id_plan_cuentas, codigo_plan, descripcion from plan_cuentas where cuenta='M' and estado='Activo' and codigo_plan like '2%' order by codigo_plan");
$pdf->SetX(5);
//$pdf->SetFillColor(216, 216, 231);
$pdf->Cell(125, 6, utf8_decode("PASIVOS "), 0, 0, 'L', 0);
$pdf->Ln(5);

$tpasivos = 0;
mostrarValCuentas2($_GET["inicio"], $_GET["fin"], 2, $conpuntoresult, $tpasivos);

/* while ($row = pg_fetch_row($sql)) {
    $sql1 = pg_query("select t.fecha_actual, t.concepto, dt.debito, dt.credito from transacciones t, detalle_transaccion dt where dt.id_transacciones=t.id_transacciones and t.fecha_actual between '$_GET[inicio]' and '$_GET[fin]' and t.estado='Activo' and dt.id_plan_cuentas=$row[0] and t.id_empresa='$conpuntoresult' order by t.fecha_actual");
    $debe = 0;
    $haber = 0;
    while ($row1 = pg_fetch_row($sql1)) {
        $debe = $debe + $row1[2];
        $haber = $haber + $row1[3];
    }
    $tot_debe = $tot_debe + $debe;
    $tot_haber = $tot_haber + $haber;
    if ($debe != 0 || $haber != 0) {
        $pdf->SetX(5);
        $pdf->Cell(125, 6, utf8_decode($row[2]), 0, 0, 'L', false);
        $pdf->SetFont('Amble-Regular', '', 9);
        $pdf->Cell(25, 6, utf8_decode(''), 0, 0, 'C', false);
        $pdf->Cell(25, 6, number_format($haber, 2, '.', ''), 0, 0, 'R', false);
        $pdf->Ln(5);
    }
} */

$tot_pas = $tot_haber;
$tot_haber = 0;
$tot_debe = 0;
$sql = pg_query("select id_plan_cuentas, codigo_plan, descripcion from plan_cuentas where cuenta='M' and estado='Activo' and codigo_plan like '3%' order by codigo_plan");
$pdf->SetX(5);
//$pdf->SetFillColor(216, 216, 231);
$pdf->Cell(125, 6, utf8_decode("PATRIMONIO "), 0, 0, 'L', 0);
$pdf->Ln(5);

$tpatrimonio = 0;
mostrarValCuentas2($_GET["inicio"], $_GET["fin"], 3, $conpuntoresult, $tpatrimonio);

/* while ($row = pg_fetch_row($sql)) {
    $sql1 = pg_query("select t.fecha_actual, t.concepto, dt.debito, dt.credito from transacciones t, detalle_transaccion dt where dt.id_transacciones=t.id_transacciones and t.fecha_actual between '$_GET[inicio]' and '$_GET[fin]' and t.estado='Activo' and dt.id_plan_cuentas=$row[0] and t.id_empresa='$conpuntoresult' order by t.fecha_actual");
    $debe = 0;
    $haber = 0;
    while ($row1 = pg_fetch_row($sql1)) {
        $debe = $debe + $row1[2];
        $haber = $haber + $row1[3];
    }
    $tot_debe = $tot_debe + $debe;
    $tot_haber = $tot_haber + $haber;
    if ($debe != 0 || $haber != 0) {
        $pdf->SetX(5);
        $pdf->Cell(125, 6, utf8_decode($row[2]), 0, 0, 'L', false);
        $pdf->SetFont('Amble-Regular', '', 9);
        $pdf->Cell(25, 6, utf8_decode(''), 0, 0, 'C', false);
        $pdf->Cell(25, 6, number_format($haber, 2, '.', ''), 0, 0, 'R', false);
        $pdf->Ln(5);
    }
} */

$tutilidad=0;
mostrarValUtilidad($_GET["inicio"], $_GET["fin"], $conpuntoresult, $tutilidad);

$pdf->SetX(5);
$pdf->Cell(150, 6, utf8_decode("TOTAL PASIVO Y PATRIMONIO: "), 0, 0, 'L', 0);
$pdf->Cell(25, 6, (number_format($tpatrimonio + $tpasivos+ $tutilidad, 2, ',', '.')), 0, 1, 'R', 0);


$pdf->SetFont('Helvetica', 'B', 9);
$pdf->Cell(205, 0, utf8_decode(''), 1, 1, 'R', 0);
$pdf->SetX(5);

$pdf->Cell(125, 6, utf8_decode("TOTALES: "), 0, 0, 'L', 0);
$pdf->Cell(25, 6, (number_format($tactivos, 2, ',', '.')), 0, 0, 'R', 0);
$pdf->Cell(25, 6, (number_format($tpasivos + $tpatrimonio+ $tutilidad , 2, ',', '.')), 0, 1, 'R', 0);
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
    order by codigo_plan
    ) 
    and t.id_empresa='$idpv' 
    group by dt.id_plan_cuentas,
    plc.descripcion,
    plc.codigo_plan
    order by codigo_plan asc;
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (!$rows) {
        return [];
    }
    return $rows;
}

function mostrarValCuentas1($finicio, $ffin, $codcuenta, $idpv, &$total)
{
    global $pdf;

    $pdf->SetFont('Amble-Regular', '', 8);

    if (empty($finicio)) {
        $regs = obtenerValCuentas($ffin, $ffin, $codcuenta, $idpv);
    } else {
        $regs = obtenerValCuentas($finicio, $ffin, $codcuenta, $idpv);
    }

    foreach ($regs as $value) {
        $diferencia = $value["debito"] - $value["credito"];
        $pdf->SetX(5);
        $pdf->Cell(25, 6, utf8_decode($value["codigo_plan"]), 0, 0, 'L', false);
        $pdf->Cell(100, 6, utf8_decode($value["descripcion"]), 0, 0, 'L', false);
        $pdf->SetFont('Amble-Regular', '', 9);
        if ($diferencia < 0) {
            $pdf->SetTextColor(255, 0, 0);
        }
        $pdf->Cell(25, 6, number_format($diferencia, 2, '.', ''), 0, 0, 'R', false);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(25, 6, "", 0, 0, 'C', false);
        $pdf->Ln(5);
        $total += $diferencia;
    }
}

function mostrarValCuentas2($finicio, $ffin, $codcuenta, $idpv, &$total)
{
    global $pdf;

    $pdf->SetFont('Amble-Regular', '', 8);

    if (empty($finicio)) {
        $regs = obtenerValCuentas($ffin, $ffin, $codcuenta, $idpv);
    } else {
        $regs = obtenerValCuentas($finicio, $ffin, $codcuenta, $idpv);
    }

    foreach ($regs as $value) {
        $diferencia =  $value["credito"] - $value["debito"];
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetX(5);
        $pdf->Cell(25, 6, utf8_decode($value["codigo_plan"]), 0, 0, 'L', false);
        $pdf->Cell(100, 6, utf8_decode($value["descripcion"]), 0, 0, 'L', false);
        $pdf->SetFont('Amble-Regular', '', 9);
        $pdf->Cell(25, 6, "", 0, 0, 'C', false);
        if ($diferencia < 0) {
            $pdf->SetTextColor(255, 0, 0);
        }
        $pdf->Cell(25, 6, number_format($diferencia, 2, '.', ''), 0, 0, 'R', false);
        $pdf->Ln(5);
        $total += $diferencia;
    }
}

function mostrarValUtilidad($finicio, $ffin, $idpv, &$total)
{
    global $pdf;

    $pdf->SetFont('Amble-Regular', '', 8);

    if (empty($finicio)) {
        $regs = obtenerValCuentas($ffin, $ffin, 4, $idpv);
        $regs = array_merge($regs, obtenerValCuentas($ffin, $ffin, 5, $idpv));
        $regs = array_merge($regs, obtenerValCuentas($ffin, $ffin, 6, $idpv));
    } else {
        $regs = obtenerValCuentas($finicio, $ffin, 4, $idpv);
        $regs = array_merge($regs, obtenerValCuentas($finicio, $ffin, 5, $idpv));
        $regs = array_merge($regs, obtenerValCuentas($finicio, $ffin, 6, $idpv));
    }

    $diferencia = 0;
    foreach ($regs as $value) {
        $diferencia +=  $value["credito"] - $value["debito"];
    }

    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetX(5);
    $pdf->Cell(25, 6, utf8_decode("3.6.01.03"), 0, 0, 'L', false);
    $pdf->Cell(100, 6, utf8_decode("UTILIDAD/PÉRDIDA EJERCICIO"), 0, 0, 'L', false);
    $pdf->SetFont('Amble-Regular', '', 9);
    $pdf->Cell(25, 6, "", 0, 0, 'C', false);
    if ($diferencia < 0) {
        $pdf->SetTextColor(255, 0, 0);
    }
    $pdf->Cell(25, 6, number_format($diferencia, 2, '.', ''), 0, 0, 'R', false);
    $pdf->Ln(5);
    $total += $diferencia;
}
