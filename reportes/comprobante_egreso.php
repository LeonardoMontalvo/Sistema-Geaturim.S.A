<?php
require('../fpdf/fpdf.php');
include '../procesos/base.php';
include '../procesos/funciones.php';
include '../procesos/convertir.php';

conectarse();
date_default_timezone_set('America/Guayaquil');
session_start();

$idgasto = $_GET["id"];
$enletras=new EnLetras();

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
    }
    function Footer()
    {
    }
}

$pdf = new PDF('P', 'mm', 'a4');
$pdf->SetTitle('Comprobante de egreso');
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
$pdf->SetFont('Amble-Regular', '', 9);

$dgenerales = datosGenerales();
if (count($dgenerales) > 0) {
    $fechae = $dgenerales["fecha_emision"];
    $tstamp = strtotime($fechae);
    $dia = date('d', $tstamp);
    $mes = date('m', $tstamp);
    $anio = date('Y', $tstamp);
    mostrarLugarFechaPor("IBARRA", $dia, $mes, $anio, $dgenerales["total"]);
    mostrarCantidadBenefConcepto($enletras->ValorEnLetras($dgenerales["total"],utf8_decode("Dólares")), $dgenerales["empresa_pro"], substr($dgenerales["concepto"], 0, 160));
} else {
    mostrarLugarFechaPor("IBARRA", "", "", "", "");
    mostrarCantidadBenefConcepto("", "", "");
}
mostrarDetalles();
mostrarFormaPago();

$pdf->Output();



function mostrarLugarFechaPor($lugar, $mes, $dia, $anio, $por)
{
    global $pdf;
    //fecha, por
    $pdf->SetXY(0, 0);
    $pdf->SetY(50); //margen borde superior
    $pdf->SetX(40);
    $pdf->Cell(20, 4, $lugar, 0, 0);
    $pdf->SetX(70);
    $pdf->Cell(20, 4, $mes, 0, 0);
    $pdf->SetX(90);
    $pdf->Cell(20, 4, $dia, 0, 0);
    $pdf->SetX(110);
    $pdf->Cell(20, 4, $anio, 0, 0);
    $pdf->SetX(140);
    //$pdf->Cell(60, 4, $por, 1, 0);
    //$pdf->MultiCell(60, 4, $por, 1);
    $pdf->MultiCell(60, 5, $por, 0);
}

function mostrarCantidadBenefConcepto($cantidad, $beneficiario, $concepto)
{
    global $pdf;
    $pdf->SetY(70); //margen borde superior
    $pdf->SetX(40); //margen borde izquierdo
    $pdf->Cell(160, 4, substr($cantidad,0,160), 0, 0);
    $pdf->SetY(77); //ceparación de 7mm entre cada línea
    $pdf->SetX(40);
    $pdf->Cell(160, 4, substr(utf8_decode($beneficiario), 0, 160), 0, 0);
    $pdf->SetY(84);
    $pdf->SetX(40);
    $pdf->Cell(160, 4, substr(utf8_decode($concepto), 0, 160), 0, 0);
    $pdf->SetY(91);
    $pdf->SetX(40);
    $pdf->Cell(160, 4, substr(utf8_decode($concepto), 161, 320), 0, 0);
}

function mostrarDetalles()
{
    global $pdf;
    $ddebito = datosDetallesDebito();
    $dcredito = datosDetallesCredito();
    $y = 113;
    $tmpy = $y;
    $espy = 5;
    $sumad = $sumac = 0;
    $pdf->SetY($y); // 1.8 cm desde última línea concepto
    foreach ($ddebito as $value) {
        $pdf->SetX(10);
        $pdf->Cell(28, 4, utf8_decode($value["codigo_plan"]), 0, 0);
        $pdf->SetX(38);
        $pdf->Cell(103, 4, substr(utf8_decode($value["descripcion"]), 0, 100), 0, 0);
        $pdf->SetX(141);
        $pdf->Cell(29, 4, utf8_decode($value["debito"]), 0, 0);
        $pdf->SetX(170);
        $pdf->Cell(29, 4, utf8_decode($value["credito"]), 0, 0);
        $tmpy += $espy;
        $pdf->SetY($tmpy);
        $sumad += $value["debito"];
    }
    foreach ($dcredito as $value) {
        $pdf->SetX(10);
        $pdf->Cell(28, 4, utf8_decode($value["codigo_plan"]), 0, 0);
        $pdf->SetX(38);
        $pdf->Cell(103, 4, substr(utf8_decode($value["descripcion"]), 0, 100), 0, 0);
        $pdf->SetX(141);
        $pdf->Cell(29, 4, utf8_decode($value["debito"]), 0, 0);
        $pdf->SetX(170);
        $pdf->Cell(29, 4, utf8_decode($value["credito"]), 0, 0);
        $tmpy += $espy;
        $pdf->SetY($tmpy);
        $sumac += $value["credito"];
    }

    mostrarSumas($sumad, $sumac);
}

function mostrarFormaPago()
{
    global $pdf;
    $efectivo = false;
    $banco = $cheque = $cta = "";
    $formap = datosFormaPago();
    if (count($formap)) {
        $banco = trim($formap["descripcion"]);
//       print_r($banco);
        if($banco == "CAJA"){
            $banco="";
        }
        $cheque = $formap["numero_documento"];
        $efectivo = $formap["forma_pago"] == "CONTADO";
    }
    $pdf->SetY(101);
    $pdf->SetX(20);
    $pdf->Cell(40, 4, substr(utf8_decode($banco), 0,40), 0, 0);
    $pdf->SetX(70);
    $pdf->Cell(40, 4, utf8_decode($cheque), 0, 0);
    $pdf->SetX(120);
    $pdf->Cell(40, 4, utf8_decode($cta), 0, 0);
    $pdf->SetX(168);
    $pdf->Cell(20, 4, ($efectivo ? "X" : ""), 0, 0);
}

function mostrarSumas($sumad, $sumac)
{
    global $pdf;
    $pdf->SetY(158); // 4.5 cm desde borde interior superior tabla
    $pdf->SetX(10);
    $pdf->Cell(28, 4, utf8_decode(""), 0, 0);
    $pdf->SetX(38);
    $pdf->Cell(103, 4, utf8_decode(""), 0, 0);
    $pdf->SetX(141);
    $pdf->Cell(29, 4, number_format($sumad, 2, ".", ""), 0, 0);
    $pdf->SetX(170);
    $pdf->Cell(29, 4, number_format($sumac, 2, ".", ""), 0, 0);
}

function datosGenerales()
{
    global $idgasto;
    $sql = "
    select
    p.empresa_pro,
    g.fecha_emision,
    dg.concepto,
    g.total
    from gastos g
    inner join proveedores p
    on g.id_proveedor=p.id_proveedor
    inner join(
    select id_gastos,string_agg(concepto,',')concepto 
    from detalle_gastos
    group by id_gastos,concepto
    ) dg
    on g.id_gastos=dg.id_gastos
    where g.id_gastos=$idgasto
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (!$rows) {
        return [];
    }
    return $rows[0];
}

function datosGenerales1()
{
    global $idgasto;
    $sql = "
    select
    p.empresa_pro,
    t.concepto,
    t.fecha_registro fecha_emision,
    t.total_debe total
    from
    transacciones t
    left join proveedores p
    on t.id_cliente=p.id_proveedor
    where t.id_transacciones=$idgasto
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (!$rows) {
        return [];
    }
    return $rows[0];
}

function datosDetallesDebito()
{
    global $idgasto;
    $sql1 = "select P.id_plan_cuentas,
    P.codigo_plan,
    P.descripcion,
    round(D.debito,2)debito,
    round(D.credito,2)credito  from transacciones T,
   detalle_transaccion D,
   plan_cuentas P 
   where T.id_transacciones = D.id_transacciones 
   and D.id_plan_cuentas = P.id_plan_cuentas 
   and  T.comprobante='$idgasto'  
   and D.debito>'0.000'
   and t.identificador_cli_pro='GAS'
   order by
   case
   when P.codigo_plan like '5%' then 1
   when P.codigo_plan like '1%' then 2
   when P.codigo_plan like '2%' then 3
   else 4
   end asc,P.id_plan_cuentas asc;";
    $res = pg_query($sql1);
    $rows = pg_fetch_all($res);
    if (!$rows) {
        return [];
    }
    return $rows;
}
function datosDetallesDebito1()
{
    global $idgasto;
    $sql1 = "select P.id_plan_cuentas,
    P.codigo_plan,
    P.descripcion,
    round(D.debito,2)debito,
    round(D.credito,2)credito  from transacciones T,
   detalle_transaccion D,
   plan_cuentas P 
   where T.id_transacciones = D.id_transacciones 
   and D.id_plan_cuentas = P.id_plan_cuentas 
   and  T.comprobante='$idgasto'  
   and D.debito>'0.000'
   and t.identificador_cli_pro='GAS'
   order by
   case
   when P.codigo_plan like '5%' then 1
   when P.codigo_plan like '1%' then 2
   when P.codigo_plan like '2%' then 3
   else 4
   end asc,P.id_plan_cuentas asc";
    $res = pg_query($sql1);
    $rows = pg_fetch_all($res);
    if (!$rows) {
        return [];
    }
    return $rows;
}

function datosDetallesCredito1()
{
    global $idgasto;
    $sql1 = "select P.id_plan_cuentas,
    P.codigo_plan,
    P.descripcion,
    round(D.debito,2)debito,
    round(D.credito,2)credito  from 
    transacciones T,
   detalle_transaccion D,
   plan_cuentas P 
   where T.id_transacciones = D.id_transacciones 
   and D.id_plan_cuentas = P.id_plan_cuentas 
   and  T.comprobante='$idgasto'  
   and D.credito>'0.000' 
   and t.identificador_cli_pro='GAS'
   order by
   case
when P.codigo_plan like '4%' then 1
when P.codigo_plan like '2%' then 2
when P.codigo_plan like '1%' then 3
   else 4
   end asc, P.id_plan_cuentas asc;";
    $res = pg_query($sql1);
    $rows = pg_fetch_all($res);
    if (!$rows) {
        return [];
    }
    return $rows;
}

function datosDetallesCredito()
{
    global $idgasto;
    $sql1 = "select P.id_plan_cuentas,
    P.codigo_plan,
    P.descripcion,
    round(D.debito,2)debito,
    round(D.credito,2)credito  from 
    transacciones T,
   detalle_transaccion D,
   plan_cuentas P 
   where T.id_transacciones = D.id_transacciones 
   and D.id_plan_cuentas = P.id_plan_cuentas 
   and   T.comprobante='$idgasto'
   and D.credito>'0.000' 
   and t.identificador_cli_pro='GAS'
   order by
   case
  when P.codigo_plan like '4%' then 1
when P.codigo_plan like '2%' then 2
when P.codigo_plan like '1%' then 3
   else 4
   end asc, P.id_plan_cuentas asc;";
    $res = pg_query($sql1);
    $rows = pg_fetch_all($res);
    if (!$rows) {
        return [];
    }
    return $rows;
}

function datosFormaPago()
{
    global $idgasto;
    $sql = "
    select
    fpg.forma_pago,
    descripcion,
    numero_documento
    from formas_pago_mixto_g fpg
    left join plan_cuentas pc
    on fpg.id_cuenta=pc.id_plan_cuentas::text
    where 
    id_gastos=$idgasto
    and(fpg.forma_pago='CHEQUE' 
    or fpg.forma_pago='CONTADO')
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (!$rows) {
        return [];
    }
    return $rows[0];
}
function datosFormaPago1()
{
    global $idgasto;
    $sql = "
    select
    fpg.forma_pago,
    descripcion,
    numero_documento
    from formas_pago_mixto_g fpg
    left join plan_cuentas pc
    on fpg.id_cuenta=pc.id_plan_cuentas::text
    where 
    id_gastos=$idgasto
    and(fpg.forma_pago='CHEQUE' 
    or fpg.forma_pago='CONTADO')
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (!$rows) {
        return [];
    }
    return $rows[0];
}
