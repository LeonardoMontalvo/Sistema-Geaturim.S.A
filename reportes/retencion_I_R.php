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
        $this->Cell(105, 5, "TESORERIA", 0, 1, 'C', 0);
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
        $this->Cell(210, 5, utf8_decode("REPORTE DE RETENCIONES IMPUESTO RENTA DE  FACTURA COMPRA"), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->Ln(3);
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
$pdf->SetMargins(0, 0, 0, 0);
$pdf->SetTitle('Retenciones Fuente');
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Amble-Regular', '', 9);

$subVAR_total = 0;
$sub_total = 0;
$total = 0;
$desc = 0;
$ivaT = 0;
$repetido = 0;
$contador = 0;
$subVAR_t = 0;
$sub_t = 0;
$codigo_reten = '';
$consulta = pg_query('select * from retencion_fuentes order by id_retencion_fuentes asc');
while ($row = pg_fetch_row($consulta)) {
    $total = 0;
    $subVAR = 0;
    $sub = 0;
    $saldo = 0;
    $repetido = 0;
    $contador = 0;
    $num_fact = 0;
    $sql1 = pg_query("select fc.num_serie, p.empresa_pro, rfc.fecha, rfc.valor_compra, rf.valor, rfc.valor_retencion,p.identificacion_pro, rf.valor from factura_compra fc, retencion_fuentes rf, retencion_fuente_factura_compra rfc, proveedores p where fc.id_factura_compra=rfc.id_factura and fc.id_proveedor=p.id_proveedor and rfc.id_retencion_fuente=rf.id_retencion_fuentes and rfc.id_retencion_fuente='$row[0]' and fc.fecha_emision between '$_GET[inicio]' and '$_GET[fin]'  and  rfc.id_gastos='1' order by rfc.id_retencion_fuente_factura_compra");
    if (pg_num_rows($sql1) > 0) {
        while ($row1 = pg_fetch_row($sql1)) {
            if ($repetido == 0) {
                $pdf->SetX(1);
                $pdf->SetFillColor(187, 179, 180);
                $pdf->Cell(70, 6, (('RETENCION:' . $row[4] . '        -       ' . $row1[7] . '%')), 1, 0, 'L', 1);
                $pdf->Cell(135, 6, maxCaracter(utf8_decode('NOMBRES:' . $row[1]), 50), 1, 1, 'L', 1);
                $pdf->Ln(2);
                $pdf->SetX(1);
                $pdf->Cell(70, 6, utf8_decode('PROVEEDOR'), 1, 0, 'L', 0);

                $pdf->Cell(27, 6, utf8_decode('IDENTIFICACION'), 1, 0, 'C', 0);
                $pdf->Cell(35, 6, utf8_decode('FECHA'), 1, 0, 'C', 0);
                $pdf->Cell(25, 6, utf8_decode('NUM FACTURA'), 1, 0, 'C', 0);
                $pdf->Cell(25, 6, utf8_decode('BASE'), 1, 0, 'C', 0);
                $pdf->Cell(25, 6, utf8_decode('RETENIDO'), 1, 1, 'C', 0);


                $repetido = 1;
                $contador = 1;
            }
            $valorfactura = explode(('-'), $row1[0]);
            $valorfacturaini = $valorfactura[2];
            $valorfecha = explode(('T'), $row1[2]);
            $valorfacturainifactura = $valorfecha[0];

            $codigo_reten = $row[4];


            $pdf->Cell(70, 6, maxCaracter($row1[1], 40), 0, 0, 'L', 0);
            $pdf->Cell(27, 6, utf8_decode($row1[6]), 0, 0, 'C', 0);
            $pdf->Cell(35, 6, ($valorfacturainifactura), 0, 0, 'C', 0);
            $pdf->Cell(25, 6, ($valorfacturaini), 0, 0, 'C', 0);
            $pdf->Cell(25, 6, number_format($row1[3], 2, '.', ''), 0, 0, 'C', 0);
            $pdf->Cell(25, 6, utf8_decode($row1[5]), 0, 1, 'C', 0);

            $subVAR = $subVAR + $row1[3];
            $sub = $sub + $row1[5];
            $subVAR_total = $subVAR_total + $row1[3];
            $sub_total = $sub_total + $row1[5];
        }

        $pdf->SetX(1);
        $pdf->Cell(207, 0, utf8_decode(""), 1, 1, 'R', 0);
        $pdf->Cell(157, 6, utf8_decode("TOTALES:"), 0, 0, 'R', 0);
        $pdf->Cell(24, 6, maxCaracter((number_format($subVAR, 2, ',', '.')), 20), 0, 0, 'C', 0);
        $pdf->Cell(25, 6, maxCaracter((number_format($sub, 2, ',', '.')), 20), 0, 1, 'C', 0);
        $pdf->Ln(3);
    }
}

$pdf->SetX(1);
$pdf->Cell(207, 0, utf8_decode(""), 1, 1, 'R', 0);
$pdf->Cell(157, 6, utf8_decode("TOTALES:"), 0, 0, 'R', 0);
$pdf->Cell(24, 6, maxCaracter((number_format($subVAR_total, 2, ',', '.')), 20), 0, 0, 'C', 0);
$pdf->Cell(25, 6, maxCaracter((number_format($sub_total, 2, ',', '.')), 20), 0, 1, 'C', 0);
$pdf->Ln(3);

$pdf->SetX(30);
$pdf->SetFillColor(187, 179, 180);
$pdf->Cell(70, 6, maxCaracter(utf8_decode('CONSOLIDADO:'), 35), 0, 1, 'L', 1);
$pdf->Ln(2);
$pdf->SetX(30);
$pdf->Cell(20, 6, utf8_decode('CODIGO'), 1, 0, 'L', 0);
$pdf->Cell(27, 6, utf8_decode('BASE'), 1, 0, 'C', 0);
$pdf->Cell(35, 6, utf8_decode('%'), 1, 0, 'C', 0);
$pdf->Cell(25, 6, utf8_decode('RETENIDO'), 1, 1, 'C', 0);
$pdf->SetX(30);

$consulta1 = pg_query('select * from retencion_fuentes order by id_retencion_fuentes asc');
while ($row = pg_fetch_row($consulta1)) {
    $total = 0;
    $saldo = 0;
    $repetido = 0;
    $contador = 0;
    $num_fact = 0;
    $subVAR = 0;
    $sub = 0;

    $sql1 = pg_query("select fc.num_serie, p.empresa_pro, rfc.fecha, rfc.valor_compra, rf.valor, rfc.valor_retencion,p.identificacion_pro,rf.codigo_formulario from factura_compra fc, retencion_fuentes rf, retencion_fuente_factura_compra rfc, proveedores p where fc.id_factura_compra=rfc.id_factura and fc.id_proveedor=p.id_proveedor and rfc.id_retencion_fuente=rf.id_retencion_fuentes and rfc.id_retencion_fuente='$row[0]' and fc.fecha_emision between '$_GET[inicio]' and '$_GET[fin]'  and  rfc.id_gastos='1' order by rfc.id_retencion_fuente_factura_compra");
    if (pg_num_rows($sql1) > 0) {
        while ($row1 = pg_fetch_row($sql1)) {
            $subVAR = $subVAR + $row1[3];
            $sub = $sub + $row1[5];
            $row1p_codigo =  $row1[7];
            $row1p_retencion =  $row1[4];
        }
        $pdf->SetX(30);

        $pdf->Cell(20, 6, utf8_decode($row1p_codigo), 0, 0, 'L', 0);
        $pdf->Cell(27, 6, utf8_decode($subVAR), 0, 0, 'C', 0);
        $pdf->Cell(35, 6, utf8_decode($row1p_retencion), 0, 0, 'C', 0);
        $pdf->Cell(25, 6, ($sub), 0, 1, 'C', 0);
    }
}
$pdf->SetX(1);
$pdf->Cell(150, 0, utf8_decode(""), 1, 1, 'R', 0);
$pdf->Cell(25, 6, utf8_decode("TOTALES:"), 0, 0, 'R', 0);
$pdf->Cell(70, 6, maxCaracter((number_format($subVAR_total, 2, ',', '.')), 20), 0, 0, 'C', 0);
$pdf->Cell(35, 6, maxCaracter((number_format($sub_total, 2, ',', '.')), 20), 0, 1, 'R', 0);
$pdf->Ln(3);
//             $sql1=pg_query("  select  DISTINCT ON (rf.codigo_formulario)  fc.num_serie, p.empresa_pro, rfc.fecha, rfc.valor_compra, rf.valor, rfc.valor_retencion,p.identificacion_pro, rf.codigo_formulario from factura_compra fc, retencion_fuentes rf, retencion_fuente_factura_compra rfc, proveedores p where fc.id_factura_compra=rfc.id_factura and fc.id_proveedor=p.id_proveedor and rfc.id_retencion_fuente=rf.id_retencion_fuentes  and rfc.fecha between '$_GET[inicio]' and '$_GET[fin]' and (rfc.estado='2'  or  rfc.estado='1') and  rfc.id_gastos='1'  ");   
//                    if(pg_num_rows($sql1)>0){
//                    while($row1p=pg_fetch_row($sql1)) {                                        
//                    $row1p_codigo=  $row1p[7];   
//                    $row1p_retencion=  $row1p[4]; 
//                
//                   $pdf->SetX(30); 
//                    $pdf->Cell(20, 6, utf8_decode($row1p_codigo),0,0, 'L',0);                                     
//                    $pdf->Cell(27, 6, utf8_decode(''),0,0, 'C',0);                                     
//                    $pdf->Cell(35, 6, utf8_decode($row1p_retencion),0,0, 'C',0);                                         
//                    $pdf->Cell(25, 6, (''),0,1, 'C',0);   
//         }
//                   
//       }










$pdf->Output();
