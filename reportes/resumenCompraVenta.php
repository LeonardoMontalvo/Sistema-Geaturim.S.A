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
        $this->Cell(105, 5, "TESORERIA", 0, 1, 'C', 0);
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
        $this->Cell(210, 5, utf8_decode("RESUMEN DE COMPRAS Y VENTAS"), 0, 1, 'C', 0);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
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
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->SetTitle('Resumen de Comras y Ventas');
$pdf->AliasNbPages();
$default = 0;
$query_fecha = "";
// RANGO DE FECHAS O FECHA ACTUAL
if ($pdf->rango) {
    $query_fecha = "BETWEEN '$_GET[inicio]' AND";
} else {
    $query_fecha = "=";
}
// COMPRAS
$pdf->SetX(0);
$pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
$pdf->Ln(3);
// tabla compras
// encabezado
$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('COMPRAS'), 0, 0, 'L', 0);
$pdf->Cell(25, 5, utf8_decode('Base NV'), 0, 0, 'R', 0);
$pdf->Cell(25, 5, utf8_decode('Base FAC.'), 0, 0, 'R', 0);
$pdf->Cell(50, 5, utf8_decode('NC'), 0, 0, 'R', 0);
$pdf->Cell(50, 5, utf8_decode('NETO'), 0, 1, 'R', 0);
// cuerpo
$pdf->SetFont('helvetica', '', 9);
$total_base = 0;
$total_base1 = 0;
$total_nc = 0;
$total_neto = 0;
// compras 12%
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Compras 12%'), 0, 0, 'L', 0);//COMPRAS 1
//echo 'LL'."SELECT SUM(tarifa12) FROM factura_compra WHERE  tipo_comprobante='FACTURA'   AND fecha_emision " . $query_fecha . "'$_GET[fin]' AND estado='Activo';";
$query = pg_fetch_row(pg_query(
    "SELECT SUM(tarifa12) FROM factura_compra WHERE  tipo_comprobante='FACTURA'   AND fecha_emision " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
));
$base = $query[0];
$total_base += $base;
$pdf->Cell(25, 5, "", 2, ',', '.', 0, 0, 'R', 0);//BASE 2
$pdf->Cell(25, 5, number_format($base, 2, ',', '.'), 0, 0, 'R', 0);//BASE 3
$query = pg_fetch_row(pg_query(
    "SELECT SUM(tarifa12) FROM devolucion_compra WHERE  num_autorizacion_sri::text " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
));
$nc = $query[0];
$total_nc += $nc;
$pdf->Cell(50, 5, number_format($nc, 2, ',', '.'), 0, 0, 'R', 0);//NC 4
$neto = $base - $nc;
$total_neto += $neto;
$pdf->Cell(50, 5, number_format($neto, 2, ',', '.'), 0, 1, 'R', 0);//NETO 5
// compras 0%
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Compras 0%'), 0, 0, 'L', 0);//COMPRAS 1
$query = pg_fetch_row(pg_query(
    "SELECT SUM(tarifa0) FROM factura_compra WHERE  tipo_comprobante='FACTURA' and   fecha_emision " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
));
$base = $query[0];
$total_base += $base;
$pdf->Cell(25, 5, "", 2, ',', '.', 0, 0, 'R', 0);//BASE 2
$pdf->Cell(25, 5, number_format($base, 2, ',', '.'), 0, 0, 'R', 0);//BASE 3
$query = pg_fetch_row(pg_query(
    "SELECT SUM(tarifa0) FROM devolucion_compra WHERE  num_autorizacion_sri::text " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
));
$nc = $query[0];
$total_nc += $nc;
$pdf->Cell(50, 5, number_format($nc, 2, ',', '.'), 0, 0, 'R', 0);//NC 4
$neto = $base - $nc;
$total_neto += $neto;
$pdf->Cell(50, 5, number_format($neto, 2, ',', '.'), 0, 1, 'R', 0);//NETO 5
// iva compras
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Iva%'), 0, 0, 'L', 0);//COMPRAS 1
$query = pg_fetch_row(pg_query(
    "SELECT SUM(iva_compra) FROM factura_compra WHERE  tipo_comprobante='FACTURA' and   fecha_emision " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
));
$base = $query[0];
$total_base += $base;
$pdf->Cell(25, 5, "", 2, ',', '.', 0, 0, 'R', 0);//BASE 2
$pdf->Cell(25, 5, number_format($base, 2, ',', '.'), 0, 0, 'R', 0);//BASE 3
$query = pg_fetch_row(pg_query(
    "SELECT SUM(tarifa0) FROM devolucion_compra WHERE  num_autorizacion_sri::text " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
));
$nc = $query[0];
$total_nc += $nc;
$pdf->Cell(50, 5, number_format($nc, 2, ',', '.'), 0, 0, 'R', 0);//NC 4
$neto = $base - $nc;
$total_neto += $neto;
$pdf->Cell(50, 5, number_format($neto, 2, ',', '.'), 0, 1, 'R', 0);//NETO 5
// compras no iva
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Compras Nota V.'), 0, 0, 'L', 0);//COMPRAS 1
$query = pg_fetch_row(pg_query(
    "SELECT SUM(total_compra) FROM factura_compra WHERE tipo_comprobante='NOTA VENTA' AND fecha_emision " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
));
$base = $query[0];
$total_base1 += $base;
$pdf->Cell(25, 5, number_format($base, 2, ',', '.'), 0, 0, 'R', 0);//BASE 2
$pdf->Cell(25, 5, "", 2, ',', '.', 0, 0, 'R', 0);//BASE 3
$query = pg_fetch_row(pg_query(
    "SELECT SUM(total_compra) FROM devolucion_compra WHERE tipo_comprobante='NOTA VENTA' AND num_autorizacion_sri::text " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
));
$nc = $query[0];
$total_nc += $nc;
$pdf->Cell(50, 5, number_format($nc, 2, ',', '.'), 0, 0, 'R', 0);//NC 4
$neto = $base - $nc;
$total_neto += $neto;
$pdf->Cell(50, 5, number_format($neto, 2, ',', '.'), 0, 1, 'R', 0);//NETO 5
$pdf->SetX(5);
$pdf->Cell(200, 0, utf8_decode(""), 1, 1, 'R', 0); //5
// totales compras
$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Totales'), 0, 0, 'L', 0);
$pdf->Cell(25, 5, number_format($total_base1, 2, ',', '.'), 0, 0, 'R', 0);
$pdf->Cell(25, 5, number_format($total_base, 2, ',', '.'), 0, 0, 'R', 0);
$pdf->Cell(50, 5, number_format($total_nc, 2, ',', '.'), 0, 0, 'R', 0);
$pdf->Cell(50, 5, number_format($total_neto, 2, ',', '.'), 0, 1, 'R', 0);
$pdf->Ln(3);
$pdf->SetX(5);
$pdf->Cell(100, 5, utf8_decode('ICE: ' . number_format($default, 2, ',', '.')), 0, 0, 'L', 0);
$pdf->Cell(100, 5, utf8_decode('RISE: ' . number_format($default, 2, ',', '.')), 0, 1, 'L', 0);
$pdf->Ln(3);
// tabla retenciones iva
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('RETENCIONES IVA'), 0, 1, 'L', 0);
// cuerpo
$pdf->SetFont('helvetica', '', 9);
$query = pg_query(
    "SELECT porsentaje, SUM(valor_retenido) FROM retencion_fuente_factura_compra r 
    INNER JOIN detallecomprobanteretencion d USING(id_retencion_fuente_factura_compra)
    INNER JOIN factura_compra f ON r.id_factura=f.id_factura_compra 
    WHERE d.id_trete='2' AND r.id_gastos='1' AND f.estado='Activo' 
    AND f.fecha_emision " . $query_fecha . "'$_GET[fin]' 
    GROUP BY porsentaje ORDER BY porsentaje ASC;"
);
$total = 0;
while ($row = pg_fetch_row($query)) {
    $pdf->SetX(5);
    $pdf->Cell(50, 5, utf8_decode($row[0] . '%'), 0, 0, 'L', 0);
    $pdf->Cell(50, 5, number_format($row[1], 2, ',', '.'), 0, 1, 'R', 0);
    $total += $row[1];
}
$pdf->SetX(5);
$pdf->Cell(100, 0, utf8_decode(""), 1, 1, 'R', 0);
// totales
$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Total'), 0, 0, 'L', 0);
$pdf->Cell(50, 5, number_format($total, 2, ',', '.'), 0, 1, 'R', 0);
$pdf->Ln(3);
// tabla reteciones impuesto renta
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('RETENCIONES IMP. RENTA'), 0, 1, 'L', 0);
// encabezado
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Codigo'), 0, 0, 'L', 0);
$pdf->Cell(50, 5, utf8_decode('Base'), 0, 0, 'R', 0);
$pdf->Cell(50, 5, utf8_decode('Retenido'), 0, 1, 'R', 0);
// cuerpo
$pdf->SetFont('helvetica', '', 9);
$query = pg_query(
    "SELECT codigo_formulario, porsentaje, SUM(base_imponible), SUM(valor_retenido) FROM retencion_fuente_factura_compra r
    INNER JOIN detallecomprobanteretencion dr USING(id_retencion_fuente_factura_compra) 
    INNER JOIN retencion_fuentes rf USING(id_retencion_fuentes)
    INNER JOIN factura_compra f ON r.id_factura=f.id_factura_compra 
    WHERE dr.id_trete='1' AND r.id_gastos='1' AND f.estado='Activo'
    AND f.fecha_emision " . $query_fecha . "'$_GET[fin]' 
    GROUP BY codigo_formulario, porsentaje ORDER BY codigo_formulario ASC;"
);
$total_base = 0;
$total_retenido = 0;
while ($row = pg_fetch_row($query)) {
    $pdf->SetX(5);
    $pdf->Cell(50, 5, utf8_decode($row[0]) . ': ' . $row[1] . '%', 0, 0, 'L', 0);
    $pdf->Cell(50, 5, number_format($row[2], 2, ',', '.'), 0, 0, 'R', 0);
    $total_base += $row[2];
    $pdf->Cell(50, 5, number_format($row[3], 2, ',', '.'), 0, 1, 'R', 0);
    $total_retenido += $row[3];
}
// totales
$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetX(5);
$pdf->Cell(150, 0, utf8_decode(""), 1, 1, 'R', 0);
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Total'), 0, 0, 'L', 0);
$pdf->Cell(50, 5, number_format($total_base, 2, ',', '.'), 0, 0, 'R', 0);
$pdf->Cell(50, 5, number_format($total_retenido, 2, ',', '.'), 0, 1, 'R', 0);
$pdf->Ln(3);
// documentos en compras
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Documentos en compras'), 0, 1, 'L', 0);
$pdf->SetX(5);

$query = pg_fetch_row(pg_query("SELECT COUNT(*) FROM factura_compra WHERE tipo_comprobante='FACTURA' and fecha_emision " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"));
$pdf->Cell(50, 5, utf8_decode('Factura: ' . $query[0]), 0, 0, 'L', 0);
$pdf->SetX(55);
$query = pg_fetch_row(pg_query("SELECT COUNT(*) FROM factura_compra WHERE  tipo_comprobante='NOTA VENTA' and fecha_emision " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"));
$pdf->Cell(50, 5, utf8_decode('Notas Venta: ' . $query[0]), 0, 0, 'L', 0);

$query = pg_fetch_row(pg_query("SELECT COUNT(*) FROM devolucion_compra WHERE num_autorizacion_sri::text " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"));
$pdf->Cell(50, 5, utf8_decode('Nota de Credito: ' . $query[0]), 0, 1, 'L', 0);
// FIN COMPRAS
// GASTOS
$pdf->Ln(3);
// tabla gastos
// encabezado
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('GASTOS'), 0, 0, 'L', 0);
$pdf->Cell(50, 5, utf8_decode('NETO'), 0, 1, 'R', 0);
// cuerpo
$pdf->SetFont('helvetica', '', 9);
$total_neto = 0;
// gastos 12%
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Gastos 12%'), 0, 0, 'L', 0);
$query = pg_fetch_row(pg_query(
    "SELECT SUM(total) FROM gastos WHERE tarifa12>0 AND fecha_emision " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
));
$neto = $query[0];
$total_neto += $neto;
$pdf->Cell(50, 5, number_format($neto, 2, ',', '.'), 0, 1, 'R', 0);
// gastos 0%
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Gastos 0%'), 0, 0, 'L', 0);
$query = pg_fetch_row(pg_query(
    "SELECT SUM(total) FROM gastos WHERE tarifa0>0 AND fecha_emision " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
));
$neto = $query[0];
$total_neto += $neto;
$pdf->Cell(50, 5, number_format($neto, 2, ',', '.'), 0, 1, 'R', 0);
$pdf->SetX(5);
$pdf->Cell(100, 0, utf8_decode(""), 1, 1, 'R', 0);
// totales gastos
$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Totales'), 0, 0, 'L', 0);
$pdf->Cell(50, 5, number_format($total_neto, 2, ',', '.'), 0, 1, 'R', 0);
// $pdf->Ln(3);
// $pdf->SetX(5);
// $pdf->Cell(50, 5, utf8_decode('ICE'), 0, 0, 'L', 0);
// $pdf->Cell(50, 5, number_format($default, 2, ',', '.'), 0, 1, 'R', 0);
// $pdf->SetX(5);
// $pdf->Cell(50, 5, utf8_decode('RISE'), 0, 0, 'L', 0);
// $pdf->Cell(50, 5, number_format($default, 2, ',', '.'), 0, 1, 'R', 0);
$pdf->Ln(3);
// tabla retenciones iva
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('RETENCIONES IVA'), 0, 1, 'L', 0);
// cuerpo
$pdf->SetFont('helvetica', '', 9);
$query = pg_query(
    "SELECT porsentaje, SUM(valor_retenido) FROM retencion_fuente_factura_compra r 
    INNER JOIN detallecomprobanteretencion d USING(id_retencion_fuente_factura_compra)
    INNER JOIN gastos g ON r.id_factura=g.id_gastos 
    WHERE d.id_trete='2' AND r.id_gastos='10' AND g.estado='Activo' 
    AND g.fecha_emision " . $query_fecha . "'$_GET[fin]' 
    GROUP BY porsentaje ORDER BY porsentaje ASC;"
);
$total = 0;
while ($row = pg_fetch_row($query)) {
    $pdf->SetX(5);
    $pdf->Cell(50, 5, utf8_decode($row[0] . '%'), 0, 0, 'L', 0);
    $pdf->Cell(50, 5, number_format($row[1], 2, ',', '.'), 0, 1, 'R', 0);
    $total += $row[1];
}
$pdf->SetX(5);
$pdf->Cell(100, 0, utf8_decode(""), 1, 1, 'R', 0);
// totales
$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Total'), 0, 0, 'L', 0);
$pdf->Cell(50, 5, number_format($total, 2, ',', '.'), 0, 1, 'R', 0);
$pdf->Ln(3);
// tabla reteciones impuesto renta
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('RETENCIONES IMP. RENTA'), 0, 1, 'L', 0);
// encabezado
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Codigo'), 0, 0, 'L', 0);
$pdf->Cell(50, 5, utf8_decode('Base'), 0, 0, 'R', 0);
$pdf->Cell(50, 5, utf8_decode('Retenido'), 0, 1, 'R', 0);
// cuerpo
$pdf->SetFont('helvetica', '', 9);
$query = pg_query(
    "SELECT codigo_formulario, porsentaje, SUM(base_imponible), SUM(valor_retenido) FROM retencion_fuente_factura_compra r
    INNER JOIN detallecomprobanteretencion dr USING(id_retencion_fuente_factura_compra) 
    INNER JOIN retencion_fuentes rf USING(id_retencion_fuentes)
    INNER JOIN gastos g ON r.id_factura=g.id_gastos 
    WHERE dr.id_trete='1' AND r.id_gastos='10' AND g.estado='Activo'
    AND g.fecha_emision " . $query_fecha . "'$_GET[fin]' 
    GROUP BY codigo_formulario, porsentaje ORDER BY codigo_formulario ASC;"
);
$total_base = 0;
$total_retenido = 0;
while ($row = pg_fetch_row($query)) {
    $pdf->SetX(5);
    $pdf->Cell(50, 5, utf8_decode($row[0]) . ': ' . $row[1] . '%', 0, 0, 'L', 0);
    $pdf->Cell(50, 5, number_format($row[2], 2, ',', '.'), 0, 0, 'R', 0);
    $total_base += $row[2];
    $pdf->Cell(50, 5, number_format($row[3], 2, ',', '.'), 0, 1, 'R', 0);
    $total_retenido += $row[3];
}
// totales
$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetX(5);
$pdf->Cell(150, 0, utf8_decode(""), 1, 1, 'R', 0);
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Total'), 0, 0, 'L', 0);
$pdf->Cell(50, 5, number_format($total_base, 2, ',', '.'), 0, 0, 'R', 0);
$pdf->Cell(50, 5, number_format($total_retenido, 2, ',', '.'), 0, 1, 'R', 0);
$pdf->Ln(3);
// documentos en gastos
$pdf->SetX(5);
$query = pg_fetch_row(pg_query("SELECT COUNT(*) FROM gastos WHERE fecha_emision " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"));
$pdf->Cell(50, 5, utf8_decode('Documentos en Gastos: ' . $query[0]), 0, 1, 'L', 0);
// FIN GASTOS
$pdf->Ln(5);
$pdf->Cell(230, 0, utf8_decode(""), 1, 1, 'R', 0);
$pdf->Ln(3);
// VENTAS
// encabezado
$pdf->SetX(5);


$pdf->Cell(50, 5, utf8_decode('VENTAS'), 0, 0, 'L', 0);
$pdf->Cell(25, 5, utf8_decode('Base NV'), 0, 0, 'R', 0);
$pdf->Cell(25, 5, utf8_decode('Base FAC.'), 0, 0, 'R', 0);
$pdf->Cell(50, 5, utf8_decode('NC'), 0, 0, 'R', 0);
$pdf->Cell(50, 5, utf8_decode('NETO'), 0, 1, 'R', 0);



// cuerpo
$pdf->SetFont('helvetica', '', 9);
$total_base = 0;
$total_nc = 0;
$total_neto = 0;
$total_base1=0;
// ventas 12%
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Ventas 12%'), 0, 0, 'L', 0); //ventas 1
$query = pg_fetch_row(pg_query(
    "SELECT SUM(tarifa12) FROM factura_venta WHERE  fecha_cancelacion " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
));
$base = $query[0];
$total_base += $base;
$pdf->Cell(25, 5, "", 2, ',', '.', 0, 0, 'R', 0);//BASE 2
$pdf->Cell(25, 5, number_format($base, 2, ',', '.'), 0, 0, 'R', 0); // base 2
$query = pg_fetch_row(pg_query(
    "SELECT SUM(tarifa12) FROM devolucion_venta WHERE tipo_comprobante='FACTURA' AND tarifa12>0 
    AND fecha_actual " . $query_fecha . "'$_GET[fin]' AND ( estado='Activo'  or  estado='2' or estado='1');"
));
$nc = $query[0];
$total_nc += $nc;
$pdf->Cell(50, 5, number_format($nc, 2, ',', '.'), 0, 0, 'R', 0);  //nc 3
$neto = $base - $nc;
$total_neto += $neto;
$pdf->Cell(50, 5, number_format($neto, 2, ',', '.'), 0, 1, 'R', 0); // neto 4
// ventas 0%
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Ventas 0%'), 0, 0, 'L', 0); // 1
$query = pg_fetch_row(pg_query(
    "SELECT SUM(tarifa0) FROM factura_venta WHERE  fecha_cancelacion " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
));
$base = $query[0];
$total_base += $base;
$pdf->Cell(25, 5, "", 2, ',', '.', 0, 0, 'R', 0);//BASE 2
$pdf->Cell(25, 5, number_format($base, 2, ',', '.'), 0, 0, 'R', 0); // 2
$query = pg_fetch_row(pg_query(
    "SELECT SUM(total_venta) FROM devolucion_venta WHERE tipo_comprobante='FACTURA' AND tarifa0>0 
    AND fecha_actual " . $query_fecha . "'$_GET[fin]' AND ( estado='Activo'  or  estado='2' or estado='1') ;"
));
$nc = $query[0];
$total_nc += $nc;
$pdf->Cell(50, 5, number_format($nc, 2, ',', '.'), 0, 0, 'R', 0); //3
$neto = $base - $nc;
$total_neto += $neto;
$pdf->Cell(50, 5, number_format($neto, 2, ',', '.'), 0, 1, 'R', 0); //4
// iva compras
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Iva%'), 0, 0, 'L', 0);//COMPRAS 1
$query = pg_fetch_row(pg_query(
    "SELECT SUM(iva_venta) FROM factura_venta WHERE fecha_actual " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
));
$base = $query[0];
$total_base += $base;
$pdf->Cell(25, 5, "", 2, ',', '.', 0, 0, 'R', 0);//BASE 2
$pdf->Cell(25, 5, number_format($base, 2, ',', '.'), 0, 0, 'R', 0);//BASE 3
$query = pg_fetch_row(pg_query(
    "SELECT SUM(iva_venta) FROM devolucion_venta WHERE  fecha_actual " . $query_fecha . "'$_GET[fin]' AND ( estado='Activo'  or  estado='2' or  estado='1') ;"
));
$nc = $query[0];
$total_nc += $nc;
$pdf->Cell(50, 5, number_format($nc, 2, ',', '.'), 0, 0, 'R', 0);//NC 4
$neto = $base - $nc;
$total_neto += $neto;
$pdf->Cell(50, 5, number_format($neto, 2, ',', '.'), 0, 1, 'R', 0);//NETO 5
// ventas no iva
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Ventas NV'), 0, 0, 'L', 0); // 1
$query = pg_fetch_row(pg_query(
    "SELECT SUM(total_venta) FROM facturas_novalidas WHERE fecha_actual " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
));
$base = $query[0];
$total_base1 += $base;
$pdf->Cell(25, 5, number_format($base, 2, ',', '.'), 0, 0, 'R', 0);//BASE 2
$pdf->Cell(25, 5, "", 2, ',', '.', 0, 0, 'R', 0);//BASE 3
$query = pg_fetch_row(pg_query(
    "SELECT SUM(total_venta) FROM devolucion_venta WHERE tipo_comprobante='NOTA VENTA' 
    AND fecha_actual " . $query_fecha . "'$_GET[fin]' AND ( estado='Activo'  or  estado='2' or estado='1') ;"
));
$nc = $query[0];
$total_nc += $nc;
$pdf->Cell(50, 5, number_format($nc, 2, ',', '.'), 0, 0, 'R', 0); //3
$neto = $base - $nc;
$total_neto += $neto;
$pdf->Cell(50, 5, number_format($neto, 2, ',', '.'), 0, 1, 'R', 0); //4
// $pdf->SetX(5);
// $pdf->Cell(50, 5, utf8_decode('COMPROBANTE 44'), 0, 0, 'L', 0);
// $pdf->Cell(50, 5, number_format($default, 2, ',', '.'), 0, 0, 'R', 0);
// $pdf->Cell(50, 5, number_format($default, 2, ',', '.'), 0, 0, 'R', 0);
// $pdf->Cell(50, 5, number_format($default, 2, ',', '.'), 0, 1, 'R', 0);
// $pdf->SetX(5);
// $pdf->Cell(50, 5, utf8_decode('COMPROBANTE 370'), 0, 0, 'L', 0);
// $pdf->Cell(50, 5, number_format($default, 2, ',', '.'), 0, 0, 'R', 0);
// $pdf->Cell(50, 5, number_format($default, 2, ',', '.'), 0, 0, 'R', 0);
// $pdf->Cell(50, 5, number_format($default, 2, ',', '.'), 0, 1, 'R', 0);
$pdf->SetX(5);
$pdf->Cell(200, 0, utf8_decode(""), 1, 1, 'R', 0);
// total ventas
$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetX(5);



$pdf->Cell(50, 5, utf8_decode('Totales'), 0, 0, 'L', 0);
$pdf->Cell(25, 5, number_format($total_base1, 2, ',', '.'), 0, 0, 'R', 0);
$pdf->Cell(25, 5, number_format($total_base, 2, ',', '.'), 0, 0, 'R', 0);
$pdf->Cell(50, 5, number_format($total_nc, 2, ',', '.'), 0, 0, 'R', 0);
$pdf->Cell(50, 5, number_format($total_neto, 2, ',', '.'), 0, 1, 'R', 0);

$pdf->Ln(3);
// tabla retenciones
$pdf->SetX(5);
$query = pg_fetch_row(pg_query(
    "SELECT SUM(valor_retencion) FROM retencion_fuente_factura_venta r
    INNER JOIN factura_venta f ON r.id_factura=f.id_factura_venta
    WHERE f.fecha_actual " . $query_fecha . "'$_GET[fin]' AND f.estado='Activo';"
));
$rf = $query[0];
$pdf->Cell(100, 5, utf8_decode('Retencion Renta: ' . number_format($rf, 2, ',', '.')), 0, 0, 'L', 0);
$query = pg_fetch_row(pg_query(
    "SELECT SUM(valor_retencion) FROM retencion_iva_factura_venta r
    INNER JOIN factura_venta f ON r.id_factura=f.id_factura_venta  
    WHERE r.fecha " . $query_fecha . "'$_GET[fin]' AND f.estado='Activo';"
));
$ri = $query[0];
$pdf->Cell(100, 5, utf8_decode('Retencion IVA: ' . number_format($ri, 2, ',', '.')), 0, 1, 'L', 0);
$pdf->Ln(3);
// documentos en ventas
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Documentos en ventas'), 0, 1, 'L', 0);
$pdf->SetX(5);
$query = pg_fetch_row(pg_query("SELECT COUNT(*) FROM factura_venta WHERE estado='Activo' AND fecha_actual " . $query_fecha . "'$_GET[fin]';"));
$fv = $query[0];
$pdf->Cell(35, 5, utf8_decode('Factura: ' . $fv), 0, 0, 'L', 0);

$query = pg_fetch_row(pg_query("SELECT COUNT(*) FROM facturas_novalidas WHERE estado='Activo' AND fecha_actual " . $query_fecha . "'$_GET[fin]';"));
$nv = $query[0];
$pdf->Cell(40, 5, utf8_decode('Notas Venta: ' . $nv), 0, 0, 'L', 0);

$query = pg_fetch_row(pg_query("SELECT COUNT(*) FROM devolucion_venta WHERE estado='Activo' AND fecha_actual " . $query_fecha . "'$_GET[fin]';"));
$nv = $query[0];
$pdf->Cell(40, 5, utf8_decode('Notas Credito: ' . $nv), 0, 1, 'L', 0);
// FIN VENTAS
$pdf->Ln(3);
$pdf->Cell(230, 0, utf8_decode(""), 1, 1, 'R', 0);
$pdf->setX(1);

$pdf->Output();
