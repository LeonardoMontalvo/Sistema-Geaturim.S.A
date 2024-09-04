<?php
require('../fpdf/fpdf.php');
include '../procesos/base.php';
include '../procesos/funciones.php';
require_once __DIR__ . "/../procesos/ConversorValores.php";
conectarse();
date_default_timezone_set('America/Guayaquil');
session_start();
error_reporting(0);
class PDF extends FPDF
{
    var $widths;
    var $aligns;
    var $nroRecibo;
    var $comprobante;

    function SetWidths($w)
    {
        $this->widths = $w;
    }

    function SetNroRecibo($nr)
    {
        $this->nroRecibo = $nr;
    }

    function Header()
    {
        $this->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->AddFont('helvetica', 'B', 'helveticab.php');
        $this->SetFont('Amble-Regular', '', 10);
        $fecha = date('Y-m-d', time());
        $hora = date('H:i:s', time());
        $this->comprobante = $comp = obtenerComprobante();
        $pagew = $this->GetCurrentWidth();

        $this->Cell($pagew / 2, 4, $fecha, 0, 0, "R");
        $this->Cell($pagew / 2, 4, $hora, 0, 1, "L");
        $this->Ln(3);
        $this->Cell($pagew / 2, 4, utf8_decode($_SESSION['nombre_empresa']), 0, 0, "L");
        $this->Cell($pagew / 2, 4, "No. " . str_pad($this->nroRecibo, 8, '0', STR_PAD_LEFT), 0, 1, "L");
        $this->Cell($pagew / 2, 4, "", 0, 0, "L");
        $this->Cell($pagew / 2, 4, "Por: " . $comp["valor_pagado"], 0, 1, "L");
        $this->Ln(3);
        $this->Cell($pagew / 2, 4, utf8_decode("Nombre: " . $comp["nombres_cli"]), 0, 1, "L");
        $this->Cell($pagew / 2, 4, utf8_decode("Cécula: " . $comp["identificacion"]), 0, 1, "L");
        $this->Cell($pagew / 2, 4, "Fecha: " . $comp["fecha_actual"], 0, 1, "L");
    }

    function Footer()
    {
       /*  $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C'); */
    }

    function SetAligns($a)
    {
        //Set the array of column alignments

        $this->aligns = $a;
    }

    function Row($data, $border = 0, $style = "", $fill = false, $hr = 5)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        $h = $hr * $nb;
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

            $this->MultiCell($w, $hr, $data[$i], 0, $a, $fill);
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

$pdf = new PDF('P', 'mm', 'a4');
$pdf->SetNroRecibo($_GET["comprobante"]);
$pdf->SetTitle('Recibo Pago');
$pdf->SetMargins(10, 2);
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Amble-Regular', '', 9);


$pdf->Ln(4);
$pagos = obtenerPagosComp();
$pagew = $pdf->GetCurrentWidth();
$w = $pagew / 6;

$pdf->Cell($pagew, 4, "ABONO A FACTURAS", 0, 1);
$pdf->Ln(2);
$pdf->SetAligns(array_fill(0, 7, "C"));
$pdf->SetWidths([$w + 20, $w - 10, $w - 10, $w, $w, $w]);
$pdf->Row(["Factura", "Fecha emi.", "Fecha ven.", "Valor", "Abono", "Saldo"], 1);
$pdf->SetAligns(["L", "L", "L", "R", "R", "R"]);
foreach ($pagos as $key => $value) {

    $pdf->Row([
        $value["num_factura"],
        $value["fecha_actual"],
        (!empty($value["fecha_vencimiento_c"]) ? $value["fecha_vencimiento_c"] : (!empty($value["fecha_vencimiento_c"]) ? $value["fecha_vencimiento_c"] : $value["fecha_vencimiento_c"])),
        $value["total_factura"],
        $value["valor_pagado"],
        $value["saldo_factura"],
    ]);
}
$pdf->Cell($pagew, 2, "", "T", 1);

$conversor = new ConversorValores();
$pdf->Cell($pagew, 5, utf8_decode("SON: " . $conversor->convertirCifrasATexto(number_format($pdf->comprobante["valor_pagado"], 2, ".", ""))), 0, 1, "L");
$pdf->Ln(3);

$w = ($pagew - 10) / 5;
$formasp = obtenerFormasPago();
$pdf->SetWidths([$w, $w, $w + 30, $w - 15, $w - 15]);
$pdf->SetAligns(["L", "L", "L", "L", "L"]);
foreach ($formasp as $key => $value) {
    $nrdoc = "No. " . $value["numero_documento"];
    if (empty($value["numero_documento"])) {
        $nrdoc = "";
    }
    $pdf->Row([
        $value["forma_pago"],
        $nrdoc,
        trim(utf8_decode($value["descripcion"])),
        $value["fecha_forma"],
        "$" . $value["valor"],
    ], 0, "", false, 4);
}

$pdf->Ln(20);
$pdf->SetFont('Amble-Regular', '', 10);
$nombreu = mb_strtoupper(utf8_decode($pdf->comprobante["nombre_usuario"] . " " . $pdf->comprobante["apellido_usuario"]));
$pdf->Cell($pagew / 2, 4, $nombreu, 0, 0, "C");
$pdf->Cell($pagew / 2, 4, utf8_decode("Recibí conforme"), 0, 1, "C");
$pdf->Output();


function obtenerComprobante()
{
    $sql = "select
    pp.comprobante,
    p.identificacion,
    p.nombres_cli,
    sum(pp.valor_pagado)valor_pagado,
    pp.fecha_actual,
    pp.hora_actual,
    u.nombre_usuario,
    u.apellido_usuario,
    pp.id_cliente,
    p.tipo_documento
    from pagos_cobrar pp
    inner join clientes p using(id_cliente) 
    inner join usuario u using(id_usuario)
    where pp.estado<>'Anulado'
    and comprobante='$_GET[comprobante]'
    group by pp.comprobante,
    pp.id_cliente,
    p.identificacion,
    p.nombres_cli,
    pp.fecha_actual,
    pp.hora_actual,
    u.nombre_usuario,
    u.apellido_usuario,
    p.tipo_documento 
    ";
    $res = pg_query($sql);
//    echo ''.$sql;
    $rows = pg_fetch_assoc($res);
    if (empty($rows)) {
        $rows = [];
    }
    return $rows;
}

function obtenerPagosComp()
{
    $sql = "select pp.*,
    fpc.fecha_actual fecha_vencimiento_c from pagos_cobrar pp
    left join factura_venta fv
     on pp.num_factura=fv.num_factura
     left join formas_pago_mixto fpc
    on fv.id_factura_venta=fpc.id_factura_venta 
    left join facturas_novalidas fnv
    on pp.num_factura=fnv.comprobante
     where pp.estado<>'Anulado'
    and pp.comprobante='$_GET[comprobante]' ";

    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        $rows = [];
    }
    return $rows;
}

function obtenerFormasPago()
{
    $sql = "select fp.*,pc.descripcion from formas_pago_mixto_cxc fp
    left join plan_cuentas pc on id_cuenta::integer=pc.id_plan_cuentas
    where comprobante_pago='$_GET[comprobante]'";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        $rows = [];
    }
    return $rows;
}
