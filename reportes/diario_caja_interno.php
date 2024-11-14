<?php
require '../fpdf/fpdf.php';
include '../procesos/base.php';
include '../procesos/funciones.php';
conectarse();
date_default_timezone_set('America/Guayaquil');

session_start();
class PDF extends FPDF
{
    public $widths;
    public $aligns;
    public function SetWidths($w)
    {
        $this->widths = $w;
    }
    public function Header()
    {
        $this->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->SetFont('Amble-Regular', '', 10);
        $fecha = date('Y-m-d', time());
        $this->SetX(1);
        $this->SetY(1);
        $this->Cell(20, 5, $fecha, 0, 0, 'C', 0);
        $this->Cell(150, 5, "CLIENTE", 0, 1, 'R', 0);
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(190, 8, $_SESSION['empresa'], 0, 1, 'C', 0);
        $this->Image('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 5, 8, 45, 30);
        $this->SetFont('Amble-Regular', '', 10);
        $this->Cell(190, 5, "PROPIETARIO: " . utf8_decode($_SESSION['propietario']), 0, 1, 'C', 0);
        $this->Cell(80, 5, "TEL.: " . utf8_decode($_SESSION['telefono']), 0, 0, 'R', 0);
        $this->Cell(80, 5, "CEL.: " . utf8_decode($_SESSION['celular']), 0, 1, 'C', 0);
        $this->Cell(190, 5, "DIR.: " . utf8_decode($_SESSION['direccion']), 0, 1, 'C', 0);
        $this->Cell(190, 5, "SLOGAN.: " . utf8_decode($_SESSION['slogan']), 0, 1, 'C', 0);
        $this->Cell(180, 5, utf8_decode($_SESSION['pais_ciudad']), 0, 1, 'C', 0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);
        $this->Line(1, 50, 210, 50);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(90, 5, utf8_decode($_GET['inicio']), 0, 0, 'R', 0);
        $this->Cell(40, 5, utf8_decode($_GET['fin']), 0, 1, 'C', 0);
        $this->Cell(190, 5, utf8_decode("DIARIO DE CAJA"), 0, 1, 'C', 0);
        $this->SetFont('Amble-Regular', '', 10);
        $this->Ln(3);
        $this->SetFillColor(255, 255, 225);
        $this->SetLineWidth(0.2);
    }
    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}
$pdf = new PDF('P', 'mm', 'a4');
$pdf->AddPage();
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AliasNbPages();
$pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
$pdf->SetFont('Amble-Regular', '', 10);
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetX(5);
$pdf->SetFont('Amble-Regular', '', 9);
$pdf->SetX(1);
$total = 0;
$contado = 0;
$credito = 0;
$cheque = 0;
$gastos = 0;
$gastose = 0;
$notaVenta = 0;
$tarjetaCredito = 0;
$cxc = 0;

$sql = pg_query("select sum(total::float)
from gastos_internos where fecha_actual between '$_GET[inicio]' and '$_GET[fin]' and estado = 'Activo'");
while ($row = pg_fetch_row($sql)) {
    $gastos = $row[0];
}

$sql = pg_query("select sum(total::float)
from gastos where fecha_actual between '$_GET[inicio]' and '$_GET[fin]' and estado = 'Activo'");
while ($row = pg_fetch_row($sql)) {
    $gastose = $row[0];
}


$porcentajeBpCref = porcentajeBPichincaCredife();
$utilidadc=totalIngresos()+$porcentajeBpCref-totalEgresos();


$pdf->SetX(10);
$pdf->Cell(170, 6, "(+)UTILIDAD CONTRATOS", 0, 0, 'L', 0);
$pdf->Cell(20, 6, (number_format(($utilidadc), 3, ',', '.')), 0, 1, 'R', 0);
$pdf->SetX(10);
$pdf->Cell(170, 6, "(-)GASTOS INTERNOS", 0, 0, 'L', 0);
$pdf->Cell(20, 6, (number_format($gastos, 3, ',', '.')), 0, 1, 'R', 0);
$pdf->SetX(10);
$pdf->Cell(170, 6, "(-)GASTOS EXTERNOS", 0, 0, 'L', 0);
$pdf->Cell(20, 6, (number_format($gastose, 3, ',', '.')), 0, 1, 'R', 0);
$pdf->SetX(10);
$pdf->Cell(170, 6, "TOTAL DINERO EN CAJA", 0, 0, 'L', 0);
$pdf->Cell(20, 6, (number_format(($utilidadc - $gastos - $gastose), 3, ',', '.')), 0, 1, 'R', 0);
$pdf->Ln(6);
$pdf->Output();

function porcentajeBPichincaCredife()
{
    $sql = "
		select
		(sum(fv.total_venta::numeric)*0.09) porcentaje from factura_venta fv
		inner join clientes c
		using(id_cliente)
		where fv.id_cliente in(17,14)
		and fv.estado='Activo'
		and fv.forma_pago='Contado'
		and  fv.fecha_actual between '$_GET[inicio]' and '$_GET[fin]'
		";
    $res = pg_query($sql);
    $row = pg_fetch_row($res);
    return $row[0];
}

function totalIngresos()
{
    $totali = 0;
    $sql = "SELECT sum(co.valor)as total
    FROM contrato_operacion co
    inner join contrato_alquiler_vehiculo_trasporte cat
    on co.id_contrato=cat.id_contrato
    where co.estado='Activo'
    and cat.estado='Activo'
    and (co.accion='a'or co.accion='i')
    and cat.fecha_contrato between '$_GET[inicio]' and '$_GET[fin]';";
    $consulta = pg_query($sql);
    if (pg_num_rows($consulta) > 0) {
        return pg_fetch_all($consulta)[0]['total'];
    }
    return $totali;
}

function totalEgresos()
{
    $totale = 0;
    $sql = "SELECT sum(co.valor)as total
    FROM contrato_operacion co
    inner join contrato_alquiler_vehiculo_trasporte cat
    on co.id_contrato=cat.id_contrato
    where co.estado='Activo'
    and cat.estado='Activo'
    and co.accion='e'
    and cat.fecha_contrato between '$_GET[inicio]' and '$_GET[fin]';";
    $consulta = pg_query($sql);
    if (pg_num_rows($consulta) > 0) {
        return pg_fetch_all($consulta)[0]['total'];
    }
    return $totale;
}
