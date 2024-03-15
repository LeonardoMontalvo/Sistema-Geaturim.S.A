<?php

require('../fpdf/fpdf.php');
include '../procesos/base.php';
include '../procesos/funciones.php';
conectarse();
date_default_timezone_set('America/Guayaquil');
session_start();
//error_reporting(0);
class PDF extends FPDF {

    var $widths;
    var $aligns;

    function SetWidths($w) {
        $this->widths = $w;
    }

    function SetAlings($a) {
        $this->aligns = $a;
    }

    function Header() {
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
        $this->Cell(105, 5, "VENTAS", 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(210, 8, utf8_decode($_SESSION['nombre_empresa']), 0, 1, 'C', 0);
        $this->Image('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 10, 7, 15, 15);
        $this->Image('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 180, 7, 15, 15);
        // $this->Cell(190, 5, "PROPIETARIO: " . utf8_decode($_SESSION['propietario']), 0, 1, 'C', 0);
        // $this->Cell(80, 5, "TEL.: " . utf8_decode($_SESSION['telefono']), 0, 0, 'R', 0);
        // $this->Cell(80, 5, "CEL.: " . utf8_decode($_SESSION['celular']), 0, 1, 'C', 0);
        // $this->Cell(190, 5, "DIR.: " . utf8_decode($_SESSION['direccion']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, "SLOGAN.: " . utf8_decode($_SESSION['slogan']), 0, 1, 'C', 0);
        // $this->Cell(190, 5, utf8_decode($_SESSION['pais_ciudad']), 0, 1, 'C', 0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);
        $this->Line(0, 30, 210, 30);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(210, 5, utf8_decode('VENTAS POR USUARIO'), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->usuario = pg_fetch_row(pg_query("SELECT ci_usuario, nombre_usuario FROM usuario WHERE id_usuario={$_GET['id']}"));
        $this->Cell(105, 6, maxCaracter(utf8_decode('RUC/CI:' . $this->usuario[0]), 35), 0, 0, 'C', 0);
        $this->Cell(105, 6, maxCaracter(utf8_decode('NOMBRES:' . $this->usuario[1]), 50), 0, 1, 'C', 0);
        $this->Ln(2);
        $this->SetX(1);
        $this->SetFillColor(175, 215, 240);
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(24, 6, utf8_decode('NÚMERO'), 1, 0, 'C', 1);
        $this->Cell(10, 6, utf8_decode('TIPO'), 1, 0, 'C', 1);
        $this->Cell(25, 6, utf8_decode('FECHA'), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode('PAGO'), 1, 0, 'C', 1);
        $this->Cell(27, 6, utf8_decode('0%'), 1, 0, 'C', 1);
        $this->Cell(27, 6, utf8_decode('12%'), 1, 0, 'C', 1);
        $this->Cell(27, 6, utf8_decode('DESCUENTO'), 1, 0, 'C', 1);
        $this->Cell(27, 6, utf8_decode('IVA'), 1, 0, 'C', 1);
        $this->Cell(27, 6, utf8_decode('TOTAL'), 1, 1, 'C', 1);
        $this->Ln(2);
        $this->SetFillColor(255, 255, 225);
        $this->SetLineWidth(0.2);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

}

$pdf = new PDF('P', 'mm', 'a4');
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->SetTitle('Ventas ' . $pdf->usuario[1]);
$pdf->AliasNbPages();

$total = 0;
$sub = 0;
$desc = 0;
$ivaT = 0;
$t0 = 0;
$t12 = 0;
$query_fecha = "";
// RANGO DE FECHAS O FECHA ACTUAL
if ($pdf->rango) {
    $query_fecha = "BETWEEN '$_GET[inicio]' AND";
} else {
    $query_fecha = "=";
}
//echo '//'.$_GET[id];

$idid=$_GET['id'];

  if ($idid == '0') {
//    echo 'entro';
$consulta2 = pg_query(
        "
select x.cod_productos, x.articulo, sum(x.cantidad) as cantidad, sum(x.total) as total, x.iva, x.precio_venta, x.incluye_iva, x.precio_compra, x.cod_barras,x.iva_minorista,x.num_factura
 from( 
 ( select dfv.cod_productos, p.articulo, sum(cantidad::numeric) as cantidad,
  coalesce(round(sum(dfv.total_venta::numeric -(dfv.total_venta::numeric *(round((fv.descuento_venta * 100) / nullif((fv.tarifa0::numeric + fv.tarifa12::numeric), 0),0) 
  / 100))),4),0) as total, p.iva, dfv.precio_venta, p.incluye_iva, p.precio_compra, p.cod_barras,p.iva_minorista,fv.num_factura
   
  from factura_venta fv, detalle_factura_venta dfv, productos p
   where fv.id_factura_venta = dfv.id_factura_venta and p.cod_productos = dfv.cod_productos and fv.fecha_actual between '$_GET[inicio]' and '$_GET[fin]' and fv.id_empresa=1 and fv.estado = 'Activo' 
     group by dfv.cod_productos,p.articulo,p.iva,dfv.precio_venta,p.incluye_iva,p.precio_compra,p.cod_barras,p.iva_minorista,fv.num_factura order by cantidad desc ) 
  union all ( select dfv.cod_productos, p.articulo, sum(cantidad::numeric) as cantidad, 
    coalesce(round(sum(dfv.total_venta::numeric -(dfv.total_venta::numeric *(round((fv.descuento_venta * 100) / nullif((fv.tarifa0::numeric + fv.tarifa12::numeric), 0),0)
     / 100))),4),0) as total, p.iva, dfv.precio_venta, p.incluye_iva, p.precio_compra, p.cod_barras,p.iva_minorista ,fv.comprobante

  from facturas_novalidas fv, detalle_facturas_novalidas dfv, productos p
      where fv.id_facturas_novalidas = dfv.id_facturas_novalidas and p.cod_productos = dfv.cod_productos and fv.fecha_actual between '$_GET[inicio]' and '$_GET[fin]'
      and fv.id_empresa=1 and fv.estado = 'Activo'
       group by dfv.cod_productos,p.articulo,p.iva,dfv.precio_venta,p.incluye_iva,p.precio_compra,p.cod_barras ,p.iva_minorista,fv.comprobante
      order by cantidad desc ) ) as x group by x.cod_productos,x.articulo,x.iva,x.precio_venta,x.incluye_iva,x.precio_compra,x.cod_barras,x.iva_minorista,x.num_factura order by articulo asc 
");

      
  }else {
   $consulta2 = pg_query(
        "
select x.cod_productos, x.articulo, sum(x.cantidad) as cantidad, sum(x.total) as total, x.iva, x.precio_venta, x.incluye_iva, x.precio_compra, x.cod_barras,x.iva_minorista,x.num_factura
 from( 
 ( select dfv.cod_productos, p.articulo, sum(cantidad::numeric) as cantidad,
  coalesce(round(sum(dfv.total_venta::numeric -(dfv.total_venta::numeric *(round((fv.descuento_venta * 100) / nullif((fv.tarifa0::numeric + fv.tarifa12::numeric), 0),0) 
  / 100))),4),0) as total, p.iva, dfv.precio_venta, p.incluye_iva, p.precio_compra, p.cod_barras,p.iva_minorista,fv.num_factura
   
  from factura_venta fv, detalle_factura_venta dfv, productos p
   where fv.id_factura_venta = dfv.id_factura_venta and p.cod_productos = dfv.cod_productos and fv.fecha_actual between '$_GET[inicio]' and '$_GET[fin]' and fv.id_empresa=1 and fv.estado = 'Activo' and fv.id_usuario='$_GET[id]'
     group by dfv.cod_productos,p.articulo,p.iva,dfv.precio_venta,p.incluye_iva,p.precio_compra,p.cod_barras,p.iva_minorista,fv.num_factura order by cantidad desc ) 
  union all ( select dfv.cod_productos, p.articulo, sum(cantidad::numeric) as cantidad, 
    coalesce(round(sum(dfv.total_venta::numeric -(dfv.total_venta::numeric *(round((fv.descuento_venta * 100) / nullif((fv.tarifa0::numeric + fv.tarifa12::numeric), 0),0)
     / 100))),4),0) as total, p.iva, dfv.precio_venta, p.incluye_iva, p.precio_compra, p.cod_barras,p.iva_minorista ,fv.comprobante

  from facturas_novalidas fv, detalle_facturas_novalidas dfv, productos p
      where fv.id_facturas_novalidas = dfv.id_facturas_novalidas and p.cod_productos = dfv.cod_productos and fv.fecha_actual between '$_GET[inicio]' and '$_GET[fin]'
      and fv.id_empresa=1 and fv.estado = 'Activo' and fv.id_usuario='$_GET[id]'
       group by dfv.cod_productos,p.articulo,p.iva,dfv.precio_venta,p.incluye_iva,p.precio_compra,p.cod_barras ,p.iva_minorista,fv.comprobante
      order by cantidad desc ) ) as x group by x.cod_productos,x.articulo,x.iva,x.precio_venta,x.incluye_iva,x.precio_compra,x.cod_barras,x.iva_minorista,x.num_factura order by articulo asc 
");
      
  }






$precio_venta_diferente = 0;
$num_factura = 0;

while ($row2 = pg_fetch_row($consulta2)) {
    $precio_venta_fv = $row2[5];
    $iva_minorista_p = $row2[9];
//echo '//'.$iva_minorista_p;

    if ($precio_venta_fv != $iva_minorista_p) {
        $precio_venta_diferente = 1;

        $num_factura = $row2[10]; //0000105
    }
}
$idid1=$_GET['id'];
  if ($idid1 == '0') {
$consulta1 = pg_query(
        "(
        SELECT num_factura AS comprobante, 'FV' AS tipo_doc, fecha_actual, forma_pago, tarifa0, tarifa12, iva_venta, total_venta, estado ,descuento_venta
        FROM factura_venta 
        WHERE  fecha_actual $query_fecha '$_GET[fin]' 
        ORDER BY id_factura_venta asc
    ) 
    UNION ALL
    (
        SELECT (concat('0', comprobante)), 'NV' AS tipo_doc, fecha_actual, forma_pago, tarifa0, tarifa12, iva_venta, total_venta, estado ,descuento_venta
        FROM facturas_novalidas 
        WHERE  fecha_actual $query_fecha '$_GET[fin]' 
        ORDER BY id_facturas_novalidas asc
    )"
);
  }else{
     $consulta1 = pg_query(
        "(
        SELECT num_factura AS comprobante, 'FV' AS tipo_doc, fecha_actual, forma_pago, tarifa0, tarifa12, iva_venta, total_venta, estado ,descuento_venta
        FROM factura_venta 
            WHERE id_usuario='$_GET[id]' AND fecha_actual $query_fecha '$_GET[fin]' 
        ORDER BY id_factura_venta asc
    ) 
    UNION ALL
    (
        SELECT (concat('0', comprobante)), 'NV' AS tipo_doc, fecha_actual, forma_pago, tarifa0, tarifa12, iva_venta, total_venta, estado ,descuento_venta
        FROM facturas_novalidas 
           WHERE id_usuario='$_GET[id]' AND fecha_actual $query_fecha '$_GET[fin]' 
        ORDER BY id_facturas_novalidas asc
    )"
); 
  }
while ($row1 = pg_fetch_row($consulta1)) {
//      echo '//'.$row1[8];
    if ($row1[8] == "Activo") {
      
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetX(1);
        if ($num_factura != 0) {
            if ($row1[0] == $num_factura) {

                $pdf->SetTextColor(208, 17, 52);
                $pdf->Cell(24, 6, utf8_decode($row1[0]), 0, 0, 'C', 0);
            } else {
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Cell(24, 6, utf8_decode($row1[0]), 0, 0, 'C', 0);
            }
        } else {
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(24, 6, utf8_decode($row1[0]), 0, 0, 'C', 0);
        }
         $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(10, 6, utf8_decode($row1[1]), 0, 0, 'C', 0);
        $pdf->Cell(25, 6, utf8_decode(($row1[2])), 0, 0, 'C', 0);
        $pdf->Cell(20, 6, utf8_decode($row1[3]), 0, 0, 'C', 0);
        $pdf->Cell(27, 6, number_format($row1[4], 2, ',', '.'), 0, 0, 'C', 0);
        $pdf->Cell(27, 6, number_format($row1[5], 2, ',', '.'), 0, 0, 'C', 0);
        if ($row1[9] == "0") {
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(27, 6, number_format($row1[9], 2, ',', '.'), 0, 0, 'C', 0);
        } else {
            $pdf->SetTextColor(208, 17, 52);
            $pdf->Cell(27, 6, number_format($row1[9], 2, ',', '.'), 0, 0, 'C', 0);
        }
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(27, 6, number_format($row1[6], 2, ',', '.'), 0, 0, 'C', 0);


        $desc = $desc + $row1[9];
        $pdf->Cell(27, 6, number_format($row1[7], 2, ',', '.'), 0, 0, 'C', 0);
        $t0 += $row1[4];
        $t12 += $row1[5];
        $ivaT += $row1[6];
        $total += $row1[7];
        $pdf->Ln(6);
    } else {
        if ($row1[8] == "Pasivo") {
            $pdf->SetTextColor(208, 17, 52);
//            $pdf->SetX(1);
            $pdf->Cell(24, 6, utf8_decode($row1[0]), 0, 0, 'C', 0);
            $pdf->Cell(12, 6, utf8_decode($row1[1]), 0, 0, 'C', 0);
            $pdf->Cell(25, 6, utf8_decode(($row1[2])), 0, 0, 'C', 0);
            $pdf->Cell(20, 6, utf8_decode($row1[3]), 0, 0, 'C', 0);
            $pdf->Cell(27, 6, number_format($row1[4], 2, ',', '.'), 0, 0, 'C', 0);
            $pdf->Cell(27, 6, number_format($row1[5], 2, ',', '.'), 0, 0, 'C', 0);
            $pdf->Cell(27, 6, number_format($row1[9], 2, ',', '.'), 0, 0, 'C', 0);
            $pdf->Cell(27, 6, number_format($row1[6], 2, ',', '.'), 0, 0, 'C', 0);
            $pdf->Cell(27, 6, number_format($row1[7], 2, ',', '.'), 0, 0, 'C', 0);
            $desc = $desc + $row1[9];
            $pdf->Ln(6);
        }
    }
}

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetX(1);
$pdf->Cell(207, 0, utf8_decode(""), 1, 1, 'R', 0);
$pdf->Cell(80, 6, utf8_decode("Totales"), 0, 0, 'R', 0);
$pdf->Cell(27, 6, maxCaracter((number_format($t0, 2, ',', '.')), 20), 0, 0, 'C', 0);
$pdf->Cell(27, 6, maxCaracter((number_format($t12, 2, ',', '.')), 20), 0, 0, 'C', 0);
$pdf->Cell(27, 6, maxCaracter((number_format($desc, 2, ',', '.')), 20), 0, 0, 'C', 0);
$pdf->Cell(27, 6, maxCaracter((number_format($ivaT, 2, ',', '.')), 20), 0, 0, 'C', 0);
$pdf->Cell(27, 6, maxCaracter((number_format($total, 2, ',', '.')), 20), 0, 0, 'C', 0);
$pdf->Ln(8);
$pdf->Output();
