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
        $this->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->SetFont('Amble-Regular', '', 10);
        $fecha = date('Y-m-d', time());
        $this->SetX(1);
        $this->SetY(1);
        $this->Cell(20, 5, $fecha, 0, 0, 'C', 0);
        $this->Cell(170, 5, "CLIENTE", 0, 1, 'R', 0);
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(190, 8, "EMPRESA: " . $_SESSION['nombre_empresa'], 0, 1, 'C', 0);
        $this->Image('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 5, 8, 20, 14);
        $this->SetFont('Amble-Regular', '', 10);
        $this->Cell(190, 5, "PROPIETARIO: " . utf8_decode($_SESSION['propietario']), 0, 1, 'C', 0);
        $this->Cell(80, 5, "TEL.: " . utf8_decode($_SESSION['telefono']), 0, 0, 'R', 0);
        $this->Cell(80, 5, "CEL.: " . utf8_decode($_SESSION['celular']), 0, 1, 'C', 0);
        //            $this->Cell(180, 5, "DIR.: ".utf8_decode($_SESSION['direccion']),0,1, 'C',0);                                
        $this->Cell(180, 5, "SLOGAN.: " . utf8_decode($_SESSION['slogan']), 0, 1, 'C', 0);
        $this->Cell(180, 5, utf8_decode($_SESSION['pais_ciudad']), 0, 1, 'C', 0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);

        $this->SetFillColor(120, 120, 120);
        $this->Line(1, 78, 210, 78);
        $this->Line(1, 45, 210, 45);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(190, 5, utf8_decode("GASTOS"), 0, 1, 'C', 0);
        $this->SetFont('Amble-Regular', '', 10);
        $this->Ln(2);
        $this->SetFillColor(255, 255, 225);
        $sql = pg_query("select id_gastos,comprobante,fecha_actual,hora_actual,num_serie,num_autorizacion,fecha_emision,empresa_pro,representante_legal,gastos.forma_pago from gastos,proveedores where gastos.id_proveedor=proveedores.id_proveedor and id_gastos='$_GET[id]'");
        $this->SetLineWidth(0.2);
        while ($row = pg_fetch_row($sql)) {
            $this->SetX(1);
            $this->Cell(85, 6, utf8_decode('COMPROBANTE: ' . $row[1]), 0, 0, 'L', 1);
            $this->Cell(120, 6, utf8_decode('FECHA: ' . $row[2]), 0, 1, 'L', 1);
            $this->SetX(1);
            $this->Cell(85, 6, utf8_decode('HORA: ' . $row[3]), 0, 0, 'L', 1);
            $this->Cell(120, 6, utf8_decode('NRO. SERIE: ' . $row[4]), 0, 1, 'L', 1);
            $this->SetX(1);
            $this->Cell(205, 6, utf8_decode('NRO AUTORIZACIÓN: ' . $row[5]), 0, 1, 'L', 1);
            $this->SetX(1);
            $this->Cell(85, 6, utf8_decode('FORMA PAGO: ' . $row[9]), 0, 0, 'L', 1);
            $this->Cell(120, 6, utf8_decode('EMPRESA: ' . $row[7]), 0, 1, 'L', 1);
            $this->SetX(1);
            $this->Cell(205, 6, utf8_decode('FECHA CANCELACIÓN: ' . $row[6]), 0, 1, 'L', 1);
            $this->SetX(1);
            $this->Cell(205, 6, utf8_decode('REPRESENTANTE: ' . $row[8]), 0, 1, 'L', 1);
        }
        $this->Ln(5);
        $this->SetX(1);
        $this->SetFont('Amble-Regular', '', 10);
        $this->Cell(35, 5, utf8_decode("Cantidad"), 1, 0, 'C', 0);
        $this->Cell(110, 5, utf8_decode("Descripción"), 1, 0, 'C', 0);
        $this->Cell(30, 5, utf8_decode("V. Unitario"), 1, 0, 'C', 0);
        $this->Cell(30, 5, utf8_decode("V. Total"), 1, 0, 'C', 0);
        $this->Ln(5);
    }
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$tarifasimpfactura = obtenerTarifasImpuestoFactura($_GET["id"]);

$pdf = new PDF('P', 'mm', 'a4');
$pdf->AddPage();
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AliasNbPages();
$pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
$pdf->SetFont('Amble-Regular', '', 10);
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetX(5);
$pdf->SetFont('Amble-Regular', '', 9);
$total = 0;
$calculoIVA = pg_query("select valor from parametros where descripcion='IVA'");
while ($rowi = pg_fetch_row($calculoIVA)) {
    $iva_base = $rowi[0];
}
$iva_base = ($iva_base / 100) + 1;
$sql = pg_query("select detalle_gastos.cantidad,detalle_gastos.concepto,detalle_gastos.precio_compra,detalle_gastos.total_compra,detalle_gastos.cuenta_contable from gastos,detalle_gastos where gastos.id_gastos=detalle_gastos.id_gastos and gastos.id_gastos='$_GET[id]'");

while ($row = pg_fetch_row($sql)) {
    $pdf->SetX(1);
    $pdf->Cell(35, 5, maxCaracter(utf8_decode("1"), 20), 0, 0, 'C', 0);
    $pdf->Cell(110, 5, maxCaracter(utf8_decode($row[1] . "--" . $row[4]), 65), 0, 0, 'L', 0);
    $pdf->Cell(30, 5, maxCaracter(utf8_decode($row[2]), 20), 0, 0, 'C', 0);
    $pdf->Cell(30, 5, maxCaracter(utf8_decode($row[3]), 20), 0, 0, 'C', 0);
    $pdf->Ln(5);
}

$pdf->SetX(1);
$pdf->Ln(5);
$sql = pg_query("select gastos.descuento_compra,gastos.tarifa0,gastos.tarifa12,gastos.iva_compra,gastos.total_compra from gastos,detalle_gastos where gastos.id_gastos=detalle_gastos.id_gastos  and detalle_gastos.id_gastos='$_GET[id]' LIMIT 1");
while ($row = pg_fetch_row($sql)) {
    $pdf->Cell(173, 6, utf8_decode("Descuento"), 0, 0, 'R', 0);
    $pdf->Cell(35, 6, number_format(round($row[0], 2), 2, '.', ''), 0, 1, 'C', 0);

    if (empty($tarifasimpfactura)) {
        $pdf->Cell(173, 6, utf8_decode("Tarifa 0"), 0, 0, 'R', 0);
        $pdf->Cell(35, 6, number_format(round($row[1], 2), 2, '.', ''), 0, 1, 'C', 0);
        $pdf->Cell(173, 6, utf8_decode("Tarifa IVA"), 0, 0, 'R', 0);
        $pdf->Cell(35, 6, number_format(round($row[2], 2), 2, '.', ''), 0, 1, 'C', 0);
    } else {
        foreach ($tarifasimpfactura as $key => $value) {
            $pdf->Cell(173, 6, utf8_decode("Tarifa ". $value["tarifa"]), 0, 0, 'R', 0);
            $pdf->Cell(35, 6, number_format($value["base_imponible"], 2, '.', ''), 0, 1, 'C', 0);
        }
    }

    $pdf->Cell(173, 6, utf8_decode("Iva ...%"), 0, 0, 'R', 0);
    $pdf->Cell(35, 6, number_format(round($row[3], 2), 2, '.', ''), 0, 1, 'C', 0);
    $pdf->Cell(173, 6, utf8_decode("Total"), 0, 0, 'R', 0);
    $pdf->Cell(35, 6, number_format(round($row[4], 2), 2, '.', ''), 0, 1, 'C', 0);
}
//////////
//    $sql=pg_query("select * from series_compra,factura_compra,productos where factura_compra.id_factura_compra=series_compra.id_factura_compra and productos.cod_productos=series_compra.cod_productos and series_compra.id_factura_compra='$_GET[id]'");
//    if(pg_num_rows($sql)){
//        $pdf->AddPage();
//        $pdf->Cell(205, 7, utf8_decode("NÚMEROS DE SERIE"),0,1, 'C',0);                                                                                                                        
//        $pdf->Cell(50, 5, utf8_decode("Cod. Producto"),1,0, 'C',0);
//        $pdf->Cell(95, 5, utf8_decode("Descripción"),1,0, 'C',0);
//        $pdf->Cell(30, 5, utf8_decode("Nro. Serie"),1,0, 'C',0);        
//        $pdf->Cell(30, 5, utf8_decode("Nro. Factura"),1,1, 'C',0); 
//        while($row=pg_fetch_row($sql)){
//            $pdf->Cell(50, 6, maxCaracter(utf8_decode($row[28]),20),0,0, 'C',0);
//            $pdf->Cell(95, 6, maxCaracter(utf8_decode($row[30]),60),0,0, 'C',0);
//            $pdf->Cell(30, 6, maxCaracter(utf8_decode($row[3]),20),0,0, 'C',0);
//            $pdf->Cell(30, 6, maxCaracter(utf8_decode($row[17]),20),0,1, 'C',0);
//        }
//    }
$pdf->Output();

function obtenerTarifasImpuestoFactura($id)
{
    $sql = "select
    di.cod_impuesto, 
    di.cod_tarifa, 
    di.tarifa, 
    sum(di.valor_impuesto)valor_impuesto, 
    sum(di.base_imponible)base_imponible
    from
    gastos fc
    inner join detalle_gastos dfc
    using(id_gastos)
    inner join detalle_impuesto_producto_gasto di
    using(id_detalle_gastos)
    where id_gastos=$id
    group by di.cod_tarifa, di.cod_impuesto, di.tarifa";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (!empty($rows)) {
        return $rows;
    }
    return [];
}
