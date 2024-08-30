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
        //        $this->Cell(105, 5, $fecha, 0, 0, 'C', 0);
        //        $this->Cell(105, 5, "TESORERIA", 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 14);

        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);
        $this->Line(0, 5, 210, 5);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(210, 5, utf8_decode("RESUMEN DE COMPRAS Y VENTAS" . "  " . 'DESDE: ' . $_GET['inicio'] . " " . 'HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        //        if ($this->rango) {
        //            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
        //            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        //        } else {
        //            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        //        }
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
//$pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
$pdf->Ln(1);
// tabla compras
// encabezado
$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('COMPRAS'), 0, 0, 'L', 0); //1
$pdf->Cell(25, 5, utf8_decode('Base NV'), 0, 0, 'R', 0); //2
$pdf->Cell(25, 5, utf8_decode('Base FAC.'), 0, 0, 'R', 0); //3
$pdf->Cell(50, 5, utf8_decode('NC'), 0, 0, 'R', 0); //4
$pdf->Cell(50, 5, utf8_decode('NETO'), 0, 1, 'R', 0); //5
// cuerpo
$pdf->SetFont('helvetica', '', 9);
$total_base = 0;
$total_base1 = 0;
$total_nc = 0;
$total_neto = 0;
$tot12 = 0;
$tot0 = 0;


/////////////
//VARIABLES//
/////////////
$base_compras_15 = 0;
$iva_compras_15 = 0;
$base_iva_compra_diferenciado = 0;
$iva_compra_diferenciado = 0;
$base_compras_5 = 0;
$iva_compras_5 = 0;
$base_compras_0 = 0;
$iva_total_compras = 0;



//////////
$base_gastos_15 = 0;
$iva_gastos_15 = 0;
$base_iva_gasto_diferenciado = 0;
$iva_gasto_diferenciado = 0;
$base_gastos_5 = 0;
$iva_gastos_5 = 0;
$base_gastos_0 = 0;
$iva_total_gastos = 0;

$pdf->SetX(5);
//=====================================================================================
$pdf->Cell(50, 5, utf8_decode('Compras 15%'), 0, 0, 'L', 0); //COMPRAS 1
$query = pg_fetch_row(pg_query(
    "SELECT SUM(tarifa12), SUM(iva_compra) FROM factura_compra WHERE  tipo_comprobante='FACTURA'   AND fecha_emision " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
));
$base_compras_15 = $query[0];
$iva_compras_15 = $query[1];


$query1 = pg_fetch_row(pg_query(
    "select SUM(base_imponible),SUM(valor_impuesto) from factura_compra fc 
inner join  detalle_factura_compra dfc on fc.id_factura_compra=dfc.id_factura_compra 
inner join  detalle_impuesto_producto_compra dipc on dfc.id_detalle_compra=dipc.id_detalle_compra  
WHERE  tipo_comprobante='FACTURA'   AND fecha_emision " . $query_fecha . "'$_GET[fin]' and tarifa='15' AND fc.estado='Activo'"
));
$base_compras_15 += $query1[0];
if (!empty($query1[1])) {
    $iva_compras_15 = $query1[1];
}


//BASE 2
$pdf->Cell(25, 5, "", 2, ',', '.', 0, 0, 'R', 0);
//BASE 3
//======================================================================================
$pdf->Cell(25, 5, number_format($base_compras_15, 2, ',', '.'), 0, 0, 'R', 0);
///=======================================================================================
$query = pg_fetch_row(pg_query(
    "SELECT SUM(tarifa12) FROM devolucion_compra WHERE  num_autorizacion_sri::text " . $query_fecha . "'$_GET[fin]' AND estado='Activo' and tipo_devolucion='C';"
));
$nc = $query[0];
$total_nc += $nc;
//NC 4
//======================================================================================
$pdf->Cell(50, 5, number_format($nc, 2, ',', '.'), 0, 0, 'R', 0);
$neto = $base_compras_15 - $nc;
$total_neto += $neto;
//NETO 5
//======================================================================================
$pdf->Cell(50, 5, number_format($neto, 2, ',', '.'), 0, 1, 'R', 0);


///////////////////
/////////////////
//////////iva diferenciado///////////////
/////////////////////
/////////////////////
$pdf->SetX(5);
//=====================================================================================
$pdf->Cell(50, 5, utf8_decode('Iva diferenciado'), 0, 0, 'L', 0); //COMPRAS 1
//$query = pg_fetch_row(pg_query(
//    "SELECT SUM(tarifa12) FROM factura_compra WHERE  tipo_comprobante='FACTURA'   AND fecha_emision " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
//));
//$base_compras_15 = $query[0];


$query1 = pg_fetch_row(pg_query(
    "select SUM(base_imponible),SUM(valor_impuesto) from factura_compra fc 
inner join  detalle_factura_compra dfc on fc.id_factura_compra=dfc.id_factura_compra 
inner join  detalle_impuesto_producto_compra dipc on dfc.id_detalle_compra=dipc.id_detalle_compra  
WHERE  tipo_comprobante='FACTURA'   AND fecha_emision " . $query_fecha . "'$_GET[fin]' and tarifa='8' AND fc.estado='Activo'"
));
$base_iva_compra_diferenciado = $query1[0];
$iva_compra_diferenciado = $query1[0];


//BASE 2
$pdf->Cell(25, 5, "", 2, ',', '.', 0, 0, 'R', 0);
//BASE 3
//======================================================================================
$pdf->Cell(25, 5, number_format($base_iva_compra_diferenciado, 2, ',', '.'), 0, 0, 'R', 0);
///=======================================================================================
$query = pg_fetch_row(pg_query(
    "SELECT SUM(tarifa12) FROM devolucion_compra WHERE  num_autorizacion_sri::text " . $query_fecha . "'$_GET[fin]' AND estado='Activo' and tipo_devolucion='C';"
));
//$nc = $query[0];
$nc = 0;
$total_nc += $nc;
//NC 4
//======================================================================================
$pdf->Cell(50, 5, number_format($nc, 2, ',', '.'), 0, 0, 'R', 0);
$neto = $base_iva_compra_diferenciado - $nc;
$total_neto += $neto;
//NETO 5
//======================================================================================
$pdf->Cell(50, 5, number_format($neto, 2, ',', '.'), 0, 1, 'R', 0);
//////////////////
//////////////////
//////////////////
//////////////////
////////TARIFA 5%//////////
//////////////////
//////////////////
//////////////////
$pdf->SetX(5);
//=====================================================================================
$pdf->Cell(50, 5, utf8_decode('Compras 5%'), 0, 0, 'L', 0); //COMPRAS 1
//$query = pg_fetch_row(pg_query(
//    "SELECT SUM(tarifa12) FROM factura_compra WHERE  tipo_comprobante='FACTURA'   AND fecha_emision " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
//));
//$base_compras_5 = $query[0];


$query1 = pg_fetch_row(pg_query(
    "select SUM(base_imponible),SUM(valor_impuesto) from factura_compra fc 
inner join  detalle_factura_compra dfc on fc.id_factura_compra=dfc.id_factura_compra 
inner join  detalle_impuesto_producto_compra dipc on dfc.id_detalle_compra=dipc.id_detalle_compra  
WHERE  tipo_comprobante='FACTURA' AND fecha_emision " . $query_fecha . "'$_GET[fin]' and tarifa='5' AND fc.estado='Activo'"
));
$base_compras_5 = $query1[0];
$iva_compras_5 = $query1[1];


//BASE 2
$pdf->Cell(25, 5, "", 2, ',', '.', 0, 0, 'R', 0);
//BASE 3
//======================================================================================
$pdf->Cell(25, 5, number_format($base_compras_5, 2, ',', '.'), 0, 0, 'R', 0);
///=======================================================================================
$query = pg_fetch_row(pg_query(
    "SELECT SUM(tarifa12) FROM devolucion_compra WHERE  num_autorizacion_sri::text " . $query_fecha . "'$_GET[fin]' AND estado='Activo' and tipo_devolucion='C';"
));
//$nc = $query[0];
$nc = 0;
$total_nc += $nc;
//NC 4
//======================================================================================
$pdf->Cell(50, 5, number_format($nc, 2, ',', '.'), 0, 0, 'R', 0);
$neto = $base_compras_5 - $nc;
$total_neto += $neto;
//NETO 5
//======================================================================================
$pdf->Cell(50, 5, number_format($neto, 2, ',', '.'), 0, 1, 'R', 0);

////////////////////////////////////
///////////////TARIFA 0%/////////////////////
////////////////////////////////////
////////////////////////////////////



// compras 0%
$pdf->SetX(5);
//======================================================================================
$pdf->Cell(50, 5, utf8_decode('Compras 0%'), 0, 0, 'L', 0); //COMPRAS 1
$query = pg_fetch_row(pg_query(
    "SELECT SUM(tarifa0) FROM factura_compra WHERE  tipo_comprobante='FACTURA' and   fecha_emision " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
));
$base_compras_0 = $query[0];

$query123 = pg_fetch_row(pg_query(
    "select SUM(base_imponible) from factura_compra fc 
inner join  detalle_factura_compra dfc on fc.id_factura_compra=dfc.id_factura_compra 
inner join  detalle_impuesto_producto_compra dipc on dfc.id_detalle_compra=dipc.id_detalle_compra  
WHERE  tipo_comprobante='FACTURA' AND fecha_emision " . $query_fecha . "'$_GET[fin]' and tarifa='0' AND fc.estado='Activo'"
));
$base_compras_0 += $query123[0];




//======================================================================================
$pdf->Cell(25, 5, "", 2, ',', '.', 0, 0, 'R', 0); //BASE 2
//======================================================================================
$pdf->Cell(25, 5, number_format($base_compras_0, 2, ',', '.'), 0, 0, 'R', 0); //BASE 3
$query = pg_fetch_row(pg_query(
    "SELECT SUM(tarifa0) FROM devolucion_compra WHERE  num_autorizacion_sri::text " . $query_fecha . "'$_GET[fin]' AND estado='Activo' and tipo_devolucion='C';"
));
$nc = $query[0];
$total_nc += $nc;
//======================================================================================
$pdf->Cell(50, 5, number_format($nc, 2, ',', '.'), 0, 0, 'R', 0); //NC 4
$neto = $base_compras_0 - $nc;
$total_neto += $neto;
//======================================================================================
$pdf->Cell(50, 5, number_format($neto, 2, ',', '.'), 0, 1, 'R', 0); //NETO 5




// SUBTOTAL
$pdf->SetX(5);
//======================================================================================
$pdf->Cell(50, 5, utf8_decode('Subtotal.c'), 0, 0, 'L', 0); //COMPRAS 1
$base11 = $tot0 + $tot12;
$result_subtotal = $base_compras_15 + $base_compras_5 + $base_compras_0 + $base_iva_compra_diferenciado;
//======================================================================================
$pdf->Cell(25, 5, "", 2, ',', '.', 0, 0, 'R', 0); //BASE 2
//======================================================================================
$pdf->Cell(25, 5, number_format($result_subtotal, 2, ',', '.'), 0, 0, 'R', 0); //BASE 3
$query = pg_fetch_row(pg_query(
    "SELECT SUM(tarifa0) FROM devolucion_compra WHERE  num_autorizacion_sri::text " . $query_fecha . "'$_GET[fin]' AND estado='Activo' and tipo_devolucion='C';"
));
$nc = $query[0];
$total_nc += $nc;
//======================================================================================
$pdf->Cell(50, 5, number_format($nc, 2, ',', '.'), 0, 0, 'R', 0); //NC 4
$neto1 = $result_subtotal - $nc;

$total_neto += $neto1;
//======================================================================================
$pdf->Cell(50, 5, number_format($neto1, 2, ',', '.'), 0, 1, 'R', 0); //NETO 5




// iva compras
$pdf->SetX(5);
//======================================================================================
$pdf->Cell(50, 5, utf8_decode('Iva%'), 0, 0, 'L', 0); //COMPRAS 1
$query = pg_fetch_row(pg_query(
    "SELECT SUM(iva_compra) FROM factura_compra WHERE  tipo_comprobante='FACTURA' and   fecha_emision " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
));
$iva_total_compras = $query[0];

//======================================================================================
$pdf->Cell(25, 5, "", 2, ',', '.', 0, 0, 'R', 0); //BASE 2
//======================================================================================
$pdf->Cell(25, 5, number_format($iva_total_compras, 2, ',', '.'), 0, 0, 'R', 0); //BASE 3
$query = pg_fetch_row(pg_query(
    "SELECT SUM(iva_compra) FROM devolucion_compra WHERE  num_autorizacion_sri::text " . $query_fecha . "'$_GET[fin]' AND estado='Activo' and tipo_devolucion='C';"
));
$nc = $query[0];
$total_nc += $nc;
//======================================================================================
$pdf->Cell(50, 5, number_format($nc, 2, ',', '.'), 0, 0, 'R', 0); //NC 4
$neto = $iva_total_compras - $nc;
$total_neto += $neto;

//======================================================================================
$pdf->Cell(50, 5, number_format($neto, 2, ',', '.'), 0, 1, 'R', 0); //NETO 5






// compras no iva
$pdf->SetX(5);
//======================================================================================
$pdf->Cell(50, 5, utf8_decode('Compras Nota V.'), 0, 0, 'L', 0); //COMPRAS 1
$query = pg_fetch_row(pg_query(
    "SELECT SUM(total_compra) FROM factura_compra WHERE tipo_comprobante='NOTA' AND fecha_emision " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
));
$base = $query[0];
$base_tota_venta = $base;
$total_base1 += $base;
//======================================================================================
$pdf->Cell(25, 5, number_format($base, 2, ',', '.'), 0, 0, 'R', 0); //BASE 2
//======================================================================================
$pdf->Cell(25, 5, "", 2, ',', '.', 0, 0, 'R', 0); //BASE 3
$query = pg_fetch_row(pg_query(
    "SELECT SUM(total_compra) FROM devolucion_compra WHERE tipo_comprobante='NOTA VENTA' AND num_autorizacion_sri::text " . $query_fecha . "'$_GET[fin]' AND estado='Activo' and tipo_devolucion='C';"
));
$nc = $query[0];
$total_nc += $nc;
//======================================================================================
$pdf->Cell(50, 5, number_format($nc, 2, ',', '.'), 0, 0, 'R', 0); //NC 4

$neto = $base - $nc;

$total_neto += $neto;

//echo '$nc'.$neto;
//======================================================================================
$pdf->Cell(50, 5, number_format($neto, 2, ',', '.'), 0, 1, 'R', 0); //NETO 5
$pdf->SetX(5);
//======================================================================================
$pdf->Cell(200, 0, utf8_decode(""), 1, 1, 'R', 0); //5
// totales compras
$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Totales C.'), 0, 0, 'L', 0);
$pdf->Cell(25, 5, number_format($total_base1, 2, ',', '.'), 0, 0, 'R', 0);
$total_facturas_compras = $base_compras_15 + $base_compras_5 + $base_compras_0 + $iva_total_compras + $base_iva_compra_diferenciado;
$pdf->Cell(25, 5, number_format($total_facturas_compras, 2, ',', '.'), 0, 0, 'R', 0);
$pdf->Cell(50, 5, number_format($total_nc, 2, ',', '.'), 0, 0, 'R', 0);

$pdf->Cell(50, 5, number_format($total_facturas_compras - $total_nc + $base_tota_venta, 2, ',', '.'), 0, 1, 'R', 0);
$pdf->Ln(1);
//////////////////////////////////////
//////////////////////////////////////
///////////RETENCIONES IVA////////////
/////////////////////////////////////
////////////////////////////////////
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('RETENCIONES IVA.'), 0, 1, 'L', 0);
// cuerpo
$pdf->SetFont('helvetica', '', 9);


////////////////////

$val_retencion_iva = 0;
$queryri = pg_query(
    "SELECT porsentaje, SUM(valor_retenido) FROM retencion_fuente_factura_compra r 
    INNER JOIN detallecomprobanteretencion d USING(id_retencion_fuente_factura_compra)
    INNER JOIN factura_compra f ON r.id_factura=f.id_factura_compra 
    WHERE d.id_trete='2' AND r.id_gastos='1' AND f.estado='Activo' 
    AND f.fecha_emision " . $query_fecha . "'$_GET[fin]' 
    GROUP BY porsentaje ORDER BY porsentaje ASC;"
);


$total = 0;
while ($row = pg_fetch_row($queryri)) {
    $pdf->SetX(5);
    //======================================================================================
    $pdf->Cell(50, 5, utf8_decode($row[0] . '%'), 0, 0, 'L', 0);
    //======================================================================================
    $pdf->Cell(50, 5, number_format($row[1], 2, ',', '.'), 0, 1, 'R', 0);
    $total += $row[1];
}
$pdf->SetX(5);
//======================================================================================
$pdf->Cell(100, 0, utf8_decode(""), 1, 1, 'R', 0);
// totales
$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetX(5);
//======================================================================================
$pdf->Cell(50, 5, utf8_decode('Total.'), 0, 0, 'L', 0);
//======================================================================================
$pdf->Cell(50, 5, number_format($total, 2, ',', '.'), 0, 1, 'R', 0);
$pdf->Ln(0);
// tabla reteciones impuesto renta
$pdf->SetX(5);
//======================================================================================
$pdf->Cell(50, 5, utf8_decode('RETENCIONES IMP. RENTA.'), 0, 1, 'L', 0);
// encabezado
$pdf->SetX(5);
//======================================================================================
$pdf->Cell(50, 5, utf8_decode('Codigo'), 0, 0, 'L', 0);
//======================================================================================
$pdf->Cell(50, 5, utf8_decode('Base'), 0, 0, 'R', 0);
//======================================================================================
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
//$pdf->Ln(3);
// documentos en compras
$pdf->SetX(5);
//$pdf->Cell(50, 5, utf8_decode('Documentos en compras'), 0, 1, 'L', 0);
$pdf->SetX(5);

$query = pg_fetch_row(pg_query("SELECT COUNT(*) FROM factura_compra WHERE tipo_comprobante='FACTURA' and fecha_emision " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"));
$pdf->Cell(50, 5, utf8_decode('Factura: ' . $query[0]), 0, 0, 'L', 0);
$pdf->SetX(55);
$query = pg_fetch_row(pg_query("SELECT COUNT(*) FROM factura_compra WHERE  tipo_comprobante='NOTA' and fecha_emision " . $query_fecha . "'$_GET[fin]' AND estado='Activo' or estado='Pendiente';"));
$pdf->Cell(50, 5, utf8_decode('Notas Venta C.: ' . $query[0]), 0, 0, 'L', 0);

$query = pg_fetch_row(pg_query("SELECT COUNT(*) FROM devolucion_compra WHERE num_autorizacion_sri::text " . $query_fecha . "'$_GET[fin]' AND estado='Activo' and tipo_devolucion='C';"));
$pdf->Cell(50, 5, utf8_decode('Nota de Credito: ' . $query[0]), 0, 1, 'L', 0);
// FIN COMPRAS

// ////////////////////////////
///////// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////
$pdf->Ln(3);
// tabla compras
// encabezado
$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('GASTOS'), 0, 0, 'L', 0);
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
$tot12 = 0;
$tot0 = 0;
// compras 15%
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Gastos 15%'), 0, 0, 'L', 0); //COMPRAS 1

$query = pg_fetch_row(pg_query(
    "SELECT SUM(tarifa12), SUM(iva_compra) FROM gastos WHERE  tipo_comprobante='FACTURA'   AND fecha_emision " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
));
$base_gastos_15 = $query[0];
$iva_gastos_15 = $query[1];


$query12345 = pg_fetch_row(pg_query(
    "select SUM(base_imponible), SUM(valor_impuesto) from gastos fc 
inner join  detalle_gastos dfc on fc.id_gastos=dfc.id_gastos 
inner join  detalle_impuesto_producto_gasto dipc on dfc.id_detalle_gastos=dipc.id_detalle_gastos  
WHERE  tipo_comprobante='FACTURA'   AND fecha_emision " . $query_fecha . "'$_GET[fin]' and tarifa='15' AND fc.estado='Activo'"
));
$base_gastos_15 += $query12345[0];
if (!empty($query12345[1])) {
    $iva_gastos_15 = $query12345[1];
}
//$base_gastos_15 = $query12345[0];

$pdf->Cell(25, 5, "", 2, ',', '.', 0, 0, 'R', 0); //BASE 2
$pdf->Cell(25, 5, number_format($base_gastos_15, 2, ',', '.'), 0, 0, 'R', 0); //BASE 3
$query = pg_fetch_row(pg_query(
    "SELECT SUM(tarifa12) FROM devolucion_compra WHERE  num_autorizacion_sri::text " . $query_fecha . "'$_GET[fin]' AND estado='Activo' and tipo_devolucion='G';"
));
$nc = $query[0];
$total_nc += $nc;
$pdf->Cell(50, 5, number_format($nc, 2, ',', '.'), 0, 0, 'R', 0); //NC 4
$neto = $base_gastos_15 - $nc;
$total_neto += $neto;
$pdf->Cell(50, 5, number_format($neto, 2, ',', '.'), 0, 1, 'R', 0); //NETO 5

/////////////////////////////////////////
/////////////////////////////////////////
/////////////GASTOS IVA DIFERENCIADO///////////////
//////////////////////////////////
/////////////////////////////
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Iva Diferenciado%'), 0, 0, 'L', 0); //COMPRAS 1
//echo 'LL'."SELECT SUM(tarifa12) FROM factura_compra WHERE  tipo_comprobante='FACTURA'   AND fecha_emision " . $query_fecha . "'$_GET[fin]' AND estado='Activo';";
//$query = pg_fetch_row(pg_query(
//    "SELECT SUM(tarifa12) FROM gastos WHERE  tipo_comprobante='FACTURA'   AND fecha_emision " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
//));
//$base_gastos_15 = $query[0];


$query123456 = pg_fetch_row(pg_query(
    "select SUM(base_imponible), SUM(valor_impuesto) from gastos fc 
inner join  detalle_gastos dfc on fc.id_gastos=dfc.id_gastos 
inner join  detalle_impuesto_producto_gasto dipc on dfc.id_detalle_gastos=dipc.id_detalle_gastos  
WHERE  tipo_comprobante='FACTURA'   AND fecha_emision " . $query_fecha . "'$_GET[fin]' and tarifa='8' AND fc.estado='Activo'"
));

//$base_gastos_15 += $query12345[0];
$base_iva_gasto_diferenciado = $query123456[0];
$iva_gasto_diferenciado = $query123456[1];


$pdf->Cell(25, 5, "", 2, ',', '.', 0, 0, 'R', 0); //BASE 2
$pdf->Cell(25, 5, number_format($base_iva_gasto_diferenciado, 2, ',', '.'), 0, 0, 'R', 0); //BASE 3
$query = pg_fetch_row(pg_query(
    "SELECT SUM(tarifa12) FROM devolucion_compra WHERE  num_autorizacion_sri::text " . $query_fecha . "'$_GET[fin]' AND estado='Activo' and tipo_devolucion='G';"
));
$nc = $query[0];
$total_nc += $nc;
$pdf->Cell(50, 5, number_format($nc, 2, ',', '.'), 0, 0, 'R', 0); //NC 4
$neto = $base_iva_gasto_diferenciado - $nc;
$total_neto += $neto;
$pdf->Cell(50, 5, number_format($neto, 2, ',', '.'), 0, 1, 'R', 0); //NETO 5

///////////////////////////////////////
//////////////////////////////////
//////////////////GASTOS 5//////////////////
/////////////////////////////////
/////////////////////////////////

$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Gastos 5%'), 0, 0, 'L', 0); //COMPRAS 1

$query12345 = pg_fetch_row(pg_query(
    "select SUM(base_imponible), SUM(valor_impuesto) from gastos fc 
inner join  detalle_gastos dfc on fc.id_gastos=dfc.id_gastos 
inner join  detalle_impuesto_producto_gasto dipc on dfc.id_detalle_gastos=dipc.id_detalle_gastos  
WHERE  tipo_comprobante='FACTURA'   AND fecha_emision " . $query_fecha . "'$_GET[fin]' and tarifa='5' AND fc.estado='Activo'"
));

$base_gastos_5 = $query12345[0];
$iva_gastos_5 = $query12345[1];



$pdf->Cell(25, 5, "", 2, ',', '.', 0, 0, 'R', 0); //BASE 2
$pdf->Cell(25, 5, number_format($base_gastos_5, 2, ',', '.'), 0, 0, 'R', 0); //BASE 3
$query = pg_fetch_row(pg_query(
    "SELECT SUM(tarifa12) FROM devolucion_compra WHERE  num_autorizacion_sri::text " . $query_fecha . "'$_GET[fin]' AND estado='Activo' and tipo_devolucion='G';"
));
$nc = $query[0];
$total_nc += $nc;
$pdf->Cell(50, 5, number_format($nc, 2, ',', '.'), 0, 0, 'R', 0); //NC 4
$neto = $base_gastos_5 - $nc;
$total_neto += $neto;
$pdf->Cell(50, 5, number_format($neto, 2, ',', '.'), 0, 1, 'R', 0); //NETO 5
////////////////////////////////
///////////////////////////////
//////////////GATOS 0/////////////////
///////////////////////////////
/////////////////////////////


// compras 0%
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Gastos 0%'), 0, 0, 'L', 0); //COMPRAS 1
$query = pg_fetch_row(pg_query(
    "SELECT SUM(tarifa0) FROM gastos WHERE  tipo_comprobante='FACTURA' and   fecha_emision " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
));
$base_gastos_0 = $query[0];
$query123456 = pg_fetch_row(pg_query(
    "select SUM(base_imponible) from gastos fc 
inner join  detalle_gastos dfc on fc.id_gastos=dfc.id_gastos 
inner join  detalle_impuesto_producto_gasto dipc on dfc.id_detalle_gastos=dipc.id_detalle_gastos  
WHERE  tipo_comprobante='FACTURA'   AND fecha_emision " . $query_fecha . "'$_GET[fin]' and tarifa='0' AND fc.estado='Activo'"
));

$base_gastos_0 += $query123456[0];

$base_sub0 = $query[0];
$tot0 += $base_sub0;

$pdf->Cell(25, 5, "", 2, ',', '.', 0, 0, 'R', 0); //BASE 2
$pdf->Cell(25, 5, number_format($base_gastos_0, 2, ',', '.'), 0, 0, 'R', 0); //BASE 3
//$query = pg_fetch_row(pg_query(
//    "SELECT SUM(tarifa0) FROM devolucion_compra WHERE  num_autorizacion_sri::text " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
//));
//$nc = $query[0];
//$total_nc += $nc;
$pdf->Cell(50, 5, number_format($nc, 2, ',', '.'), 0, 0, 'R', 0); //NC 4
$neto = $base_gastos_0 - $nc;
$total_neto += $neto;
$pdf->Cell(50, 5, number_format($neto, 2, ',', '.'), 0, 1, 'R', 0); //NETO 5
//

// SUBTOTAL
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Subtotal.G'), 0, 0, 'L', 0); //COMPRAS 1


$pdf->Cell(25, 5, "", 2, ',', '.', 0, 0, 'R', 0); //BASE 2
$result_gasto_subtotal = $base_gastos_15 + $base_gastos_5 + $base_gastos_0 + $base_iva_gasto_diferenciado;
$pdf->Cell(25, 5, number_format($result_gasto_subtotal, 2, ',', '.'), 0, 0, 'R', 0); //BASE 3
//$query = pg_fetch_row(pg_query(
//    "SELECT SUM(tarifa0) FROM devolucion_compra WHERE  num_autorizacion_sri::text " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
//));
//$nc = $query[0];
//$total_nc += $nc;
$pdf->Cell(50, 5, number_format($nc, 2, ',', '.'), 0, 0, 'R', 0); //NC 4
$neto1 = $result_gasto_subtotal - $nc;


$pdf->Cell(50, 5, number_format($neto1, 2, ',', '.'), 0, 1, 'R', 0); //NETO 5


//
// iva compras
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Iva%'), 0, 0, 'L', 0); //COMPRAS 1
$query = pg_fetch_row(pg_query(
    "SELECT SUM(iva_compra) FROM gastos WHERE  tipo_comprobante='FACTURA' and   fecha_emision " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
));
$iva_total_gastos = $query[0];

$pdf->Cell(25, 5, "", 2, ',', '.', 0, 0, 'R', 0); //BASE 2
$pdf->Cell(25, 5, number_format($iva_total_gastos, 2, ',', '.'), 0, 0, 'R', 0); //BASE 3
//$query = pg_fetch_row(pg_query(
//    "SELECT SUM(iva_compra) FROM devolucion_compra WHERE  num_autorizacion_sri::text " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
//));
//$nc = $query[0];
//$total_nc += $nc;

$pdf->Cell(50, 5, number_format($nc, 2, ',', '.'), 0, 0, 'R', 0); //NC 4
$neto = $iva_total_gastos - $nc;
$total_neto += $neto;
$pdf->Cell(50, 5, number_format($neto, 2, ',', '.'), 0, 1, 'R', 0); //NETO 5
// compras no iva
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Gastos Nota V.'), 0, 0, 'L', 0); //COMPRAS 1
$query = pg_fetch_row(pg_query(
    "SELECT SUM(total_compra) FROM gastos WHERE tipo_comprobante='NOTA VENTA' AND fecha_emision " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
));
$base = $query[0];

$pdf->Cell(25, 5, number_format($base, 2, ',', '.'), 0, 0, 'R', 0); //BASE 2
$pdf->Cell(25, 5, "", 2, ',', '.', 0, 0, 'R', 0); //BASE 3
//$query = pg_fetch_row(pg_query(
//    "SELECT SUM(total_compra) FROM devolucion_compra WHERE tipo_comprobante='NOTA VENTA' AND num_autorizacion_sri::text " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
//));
//$nc = $query[0];
//$total_nc += $nc;
$pdf->Cell(50, 5, number_format($nc, 2, ',', '.'), 0, 0, 'R', 0); //NC 4
$neto = $base - $nc;
$total_neto += $neto;
$pdf->Cell(50, 5, number_format($neto, 2, ',', '.'), 0, 1, 'R', 0); //NETO 5
$pdf->SetX(5);
$pdf->Cell(200, 0, utf8_decode(""), 1, 1, 'R', 0); //5
// totales compras
$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Totales G.'), 0, 0, 'L', 0);
$pdf->Cell(25, 5, number_format($total_base1, 2, ',', '.'), 0, 0, 'R', 0);
$total_facturas_gastos = $base_gastos_15 + $base_gastos_5 + $base_gastos_0 + $iva_total_gastos + $base_iva_gasto_diferenciado;
$pdf->Cell(25, 5, number_format($total_facturas_gastos, 2, ',', '.'), 0, 0, 'R', 0);
$pdf->Cell(50, 5, number_format($total_nc, 2, ',', '.'), 0, 0, 'R', 0);
$pdf->Cell(50, 5, number_format($total_facturas_gastos - $total_nc + $neto, 2, ',', '.'), 0, 1, 'R', 0);


////////////////////////////
///////// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////

$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('RETENCIONES IVA..'), 0, 1, 'L', 0);
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
$pdf->Ln(-1);
// tabla reteciones impuesto renta
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('RETENCIONES IMP. RENTA..'), 0, 1, 'L', 0);
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
$pdf->Ln(-1);
// documentos en gastos
$pdf->SetX(5);

//$pdf->Cell(50, 5, utf8_decode('Documentos en Gastos: ' . $query[0]), 0, 1, 'L', 0);
$pdf->SetX(5);

$query = pg_fetch_row(pg_query("SELECT COUNT(*) FROM gastos WHERE tipo_comprobante='FACTURA' and fecha_emision " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"));
$pdf->Cell(50, 5, utf8_decode('Factura: ' . $query[0]), 0, 0, 'L', 0);
$pdf->SetX(55);
$query = pg_fetch_row(pg_query("SELECT COUNT(*) FROM gastos WHERE  tipo_comprobante='NOTA VENTA' and fecha_emision " . $query_fecha . "'$_GET[fin]' AND estado='Activo' or estado='Pendiente';"));
$pdf->Cell(50, 5, utf8_decode('Notas Venta G.: ' . $query[0]), 0, 0, 'L', 0);

$query = pg_fetch_row(pg_query("SELECT COUNT(*) FROM devolucion_compra WHERE num_autorizacion_sri::text " . $query_fecha . "'$_GET[fin]' AND estado='Activo' and tipo_devolucion='G';"));
$pdf->Cell(50, 5, utf8_decode('Nota de Credito: ' . $query[0]), 0, 1, 'L', 0);

$pdf->Cell(230, 0, utf8_decode(""), 1, 1, 'R', 0);

// VENTAS
// encabezado
$pdf->SetX(5);


$pdf->Cell(50, 5, utf8_decode('VENTAS'), 0, 0, 'L', 0);
$pdf->Cell(25, 5, utf8_decode('Base NV'), 0, 0, 'R', 0);
$pdf->Cell(25, 5, utf8_decode('Base FAC.'), 0, 0, 'R', 0);
$pdf->Cell(50, 5, utf8_decode('NC'), 0, 0, 'R', 0);
$pdf->Cell(50, 5, utf8_decode('NETO'), 0, 1, 'R', 0);


$tarifasfactura = obtenerTarifasImpuestoFactura();
$tarifas15 = [];
$tarifas0 = [];
$tarifas5 = [];
$tarifasdiferenciado = [];
foreach ($tarifasfactura as $key => $value) {
    if (floatval($value["tarifa"]) == 15) {
        $tarifas15 = $value;
    }
    if (floatval($value["tarifa"]) == 0) {
        $tarifas0 = $value;
    }
    if (floatval($value["tarifa"]) == 5) {
        $tarifas5 = $value;
    }
    if (floatval($value["tarifa"]) == 8) {
        $tarifasdiferenciado = $value;
    }
}

$tarifasfacturanc = obtenerTarifasImpuestoNotaCredito();
$tarifas15nc = [];
$tarifas0nc = [];
$tarifas5nc = [];
$tarifasdiferenciadonc = [];
foreach ($tarifasfacturanc as $key => $value) {
    if (floatval($value["tarifa"]) == 15) {
        $tarifas15nc = $value;
    }
    if (floatval($value["tarifa"]) == 0) {
        $tarifas0nc = $value;
    }
    if (floatval($value["tarifa"]) == 5) {
        $tarifas5nc = $value;
    }
    if (floatval($value["tarifa"]) == 8) {
        $tarifasdiferenciadonc = $value;
    }
}


// cuerpo
$pdf->SetFont('helvetica', '', 9);
$total_base = 0;
$total_nc = 0;
$total_neto = 0;
$total_base1 = 0;
// ventas 15%
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Ventas 15%'), 0, 0, 'L', 0); //ventas 1
$query = pg_fetch_row(pg_query(
    "SELECT SUM(tarifa12) FROM factura_venta WHERE  fecha_cancelacion " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
));
$base = $query[0];
if (!empty($tarifas15)) {
    $base += $tarifas15["base_imponible"];
}
$total_base += $base;
$pdf->Cell(25, 5, "", 2, ',', '.', 0, 0, 'R', 0); //BASE 2
$pdf->Cell(25, 5, number_format($base, 2, ',', '.'), 0, 0, 'R', 0); // base 2
$query = pg_fetch_row(pg_query(
    "SELECT SUM(tarifa12) FROM devolucion_venta WHERE tipo_comprobante='FACTURA' AND tarifa12>0 
    AND fecha_actual " . $query_fecha . "'$_GET[fin]' AND ( estado='Activo'  or  estado='2' or estado='1');"
));
$nc = $query[0];
if (!empty($tarifas15nc)) {
    $nc += $tarifas15nc["base_imponible"];
}
$total_nc += $nc;
$pdf->Cell(50, 5, number_format($nc, 2, ',', '.'), 0, 0, 'R', 0);  //nc 3
$neto = $base - $nc;
$total_neto += $neto;
$pdf->Cell(50, 5, number_format($neto, 2, ',', '.'), 0, 1, 'R', 0); // neto 4

// ventas diferenciado%
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Ventas Diferenciado%'), 0, 0, 'L', 0); // 1
$base = 0;
if (!empty($tarifasdiferenciado)) {
    $base += $tarifasdiferenciado["base_imponible"];
}
$total_base += $base;
$pdf->Cell(25, 5, "", 2, ',', '.', 0, 0, 'R', 0); //BASE 2
$pdf->Cell(25, 5, number_format($base, 2, ',', '.'), 0, 0, 'R', 0); // 2
$query = pg_fetch_row(pg_query(
    "SELECT SUM(total_venta) FROM devolucion_venta WHERE tipo_comprobante='FACTURA' AND tarifa0>0 
    AND fecha_actual " . $query_fecha . "'$_GET[fin]' AND ( estado='Activo'  or  estado='2' or estado='1') ;"
));
$nc = 0;
if (!empty($tarifasdiferenciadonc)) {
    $nc += $tarifasdiferenciadonc["base_imponible"];
}
$total_nc += $nc;
$pdf->Cell(50, 5, number_format($nc, 2, ',', '.'), 0, 0, 'R', 0); //3
$neto = $base - $nc;
$total_neto += $neto;
$pdf->Cell(50, 5, number_format($neto, 2, ',', '.'), 0, 1, 'R', 0); //4

// ventas 5%
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Ventas 5%'), 0, 0, 'L', 0); // 1
$base = 0;
if (!empty($tarifas5)) {
    $base += $tarifas5["base_imponible"];
}
$total_base += $base;
$pdf->Cell(25, 5, "", 2, ',', '.', 0, 0, 'R', 0); //BASE 2
$pdf->Cell(25, 5, number_format($base, 2, ',', '.'), 0, 0, 'R', 0); // 2
$nc = 0;
if (!empty($tarifas0nc)) {
    $nc += $tarifas0nc["base_imponible"];
}
$total_nc += $nc;
$pdf->Cell(50, 5, number_format($nc, 2, ',', '.'), 0, 0, 'R', 0); //3
$neto = $base - $nc;
$total_neto += $neto;
$pdf->Cell(50, 5, number_format($neto, 2, ',', '.'), 0, 1, 'R', 0); //4

// ventas 0%
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Ventas 0%'), 0, 0, 'L', 0); // 1
$query = pg_fetch_row(pg_query(
    "SELECT SUM(tarifa0) FROM factura_venta WHERE  fecha_cancelacion " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
));
$base = $query[0];
if (!empty($tarifas0)) {
    $base += $tarifas0["base_imponible"];
}
$total_base += $base;
$pdf->Cell(25, 5, "", 2, ',', '.', 0, 0, 'R', 0); //BASE 2
$pdf->Cell(25, 5, number_format($base, 2, ',', '.'), 0, 0, 'R', 0); // 2
$query = pg_fetch_row(pg_query(
    "SELECT SUM(total_venta) FROM devolucion_venta WHERE tipo_comprobante='FACTURA' AND tarifa0>0 
    AND fecha_actual " . $query_fecha . "'$_GET[fin]' AND ( estado='Activo'  or  estado='2' or estado='1') ;"
));
$nc = $query[0];
if (!empty($tarifas0nc)) {
    $nc += $tarifas0nc["base_imponible"];
}
$total_nc += $nc;
$pdf->Cell(50, 5, number_format($nc, 2, ',', '.'), 0, 0, 'R', 0); //3
$neto = $base - $nc;
$total_neto += $neto;
$pdf->Cell(50, 5, number_format($neto, 2, ',', '.'), 0, 1, 'R', 0); //4

// iva 15
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Iva 15%'), 0, 0, 'L', 0); //COMPRAS 1
$query = pg_fetch_row(pg_query(
    "SELECT SUM(iva_venta) FROM factura_venta WHERE  fecha_cancelacion " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
));
$base = $query[0];
if (!empty($tarifas15)) {
    $base += $tarifas15["valor_impuesto"];
}
$total_base += $base;
$pdf->Cell(25, 5, "", 2, ',', '.', 0, 0, 'R', 0); //BASE 2
$pdf->Cell(25, 5, number_format($base, 2, ',', '.'), 0, 0, 'R', 0); //BASE 3
$query = pg_fetch_row(pg_query(
    "SELECT SUM(iva_venta) FROM devolucion_venta WHERE  fecha_actual " . $query_fecha . "'$_GET[fin]' AND ( estado='Activo'  or  estado='2' or  estado='1') ;"
));
$nc = $query[0];
if (!empty($tarifas15nc)) {
    $nc += $tarifas15nc["valor_impuesto"];
}
$total_nc += $nc;
$pdf->Cell(50, 5, number_format($nc, 2, ',', '.'), 0, 0, 'R', 0); //NC 4
$neto = $base - $nc;
$total_neto += $neto;
$pdf->Cell(50, 5, number_format($neto, 2, ',', '.'), 0, 1, 'R', 0); //NETO 5

// iva diferenciado
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Iva diferenciado%'), 0, 0, 'L', 0); //COMPRAS 1
$base = 0;
if (!empty($tarifasdiferenciado)) {
    $base += $tarifasdiferenciado["valor_impuesto"];
}
$total_base += $base;
$pdf->Cell(25, 5, "", 2, ',', '.', 0, 0, 'R', 0); //BASE 2
$pdf->Cell(25, 5, number_format($base, 2, ',', '.'), 0, 0, 'R', 0); //BASE 3
$query = pg_fetch_row(pg_query(
    "SELECT SUM(iva_venta) FROM devolucion_venta WHERE  fecha_actual " . $query_fecha . "'$_GET[fin]' AND ( estado='Activo'  or  estado='2' or  estado='1') ;"
));
$nc = 0;
if (!empty($tarifasdiferenciadonc)) {
    $nc += $tarifasdiferenciadonc["valor_impuesto"];
}
$total_nc += $nc;
$pdf->Cell(50, 5, number_format($nc, 2, ',', '.'), 0, 0, 'R', 0); //NC 4
$neto = $base - $nc;
$total_neto += $neto;
$pdf->Cell(50, 5, number_format($neto, 2, ',', '.'), 0, 1, 'R', 0); //NETO 5

// iva 5%
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Iva 5%'), 0, 0, 'L', 0); //COMPRAS 1
$base = 0;
if (!empty($tarifas5)) {
    $base += $tarifas5["valor_impuesto"];
}
$total_base += $base;
$pdf->Cell(25, 5, "", 2, ',', '.', 0, 0, 'R', 0); //BASE 2
$pdf->Cell(25, 5, number_format($base, 2, ',', '.'), 0, 0, 'R', 0); //BASE 3
$nc = 0;
if (!empty($tarifas0nc)) {
    $nc += $tarifas0nc["valor_impuesto"];
}
$total_nc += $nc;
$pdf->Cell(50, 5, number_format($nc, 2, ',', '.'), 0, 0, 'R', 0); //NC 4
$neto = $base - $nc;
$total_neto += $neto;
$pdf->Cell(50, 5, number_format($neto, 2, ',', '.'), 0, 1, 'R', 0); //NETO 5


// ventas no iva
$pdf->SetX(5);
$pdf->Cell(50, 5, utf8_decode('Ventas NV'), 0, 0, 'L', 0); // 1
$query = pg_fetch_row(pg_query(
    "SELECT SUM(total_venta) FROM facturas_novalidas WHERE fecha_actual " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
));
$base = $query[0];
$total_base1 += $base;
$pdf->Cell(25, 5, number_format($base, 2, ',', '.'), 0, 0, 'R', 0); //BASE 2
$pdf->Cell(25, 5, "", 2, ',', '.', 0, 0, 'R', 0); //BASE 3
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

$pdf->Ln(1);
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
    WHERE f.fecha_actual " . $query_fecha . "'$_GET[fin]' AND f.estado='Activo';"
));
$ri = $query[0];
$pdf->Cell(100, 5, utf8_decode('Retencion IVA: ' . number_format($ri, 2, ',', '.')), 0, 1, 'L', 0);
$pdf->Ln(-1);
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

$query = pg_fetch_row(pg_query("SELECT COUNT(*) FROM devolucion_venta WHERE ( estado='Activo'  or  estado='2' or estado='1') AND fecha_actual " . $query_fecha . "'$_GET[fin]';"));
$nv = $query[0];
$pdf->Cell(40, 5, utf8_decode('Notas Credito: ' . $nv), 0, 1, 'L', 0);
// FIN VENTAS
$pdf->Ln(3);
$pdf->Cell(230, 0, utf8_decode(""), 1, 1, 'R', 0);
$pdf->setX(1);


///////////////////////////////////////////////
///////////////////////////////////////////////
///////////TOTALES COMPRAS GASTOS//////////////
///////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
$pdf->Ln(1);

$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetX(5);

$pdf->Cell(50, 5, utf8_decode('TOTALES COMPRAS + GASTOS (F.)'), 0, 0, 'L', 0); //1
$pdf->Cell(25, 5, utf8_decode('TOTALES'), 0, 0, 'R', 0); //2


// cuerpo
$pdf->SetFont('helvetica', '', 9);
$total_base = 0;
$total_base1 = 0;
$total_nc = 0;
$total_neto = 0;
$tot12 = 0;
$tot0 = 0;
$pdf->Ln(4);
$pdf->SetX(5);
//=====================================================================================
$pdf->Cell(75, 5, utf8_decode('Compras 15%'), 0, 0, 'L', 0); //COMPRAS 1
$suma_compras_gastos = $base_compras_15 + $base_gastos_15;

$pdf->Cell(25, 5, number_format($suma_compras_gastos, 2, ',', '.'), 0, 0, 'R', 0);

$pdf->Cell(75, 5, utf8_decode('IVA 15%'), 0, 0, 'L', 0); //COMPRAS 1
$suma_compras_gastos_iva = $iva_compras_15 + $iva_gastos_15;
$pdf->Cell(25, 5, number_format($suma_compras_gastos_iva, 2, ',', '.'), 0, 0, 'R', 0);

$pdf->Ln(5);
$pdf->SetX(5);
//=====================================================================================
$pdf->Cell(75, 5, utf8_decode('Compras Iva Diferenciado%'), 0, 0, 'L', 0); //COMPRAS 1
$suma_compras_gastos_dife = $base_iva_compra_diferenciado + $base_iva_gasto_diferenciado;

$pdf->Cell(25, 5, number_format($suma_compras_gastos_dife, 2, ',', '.'), 0, 0, 'R', 0);

$pdf->Cell(75, 5, utf8_decode('IVA Diferenciado%'), 0, 0, 'L', 0); //COMPRAS 1
$suma_compras_gastos_dife_iva = $iva_compra_diferenciado + $iva_gasto_diferenciado;

$pdf->Cell(25, 5, number_format($suma_compras_gastos_dife_iva, 2, ',', '.'), 0, 0, 'R', 0);

$pdf->Ln(5);
// compras 0%
$pdf->SetX(5);
//=====================================================================================
$pdf->Cell(75, 5, utf8_decode('Compras 5%'), 0, 0, 'L', 0); //COMPRAS 1
$suma_compras_gastos = $base_compras_5 + $base_gastos_5;

$pdf->Cell(25, 5, number_format($suma_compras_gastos, 2, ',', '.'), 0, 0, 'R', 0);

$pdf->Cell(75, 5, utf8_decode('IVA 5%'), 0, 0, 'L', 0); //COMPRAS 1
$suma_compras_gastos_iva = $iva_compras_5 + $iva_gastos_5;

$pdf->Cell(25, 5, number_format($suma_compras_gastos_iva, 2, ',', '.'), 0, 0, 'R', 0);


$pdf->Ln(5);
// compras 0%
$pdf->SetX(5);
//======================================================================================
$pdf->Cell(75, 5, utf8_decode('Compras 0%'), 0, 0, 'L', 0); //COMPRAS 1

$pdf->Cell(25, 5, number_format($base_compras_0 + $base_gastos_0, 2, ',', '.'), 0, 0, 'R', 0); //BASE 2

$pdf->Cell(75, 5, "", 0, 0, 'L', 0); //COMPRAS 1

$pdf->Cell(25, 5, "", 0, 0, 'R', 0); //BASE 2


$pdf->Ln(5);
// SUBTOTAL
$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetX(5);
$pdf->Cell(75, 5, utf8_decode('TOTAL:'), "T", 0, 'L', 0); //COMPRAS 1

$pdf->Cell(25, 5, number_format($result_gasto_subtotal + $result_subtotal, 2, ',', '.'), "T", 0, 'R', 0); //BASE 2

$pdf->Cell(75, 5, utf8_decode('TOTAL:'), "T", 0, 'L', 0); //COMPRAS 1

$total_iva_cg = $iva_total_compras + $iva_total_gastos;
$pdf->Cell(25, 5, number_format($total_iva_cg, 2, ',', '.'), "T", 0, 'R', 0); //BASE 2
$pdf->Ln(5);
/* //======================================================================================

$pdf->SetX(5);
$pdf->Cell(60, 5, utf8_decode('TOTAL'), 0, 0, 'L', 0); //COMPRAS 1

//======================================================================================
$total_iva_cg = $iva_total_compras + $iva_total_gastos;

$pdf->Cell(25, 5, number_format($total_facturas_gastos + $total_facturas_compras, 2, ',', '.'), 0, 0, 'R', 0); //BASE 2 */


///////////////////////////////////////////////
///////////////////////////////////////////////
///////////TOTALES NOTAS DE VENTA COMPRAS GASTOS//////////////
///////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
$pdf->Ln(6);

$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetX(5);

$pdf->Cell(50, 5, utf8_decode('TOTALES COMPRAS + GASTOS(N.V)'), 0, 0, 'L', 0); //1
$pdf->Cell(25, 5, utf8_decode('TOTALES'), 0, 0, 'R', 0); //2


// cuerpo
$pdf->SetFont('helvetica', '', 9);
$total_base = 0;
$total_base1 = 0;
$total_nc = 0;
$total_neto = 0;
$tot12 = 0;
$tot0 = 0;
$pdf->Ln(4);
$pdf->SetX(5);

// compras 0%
$pdf->SetX(5);
//======================================================================================
$pdf->Cell(60, 5, utf8_decode('Compras 0%'), 0, 0, 'L', 0); //COMPRAS 1

//$query = pg_fetch_row(pg_query(
//    "SELECT SUM(tarifa0) FROM factura_compra WHERE  tipo_comprobante='NOTA' and   fecha_emision " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
//));
//
//$tarifa_compra_nv_cero=$query[0];

$query1234 = pg_fetch_row(pg_query(
    "select SUM(base_imponible) from factura_compra fc 
inner join  detalle_factura_compra dfc on fc.id_factura_compra=dfc.id_factura_compra 
inner join  detalle_impuesto_producto_compra dipc on dfc.id_detalle_compra=dipc.id_detalle_compra  
WHERE  tipo_comprobante='NOTA'   AND fecha_emision " . $query_fecha . "'$_GET[fin]' and tarifa='0' AND fc.estado='Activo'"
));
//$tarifa_compra_nv_cero += $query1234[0];
$tarifa_compra_nv_cero = $query1234[0];
//$query = pg_fetch_row(pg_query(
//    "SELECT SUM(tarifa0) FROM gastos WHERE  tipo_comprobante='NOTA VENTA' and   fecha_emision " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
//));
//
//$total_gasto_nv_cero=$query[0];


$query12345 = pg_fetch_row(pg_query(
    "select SUM(base_imponible) from gastos fc 
inner join  detalle_gastos dfc on fc.id_gastos=dfc.id_gastos 
inner join  detalle_impuesto_producto_gasto dipc on dfc.id_detalle_gastos=dipc.id_detalle_gastos  
WHERE  tipo_comprobante='NOTA VENTA'   AND fecha_emision " . $query_fecha . "'$_GET[fin]' and tarifa='0' AND fc.estado='Activo'"
));
//$total_gasto_nv_cero += $query12345[0];
$total_gasto_nv_cero = $query12345[0];

$suma_total_cero_nv = $tarifa_compra_nv_cero + $total_gasto_nv_cero;
//======================================================================================
$pdf->Cell(25, 5, number_format($suma_total_cero_nv, 2, ',', '.'), 0, 0, 'R', 0); //BASE 2

$pdf->Ln(5);
// SUBTOTAL
$pdf->SetX(5);
//======================================================================================
$pdf->Cell(60, 5, utf8_decode('Subtotal'), 0, 0, 'L', 0); //COMPRAS 1

//======================================================================================
$pdf->Cell(25, 5, number_format($suma_total_cero_nv, 2, ',', '.'), 0, 0, 'R', 0); //BASE 2

$pdf->Ln(5);
$pdf->SetX(5);
//======================================================================================
//
//$pdf->Cell(60, 5, utf8_decode('Iva%'), 0, 0, 'L', 0);//COMPRAS 1
//$query = pg_fetch_row(pg_query(
//    "SELECT SUM(iva_compra) FROM factura_compra WHERE  tipo_comprobante='NOTA' and   fecha_emision " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
//));
//$base = $query[0];
//$iva_compras=$base;
//
//$query = pg_fetch_row(pg_query(
//    "SELECT SUM(iva_compra) FROM gastos WHERE  tipo_comprobante='NOTA VENTA' and   fecha_emision " . $query_fecha . "'$_GET[fin]' AND estado='Activo';"
//));
//$base = $query[0];
//$iva_gastos=$base;
//
////======================================================================================
//$total_iva_cg=$iva_compras+$iva_gastos;
//$pdf->Cell(25, 5, number_format($total_iva_cg, 2, ',', '.'), 0, 0, 'R', 0);//BASE 2
$pdf->Ln(1);
$pdf->SetX(5);
$pdf->Cell(60, 5, utf8_decode('TOTAL'), 0, 0, 'L', 0); //COMPRAS 1

//======================================================================================
//$total_iva_cg=$iva_compras+$iva_gastos;
$pdf->Cell(25, 5, number_format($suma_total_cero_nv, 2, ',', '.'), 0, 0, 'R', 0); //BASE 2

///////////////////////////////////////////////////////////////////////////////////////////////////////////
$pdf->Output();

function obtenerTarifasImpuestoFactura()
{
    global $query_fecha;
    $sql = "
    select
    di.cod_impuesto, 
    di.cod_tarifa, 
    di.tarifa, 
    sum(di.valor_impuesto)valor_impuesto, 
    sum(di.base_imponible)base_imponible
    from
    factura_venta fc
    inner join detalle_factura_venta dfc
    using(id_factura_venta)
    inner join detalle_impuesto_producto_venta di
    using(id_detalle_venta)
    where fc.fecha_actual " . $query_fecha . "'$_GET[fin]'
    group by di.cod_tarifa, di.cod_impuesto, di.tarifa
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (!empty($rows)) {
        return $rows;
    }
    return [];
}

function obtenerTarifasImpuestoNotaCredito()
{
    global $query_fecha;
    $sql = "
    select
    di.cod_impuesto, 
    di.cod_tarifa, 
    di.tarifa, 
    sum(di.valor_impuesto)valor_impuesto, 
    sum(di.base_imponible)base_imponible
    from
    devolucion_venta fc
    inner join detalle_devolucion_venta dfc
    using(id_devolucion_venta)
    inner join detalle_impuesto_producto_dev_venta di
    using(id_detalle_deventa)
    where fc.fecha_actual " . $query_fecha . "'$_GET[fin]'
    group by di.cod_tarifa, di.cod_impuesto, di.tarifa
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (!empty($rows)) {
        return $rows;
    }
    return [];
}
