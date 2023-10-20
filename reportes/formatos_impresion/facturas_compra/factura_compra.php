<?php

require(__DIR__ . '/../../../fpdf/fpdf.php');
include __DIR__ . '/../../../procesos/base.php';
include __DIR__ . '/../../../procesos/funciones.php';
conectarse();
date_default_timezone_set('America/Guayaquil');
session_start();

class PDF extends FPDF {

    var $widths;
    var $aligns;

    function SetWidths($w) {
        $this->widths = $w;
    }

    function Header() {
        $this->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->SetFont('Amble-Regular', '', 10);
        $fecha = date('Y-m-d', time());
        $this->SetX(0);
        $this->SetY(0);
        $this->Cell(105, 5, $fecha, 0, 0, 'C', 0);
        $this->Cell(105, 5, "COMPRAS", 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(210, 8, utf8_decode($_SESSION['nombre_empresa']), 0, 1, 'C', 0);
        $this->Image('../../../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 10, 7, 15, 15);
        $this->Image('../../../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 180, 7, 15, 15);
        // $this->SetFont('Amble-Regular', '', 10);
        // $this->Cell(190, 5, "PROPIETARIO: " . utf8_decode($_SESSION['propietario']), 0, 1, 'C', 0);
        // $this->Cell(80, 5, "TEL.: " . utf8_decode($_SESSION['telefono']), 0, 0, 'R', 0);
        // $this->Cell(80, 5, "CEL.: " . utf8_decode($_SESSION['celular']), 0, 1, 'C', 0);
        // $this->Cell(190, 5, "DIR.: " . utf8_decode($_SESSION['direccion']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, "SLOGAN.: " . utf8_decode($_SESSION['slogan']), 0, 1, 'C', 0);
        // $this->Cell(190, 5, utf8_decode($_SESSION['pais_ciudad']), 0, 1, 'C', 0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);
        $this->Line(0, 25, 210, 25);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(210, 5, utf8_decode("FACTURA COMPRA"), 0, 1, 'C', 0);
        $this->SetFont('Amble-Regular', '', 10);
        $this->Ln(9);
        $this->SetFillColor(220, 240, 210);
        $row = pg_fetch_row(
                pg_query(
                        "SELECT id_factura_compra,comprobante,fecha_actual,hora_actual,num_serie,num_autorizacion,fecha_cancelacion,empresa_pro,representante_legal,factura_compra.forma_pago,tipo_comprobante  
                FROM factura_compra,proveedores where factura_compra.id_proveedor=proveedores.id_proveedor and id_factura_compra='$_GET[id]';"
                )
        );

        $row1 = pg_fetch_row(
                pg_query("SELECT forma_pago FROM formas_pago_mixto_c where 
                        id_factura_compra='$_GET[id]'")
        );
        if ($row1[0] == "") {
            $row1[0] = "CONTADO";
        }
        $this->Cell(90, 6, utf8_decode('COMPROBANTE: ' . $row[1]), 0, 0, 'L', 1);
        $this->Cell(120, 6, utf8_decode('FECHA: ' . $row[2]), 0, 1, 'L', 1);


//        $this->Cell(90, 6, utf8_decode('HORA: ' . $row[3]), 0, 0, 'L', 1);

        $sql1 = pg_query("select  D.cod_productos, P.codigo, P.articulo, D.cantidad, D.precio_compra, D.descuento_producto, D.total_compra, P.iva, P.incluye_iva,D.cantidad_unidad,D.unidad_medida,d.fecha_emision,campo_dijitar
,nombre,cc.id_centro_costo,D.id_detalle_compra
 from factura_compra F INNER JOIN  detalle_factura_compra D ON  F.id_factura_compra = D.id_factura_compra 
 INNER JOIN  productos P ON  D.cod_productos = P.cod_productos  
 INNER JOIN detalle_centro_costos dcc  ON  D.id_detalle_compra=dcc.id_documento 
 INNER JOIN centro_costos cc ON  cc.id_centro_costo=dcc.id_centro_costo where  F.id_factura_compra='$_GET[id]' LIMIT 1");
        while ($row = pg_fetch_row($sql1)) {

            $id_cc = $row[14];
        }


        $query = pg_query(
                "SELECT  nombre
  FROM centro_costos where 
   id_centro_costo=$id_cc "
        );
        $nombre_cc = '';
        while ($row = pg_fetch_row($query)) {
            $nombre_cc = $row[0];
        }

        $this->Cell(120, 6, utf8_decode($nombre_cc), 0, 1, 'L', 1);
//        $this->Cell(210, 6, utf8_decode('NRO AUTORIZACIÓN: ' . $row[5]), 0, 1, 'L', 1);
//        $this->Cell(90, 6, utf8_decode('FORMA PAGO: ' . $row1[0]), 0, 0, 'L', 1);
//        $this->Cell(120, 6, utf8_decode('EMPRESA: ' . $row[7]), 0, 1, 'L', 1);
//        $this->Cell(210, 6, utf8_decode('FECHA CANCELACIÓN: ' . $row[6]), 0, 1, 'L', 1);
//        $this->Cell(210, 6, utf8_decode('REPRESENTANTE: ' . $row[8]), 0, 1, 'L', 1);
        $this->SetLineWidth(0.2);
        $this->Ln(6);
        $this->SetX(5);
        $this->SetFont('helvetica', 'B', 10);
        $this->SetFillColor(175, 215, 240);
        $this->Cell(15, 6, utf8_decode("Nº"), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode("FECHA"), 1, 0, 'C', 1);
        $this->Cell(70, 6, utf8_decode("DETALLE"), 1, 0, 'L', 1);
        $this->Cell(120, 6, utf8_decode("DESCRIPCIÓN"), 1, 0, 'L', 1);
        $this->Cell(30, 6, utf8_decode("VALOR"), 1, 1, 'C', 1);
        $this->Ln(1);
    }
    function Footer() {
        $this->SetY(-10);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDF('L', 'mm', 'a4');
$pdf->SetTitle('Comprobante Compra');
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->AliasNbPages();
$total = 0;
$calculoIVA = pg_query("select valor from parametros where descripcion='IVA'");
while ($rowi = pg_fetch_row($calculoIVA)) {
    $iva_base = $rowi[0];
}
$iva_base = ($iva_base / 100) + 1;
$sql = pg_query("
select  D.cod_productos, P.codigo, P.articulo, D.precio_compra, D.total_compra,d.fecha_emision,campo_dijitar
,nombre,cc.id_centro_costo,D.id_detalle_compra
 from factura_compra F INNER JOIN  detalle_factura_compra D ON  F.id_factura_compra = D.id_factura_compra 
 INNER JOIN  productos P ON  D.cod_productos = P.cod_productos  
 INNER JOIN detalle_centro_costos dcc  ON  D.id_detalle_compra=dcc.id_documento 
 INNER JOIN centro_costos cc ON  cc.id_centro_costo=dcc.id_centro_costo where  F.id_factura_compra='$_GET[id]' group by  D.cod_productos, P.codigo, P.articulo, D.precio_compra, D.total_compra,d.fecha_emision,campo_dijitar
,nombre,cc.id_centro_costo,D.id_detalle_compra");
while ($row = pg_fetch_row($sql)) {
    $pdf->SetX(5);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(15, 5, $row[9], 0, 0, 'C', 0);
    $pdf->Cell(25, 5, $row[5], 0, 0, 'L', 0);
    $pdf->Cell(70, 5, $row[2], 0, 0, 'L', 0);
    $pdf->Cell(120, 5, $row[6], 0, 0, 'L', 0);
    $pdf->Cell(30, 5, number_format($row[3], 2, ',', '.'), 0, 1, 'R', 0);
}

$pdf->SetX(5);
$pdf->Ln(5);
$ice = 0;
$irbp = 0;
$sql = pg_query("SELECT ice, irbp
  FROM ice_factura_compra where id_factura_compra ='$_GET[id]'
");
while ($row = pg_fetch_row($sql)) {
    $ice = $row[0];
    $irbp = $row[1];
}

$sql = pg_query("select factura_compra.descuento_compra,factura_compra.tarifa0,factura_compra.tarifa12,factura_compra.iva_compra,factura_compra.total_compra from factura_compra,detalle_factura_compra,productos where factura_compra.id_factura_compra=detalle_factura_compra.id_factura_compra and detalle_factura_compra.cod_productos=productos.cod_productos and detalle_factura_compra.id_factura_compra='$_GET[id]' LIMIT 1");
while ($row = pg_fetch_row($sql)) {
    $pdf->SetFont('helvetica', 'B', 9);

    $pdf->Cell(230, 6, utf8_decode("Total"), 0, 0, 'R', 0);
    $pdf->Cell(35, 6, number_format(round($row[4] + $irbp + $ice, 2), 2, ',', '.'), 0, 1, 'R', 0);
}

$sql = pg_query("select * from series_compra,factura_compra,productos where factura_compra.id_factura_compra=series_compra.id_factura_compra and productos.cod_productos=series_compra.cod_productos and series_compra.id_factura_compra='$_GET[id]'");
if (pg_num_rows($sql)) {
    $pdf->AddPage();
    $pdf->Cell(205, 7, utf8_decode("NÚMEROS DE SERIE"), 0, 1, 'C', 0);
    $pdf->Cell(50, 5, utf8_decode("Cod. Producto"), 1, 0, 'C', 0);
    $pdf->Cell(95, 5, utf8_decode("Descripción"), 1, 0, 'C', 0);
    $pdf->Cell(30, 5, utf8_decode("Nro. Serie"), 1, 0, 'C', 0);
    $pdf->Cell(30, 5, utf8_decode("Nro. Factura"), 1, 1, 'C', 0);
    while ($row = pg_fetch_row($sql)) {
        $pdf->Cell(50, 6, maxCaracter(utf8_decode($row[28]), 20), 0, 0, 'C', 0);
        $pdf->Cell(95, 6, maxCaracter(utf8_decode($row[30]), 60), 0, 0, 'C', 0);
        $pdf->Cell(30, 6, maxCaracter(utf8_decode($row[3]), 20), 0, 0, 'C', 0);
        $pdf->Cell(30, 6, maxCaracter(utf8_decode($row[17]), 20), 0, 1, 'C', 0);
    }
}
$pdf->Output();
