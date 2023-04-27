<?php
require('../fpdf/fpdf.php');
include '../procesos/base.php';
include '../procesos/funciones.php';
conectarse();
date_default_timezone_set('America/Guayaquil');
session_start();
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
        $this->rango = ($_GET['inicio'] != '');
        $this->vendedor = ($_GET['id'] != '0');
        $this->ruta = ($_GET['idr'] != '0');
        $this->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->AddFont('helvetica', 'B', 'helveticab.php');
        $this->SetFont('Amble-Regular', '', 10);
        $fecha = date('Y-m-d', time());
        $this->SetX(0);
        $this->SetY(0);
        $this->Cell(105, 5, $fecha, 0, 0, 'C', 0);
        $this->Cell(105, 5, "VENTAS", 0, 1, 'C', 0);
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
        if($this->vendedor){
            $row = pg_fetch_assoc(pg_query("SELECT nombre_vendedor from vendedores where id_vendedor='$_GET[id]'"));
            $this->Cell(210, 5, utf8_decode("VENTAS DE $row[nombre_vendedor]"), 0, 1, 'C', 0);
        }else{
            $this->Cell(210, 5, utf8_decode("VENTAS DE TODOS LOS VENDEDORES"), 0, 1, 'C', 0);
        }
        if($this->ruta){
            $row = pg_fetch_assoc(pg_query("SELECT nombre_ruta from rutas where id_ruta='$_GET[idr]'"));
            $this->Cell(210, 5, utf8_decode("EN LA RUTA $row[nombre_ruta]"), 0, 1, 'C', 0);
        }else{
            $this->Cell(210, 5, utf8_decode("EN TODAS LAS RUTAS"), 0, 1, 'C', 0);
        }
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->Ln(3);
        $this->SetFont('helvetica', 'B', 9);
        $this->SetFillColor(175, 215, 240);
        $this->Cell(22, 6, utf8_decode('Comprobante'), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode('Fecha'), 1, 0, 'C', 1);
        $this->Cell(70, 6, utf8_decode('Cliente'), 1, 0, 'C', 1);
        // $this->Cell(40, 6, utf8_decode('Nombres'), 1, 0, 'C', 1);
        if($this->ruta){
            $this->Cell(30, 6, utf8_decode('Vendedor'), 1, 0, 'C', 1);
        }elseif($this->vendedor){
            $this->Cell(30, 6, utf8_decode('Ruta'), 1, 0, 'C', 1);
        }else{
            $this->Cell(30, 6, utf8_decode('Ruta/Vendedor'), 1, 0, 'C', 1);
        }
        $this->Cell(18, 6, utf8_decode('Subtotal'), 1, 0, 'C', 1);
        $this->Cell(16, 6, utf8_decode('Descto'), 1, 0, 'C', 1);
        $this->Cell(16, 6, utf8_decode('Iva ..%'), 1, 0, 'C', 1);
        $this->Cell(18, 6, utf8_decode('Total'), 1, 1, 'C', 1);
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
$pdf->SetTitle('Ventas por Ruta/Vendedor');
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->AliasNbPages();

$total_ventas = 0;
$query_fecha = "=";
$query_vendedor = "";
$query_ruta = "";
if ($pdf->rango) {
    // RANGO DE FECHAS O FECHA ACTUAL
    $query_fecha = "BETWEEN '$_GET[inicio]' AND";
} 
// VENDEDOR ESPECIFICO O TODOS
if ($pdf->vendedor) {
    $query_vendedor = "and v.id_vendedor='$_GET[id]'";
} 
if ($pdf->ruta) {
    // RUTA ESPECIFICA O TODAS
    $query_ruta = "and r.id_ruta='$_GET[idr]'";
}

$consulta1 = pg_query(
    "SELECT DISTINCT ON(fv.id_factura_venta) num_factura, fv.fecha_actual, iva_venta, 
    descuento_venta, total_venta, identificacion, nombres_cli, nombre_ruta, nombre_vendedor
    FROM factura_venta fv inner join clientes c using(id_cliente) 
    inner join empresa e using(id_empresa) 
    inner join vendedores v using(id_vendedor)
    inner join rutas r using(id_vendedor)
    where e.id_empresa='$_GET[idm]' $query_vendedor $query_ruta 
    and fv.fecha_actual $query_fecha '$_GET[fin]' 
    and fv.estado='Activo' order by fv.id_factura_venta asc"
);
if (pg_num_rows($consulta1)) {
    $total = 0;
    $sub = 0;
    $desc = 0;
    $ivaT = 0;
    while ($row1 = pg_fetch_assoc($consulta1)) {
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(22, 6, utf8_decode($row1['num_factura']), 0, 0, 'C', 0);
        $pdf->Cell(20, 6, utf8_decode($row1['fecha_actual']), 0, 0, 'C', 0);
        $pdf->Cell(27, 6, utf8_decode($row1['identificacion']), 0, 0, 'L', 0);
        $pdf->Cell(43, 6, utf8_decode(substr($row1['nombres_cli'], 0, 20)), 0, 0, 'L', 0);
        if($pdf->ruta){
            $pdf->Cell(30, 6, utf8_decode($row1['nombre_vendedor']), 0, 0, 'C', 0);
        }elseif($pdf->vendedor){
            $pdf->Cell(30, 6, utf8_decode($row1['nombre_ruta']), 0, 0, 'C', 0);
        }else{
            $pdf->Cell(30, 6, utf8_decode(substr($row1['nombre_ruta'],0, 7) . "/" . substr($row1['nombre_vendedor'],0,7)), 0, 0, 'C', 0);
        }
        $sub += ($row1['total_venta'] - $row1['iva_venta'] + $row1['descuento_venta']);
        $pdf->Cell(18, 6, utf8_decode(round($row1['total_venta'] - $row1['iva_venta'] + $row1['descuento_venta'], 2, PHP_ROUND_HALF_EVEN)), 0, 0, 'R', 0);
        $desc += $row1['descuento_venta'];
        $pdf->Cell(16, 6, utf8_decode(round($row1['descuento_venta'], 2, PHP_ROUND_HALF_EVEN)), 0, 0, 'R', 0);
        $ivaT += $row1['iva_venta'];
        $pdf->Cell(16, 6, utf8_decode(round($row1['iva_venta'], 2, PHP_ROUND_HALF_EVEN)), 0, 0, 'R', 0);
        $total += $row1['total_venta'];
        $pdf->Cell(18, 6, utf8_decode(round($row1['total_venta'], 2, PHP_ROUND_HALF_EVEN)), 0, 1, 'R', 0);
    }
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('helvetica', 'b', 9);
    $pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
    $pdf->Cell(142, 6, utf8_decode("Totales"), 0, 0, 'R', 0);
    $pdf->Cell(18, 6, maxCaracter((number_format($sub, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(16, 6, maxCaracter((number_format($desc, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(16, 6, maxCaracter((number_format($ivaT, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(18, 6, maxCaracter((number_format($total, 2, ',', '.')), 20), 0, 1, 'R', 0);
    $total_ventas += $total;
}
$pdf->Cell(210, 10, utf8_decode("Notas de Venta"), 1, 1, 'C', 0);

$consulta1 = pg_query(
    "SELECT DISTINCT ON(nv.id_facturas_novalidas) comprobante, nv.fecha_actual, iva_venta, descuento_venta, 
    total_venta, identificacion, nombres_cli, nombre_ruta, nombre_vendedor
    FROM facturas_novalidas nv inner join clientes c using(id_cliente) 
    inner join empresa e using(id_empresa) 
    inner join vendedores v using(id_vendedor)
    inner join rutas r using(id_vendedor)
    where e.id_empresa='$_GET[idm]' $query_vendedor $query_ruta 
    and nv.fecha_actual $query_fecha '$_GET[fin]' 
    and nv.estado='Activo' order by nv.id_facturas_novalidas asc"
);
if (pg_num_rows($consulta1)) {
    $total = 0;
    $sub = 0;
    $desc = 0;
    $ivaT = 0;
    while ($row1 = pg_fetch_assoc($consulta1)) {
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(22, 6, utf8_decode($row1['comprobante']), 0, 0, 'C', 0);
        $pdf->Cell(20, 6, utf8_decode($row1['fecha_actual']), 0, 0, 'C', 0);
        $pdf->Cell(27, 6, utf8_decode($row1['identificacion']), 0, 0, 'L', 0);
        $pdf->Cell(43, 6, utf8_decode(substr($row1['nombres_cli'], 0, 20)), 0, 0, 'L', 0);
        if($pdf->ruta){
            $pdf->Cell(30, 6, utf8_decode($row1['nombre_vendedor']), 0, 0, 'C', 0);
        }elseif($pdf->vendedor){
            $pdf->Cell(30, 6, utf8_decode($row1['nombre_ruta']), 0, 0, 'C', 0);
        }else{
            $pdf->Cell(30, 6, utf8_decode(substr($row1['nombre_ruta'],0, 7) . "/" . substr($row1['nombre_vendedor'],0,7)), 0, 0, 'C', 0);
        }
        $sub += ($row1['total_venta'] - $row1['iva_venta'] + $row1['descuento_venta']);
        $pdf->Cell(18, 6, utf8_decode(round($row1['total_venta'] - $row1['iva_venta'] + $row1['descuento_venta'], 2, PHP_ROUND_HALF_EVEN)), 0, 0, 'R', 0);
        $desc += $row1['descuento_venta'];
        $pdf->Cell(16, 6, utf8_decode(round($row1['descuento_venta'], 2, PHP_ROUND_HALF_EVEN)), 0, 0, 'R', 0);
        $ivaT += $row1['iva_venta'];
        $pdf->Cell(16, 6, utf8_decode(round($row1['iva_venta'], 2, PHP_ROUND_HALF_EVEN)), 0, 0, 'R', 0);
        $total += $row1['total_venta'];
        $pdf->Cell(18, 6, utf8_decode(round($row1['total_venta'], 2, PHP_ROUND_HALF_EVEN)), 0, 1, 'R', 0);
    }
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('helvetica', 'b', 9);
    $pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
    $pdf->Cell(142, 6, utf8_decode("Totales"), 0, 0, 'R', 0);
    $pdf->Cell(18, 6, maxCaracter((number_format($sub, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(16, 6, maxCaracter((number_format($desc, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(16, 6, maxCaracter((number_format($ivaT, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(18, 6, maxCaracter((number_format($total, 2, ',', '.')), 20), 0, 1, 'R', 0);
    $total_ventas += $total;
}
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('helvetica', 'b', 9.5);
$pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
$pdf->Cell(192, 6, utf8_decode("Total Ventas"), 0, 0, 'R', 0);
$pdf->Cell(18, 6, maxCaracter((number_format($total_ventas, 2, ',', '.')), 20), 0, 1, 'R', 0);
$pdf->Output();
