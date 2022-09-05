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
        $this->SetY(3);
//            $this->Cell(20, 5, $fecha, 0,0, 'C', 0);                         
//            $this->Cell(150, 5, "CLIENTE", 0,1, 'R', 0);      
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(190, 8, $_SESSION['empresa'], 0, 1, 'C', 0);

//            $this->Image('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"],5,8,45,30);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(190, 4, utf8_decode($_SESSION['propietario']), 0, 1, 'C', 0);
        $this->SetFont('Amble-Regular', '', 9);
        $this->Cell(190, 4, "DIR.: " . utf8_decode($_SESSION['direccion']), 0, 1, 'C', 0);
        $this->Cell(190, 4, "RUC.: " . utf8_decode($_SESSION['ruc_cedula']), 0, 1, 'C', 0);

        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);
//            $this->Line(1,50,210,50);            
        $this->SetFont('Arial', 'B', 10);

        $this->Cell(200, 5, utf8_decode("ROL DE PAGOS INDIVIDUAL"), 0, 1, 'C', 0);

        $this->SetFont('Amble-Regular', '', 10);
        $this->Ln(3);
        $this->SetFillColor(255, 255, 225);
        $this->SetLineWidth(0.2);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
//        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

}

$pdf = new PDF('P', 'mm', 'a4');
conectarse();
$string = $_GET['id'];
$array = explode(",", $string);
$varresult = count($array);

for ($v = 0; $v < $varresult; $v++) {

    $pdf->AddPage();
    $arrayresult = $array[$v];
    $pdf->SetMargins(0, 0, 0, 0);
//$pdf->AliasNbPages();
    $pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
    $pdf->SetFont('Amble-Regular', '', 10);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetX(5);
    $pdf->SetFont('Amble-Regular', '', 9);
    $pdf->SetX(1);

    $nombres_nomina = '';

    $cargo = '';
    $salario = 0;
    $dias_trabajados = 0;
    $sueldo_empleado = 0;
    $horas_extras = 0;

    $fondo_reserva = 0;

    $comisiones_cupo_mensu = 0;

    $comiciones_promociones = 0;
    $total_aportes = 0;
    $otros_ingresos = 0;
    $total_ingresos = 0;
    $neto_recibir = 0;
    $aporte_patronal = 0;
    $fecha_rol = '';
    $prestamos_qui_iess = 0;
    $total_deduccion = 0;
    $id_empleado = 0;
    $total_anticipos = 0;
    $credito_personal = 0;
    $faltantes_caja = 0;
    $otros_descuentos = 0;
    $aporte_personal = 0;
    $sql = pg_query("SELECT id_detalle_rol, id_rol_pagos, id_empleado, dias_laborados, afiliacion, 
       sueldo_percibido, horas_extras, otros, empleados, iess, aportacion_patronal, 
       tercer_sueldo, cuarto_sueldo, total_nomina, aporte_personal, 
       total_anticipos, faltante_caja, total_multas, impuesto_renta, 
       prestamo_iess, comisariato, otros_descuentos, total_deduccion, 
       liquido_recivir
  FROM detalle_rol where id_detalle_rol='$arrayresult'");
    while ($row = pg_fetch_row($sql)) {

        $id_empleado = $row[2];
        $dias_trabajados = $row[3];
        $horas_extras = $row[6];
        $otros_ingresos = $row[7];
        $aporte_patronal = $row[10];
        $total_ingresos = $row[13];
        $aporte_personal = $row[14];
        $total_anticipos = $row[15];
        $prestamos_qui_iess = $row[19];
        $total_deduccion = $row[22];
        $neto_recibir = $row[23];
        $total_aportes = $aporte_patronal + $aporte_personal;
        $credito_personal = $row[20];
        $faltantes_caja = $row[16];
        $otros_descuentos = $row[17];
    }
    $sql1 = pg_query("SELECT id_rol_pagos, fecha_actual, hora, id_empresa, total, estado, 
       id_usuario, anio, mes
  FROM rol_pagos;
");

    $sql = pg_query("select id_empleado,identificacion,nombres_empleado,nombre_cargo,sueldo_base"
            . " from empleado, cargo where empleado.id_cargo=cargo.id_cargo and empleado.estado='Activo' "
            . "and cargo.estado='Activo' and empleado.id_empleado ='$id_empleado'");

    while ($row = pg_fetch_row($sql)) {
        $nombres_nomina = $row[2];
        $cargo = $row[3];
        $salario = $row[4];
        $sueldo_empleado = $row[4];
    }
    while ($row = pg_fetch_row($sql1)) {

        $fecha_rol = $row[1];
    }
    $num = date("j", strtotime($fecha_rol));
    $anno = date("Y", strtotime($fecha_rol));
    $mes = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $mes = $mes[(date('m', strtotime($fecha_rol)) * 1) - 1];

    $pdf->SetX(10);
    $pdf->SetFont('Amble-Regular', '', 9);
    $pdf->Cell(170, 5, "CORRESPONDIENTE AL MES DE:", 0, 0, 'L', 0);
    $pdf->SetX(10);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(100, 5, ($mes . ' del ' . $anno), 0, 1, 'R', 0);

    $pdf->SetX(10);
    $pdf->SetFont('Amble-Regular', '', 9);
    $pdf->Cell(170, 4, "EMPLEADO / APELLIDOS Y NOMBRES:", 0, 0, 'L', 0);
    $pdf->SetX(10);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(116, 4, ($nombres_nomina), 0, 1, 'R', 0);

    $pdf->SetX(10);
    $pdf->SetFont('Amble-Regular', '', 9);
    $pdf->Cell(170, 6, utf8_decode("CARGO / ACTIVIDAD SECTORIAL:"), 0, 0, 'L', 0);
    $pdf->SetX(10);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(105, 4, ($cargo), 0, 1, 'R', 0);

    $pdf->SetX(10);
    $pdf->SetFont('Amble-Regular', '', 9);
    $pdf->Cell(170, 6, utf8_decode("SALARIO MÍNIMO SECTORIAL"), 0, 0, 'L', 0);
    $pdf->SetX(10);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(92, 4, ("$" . " " . number_format($salario, 2, ',', '.')), 0, 1, 'R', 0);
//    
//   
    $pdf->SetX(10);
    $pdf->SetFont('Amble-Regular', '', 9);
    $pdf->Cell(170, 4, utf8_decode("DIAS TRABAJADOS EN EL PERIODO: "), 0, 0, 'L', 0);
    $pdf->SetX(10);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(83, 4, ($dias_trabajados), 0, 1, 'R', 0);
    $pdf->SetX(70);
    $pdf->SetFont('Arial', 'U', 9);
    $pdf->Cell(270, 4, "INGRESOS", 0, 0, 'L', 0);
    $pdf->SetFont('Amble-Regular', '', 9);
    $pdf->SetX(10);
    $pdf->Cell(61, 15, utf8_decode("REMUNERACIÓN B.U DEL PERIODO"), 0, 0, 'L', 0);
    $pdf->Cell(23, 15, ("$" . " " . number_format($sueldo_empleado, 3, ',', '.')), 0, 0, 'R', 0);
    $pdf->SetX(10);
    $pdf->Cell(70, 25, "HORAS EXTRAS", 0, 0, 'L', 0);
    $pdf->Cell(12, 25, ("$" . " " . number_format(($horas_extras), 3, ',', '.')), 0, 0, 'R', 0);

    $pdf->SetX(10);
    $pdf->Cell(70, 35, "FONDO DE RESERVA", 0, 0, 'L', 0);
    $pdf->Cell(12, 35, ("$" . " " . number_format(($fondo_reserva), 3, ',', '.')), 0, 0, 'R', 0);
    $pdf->SetX(10);
    $pdf->Cell(70, 45, "COMISIONES VENTA CUPO MENSUAL", 0, 0, 'L', 0);
    $pdf->Cell(12, 45, ("$" . " " . number_format(($comisiones_cupo_mensu), 3, ',', '.')), 0, 0, 'R', 0);
    $pdf->SetX(10);
    $pdf->Cell(70, 55, "COMISIONES POR PROMOCIONALES", 0, 0, 'L', 0);
    $pdf->Cell(12, 55, ("$" . " " . number_format(($comiciones_promociones), 3, ',', '.')), 0, 0, 'R', 0);

    $pdf->SetX(10);
    $pdf->Cell(70, 65, "OTROS INGRESOS", 0, 0, 'L', 0);
    $pdf->Cell(12, 65, ("$" . " " . number_format(($otros_ingresos), 3, ',', '.')), 0, 0, 'R', 0);
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetLineWidth(0.4);
    $pdf->Line(10, 107, 200, 107);
    $pdf->SetX(10);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(70, 75, "TOTAL INGRESOS", 0, 0, 'L', 0);
    $pdf->Cell(12, 75, ("$" . " " . number_format(($total_ingresos), 3, ',', '.')), 0, 0, 'R', 0);

/////////////////////////////////////////////////////////////////
/////////////////////////////////////

    $pdf->SetX(160);
    $pdf->SetFont('Arial', 'U', 9);
    $pdf->Cell(270, 6, "DESCUENTOS", 0, 0, 'L', 0);
    $pdf->SetFont('Amble-Regular', '', 9);
    $pdf->SetX(110);
    $pdf->Cell(61, 15, utf8_decode("APORTE INDIVIDUAL IESS 9,45%"), 0, 0, 'L', 0);
    $pdf->Cell(23, 15, ("$" . " " . number_format($aporte_personal, 3, ',', '.')), 0, 0, 'R', 0);
    $pdf->SetX(110);
    $pdf->Cell(70, 25, "PRESTAMOS QUIROGRAFARIOS IESS", 0, 0, 'L', 0);
    $pdf->Cell(12, 25, ("$" . " " . number_format(($prestamos_qui_iess), 3, ',', '.')), 0, 0, 'R', 0);

    $pdf->SetX(110);
    $pdf->Cell(70, 35, "CREDITO PERSONAL", 0, 0, 'L', 0);
    $pdf->Cell(12, 35, ("$" . " " . number_format(($credito_personal), 3, ',', '.')), 0, 0, 'R', 0);
    $pdf->SetX(110);
    $pdf->Cell(70, 45, "ANTICIPOS Y CONSUMOS", 0, 0, 'L', 0);
    $pdf->Cell(12, 45, ("$" . " " . number_format(($total_anticipos), 3, ',', '.')), 0, 0, 'R', 0);
    $pdf->SetX(110);
    $pdf->Cell(70, 55, "FALTANTES DE CAJA", 0, 0, 'L', 0);
    $pdf->Cell(12, 55, ("$" . " " . number_format(($faltantes_caja), 3, ',', '.')), 0, 0, 'R', 0);
    $pdf->SetX(110);
    $pdf->Cell(70, 65, "VARIOS", 0, 0, 'L', 0);
    $pdf->Cell(12, 65, ("$" . " " . number_format(($otros_descuentos), 3, ',', '.')), 0, 0, 'R', 0);


    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetLineWidth(0.4);
    $pdf->Line(10, 87, 200, 87);
    $pdf->SetX(110);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(70, 75, "TOTAL DESCUENTOS", 0, 0, 'L', 0);
    $pdf->Cell(12, 75, ("$" . " " . number_format(($total_deduccion), 3, ',', '.')), 0, 0, 'R', 0);
    $total = 0;

    $pdf->SetX(10);
    $y = $pdf->GetY();
    $x = $pdf->GetX();
    $pdf->SetY($y + 40);
    $pdf->SetX($x);
    $pdf->multiCell(183, 6, utf8_decode("NETO A RECIBIR PRESENTE MES---->" . "                                                                                                                           " . "$ " . $neto_recibir), 1);


//////////////////////////////////////////////////
//////////////////////7
    $pdf->SetX(10);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(70, 7, "APORTE PATRONAL IESS 12,15%", 0, 0, 'L', 0);
    $pdf->Cell(12, 7, ("$" . " " . number_format(($aporte_patronal), 3, ',', '.')), 0, 0, 'R', 0);
    $pdf->SetX(10);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(70, 15, "TOTAL APORTES AL 21,60%", 0, 0, 'L', 0);
    $pdf->Cell(12, 15, ("$" . " " . number_format(($total_aportes), 3, ',', '.')), 0, 0, 'R', 0);

////////////////////////////////////////////////////////////777
//////////////////////////7777

    $pdf->SetX(10);
    $y = $pdf->GetY();
    $x = $pdf->GetX();
    $pdf->SetY($y + 12);
    $pdf->SetX($x);
    $pdf->multiCell(100, 6, utf8_decode("Certifico que he recibido a entera satisfacciòn los valores contenidos en el presente comprobante"
                    . "por pago de remuneraciones del mes indicado, por lo cual no tengo ningùn cargo o reclamo posterior que efectuar a mi empleador."), 1);

//////////////////

    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetLineWidth(0.4);
    $pdf->Line(120, 120, 190, 120);

    $pdf->SetX(130);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(70, -20, "(f.)RECIBI CONFORME", 0, 0, 'L', 0);

    $pdf->SetX(120);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(80, -5, "C.C______________________________", 0, 0, 'L', 0);


//////////////////////////////PAGINA SIGUIENTE
////////////////////////////////7




    $pdf->Ln(6);
    $pdf->SetX(30);
    $pdf->SetY(140);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(213, 8, $_SESSION['empresa'], 0, 1, 'C', 0);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(190, 4, utf8_decode($_SESSION['propietario']), 0, 1, 'C', 0);
    $pdf->SetFont('Amble-Regular', '', 9);
    $pdf->Cell(190, 4, "DIR.: " . utf8_decode($_SESSION['direccion']), 0, 1, 'C', 0);
    $pdf->Cell(190, 4, "RUC.: " . utf8_decode($_SESSION['ruc_cedula']), 0, 1, 'C', 0);

    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetLineWidth(0.4);
//            $this->Line(1,50,210,50);            
    $pdf->SetFont('Arial', 'B', 10);

    $pdf->Cell(200, 5, utf8_decode("ROL DE PAGOS INDIVIDUAL"), 0, 1, 'C', 0);

    $pdf->SetFont('Amble-Regular', '', 10);
    $pdf->Ln(3);
    $pdf->SetFillColor(255, 255, 225);
    $pdf->SetLineWidth(0.2);

    $pdf->SetX(10);
    $pdf->SetFont('Amble-Regular', '', 9);
    $pdf->Cell(170, 5, "CORRESPONDIENTE AL MES DE:", 0, 0, 'L', 0);
    $pdf->SetX(10);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(100, 5, ($mes . ' del ' . $anno), 0, 1, 'R', 0);

    $pdf->SetX(10);
    $pdf->SetFont('Amble-Regular', '', 9);
    $pdf->Cell(170, 4, "EMPLEADO / APELLIDOS Y NOMBRES:", 0, 0, 'L', 0);
    $pdf->SetX(10);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(116, 4, ($nombres_nomina), 0, 1, 'R', 0);

    $pdf->SetX(10);
    $pdf->SetFont('Amble-Regular', '', 9);
    $pdf->Cell(170, 6, utf8_decode("CARGO / ACTIVIDAD SECTORIAL:"), 0, 0, 'L', 0);
    $pdf->SetX(10);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(105, 4, ($cargo), 0, 1, 'R', 0);

    $pdf->SetX(10);
    $pdf->SetFont('Amble-Regular', '', 9);
    $pdf->Cell(170, 6, utf8_decode("SALARIO MÍNIMO SECTORIAL"), 0, 0, 'L', 0);
    $pdf->SetX(10);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(92, 4, ("$" . " " . number_format($salario, 2, ',', '.')), 0, 1, 'R', 0);
//    
//   
    $pdf->SetX(10);
    $pdf->SetFont('Amble-Regular', '', 9);
    $pdf->Cell(170, 4, utf8_decode("DIAS TRABAJADOS EN EL PERIODO: "), 0, 0, 'L', 0);
    $pdf->SetX(10);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(83, 4, ($dias_trabajados), 0, 1, 'R', 0);
    $pdf->SetX(70);
    $pdf->SetFont('Arial', 'U', 9);
    $pdf->Cell(270, 4, "INGRESOS", 0, 0, 'L', 0);
    $pdf->SetFont('Amble-Regular', '', 9);
    $pdf->SetX(10);
    $pdf->Cell(61, 15, utf8_decode("REMUNERACIÓN B.U DEL PERIODO"), 0, 0, 'L', 0);
    $pdf->Cell(23, 15, ("$" . " " . number_format($sueldo_empleado, 3, ',', '.')), 0, 0, 'R', 0);
    $pdf->SetX(10);
    $pdf->Cell(70, 25, "HORAS EXTRAS", 0, 0, 'L', 0);
    $pdf->Cell(12, 25, ("$" . " " . number_format(($horas_extras), 3, ',', '.')), 0, 0, 'R', 0);

    $pdf->SetX(10);
    $pdf->Cell(70, 35, "FONDO DE RESERVA", 0, 0, 'L', 0);
    $pdf->Cell(12, 35, ("$" . " " . number_format(($fondo_reserva), 3, ',', '.')), 0, 0, 'R', 0);
    $pdf->SetX(10);
    $pdf->Cell(70, 45, "COMISIONES VENTA CUPO MENSUAL", 0, 0, 'L', 0);
    $pdf->Cell(12, 45, ("$" . " " . number_format(($comisiones_cupo_mensu), 3, ',', '.')), 0, 0, 'R', 0);
    $pdf->SetX(10);
    $pdf->Cell(70, 55, "COMISIONES POR PROMOCIONALES", 0, 0, 'L', 0);
    $pdf->Cell(12, 55, ("$" . " " . number_format(($comiciones_promociones), 3, ',', '.')), 0, 0, 'R', 0);

    $pdf->SetX(10);
    $pdf->Cell(70, 65, "OTROS INGRESOS", 0, 0, 'L', 0);
    $pdf->Cell(12, 65, ("$" . " " . number_format(($otros_ingresos), 3, ',', '.')), 0, 0, 'R', 0);
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetLineWidth(0.4);
    $pdf->Line(10, 107, 200, 107);
    $pdf->SetX(10);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(70, 75, "TOTAL INGRESOS", 0, 0, 'L', 0);
    $pdf->Cell(12, 75, ("$" . " " . number_format(($total_ingresos), 3, ',', '.')), 0, 0, 'R', 0);

/////////////////////////////////////////////////////////////////
/////////////////////////////////////

    $pdf->SetX(160);
    $pdf->SetFont('Arial', 'U', 9);
    $pdf->Cell(270, 6, "DESCUENTOS", 0, 0, 'L', 0);
    $pdf->SetFont('Amble-Regular', '', 9);
    $pdf->SetX(110);
    $pdf->Cell(61, 15, utf8_decode("APORTE INDIVIDUAL IESS 9,45%"), 0, 0, 'L', 0);
    $pdf->Cell(23, 15, ("$" . " " . number_format($aporte_personal, 3, ',', '.')), 0, 0, 'R', 0);
    $pdf->SetX(110);
    $pdf->Cell(70, 25, "PRESTAMOS QUIROGRAFARIOS IESS", 0, 0, 'L', 0);
    $pdf->Cell(12, 25, ("$" . " " . number_format(($prestamos_qui_iess), 3, ',', '.')), 0, 0, 'R', 0);

    $pdf->SetX(110);
    $pdf->Cell(70, 35, "CREDITO PERSONAL", 0, 0, 'L', 0);
    $pdf->Cell(12, 35, ("$" . " " . number_format(($credito_personal), 3, ',', '.')), 0, 0, 'R', 0);
    $pdf->SetX(110);
    $pdf->Cell(70, 45, "ANTICIPOS Y CONSUMOS", 0, 0, 'L', 0);
    $pdf->Cell(12, 45, ("$" . " " . number_format(($total_anticipos), 3, ',', '.')), 0, 0, 'R', 0);
    $pdf->SetX(110);
    $pdf->Cell(70, 55, "FALTANTES DE CAJA", 0, 0, 'L', 0);
    $pdf->Cell(12, 55, ("$" . " " . number_format(($faltantes_caja), 3, ',', '.')), 0, 0, 'R', 0);
    $pdf->SetX(110);
    $pdf->Cell(70, 65, "VARIOS", 0, 0, 'L', 0);
    $pdf->Cell(12, 65, ("$" . " " . number_format(($otros_descuentos), 3, ',', '.')), 0, 0, 'R', 0);


    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetLineWidth(0.4);
$pdf->Line(10, 223, 200, 223);
    $pdf->SetX(110);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(70, 75, "TOTAL DESCUENTOS", 0, 0, 'L', 0);
    $pdf->Cell(12, 75, ("$" . " " . number_format(($total_deduccion), 3, ',', '.')), 0, 0, 'R', 0);
    $total = 0;

    $pdf->SetX(10);
    $y = $pdf->GetY();
    $x = $pdf->GetX();
    $pdf->SetY($y + 40);
    $pdf->SetX($x);
    $pdf->multiCell(183, 6, utf8_decode("NETO A RECIBIR PRESENTE MES---->" . "                                                                                                                           " . "$ " . $neto_recibir), 1);


//////////////////////////////////////////////////
//////////////////////7
    $pdf->SetX(10);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(70, 7, "APORTE PATRONAL IESS 12,15%", 0, 0, 'L', 0);
    $pdf->Cell(12, 7, ("$" . " " . number_format(($aporte_patronal), 3, ',', '.')), 0, 0, 'R', 0);
    $pdf->SetX(10);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(70, 15, "TOTAL APORTES AL 21,60%", 0, 0, 'L', 0);
    $pdf->Cell(12, 15, ("$" . " " . number_format(($total_aportes), 3, ',', '.')), 0, 0, 'R', 0);

////////////////////////////////////////////////////////////777
//////////////////////////7777

    $pdf->SetX(10);
    $y = $pdf->GetY();
    $x = $pdf->GetX();
    $pdf->SetY($y + 12);
    $pdf->SetX($x);
    $pdf->multiCell(100, 6, utf8_decode("Certifico que he recibido aentera satisfacciòn los valores contenidos en el presente comprobante"
                    . "por pago de remuneraciones del mes indicado, por lo cual no tengo ningùn cargo o reclamo posterior que efectuar a mi empleador."), 1);

//////////////////

    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetLineWidth(0.4);
    $pdf->Line(120, 120, 190, 120);

    $pdf->SetX(130);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(70, -20, "(f.)RECIBI CONFORME", 0, 0, 'L', 0);

    $pdf->SetX(120);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(80, -5, "C.C______________________________", 0, 0, 'L', 0);
}
$pdf->Output();
?>