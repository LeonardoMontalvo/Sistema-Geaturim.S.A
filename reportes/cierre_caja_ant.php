<?php
require('../fpdf/fpdf.php');
include '../procesos/base.php';
include '../procesos/funciones.php';
session_start();
conectarse();

class PDF extends FPDF
{

    var $widths;
    var $aligns;

    // Page header
    function Header()
    {
    }

    function SetWidths($w)
    {
        //Set the array of column widths

        $this->widths = $w;
    }

    function SetAligns($a)
    {
        //Set the array of column alignments

        $this->aligns = $a;
    }

    function Row($data, $border = 0, $style = "", $fill = false, $border_cell = 0)
    {
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

            if ($border == 1) {
                //Draw the border
                $this->Rect($x, $y, $w, $h, $style);
            }

            $this->MultiCell($w, 5, $data[$i], $border_cell, $a, $fill);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function CheckPageBreak($h)
    {
        //If the height h would cause an overflow, add a new page immediately
        if ($this->GetY() + $h > $this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w, $txt)
    {
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

    function GetMultiCellHeight($w, $h, $txt, $border = null, $align = 'J')
    {
        // Calculate MultiCell with automatic or explicit line breaks height
        // $border is un-used, but I kept it in the parameters to keep the call
        //   to this function consistent with MultiCell()
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $ns = 0;
        $height = 0;
        while ($i < $nb) {
            // Get next character
            $c = $s[$i];
            if ($c == "\n") {
                // Explicit line break
                if ($this->ws > 0) {
                    $this->ws = 0;
                    $this->_out('0 Tw');
                }
                //Increase Height
                $height += $h;
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $ns = 0;
                continue;
            }
            if ($c == ' ') {
                $sep = $i;
                $ls = $l;
                $ns++;
            }
            $l += $cw[$c];
            if ($l > $wmax) {
                // Automatic line break
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                    if ($this->ws > 0) {
                        $this->ws = 0;
                        $this->_out('0 Tw');
                    }
                    //Increase Height
                    $height += $h;
                } else {
                    if ($align == 'J') {
                        $this->ws = ($ns > 1) ? ($wmax - $ls) / 1000 * $this->FontSize / ($ns - 1) : 0;
                        $this->_out(sprintf('%.3F Tw', $this->ws * $this->k));
                    }
                    //Increase Height
                    $height += $h;
                    $i = $sep + 1;
                }
                $sep = -1;
                $j = $i;
                $l = 0;
                $ns = 0;
            } else
                $i++;
        }
        // Last chunk
        if ($this->ws > 0) {
            $this->ws = 0;
            $this->_out('0 Tw');
        }
        //Increase Height
        $height += $h;

        return $height;
    }


    function GetCurrentWidth()
    {
        return $this->w - ($this->lMargin * 2);
    }
}

$largo_detalle_inicio = 5;
$largo_detalle = 100;
$largo_detalle_segundo = 23;


$pdf = new PDF('P', 'mm', array(77, 260));
date_default_timezone_set('America/Guayaquil');

$fecha = date('Y-m-d H:i:s', time());
$cierre = obtenerCierre($_GET["id"]);

$pdf->AddPage();
$pdf->setTitle('Cierre de Caja');

$pdf->SetMargins(5, 0);
$pdf->Ln(0);
$cw = $pdf->GetCurrentWidth();

$pdf->SetY(5);
$cw = $pdf->GetCurrentWidth();
$pdf->SetFont('Arial', 'B', 9);
if ($cierre["estado"] == 'Pasivo') {
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell($cw, 4, utf8_decode("ANULADO"), 0, 1, "C");
    $pdf->Ln(3);
    $pdf->SetFont('Arial', 'B', 9);
}
$pdf->MultiCell($cw, 4, utf8_decode($_SESSION["nombre_empresa"]), 0, "C");
$pdf->Ln(2);
$pdf->Cell($cw, 4, utf8_decode("CIERRE DE CAJA"), 0, 1, "C");
$pdf->SetFont('Arial', '', 9);
$pdf->Cell($cw, 2, utf8_decode("-------------------------------------------------------------------"), 0, 1, "C");
$pdf->Ln(2);

/* $pdf->SetFont('Arial', 'B', 9);
$pdf->MultiCell($cw, 4, utf8_decode($_SESSION["nombre_empresa"]), 0, "C");
$pdf->Ln(2);
$pdf->Cell($cw, 4, utf8_decode("CIERRE DE CAJA"), 0, 1, "C");
$pdf->SetFont('Arial', '', 9);
$pdf->Cell($cw, 2, utf8_decode("-------------------------------------------------------------------"), 0, 1, "C");
$pdf->Ln(2); */

$pdf->Cell($cw, 4, utf8_decode("Fecha de cierre: $cierre[fecha_cierre]"), 0, 1, "L");
$pdf->Cell($cw, 4, utf8_decode("Hora de cierre: $cierre[hora_cierre]"), 0, 1, "L");
$pdf->Cell($cw, 4, utf8_decode("Usuario de cierre: $cierre[usuario]"), 0, 1, "L");
$pdf->Cell($cw, 2, utf8_decode("-------------------------------------------------------------------"), 0, 1, "C");
$pdf->Ln(2);

$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell($cw / 2, 4, "MONTO DE APERTURA:", 0, 0, "L");
$pdf->Cell($cw / 2, 4, "$$cierre[monto_apertura]", 0, 1, "R");
$pdf->SetFont('Arial', '', 9);
$pdf->Cell($cw, 2, utf8_decode("-------------------------------------------------------------------"), 0, 1, "C");
$pdf->Ln(2);

$pdf->Cell($cw, 4, utf8_decode("DESGLOSE DE CIERRE DE CAJA:"), 0, 1, "L");
$pdf->Cell($cw, 2, utf8_decode("-------------------------------------------------------------------"), 0, 1, "C");
$pdf->Ln(2);

$w = $cw / 3;
$pdf->SetWidths([$w + 4, $w - 2, $w - 2]);
$pdf->SetAligns(["C", "C", "C"]);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Row(array(utf8_decode("Denominación"), utf8_decode("Cantidad"), "Valor"));
$pdf->SetFont('Arial', '', 9);
$pdf->SetAligns(["L", "C", "R"]);
$pdf->Row(array(utf8_decode($cierre["denominacion_cien"]), $cierre["cantidad_cien"], $cierre["total_cantidad_cien"]));
$pdf->Row(array(utf8_decode($cierre["denominacion_cincuenta"]), $cierre["cantidad_cincuenta"], $cierre["total_cantidad_cincuenta"]));
$pdf->Row(array(utf8_decode($cierre["denominacion_veinte"]), $cierre["cantidad_veinte"], $cierre["total_cantidad_veinte"]));
$pdf->Row(array(utf8_decode($cierre["denominacion_diez"]), $cierre["cantidad_diez"], $cierre["total_cantidad_diez"]));
$pdf->Row(array(utf8_decode($cierre["denominacion_cinco"]), $cierre["cantidad_cinco"], $cierre["total_cantidad_cinco"]));
$pdf->Row(array(utf8_decode($cierre["denominacion_uno"]), $cierre["cantidad_uno"], $cierre["total_cantidad_uno"]));

$pdf->Row(array(utf8_decode($cierre["denominacion_cero_cincuenta"]), $cierre["cantidad_cero_cincuenta"], $cierre["total_cantidad_cero_cincuenta"]));
$pdf->Row(array(utf8_decode($cierre["denominacion_cero_veinticinco"]), $cierre["cantidad_cero_veinticinco"], $cierre["total_cantidad_cero_veinticinco"]));
$pdf->Row(array(utf8_decode($cierre["denominacion_cero_diez"]), $cierre["cantidad_cero_diez"], $cierre["total_cantidad_cero_diez"]));
$pdf->Row(array(utf8_decode($cierre["denominacion_cero_cinco"]), $cierre["cantidad_cero_cinco"], $cierre["total_cantidad_cero_cinco"]));
$pdf->Row(array(utf8_decode($cierre["denominacion_cero_uno"]), $cierre["cantidad_cero_uno"], $cierre["total_cantidad_cero_uno"]));
$pdf->Ln(2);
$total =
    $cierre["total_cantidad_cien"] +
    $cierre["total_cantidad_cincuenta"] +
    $cierre["total_cantidad_veinte"] +
    $cierre["total_cantidad_diez"] +
    $cierre["total_cantidad_cinco"] +
    $cierre["total_cantidad_uno"] +
    $cierre["total_cantidad_cero_cincuenta"] +
    $cierre["total_cantidad_cero_veinticinco"] +
    $cierre["total_cantidad_cero_diez"] +
    $cierre["total_cantidad_cero_cinco"] +
    $cierre["total_cantidad_cero_uno"];

$pdf->Cell(($w * 2) + 2, 5, "TOTAL:", "T");
$pdf->Cell($w - 2, 5, "$" . $total, "T", 1, "R");
$pdf->Cell($cw, 2, utf8_decode("-------------------------------------------------------------------"), 0, 1, "C");
$pdf->Ln(2);

$totalentregar = /*$cierre["monto_apertura"] +*/ $total;
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(($w * 2) + 2, 4, "TOTAL ENTREGAR:", 0, 0, "L");
$pdf->Cell($w - 2, 5, "$" . $totalentregar, 0, 1, "R");
$pdf->SetFont('Arial', '', 9);
$pdf->Cell($cw, 2, utf8_decode("-------------------------------------------------------------------"), 0, 1, "C");
$pdf->Ln(2);

$pdf->Cell($cw, 4, "OBSERVACIONES DE CIERRE: ", 0, 1, "L");
$pdf->Ln(1);
$pdf->Cell($cw, 4, $cierre["observacion_cierre"], 0, 1, "L");



$pdf->Output();

function obtenerCierre($id)
{
    $sql = "
    select
    cc.*,
    u.usuario
    from cierre_caja cc 
    inner join usuario u
    using(id_usuario)
    where id_cierre_caja=$id";
    $res = pg_query($sql);
    return pg_fetch_assoc($res);
}


exit();

$pdf->SetFont('Arial', '', 8);
$total_movi = 0;
$sub_total = 0;
$sub_total_faltante = 0;
$sub = 0;
$sub_des_cien = 0;
$sub_des_cincuenta = 0;
$sub_des_veinte = 0;
$sub_des_diez = 0;
$sub_des_cinco = 0;
$sub_des_uno = 0;
$sub_des_cero_cincuenta = 0;
$sub_des_cero_veinticinco = 0;
$sub_des_cero_diez = 0;
$sub_des_cero_cinco = 0;
$sub_des_cero_uno = 0;









$fecha_actual = '';
$id_usuario = '';
$tt = 0;
$total_desglose = 0;
$nombre_usuario = '';
$fecha_cierre = '';
$hora_cierre = '';
$fecha_hora_cierre = '';
$cierre_caja_ant = '';
$total_cierre_rojo = '';
$monto_apertura = '';
$total_rojo_m_apertura = '0';
$sql = pg_query("SELECT cierre_caja.fecha_actual,hora_actual,usuario,cierre_caja.id_usuario,totales_dierio_caja,total_valor_ingresado,monto_apertura
  FROM cierre_caja,usuario  
  where  usuario.id_usuario=cierre_caja.id_usuario  and comprobante='$_GET[id]' limit 1");

$numfilas = pg_num_rows($sql);
while ($row = pg_fetch_row($sql)) {
    $fecha_actual = $row[0];
    $id_usuario = $row[3];
    $nombre_usuario = $row[2];
    $fecha_hora_cierre = $row[0] . "" . "   " . $row[1];
    $cierre_caja_ant = $row[4]; //DIARIO CAJA EFECTIVO
    $total_cierre_rojo = $row[5]; //TOTAL DIARIO CAJA COLOR ROJO
    $monto_apertura = $row[6]; //MONTO APERTURA
}


$total = 0;
$contado = 0;
$credito = 0;
$cheque = 0;
$gastos = 0;
$notaVentacont = 0;
$notaVentacredito = 0;
$tarjetaCredito = 0;
$cxce = 0;
$cxcc = 0;
$cxct = 0;
$ncred = 0;

$total_diario_caja = $cierre_caja_ant;
$var_total_movi = 0;


$sql = pg_query("SELECT denominacion_cien,cc.cantidad_cien,cc.valor_cien
  FROM cierre_caja cc  
  where  comprobante='$_GET[id]'");
$apertura = 0;
$sql1 = pg_query("SELECT nombre_comercial, ruc_empresa,u.usuario, t.fecha_actual, t.hora_actual,total_debe
    FROM empresa e, usuario u ,transacciones t
    WHERE t.id_usuario= u.id_usuario and identificador_cli_pro='AUD' and concepto like '%INICIO%' 
    order by id_transacciones desc limit 1");

while ($fila = pg_fetch_row($sql1)) {
    $apertura = $fila[5];
}
while ($fila = pg_fetch_row($sql)) {

    $sub = $sub + $fila[2];
    //////$sub =TOTAL VALOR CIERRE DE CAJA 48- 50=-2
    $sub_total_faltante = $sub - $total_cierre_rojo;
}
$faltante_caja_ant = 0;
$faltante_caja = 0;
$tt = $sub_total_faltante - $total_diario_caja;
$faltante_caja_ant = ($sub - $total_diario_caja);



//TOTAL COLOR ROJO  -  MONTO APERTURA;
$total_rojo_m_apertura = $total_cierre_rojo - $monto_apertura;
//TOTAL DIARIO CAJA SISTEMA - TOTAL INGRESO BILLETES RESTADO MONTO INGRESO
$faltante_caja = $total_rojo_m_apertura - $cierre_caja_ant;




//print_r($total_diario_caja);
/* $sql = pg_query("SELECT nombre_comercial, ruc_empresa,u.usuario, t.fecha_actual, t.hora_actual,total_debe
    FROM empresa e, usuario u ,transacciones t
    WHERE t.id_usuario= u.id_usuario and identificador_cli_pro='AUD' and concepto like '%INICIO%' and t.id_usuario='$_SESSION[id]' order by fecha_actual desc limit 1"); */

$numfilas = pg_num_rows($sql);
for ($i = 0; $i < $numfilas; $i++) {
    $largo_detalle_inicio += 2;
    $fila = pg_fetch_row($sql);
    /*     $pdf->SetFont('Arial', '', 11);
    $pdf->Text(12, $largo_detalle_inicio + 1, utf8_decode('' . "CIERRE DIA/TURNO: VITAL CAHUASQUI"), 0, 'C', 0); // 1:  
    $pdf->Text(30, 15, utf8_decode('' . strtoupper($fila[1])), 0, 'C', 0); 
    $pdf->SetFont('Arial', '', 11);
    $pdf->Text(28, $largo_detalle_inicio + 4, utf8_decode('' . "CIERRE DE CAJA"), 0, 'C', 0);*/

    /* $pdf->SetFont('Arial', '', 9);
    $pdf->Text(10, $largo_detalle_inicio + 18, utf8_decode('' . "Usuario Apertura:"), 0, 'C', 0);
    $pdf->Text(37, $largo_detalle_inicio + 18, utf8_decode('' . strtoupper($fila[2])), 0, 'C', 0);

    $pdf->Text(10, $largo_detalle_inicio + 22, utf8_decode('' . "Fecha Aper:"), 0, 'C', 0);
    $pdf->Text(37, $largo_detalle_inicio + 22, utf8_decode('' . strtoupper($fila[3] . "  " . $fila[4])), 0, 'C', 0); */ ////CLIENTE (X,Y)
    //    $result = pg_query("SELECT COUNT(*) AS count FROM plan_cuentas where cuenta='M' and  descripcion like '%BANCO%' or descripcion like '%MUTU%' ");
    //    $row = pg_fetch_row($result);
    //    $count = $row[0];
    //    
    $pdf->SetFont('Arial', '', 9);
    //    $pdf->Text(10, 53, utf8_decode('' . "COMPROBANTES EMITIDOS" . "                    $" . $fila[5]), 0, 'C', 0); ////CLIENTE (X,Y)   

    $var_total_movi = $monto_apertura + $total_rojo_m_apertura - $faltante_caja;
    $pdf->Text(10, $largo_detalle_inicio + 57, utf8_decode('' . "TOTAL ENTREGAR:"), 0, 'C', 0); ////CLIENTE (X,Y)   
    $pdf->Text(70, $largo_detalle_inicio + 57, "$" . truncateFloat($monto_apertura + $total_rojo_m_apertura, 2), 0, 'C', 0);

    $total_movi = $fila[5];
    $pdf->Ln(50);
}
///////////////////////////////////////////////////////////////////////////////

for ($i = 0; $i < $numfilas; $i++) {
    $largo_detalle_segundo += 2;
    $fila = pg_fetch_row($sql);

    $fecha_actual = $fila[0];

    /* $pdf->Text(10, $largo_detalle_segundo + 10, utf8_decode('' . "Usuario cie:"), 0, 'C', 0);
    $pdf->Text(37, $largo_detalle_segundo + 10, utf8_decode('' . strtoupper($nombre_usuario)), 0, 'C', 0);


    $pdf->Text(10, $largo_detalle_segundo + 15, utf8_decode('' . "Fecha cie:"), 0, 'C', 0);
    $pdf->Text(37, $largo_detalle_segundo + 15, utf8_decode('' . $fecha_hora_cierre), 0, 'C', 0); */

    /* $pdf->SetX(10);
    $pdf->Text(15, $largo_detalle_segundo + 20, utf8_decode('' . "TICKETS EMITIDOS:"), 0, 'C', 0);
    $pdf->Text(5, (49), utf8_decode('-----------------------------------------------------------------------------'), 0, 'C', 0); */ ////CLIENTE (X,Y)




    /*  $pdf->Text(10, $largo_detalle_inicio + 45, utf8_decode('' . "TOTAL EFECTIVO APERTURA"), 0, 'C', 0); ////CLIENTE (X,Y)   
    $pdf->Text(70, $largo_detalle_inicio + 45, "$" . truncateFloat($monto_apertura, 2), 0, 'C', 0);
 */

    $pdf->Text(10, $largo_detalle_inicio + 49, utf8_decode('' . "TOTAL EFECTIVO CIERRE"), 0, 'C', 0); ////CLIENTE (X,Y)   
    $pdf->Text(70, $largo_detalle_inicio + 49, "$" . truncateFloat($total_rojo_m_apertura, 2), 0, 'C', 0);


    $pdf->SetFont('Arial', '', 9);
    //    $pdf->Text(10, 60, utf8_decode('' . "FALTANTE: CAJA"), 0, 'C', 0); ////CLIENTE (X,Y)  
    //    $pdf->Text(70, $largo_detalle_inicio + 53, "$" . truncateFloat($faltante_caja, 2), 0, 'C', 0);


    $pdf->Text(10, $largo_detalle_inicio + 64, utf8_decode('' . "Desglose del cierre de caja:"), 0, 'C', 0);

    $pdf->Text(5, ($largo_detalle_inicio + 66), utf8_decode('-----------------------------------------------------------------------------'), 0, 'C', 0); ////CLIENTE (X,Y)
    $pdf->Ln(15);
}

$pdf->SetX(10);
$pdf->SetWidths(array(40, 20, 20));

$sql_cien = pg_query("SELECT denominacion_cien,cc.cantidad_cien,cc.total_cantidad_cien
  FROM cierre_caja cc where comprobante='$_GET[id]'");

$sql_cincuenta = pg_query("SELECT denominacion_cincuenta,cc.cantidad_cincuenta,cc.total_cantidad_cincuenta
  FROM cierre_caja cc where comprobante='$_GET[id]'");

$sql_veinte = pg_query("SELECT denominacion_veinte,cc.cantidad_veinte,cc.total_cantidad_veinte
  FROM cierre_caja cc where comprobante='$_GET[id]'");

$sql_diez = pg_query("SELECT denominacion_diez,cc.cantidad_diez,cc.total_cantidad_diez
  FROM cierre_caja cc where comprobante='$_GET[id]'");

$sql_cinco = pg_query("SELECT denominacion_cinco,cc.cantidad_cinco,cc.total_cantidad_cinco
  FROM cierre_caja cc where comprobante='$_GET[id]'");

$sql_uno = pg_query("SELECT denominacion_uno,cc.cantidad_uno,cc.total_cantidad_uno
  FROM cierre_caja cc where comprobante='$_GET[id]'");


$sql_cero_cincuenta = pg_query("SELECT denominacion_cero_cincuenta,cc.cantidad_cero_cincuenta,cc.total_cantidad_cero_cincuenta
  FROM cierre_caja cc where comprobante='$_GET[id]'");

$sql_cero_veinticinco = pg_query("SELECT denominacion_cero_veinticinco,cc.cantidad_cero_veinticinco,cc.total_cantidad_cero_veinticinco
  FROM cierre_caja cc where comprobante='$_GET[id]'");


$sql_cero_diez = pg_query("SELECT denominacion_cero_diez,cc.cantidad_cero_diez,cc.total_cantidad_cero_diez
  FROM cierre_caja cc where comprobante='$_GET[id]'");


$sql_cero_cinco = pg_query("SELECT denominacion_cero_cinco,cc.cantidad_cero_cinco,cc.total_cantidad_cero_cinco
  FROM cierre_caja cc where comprobante='$_GET[id]'");

$sql_cero_uno = pg_query("SELECT denominacion_cero_uno,cc.cantidad_cero_uno,cc.total_cantidad_cero_uno
  FROM cierre_caja cc where comprobante='$_GET[id]'");


$pdf->Row(array(utf8_decode("Denominaciòn"), utf8_decode("Cantidad"), " " . "Valor"));

while ($fila = pg_fetch_row($sql_cien)) {
    $largo_detalle += 20;
    $pdf->SetX(10);
    $pdf->SetFont('Arial', '', 8);
    $pdf->SetX(10);

    $pdf->Row(array(
        utf8_decode("$" . " " . $fila[0]),
        maxCaracter(utf8_decode($fila[1]), 40),
        utf8_decode("$" . " " . $fila[2])
    ));
    $sub_des_cien = $sub_des_cien + $fila[2];
}
while ($fila = pg_fetch_row($sql_cincuenta)) {
    $largo_detalle += 20;
    $pdf->SetX(10);
    $pdf->SetFont('Arial', '', 8);
    $pdf->SetX(10);

    $pdf->Row(array(
        utf8_decode("$" . " " . $fila[0]),
        maxCaracter(utf8_decode($fila[1]), 40),
        utf8_decode("$" . " " . $fila[2])
    ));
    $sub_des_cincuenta = $sub_des_cincuenta + $fila[2];
}
while ($fila = pg_fetch_row($sql_veinte)) {
    $largo_detalle += 20;
    $pdf->SetX(10);
    $pdf->SetFont('Arial', '', 8);
    $pdf->SetX(10);

    $pdf->Row(array(
        utf8_decode("$" . " " . $fila[0]),
        maxCaracter(utf8_decode($fila[1]), 40),
        utf8_decode("$" . " " . $fila[2])
    ));
    $sub_des_veinte = $sub_des_veinte + $fila[2];
}
while ($fila = pg_fetch_row($sql_diez)) {
    $largo_detalle += 20;
    $pdf->SetX(10);
    $pdf->SetFont('Arial', '', 8);
    $pdf->SetX(10);

    $pdf->Row(array(
        utf8_decode("$" . " " . $fila[0]),
        maxCaracter(utf8_decode($fila[1]), 40),
        utf8_decode("$" . " " . $fila[2])
    ));
    $sub_des_diez = $sub_des_diez + $fila[2];
}
while ($fila = pg_fetch_row($sql_cinco)) {
    $largo_detalle += 20;
    $pdf->SetX(10);
    $pdf->SetFont('Arial', '', 8);
    $pdf->SetX(10);

    $pdf->Row(array(
        utf8_decode("$" . " " . $fila[0]),
        maxCaracter(utf8_decode($fila[1]), 40),
        utf8_decode("$" . " " . $fila[2])
    ));
    $sub_des_cinco = $sub_des_cinco + $fila[2];
}
while ($fila = pg_fetch_row($sql_uno)) {
    $largo_detalle += 20;
    $pdf->SetX(10);
    $pdf->SetFont('Arial', '', 8);
    $pdf->SetX(10);

    $pdf->Row(array(
        utf8_decode("$" . " " . $fila[0]),
        maxCaracter(utf8_decode($fila[1]), 40),
        utf8_decode("$" . " " . $fila[2])
    ));
    $sub_des_uno = $sub_des_uno + $fila[2];
}
while ($fila = pg_fetch_row($sql_cero_cincuenta)) {
    $largo_detalle += 20;
    $pdf->SetX(10);
    $pdf->SetFont('Arial', '', 8);
    $pdf->SetX(10);

    $pdf->Row(array(
        utf8_decode("$" . " " . $fila[0]),
        maxCaracter(utf8_decode($fila[1]), 40),
        utf8_decode("$" . " " . $fila[2])
    ));
    $sub_des_cero_cincuenta = $sub_des_cero_cincuenta + $fila[2];
}
while ($fila = pg_fetch_row($sql_cero_veinticinco)) {
    $largo_detalle += 20;
    $pdf->SetX(10);
    $pdf->SetFont('Arial', '', 8);
    $pdf->SetX(10);

    $pdf->Row(array(
        utf8_decode("$" . " " . $fila[0]),
        maxCaracter(utf8_decode($fila[1]), 40),
        utf8_decode("$" . " " . $fila[2])
    ));
    $sub_des_cero_veinticinco = $sub_des_cero_veinticinco + $fila[2];
}
while ($fila = pg_fetch_row($sql_cero_diez)) {
    $largo_detalle += 20;
    $pdf->SetX(10);
    $pdf->SetFont('Arial', '', 8);
    $pdf->SetX(10);

    $pdf->Row(array(
        utf8_decode("$" . " " . $fila[0]),
        maxCaracter(utf8_decode($fila[1]), 40),
        utf8_decode("$" . " " . $fila[2])
    ));
    $sub_des_cero_diez = $sub_des_cero_diez + $fila[2];
}
while ($fila = pg_fetch_row($sql_cero_cinco)) {
    $largo_detalle += 20;
    $pdf->SetX(10);
    $pdf->SetFont('Arial', '', 8);
    $pdf->SetX(10);

    $pdf->Row(array(
        utf8_decode("$" . " " . $fila[0]),
        maxCaracter(utf8_decode($fila[1]), 40),
        utf8_decode("$" . " " . truncateFloat(round($fila[2], 2, PHP_ROUND_HALF_EVEN), 2))
    ));
    $sub_des_cero_cinco = $sub_des_cero_cinco + $fila[2];
}
while ($fila = pg_fetch_row($sql_cero_uno)) {
    $largo_detalle += 20;
    $pdf->SetX(10);
    $pdf->SetFont('Arial', '', 8);
    $pdf->SetX(10);

    $pdf->Row(array(
        utf8_decode("$" . " " . $fila[0]),
        maxCaracter(utf8_decode($fila[1]), 40),
        utf8_decode("$" . " " . truncateFloat(round($fila[2], 2, PHP_ROUND_HALF_EVEN), 2))
    ));

    $sub_des_cero_uno = $sub_des_cero_uno + $fila[2];
}

$sub_total = $sub - $total_movi;


$pdf->SetY($largo_detalle);



$total_desglose = $sub_des_cien - $var_total_movi;

$pdf->Text(45, ($largo_detalle - 180), utf8_decode('Total:'), 0, 'C', 0);
$pdf->Text(70, $largo_detalle - 180, "$" . truncateFloat(
    $sub_des_cien + $sub_des_cincuenta + $sub_des_veinte + $sub_des_diez + $sub_des_cinco + $sub_des_uno + $sub_des_cero_cincuenta + $sub_des_cero_veinticinco + $sub_des_cero_diez + $sub_des_cero_cinco + $sub_des_cero_uno,
    2
), 0, 'C', 0);

//$pdf->SetX(4);
//$pdf->SetY($largo_detalle);
//$pdf->Row(array("$$"));
//$pdf->Text(10, ($largo_detalle - 10), utf8_decode('' . " Detalle de forma de pago"), 0, 'C', 0); ////CLIENTE (X,Y) 
//$pdf->Text(10, ($largo_detalle - 8), utf8_decode('' . " Efectivo en caja" . "                                 $" . truncateFloat(round($total_desglose, 2, PHP_ROUND_HALF_EVEN), 2)), 0, 'C', 0);
//$pdf->Text(10, ($largo_detalle - 5), utf8_decode('' . "Total pagos" . "                                        $" . truncateFloat(round($total_desglose, 2, PHP_ROUND_HALF_EVEN), 2)), 0, 'C', 0);
//$pdf->Text(10, ($largo_detalle - 150), utf8_decode('           ' . "Entregado por:" . "                           " . "Recibido por:"), 0, 'C', 0); ////CLIENTE (X,Y)   


//$pdf->Text(10, ($largo_detalle - 160), utf8_decode("           " . '------------------------' . "                       " . '-------------------'), 0, 'C', 0); ////CLIENTE (X,Y)



$pdf->Output();
