<?php

require('../fpdf/fpdf.php');
include '../procesos/base.php';
include '../procesos/funciones.php';
conectarse();
date_default_timezone_set('America/Guayaquil');
session_start();
//$obj = json_decode($_POST['obj']);

$idcargousuario = getIdCargoUsuario();
function getIdCargoUsuario()
{
    $idusuario = $_SESSION["id"];
    $sql = "
    select id_cargo_usuario from usuario
    where id_usuario=$idusuario
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_assoc($res);
    if (empty($rows)) {
        return 0;
    }
    return $rows["id_cargo_usuario"];
}


class PDF extends FPDF
{

    var $widths;
    var $aligns;

    function SetWidths($w)
    {
        $this->widths = $w;
    }

    function Header()
    {
        $this->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->SetFont('Amble-Regular', '', 10);
        $fecha = date('Y-m-d', time());
        $this->SetX(1);
        $this->SetY(1);
        $this->Cell(20, 5, $fecha, 0, 0, 'C', 0);
        $this->Cell(150, 5, "CLIENTE", 0, 1, 'R', 0);
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(190, 8, "EMPRESA: " . $_SESSION['nombre_empresa'], 0, 1, 'C', 0);
        $this->Image('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 5, 8, 45, 14);
        $this->SetFont('Amble-Regular', '', 10);
        $this->Cell(180, 5, "PROPIETARIO: " . utf8_decode($_SESSION['propietario']), 0, 1, 'C', 0);
        $this->Cell(80, 5, "TEL.: " . utf8_decode($_SESSION['telefono']), 0, 0, 'R', 0);
        $this->Cell(80, 5, "CEL.: " . utf8_decode($_SESSION['celular']), 0, 1, 'C', 0);
        $this->Cell(180, 5, "DIR.: " . utf8_decode($_SESSION['direccion']), 0, 1, 'C', 0);
        $this->Cell(180, 5, "SLOGAN.: " . utf8_decode($_SESSION['slogan']), 0, 1, 'C', 0);
        $this->Cell(180, 5, utf8_decode($_SESSION['pais_ciudad']), 0, 1, 'C', 0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);
        $this->Line(1, 46, 210, 46);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(190, 5, utf8_decode("INGRESOS "), 0, 1, 'C', 0);
        $this->SetFont('Amble-Regular', '', 10);
        $this->Ln(3);
        $this->SetFillColor(255, 255, 225);
        $this->SetLineWidth(0.2);
    }

    function Footer()
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


$sql = pg_query("
SELECT 
I.*,
U.* ,
pvo.nombre_punto origen,
pvd.nombre_punto destino
from 
ingresos I
left join punto_venta pvo
on I.origen=pvo.id_punto_venta
left join punto_venta pvd
on I.destino=pvd.id_punto_venta, 
usuario U
where 
I.id_usuario = U.id_usuario 
and I.id_ingresos = '" . $_GET["comprobante"] . "'");
$ingreso = pg_fetch_all($sql)[0];
$pdf->SetX(1);
$pdf->SetFillColor(187, 179, 180);
$pdf->Cell(90, 6, maxCaracter(utf8_decode('RUC/CI:' . $ingreso["ci_usuario"]), 35), 1, 0, 'L', 1);
$pdf->Cell(115, 6, maxCaracter(utf8_decode('NOMBRES:' . $ingreso["apellido_usuario"] . ' ' . $ingreso["nombre_usuario"]), 35), 1, 1, 'L', 1);
$pdf->SetX(1);
$pdf->Cell(75, 6, maxCaracter(utf8_decode('ORIGEN:' . $ingreso["origen"]), 50), 1, 0, 'L', 1);
$pdf->Cell(70, 6, maxCaracter(utf8_decode('DESTINO:' . $ingreso["destino"]), 50), 1, 0, 'L', 1);
$pdf->Cell(60, 6, maxCaracter(utf8_decode('FECHA INGRESO:' . $ingreso["fecha_actual"]), 50), 1, 1, 'L', 1);
$pdf->Ln(3);

$pdf->SetX(1);
$pdf->Cell(25, 6, utf8_decode('Comprobante'), 1, 0, 'C', 0);
$pdf->Cell(30, 6, utf8_decode('Código'), 1, 0, 'C', 0);
$pdf->Cell(60, 6, utf8_decode('Producto'), 1, 0, 'C', 0);
$pdf->Cell(20, 6, utf8_decode('Cantidad'), 1, 0, 'C', 0);
$pdf->Cell(25, 6, utf8_decode('Precio Costo'), 1, 0, 'C', 0);
$pdf->Cell(25, 6, utf8_decode('Precio Venta'), 1, 0, 'C', 0);
$pdf->Cell(20, 6, utf8_decode('Total'), 1, 1, 'C', 0);

$temp1 = $ingreso["tarifa0"];
$temp2 = $ingreso["tarifa12"];
$temp3 = $ingreso["iva_ingreso"];
$temp4 = $ingreso["descuento_ingreso"];
$temp5 = $ingreso["total_ingreso"];

$sql = pg_query("SELECT * from detalle_ingreso,productos where detalle_ingreso.cod_productos=productos.cod_productos and id_ingresos=" . $_GET["comprobante"] . "");
$detalles = pg_fetch_all($sql);
foreach ($detalles as $value) {
    $precioc = $value["precio_costo"];
    $totalc = $value["total"];
    if ($idcargousuario != 1) {
        $precioc = 0;
        $totalc = 0;
    }
    $pdf->Cell(25, 6, utf8_decode($value["id_detalle_ingreso"]), 0, 0, 'C', 0);
    $pdf->Cell(30, 6, maxCaracter(utf8_decode($value["codigo"]), 15), 0, 0, 'L', 0);
    $pdf->Cell(60, 6, maxCaracter(utf8_decode($value["articulo"] . "(" . $value["unidad_medida"] . ")"), 25), 0, 0, 'L', 0);
    $pdf->Cell(20, 6, utf8_decode($value["cantidad"]), 0, 0, 'C', 0);
    $pdf->Cell(25, 6, utf8_decode($precioc), 0, 0, 'C', 0);
    $pdf->Cell(25, 6, utf8_decode($value["iva_minorista"]), 0, 0, 'C', 0);
    $pdf->Cell(20, 6, utf8_decode($totalc), 0, 1, 'C', 0);
}

$pdf->SetX(1);
$pdf->Cell(205, 0, utf8_decode(''), 1, 1, 'R', 1);
$pdf->SetX(5);
$pdf->MultiCell(150, 5, utf8_decode("Observación: " . $ingreso["observaciones"]), 0, 'L');

if ($idcargousuario == 1) {


    $pdf->SetY($pdf->GetY() - 5);
    $pdf->SetX(167);
    $pdf->Cell(20, 6, utf8_decode('Tarifa 0:'), 0, 0, 'R', 0);
    $pdf->Cell(20, 6, (number_format($temp1, 2, ',', '.')), 0, 1, 'C', 0);

    $pdf->SetX(1);
    $pdf->Cell(186, 6, utf8_decode('Tarifa 12:'), 0, 0, 'R', 0);
    $pdf->Cell(20, 6, (number_format($temp2, 2, ',', '.')), 0, 1, 'C', 0);

    //$pdf->SetX(1);
    //$pdf->Cell(186, 6, utf8_decode('Iva:'), 0, 0, 'R', 0);
    //$pdf->Cell(20, 6, (number_format($temp3, 2, ',', '.')), 0, 1, 'C', 0);
    //
    //$pdf->SetX(1);
    //$pdf->Cell(186, 6, utf8_decode('Descuento:'), 0, 0, 'R', 0);
    //$pdf->Cell(20, 6, (number_format($temp4, 2, ',', '.')), 0, 1, 'C', 0);

    $pdf->SetX(1);
    $pdf->Cell(186, 6, utf8_decode('Total:'), 0, 0, 'R', 0);
    $pdf->Cell(20, 6, (number_format($temp5, 2, ',', '.')), 0, 1, 'C', 0);
}
$pdf->Output();
