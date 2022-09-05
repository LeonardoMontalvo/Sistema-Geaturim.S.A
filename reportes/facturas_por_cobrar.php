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
    var $tipor;

    function SetWidths($w)
    {
        $this->widths = $w;
    }

    function SetTipoReporte($tr)
    {
        $this->tipor = $tr;
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
        $this->Image('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 10, 7, 15, 15);
        $this->Image('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 180, 7, 15, 15);
        // $this->Cell(180, 5, "PROPIETARIO: " . utf8_decode($_SESSION['propietario']), 0, 1, 'C', 0);
        // $this->Cell(80, 5, "TEL.: " . utf8_decode($_SESSION['telefono']), 0, 0, 'R', 0);
        // $this->Cell(80, 5, "CEL.: " . utf8_decode($_SESSION['celular']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, "DIR.: " . utf8_decode($_SESSION['direccion']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, "SLOGAN.: " . utf8_decode($_SESSION['slogan']), 0, 1, 'C', 0);
        // $this->Cell(180, 5, utf8_decode($_SESSION['pais_ciudad']), 0, 1, 'C', 0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);
        $this->Line(0, 27, 210, 27);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(210, 5, utf8_decode("FACTURAS POR COBRAR GENERAL"), 0, 1, 'C', 0);
        $this->Cell(210, 5, utf8_decode($this->tipor), 0, 1, 'C', 0);
        $this->SetFont('Arial', 'B', 10);
        if ($this->rango) {
            $this->Cell(105, 5, utf8_decode('DESDE: ' . $_GET['inicio']), 0, 0, 'C', 0);
            $this->Cell(105, 5, utf8_decode('HASTA: ' . $_GET['fin']), 0, 1, 'C', 0);
        } else {
            $this->Cell(210, 5, utf8_decode('DE LA FECHA: ' . $_GET['fin']), 0, 1, 'C', 0);
        }
        $this->Ln(4);
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
$pdf->SetTitle('Facturas por Cobrar');
$pdf->SetMargins(0, 0, 0, 0);
if ($_GET['tipo'] == 'Externas') {
    $pdf->SetTipoReporte(" CUENTAS EXTERNAS");
} else if ($_GET['tipo'] == 'Internas') {
    $pdf->SetTipoReporte("CUENTAS INTERNAS");
} else {
    $pdf->SetTipoReporte("CUENTAS INTERNAS Y EXTERNAS");
}

$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Amble-Regular', '', 9);

$querycliente="";
if(!empty($_GET["id_cliente"])){
    $querycliente=" where id_cliente=".$_GET["id_cliente"];
}

$consulta = pg_query(
    "SELECT * from clientes $querycliente order by id_cliente asc;"
);
if (pg_num_rows($consulta)) {
    $total = 0;
    $abono = 0;
    $saldo = 0;
    $totaltf = 0;
    $subtf = 0;
    $subtc = 0;
    $subtr = 0;
    $subai = 0;
    $query_fecha = "";
    // RANGO DE FECHAS O FECHA ACTUAL
    if ($pdf->rango) {
        $query_fecha = "BETWEEN '$_GET[inicio]' AND";
    } else {
        $query_fecha = "=";
    }
    $query_punto = "";

    if ($_GET['id_empre'] != '0') {
        $query_punto = "AND fv.id_empresa='$_GET[id_empre]'";
    }


    $id_usuario = "";
    if ($_GET['id'] != '0') {
        $id_usuario = "and c.id_usuario='$_GET[id]'";
    }
    $id_usuario_fv = "";
    if ($_GET['id'] != '0') {
        $id_usuario_fv = "and fv.id_usuario='$_GET[id]'";
    }
    while ($row = pg_fetch_row($consulta)) {
        if ($_GET['tipo'] == 'Externas') {
            $tipo;
            if ($_GET['tipo_documento'] == 'factura') {
                $tipo = 1;
            } elseif ($_GET['tipo_documento'] == 'nota') {
                $tipo = 2;
            }
            $sqltxt = "
            SELECT comprobante, descripcion, num_factura, total, total::numeric-saldo::numeric, saldo, fecha_actual,
            fecha_emicion, fecha_vencimiento, abreviatura, (fecha_vencimiento::date-date(now())) vence
                FROM c_cobrarexternas c left join tipo_comprobante t 
                on c.tipo_documento=t.id_tipo_comprobante 
                where id_cliente='$row[0]' and c.estado='Activo' $id_usuario
                and  fecha_actual $query_fecha '$_GET[fin]' ;
            ";
            //var_dump($sqltxt);
            $sql = pg_query($sqltxt);
            if (pg_num_rows($sql)) {
                $pdf->SetFillColor(220, 240, 210);
                $pdf->SetFont('Helvetica', 'B', 9);
                $pdf->Cell(75, 6, maxCaracter(utf8_decode('RUC/CI:' . $row[2]), 35), 0, 0, 'C', 1);
                $pdf->Cell(135, 6, maxCaracter(utf8_decode('NOMBRES:' . $row[3]), 50), 0, 1, 'C', 1);
                $sub = 0;
                $suba = 0;
                $subs = 0;
                $subtf = 0;
                $subtc = 0;
                $subc = 0;
                $subsi = 0;
                $subtr = 0;
                //$pdf->SetX(2);
                $pdf->SetFillColor(175, 215, 240);
                /*
                $pdf->Cell(25, 6, utf8_decode('Comprobante'), 1, 0, 'C', 1);
                $pdf->Cell(30, 6, utf8_decode('Tipo Documento'), 1, 0, 'C', 1);
                $pdf->Cell(45, 6, utf8_decode('Nro Factura'), 1, 0, 'C', 1);
                $pdf->Cell(25, 6, utf8_decode('Total'), 1, 0, 'C', 1);
                $pdf->Cell(25, 6, utf8_decode('Valor Pago'), 1, 0, 'C', 1);
                $pdf->Cell(25, 6, utf8_decode('Saldo'), 1, 0, 'C', 1);
                $pdf->Cell(30, 6, utf8_decode('Fecha Pago'), 1, 1, 'C', 1); */
                $pdf->Cell(30, 6, utf8_decode('Nº DOCUMENTO'), 1, 0, 'C', 1);
                $pdf->Cell(20, 6, utf8_decode('EMISIÓN'), 1, 0, 'C', 1);
                $pdf->Cell(25, 6, utf8_decode('VENCIMIENTO'), 1, 0, 'C', 1);
                $pdf->Cell(35, 6, utf8_decode('CADUCA (DÍAS)'), 1, 0, 'C', 1);
                $pdf->Cell(25, 6, utf8_decode('TIPO DOC.'), 1, 0, 'C', 1);
                $pdf->Cell(25, 6, utf8_decode('TOTAL'), 1, 0, 'C', 1);
                $pdf->Cell(25, 6, utf8_decode('ABONOS'), 1, 0, 'C', 1);
                $pdf->Cell(25, 6, utf8_decode('SALDO'), 1, 1, 'C', 1);
                while ($row = pg_fetch_row($sql)) {
                    //$pdf->SetX(2);
                    $pdf->SetFont('Helvetica', '', 9);
                    /*  $pdf->Cell(25, 6, utf8_decode($row[0]), 0, 0, 'C', 0);
                    $pdf->Cell(30, 6, utf8_decode($row[1]), 0, 0, 'C', 0);
                    $pdf->Cell(45, 6, utf8_decode($row[2]), 0, 0, 'C', 0);
                    $pdf->Cell(25, 6, number_format($row[3], 2, '.', ','), 0, 0, 'R', 0);
                    $pdf->Cell(25, 6, number_format($row[4], 2, ',', '.'), 0, 0, 'R', 0);
                    $pdf->Cell(25, 6, number_format($row[5], 2, ',', '.'), 0, 0, 'R', 0);
                    $pdf->Cell(30, 6, utf8_decode($row[6]), 0, 1, 'C', 0); */
                    $pdf->Cell(30, 6, utf8_decode($row[2]), 0, 0, 'C', 0);
                    $pdf->Cell(20, 6, utf8_decode($row[7]), 0, 0, 'C', 0);
                    $pdf->Cell(25, 6, utf8_decode($row[8]), 0, 0, 'C', 0);
                    $pdf->Cell(35, 6, utf8_decode($row[10]), 0, 0, 'C', 0);
                    $pdf->Cell(25, 6, utf8_decode($row[9]), 0, 0, 'C', 0);
                    $pdf->Cell(25, 6, number_format($row[3], 2, ',', '.'), 0, 0, 'R', 0);
                    $pdf->Cell(25, 6, number_format($row[4], 2, ',', '.'), 0, 0, 'R', 0);
                    $pdf->Cell(25, 6, number_format($row[5], 2, ',', '.'), 0, 1, 'R', 0);
                    $sub += $row[3];
                    $subc += $row[4];
                    $suba += $row[4];
                    $subs += $row[5];
                    $subsi += $row[6];
                }
                $pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
                $pdf->SetFont('Helvetica', 'B', 9);
                $pdf->SetX(2);
                $pdf->Cell(133, 6, utf8_decode("Total Cliente:"), 0, 0, 'R', 0);
                $pdf->Cell(25, 6, maxCaracter((number_format($sub, 2, ',', '.')), 20), 0, 0, 'R', 0);
                $pdf->Cell(25, 6, maxCaracter((number_format($suba, 2, ',', '.')), 20), 0, 0, 'R', 0);
                $pdf->Cell(25, 6, maxCaracter((number_format($subs, 2, ',', '.')), 20), 0, 1, 'R', 0);
                $total += $sub;
                $abono += $suba;
                $saldo += $subs;
                $subtf += $sub;
                $subtc += $subc;
                $subtr += $subs;
                $subai += $subsi;
                $totaltf += $subs;
            }
        } else if ($_GET['tipo'] == 'Internas') {
            if ($_GET['tipo_documento'] == 'factura') {
                $sqltxt = " SELECT pv.id_factura_venta, fpm.tipo_documento, 
                fv.num_factura, total_venta, valor, 
                SUM(drv1.valor_retenido) renta,
                SUM( drv2.valor_retenido) iva,
                monto_credito, 
                monto_credito::numeric-saldo::numeric abonos, 
                saldo, 
                fpm.fecha_actual as fecha_dias
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
                WHERE fv.estado='Activo' and pv.id_cliente='$row[0]' and pv.estado='Activo' and fv.forma_pago='otros' 
                $id_usuario_fv and fv.forma_pago='otros' 
                and pv.fecha_credito $query_fecha '$_GET[fin]' 
                and pv.tipo_documento='Factura' 
                and (fpm.forma_pago='CREDITO' or fpm.forma_pago='CPOSFECHADO') $query_punto
                and fpm.tipo_documento='FACTURA'
                GROUP BY pv.id_pagos_venta, fpm.tipo_documento, 
                fv.num_factura, total_venta, valor, 
                monto_credito, 
                monto_credito::numeric-saldo::numeric, 
                saldo, 
               fpm.fecha_actual
                order by pv.id_pagos_venta asc
               ;";
                $sql = pg_query($sqltxt);
                if (pg_num_rows($sql)) {
                    $pdf->SetFillColor(220, 240, 210);
                    $pdf->SetFont('Helvetica', 'B', 9);
                    $pdf->Cell(75, 6, maxCaracter(utf8_decode('RUC/CI:' . $row[2]), 35), 0, 0, 'C', 1);
                    $pdf->Cell(135, 6, maxCaracter(utf8_decode('NOMBRES:' . $row[3]), 50), 0, 1, 'C', 1);
                    $sub = 0;
                    $suba = 0;
                    $subs = 0;
                    $pdf->SetFillColor(175, 215, 240);
                    /* $pdf->Cell(10, 6, utf8_decode('Comp.'), 1, 0, 'C', 1);
                    $pdf->Cell(20, 6, utf8_decode('Tipo Doc.'), 1, 0, 'C', 1);
                    $pdf->Cell(20, 6, utf8_decode('Nro Factura'), 1, 0, 'C', 1);
                    $pdf->Cell(20, 6, utf8_decode('T. Fact.'), 1, 0, 'C', 1);
                    $pdf->Cell(20, 6, utf8_decode('T. Credito'), 1, 0, 'C', 1);
                    $pdf->Cell(20, 6, utf8_decode('-Ret. Fuente'), 1, 0, 'C', 1);
                    $pdf->Cell(20, 6, utf8_decode('-Ret. Iva'), 1, 0, 'C', 1);
                    $pdf->Cell(20, 6, utf8_decode('A pagar'), 1, 0, 'C', 1);
                    $pdf->Cell(20, 6, utf8_decode('Abonos'), 1, 0, 'C', 1);
                    $pdf->Cell(20, 6, utf8_decode('Saldo'), 1, 0, 'C', 1);
                    $pdf->Cell(20, 6, utf8_decode('Fecha Pago'), 1, 1, 'C', 1); */
                    //$pdf->Cell(20, 6, utf8_decode('Tipo C/G.'), 1, 0, 'C', 1);
                    $pdf->Cell(30, 6, utf8_decode('Nº DOCUMENTO'), 1, 0, 'C', 1);
                    $pdf->Cell(20, 6, utf8_decode('EMISIÓN'), 1, 0, 'C', 1);
                    $pdf->Cell(25, 6, utf8_decode('VENCIMIENTO'), 1, 0, 'C', 1);
                    $pdf->Cell(25, 6, utf8_decode('TIPO DOC.'), 1, 0, 'C', 1);
                    $pdf->Cell(25, 6, utf8_decode('TOTAL'), 1, 0, 'C', 1);
                    $pdf->Cell(20, 6, utf8_decode('- R.FUENTE'), 1, 0, 'C', 1);
                    $pdf->Cell(15, 6, utf8_decode('- R.IVA'), 1, 0, 'C', 1);
                    $pdf->Cell(25, 6, utf8_decode('ABONOS'), 1, 0, 'C', 1);
                    $pdf->Cell(25, 6, utf8_decode('SALDO'), 1, 1, 'C', 1);
                    while ($row = pg_fetch_row($sql)) {
                        $pdf->SetFont('Helvetica', '', 9);
                        /* $pdf->Cell(10, 6, utf8_decode($row[0]), 0, 0, 'C', 0);
                        $pdf->Cell(20, 6, utf8_decode($row[1]), 0, 0, 'C', 0);
                        $pdf->Cell(20, 6, utf8_decode($row[2]), 0, 0, 'C', 0);
                        $pdf->Cell(20, 6, number_format($row[3], 2, ',', '.'), 0, 0, 'R', 0);
                        $pdf->Cell(20, 6, number_format($row[4], 2, ',', '.'), 0, 0, 'R', 0);
                        $pdf->Cell(20, 6, number_format($row[5], 2, ',', '.'), 0, 0, 'R', 0);
                        $pdf->Cell(20, 6, number_format($row[6], 2, ',', '.'), 0, 0, 'R', 0);
                        $pdf->Cell(20, 6, number_format($row[7], 2, ',', '.'), 0, 0, 'R', 0);
                        $pdf->Cell(20, 6, number_format($row[8], 2, ',', '.'), 0, 0, 'R', 0);
                        $pdf->Cell(20, 6, number_format($row[9], 2, ',', '.'), 0, 0, 'R', 0);
                        $pdf->Cell(20, 6, utf8_decode($row[10]), 0, 1, 'C', 0); */
                        $pdf->Cell(30, 6, utf8_decode($row[2]), 0, 0, 'C', 0);
                        $pdf->Cell(20, 6, utf8_decode($row[10]), 0, 0, 'C', 0);
                        $pdf->Cell(25, 6, utf8_decode($row[10]), 0, 0, 'C', 0);
                        $pdf->Cell(25, 6, utf8_decode($row[1]), 0, 0, 'C', 0);
                        $pdf->Cell(25, 6, number_format($row[7], 2, ',', '.'), 0, 0, 'R', 0);
                        $pdf->Cell(20, 6, number_format($row[5], 2, ',', '.'), 0, 0, 'R', 0);
                        $pdf->Cell(15, 6, number_format($row[6], 2, ',', '.'), 0, 0, 'R', 0);
                        $pdf->Cell(25, 6, number_format($row[8], 2, ',', '.'), 0, 0, 'R', 0);
                        $pdf->Cell(25, 6, number_format($row[9], 2, ',', '.'), 0, 1, 'R', 0);
                        $sub += $row[7];
                        $suba += $row[8];
                        $subs += $row[9];
                        $subtf += $row[3];
                        $subtc += $row[4];
                        $subtr += $row[5];
                        $subai += $row[6];
                    }
                    $pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
                    $pdf->SetFont('Helvetica', 'B', 9);
                    $pdf->Cell(100, 6, utf8_decode("Total Cliente:"), 0, 0, 'R', 0);
                    $pdf->Cell(25, 6, maxCaracter((number_format($subtf, 2, ',', '.')), 20), 0, 0, 'R', 0);
                    $pdf->Cell(20, 6, maxCaracter((number_format($subtc, 2, ',', '.')), 20), 0, 0, 'R', 0);
                    $pdf->Cell(15, 6, maxCaracter((number_format($subtr, 2, ',', '.')), 20), 0, 0, 'R', 0);
                    $pdf->Cell(25, 6, maxCaracter((number_format($subai, 2, ',', '.')), 20), 0, 0, 'R', 0);
                    $pdf->Cell(25, 6, maxCaracter((number_format($subs, 2, ',', '.')), 20), 0, 0, 'R', 0);

                    $pdf->Cell(20, 6, maxCaracter((number_format($suba, 2, ',', '.')), 20), 0, 0, 'R', 0);
                    $pdf->Cell(20, 6, maxCaracter((number_format($subs, 2, ',', '.')), 20), 0, 1, 'R', 0);
                    $total += $sub;
                    $abono += $suba;
                    $saldo += $subs;
                    $totaltf += $subs;
                }
            } elseif ($_GET['tipo_documento'] == 'nota1') {
                $filas = obtenerCCInternasNota($row[0]);
                if (count($filas)) {
                    $pdf->SetFillColor(187, 179, 180);
                    $pdf->SetFont('Helvetica', 'B', 9);
                    $pdf->Cell(75, 6, maxCaracter(utf8_decode('RUC/CI:' . $row[2]), 35), 0, 0, 'C', 1);
                    $pdf->Cell(135, 6, maxCaracter(utf8_decode('NOMBRES:' . $row[3]), 50), 0, 1, 'C', 1);
                    $sub = 0;
                    $suba = 0;
                    $subs = 0;
                    $subtf = 0;
                    $subtc = 0;
                    $subc = 0;
                    $subsi = 0;
                    $subtr = 0;
                    //$pdf->SetX(2);
                    $pdf->SetFillColor(175, 215, 240);
                    /*  $pdf->Cell(25, 6, utf8_decode('Comprobante'), 1, 0, 'C', 1);
                    $pdf->Cell(30, 6, utf8_decode('Tipo Documento'), 1, 0, 'C', 1);
                    $pdf->Cell(45, 6, utf8_decode('Nro Factura'), 1, 0, 'C', 1);
                    $pdf->Cell(25, 6, utf8_decode('Total'), 1, 0, 'C', 1);
                    $pdf->Cell(25, 6, utf8_decode('Valor Pagado'), 1, 0, 'C', 1);
                    $pdf->Cell(25, 6, utf8_decode('Saldo'), 1, 0, 'C', 1);
                    $pdf->Cell(30, 6, utf8_decode('Fecha Pago'), 1, 1, 'C', 1); */
                    $pdf->Cell(30, 6, utf8_decode('Nº DOCUMENTO'), 1, 0, 'C', 1);
                    $pdf->Cell(20, 6, utf8_decode('EMISIÓN'), 1, 0, 'C', 1);
                    $pdf->Cell(25, 6, utf8_decode('VENCIMIENTO'), 1, 0, 'C', 1);
                    $pdf->Cell(35, 6, utf8_decode('CADUCA (DÍAS)'), 1, 0, 'C', 1);
                    $pdf->Cell(25, 6, utf8_decode('TIPO DOC.'), 1, 0, 'C', 1);
                    $pdf->Cell(25, 6, utf8_decode('TOTAL'), 1, 0, 'C', 1);
                    $pdf->Cell(25, 6, utf8_decode('ABONOS'), 1, 0, 'C', 1);
                    $pdf->Cell(25, 6, utf8_decode('SALDO'), 1, 1, 'C', 1);
                    foreach ($filas as $fila) {
                        //$pdf->SetX(2);
                        $pdf->SetFont('Helvetica', '', 9);
                        /*  $pdf->Cell(25, 6, utf8_decode($fila["comprobante"]), 0, 0, 'C', 0);
                        $pdf->Cell(30, 6, utf8_decode($fila["descripcion"]), 0, 0, 'C', 0);
                        $pdf->Cell(45, 6, utf8_decode($fila["num_factura"]), 0, 0, 'C', 0);
                        $pdf->Cell(25, 6, number_format($fila["total"], 2, '.', ','), 0, 0, 'R', 0);
                        $pdf->Cell(25, 6, number_format($fila["valor_pagado"], 2, ',', '.'), 0, 0, 'R', 0);
                        $pdf->Cell(25, 6, number_format($fila["saldo"], 2, ',', '.'), 0, 0, 'R', 0);
                        $pdf->Cell(30, 6, utf8_decode($fila["fecha_pago"]), 0, 1, 'C', 0); */
                        $pdf->Cell(30, 6, utf8_decode($fila["num_factura"]), 0, 0, 'C', 0);
                        $pdf->Cell(20, 6, utf8_decode($fila["fecha_actual"]), 0, 0, 'C', 0);
                        $pdf->Cell(25, 6, utf8_decode($fila["fecha_vencimiento"]), 0, 0, 'C', 0);
                        $pdf->Cell(35, 6, utf8_decode($fila["vence"]), 0, 0, 'C', 0);
                        $pdf->Cell(25, 6, utf8_decode($fila["descripcion"]), 0, 0, 'C', 0);
                        $pdf->Cell(25, 6, number_format($fila["total"], 2, ',', '.'), 0, 0, 'R', 0);
                        $pdf->Cell(25, 6, number_format($fila["valor_pagado"], 2, ',', '.'), 0, 0, 'R', 0);
                        $pdf->Cell(25, 6, number_format($fila["saldo"], 2, ',', '.'), 0, 1, 'R', 0);
                        $sub += $fila["total"];
                        $subc += $fila["valor_pagado"];
                        $suba += $fila["valor_pagado"];
                        $subs += $fila["saldo"];
                        //$subsi += $fila[6];
                    }
                    $pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
                    $pdf->SetFont('Helvetica', 'B', 9);
                    $pdf->SetX(2);
                    $pdf->Cell(100, 6, utf8_decode("Saldo Cliente:"), 0, 0, 'R', 0);
                    $pdf->Cell(25, 6, maxCaracter((number_format($sub, 2, ',', '.')), 20), 0, 0, 'R', 0);
                    $pdf->Cell(25, 6, maxCaracter((number_format($suba, 2, ',', '.')), 20), 0, 0, 'R', 0);
                    $pdf->Cell(25, 6, maxCaracter((number_format($subs, 2, ',', '.')), 20), 0, 1, 'R', 0);
                    $total += $sub;
                    $abono += $suba;
                    $saldo += $subs;
                    $subtf += $sub;
                    $subtc += $subc;
                    $subtr += $subs;
                    $subai += $subsi;
                    $totaltf += $subs;
                }
            }
        } else {
            $filas = obtenerCCInternasExternas($row[0]);
            if (count($filas)) {
                $pdf->SetFillColor(220, 240, 210);
                $pdf->SetFont('Helvetica', 'B', 9);
                $pdf->Cell(75, 6, maxCaracter(utf8_decode('RUC/CI:' . $row[2]), 35), 0, 0, 'C', 1);
                $pdf->Cell(135, 6, maxCaracter(utf8_decode('NOMBRES:' . $row[3]), 50), 0, 1, 'C', 1);
                $sub = 0;
                $suba = 0;
                $subs = 0;
                $subtf = 0;
                $subtc = 0;
                $subc = 0;
                $subsi = 0;
                $subtr = 0;
                //$pdf->SetX(2);
                $pdf->SetFillColor(175, 215, 240);
                /* $pdf->Cell(25, 6, utf8_decode('Comprobante'), 1, 0, 'C', 1);
                $pdf->Cell(30, 6, utf8_decode('Tipo Documento'), 1, 0, 'C', 1);
                $pdf->Cell(45 - 10, 6, utf8_decode('Nro Factura'), 1, 0, 'C', 1);
                $pdf->Cell(25, 6, utf8_decode('Total'), 1, 0, 'C', 1);
                $pdf->Cell(25, 6, utf8_decode('Valor Pagado'), 1, 0, 'C', 1);
                $pdf->Cell(25, 6, utf8_decode('Saldo'), 1, 0, 'C', 1);
                $pdf->Cell(30 - 5, 6, utf8_decode('Fecha Pago'), 1, 0, 'C', 1);
                $pdf->Cell(15, 6, utf8_decode('Tipo'), 1, 1, 'C', 1); */
                $pdf->Cell(30, 6, utf8_decode('N° DOCUMENTO'), 1, 0, 'C', 1);
                $pdf->Cell(22, 6, utf8_decode('EMISIÓN'), 1, 0, 'C', 1);
                $pdf->Cell(25, 6, utf8_decode('VENCIMIENTO'), 1, 0, 'C', 1);
                $pdf->Cell(25, 6, utf8_decode('TIPO DOC.'), 1, 0, 'C', 1);
                $pdf->Cell(26, 6, utf8_decode('TOTAL'), 1, 0, 'C', 1);
                $pdf->Cell(26, 6, utf8_decode('ABONOS'), 1, 0, 'C', 1);
                $pdf->Cell(26, 6, utf8_decode('SALDO'), 1, 0, 'C', 1);
                $pdf->Cell(30, 6, utf8_decode('CUENTA'), 1, 1, 'C', 1);
                foreach ($filas as $fila) {
                    //$pdf->SetX(2);
                    $pdf->SetFont('Helvetica', '', 9);
                    /*  $pdf->Cell(25, 6, utf8_decode($fila["comprobante"]), 0, 0, 'C', 0);
                    $pdf->Cell(30, 6, utf8_decode($fila["descripcion"]), 0, 0, 'C', 0);
                    $pdf->Cell(45 - 10, 6, utf8_decode($fila["num_factura"]), 0, 0, 'C', 0);
                    $pdf->Cell(25, 6, number_format($fila["total"], 2, '.', ','), 0, 0, 'R', 0);
                    $pdf->Cell(25, 6, number_format($fila["valor_pagado"], 2, ',', '.'), 0, 0, 'R', 0);
                    $pdf->Cell(25, 6, number_format($fila["saldo"], 2, ',', '.'), 0, 0, 'R', 0);
                    $pdf->Cell(30 - 5, 6, utf8_decode($fila["fecha_pago"]), 0, 0, 'C', 0);
                    $pdf->Cell(15, 6, utf8_decode($fila["tipo"]), 0, 1, 'C', 0); */
                    $pdf->Cell(30, 6, utf8_decode($fila["num_factura"]), 0, 0, 'C', 0);
                    $pdf->Cell(22, 6, utf8_decode($fila["fecha_emision"]), 0, 0, 'C', 0);
                    $pdf->Cell(25, 6, utf8_decode($fila["fecha_vencimiento"]), 0, 0, 'C', 0);
                    $pdf->Cell(25, 6, utf8_decode($fila["descripcion"]), 0, 0, 'C', 0);
                    $pdf->Cell(26, 6, number_format($fila["total"], 2, ',', '.'), 0, 0, 'R', 0);
                    $pdf->Cell(26, 6, number_format($fila["valor_pagado"], 2, ',', '.'), 0, 0, 'R', 0);
                    $pdf->Cell(26, 6, number_format($fila["saldo"], 2, ',', '.'), 0, 0, 'R', 0);
                    $pdf->Cell(30, 6, utf8_decode($fila["tipo"]), 0, 1, 'C', 0);
                    $sub += $fila["total"];
                    $subc += $fila["valor_pagado"];
                    $suba += $fila["valor_pagado"];
                    $subs += $fila["saldo"];
                    //$subsi += $fila[6];
                }
                $pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
                $pdf->SetFont('Helvetica', 'B', 9);
                $pdf->SetX(2);
                $pdf->Cell(105, 6, utf8_decode("Total Cliente:"), 0, 0, 'R', 0);
                $pdf->Cell(25, 6, maxCaracter((number_format($sub, 2, ',', '.')), 20), 0, 0, 'R', 0);
                $pdf->Cell(25, 6, maxCaracter((number_format($suba, 2, ',', '.')), 20), 0, 0, 'R', 0);
                $pdf->Cell(25, 6, maxCaracter((number_format($subs, 2, ',', '.')), 20), 0, 1, 'R', 0);
                $total += $sub;
                $abono += $suba;
                $saldo += $subs;
                $subtf += $sub;
                $subtc += $subc;
                $subtr += $subs;
                $subai += $subsi;
                $totaltf += $subs;
            }
        }
    }
    $pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
    $pdf->SetFont('Helvetica', 'B', 9);
    $pdf->SetX(1);
    if (!empty($_GET['tipo'])) {
        $pdf->Cell(169 + 15, 6, utf8_decode("Totales:"), 0, 0, 'R', 0);
    } else {
        $pdf->Cell(156, 6, utf8_decode("Totales:"), 0, 0, 'R', 0);
    }

    $pdf->Cell(25, 6, maxCaracter((number_format($totaltf, 2, ',', '.')), 20), 0, 0, 'R', 0);
    //    $pdf->Cell(25, 6, maxCaracter((number_format($total, 2, ',', '.')), 20), 0, 0, 'R', 0);
    //    $pdf->Cell(25, 6, maxCaracter((number_format($abono, 2, ',', '.')), 20), 0, 1, 'R', 0);
    //    $pdf->Cell(25, 6, maxCaracter((number_format($saldo, 2, ',', '.')), 20), 0, 1, 'R', 0);
}
$pdf->Output();

function obtenerCCInternasExternas($idcliente)
{
    global $id_usuario_fv, $query_punto, $id_usuario, $query_fecha, $tipo;
    $sql = "(SELECT 
    pv.id_factura_venta::text comprobante, 
    fpm.tipo_documento descripcion, 
    fv.num_factura, 
    total_venta total, 
    monto_credito::numeric-saldo::numeric valor_pagado, 
    saldo, 
    pv.fecha_dias as fecha_pago,
    'I'::text tipo,
    fv.fecha_actual fecha_emision,
    fpm.fecha_actual fecha_vencimiento
    FROM factura_venta fv 
    LEFT JOIN pagos_venta pv USING(id_factura_venta) 
    left JOIN (select id_factura,id_retencion_fuente_factura_venta 
    from retencion_fuente_factura_venta) rf ON pv.id_factura_venta=rf.id_factura 
    LEFT JOIN formas_pago_mixto fpm on fpm.id_factura_venta=fv.id_factura_venta 
    LEFT JOIN detallecomprobanteretencion_v drv1 
    on drv1.id_retencion_fuente_factura_venta=rf.id_retencion_fuente_factura_venta and drv1.id_trete=1 
    LEFT JOIN detallecomprobanteretencion_v drv2 on drv2.id_retencion_fuente_factura_venta=rf.id_retencion_fuente_factura_venta and drv2.id_trete=2 
    WHERE fv.estado='Activo' and pv.id_cliente='$idcliente' and pv.estado='Activo' and fv.forma_pago='otros' 
    $id_usuario_fv $query_punto
    and pv.fecha_credito $query_fecha '$_GET[fin]'  
    and (fpm.forma_pago='CREDITO' or fpm.forma_pago='CPOSFECHADO')
    and fpm.tipo_documento='FACTURA'
    and pv.tipo_documento = 'Factura'
     GROUP BY pv.id_pagos_venta, fpm.tipo_documento, 
    fv.num_factura, total_venta, valor, monto_credito, 
    monto_credito::numeric-saldo::numeric, saldo, 
    fpm.fecha_actual,fv.fecha_actual 
    order by pv.id_pagos_venta asc)
    union all
    (
    SELECT comprobante, descripcion, num_factura, total::numeric, 
    total::numeric-saldo::numeric valor_pagado, saldo, fecha_actual::date,
    'E'::text tipo, fecha_emicion::date, fecha_vencimiento::date
    FROM c_cobrarexternas c 
    left join tipo_comprobante t on c.tipo_documento=t.id_tipo_comprobante 
    where id_cliente='$idcliente' and c.estado='Activo' 
    $id_usuario
    and  fecha_actual $query_fecha '$_GET[fin]' 
    )
    union all
    (
    SELECT pv.id_factura_venta::text comprobante, 
    fpm.tipo_documento descripcion, 
    fv.comprobante num_factura, 
    total_venta total, 
    monto_credito::numeric-saldo::numeric valor_pagado, 
    saldo, 
    pv.fecha_dias as fecha_pago,
    'I'::text tipo,
    fv.fecha_actual fecha_emision,
    fpm.fecha_actual fecha_caducidad
    FROM facturas_novalidas fv 
    LEFT JOIN pagos_venta pv on pv.id_factura_venta=fv.id_facturas_novalidas
    left JOIN (select id_factura,id_retencion_fuente_factura_venta from retencion_fuente_factura_venta) rf ON pv.id_factura_venta=rf.id_factura 
    LEFT JOIN formas_pago_mixto fpm on fpm.id_factura_venta=fv.id_facturas_novalidas 
    WHERE fv.estado='Activo' 
    and pv.id_cliente='$idcliente' 
    and pv.estado='Activo' 
    and fv.forma_pago='otros' 
    $id_usuario_fv $query_punto
    and pv.fecha_credito $query_fecha '$_GET[fin]'  
    and pv.tipo_documento='Nota' 
    and (fpm.forma_pago='CREDITO' or fpm.forma_pago='CPOSFECHADO')  
    and fpm.tipo_documento='NOTA'
    GROUP BY pv.id_pagos_venta, fpm.tipo_documento, fv.comprobante, 
    total_venta, valor, monto_credito, saldo, fpm.fecha_actual, fv.fecha_actual
    order by pv.id_pagos_venta asc
    )";
    
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (!$rows) {
        return [];
    }
    return $rows;
}

function obtenerCCInternasNota($idcliente)
{
    global $id_usuario_fv, $query_punto, $query_fecha;
    $sql = "
    SELECT pv.id_factura_venta::text comprobante, 
    fpm.tipo_documento descripcion, 
    fv.comprobante num_factura, 
    total_venta total, 
    monto_credito::numeric-saldo::numeric valor_pagado, 
    saldo, 
    pv.fecha_dias as fecha_pago,
    fv.fecha_actual,
    fpm.fecha_actual fecha_vencimiento,
    (fpm.fecha_actual-date(now())) vence
    FROM facturas_novalidas fv 
    LEFT JOIN pagos_venta pv on pv.id_factura_venta=fv.id_facturas_novalidas
    left JOIN (select id_factura,id_retencion_fuente_factura_venta from retencion_fuente_factura_venta) rf ON pv.id_factura_venta=rf.id_factura 
    LEFT JOIN formas_pago_mixto fpm on fpm.id_factura_venta=fv.id_facturas_novalidas 
    WHERE fv.estado='Activo' 
    and pv.id_cliente='$idcliente' 
    and pv.estado='Activo' 
    and fv.forma_pago='otros' 
    $id_usuario_fv $query_punto
    and pv.fecha_credito $query_fecha '$_GET[fin]'  
    and pv.tipo_documento='Nota' 
    and (fpm.forma_pago='CREDITO' or fpm.forma_pago='CPOSFECHADO')  
    and fpm.tipo_documento='NOTA'
    GROUP BY pv.id_pagos_venta, fpm.tipo_documento, fv.comprobante, 
    total_venta, valor, monto_credito, saldo, fpm.fecha_actual, fv.fecha_actual
    order by pv.id_pagos_venta asc ;
    ";

    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (!$rows) {
        return [];
    }
    return $rows;
}
