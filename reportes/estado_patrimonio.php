<?php
require('../fpdf/fpdf.php');
include '../procesos/base.php';
include '../procesos/funciones.php';
setlocale(LC_ALL, "es_ES@euro", "es_ES", "esp", "es", "esm", "esn", "spanish", "spanish-modern", "es-ES", "es_LA", "es-LA");

conectarse();
date_default_timezone_set('America/Guayaquil');
session_start();
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
        $this->Cell(105, 5, "CONTABILIDAD", 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(210, 8, utf8_decode($_SESSION['nombre_empresa']), 0, 1, 'C', 0);
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
        $this->Line(0, 30, 210, 30);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(210, 5, utf8_decode("ESTADO DE EVOLUCIÓN DEL PATRIMONIO"), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        /*  if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
           
        } */
        $fechastr = mb_strtoupper(strftime("%d de %B de %Y", strtotime($_GET['fin'])));
        $this->Cell(210, 5, utf8_decode('AL ' . $fechastr), 0, 1, 'C', 0);
        $this->Cell(210, 5, utf8_decode('EN DÓLARES'), 0, 1, 'C', 0);
        $this->Ln(8);
    }
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDF('P', 'mm', 'a4');
$pdf->SetTitle('Transacciones por Cuenta');
$pdf->SetMargins(2, 0);
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Amble-Regular', '', 9);
$pdf->SetX(0);
$pdf->Ln(0);

$tsaldoinicial = 0;
$tincremento = 0;
$tdisminucion = 0;
$tsaldofinal = 0;

$totalw = $pdf->GetCurrentWidth();
$colw = $totalw / 5;

$registrosp = obtenerRegistrosPatrimonio();
$pdf->SetWidths([$colw + 48, $colw - 12, $colw - 12, $colw - 12, $colw - 12]);
$pdf->SetAligns(array_fill(0, 6, "C"));
$pdf->SetFont('Arial', 'B', 9);
$pdf->Row([
    "PARTIDA CONTABLE",
    "SALDO INICIAL",
    "INCREMENTO",
    utf8_decode("DISMINUCIÓN"),
    "SALDO FINAL"
], 1);
$pdf->SetAligns(["L", "R", "R", "R", "R"]);
$pdf->SetFont('Amble-Regular', '', 9);
foreach ($registrosp as $value) {
    $pdf->Row([
        utf8_decode(trim($value["descripcion"])),
        $value["saldo_inicial"],
        $value["credito"],
        $value["debito"],
        $value["saldo_final"]
    ]);
    $tsaldoinicial += $value["saldo_inicial"];
    $tincremento += $value["credito"];
    $tdisminucion += $value["debito"];
    $tsaldofinal += $value["saldo_final"];
}
$pdf->Row([
    utf8_decode("UTILIDAD/PÉRDIDA EJERCICIO"),
    number_format(mostrarValUtilidad($_GET["inicio"], $_GET["fin"], 1)[0], 2, ".", ""),
    number_format(mostrarValUtilidad($_GET["inicio"], $_GET["fin"], 1)[1], 2, ".", ""),
    $value["debito"],
    number_format(mostrarValUtilidad($_GET["inicio"], $_GET["fin"], 1)[1] + mostrarValUtilidad($_GET["inicio"], $_GET["fin"], 1)[0], 2, ".", "")
]);
$tsaldoinicial += mostrarValUtilidad($_GET["inicio"], $_GET["fin"], 1)[0];
$tincremento += mostrarValUtilidad($_GET["inicio"], $_GET["fin"], 1)[1];
$tsaldofinal += mostrarValUtilidad($_GET["inicio"], $_GET["fin"], 1)[1] + mostrarValUtilidad($_GET["inicio"], $_GET["fin"], 1)[0];
$pdf->SetFont('Arial', 'B', 9);
$pdf->Row([
    "TOTALES",
    number_format($tsaldoinicial, 2, ".", ""),
    number_format($tincremento, 2, ".", ""),
    number_format($tdisminucion, 2, ".", ""),
    number_format($tsaldofinal, 2, ".", "")
], 1);
$pdf->SetFont('Amble-Regular', '', 9);

$pdf->Ln(16);

$colw = $totalw / 2;
$pdf->SetAligns(array_fill(0, 3, "C"));
$pdf->SetWidths([$colw, $colw]);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Line(10, $pdf->GetY(), 90, $pdf->GetY());
$pdf->Line(110, $pdf->GetY(), $totalw - 8, $pdf->GetY());
$pdf->Row([
    utf8_decode("FIRMA GERENTE"),
    utf8_decode("FIRMA CONTADOR")
]);
$pdf->SetFont('Amble-Regular', '', 9);
$pdf->Output();


function obtenerRegistrosPatrimonio()
{
    $end    = (new DateTime($_GET["inicio"]))->modify('yesterday')->format("Y-m-d");
    $sql = "
    with saldo_inicial as(
        select
        dt.id_plan_cuentas,	
        coalesce((sum(dt.credito)-sum(dt.debito)),0) saldo
        from transacciones t
        inner join detalle_transaccion dt
        on t.id_transacciones=dt.id_transacciones
        where dt.id_plan_cuentas in (
        select id_plan_cuentas from plan_cuentas 
        where codigo_plan ilike '3%'
        )
        and t.estado='Activo'
        and t.fecha_registro 
        between (select fecha_registro from transacciones t
        where estado='Activo'
        group by id_transacciones,fecha_registro
        order by id_transacciones asc
        limit 1) and '$end'
        group by dt.id_plan_cuentas
        having sum(dt.credito)>0 or sum(dt.debito)>0
    )
    select
    dt.id_plan_cuentas,
    pc.descripcion,
    round(coalesce(si.saldo,0),2)saldo_inicial,
    round(sum(dt.credito),2)credito,
    round(sum (dt.debito),2)debito,
    round((coalesce(si.saldo,0)+sum(dt.credito)-sum(dt.debito)),2) saldo_final
    from transacciones t
    inner join detalle_transaccion dt
    on t.id_transacciones=dt.id_transacciones
    inner join plan_cuentas pc
    on pc.id_plan_cuentas=dt.id_plan_cuentas
    left join saldo_inicial si
    on si.id_plan_cuentas=pc.id_plan_cuentas
    where dt.id_plan_cuentas in(
    select id_plan_cuentas from plan_cuentas 
    where codigo_plan ilike '3%'
    )
    and t.estado='Activo'
    and t.fecha_registro between '$_GET[inicio]'
    and '$_GET[fin]'
    group by dt.id_plan_cuentas,pc.descripcion,si.saldo
    having sum(dt.credito)>0 or sum (dt.debito)>0;
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return obtenerRegistrosPatrimonioSaldoInicial();
    }
    return $rows;
}

function obtenerRegistrosPatrimonioSaldoInicial()
{
    $end    = (new DateTime($_GET["inicio"]))->modify('yesterday')->format("Y-m-d");
    $sql = "
    select
	dt.id_plan_cuentas,
	pc.descripcion,	
	coalesce((sum(dt.credito)-sum(dt.debito)),0) saldo_inicial,
	(0)credito,
	(0)debito,
	coalesce((sum(dt.credito)-sum(dt.debito)),0) saldo_final
	from transacciones t
	inner join detalle_transaccion dt
	on t.id_transacciones=dt.id_transacciones
	inner join plan_cuentas pc
	on pc.id_plan_cuentas=dt.id_plan_cuentas
	where dt.id_plan_cuentas in (
	select id_plan_cuentas from plan_cuentas 
	where codigo_plan ilike '3%'
	)
	and t.estado='Activo'
	and t.fecha_registro 
	between (select fecha_registro from transacciones t
	where estado='Activo'
	group by id_transacciones,fecha_registro
	order by id_transacciones asc
	limit 1) and '$end'
	group by dt.id_plan_cuentas,pc.descripcion
	having sum(dt.credito)>0 or sum(dt.debito)>0
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}


//Utilidad
function obtenerValCuentasSaldoI($codcuenta, $idpv)
{
    $end    = (new DateTime($_GET["inicio"]))->modify('yesterday')->format("Y-m-d");
    $sql = "
    select 
    dt.id_plan_cuentas,
    plc.codigo_plan,
    plc.descripcion,
    round(sum(dt.debito),2)debito,
    round(sum(dt.credito),2)credito
    from transacciones t,
    detalle_transaccion dt 
    inner join plan_cuentas plc
    on plc.id_plan_cuentas=dt.id_plan_cuentas
    where dt.id_transacciones=t.id_transacciones 
    and t.fecha_registro BETWEEN (select fecha_registro from transacciones t
	where estado='Activo'
	group by id_transacciones,fecha_registro
	order by id_transacciones asc
	limit 1) and '$end' 
    and t.estado='Activo' 
    and dt.id_plan_cuentas in (
    select id_plan_cuentas from 
    plan_cuentas 
    where 
    cuenta='M' and
    estado='Activo' 
    and codigo_plan like '$codcuenta%' 
    order by codigo_plan
    ) 
    and t.id_empresa='$idpv' 
    group by dt.id_plan_cuentas,
    plc.descripcion,
    plc.codigo_plan
    order by codigo_plan asc;
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (!$rows) {
        return [];
    }
    return $rows;
}
function obtenerValCuentas($finicio, $ffin, $codcuenta, $idpv)
{
    $sql = "
    select 
    dt.id_plan_cuentas,
    plc.codigo_plan,
    plc.descripcion,
    sum(round(dt.debito,2))debito,
    sum(round(dt.credito,2))credito
    from transacciones t,
    detalle_transaccion dt 
    inner join plan_cuentas plc
    on plc.id_plan_cuentas=dt.id_plan_cuentas
    where dt.id_transacciones=t.id_transacciones 
    and t.fecha_registro BETWEEN '$finicio' and '$ffin' 
    and t.estado='Activo' 
    and dt.id_plan_cuentas in (
    select id_plan_cuentas from 
    plan_cuentas 
    where 
    cuenta='M' and
    estado='Activo' 
    and codigo_plan like '$codcuenta%' 
    order by codigo_plan
    ) 
    and t.id_empresa='$idpv' 
    group by dt.id_plan_cuentas,
    plc.descripcion,
    plc.codigo_plan
    order by codigo_plan asc;
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (!$rows) {
        return [];
    }
    return $rows;
}
function mostrarValUtilidad($finicio, $ffin, $idpv)
{
    //if (empty($finicio)) {
    $regsi = obtenerValCuentasSaldoI(4, $idpv);
    $regsi = array_merge($regsi, obtenerValCuentasSaldoI(5, $idpv));
    $regsi = array_merge($regsi, obtenerValCuentasSaldoI(6, $idpv));
    //} else {
    $regs = obtenerValCuentas($finicio, $ffin, 4, $idpv);
    $regs = array_merge($regs, obtenerValCuentas($finicio, $ffin, 5, $idpv));
    $regs = array_merge($regs, obtenerValCuentas($finicio, $ffin, 6, $idpv));
    //}

    $diferenciai = 0;
    $diferencia = 0;
    foreach ($regs as $value) {
        $diferencia +=  $value["credito"] - $value["debito"];
    }
    foreach ($regsi as $value) {
        $diferenciai +=  $value["credito"] - $value["debito"];
    }

    //$total += $diferencia;
    return [$diferenciai, $diferencia];
}
