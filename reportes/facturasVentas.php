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
        $this->Line(0, 25, 210, 25);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(210, 5, utf8_decode("RESUMEN DE VENTAS"), 0, 1, 'C', 0);
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
        $this->Cell(24, 6, utf8_decode('Comprobante'), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode('Fecha'), 1, 0, 'C', 1);
        $this->Cell(30, 6, utf8_decode('Nro Factura'), 1, 0, 'C', 1);
        $this->Cell(16, 6, utf8_decode('Subtotal'), 1, 0, 'C', 1);
        $this->Cell(16, 6, utf8_decode('Dsco'), 1, 0, 'C', 1);
        $this->Cell(16, 6, utf8_decode('0%'), 1, 0, 'C', 1);
        $this->Cell(16, 6, utf8_decode('12%'), 1, 0, 'C', 1);
        $this->Cell(16, 6, utf8_decode('IVA'), 1, 0, 'C', 1);
        $this->Cell(16, 6, utf8_decode('Total'), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode('Forma Pago'), 1, 0, 'C', 1);
        $this->Cell(20, 6, utf8_decode('Costo V.'), 1, 1, 'C', 1);
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
$pdf->SetTitle('Ventas Generales');
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
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

$sql = "
SELECT num_factura, factura_venta.fecha_cancelacion, factura_venta.hora_actual, 
fecha_cancelacion, tipo_precio,UPPER(coalesce(fpm.forma_pago,factura_venta.forma_pago))forma_pago, tarifa0, tarifa12, 
iva_venta, descuento_venta, total_venta, identificacion, nombres_cli, 
nombre_punto, factura_venta.id_factura_venta, factura_venta.estado, fpm.valor
FROM factura_venta
LEFT join formas_pago_mixto fpm 
on fpm.id_factura_venta=factura_venta.id_factura_venta
and tipo_documento='FACTURA',
clientes,punto_venta,usuario 
where factura_venta.id_cliente=clientes.id_cliente 
and id_punto_venta=factura_venta.id_empresa
AND factura_venta.id_empresa='$_GET[id]' 
and usuario.id_usuario=factura_venta.id_usuario 
and factura_venta.id_cliente=clientes.id_cliente 
AND factura_venta.fecha_cancelacion $query_fecha '$_GET[fin]' 
order by factura_venta.id_factura_venta asc";


$sqlnv = "
SELECT comprobante, fv.fecha_actual, fv.hora_actual, fv.fecha_actual, tipo_precio, 
UPPER(coalesce(fpm.forma_pago,fv.forma_pago))forma_pago, tarifa0, tarifa12, iva_venta, descuento_venta, total_venta, 
identificacion, nombres_cli, nombre_punto, id_facturas_novalidas, fv.estado, fpm.valor
FROM facturas_novalidas fv
LEFT join formas_pago_mixto fpm 
on fpm.id_factura_venta=fv.id_facturas_novalidas
and tipo_documento='NOTA', 
clientes,punto_venta,usuario 
where fv.id_cliente=clientes.id_cliente 
and id_punto_venta=fv.id_empresa
AND fv.id_empresa='$_GET[id]' 
and usuario.id_usuario=fv.id_usuario 
and fv.id_cliente=clientes.id_cliente 
AND fv.fecha_actual 
$query_fecha '$_GET[fin]' 
order by fv.id_facturas_novalidas asc;
";

//echo $sqlnv;

$consulta1 = pg_query($sql);
$consulta2 = pg_query($sqlnv);
$iddoc = 0;
if (pg_num_rows($consulta1)) {

    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(210, 8, utf8_decode(" Lista Facturas"), 1, 1, 'L', 0);

    $pdf->SetFont('helvetica', 'B', 9);
    while ($row1 = pg_fetch_row($consulta1)) {
        //var_dump(obtenerCostoDeVenta($row1[14]));
        if ($row1[15] == "Activo") {
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetX(1);
            $pdf->Cell(23, 6, utf8_decode($row1[14]), 0, 0, 'C', 0);
            $pdf->Cell(20, 6, utf8_decode($row1[1]), 0, 0, 'C', 0);
            $pdf->Cell(30, 6, utf8_decode("FV: " . ($row1[0])), 0, 0, 'L', 0);
            if ($row1[14] != $iddoc) {
                $sub = $sub + ($row1[6] + $row1[7]);
            }
            $pdf->Cell(16, 6, utf8_decode(round($row1[6] + $row1[7], 2, PHP_ROUND_HALF_EVEN)), 0, 0, 'R', 0);
            if ($row1[14] != $iddoc) {
                $desc = $desc + $row1[9];
            }
            $pdf->Cell(16, 6, utf8_decode(round($row1[9], 2, PHP_ROUND_HALF_EVEN)), 0, 0, 'R', 0);
            $pdf->Cell(16, 6, utf8_decode(round($row1[6], 2, PHP_ROUND_HALF_EVEN)), 0, 0, 'R', 0);
            $pdf->Cell(16, 6, utf8_decode(round($row1[7], 2, PHP_ROUND_HALF_EVEN)), 0, 0, 'R', 0);
            if ($row1[14] != $iddoc) {
                $ivaT = $ivaT + $row1[8];
            }
            $pdf->Cell(16, 6, utf8_decode(round($row1[8], 2, PHP_ROUND_HALF_EVEN)), 0, 0, 'R', 0);

            $total = $total + (empty($row1[16]) ? $row1[10] : $row1[16]); //$row1[10];

            if ($row1[14] != $iddoc) {
                $t0 = $t0 + $row1[6];
                $t12 = $t12 + $row1[7];
            }

            $pdf->Cell(16, 6, (empty($row1[16]) ? utf8_decode(round($row1[10], 2, PHP_ROUND_HALF_EVEN)) : utf8_decode(round($row1[16], 2, PHP_ROUND_HALF_EVEN))), 0, 0, 'R', 0);
            $pdf->Cell(20, 6, $row1[5], 0, 0, 'L', 0);
            $pdf->Cell(20, 6, number_format(obtenerCostoVentaFactura($row1[14]), 2, ",", "."), 0, 1, 'R', 0);
        } else {
            if ($row1[15] == "Pasivo") {
                $pdf->SetTextColor(208, 17, 52);
                $pdf->SetFont('helvetica', '', 9);
                $pdf->SetX(1);
                $pdf->Cell(23, 6, utf8_decode($row1[14]), 0, 0, 'C', 0);
                $pdf->Cell(20, 6, utf8_decode($row1[1]), 0, 0, 'C', 0);
                $pdf->Cell(30, 6, utf8_decode("FV: " . substr($row1[0], 8)), 0, 0, 'L', 0);
                $pdf->Cell(16, 6, utf8_decode(round($row1[6] + $row1[7], 2, PHP_ROUND_HALF_EVEN)), 0, 0, 'R', 0);
                $pdf->Cell(16, 6, utf8_decode(round($row1[9], 2, PHP_ROUND_HALF_EVEN)), 0, 0, 'R', 0);
                $pdf->Cell(16, 6, utf8_decode(round($row1[6], 2, PHP_ROUND_HALF_EVEN)), 0, 0, 'R', 0);
                $pdf->Cell(16, 6, utf8_decode(round($row1[7], 2, PHP_ROUND_HALF_EVEN)), 0, 0, 'R', 0);
                $pdf->Cell(16, 6, utf8_decode(round($row1[8], 2, PHP_ROUND_HALF_EVEN)), 0, 0, 'R', 0);
                $pdf->Cell(16, 6, utf8_decode(round($row1[10], 2, PHP_ROUND_HALF_EVEN)), 0, 0, 'R', 0);
                $pdf->Cell(20, 6, $row1[5], 0, 0, 'L', 0);
                $pdf->Cell(20, 6, number_format(obtenerCostoVentaFactura($row1[14]), 2, ",", "."), 0, 1, 'R', 0);
            }
        }
        $iddoc = $row1[14];
    }


    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(207, 0, utf8_decode(""), 1, 1, 'R', 0);
    $pdf->SetX(1);
    $pdf->Cell(73, 6, utf8_decode("Totales Facturas"), 0, 0, 'R', 0);
    $pdf->Cell(16, 6, maxCaracter((number_format($sub, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(16, 6, maxCaracter((number_format($desc, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(16, 6, maxCaracter((number_format($t0, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(16, 6, maxCaracter((number_format($t12, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(16, 6, maxCaracter((number_format($ivaT, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(16, 6, maxCaracter((number_format($total, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(207, 0, utf8_decode(""), 1, 1, 'R', 0);
    $pdf->Ln(15);
}

if (pg_num_rows($consulta2)) {
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(210, 8, utf8_decode(" Lista Notas de Venta"), 1, 1, 'L', 0);

    $subnv = 0;
    $descnv = 0;
    $t0nv = 0;
    $t12nv = 0;
    $ivaTnv = 0;
    $totalnv = 0;

    $iddoc = 0;
    while ($row1 = pg_fetch_row($consulta2)) {
        //var_dump(obtenerCostoDeVenta($row1[14]));
        if ($row1[15] == "Activo") {
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetX(1);
            $pdf->Cell(23, 6, utf8_decode($row1[14]), 0, 0, 'C', 0);
            $pdf->Cell(20, 6, utf8_decode($row1[1]), 0, 0, 'C', 0);
            $pdf->Cell(30, 6, utf8_decode("NV: " . ($row1[0])), 0, 0, 'L', 0);
            if ($row1[14] != $iddoc) {
                $subnv = $subnv + ($row1[6] + $row1[7]);
            }
            $pdf->Cell(16, 6, utf8_decode(round($row1[6] + $row1[7], 2, PHP_ROUND_HALF_EVEN)), 0, 0, 'R', 0);
            if ($row1[14] != $iddoc) {
                $descnv = $descnv + $row1[9];
            }
            $pdf->Cell(16, 6, utf8_decode(round($row1[9], 2, PHP_ROUND_HALF_EVEN)), 0, 0, 'R', 0);
            $pdf->Cell(16, 6, utf8_decode(round($row1[6], 2, PHP_ROUND_HALF_EVEN)), 0, 0, 'R', 0);
            $pdf->Cell(16, 6, utf8_decode(round($row1[7], 2, PHP_ROUND_HALF_EVEN)), 0, 0, 'R', 0);
            if ($row1[14] != $iddoc) {
                $ivaTnv = $ivaTnv + $row1[8];
            }
            $pdf->Cell(16, 6, utf8_decode(round($row1[8], 2, PHP_ROUND_HALF_EVEN)), 0, 0, 'R', 0);
            $totalnv = $totalnv + (empty($row1[16]) ? $row1[10] : $row1[16]); //$row1[10];
            if ($row1[14] != $iddoc) {
                $t0nv = $t0nv + $row1[6];
                $t12nv = $t12nv + $row1[7];
            }
            $pdf->Cell(16, 6, (empty($row1[16]) ? utf8_decode(round($row1[10], 2, PHP_ROUND_HALF_EVEN)) : utf8_decode(round($row1[16], 2, PHP_ROUND_HALF_EVEN))), 0, 0, 'R', 0);
            $pdf->Cell(20, 6, $row1[5], 0, 0, 'L', 0);
            $pdf->Cell(20, 6, number_format(obtenerCostoVentaNota($row1[14]), 2, ",", "."), 0, 1, 'R', 0);
        } else {
            if ($row1[15] == "Pasivo") {
                $pdf->SetTextColor(208, 17, 52);
                $pdf->SetFont('helvetica', '', 9);
                $pdf->SetX(1);
                $pdf->Cell(23, 6, utf8_decode($row1[14]), 0, 0, 'C', 0);
                $pdf->Cell(20, 6, utf8_decode($row1[1]), 0, 0, 'C', 0);
                $pdf->Cell(30, 6, utf8_decode("NV:" . substr($row1[0], 8)), 0, 0, 'L', 0);
                $pdf->Cell(16, 6, utf8_decode(round($row1[6] + $row1[7], 2, PHP_ROUND_HALF_EVEN)), 0, 0, 'R', 0);
                $pdf->Cell(16, 6, utf8_decode(round($row1[9], 2, PHP_ROUND_HALF_EVEN)), 0, 0, 'R', 0);
                $pdf->Cell(16, 6, utf8_decode(round($row1[6], 2, PHP_ROUND_HALF_EVEN)), 0, 0, 'R', 0);
                $pdf->Cell(16, 6, utf8_decode(round($row1[7], 2, PHP_ROUND_HALF_EVEN)), 0, 0, 'R', 0);
                $pdf->Cell(16, 6, utf8_decode(round($row1[8], 2, PHP_ROUND_HALF_EVEN)), 0, 0, 'R', 0);
                $pdf->Cell(16, 6, (empty($row1[16]) ? utf8_decode(round($row1[10], 2, PHP_ROUND_HALF_EVEN)) : utf8_decode(round($row1[16], 2, PHP_ROUND_HALF_EVEN))), 0, 0, 'R', 0);
                $pdf->Cell(20, 6, $row1[5], 0, 0, 'L', 0);
                $pdf->Cell(20, 6, number_format(obtenerCostoVentaNota($row1[14]), 2, ",", "."), 0, 1, 'R', 0);
            }
        }
        $iddoc = $row1[14];
    }

    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(207, 0, utf8_decode(""), 1, 1, 'R', 0);
    $pdf->SetX(1);
    $pdf->Cell(73, 6, utf8_decode("Totales Notas de Venta"), 0, 0, 'R', 0);
    $pdf->Cell(16, 6, maxCaracter((number_format($subnv, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(16, 6, maxCaracter((number_format($descnv, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(16, 6, maxCaracter((number_format($t0nv, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(16, 6, maxCaracter((number_format($t12nv, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(16, 6, maxCaracter((number_format($ivaTnv, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(16, 6, maxCaracter((number_format($totalnv, 2, ',', '.')), 20), 0, 1, 'R', 0);
    $pdf->Cell(207, 0, utf8_decode(""), 1, 1, 'R', 0);
    $pdf->Ln(15);

    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->SetTextColor(0, 0, 0);

    $pdf->Cell(207, 0, utf8_decode(""), 1, 1, 'R', 0);
    $pdf->SetX(1);
    $pdf->Cell(73, 6, utf8_decode("Totales Facturas"), 0, 0, 'R', 0);
    $pdf->Cell(80, 6, "", 0, 0, 'R', 0);
    $pdf->Cell(16, 6, maxCaracter((number_format($total, 2, ',', '.')), 20), 0, 1, 'R', 0);

    $pdf->SetTextColor(0, 0, 0);
    //$pdf->SetFont('helvetica', 'B', 9);
    $pdf->SetX(1);
    $pdf->Cell(73, 6, utf8_decode("Totales Notas de Venta"), 0, 0, 'R', 0);
    $pdf->Cell(80, 6, "", 0, 0, 'R', 0);
    $pdf->Cell(16, 6, maxCaracter((number_format($totalnv, 2, ',', '.')), 20), 0, 1, 'R', 0);
    $pdf->Cell(207, 0, utf8_decode(""), 1, 1, 'R', 0);

    $pdf->SetTextColor(0, 0, 0);
    //$pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(207, 0, utf8_decode(""), 1, 1, 'R', 0);
    $pdf->SetX(1);
    $pdf->Cell(73, 6, utf8_decode("Total"), 0, 0, 'R', 0);
    $pdf->Cell(80, 6, "", 0, 0, 'R', 0);
    $pdf->Cell(16, 6, maxCaracter((number_format($totalnv + $total, 2, ',', '.')), 20), 0, 1, 'R', 0);
    $pdf->Cell(207, 0, utf8_decode(""), 1, 1, 'R', 0);
}
$pdf->Output();


function obtenerCostoDeVenta($idfacturaventa)
{
    $sql = "
    select sum(costo_prom_unitario*salida) from kardex_valorizado
    where compra_venta='V'
    and comprobante::integer=$idfacturaventa
    ";
    $res = pg_query($sql);
    $row = pg_fetch_row($res);
    if (empty($row)) {
        return 0;
    }
    return $row[0];
}

function obtenerCostoVentaFactura($idfacturaventa)
{
    $sql = "
    select concepto, costo_prom_unitario,salida from kardex_valorizado
    where compra_venta='V'
    and comprobante::integer=$idfacturaventa
    ";

    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return 0;
    }
    $suma = 0;
    foreach ($rows as $r) {
        $fac = strpos($r["concepto"], 'F.V');
        if (is_numeric($fac)) {
            $suma += ($r["costo_prom_unitario"] * $r["salida"]);
        }
    }
    return $suma;
}

function obtenerCostoVentaNota($idfacturaventa)
{
    $sql = "
    select concepto, costo_prom_unitario,salida from kardex_valorizado
    where compra_venta='V'
    and comprobante::integer=$idfacturaventa
    ";

    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return 0;
    }
    $suma = 0;
    foreach ($rows as $r) {
        $fac = strpos($r["concepto"], 'N.V');
        if (is_numeric($fac)) {
            $suma += ($r["costo_prom_unitario"] * $r["salida"]);
        }
    }
    return $suma;
}
