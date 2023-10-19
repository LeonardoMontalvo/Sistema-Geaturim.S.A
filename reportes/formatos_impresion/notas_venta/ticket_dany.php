<?php
//require('../fpdf/fpdf.php');
//include '../procesos/base.php';
//include '../procesos/funciones.php';

include __DIR__.'/../../../fpdf/rotation.php';
include __DIR__.'/../../../procesos/base.php';
include __DIR__.'/../../../procesos/funciones.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


conectarse();

class PDF extends FPDF {

    var $widths;
    var $aligns;

    // Page header
    function Header() {
        // Logo
        //$this->Image('../images/logo_nota_venta.jpeg', 5, 1, 75);
        // Arial bold 15
        $this->SetFont('Arial', 'B', 9);
        // Move to the right
        $this->setY(2);
        $this->Cell(47, 2);
        // Title
        // Line break
        $this->Ln(10);
    }

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
        return $this->w - ($this->lMargin * 15);
    }

}

$gdescuento=0;

$valory=23;

$largo_detalle = 100;

$sql = pg_query("SELECT dv.cantidad, p.articulo, dv.precio_venta, dv.total_venta, p.iva 
    from facturas_novalidas nv,detalle_facturas_novalidas dv, productos p
    where nv.id_facturas_novalidas=dv.id_facturas_novalidas and dv.cod_productos=p.cod_productos and dv.id_facturas_novalidas='$_GET[id]' 
    ORDER BY dv.id_facturas_novalidas asc;");
$y = pg_num_rows($sql);
$y *= 10;

$pdf = new PDF('P', 'mm', array(95, 200 + $y));
date_default_timezone_set('America/Guayaquil');

$fecha = date('Y-m-d H:i:s', time());

$pdf->AddPage();
$pdf->setTitle('Nota de Venta');

$pdf->SetMargins(0, 0, 0, 0);
$pdf->Ln(0);

$pdf->SetFont('Arial', '', 8);

$sql = pg_query(
        "SELECT id_facturas_novalidas, comprobante, nombres_cli, nombres_cli, nombres_cli, u.nombre_usuario, nombres_cli, c.telefono, nv.hora_actual, identificacion, direccion_cli, celular, ciudad, nv.fecha_actual, forma_pago, nv.fecha_actual, nombre_usuario, apellido_usuario
    FROM facturas_novalidas nv,clientes c,usuario u 
    WHERE nv.id_cliente=c.id_cliente  and nv.id_usuario=u.id_usuario and nv.id_facturas_novalidas='$_GET[id]'
    ORDER BY nv.id_facturas_novalidas;"
);

$numfilas = pg_num_rows($sql);

$pdf->Image('../../../images/'.$_SESSION["parametros_empresa"]["logo_empresa"], 30, 5, 15); // Img Empresa

for ($i = 0; $i < $numfilas; $i++) {


    $fila = pg_fetch_row($sql);

    $pdf->SetFont('Arial', 'B', 8.5);
    $pdf->Text(20, 2+$valory, utf8_decode('' . "NOTA DE ENTREGA.:"), 0, 'C', 0); ////NUM FACT (X,Y)   

    //$pdf->Text(2, 10+$valory, utf8_decode('' . "NUM. ORDEN:"), 0, 'C', 0); ////NUM FACT (X,Y)   
    //$pdf->Text(10, 10+$valory, utf8_decode('0' . strtoupper($fila[1])), 0, 'C', 0); ////NUM FACT (X,Y)

    $pdf->SetY(3+$valory);
    $pdf->SetX(5);
    $nro=strtoupper($fila[1]);
    $mesa=nroMesaNota($nro);
    if(!empty($mesa)){
        $mesa=" - MESA: ".$mesa;
    }
    $pdf->Cell(10,5,"NUM. ORDEN: ".$nro.$mesa,0,1);

    $pdf->SetFont('Arial', '', 8);


    $pdf->Text(5, 10+$valory, utf8_decode('' . "Vendedor:"), 0, 'C', 0); ////NUM FACT (X,Y)   

    $pdf->Text(20, 10+$valory, utf8_decode('' . strtoupper($fila[5])), 0, 'C', 0); ////NUM FACT (X,Y)


    $pdf->Text(5, 13+$valory, utf8_decode('' . "Cliente:"), 0, 'C', 0); ////CLIENTE (X,Y)   

    $pdf->Text(15, 13+$valory, utf8_decode('' . strtoupper($fila[6])), 0, 'C', 0); ////CLIENTE (X,Y)
    //$pdf->Text(7,150,utf8_decode(''."Cliente:"),0,'C', 0);////CLIENTE (X,Y)   
    //$pdf->Text(19,150,utf8_decode(''.strtoupper($fila[8])),0,'C', 0);////CLIENTE (X,Y)


    $pdf->Text(5, 17+$valory, utf8_decode('' . "CI/RUC:"), 0, 'C', 0); ////CLIENTE (X,Y)   

    $pdf->Text(16, 17+$valory, utf8_decode('' . strtoupper($fila[9])), 0, 'C', 0); ////CLIENTE (X,Y)
    //$pdf->Text(5,155,utf8_decode(''."CI/RUC:"),0,'C', 0);////CLIENTE (X,Y)   
    //$pdf->Text(17,155,utf8_decode(''.strtoupper($fila[9])),0,'C', 0);////CLIENTE (X,Y)  

    $pdf->Text(37, 17+$valory, utf8_decode('' . "Fecha:"), 0, 'C', 0); ////CLIENTE (X,Y)   

    $pdf->Text(47, 17+$valory, utf8_decode('' . strtoupper($fila[13] . ' / ' . $fila[8])), 0, 'C', 0); ////CLIENTE (X,Y)


    $pdf->Text(5, 20+$valory, utf8_decode('' . "Direcion:"), 0, 'C', 0); ////CLIENTE (X,Y) 

    $pdf->Text(18, 20+$valory, utf8_decode('' . strtoupper($fila[10])), 0, 'C', 0); ////CLIENTE (X,Y)
    ////$pdf->Text(17,160,utf8_decode(''.strtoupper($fila[18])),0,'C', 0);////CLIENTE (X,Y)


    $pdf->Text(5, 23+$valory, utf8_decode('' . "Tel:"), 0, 'C', 0); ////CLIENTE (X,Y)   

    $tel = $fila[11];
    if ($tel == "") {
        $tel = $fila[7];
    }

    $pdf->Text(8, 23+$valory, utf8_decode('' . strtoupper($tel)), 0, 'C', 0); ////CLIENTE (X,Y)
    //$pdf->Text(5,465,utf8_decode(''."Telf:"),0,'C', 0);////CLIENTE (X,Y)   
    //$pdf->Text(17,165,utf8_decode(''.strtoupper($fila[11])),0,'C', 0);////CLIENTE (X,Y)


    $pdf->Text(30, 23+$valory, utf8_decode('' . "Ciudad:"), 0, 'C', 0); ////CLIENTE (X,Y)   

    $pdf->Text(42, 23+$valory, utf8_decode('' . strtoupper($fila[12])), 0, 'C', 0); ////CLIENTE (X,Y)

    $pdf->Text(2, 26+$valory, utf8_decode('--------------------------------------------------------------------------------------'), 0, 'C', 0); ////CLIENTE (X,Y)
    //$pdf->Text(40,165,utf8_decode(''."Fecha:"),0,'C', 0);////CLIENTE (X,Y)   
    //$pdf->Text(53,165,utf8_decode(''.strtoupper($fila[13])),0,'C', 0);////CLIENTE (X,Y)
    /// $pdf->Text(50,130,utf8_decode(''." ."),0,'C', 0);////CLIENTE (X,Y)

    $pdf->Ln(18);
}

$pdf->SetX(5);

$pdf->SetWidths(array(10, 40, 12, 17));

$sql = pg_query("SELECT dv.cantidad, p.articulo, dv.precio_venta, dv.total_venta, p.iva 
    from facturas_novalidas nv,detalle_facturas_novalidas dv, productos p
    where nv.id_facturas_novalidas=dv.id_facturas_novalidas and dv.cod_productos=p.cod_productos and dv.id_facturas_novalidas='$_GET[id]' 
    ORDER BY dv.id_facturas_novalidas asc;");

$pdf->Row(array("Cant", utf8_decode("Descripción"), "Pre.Uni", "Total"));
while ($fila = pg_fetch_row($sql)) {

    $largo_detalle += -30;

    $pdf->SetX(5);

    $pdf->SetFont('Arial', '', 8);

    $sub = $fila[2];

    if ($fila[4] == "Si") {

        $total = $sub * $fila[0];

        $pdf->SetX(5);

        $pdf->Row(array(
            utf8_decode(truncateFloat($fila[0], 2)), maxCaracter(utf8_decode($fila[1]), 15),
            number_format((truncateFloat($sub, 4)), 3, '.', ','),
            utf8_decode(number_format(truncateFloat(round($total, 4, PHP_ROUND_HALF_EVEN), 4), 2) . " *")
        ));
    } else {

        $descripcion = utf8_decode($fila[1]);
        $pdf->SetX(5);

        $pdf->Row(array(
            utf8_decode(truncateFloat($fila[0], 2)),
            maxCaracter(utf8_decode($fila[1]), 15),
            number_format((truncateFloat($sub, 4)), 3, '.', ','),
            utf8_decode(number_format(truncateFloat(round($fila[3], 4, PHP_ROUND_HALF_EVEN), 4), 2))
        ));
        $pdf->Ln(1);
    }
}
//$pdf->SetY($largo_detalle);

$sql = pg_query("select tarifa0,tarifa12,iva_venta,descuento_venta,total_venta from facturas_novalidas where id_facturas_novalidas='$_GET[id]'");

$sub0 = 0;

$sub12 = 0;

$iva = 0;

$total = 0;

while ($fila = pg_fetch_row($sql)) {
    if ($fila[4] < 1000) {

        $tar0 = truncateFloat(round($fila[0], 4, PHP_ROUND_HALF_EVEN), 4);

        $sub0 = truncateFloat(round($fila[1], 4, PHP_ROUND_HALF_EVEN), 4);

        $sub12 = truncateFloat(round($fila[2], 4, PHP_ROUND_HALF_EVEN), 4);

        $iva = truncateFloat(round($fila[3], 4, PHP_ROUND_HALF_EVEN), 4);

        $total = truncateFloat(round($fila[4], 4, PHP_ROUND_HALF_EVEN), 4);


        $pdf->SetFont('Arial', '', 8);
        $sub_total = $sub0 + $tar0;

        $tar0 = $tar0 + 0;

        $sub = $sub_total;

        $total = $total + 0;

        $pdf->SetX(37);

        $pdf->SetWidths(array(20, 35));

        $pdf->Row(array("Tarifa 12%", number_format($sub0, 2)));

        $pdf->SetX(37);

        $pdf->SetWidths(array(20, 35));

        $pdf->Row(array("Tarifa 0%", number_format($tar0, 2)));

        $pdf->SetX(37);

        $pdf->SetWidths(array(20, 35));

        $pdf->Row(array("Subtotal", number_format($sub, 2)));

        $pdf->SetX(37);

        $pdf->SetWidths(array(20, 35));

        $gdescuento=$iva;
        $pdf->Row(array("Descuento", number_format($iva, 2)));

        $pdf->SetX(37);

        $pdf->SetWidths(array(20, 35));

        $pdf->Row(array("Iva 12%", number_format($sub12, 2)));

        $pdf->SetX(37);

        $pdf->SetWidths(array(20, 35));

        $pdf->Row(array("Total", number_format($total, 2)));
    } else {

        $tar0 = $fila[0];

        $tar0 = truncateFloat(round($fila[0], 4, PHP_ROUND_HALF_EVEN), 4);

        $sub0 = truncateFloat(round($fila[1], 4, PHP_ROUND_HALF_EVEN), 4);

        $sub12 = truncateFloat(round($fila[2], 4, PHP_ROUND_HALF_EVEN), 4);

        $iva = truncateFloat(round($fila[3], 4, PHP_ROUND_HALF_EVEN), 4);

        $total = truncateFloat(round($fila[4], 4, PHP_ROUND_HALF_EVEN), 4);


        $pdf->SetFont('Arial', '', 8);


        $sub_total = $sub0 + $tar0;
        $tarvar = $tar0 + 0;
        $tarvar1 = truncateFloat($tarvar, 2);

        $sub = truncateFloat($sub_total, 2);

        $pdf->SetX(40);

        $pdf->SetWidths(array(20, 20));

        $pdf->Row(array("Tarifa 12%", $sub0));

        $pdf->SetX(40);

        $pdf->SetWidths(array(20, 35));

        $pdf->Row(array("Tarifa 0%", $tarvar1));

        $pdf->SetX(40);

        $pdf->SetWidths(array(20, 35));

        $pdf->Row(array("Subtotal", $sub));

        $pdf->SetX(40);

        $pdf->SetWidths(array(20, 35));

        $gdescuento=$iva;
        $pdf->Row(array("Descuento", $iva));

        $pdf->SetX(40);

        $pdf->SetWidths(array(20, 35));

        $pdf->Row(array("Iva 12%", $sub12));

        $pdf->SetX(40);

        $pdf->SetWidths(array(20, 35));

//        $pdf->Row(array("Total", $total));
    }
    $pdf->Ln(5);
}


$pdf->SetX(10);
//$pdf->SetY($largo_detalle);
$pdf->Ln(5);
//$pdf->Row(array("$$"));

if($gdescuento>0){
    $pdf->Cell(77,5,"SU DESCUENTO ES DE: ".number_format($gdescuento,2,".",""),0,1);
}
$pdf->SetX(10);
$pdf->Cell(90,10,utf8_decode('' . "CANJEE SU FACTURA EN VENTANILLA"),0,1);

//$pdf->Text(2, ($pdf->GetY()), utf8_decode('' . "CANJEE SU FACTURA EN VENTANILLA"), 0, 'C', 0); ////CLIENTE (X,Y) 

$pdf->Row(array("", "."));
$pdf->Output();
?>

// SALTO DE PAGINA DE IMPRESION

<?php
require('../fpdf/fpdf.php');
include '../procesos/base.php';
include '../procesos/funciones.php';

conectarse();

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

$pdf = new PDF('P', 'mm', array(76, 300));
date_default_timezone_set('America/Guayaquil');

$fecha = date('Y-m-d H:i:s', time());

$pdf->AddPage();

$pdf->SetMargins(0, 0, 0, 0);
$pdf->Ln(0);

$pdf->SetFont('Arial', '', 9);

$sql = pg_query("select id_facturas_novalidas,comprobante,nombres_cli,nombres_cli,nombres_cli,nombres_cli,nombres_cli,nombres_cli,nombres_cli,identificacion,direccion_cli,telefono,ciudad,facturas_novalidas.fecha_actual,forma_pago,facturas_novalidas.fecha_actual,nombre_usuario,apellido_usuario,direccion_cli from facturas_novalidas,clientes,usuario where facturas_novalidas.id_cliente=clientes.id_cliente  and facturas_novalidas.id_usuario=usuario.id_usuario and facturas_novalidas.id_facturas_novalidas='$_GET[id]'");
$numfilas = pg_num_rows($sql);
for ($i = 0; $i < $numfilas; $i++) {

    $fila = pg_fetch_row($sql);
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetX(0);
    $pdf->Text(2, 8+$valory, utf8_decode('' . "Cliente:"), 0, 'C', 0); ////CLIENTE (X,Y)   
    $pdf->Text(17, 8+$valory, utf8_decode('' . strtoupper($fila[8])), 0, 'C', 0); ////CLIENTE (X,Y)             
    $pdf->Text(2, 13+$valory, utf8_decode('' . "CI/RUC:"), 0, 'C', 0); ////CLIENTE (X,Y)   
    $pdf->Text(17, 13+$valory, utf8_decode('' . strtoupper($fila[9])), 0, 'C', 0); ////CLIENTE (X,Y)      

    $pdf->Text(60, 18+$valory, utf8_decode('' . "Direc:"), 0, 'C', 0); ////CLIENTE (X,Y) 

    $pdf->Text(17, 18+$valory, utf8_decode('' . strtoupper($fila[18])), 0, 'C', 0); ////CLIENTE (X,Y)

    $pdf->Text(2, 23+$valory, utf8_decode('' . "Telf:"), 0, 'C', 0); ////CLIENTE (X,Y)   

    $pdf->Text(17, 23+$valory, utf8_decode('' . strtoupper($fila[11])), 0, 'C', 0); ////CLIENTE (X,Y) 

    $pdf->Text(40, 23+$valory, utf8_decode('' . "Fecha:"), 0, 'C', 0); ////CLIENTE (X,Y)   

    $pdf->Text(53, 23+$valory, utf8_decode('' . strtoupper($fila[13])), 0, 'C', 0); ////CLIENTE (X,Y)  

    $pdf->Ln(20);
}


$pdf->SetX(2);

$pdf->SetWidths(array(10, 33, 15, 15));

$sql = pg_query("select detalle_facturas_novalidas.cantidad,productos.articulo,detalle_facturas_novalidas.precio_venta,detalle_facturas_novalidas.total_venta, productos.iva from facturas_novalidas,detalle_facturas_novalidas,productos where facturas_novalidas.id_facturas_novalidas=detalle_facturas_novalidas.id_facturas_novalidas and detalle_facturas_novalidas.cod_productos=productos.cod_productos and detalle_facturas_novalidas.id_facturas_novalidas='$_GET[id]' order by detalle_facturas_novalidas.id_facturas_novalidas asc");

$pdf->Row(array("Cant", utf8_decode("Descripcion"), "Pre.Uni", "Total"));

while ($fila = pg_fetch_row($sql)) {

    $pdf->SetX(2);

    $pdf->SetFont('Arial', '', 9);

    if ($fila[4] == "Si") {

        $sub = $fila[2] / 1.12;

        $total = $sub * $fila[0];

        //$descripcion =  utf8_decode($fila[1]);
        //if(strlen($descripcion) > 20) {
        // $descripcion = substr($descripcion, 0,15);
        //}


        $pdf->SetX(2);

        $pdf->Row(array(
            utf8_decode(truncateFloat($fila[0], 2)), maxCaracter(utf8_decode($fila[1]), 15),
            utf8_decode(truncateFloat($sub, 2)),
            utf8_decode(truncateFloat(round($total, 2, PHP_ROUND_HALF_EVEN), 2) . "  *")
        ));
    } else {
        $descripcion = utf8_decode($fila[1]);

        //if(strlen($descripcion) > 20) {
        // $descripcion = substr($descripcion, 0,10);
        //}


        $pdf->SetX(25);

        $pdf->Row(array(
            utf8_decode(truncateFloat($fila[0], 2)),
            maxCaracter(utf8_decode($fila[1]), 15),
            utf8_decode(truncateFloat($fila[2], 2)),
            utf8_decode(truncateFloat(round($fila[3], 2, PHP_ROUND_HALF_EVEN), 2))
        ));
    }
}


$pdf->SetY(68+$valory);

$sql = pg_query("select tarifa0,tarifa12,iva_venta,descuento_venta,total_venta from facturas_novalidas where id_facturas_novalidas='$_GET[id]'");

$sub0 = 0;

$sub12 = 0;

$iva = 0;

$total = 0;

while ($fila = pg_fetch_row($sql)) {

    $tar0 = truncateFloat(round($fila[0], 2, PHP_ROUND_HALF_EVEN), 2);

    $sub0 = truncateFloat(round($fila[1], 2, PHP_ROUND_HALF_EVEN), 2);

    $sub12 = truncateFloat(round($fila[2], 2, PHP_ROUND_HALF_EVEN), 2);

    $iva = truncateFloat(round($fila[3], 2, PHP_ROUND_HALF_EVEN), 2);

    $total = truncateFloat(round($fila[4], 2, PHP_ROUND_HALF_EVEN), 2);
}

$pdf->SetFont('Arial', '', 10);

$sub_total = $sub0 + $tar0;

$sub = truncateFloat($sub_total, 2);

$pdf->SetX(29);

$pdf->SetWidths(array(32, 35));

$pdf->Row(array("Tarifa 12%", $sub0));

$pdf->SetX(29);

$pdf->SetWidths(array(32, 35));

$pdf->Row(array("Tarifa 0%", $tar0));

$pdf->SetX(29);

$pdf->SetWidths(array(32, 35));

$pdf->Row(array("Subtotal", $sub));

$pdf->SetX(29);

$pdf->SetWidths(array(32, 35));

$pdf->Row(array("Descuento", $iva));

$pdf->SetX(29);

$pdf->SetWidths(array(32, 35));

$pdf->Row(array("Iva 12%", $sub12));

$pdf->SetX(29);

$pdf->SetWidths(array(32, 35));

$pdf->Row(array("Total", $total));

$pdf->Ln(2);

$pdf->SetX(2);


$pdf->SetY(90+$valory);

$pdf->Row(array("", "."));

$pdf->Output();


function nroMesaNota($idfactura)
{
    $sql="
    select mesa from restaurante_ordenes
    where tipo_documento='NOTA' and id_documento=$idfactura
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