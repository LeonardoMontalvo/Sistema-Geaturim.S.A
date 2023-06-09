<?php
require('../fpdf/fpdf.php');
include '../procesos/base.php';
include '../procesos/funciones.php';
session_start();
conectarse();

$id = $_GET["id"];
class PDF extends FPDF
{

    var $widths;
    var $aligns;

    // Page header
    function Header()
    {
        // Logo
        //$this->Image('../images/logo_factura_venta.jpeg', 5, 0, 70);
        // Arial bold 15
        //$this->SetFont('Arial', 'B', 9);
        // Move to the right
        /* $this->setY(2);
        $this->Cell(47, 2); */
        // Title
        // Line break
        //$this->Ln(10);
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

    function Row($data)
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
            //Draw the border
            //$this->Rect($x,$y,$w,$h);

            $this->MultiCell($w, 5, $data[$i], 0, $a, false);
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

    function GetCurrentWidth()
    {
        return $this->w - ($this->lMargin * 2);
    }
}




$pdf = new PDF('P', 'mm', array(77, 130));
date_default_timezone_set('America/Guayaquil');

$apertura = obtenerApertura($id);
$pdf->AddPage();
$pdf->setTitle('Apertura de caja');
$pdf->SetMargins(5, 0);
$pdf->Ln(0);

$cw = $pdf->GetCurrentWidth();

$pdf->SetFont('Arial', 'B', 9);
if ($apertura["estado"] == 'Pasivo') {
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell($cw, 4, utf8_decode("ANULADO"), 0, 1, "C");
    $pdf->Ln(3);
    $pdf->SetFont('Arial', 'B', 9);
}
$pdf->MultiCell($cw, 4, utf8_decode($_SESSION["nombre_empresa"]), 0, "C");
$pdf->Ln(2);
$pdf->Cell($cw, 4, utf8_decode("APERTURA DE CAJA"), 0, 1, "C");

$pdf->SetFont('Arial', '', 9);
$pdf->Cell($cw, 2, utf8_decode("-------------------------------------------------------------------"), 0, 1, "C");
$pdf->Ln(2);
$pdf->Cell($cw, 4, utf8_decode("Fecha de apertura: $apertura[fecha_actual]"), 0, 1, "L");
$pdf->Cell($cw, 4, utf8_decode("Hora de apertura: $apertura[hora_actual]"), 0, 1, "L");
$pdf->Cell($cw, 4, utf8_decode("Usuario de apertura: $apertura[usuario]"), 0, 1, "L");
$pdf->Cell($cw, 2, utf8_decode("-------------------------------------------------------------------"), 0, 1, "C");
$pdf->Ln(2);

$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell($cw / 2, 4, "MONTO DE APERTURA:", 0, 0, "L");
$pdf->Cell($cw / 2, 4, $apertura["monto_apertura"], 0, 1, "R");
$pdf->SetFont('Arial', '', 9);
$pdf->Ln(2);

$pdf->Cell($cw, 4, "OBSERVACIONES DE APERTURA:", 0, 1, "L");
$pdf->Ln(1);
$pdf->MultiCell($cw, 4, utf8_decode($apertura["observacion"]), 0, "J");

$pdf->Output();

function obtenerApertura($id)
{
    $sql = "
    select
    cc.fecha_actual,
    cc.hora_actual,
    cc.monto_apertura,
    cc.observacion,
    u.usuario,
    cc.estado
    from cierre_caja cc 
    inner join usuario u
    using(id_usuario)
    where id_cierre_caja=$id";
    $res = pg_query($sql);
    return pg_fetch_assoc($res);
}
