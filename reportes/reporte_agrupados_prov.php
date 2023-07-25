<?php
require('../fpdf/fpdf.php');
include '../procesos/base.php';
include '../procesos/funciones.php';
conectarse();
date_default_timezone_set('America/Guayaquil');
session_start();

$cols = [
    34,
    28,
    68,
    20,
    20,
    20,
    20
];

$finicio = $_GET["inicio"];
$ffin = $_GET["fin"];
if (!empty($finicio) && empty($ffin)) {
    $ffin = date("Y-m-d");
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
        $this->SetX(0);
        $this->SetY(0);
        $this->Cell(105, 5, $fecha, 0, 0, 'C', 0);
        $this->Cell(105, 5, "COMPRAS", 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(210, 8, $_SESSION['nombre_empresa'], 0, 1, 'C', 0);
        $this->Image('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 10, 7, 15, 15);
        $this->Image('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 180, 7, 15, 15);
        // $this->SetFont('Amble-Regular', '', 10);
        // $this->Cell(190, 5, "PROPIETARIO: " . utf8_decode($_SESSION['propietario']), 0, 1, 'C', 0);
        // $this->Cell(80, 5, "TEL.: " . utf8_decode($_SESSION['telefono']), 0, 0, 'R', 0);
        // $this->Cell(80, 5, "CEL.: " . utf8_decode($_SESSION['celular']), 0, 1, 'C', 0);
        // $this->Cell(190, 5, "DIR.: " . utf8_decode($_SESSION['direccion']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, "SLOGAN.: " . utf8_decode($_SESSION['slogan']), 0, 1, 'C', 0);
        // $this->Cell(190, 5, utf8_decode($_SESSION['pais_ciudad']), 0, 1, 'C', 0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);
        $this->Line(0, 32, 210, 32);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(210, 5, utf8_decode("PRODUCTOS AGRUPADOS POR PROVEEDOR"), 0, 1, 'C', 0);
        $this->SetFont('HELVETICA', 'B', 10);
        $row = pg_fetch_row(
            pg_query( "SELECT proveedores.id_proveedor, identificacion_pro,empresa_pro 
            from proveedores,factura_compra where proveedores.id_proveedor='$_GET[id]' LIMIT 1;"
            )
        );
        $this->Cell(105, 8, utf8_decode('RUC/CI: ' . $row[1]), 0, 0, 'C', 0);
        $this->Cell(105, 8, utf8_decode('NOMBRE: ' . $row[2]), 0, 1, 'C', 0);

        $this->Cell(105, 8, utf8_decode('DESDE: ' . $GLOBALS["finicio"]), 0, 0, 'C', 0);
        $this->Cell(105, 8, utf8_decode('HASTA: ' . $GLOBALS["ffin"]), 0, 1, 'C', 0);
        $this->SetLineWidth(0.2);

        $this->SetFont('helvetica', 'B', 9);
        $this->SetFillColor(175, 215, 240);
        $this->Cell($GLOBALS["cols"][0], 6, utf8_decode("FACTURA"), 1, 0, 'C', 1);
        $this->Cell($GLOBALS["cols"][1], 6, utf8_decode("CODIGO"), 1, 0, 'C', 1);
        $this->Cell($GLOBALS["cols"][2], 6, utf8_decode("PRODUCTO"), 1, 0, 'C', 1);
        $this->Cell($GLOBALS["cols"][3], 6, utf8_decode("P. COMPRA"), 1, 0, 'C', 1);
        $this->Cell($GLOBALS["cols"][4], 6, utf8_decode("T. COMPRA"), 1, 0, 'C', 1);
        $this->Cell($GLOBALS["cols"][5], 6, utf8_decode("CANTIDAD"), 1, 0, 'C', 1);
        $this->Cell($GLOBALS["cols"][6], 6, utf8_decode("FECHA EMI."), 1, 1, 'C', 1);
    }
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}


$pdf = new PDF('P', 'mm', 'a4');
$pdf->SetTitle('Productos Proveedores');
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->AliasNbPages();

$total = 0;
$sql = pg_query(
    "SELECT proveedores.id_proveedor, identificacion_pro,factura_compra.id_factura_compra,num_serie, factura_compra.fecha_emision
    FROM proveedores,factura_compra 
    where proveedores.id_proveedor='$_GET[id]' 
    and factura_compra.fecha_emision between '$finicio' and '$ffin'
    and factura_compra.id_proveedor=proveedores.id_proveedor;"
);
if (pg_num_rows($sql)) {
    while ($row = pg_fetch_row($sql)) {
        $sql1 = pg_query(
            "SELECT detalle_factura_compra.id_detalle_compra,productos.codigo,productos.articulo,
            productos.iva_minorista,productos.iva_mayorista,productos.stock,detalle_factura_compra.precio_compra,
            detalle_factura_compra.total_compra,cantidad 
            FROM detalle_factura_compra
            inner join factura_compra fc
            using(id_factura_compra)
            ,productos 
            where detalle_factura_compra.cod_productos=productos.cod_productos 
            and detalle_factura_compra.id_factura_compra='$row[2]' 
            and fc.estado='Activo'
            order by id_detalle_compra asc;"
        );
        while ($row1 = pg_fetch_row($sql1)) {
            $pdf->SetX(1);
            $pdf->SetFont('helvetica', '', 9);
            $pdf->Cell($cols[0], 5, maxCaracter(utf8_decode($row[3]), 20), 0, 0, 'L', 0);
            $pdf->Cell($cols[1], 5, maxCaracter(utf8_decode($row1[1]), 12), 0, 0, 'L', 0);
            $pdf->Cell($cols[2], 5, maxCaracter(utf8_decode($row1[2]), 30), 0, 0, 'L', 0);
            $pdf->Cell($cols[3], 5, number_format($row1[6], 2, ',', '.'), 0, 0, 'R', 0);
            $pdf->Cell($cols[4], 5, number_format($row1[7], 2, ',', '.'), 0, 0, 'R', 0);
            $pdf->Cell($cols[5], 5, number_format($row1[8], 2, ',', '.'), 0, 0, 'R', 0);
            $pdf->Cell($cols[6], 5, $row[4], 0, 1, 'C', 0);
            $total += $row1[7];
        }
    }
    $pdf->SetX(1);
    $pdf->Ln(5);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(210, 0, "", 1, 1, 'R', 0);
    $pdf->Cell(155, 5, "Totales:", 0, 0, 'R', 0);
    $pdf->Cell(20, 5, number_format($total, 2, ',', '.'), 0, 1, 'C', 0);
}
$pdf->Output();
