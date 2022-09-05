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
        $this->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->SetFont('Amble-Regular', '', 10);
        $fecha = date('Y-m-d', time());
        $this->SetX(1);
        $this->SetY(1);
        $this->Cell(105, 5, $fecha, 0, 0, 'C', 0);
        $this->Cell(105, 5, "NOMINA", 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(210, 8, $_SESSION['nombre_empresa'], 0, 1, 'C', 0);
        $this->Image('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"], 10, 7, 15, 15);
        $this->Image('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"], 180, 7, 15, 15);
        // $this->SetFont('Amble-Regular', '', 10);
        // $this->Cell(190, 5, "PROPIETARIO: " . utf8_decode($_SESSION['propietario']), 0, 1, 'C', 0);
        // $this->Cell(80, 5, "TEL.: " . utf8_decode($_SESSION['telefono']), 0, 0, 'R', 0);
        // $this->Cell(80, 5, "CEL.: " . utf8_decode($_SESSION['celular']), 0, 1, 'C', 0);
        // $this->Cell(190, 5, "DIR.: " . utf8_decode($_SESSION['direccion']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, "SLOGAN.: " . utf8_decode($_SESSION['slogan']), 0, 1, 'C', 0);
        // $this->Cell(190, 5, utf8_decode($_SESSION['pais_ciudad']), 0, 1, 'C', 0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.5);
        $this->Line(0, 25, 210, 25);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(210, 5, utf8_decode("LISTA DE NOMINA"), 0, 1, 'C', 0);
        $this->SetFont('helvetica', 'B', 10);
        $this->Ln(7);
        $this->SetX(0);
        $this->SetFont('helvetica', 'B', 9);
        $this->SetFillColor(175, 215, 240);
        $this->Cell(8, 6, utf8_decode("N°"), 1, 0, 'C', 1);
        $this->Cell(50, 6, utf8_decode("NOMBRE Y APELLIDOS"), 1, 0, 'l', 1);
        $this->Cell(20, 6, utf8_decode("CEDULA"), 1, 0, 'l', 1);
        $this->Cell(30, 6, utf8_decode("CARGO"), 1, 0, 'l', 1);
        $this->Cell(20, 6, utf8_decode("SUELDO"), 1, 0, 'l', 1);
        $this->Cell(20, 6, utf8_decode("IESS PER."), 1, 0, 'l', 1);
        $this->Cell(20, 6, utf8_decode("IESS PATRO."), 1, 0, 'l', 1);
        $this->Cell(20, 6, utf8_decode("ANTICIPOS"), 1, 0, 'l', 1);
        $this->Cell(23, 6, utf8_decode("LIQUIDO RECIBIR"), 1, 1, 'l', 1);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

}

$pdf = new PDF('P', 'mm', 'a4');
$pdf->SetTitle('Lista de Precios');
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->AliasNbPages();

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

$mes = $_GET["mes"];

if ($mes == '01') {
    $mes = 'Enero';
}
if ($mes == '02') {
    $mes = 'Febrero';
}
if ($mes == '03') {
    $mes = 'Marzo';
}

if ($mes == '04') {
    $mes = 'Abril';
}

if ($mes == '05') {
    $mes = 'Mayo';
}

if ($mes == '06') {
    $mes = 'Junio';
}

if ($mes == '07') {
    $mes = 'Julio';
}

if ($mes == '08') {
    $mes = 'Agosto';
}

if ($mes == '09') {
    $mes = 'Septiembre';
}

if ($mes == '10') {
    $mes = 'Octubre';
}

if ($mes == '11') {
    $mes = 'Noviembre';
}

if ($mes == '12') {
    $mes = 'Diciembre';
}



$consulta = pg_query(
        "   
     select DISTINCT ON (empleado.id_empleado) empleado.id_empleado,nombres_empleado,identificacion,nombre_cargo,detalle_rol.sueldo_percibido,
  detalle_rol.total_anticipos,detalle_rol.aporte_personal,detalle_rol.aportacion_patronal, total_deduccion ,liquido_recivir

  from empleado

LEFT JOIN cargo ON empleado.id_cargo=cargo.id_cargo
LEFT JOIN anticipos as emple ON emple.id_empleado=empleado.id_empleado

LEFT JOIN detalle_rol ON detalle_rol.id_empleado=emple.id_empleado
LEFT JOIN anticipos as deta ON detalle_rol.id_empleado=emple.id_empleado

LEFT JOIN rol_pagos ON rol_pagos.id_rol_pagos=detalle_rol.id_rol_pagos

where    empleado.estado='Activo' and rol_pagos.anio='$_GET[anio]' and rol_pagos.mes='$mes'
"
);
if (pg_num_rows($consulta)) {
    $total=0;
    while ($row = pg_fetch_row($consulta)) {
        $pdf->SetX(1);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(8, 5, maxCaracter(utf8_decode($row[0]), 30), 0, 0, 'L', 0);//nu
        $pdf->Cell(48, 5, maxCaracter(utf8_decode($row[1]), 40), 0, 0, 'L', 0);//nombres
        $pdf->Cell(20, 5, utf8_decode($row[2]), 0, 0, 'l', 0);//cedula
        $pdf->Cell(30, 5, utf8_decode($row[3]), 0, 0, 'l', 0);//cargo
        $pdf->Cell(20, 5, utf8_decode($row[4]), 0, 0, 'l', 0);//sueldo
        $pdf->Cell(20, 5,  (number_format($row[6], 2, ',', '.')), 0, 0, 'l', 0);//iess per
        $pdf->Cell(20, 5, (number_format($row[7], 2, ',', '.')), 0, 0, 'l', 0);//iess patr
        $pdf->Cell(20, 5, utf8_decode($row[5]), 0, 0, 'l', 0);//antici
          $pdf->Cell(23, 5, utf8_decode($row[9]), 0, 1, 'l', 0);//antici
        
    
 $total = $total + ($row[9]);

     
    }
    $pdf->Ln(2);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(173, 6, utf8_decode('Total'), 0, 0, 'R', 0);
    $pdf->Cell(25, 6, (number_format($total, 2, ',', '.')), 0, 1, 'R', 0);
}
$pdf->Output();
