<?php

require('../fpdf/fpdf.php');
include '../procesos/base.php';
include '../procesos/funciones.php';
conectarse();
date_default_timezone_set('America/Guayaquil');
session_start();
$anio = date('Y', time());

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
        //$this->Cell(20, 5, $fecha, 0,0, 'C', 0);                                                                                               
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        //$this->Cell(0,10,'Pag. '.$this->PageNo().'/{nb}',0,0,'C');
    }

}

//$pdf = new PDF('P','cm','Legal');
$pdf = new PDF('L', 'mm', array(200, 145));
//$pdf = new PDF('L','mm','a5');
$pdf->AddPage();
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AliasNbPages();
$pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
$pdf->SetFont('Amble-Regular', '', 10);
$pdf->SetFont('Arial', '', 9);
$pdf->SetX(10);
$pdf->SetY(46);
$sql = pg_query("select p.empresa_pro,p.identificacion_pro, p.direccion_pro,p.ciudad,p.celular
from retencion_fuente_factura_compra rf, proveedores p, gastos fc
 where fc.id_gastos = rf.id_factura and fc.id_proveedor=p.id_proveedor and fc.id_gastos='$_GET[id]' limit 1");
while ($row = pg_fetch_row($sql)) {
    $pdf->SetX(27);
    $pdf->Cell(130, 5, maxCaracter(utf8_decode($row[0]), 80), 0, 0, 'L', 0);
    $pdf->Ln(5);
    $pdf->SetX(27);
    $pdf->Cell(30, 5, maxCaracter(utf8_decode($row[1]), 25), 0, 0, 'L', 0);
    $pdf->Ln(5);
    $pdf->SetX(27);
    $pdf->Cell(85, 5, maxCaracter(utf8_decode($row[2]), 20), 0, 0, 'L', 0);
    $pdf->Ln(5);
    $pdf->SetX(27);
    $pdf->Cell(85, 5, maxCaracter(utf8_decode($row[3]), 20), 0, 0, 'L', 0);
    $pdf->Ln(5);
    $pdf->SetX(27);
    $pdf->Cell(125, 5, maxCaracter(utf8_decode($row[4]), 20), 0, 0, 'L', 0);
    $r = substr($row[3], 8, 9);
    $pdf->Ln(6);
    $pdf->SetX(32);
    $pdf->Ln(5);
}
$pdf->SetFont('Amble-Regular', '', 12);
$total = 0;
$fuente = 0;
$iva = 0;
$codigo=0;
$base=0;
$impuesto=0;


$pdf->SetY(80);
$sql = pg_query("select  CD.porsentaje,
       CD.base_imponible,         
       CD.valor_retenido
       from retencion_fuente_factura_compra CR 
       inner join detallecomprobanteretencion CD on CR.id_retencion_fuente_factura_compra = CD.id_retencion_fuente_factura_compra 
       inner join tipo_retencion R on CD.id_trete = R.id_trete 
       inner join retencion_fuentes TR on CD.id_retencion_fuentes = TR.id_retencion_fuentes where CR.id_factura = '$_GET[id]' and CR.id_gastos='10'");
while ($row = pg_fetch_row($sql)) {
    $pdf->SetX(40);
    $pdf->Cell(40, 5, maxCaracter(utf8_decode($row[0]), 20), 0, 0, 'C', 0);
    $pdf->Cell(40, 5, maxCaracter(utf8_decode($row[1]), 25), 0, 0, 'C', 0);
    $pdf->Cell(40, 5, maxCaracter(utf8_decode($row[2]), 20), 0, 0, 'C', 0);
 $codigo += $row[0];
   $base += $row[1];
     $impuesto += $row[2];
    $pdf->Ln(5);
}

 $pdf->Ln(5);
 $pdf->SetX(40);
 
 
    $pdf->Cell(40, 5, (""), 0, 0, 'C', 0);
    $pdf->Cell(40, 5, maxCaracter(utf8_decode($base), 25), 0, 0, 'C', 0);

    $pdf->Cell(40, 5, maxCaracter(utf8_decode($impuesto), 20), 0, 0, 'C', 0);

  
   
/* $sql=pg_query("select factura_compra.descuento_compra,factura_compra.tarifa0,factura_compra.tarifa12,factura_compra.iva_compra,factura_compra.total_compra from factura_compra,detalle_factura_compra,productos where factura_compra.id_factura_compra=detalle_factura_compra.id_factura_compra and detalle_factura_compra.cod_productos=productos.cod_productos and detalle_factura_compra.id_factura_compra='10' LIMIT 1");    
  while($row=pg_fetch_row($sql)){
  $pdf->Cell(173, 6, utf8_decode("Descuento"),0,0, 'R',0);
  $pdf->Cell(35, 6, number_format(round($row[0],2),2,'.',''),0,1, 'C',0);
  $pdf->Cell(173, 6, utf8_decode("Tarifa 0"),0,0, 'R',0);
  $pdf->Cell(35, 6, number_format(round($row[1],2),2,'.',''),0,1, 'C',0);
  $pdf->Cell(173, 6, utf8_decode("Tarifa IVA"),0,0, 'R',0);
  $pdf->Cell(35, 6, number_format(round($row[2],2),2,'.',''),0,1, 'C',0);
  $pdf->Cell(173, 6, utf8_decode("Iva ...%"),0,0, 'R',0);
  $pdf->Cell(35, 6, number_format(round($row[3],2),2,'.',''),0,1, 'C',0);
  $pdf->Cell(173, 6, utf8_decode("Total"),0,0, 'R',0);
  $pdf->Cell(35, 6, number_format(round($row[4],2),2,'.',''),0,1, 'C',0);
  } */
//////////
$pdf->Output();
?>
