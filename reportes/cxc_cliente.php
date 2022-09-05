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
        $this->Cell(105, 5, "CARTERA CxC", 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(210, 8, $_SESSION['nombre_empresa'], 0, 1, 'C', 0);
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
        $this->row = pg_fetch_row(pg_query("SELECT * from clientes WHERE id_cliente=$_GET[id] order by id_cliente asc;"));
        $this->Cell(210, 5, utf8_decode("FACTURAS POR COBRAR A: " . maxCaracter($this->row[2], 30)), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
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
$pdf->SetTitle('Clientes por Cobrar');
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Amble-Regular', '', 9);

$total = 0;
$abono = 0;
$saldo = 0;
$query_fecha = "";
// RANGO DE FECHAS O FECHA ACTUAL
if ($pdf->rango) {
    $query_fecha = "BETWEEN '$_GET[inicio]' AND";
} else {
    $query_fecha = "=";
}
$query_punto="";
if ($_GET['id_empre'] != '0') {
    $query_punto = "AND fv.id_empresa='$_GET[id_empre]'";
    
}
$query_punto_cp="";

if ($_GET['id_empre'] != '0') {
    $query_punto_cp = "AND c.id_empresa='$_GET[id_empre]'";
    
}
$query_punto_nv="";

if ($_GET['id_empre'] != '0') {
    $query_punto_nv = "AND nv.id_empresa='$_GET[id_empre]'";
    
}
$id_usuario="";
if ($_GET['id_usuario'] != '0') {
    $id_usuario = "and c.id_usuario='$_GET[id_usuario]'";
}
$id_usuario_fv="";
if ($_GET['id_usuario'] != '0') {
    $id_usuario_fv = "and fv.id_usuario='$_GET[id_usuario]'";
}
$id_usuario_nv="";
if ($_GET['id_usuario'] != '0') {
    $id_usuario_nv = "and nv.id_usuario='$_GET[id_usuario]'";
}

if ($_GET['tipo'] == 'Externas') {
    $tipo;
    if ($_GET['tipo_documento'] == 'factura') {
        $tipo = 1;
    } elseif ($_GET['tipo_documento'] == 'nota') {
        $tipo = 2;
    }
    $sql = pg_query(
        "SELECT comprobante, descripcion, num_factura, total, total::numeric-saldo::numeric, saldo, fecha_actual  
                FROM c_cobrarexternas c left join tipo_comprobante t 
                on c.tipo_documento=t.id_tipo_comprobante 
                where id_cliente='$_GET[id]' and c.estado='Activo' $query_punto_cp $id_usuario
                and  fecha_actual $query_fecha '$_GET[fin]' and c.tipo_documento=$tipo;"
    );
    if (pg_num_rows($sql)) {
        $pdf->SetFillColor(187, 179, 180);
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(75, 6, maxCaracter(utf8_decode('RUC/CI:' . $pdf->row[2]), 35), 0, 0, 'C', 1);
        $pdf->Cell(135, 6, maxCaracter(utf8_decode('NOMBRES:' . $pdf->row[3]), 50), 0, 1, 'C', 1);
        $sub = 0;
        $suba = 0;
        $subs = 0;
        $pdf->SetX(2);
        $pdf->SetFillColor(175, 215, 240);
        $pdf->Cell(25, 6, utf8_decode('Comprobante'), 1, 0, 'C', 1);
        $pdf->Cell(30, 6, utf8_decode('Tipo Documento'), 1, 0, 'C', 1);
        $pdf->Cell(45, 6, utf8_decode('Nro Factura'), 1, 0, 'C', 1);
        $pdf->Cell(25, 6, utf8_decode('Total'), 1, 0, 'C', 1);
        $pdf->Cell(25, 6, utf8_decode('Valor Pago'), 1, 0, 'C', 1);
        $pdf->Cell(25, 6, utf8_decode('Saldo'), 1, 0, 'C', 1);
        $pdf->Cell(30, 6, utf8_decode('Fecha Pago'), 1, 1, 'C', 1);
        while ($row = pg_fetch_row($sql)) {
            $pdf->SetX(2);
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(25, 6, utf8_decode($row[0]), 0, 0, 'C', 0);
            $pdf->Cell(30, 6, utf8_decode($row[1]), 0, 0, 'C', 0);
            $pdf->Cell(45, 6, utf8_decode($row[2]), 0, 0, 'C', 0);
            $pdf->Cell(25, 6, number_format($row[3], 2, '.', ','), 0, 0, 'R', 0);
            $pdf->Cell(25, 6, number_format($row[4], 2, ',', '.'), 0, 0, 'R', 0);
            $pdf->Cell(25, 6, number_format($row[5], 2, ',', '.'), 0, 0, 'R', 0);
            $pdf->Cell(30, 6, utf8_decode($row[6]), 0, 1, 'C', 0);
            $sub += $row[3];
            $suba += $row[4];
            $subs += $row[5];
        }
        $pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->SetX(2);
        $pdf->Cell(100, 6, utf8_decode("Totales:"), 0, 0, 'R', 0);
        $pdf->Cell(25, 6, maxCaracter((number_format($sub, 2, ',', '.')), 20), 0, 0, 'R', 0);
        $pdf->Cell(25, 6, maxCaracter((number_format($suba, 2, ',', '.')), 20), 0, 0, 'R', 0);
        $pdf->Cell(25, 6, maxCaracter((number_format($subs, 2, ',', '.')), 20), 0, 1, 'R', 0);
        $total += $sub;
        $abono += $suba;
        $saldo += $subs;
    }
} else {
    if ($_GET['tipo_documento'] == 'factura') {
        $sql = pg_query(
            "SELECT pv.id_pagos_venta, pv.tipo_documento, 
                    fv.num_factura, total_venta, valor, 
                    SUM(drv1.valor_retenido),
                   SUM( drv2.valor_retenido),
                    monto_credito, 
                    monto_credito::numeric-saldo::numeric, 
                    saldo, 
                    fv.fecha_actual
                    FROM factura_venta fv 
                    LEFT JOIN pagos_venta pv 
                    USING(id_factura_venta)
                    left JOIN (select id_factura,id_retencion_fuente_factura_venta from retencion_fuente_factura_venta) rf  
                    ON pv.id_factura_venta=rf.id_factura 
                     LEFT JOIN formas_pago_mixto fpm
                    on fpm.id_factura_venta=fv.id_factura_venta   
                    LEFT JOIN detallecomprobanteretencion_v drv1
                    on drv1.id_retencion_fuente_factura_venta=rf.id_retencion_fuente_factura_venta    
                    and drv1.id_trete=1
                    LEFT JOIN detallecomprobanteretencion_v drv2
                    on drv2.id_retencion_fuente_factura_venta=rf.id_retencion_fuente_factura_venta    
                    and drv2.id_trete=2
                    WHERE fv.estado='Activo' and pv.id_cliente='$_GET[id]' and pv.estado='Activo' and fv.forma_pago='otros' 
                    and fv.forma_pago='otros' 
                    and pv.fecha_credito $query_fecha '$_GET[fin]' 
                    and pv.tipo_documento='Factura' and fpm.forma_pago='CREDITO' $query_punto $id_usuario_fv
                    GROUP BY pv.id_pagos_venta, pv.tipo_documento, 
                    fv.num_factura, total_venta, valor, 
                   
                    monto_credito, 
                    monto_credito::numeric-saldo::numeric, 
                    saldo, 
                    fv.fecha_actual
                    order by pv.id_pagos_venta asc;"
        );
    } elseif ($_GET['tipo_documento'] == 'nota') {
        $sql = pg_query(
            "SELECT pv.id_pagos_venta, pv.tipo_documento, nv.comprobante, total_venta, adelanto, rf.valor_retencion, ri.valor_retencion, 
                    monto_credito, monto_credito::numeric-saldo::numeric, saldo, nv.fecha_actual   
                    FROM facturas_novalidas nv INNER JOIN pagos_venta pv ON nv.id_facturas_novalidas=pv.id_factura_venta
                    LEFT JOIN retencion_fuente_factura_venta rf ON pv.id_factura_venta=rf.id_factura
                    LEFT JOIN retencion_iva_factura_venta ri ON pv.id_factura_venta=ri.id_factura   
                    WHERE nv.estado='Activo' and pv.estado='Activo' and nv.id_cliente='$_GET[id]' and nv.forma_pago='otros' $query_punto_nv $id_usuario_nv
                    AND pv.fecha_credito $query_fecha '$_GET[fin]' order by pv.id_pagos_venta asc;"
        );
    }
    if (pg_num_rows($sql)) {
        $pdf->SetFillColor(187, 179, 180);
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(75, 6, maxCaracter(utf8_decode('RUC/CI:' . $pdf->row[2]), 35), 0, 0, 'C', 1);
        $pdf->Cell(135, 6, maxCaracter(utf8_decode('NOMBRES:' . $pdf->row[3]), 50), 0, 1, 'C', 1);
        $sub = 0;
        $suba = 0;
        $subs = 0;
        $pdf->SetFillColor(175, 215, 240);
        $pdf->Cell(10, 6, utf8_decode('Comp.'), 1, 0, 'C', 1);
        $pdf->Cell(20, 6, utf8_decode('Tipo Doc.'), 1, 0, 'C', 1);
        $pdf->Cell(20, 6, utf8_decode('Nro Factura'), 1, 0, 'C', 1);
        $pdf->Cell(20, 6, utf8_decode('T. Fact.'), 1, 0, 'C', 1);
        $pdf->Cell(20, 6, utf8_decode('T. Credito'), 1, 0, 'C', 1);
        $pdf->Cell(20, 6, utf8_decode('-Ret. Fuente'), 1, 0, 'C', 1);
        $pdf->Cell(20, 6, utf8_decode('-Ret. Iva'), 1, 0, 'C', 1);
        $pdf->Cell(20, 6, utf8_decode('A pagar'), 1, 0, 'C', 1);
        $pdf->Cell(20, 6, utf8_decode('Abonos'), 1, 0, 'C', 1);
        $pdf->Cell(20, 6, utf8_decode('Saldo'), 1, 0, 'C', 1);
        $pdf->Cell(20, 6, utf8_decode('Fecha Pago'), 1, 1, 'C', 1);
        while ($row = pg_fetch_row($sql)) {
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(10, 6, utf8_decode($row[0]), 0, 0, 'C', 0);
            $pdf->Cell(20, 6, utf8_decode($row[1]), 0, 0, 'C', 0);
            $pdf->Cell(20, 6, utf8_decode($row[2]), 0, 0, 'C', 0);
            $pdf->Cell(20, 6, number_format($row[3], 2, ',', '.'), 0, 0, 'R', 0);
            $pdf->Cell(20, 6, number_format($row[4], 2, ',', '.'), 0, 0, 'R', 0);
            $pdf->Cell(20, 6, number_format($row[5], 2, ',', '.'), 0, 0, 'R', 0);
            $pdf->Cell(20, 6, number_format($row[6], 2, ',', '.'), 0, 0, 'R', 0);
            $pdf->Cell(20, 6, number_format($row[7], 2, ',', '.'), 0, 0, 'R', 0);
            $pdf->Cell(20, 6, number_format($row[8], 2, ',', '.'), 0, 0, 'R', 0);
            $pdf->Cell(20, 6, number_format($row[9], 2, ',', '.'), 0, 0, 'R', 0);
            $pdf->Cell(20, 6, utf8_decode($row[10]), 0, 1, 'C', 0);
            $sub += $row[7];
            $suba += $row[8];
            $subs += $row[9];
        }
        $pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(130, 6, utf8_decode("Totales:"), 0, 0, 'R', 0);
        $pdf->Cell(20, 6, maxCaracter((number_format($sub, 2, ',', '.')), 20), 0, 0, 'R', 0);
        $pdf->Cell(20, 6, maxCaracter((number_format($suba, 2, ',', '.')), 20), 0, 0, 'R', 0);
        $pdf->Cell(20, 6, maxCaracter((number_format($subs, 2, ',', '.')), 20), 0, 1, 'R', 0);
        $total += $sub;
        $abono += $suba;
        $saldo += $subs;
    }
}
$pdf->Output();
