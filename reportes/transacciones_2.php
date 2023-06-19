<?php

require('../fpdf/fpdf.php');
include '../procesos/base.php';
include '../procesos/funciones.php';
conectarse();
date_default_timezone_set('America/Guayaquil');
session_start();
$contador = 0;

class PDF extends FPDF {

    var $widths;
    var $aligns;
    var $tipo;
    var $fecha;
    var $num;
    var $total;
    var $total1;
    var $concepto;
    var $transaccion;
    var $abreviatura;
    var $comprobante;
    var $concepto_gasto;
    var $concepto_gasto_text;

    function SetWidths($w) {
        $this->widths = $w;
    }

    function Header() {
        $total = 0;
        $total1 = 0;
        $concepto = "";
        $consulta1 = pg_query("select  T.fecha_actual, T.hora_actual, U.nombre_usuario, U.apellido_usuario, T.num_transaccion, T.concepto, T.total_debe, T.total_haber, T.saldo, TT.descripcion, T.id_transacciones, TT.abreviatura,T.comprobante from transacciones T, usuario U, tipo_transaccion TT where T.id_usuario=U.id_usuario and T.comprobante='$_GET[id]' and T.id_tipo_transaccion=TT.id_tipo_transaccion");
        while ($row = pg_fetch_row($consulta1)) {
            $this->tipo = $row[9];
            $this->fecha = $row[0];
            $this->num = $row[4];
            $this->total = $row[6];
            $this->total1 = $row[7];
            $this->concepto = $row[5];
            $this->transaccion = $row[10];
            $this->abreviatura = $row[11];

            $this->comprobante = $row[12];
        }
        $id_gasto = 0;
        $consulta12 = pg_query("select  
T.comprobante from transacciones T, usuario U, tipo_transaccion TT 
 where T.id_usuario=U.id_usuario and T.comprobante='$_GET[id]' and T.id_tipo_transaccion=TT.id_tipo_transaccion and T.concepto LIKE '%COMPRA%'");
        while ($row = pg_fetch_row($consulta12)) {
            $id_gasto = $row[0];
        }
//        if ($id_gasto != 0) {
//            $consulta122 = pg_query("select concepto from gastos,detalle_gastos where gastos.id_gastos=detalle_gastos.id_gastos and comprobante='$id_gasto'");
//            while ($row = pg_fetch_row($consulta122)) {
//                $this->concepto_gasto_text = $row[0];
//            }
//        }
        //$codigo.='<h2 style="color:#1B8D72;font-weight: bold;font-size:13px;">Transación Nro: 1 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Nro de Documento: '.$num.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Fecha: '.$fecha.'</h2>';                     
        $this->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->SetFont('Amble-Regular', '', 10);
        $fecha = date('Y-m-d', time());
        $this->SetX(1);
        $this->SetY(1);
        $this->Cell(20, 5, $fecha, 0, 0, 'C', 0);
        $this->Cell(150, 5, "CLIENTE", 0, 1, 'R', 0);
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(190, 8, "EMPRESA: " . $_SESSION['nombre_empresa'], 0, 1, 'C', 0);
        $this->Image('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"], 5, 8, 20, 14);
        $this->SetFont('Amble-Regular', '', 10);
        $this->Cell(180, 5, "PROPIETARIO: " . utf8_decode($_SESSION['propietario']), 0, 1, 'C', 0);
        $this->Cell(80, 5, "TEL.: " . utf8_decode($_SESSION['telefono']), 0, 0, 'R', 0);
        $this->Cell(80, 5, "CEL.: " . utf8_decode($_SESSION['celular']), 0, 1, 'C', 0);
        $this->Cell(180, 5, "DIR.: " . utf8_decode($_SESSION['direccion']), 0, 1, 'C', 0);
        $this->Cell(180, 5, "SLOGAN.: " . utf8_decode($_SESSION['slogan']), 0, 1, 'C', 0);
        $this->Cell(180, 5, utf8_decode($_SESSION['pais_ciudad']), 0, 1, 'C', 0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);
        $this->Line(1, 46, 210, 46);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(190, 5, utf8_decode('COMPROBANTE DE: ' . $this->tipo), 0, 1, 'C', 0);
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
$pdf->AddPage();
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AliasNbPages();
$pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
$pdf->SetFont('Amble-Regular', '', 10);
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetX(5);
$pdf->SetFont('Amble-Regular', '', 9);

$pdf->SetX(1);
$pdf->SetFillColor(187, 179, 180);
$pdf->Cell(60, 6, maxCaracter(utf8_decode('Transación Nro: ' . $pdf->transaccion), 35), 1, 0, 'L', 1);
$pdf->Cell(70, 6, maxCaracter(utf8_decode('Nro. Asiento: ' . $pdf->abreviatura . "-" . $pdf->num), 35), 1, 0, 'L', 1);
$pdf->Cell(75, 6, maxCaracter(utf8_decode('Fecha: ' . $pdf->fecha), 35), 1, 1, 'L', 1);
$pdf->Ln(3);
$pdf->Cell(20, 7, utf8_decode('CONCEPTO: '), 0, 0, 'R', 0);
$pdf->Cell(85, 7, maxCaracter("", 100), 0, 0, 'L', 0);
$pdf->Ln(3);
$pdf->Cell(20, 10, utf8_decode(''), 0, 0, 'R', 0);
$pdf->Cell(85, 10, maxCaracter($pdf->concepto, 100), 0, 0, 'L', 0);
$pdf->Ln(8);
$repetido = 0;
$sql1 = pg_query("select P.id_plan_cuentas,
 P.codigo_plan,
 P.descripcion,
 D.debito,
 D.credito  from transacciones T,
detalle_transaccion D,
plan_cuentas P 
where T.id_transacciones = D.id_transacciones 
and D.id_plan_cuentas = P.id_plan_cuentas 
and  T.comprobante='$_GET[id]'  
and D.debito>'0.000'
and t.identificador_cli_pro='COM'
order by
case
when P.codigo_plan like '5%' then 1
when P.codigo_plan like '1%' then 2
when P.codigo_plan like '2%' then 3
else 4
end asc,P.id_plan_cuentas asc;");
if (pg_num_rows($sql1)) {
    while ($row1 = pg_fetch_row($sql1)) {
        if ($repetido == 0) {
            $pdf->SetX(1);
            $pdf->Cell(45, 6, utf8_decode('Cod. Cuenta'), 1, 0, 'C', 0);
            $pdf->Cell(120, 6, utf8_decode('Descripción'), 1, 0, 'C', 0);
            //$pdf->Cell(40, 6, utf8_decode('Tipo Ref.'),1,0, 'C',0);                                         
            //$pdf->Cell(45, 6, utf8_decode('# Ref.'),1,0, 'C',0);                                         
            $pdf->Cell(20, 6, utf8_decode('Debe'), 1, 0, 'C', 0);
            $pdf->Cell(20, 6, utf8_decode('Haber'), 1, 1, 'C', 0);
            $repetido = 1;
            $contador = 1;
        }
        $pdf->SetX(3);
        $pdf->Cell(45, 6, maxCaracter(utf8_decode($row1[1]), 15), 0, 0, 'L', 0);
        $pdf->Cell(118, 6, maxCaracter(utf8_decode($row1[2]), 70), 0, 0, 'L', 0);
        //$pdf->Cell(40, 6, maxCaracter(utf8_decode($row1[3]),20),0,0, 'L',0);                                             
        //$pdf->Cell(45, 6, maxCaracter(utf8_decode($row1[4]),22),0,0, 'L',0);                                             
        $pdf->Cell(20, 6, maxCaracter(utf8_decode(number_format($row1[3], 2, '.', '')), 15), 0, 0, 'R', 0);
        $pdf->Cell(20, 6, maxCaracter(utf8_decode(number_format($row1[4], 2, '.', '')), 15), 0, 1, 'R', 0);
        $repetido = 1;
    }
}
$sql1 = pg_query("select P.id_plan_cuentas,
 P.codigo_plan,
 P.descripcion,
 D.debito,
 D.credito  from 
 transacciones T,
detalle_transaccion D,
plan_cuentas P 
where T.id_transacciones = D.id_transacciones 
and D.id_plan_cuentas = P.id_plan_cuentas 
and  T.comprobante='$_GET[id]'  
and D.credito>'0.000' 
and t.identificador_cli_pro='COM'
order by
case
when P.codigo_plan like '4%' then 1
when P.codigo_plan like '2%' then 2
when P.codigo_plan like '1%' then 3
else 4
end asc, P.id_plan_cuentas asc;");
if (pg_num_rows($sql1)) {
    while ($row1 = pg_fetch_row($sql1)) {
        if ($repetido == 0) {
            $pdf->SetX(1);
            $pdf->Cell(45, 6, utf8_decode('Cod. Cuenta'), 1, 0, 'C', 0);
            $pdf->Cell(120, 6, utf8_decode('Descripción'), 1, 0, 'C', 0);
            //$pdf->Cell(40, 6, utf8_decode('Tipo Ref.'),1,0, 'C',0);                                         
            //$pdf->Cell(45, 6, utf8_decode('# Ref.'),1,0, 'C',0);                                         
            $pdf->Cell(20, 6, utf8_decode('Débito'), 1, 0, 'C', 0);
            $pdf->Cell(20, 6, utf8_decode('Crédito'), 1, 1, 'C', 0);
            $repetido = 1;
            $contador = 1;
        }
        $pdf->SetX(3);
        $pdf->Cell(45, 6, maxCaracter(utf8_decode($row1[1]), 15), 0, 0, 'L', 0);
        $pdf->Cell(118, 6, maxCaracter(utf8_decode($row1[2]), 70), 0, 0, 'L', 0);
        //$pdf->Cell(40, 6, maxCaracter(utf8_decode($row1[3]),20),0,0, 'L',0);                                             
        //$pdf->Cell(45, 6, maxCaracter(utf8_decode($row1[4]),22),0,0, 'L',0);                                             
        $pdf->Cell(20, 6, maxCaracter(utf8_decode(number_format($row1[3], 2, ',', '')), 15), 0, 0, 'R', 0);
        $pdf->Cell(20, 6, maxCaracter(utf8_decode(number_format($row1[4], 2, ',', '')), 15), 0, 1, 'R', 0);
        $repetido = 1;
    }
}

if ($contador > 0) {
    $pdf->SetX(1);
    $pdf->Cell(205, 0, utf8_decode(''), 1, 1, 'R', 1);
    $pdf->Cell(166, 6, utf8_decode('Totales:'), 0, 0, 'R', 0);
    $pdf->Cell(20, 6, (number_format($pdf->total, 2, ',', '.')), 0, 0, 'R', 0);
    $pdf->Cell(20, 6, (number_format($pdf->total1, 2, ',', '.')), 0, 1, 'R', 0);
}
$pdf->Ln(20);
$pdf->SetX(7);
$pdf->Cell(40, 0, utf8_decode(''), 1, 1, 'R', 1);
$pdf->SetX(44);
$pdf->Cell(5, 0, "", 0, 0, 'R', 0);
$pdf->SetX(57);
$pdf->Cell(40, 0, utf8_decode(''), 1, 1, 'R', 1);

$pdf->SetX(94);
$pdf->Cell(5, 0, "", 0, 0, 'R', 0);
$pdf->SetX(107);
$pdf->Cell(40, 0, utf8_decode(''), 1, 1, 'R', 1);

$pdf->SetX(144);
$pdf->Cell(5, 0, "", 0, 0, 'R', 0);
$pdf->SetX(157);
$pdf->Cell(40, 0, utf8_decode(''), 1, 1, 'R', 1);


$pdf->Ln(4);
$pdf->SetX(7);
$pdf->Cell(40, 0, utf8_decode('Elaborado por:'), 0, 0, 'C', 0);
$pdf->SetX(44);
//$pdf->Cell(5, 0, "    ",1,1, 'R',1); 
$pdf->SetX(57);
$pdf->Cell(40, 0, utf8_decode('Aprobado'), 0, 0, 'C', 0);

$pdf->SetX(94);
//$pdf->Cell(5, 0, "",1,1, 'R',1); 
$pdf->SetX(107);
$pdf->Cell(40, 0, utf8_decode('Contabilidad'), 0, 0, 'C', 0);

$pdf->SetX(144);
//$pdf->Cell(5, 0, "",1,1, 'R',1); 
$pdf->SetX(157);
$pdf->Cell(40, 0, utf8_decode('Recibí Conforme'), 0, 0, 'C', 0);

$usuario = pg_query("select u.nombre_usuario, u.apellido_usuario from usuario u, transacciones t where t.id_usuario=u.id_usuario and t.comprobante='$_GET[id]'");
$datos = pg_fetch_row($usuario);
$pdf->Ln(4);
$pdf->SetX(7);
$pdf->Cell(100, 0, utf8_decode($datos[0] . " " . $datos[1]), 0, 0, 'L', 0);
$pdf->SetX(44);

$pdf->SetX(157);
$pdf->Cell(40, 0, utf8_decode('C.I.:'), 0, 0, 'L', 0);

$pdf->SetDrawColor(0, 0, 0);
$pdf->SetLineWidth(0.4);
$pdf->Line(1, 46, 20, 46);

$pdf->Output();
?>