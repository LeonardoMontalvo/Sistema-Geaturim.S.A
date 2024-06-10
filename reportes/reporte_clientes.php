<?php
session_start();
error_reporting(0);
include __DIR__ . "/../fpdf/fpdf.php";
include __DIR__ . "/../procesos/base.php";

class PDF extends FPDF
{

    var $widths;
    var $aligns;

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

    function Row($data, $border = 0, $style = "", $fill = false)
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

            $this->MultiCell($w, 5, $data[$i], 0, $a, $fill);
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

    function Header()
    {
        $w = $this->GetCurrentWidth();
        $this->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->SetFont('Amble-Regular', '', 10);
        $fecha = date('Y-m-d', time());
        $this->SetX(0);
        $this->SetY(0);
        $this->Cell($w / 2, 5, $fecha, 0, 0, 'C', 0);
        $this->Cell($w / 2, 5, "CLIENTES", 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell($w, 8, utf8_decode($_SESSION['nombre_empresa']), 0, 1, 'C', 0);
        $this->Image('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 10, 7, 15, 15);
        $this->Image('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], $w-15, 7, 15, 15);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);
        $this->Line(0, 25, $w, 25);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell($w, 5, utf8_decode("LISTA DE CLIENTES"), 0, 1, 'C', 0);
        $this->Ln(7);
        $this->SetX(0);
        $this->SetFont('helvetica', 'B', 9);
        $this->SetFillColor(175, 215, 240);

        $this->Ln(1);
        $cw = $w / 6;
        $this->Cell($cw - 20, 6, utf8_decode("IDENTIFICACIÓN"), 1, 0, 'C', 1);
        $this->Cell($cw + 20, 6, utf8_decode("NOMBRE"), 1, 0, 'C', 1);
        $this->Cell($cw + 20, 6, utf8_decode("DIRECCIÓN"), 1, 0, 'C', 1);
        $this->Cell($cw - 20, 6, utf8_decode("CELULAR"), 1, 0, 'C', 1);
        $this->Cell($cw - 20, 6, utf8_decode("TELÉFONO"), 1, 0, 'C', 1);
        $this->Cell($cw + 20, 6, utf8_decode("CORREO"), 1, 1, 'C', 1);
    }

    function Footer()
    {
        $this->SetY(-10);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDF('L', 'mm', 'A4');
$pdf->SetMargins(5, 0);
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true, 10);
$pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
$pdf->SetFont('Amble-Regular', '', 9);
$pdf->SetFont('Arial', '', 9);


$sql = "select*from clientes where estado='Activo'";
$res = pg_query($sql);
$rows = pg_fetch_all($res);
if (empty($rows)) {
    $rows = [];
}

$w = $pdf->GetCurrentWidth();
$cw = $w / 6;
foreach ($rows as $value) {
    $pdf->SetWidths([$cw - 20, $cw + 20, $cw + 20, $cw - 20, $cw - 20, $cw + 20]);
    $pdf->SetAligns(['L', 'L', 'L', 'L', 'L']);
    $pdf->Row([
        $value["identificacion"],
        utf8_decode($value["nombres_cli"]),
        utf8_decode($value["direccion_cli"]),
        $value["celular"],
        $value["telefono"],
        $value["correo"],
    ], 1);
}

$pdf->Output();
