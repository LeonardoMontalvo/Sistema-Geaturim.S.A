<?php
require('../fpdf/fpdf.php');
include '../procesos/base.php';
include '../procesos/funciones.php';

conectarse();
date_default_timezone_set('America/Guayaquil');
session_start();

$idpv = $_GET["id_empre"];
$idusuario = $_GET["id"];
$idproveedor = $_GET["id_proveedor"];
$querypunto = "";
$queryusuario = "";
$querycli = "";
if (!empty($idpv)) {
    $querypunto = " and dv.id_empresa=$idpv";
}
if (!empty($idusuario)) {
    $queryusuario = " and dv.id_usuario=$idusuario";
}
if (!empty($idproveedor)) {
    $querycli = " and dv.id_proveedor=$idproveedor";
}
class PDF extends FPDF
{
    var $widths;
    var $aligns;
    var $rango;

    function Header()
    {
        $this->rango = false;
        if ($_GET['inicio'] != '') {
            $this->rango = true;
        }
        $this->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->AddFont('helvetica', 'B', 'helveticab.php');
        $this->SetFont('Amble-Regular', '', 10);
        $this->fecha = date('Y-m-d', time());
        $this->SetX(0);
        $this->SetY(0);
        $this->Cell(105, 5, $this->fecha, 0, 0, 'C', 0);
        $this->Cell(105, 5, "CARTERA VALORES NC COMPRAS", 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(210, 8, $_SESSION['nombre_empresa'], 0, 1, 'C', 0);
        $this->Image('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 10, 7, 15, 15);
        $this->Image('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 180, 7, 15, 15);
        $this->SetLineWidth(0.4);
        $this->Line(0, 28, 210, 28);
        $this->SetFont('Arial', 'B', 11);
        //$this->Cell(210, 5, utf8_decode("RESUMEN CUENTAS EXTERNAS"), 0, 1, 'C', 0);
        $this->Cell(204, 5, utf8_decode("VALORES A FAVOR DE LA EMPRESA POR NOTAS DE CRÉDITO EN COMPRAS"), 0, 1, 'C', 0);
        //$this->Cell(210, 5, utf8_decode($this->tipoCuenta), 0, 1, 'C', 0);
        $this->Ln(3);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->Ln(5);
        $this->SetLineWidth(0.2);
    }
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
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
}

$pdf = new PDF('P', 'mm', 'a4');
$pdf->SetTitle('Valores Favor Empresa NC Compras');
$pdf->SetMargins(3, 3);
$pdf->AddPage();
$pdf->AliasNbPages();

$clientes = obtenerInfoProveedor();
$total = 0;
foreach ($clientes as $value) {
    dibujarInfoProveedor($value["identificacion_pro"], $value["empresa_pro"]);
    $total += dibujarRegistrosValores($value["id_proveedor"]);
}

$pdf->Line($pdf->lMargin, $pdf->GetY(), $pdf->GetCurrentWidth(), $pdf->GetY());
$pdf->SetLineWidth(0.2);
$pdf->Cell($pdf->GetCurrentWidth() - $pdf->lMargin, 6, maxCaracter(utf8_decode("TOTAL PENDIENTE: $total"), 50), 0, 1, 'R');

$pdf->Output();

function dibujarInfoProveedor($id, $nombre)
{
    global $pdf;
    $pdf->SetFillColor(220, 240, 210);
    $pdf->SetFont('Helvetica', 'B', 9);
    $pdf->Cell(75, 6, maxCaracter(utf8_decode('RUC/CI:' . "$id"), 35), 0, 0, 'C', 1);
    $pdf->Cell(135, 6, maxCaracter(utf8_decode('NOMBRES:' . "$nombre"), 50), 0, 1, 'C', 1);
    $pdf->Ln(1);
    $pdf->SetFillColor(175, 215, 240);
}

function dibujarRegistrosValores($idproveedor)
{
    global $pdf;
    $total = 0;
    $pdf->SetFont('Helvetica', 'B', 9);
    $pdf->SetFillColor(175, 215, 240);
    $totalwidth = $pdf->GetCurrentWidth();
    $width = $totalwidth / 4;
    $pdf->SetWidths([
        $width,
        $width,
        $width,
        $width
    ]);
    $pdf->SetAligns(["C", "C", "C", "C"]);
    $pdf->Row([
        utf8_decode('N° DOCUMENTO'),
        utf8_decode('FECHA EMISIÓN'),
        utf8_decode('VALOR A FAVOR'),
        utf8_decode('ESTADO')
    ], 1, "FD");
    $pdf->Ln(1);
    $pdf->SetFont('Helvetica', '', 9);
    $valores = obtenerValoresNcProveedor($idproveedor);
    $pdf->SetAligns(["C", "C", "C", "C"]);
    foreach ($valores as $value) {
        $pdf->Row([
            $value["num_nota_credito"],
            $value["fecha_actual"],
            $value["valor"],
            $value["estado"] == 'Activo' ? 'PENDIENTE' : 'CRUZADO'
        ]);
        if ($value["estado"] == 'Activo') {
            $total += $value["valor"];
        }
    }
    $pdf->Line($pdf->lMargin, $pdf->GetY(), $pdf->GetCurrentWidth(), $pdf->GetY());
    $pdf->SetLineWidth(0.2);
    $pdf->SetFont('Helvetica', 'B', 9);
    $pdf->SetAligns(["C", "R", "C", "C"]);
    $pdf->Row([
        "",
        "TOTAL PENDIENTE:",
        $total,
        "",
    ]);
    $pdf->Ln(2);

    return $total;
}

function obtenerInfoProveedor()
{
    global $querypunto, $queryusuario, $querycli;
    $sql="
    select dv.id_proveedor, c.identificacion_pro, c.empresa_pro, fpnc.estado 
    from formas_pago_mixto_nc fpnc
    inner join devolucion_compra dv
    using(id_devolucion_compra)
    inner join proveedores c using(id_proveedor)
    where fpnc.forma_pago='VALOR_FAVOR_EMPRESA'
    and fpnc.fecha_actual between '$_GET[inicio]' and '$_GET[fin]'
    $querypunto
    $queryusuario
    $querycli
    group by id_proveedor,c.identificacion_pro, c.empresa_pro, fpnc.estado 
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}

function obtenerValoresNcProveedor($idproveedor)
{
    $sql = "
    select dv.clave num_nota_credito, fpnc.fecha_actual,fpnc.valor,fpnc.estado 
    from formas_pago_mixto_nc fpnc
    inner join devolucion_compra dv
    using(id_devolucion_compra)
    inner join proveedores c using(id_proveedor)
    where fpnc.forma_pago='VALOR_FAVOR_EMPRESA'
    and dv.id_proveedor=$idproveedor
    and fpnc.fecha_actual between '$_GET[inicio]' and '$_GET[fin]'
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}
