<?php
//        include '../fpdf/rotation.php';        
//        include("../fpdf/barcode.inc.php");
//        include '../procesos/base.php';
include __DIR__ . '/../../../fpdf/rotation.php';
include( __DIR__ . "/../../../fpdf/barcode.inc.php");
require_once(__DIR__ . '/../../../procesos/base.php');

//                require_once( '../../procesos/funciones.php');
//error_reporting(0);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class PDF extends FPDF {

    var $widths;
    var $aligns;

    function SetWidths($w) {
        //Set the array of column widths

        $this->widths = $w;
    }

    function SetAligns($a) {
        //Set the array of column alignments

        $this->aligns = $a;
    }

    function Row($data) {
        //Calculate the height of the row

        $nb = 0;

        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));

        $h = 5 * $nb;
        //Issue a page break first if needed

        $this->CheckPageBreak($h);
        //Draw the cells of the row

        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];

            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            //Save the current position

            $x = $this->GetX();

            $y = $this->GetY();
            //Draw the border
        //$this->Rect($x,$y,$w,$h);


            $this->MultiCell($w, 5, $data[$i], 0, $a, false);
            //Put the position to the right of the cell

            $this->SetXY($x + $w, $y);
        }
        //Go to the next line

        $this->Ln($h);
    }

    function CheckPageBreak($h) {
        //If the height h would cause an overflow, add a new page immediately

        if ($this->GetY() + $h > $this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w, $txt) {
        //Computes the number of lines a MultiCell of width w will take

        $cw = &$this->CurrentFont['cw'];

        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;

        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;

        $s = str_replace("\r", '', $txt);

        $nb = strlen($s);

        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb--;

        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;

        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {

                $i++;

                $sep = -1;

                $j = $i;

                $l = 0;

                $nl++;

                continue;
            }

            if ($c == ' ')
                $sep = $i;

            $l += $cw[$c];

            if ($l > $wmax) {

                if ($sep == -1) {

                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;

                $sep = -1;

                $j = $i;

                $l = 0;

                $nl++;
            } else
                $i++;
        }

        return $nl;
    }

    function GetCurrentWidth()
    {
        return $this->w - ($this->lMargin * 2);
    }

}

$gdescuento=0;
$pdf = new PDF('P', 'mm', array(77, 200));
date_default_timezone_set('America/Guayaquil');

$fecha = date('Y-m-d H:i:s', time());

$pdf->AddPage();

$pdf->SetMargins(0, 0, 0, 0);
$pdf->Ln(0);

$pdf->SetFont('Arial', '', 7);
$id = 0;
$id = $_GET['id'];

$sql = pg_query("select * from empresa left join factura_venta on empresa.id_empresa  = factura_venta.id_empresa left join clientes on factura_venta.id_cliente=clientes.id_cliente left join tipo_documento on tipo_documento.id_tdocu=clientes.id_tdocu where factura_venta.id_factura_venta='" . $id . "' ");

$numfilas = pg_num_rows($sql);
list($width, $height, $type, $attr) = getimagesize('../../../images/'.$_SESSION["parametros_empresa"]["logo_empresa"]);
$offsety=10;
if($height==$width){
    $offsety=20;
}
$valory=$offsety;

$pdf->Image('../../../images/'.$_SESSION["parametros_empresa"]["logo_empresa"], 30, 5, 20); // Img Empresa



for ($i = 0; $i < $numfilas; $i++) {


    $fila = pg_fetch_row($sql);

    $pdf->SetFont('Arial', '', 8);

    $pdf->SetX(0);
    $pdf->SetY(7+$valory);
    //$pdf->Text(20, 10, strtoupper($fila[1]), 0, 0, 'C', 0);
    $pdf->Cell($pdf->GetCurrentWidth(),5,strtoupper($fila[1]),0,1,'C');
    //$pdf->Ln(1);

    $valor_x=5;

    $pdf->Text(20, 14+$valory, utf8_decode('' . "RUC:"), 0, 'C', 0); ////CLIENTE (X,Y)   
    $pdf->Text(27, 14+$valory, utf8_decode('' . strtoupper($fila[2])), 0, 'C', 0); ////CLIENTE (X,Y)
    /* $pdf->SetX(0);
    $pdf->Cell($pdf->GetCurrentWidth(),5,"RUC: ".strtoupper($fila[3]),0,1,'C'); */

    $pdf->SetFont('Arial', '', 7);
    $pdf->Text(6, 18+$valory, utf8_decode('' . ""), 0, 'C', 0); ////CLIENTE (X,Y)   
    $pdf->Text($valor_x, 18+$valory, utf8_decode('' . strtoupper($fila[3])), 0, 'C', 0); ////CLIENTE (X,Y)
    $pdf->SetFont('Arial', '', 8);

    //$pdf->Text(20, 22, utf8_decode('' . "NUM ORDEN:"), 0, 'C', 0); ////CLIENTE (X,Y)   
    //$pdf->Text(48, 22, utf8_decode('' . strtoupper($fila[28])), 0, 'C', 0); ////CLIENTE (X,Y)
      $pdf->Text(20, 22+$offsety, utf8_decode('' . "Telf:"), 0, 'C', 0); ////CLIENTE (X,Y)   

    $pdf->Text(26, 22+$offsety, utf8_decode('' . strtoupper($fila[4])), 0, 'C', 0); ////CLIENTE (X,Y)
    $pdf->SetY(19+$valory);
    $nro=strtoupper($fila[28]);
    $mesa=nroMesaFactura($nro);
    if(!empty($mesa)){
        $mesa=" - MESA: ".$mesa;
    }
//    $pdf->Cell($pdf->GetCurrentWidth(),5,"NUM ORDEN: ". $nro.$mesa,0,1,'C');
    
    $pdf->SetFont('Arial', '', 8);
    $pdf->Text($valor_x, 26+$valory, utf8_decode('' . "E-MAIL:"), 0, 'C', 0); ////CLIENTE (X,Y)   
    $pdf->Text(21, 26+$valory, utf8_decode('' . $fila[9]), 0, 'C', 0); ////CLIENTE (X,Y)  



    $pdf->Text($valor_x, 30+$valory, utf8_decode('' . "Obligado a llevar Contabilidad: "), 0, 'C', 0); ////CLIENTE (X,Y)       
    $pdf->Text(51, 30+$valory, utf8_decode('' . strtoupper($fila[18])), 0, 'C', 0); ////CLIENTE (X,Y)
    $secuencial = $fila[29];
//    $ip = $secuencial;
//    $iparr = split("\-", $ip);
//    $secuencial = $iparr[2];
    $pdf->Text($valor_x, 34+$valory, utf8_decode('' . "FACTURA NRO.: "), 0, 'C', 0); ////CLIENTE (X,Y)       
    $pdf->Text(32, 34+$valory, utf8_decode('' . $fila[22] . '-' . $fila[23] . '-' . $secuencial), 0, 'C', 0); ////CLIENTE (X,Y)

    $pdf->Text($valor_x, 37+$valory, utf8_decode('' . "Nro.Autorizacion: "), 0, 'C', 0); ////CLIENTE (X,Y)       
// $pdf->Text(10,42,utf8_decode(''.strtoupper($fila[35])),0,'C', 0);////CLIENTE (X,Y)
    $pdf->SetY(38+$valory);
    $pdf->SetX($valor_x);
    if (strlen($fila[35]) > 50)
        $tam = 3;
    else
        $tam = 3;


    $numeroAutorizacion = $fila[35];
    if ($numeroAutorizacion == "") {
        $numeroAutorizacion = $fila[54];
    } else {
        $numeroAutorizacion = $fila[35];
    }

    $pdf->SetFont('Arial', '', 7);
    $pdf->multiCell(73, $tam, $numeroAutorizacion, 0);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Text($valor_x, 44+$valory, utf8_decode('' . "Clave de Acceso: "), 0, 'C', 0); ////CLIENTE (X,Y)     
    $pdf->SetY(46+$valory);
    $pdf->SetX($valor_x);
    if (strlen($fila[54]) > 50)
        $tam = 3;
    else
        $tam = 3;
    $pdf->SetFont('Arial', '', 7);
    $pdf->multiCell(73, $tam, $fila[54], 0);
    $consulta_ambiente = pg_query("select nombre_ambi from ambiente where estado_ambi='Activo'  ");
    while ($row = pg_fetch_row($consulta_ambiente)) {
        $nombre_ambi = $row[0];
    }
    $ambiente = $nombre_ambi;

    $pdf->Text($valor_x, 52+$valory, utf8_decode('' . "Ambiente:"), 0, 'C', 0); ////CLIENTE (X,Y)          
    $pdf->Text(26, 52+$valory, utf8_decode('' . ($ambiente)), 0, 'C', 0); ////CLIENTE (X,Y)
    $consulta_emision = pg_query("select nombre_temision from tipo_emision  where id_temision='1' ");
    while ($row = pg_fetch_row($consulta_emision)) {
        $nombre_emi = $row[0];
    }
    $emision = $nombre_emi;
    $pdf->Text(45, 52+$valory, utf8_decode('' . "Emision:"), 0, 'C', 0); ////CLIENTE (X,Y)          
    $pdf->Text(55, 52+$valory, utf8_decode('' . ($emision)), 0, 'C', 0); ////CLIENTE (X,Y)

    $pdf->Text($valor_x, 58+$valory, utf8_decode('' . "Cliente:"), 0, 'C', 0); ////CLIENTE (X,Y)          
    $pdf->Text(20, 58+$valory, utf8_decode('' . strtoupper($fila[66])), 0, 'C', 0); ////CLIENTE (X,Y)
    $pdf->Text($valor_x, 62+$valory, utf8_decode('' . "RUC/CI:"), 0, 'C', 0); ////CLIENTE (X,Y)          
    $pdf->Text(20, 62+$valory, utf8_decode('' . strtoupper($fila[65])), 0, 'C', 0); ////CLIENTE (X,Y)

    $pdf->Text($valor_x, 66+$valory, utf8_decode('' . "Direcciòn:"), 0, 'C', 0); ////CLIENTE (X,Y)          
    $pdf->Text(20, 66+$valory, utf8_decode('' . strtoupper($fila[68])), 0, 'C', 0); ////CLIENTE (X,Y)
    $pdf->Text($valor_x, 70+$valory, utf8_decode('' . "Telèfono:"), 0, 'C', 0); ////CLIENTE (X,Y)          
    $pdf->Text(20, 70+$valory, utf8_decode('' . strtoupper($fila[69])), 0, 'C', 0); ////CLIENTE (X,Y)
    $pdf->Text($valor_x+15, 73+$valory, utf8_decode('' . "Fecha de Emisión :"), 0, 'C', 0); ////CLIENTE (X,Y)   
    $fechaEmision = $row[39];
    $date = new DateTime($fechaEmision);
    $fechaEmision = $date->format('d/m/Y');
    $pdf->Text(45, 73+$valory, utf8_decode('' . strtoupper($fechaEmision)), 0, 'C', 0); ////CLIENTE (X,Y)



    $pdf->Ln(27);
}


$pdf->SetX(0);

$pdf->SetWidths(array(7, 39, 15, 25));

$sql = pg_query("select detalle_factura_venta.cantidad,productos.articulo,detalle_factura_venta.precio_venta,detalle_factura_venta.total_venta, productos.iva from factura_venta,detalle_factura_venta,productos where factura_venta.id_factura_venta=detalle_factura_venta.id_factura_venta and detalle_factura_venta.cod_productos=productos.cod_productos and detalle_factura_venta.id_factura_venta='" . $id . "'  order by detalle_factura_venta.id_detalle_venta asc");
$consulta_ambiente = pg_query("select nombre_ambi from ambiente where estado_ambi='Activo'");
while ($row = pg_fetch_row($consulta_ambiente)) {
    $nombre_ambi = $row[0];
}
$ambiente = $nombre_ambi;
$consulta_emision = pg_query("select nombre_temision from tipo_emision  ");
while ($row = pg_fetch_row($consulta_emision)) {
    $nombre_emi = $row[0];
}
$emision = $nombre_emi;


//$pdf->Row(array("Cant",utf8_decode("Descripcion"),"Pre.Uni","Total"));
$pdf->Text(4, 76+$valory, "CA");
$pdf->Text(13, 76+$valory, "DESCRIPCION");
$pdf->Text(47, 76+$valory, "P.UNIT");
$pdf->Text(62, 76+$valory, "V.TOTAL");

while ($fila = pg_fetch_row($sql)) {

    $pdf->SetX(4);

    $pdf->SetFont('Arial', '', 7);

    if ($fila[4] == "Si") {

        $sub = $fila[2];

        $total = $sub * $fila[0];
        $total = $total + 0;
        $total = number_format($total, 2, '.', '');
        $totalfila = 0;
        $totalfila = $fila[3];
        $totalfila = truncateFloat($fila[3], 2);

        $pdf->SetX(3);

        $pdf->Row(array(utf8_decode($fila[0]), maxCaracter(utf8_decode($fila[1]),23), utf8_decode(truncateFloat($sub, 2)), utf8_decode(truncateFloat(round($total, 2, PHP_ROUND_HALF_EVEN), 2) . "  *")));
    } else {

        $descripcion = utf8_decode($fila[1]);



        $pdf->SetX(3);

        $pdf->Row(array(utf8_decode($fila[0]), maxCaracter(utf8_decode($fila[1]), 23), utf8_decode(truncateFloat($fila[2], 2)), utf8_decode(truncateFloat(round($fila[3], 2, PHP_ROUND_HALF_EVEN), 2))));
    }
}


//PIE PAGINA	
$pdf->SetY(108+$valory);

$sql = pg_query("select tarifa0,tarifa12,iva_venta,descuento_venta,total_venta from factura_venta where id_factura_venta= '" . $id . "' ");

$sub0 = 0;

$sub12 = 0;

$iva = 0;

$total = 0;

while ($fila = pg_fetch_row($sql)) {

    if ($fila[4] < 1000) {

        $tar0 = truncateFloat(round($fila[0], 3, PHP_ROUND_HALF_EVEN), 3);

        $sub0 = truncateFloat(round($fila[1], 3, PHP_ROUND_HALF_EVEN), 2);

        $sub12 = truncateFloat(round($fila[2], 2, PHP_ROUND_HALF_EVEN), 2);

        $iva = truncateFloat(round($fila[3], 2, PHP_ROUND_HALF_EVEN), 2);

        $total = truncateFloat(round($fila[4], 3, PHP_ROUND_HALF_EVEN), 3);

        $pdf->SetFont('Arial', '', 8);

        $sub_total = $sub0 + $tar0;

        $tar0 = $tar0 + 0;

        $sub = $sub_total;

        $total = $total + 0;
        $total = number_format($total, 2, '.', '');

        $pdf->SetX(35);
        $pdf->SetAligns(array('l', 'R'));
        $pdf->SetWidths(array(22, 15));

        $pdf->Row(array("Tarifa 15%", $sub0));

        $pdf->SetX(35);

        $pdf->SetWidths(array(22, 15));

        $pdf->Row(array("Tarifa 0%", $tar0));

        $pdf->SetX(35);

        $pdf->SetWidths(array(22, 15));

        $pdf->Row(array("Subtotal", $sub));

        $pdf->SetX(35);

        $pdf->SetWidths(array(22, 15));

        $gdescuento=$iva;
        $pdf->Row(array("Descuento", $iva));

        $pdf->SetX(35);

        $pdf->SetWidths(array(22, 15));

        $pdf->Row(array("Iva 15%", $sub12));

        $pdf->SetX(35);

        $pdf->SetWidths(array(22, 15));

        $pdf->Row(array("Total", $total));
    } else {

        $tar0 = $fila[0];

//$tar0 = truncateFloat(round($fila[0], 5, PHP_ROUND_HALF_EVEN),5);

        $sub0 = truncateFloat(round($fila[1], 3, PHP_ROUND_HALF_EVEN), 3);

        $sub12 = truncateFloat(round($fila[2], 3, PHP_ROUND_HALF_EVEN), 3);

        $iva = truncateFloat(round($fila[3], 3, PHP_ROUND_HALF_EVEN), 3);

        $total = truncateFloat(round($fila[4], 3, PHP_ROUND_HALF_EVEN), 2);



        $pdf->SetFont('Arial', '', 8);

        $sub_total = $sub0 + $tar0;

        $tar0 = $tar0 + 0;

        $sub = $sub_total;

        $total = $total + 0;
        $total = number_format($total, 2, '.', '');

        $pdf->SetX(35);
        $pdf->SetAligns(array('l', 'R'));
        $pdf->SetWidths(array(22, 15));

        $pdf->Row(array("Tarifa 15%", $sub0));

        $pdf->SetX(35);

        $pdf->SetWidths(array(22, 15));

        $pdf->Row(array("Tarifa 0%", $tar0));

        $pdf->SetX(35);

        $pdf->SetWidths(array(22, 15));

        $pdf->Row(array("Subtotal", $sub));

        $pdf->SetX(35);

        $pdf->SetWidths(array(22, 15));

        $gdescuento=$iva;
        $pdf->Row(array("Descuento", $iva));

        $pdf->SetX(35);

        $pdf->SetWidths(array(22, 15));

        $pdf->Row(array("Iva 15%", $sub12));

        $pdf->SetX(35);

        $pdf->SetWidths(array(22, 15));

        $pdf->Row(array("Total", $total));
    }
}

function maxCaracter($texto, $cant) {
    $texto = substr($texto, 0, $cant);
    return $texto;
}

function ceil_caracter($texto, $cant) {
    $array_t = array();
    $var = 0;
    $total = strlen($texto) / $cant;
    $total = ceil($total);
    for ($i = 0; $i < $total; $i++) {
        $array_t[$i] = substr($texto, $var, $cant);
        $var = $var + $cant;
    }
    return $array_t;
}

function truncateFloat($number, $digitos) {
    $raiz = 10;
    $multiplicador = pow($raiz, $digitos);
    $resultado = ((int) ($number * $multiplicador)) / $multiplicador;
    return number_format($resultado, $digitos);
}

$pdf->Ln(5);
if($gdescuento>0){
    $pdf->Cell(77,5,"SU DESCUENTO ES DE: ".number_format($gdescuento,2,".",""),0,1);
}



$pdf->SetX(10);


//$pdf->SetY(50);        
//$pdf->Row(array("**","."));
$pdf->Output();

function nroMesaFactura($idfactura)
{
    $sql="
    select mesa from restaurante_ordenes
    where tipo_documento='FACTURA' and id_documento=$idfactura
    ";
    //var_dump($sql);
    $res=pg_query($sql);
    $row=pg_fetch_row($res);
    //var_dump($row);
    if(empty($row)){
        return "";
    }
    return $row[0];
}

