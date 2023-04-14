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
        $this->Cell(105, 5, "CARTERA CxP", 0, 1, 'C', 0);
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
        $this->Line(0, 25, 210, 25);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(210, 5, utf8_decode("FACTURAS POR PAGAR PROVEEDORES"), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->Ln(2);
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
$pdf->SetTitle('Facturas por Pagar');
$pdf->AliasNbPages();
$pdf->SetFont('Amble-Regular', '', 9);

$query_fecha = "";
// RANGO DE FECHAS O FECHA ACTUAL
if ($pdf->rango) {
    $query_fecha = "BETWEEN '$_GET[inicio]' AND";
} else {
    $query_fecha = "=";
}
//GENERAL O POR PROVEEDOR
if (isset($_GET['id']) && $_GET['id'] != '')
    $sql = pg_query(
        "SELECT id_proveedor, tipo_documento, identificacion_pro, empresa_pro, representante_legal 
        FROM proveedores WHERE estado='Activo' and id_proveedor={$_GET['id']}"
    );
else
    $sql = pg_query(
        "SELECT id_proveedor, tipo_documento, identificacion_pro, empresa_pro, representante_legal 
        FROM proveedores WHERE estado='Activo'"
    );

if (pg_num_rows($sql)) {
    //EXTERNAS E INTERNAS
    if ($_GET['tipo'] == 'Externas') {
        $total = 0;
        while ($row = pg_fetch_row($sql)) {
            $sub = 0;
            $sql1 = pg_query(
                "SELECT DISTINCT ON (cp.comprobante) cp.comprobante, cp.fecha_actual, descripcion, cp.num_factura, total, (total::numeric - saldo::numeric) as abonos, saldo, pp.fecha_actual as fecha_pago
                    FROM tipo_comprobante tc, c_pagarexternas cp
                    LEFT JOIN pagos_pagar pp USING (num_factura)
                    WHERE cp.tipo_documento=tc.id_tipo_comprobante 
                    AND cp.estado='Activo'
                    AND cp.id_proveedor='$row[0]'
                    AND cp.fecha_actual $query_fecha '$_GET[fin]'
                    ORDER BY cp.comprobante asc, fecha_pago desc;"
            );
            if (pg_num_rows($sql1)) {
                $pdf->SetFont('helvetica', 'B', 9);
                $pdf->SetFillColor(216, 216, 231);
                $pdf->Cell(50, 8, utf8_decode(strtoupper($row[1]) . ": " . $row[2]), 1, 0, 'L', true);
                $pdf->Cell(80, 8, utf8_decode("PROVEEDOR: " . $row[3]), 1, 0, 'L', true);
                $pdf->Cell(80, 8, utf8_decode("REPRESENTANTE: " . $row[4]), 1, 1, 'L', true);
                $pdf->SetFillColor(175, 215, 240);
                $pdf->Cell(30, 6, utf8_decode('COMPROBANTE'), 1, 0, 'C', 1);
                $pdf->Cell(30, 6, utf8_decode('REGISTRO'), 1, 0, 'C', 1);
                $pdf->Cell(30, 6, utf8_decode('N° DOCUMENTO'), 1, 0, 'C', 1);
                $pdf->Cell(30, 6, utf8_decode('TOTAL'), 1, 0, 'C', 1);
                $pdf->Cell(30, 6, utf8_decode('ABONOS'), 1, 0, 'C', 1);
                $pdf->Cell(30, 6, utf8_decode('SALDO'), 1, 0, 'C', 1);
                $pdf->Cell(30, 6, utf8_decode('ULTIMO PAGO'), 1, 0, 'C', 1);
                $pdf->Ln(7);
                while ($row1 = pg_fetch_row($sql1)) {
                    $pdf->SetFont('helvetica', '', 9);
                    $pdf->Cell(30, 6, utf8_decode($row1[0]), 0, 0, 'C', 0);
                    $pdf->Cell(30, 6, utf8_decode($row1[1]), 0, 0, 'C', 0);
                    $pdf->Cell(30, 6, utf8_decode($row1[3]), 0, 0, 'C', 0);
                    $pdf->Cell(30, 6, number_format($row1[4], 2, ',', '.'), 0, 0, 'R', 0);
                    $pdf->Cell(30, 6, number_format($row1[5], 2, ',', '.'), 0, 0, 'R', 0);
                    $pdf->Cell(30, 6, number_format($row1[6], 2, ',', '.'), 0, 0, 'R', 0);
                    $pdf->Cell(30, 6, utf8_decode($row1[7]), 0, 1, 'C', 0);
                    $sub += $row1[6];
                }
                $pdf->SetFont('helvetica', 'B', 9);
                $pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
                $pdf->Cell(150, 6, utf8_decode("Saldo Proveedor"), 0, 0, 'R', 0);
                $pdf->Cell(30, 6, maxCaracter((number_format($sub, 2, ',', '.')), 20), 0, 1, 'R', 0);
                $pdf->Ln(3);
                $total += $sub;
            }
        }
    } else {
        $total = 0;
        while ($row = pg_fetch_row($sql)) {
            $sub = 0;
            /* $sql1 = pg_query(
                "SELECT DISTINCT ON (fc.num_serie) fc.num_serie, pc.fecha_credito, pc.tipo_documento, pc.adelanto, meses, monto_credito, (monto_credito::numeric - saldo::numeric) as abonos, saldo, pp.fecha_actual as fecha_pago
                    FROM pagos_compra pc, factura_compra fc
                    LEFT JOIN pagos_pagar pp on fc.num_serie=pp.num_factura
                    WHERE pc.estado='Activo'
                    AND pc.id_proveedor='$row[0]'
                    AND pc.fecha_credito $query_fecha '$_GET[fin]'
                    AND pc.id_factura_compra=fc.id_factura_compra
                    ORDER BY fc.num_serie asc, fecha_pago desc;"
            ); */
            $sql1 = pg_query(
                "(SELECT DISTINCT ON (fc.num_serie) 
                fc.num_serie, 
                pc.fecha_credito, 
                pc.tipo_documento,
                pc.adelanto, 
                meses, 
                monto_credito, 
                (monto_credito::numeric - saldo::numeric) as abonos, 
                saldo, 
                pp.fecha_actual as fecha_pago
                FROM pagos_compra pc, factura_compra fc
                LEFT JOIN pagos_pagar pp on fc.num_serie=pp.num_factura
                WHERE pc.estado='Activo'
                AND pc.id_proveedor='$row[0]'
                AND pc.fecha_credito $query_fecha '$_GET[fin]'
                AND pc.id_factura_compra=fc.id_factura_compra
                AND pc.comprao_gasto='C'
                ORDER BY fc.num_serie asc, fecha_pago desc)
                UNION ALL
                (SELECT DISTINCT ON (fc.num_serie) 
                fc.num_serie, 
                pc.fecha_credito, 
                pc.tipo_documento,
                pc.adelanto, 
                meses, 
                monto_credito, 
                (monto_credito::numeric - saldo::numeric) as abonos, 
                saldo, 
                pp.fecha_actual as fecha_pago
                FROM pagos_compra pc, gastos fc
                LEFT JOIN pagos_pagar pp on 
                fc.num_serie=pp.num_factura
                WHERE pc.estado='Activo'
                AND pc.id_proveedor='$row[0]'
                AND pc.fecha_credito $query_fecha '$_GET[fin]'
                AND pc.id_factura_compra=fc.id_gastos
                AND pc.comprao_gasto='G'
                ORDER BY fc.num_serie asc, fecha_pago desc)
                ORDER BY num_serie asc, fecha_pago desc
                "
            );
            if (pg_num_rows($sql1)) {
                $pdf->SetFont('helvetica', 'B', 9);
                $pdf->SetFillColor(216, 216, 231);
                $pdf->Cell(50, 8, utf8_decode(strtoupper($row[1]) . ": " . $row[2]), 1, 0, 'L', true);
                $pdf->Cell(80, 8, utf8_decode("PROVEEDOR: " . $row[3]), 1, 0, 'L', true);
                $pdf->Cell(80, 8, utf8_decode("REPRESENTANTE: " . $row[4]), 1, 1, 'L', true);
                $pdf->SetFillColor(175, 215, 240);
                $pdf->Cell(32, 6, utf8_decode('N° DOCUMENTO'), 1, 0, 'C', 1);
                $pdf->Cell(28, 6, utf8_decode('REGISTRO'), 1, 0, 'C', 1);
                $pdf->Cell(24, 6, utf8_decode('ADELANTO'), 1, 0, 'C', 1);
                $pdf->Cell(20, 6, utf8_decode('MESES'), 1, 0, 'C', 1);
                $pdf->Cell(26, 6, utf8_decode('TOTAL'), 1, 0, 'C', 1);
                $pdf->Cell(26, 6, utf8_decode('ABONOS'), 1, 0, 'C', 1);
                $pdf->Cell(26, 6, utf8_decode('SALDO'), 1, 0, 'C', 1);
                $pdf->Cell(28, 6, utf8_decode('ULTIMO PAGO'), 1, 0, 'C', 1);
                $pdf->Ln(7);
                while ($row1 = pg_fetch_row($sql1)) {
                    $pdf->SetFont('helvetica', '', 9);
                    $pdf->Cell(32, 6, utf8_decode($row1[0]), 0, 0, 'C', 0);
                    $pdf->Cell(28, 6, utf8_decode($row1[1]), 0, 0, 'C', 0);
                    $pdf->Cell(24, 6, number_format($row1[3], 2, ',', '.'), 0, 0, 'R', 0);
                    $pdf->Cell(20, 6, utf8_decode($row1[4]), 0, 0, 'C', 0);
                    $pdf->Cell(26, 6, number_format($row1[5], 2, ',', '.'), 0, 0, 'R', 0);
                    $pdf->Cell(26, 6, number_format($row1[6], 2, ',', '.'), 0, 0, 'R', 0);
                    $pdf->Cell(26, 6, number_format($row1[7], 2, ',', '.'), 0, 0, 'R', 0);
                    $pdf->Cell(28, 6, utf8_decode($row1[8]), 0, 1, 'C', 0);
                    $sub += $row1[7];
                }
                $pdf->SetFont('helvetica', 'B', 9);
                $pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
                $pdf->Cell(154, 6, utf8_decode("Saldo Proveedor"), 0, 0, 'R', 0);
                $pdf->Cell(30, 6, maxCaracter((number_format($sub, 2, ',', '.')), 20), 0, 1, 'R', 0);
                $pdf->Ln(3);
                $total += $sub;
            }
        }
    }
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
    $pdf->Cell(180, 6, utf8_decode("Total Saldos"), 0, 0, 'R', 0);
    $pdf->Cell(30, 6, maxCaracter((number_format($total, 2, ',', '.')), 20), 0, 1, 'R', 0);
    $pdf->Ln(3);
}
$pdf->Output();

function obtenerGastos()
{
    $sql = "
    select
    p.identificacion_pro,
    p.empresa_pro,
    p.representante_legal,
    g.comprobante,
    g.num_factura,
    g.fecha_emision,
    g.total,
    fpg.valor
    from gastos g
    inner join formas_pago_mixto_g fpg
    on g.id_gastos=fpg.id_gastos
    inner join proveedores p
    on p.id_proveedor=g.id_proveedor
    where fpg.forma_pago='CREDITO'
    and bien_servicio='otros'
    and g.estado='Activo'
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (!$rows) {
        return [];
    }
    return $rows;
}
