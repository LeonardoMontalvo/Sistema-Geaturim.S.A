<?php
//        include '../fpdf/rotation.php';        
//        include("../fpdf/barcode.inc.php");
//        include '../procesos/base.php';
include __DIR__.'/../../../fpdf/rotation.php';
include(__DIR__."/../../../fpdf/barcode.inc.php");
require_once(__DIR__.'/../../../procesos/base.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


//                require_once( '../../procesos/funciones.php');
//error_reporting(0);


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

}


$pdf = new PDF('P', 'mm', array(77, 310));
date_default_timezone_set('America/Guayaquil');

$fecha = date('Y-m-d H:i:s', time());

$pdf->AddPage();

$pdf->SetMargins(0, 0, 0, 0);
$pdf->Ln(0);

$pdf->SetFont('Arial', '', 7);
$id = 0;
$id = $_GET['id'];

$sql = pg_query("SELECT nombre_empresa, ruc_empresa, direccion_empresa, telefono_empresa, celular_empresa,
        email_empresa, nombre_comercial, obligacion, contribuyente_espe, establecimiento, punto_emision,
        fecha_actual as fecha_emision, num_autorizacion, fecha_autorizacion, num_factura, num_serie, 
        fv.clave, serie_guia_remision, marca_vehiculo, identificacion, nombres_cli, direccion_cli, 
        case when telefono!='' then telefono else celular end as telefono_cli
        from empresa e left join factura_venta fv using(id_empresa) 
        left join clientes c using(id_cliente) 
        left join tipo_documento td using(id_tdocu) 
        where fv.id_factura_venta='" . $id . "'  ");


list($width, $height, $type, $attr) = getimagesize('../../../images/'.$_SESSION["parametros_empresa"]["logo_empresa"]);
$offsety=10;
if($height==$width){
    $offsety=20;
}
//$pdf->Image('../../../images/'.$_SESSION["parametros_empresa"]["logo_empresa"], 30, 5, 20); // Img Empresa



$numfilas = pg_num_rows($sql);

for ($i = 0; $i < $numfilas; $i++) {


    $rowempre = pg_fetch_assoc($sql);

    $pdf->SetFont('Arial', '', 8);

    $pdf->SetX(2);
    $pdf->Text(10, 10+$offsety, $rowempre['nombre_empresa'], 0, 0, 'C', 0);



    $pdf->Text(20, 14+$offsety, utf8_decode('' . "RUC:"), 0, 'C', 0); ////CLIENTE (X,Y)   

    $pdf->Text(27, 14+$offsety, utf8_decode('' . strtoupper($rowempre['ruc_empresa'])), 0, 'C', 0); ////CLIENTE (X,Y)

    $pdf->Text(7, 18+$offsety, utf8_decode('' . "Matr.:"), 0, 'C', 0); ////CLIENTE (X,Y)   
    $pdf->Text(15, 18+$offsety, utf8_decode('' . strtoupper($rowempre['direccion_empresa'])), 0, 'C', 0); ////CLIENTE (X,Y)

    $pdf->Text(20, 22+$offsety, utf8_decode('' . "Telf:"), 0, 'C', 0); ////CLIENTE (X,Y)   

    $pdf->Text(26, 22+$offsety, utf8_decode('' . strtoupper($rowempre['celular_empresa'])), 0, 'C', 0); ////CLIENTE (X,Y)

    $pdf->Text(8, 26+$offsety, utf8_decode('' . "E-MAIL:"), 0, 'C', 0); ////CLIENTE (X,Y)   
    $pdf->Text(21, 26+$offsety, utf8_decode('' . $rowempre['email_empresa']), 0, 'C', 0); ////CLIENTE (X,Y)  



    $pdf->Text(8, 30+$offsety, utf8_decode('' . "Obligado a llevar Contabilidad: "), 0, 'C', 0); ////CLIENTE (X,Y)       
    $pdf->Text(51, 30+$offsety, utf8_decode('' . strtoupper($rowempre['obligacion'])), 0, 'C', 0); ////CLIENTE (X,Y)
    $secuencial = "$rowempre[num_serie]" . "-" . "$rowempre[num_factura]";
    $ip = $secuencial;
    $iparr = split("\-", $ip);
//    $secuencial = $iparr[2];
    $pdf->Text(8, 34+$offsety, utf8_decode('' . "FACTURA NRO.: "), 0, 'C', 0); ////CLIENTE (X,Y)       
    $pdf->Text(32, 34+$offsety, utf8_decode('' .  $secuencial), 0, 'C', 0); ////CLIENTE (X,Y)

    $pdf->Text(8, 37+$offsety, utf8_decode('' . "Nro.Autorizacion: "), 0, 'C', 0); ////CLIENTE (X,Y)       
// $pdf->Text(10,42,utf8_decode(''.strtoupper($fila[35])),0,'C', 0);////CLIENTE (X,Y)
    $pdf->SetY(38+$offsety);
    $pdf->SetX(8);
     $numeroAutorizacion = $rowempre['num_autorizacion'];
    if ($numeroAutorizacion == "" || $numeroAutorizacion == "undefined") {
        $numeroAutorizacion = $rowempre['clave'];
    } else {
        $numeroAutorizacion = $numeroAutorizacion;
    }
    if (strlen($numeroAutorizacion) > 50)
        $tam = 3;
    else
        $tam = 3;


   

    $pdf->SetFont('Arial', '', 7);
    $pdf->multiCell(62, $tam, $numeroAutorizacion, 0);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Text(8, 46+$offsety, utf8_decode('' . "Clave de Acceso: "), 0, 'C', 0); ////CLIENTE (X,Y)     
    $pdf->SetY(46+$offsety);
    $pdf->SetX(8);
    if (strlen($numeroAutorizacion) > 50)
        $tam = 3;
    else
        $tam = 3;
    $pdf->SetFont('Arial', '', 7);
    $pdf->multiCell(62, $tam, $numeroAutorizacion, 0);
    $consulta_ambiente = pg_query("select nombre_ambi from ambiente  where estado_ambi='Activo' ");
    while ($row = pg_fetch_row($consulta_ambiente)) {
        $nombre_ambi = $row[0];
    }
    $ambiente = $nombre_ambi;

    $pdf->Text(8, 54+$offsety, utf8_decode('' . "Ambiente:"), 0, 'C', 0); ////CLIENTE (X,Y)          
    $pdf->Text(26, 54+$offsety, utf8_decode('' . ($ambiente)), 0, 'C', 0); ////CLIENTE (X,Y)
    $consulta_emision = pg_query("select nombre_temision from tipo_emision  where id_temision='1' ");
    while ($row = pg_fetch_row($consulta_emision)) {
        $nombre_emi = $row[0];
    }
    $emision = $nombre_emi;
    $pdf->Text(45, 54+$offsety, utf8_decode('' . "Emision:"), 0, 'C', 0); ////CLIENTE (X,Y)          
    $pdf->Text(55, 54+$offsety, utf8_decode('' . ($emision)), 0, 'C', 0); ////CLIENTE (X,Y)

    $pdf->Text(8, 58+$offsety, utf8_decode('' . "Cliente:"), 0, 'C', 0); ////CLIENTE (X,Y)          
    $pdf->Text(20, 58+$offsety, utf8_decode('' . strtoupper($rowempre['nombres_cli'])), 0, 'C', 0); ////CLIENTE (X,Y)
    $pdf->Text(8, 62+$offsety, utf8_decode('' . "RUC/CI:"), 0, 'C', 0); ////CLIENTE (X,Y)          
    $pdf->Text(28, 62+$offsety, utf8_decode('' . strtoupper($rowempre['identificacion'])), 0, 'C', 0); ////CLIENTE (X,Y)

    $pdf->Text(8, 66+$offsety, utf8_decode('' . "Direcciòn:"), 0, 'C', 0); ////CLIENTE (X,Y)          
    $pdf->Text(28, 66+$offsety, utf8_decode('' . strtoupper($rowempre['direccion_cli'])), 0, 'C', 0); ////CLIENTE (X,Y)
    $pdf->Text(8, 70+$offsety, utf8_decode('' . "Telèfono:"), 0, 'C', 0); ////CLIENTE (X,Y)          
    $pdf->Text(28, 70+$offsety, utf8_decode('' . strtoupper( $rowempre['telefono_cli'])), 0, 'C', 0); ////CLIENTE (X,Y)
    $pdf->Text(8, 73+$offsety, utf8_decode('' . "Fecha de Emisión :"), 0, 'C', 0); ////CLIENTE (X,Y)   
    $fechaEmision = $row[36];
    $date = new DateTime($fechaEmision);
    $fechaEmision = $date->format('d/m/Y');
    $pdf->Text(33, 73+$offsety, utf8_decode('' . strtoupper($fechaEmision)), 0, 'C', 0); ////CLIENTE (X,Y)



    $pdf->Ln(27);
}


$pdf->SetX(5);

$pdf->SetWidths(array(7, 30, 10, 10));

$sql = pg_query("select detalle_factura_venta.cantidad,productos.articulo,detalle_factura_venta.precio_venta,detalle_factura_venta.total_venta, productos.iva from factura_venta,detalle_factura_venta,productos where factura_venta.id_factura_venta=detalle_factura_venta.id_factura_venta and detalle_factura_venta.cod_productos=productos.cod_productos and detalle_factura_venta.id_factura_venta='" . $id . "'  order by detalle_factura_venta.id_detalle_venta asc");
$consulta_ambiente = pg_query("select nombre_ambi from ambiente  ");
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
$pdf->Text(8, 76+$offsety, "CA");
$pdf->Text(20, 76+$offsety, "DESCRIPCION");
$pdf->Text(44, 76+$offsety, "P.UNIT");
$pdf->Text(56, 76+$offsety, "V.TOTAL");

while ($fila = pg_fetch_row($sql)) {

    $pdf->SetX(7);

    $pdf->SetFont('Arial', '', 7);

    if ($fila[4] == "Si") {

        $sub = $fila[2];

        $total = $sub * $fila[0];
        $total = $total + 0;
        $total = number_format($total, 2, '.', '');
        $totalfila = 0;
        $totalfila = $fila[3];
        $totalfila = truncateFloat($fila[3], 2);

        $pdf->SetX(4);

        $pdf->Row(array(utf8_decode(truncateFloat($fila[0], 2)), maxCaracter(utf8_decode($fila[1]), 15), utf8_decode(truncateFloat($sub, 2)), utf8_decode(truncateFloat(round($total, 2, PHP_ROUND_HALF_EVEN), 2) . "  *")));
    } else {

        $descripcion = utf8_decode($fila[1]);



        $pdf->SetX(7);

        $pdf->Row(array(utf8_decode(truncateFloat($fila[0], 2)), maxCaracter(utf8_decode($fila[1]), 15), utf8_decode(truncateFloat($fila[2], 2)), utf8_decode(truncateFloat(round($fila[3], 2, PHP_ROUND_HALF_EVEN), 2))));
    
        }
       
}


//PIE PAGINA	


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

        $pdf->SetWidths(array(22, 80));

        $pdf->Row(array("Tarifa 12%", $sub0));

        $pdf->SetX(35);

        $pdf->SetWidths(array(22, 35));

        $pdf->Row(array("Tarifa 0%", $tar0));

        $pdf->SetX(35);

        $pdf->SetWidths(array(22, 35));

        $pdf->Row(array("Subtotal", $sub));

        $pdf->SetX(35);

        $pdf->SetWidths(array(22, 35));

        $pdf->Row(array("Descuento", $iva));

        $pdf->SetX(35);

        $pdf->SetWidths(array(22, 35));

        $pdf->Row(array("Iva 12%", $sub12));

        $pdf->SetX(35);

        $pdf->SetWidths(array(22, 35));

        $pdf->Row(array("Total", $total));
    } else {

        $tar0 = $fila[0];

//$tar0 = truncateFloat(round($fila[0], 5, PHP_ROUND_HALF_EVEN),5);

        $sub0 = truncateFloat(round($fila[1], 3, PHP_ROUND_HALF_EVEN), 3);

        $sub12 = truncateFloat(round($fila[2], 3, PHP_ROUND_HALF_EVEN), 3);

        $iva = truncateFloat(round($fila[3], 3, PHP_ROUND_HALF_EVEN), 3);

        $total = truncateFloat(round($fila[4], 3, PHP_ROUND_HALF_EVEN), 2);


        $pdf->SetFont('Arial', '', 7);


        $sub_total = $sub0 + $tar0;
        $tarvar = $tar0 + 0;
        $tarvar1 = truncateFloat($tarvar, 2);

        $sub = truncateFloat($sub_total, 2);

        $pdf->SetX(40);

        $pdf->SetWidths(array(22, 80));

        $pdf->Row(array("Tarifa 12%", $sub0));

        $pdf->SetX(40);

        $pdf->SetWidths(array(22, 80));

        $pdf->Row(array("Tarifa 0%", $tarvar1));

        $pdf->SetX(40);

        $pdf->SetWidths(array(22, 35));

        $pdf->Row(array("Subtotal", $sub));

        $pdf->SetX(40);

        $pdf->SetWidths(array(22, 35));

        $pdf->Row(array("Descuento", $iva));

        $pdf->SetX(40);

        $pdf->SetWidths(array(22, 35));

        $pdf->Row(array("Iva 12%", $sub12));

        $pdf->SetX(40);

        $pdf->SetWidths(array(22, 35));

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

$pdf->Ln(2);


$pdf->SetX(10);


//$pdf->SetY(50);        
//$pdf->Row(array("**","."));
$pdf->Output();
?>

//////////////////
