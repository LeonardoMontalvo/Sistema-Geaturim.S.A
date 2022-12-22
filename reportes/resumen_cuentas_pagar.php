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
    var $tipoCuenta;

    function SetWidths($w)
    {
        $this->widths = $w;
    }


    function SetTipoCuenta($tc){
        $this->tipoCuenta=$tc;
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
        $this->Line(0, 32, 210, 32);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(210, 5, utf8_decode("RESUMEN CUENTAS POR PAGAR"), 0, 1, 'C', 0);
        $this->Cell(210, 5, utf8_decode($this->tipoCuenta), 0, 1, 'C', 0);
        $this->Ln(3);
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

$tipoCuenta="CUENTAS INTERNAS Y EXTERNAS";
if($_GET['tipo'] == 'Externas'){
    $tipoCuenta="CUENTAS EXTERNAS";
}elseif ($_GET['tipo'] == 'Internas'){
    $tipoCuenta="CUENTAS INTERNAS";
}

$pdf = new PDF('P', 'mm', 'a4');
$pdf->SetMargins(0, 0, 0, 0);
$pdf->SetTitle('Cuentas Pagar ' . $_GET['tipo']);
$pdf->SetTipoCuenta($tipoCuenta);
$pdf->AddPage();
$pdf->AliasNbPages();

// RANGO DE FECHAS O FECHA ACTUAL
$query_fecha = "=";
if ($pdf->rango) {
    $query_fecha = "BETWEEN '$_GET[inicio]' AND";
}
$query_punto = "";
if ($_GET['id_empre'] != '0') {
    $query_punto = "AND cp.id_empresa='$_GET[id_empre]'";
}

$id_usuario_cp = "";
if ($_GET['id'] != '0') {
    $id_usuario_cp = "and cp.id_usuario='$_GET[id]'";
}
$query_punto_fv = "";
$query_punto_fv_2 = "";
if ($_GET['id_empre'] != '0') {
    $query_punto_fv = "AND cp.id_empresa='$_GET[id_empre]'";
    $query_punto_fv_2 = "AND g.id_empresa='$_GET[id_empre]'";
}
$id_usuario_fv = "";
$id_usuario_fv_2 = "";
if ($_GET['id'] != '0') {
    $id_usuario_fv = "and cp.id_usuario='$_GET[id]'";
    $id_usuario_fv_2 = "and g.id_usuario='$_GET[id]'";
}

$queryprov="";
if(!empty($_GET['id_proveedor'])){
    $queryprov=" and id_proveedor='".$_GET['id_proveedor']."'";
}

$sql = pg_query(
    "SELECT id_proveedor, tipo_documento, identificacion_pro, empresa_pro, representante_legal 
    FROM proveedores WHERE estado='Activo' $queryprov;"
);
if (pg_num_rows($sql)) {
    $totala = 0;
    $total = 0;
    $saldo = 0;
    $abonos = 0;
    //EXTERNAS E INTERNAS
    if ($_GET['tipo'] == 'Externas') {
        while ($row = pg_fetch_row($sql)) {
            $sql1 = pg_query(
                "
                SELECT cp.num_factura,
                    tc.descripcion,
                    fecha_actual,
                    fecha_emicion,
                    (fecha_vencimiento::date-date(now())) dias_vence,
                    '0',
                    '0',
                    total,
                    (total::numeric - saldo::numeric) as abonos,
                    saldo,
                    cp.estado,
                    fecha_vencimiento
                FROM c_pagarexternas cp
                    LEFT JOIN tipo_comprobante tc on cp.tipo_documento = tc.id_tipo_comprobante
                WHERE id_proveedor='$row[0]'AND cp.fecha_actual $query_fecha '$_GET[fin]' $id_usuario_cp    $query_punto
                ORDER BY fecha_emicion asc;"
            );

            if (pg_num_rows($sql1)) {
                $sub = 0;
                $subta = 0;
                $subto = 0;
                $subabo = 0;
                $subsa = 0;
                $pdf->SetFillColor(216, 216, 231);
                $pdf->SetFont('helvetica', 'B', 9);
                $pdf->Cell(50, 6, utf8_decode(strtoupper($row[1]) . ": " . $row[2]), 0, 0, 'L', true);
                $pdf->Cell(80, 6, utf8_decode("PROVEEDOR: " . maxCaracter($row[3], 20)), 0, 0, 'L', true);
                $pdf->Cell(80, 6, utf8_decode("REPRESENTANTE: " . maxCaracter($row[4], 20)), 0, 1, 'L', true);
                $pdf->Ln(1);
                $pdf->SetFillColor(175, 215, 240);
                $pdf->Cell(30, 6, utf8_decode('N° DOCUMENTO'), 1, 0, 'C', 1);
                $pdf->Cell(22, 6, utf8_decode('EMISIÓN'), 1, 0, 'C', 1);
                $pdf->Cell(25, 6, utf8_decode('VENCIMIENTO'), 1, 0, 'C', 1);
                $pdf->Cell(30, 6, utf8_decode('CADUCA (DÍAS)'), 1, 0, 'C', 1);
                //$pdf->Cell(15, 6, utf8_decode('DIAS'), 1, 0, 'C', 1);
                $pdf->Cell(25, 6, utf8_decode('TIPO DOC.'), 1, 0, 'C', 1);
                //$pdf->Cell(25, 6, utf8_decode('ADELANTO'), 1, 0, 'C', 1);
                $pdf->Cell(26, 6, utf8_decode('TOTAL'), 1, 0, 'C', 1);
                $pdf->Cell(26, 6, utf8_decode('ABONOS'), 1, 0, 'C', 1);
                $pdf->Cell(26, 6, utf8_decode('SALDO'), 1, 1, 'C', 1);
              
                while ($row1 = pg_fetch_row($sql1)) {
                    $pdf->SetFont('helvetica', '', 8.5);
                    $pdf->Cell(30, 6, utf8_decode($row1[0]), 0, 0, 'L', 0);
                    $pdf->Cell(22, 6, utf8_decode($row1[3]), 0, 0, 'L', 0);
                    $pdf->Cell(25, 6, utf8_decode($row1[11]), 0, 0, 'L', 0);
                    if($row[4]>=0){
                        $pdf->Cell(30, 6, $row1[4], 0, 0, 'L', 0);
                    }else{
                        $pdf->Cell(30, 6, utf8_decode("VENCIDA"), 0, 0, 'L', 0);
                    }
                    //$pdf->Cell(15, 6, utf8_decode($row1[4]), 0, 0, 'C', 0);
                    $pdf->Cell(25, 6, ($row1[1]), 0, 0, 'C', 0);
                    //$pdf->Cell(25, 6, number_format($row1[5], 2, ',', '.'), 0, 0, 'R', 0);
                    $pdf->Cell(26, 6, number_format($row1[7], 2, ',', '.'), 0, 0, 'R', 0);
                    $pdf->Cell(26, 6, number_format($row1[8], 2, ',', '.'), 0, 0, 'R', 0);
                    $pdf->Cell(26, 6, number_format($row1[9], 2, ',', '.'), 0, 1, 'R', 0);
                    
                    $subta += $row1[5];
                    $subto += $row1[7];
                    $subabo += $row1[8];
                    $subsa += $row1[9];
                }
                $pdf->SetFont('helvetica', 'B', 9);
                $pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
                $pdf->Cell(132, 6, utf8_decode("Total Proveedor:"), 0, 0, 'R', 0);
                //$pdf->Cell(26, 6, maxCaracter((number_format($subta, 2, ',', '.')), 20), 0, 0, 'R', 0);
                $pdf->Cell(26, 6, maxCaracter((number_format($subto, 2, ',', '.')), 20), 0, 0, 'R', 0);
                $pdf->Cell(26, 6, maxCaracter((number_format($subabo, 2, ',', '.')), 20), 0, 0, 'R', 0);
                $pdf->Cell(26, 6, maxCaracter((number_format($subsa, 2, ',', '.')), 20), 0, 1, 'R', 0);
                $pdf->Ln(2);
                $totala += $subta;
                $total += $subto;
                $abonos += $subabo;
                $saldo += $subsa;
            }
        }
    } elseif ($_GET['tipo'] == 'Internas') {
        while ($row = pg_fetch_row($sql)) {
            $sub = 0;
            $subta = 0;
            $subto = 0;
            $subabo = 0;
            $subsa = 0;
            /* $sql1 = pg_query(
              "SELECT num_serie, tipo_documento, fecha_credito, adelanto, meses, monto_credito, (monto_credito::numeric - saldo::numeric) as abonos, saldo, cp.estado
              FROM pagos_compra cp
              LEFT JOIN factura_compra fc on cp.id_factura_compra=fc.id_factura_compra
              WHERE cp.id_proveedor='$row[0]'
              AND cp.fecha_credito $query_fecha '$_GET[fin]'
              ORDER BY id_pagos_compra;"
              ); */
            $sql1 = pg_query(
                "
            (
                SELECT g.num_factura,
                    cp.tipo_documento,
                    fecha_credito,
                    fpm.fecha_actual fecha_caduca,
                    (fpm.fecha_actual::date - date(now()))dias_vence,
                    adelanto,
                    meses,
                    monto_credito,
                    (monto_credito::numeric - saldo::numeric) as abonos,
                    saldo,
                    cp.estado,
                    cp.comprao_gasto,
                    fpm.forma_pago
                FROM pagos_compra cp
                    inner join formas_pago_mixto_g fpm on cp.id_factura_compra = fpm.id_gastos
                    inner join gastos g using(id_gastos)
                where fpm.forma_pago = 'CREDITO'
                and cp.comprao_gasto='G'
                and cp.id_proveedor='$row[0]'
                AND cp.fecha_credito $query_fecha '$_GET[fin]' 
                and g.estado='Activo'   $id_usuario_fv_2    $query_punto_fv_2
            
            )
            union all
            (
                SELECT g.num_serie,
                    cp.tipo_documento,
                    fecha_credito,
                    fpm.fecha_actual fecha_caduca,
                    (fpm.fecha_actual::date - date(now())) dias_vence,
                    adelanto,
                    meses,
                    monto_credito,
                    (monto_credito::numeric - saldo::numeric) as abonos,
                    saldo,
                    cp.estado,
                    cp.comprao_gasto,
                    fpm.forma_pago
                FROM pagos_compra cp
                    inner join formas_pago_mixto_c fpm using(id_factura_compra)
                    inner join factura_compra g using(id_factura_compra)
                    WHERE cp.comprao_gasto='C'   $id_usuario_fv_2    $query_punto_fv_2
                and fpm.forma_pago='CREDITO' and cp.id_proveedor='$row[0]'
                AND cp.fecha_credito $query_fecha '$_GET[fin]'   
                and g.estado='Activo' 
            
            )
            order by fecha_credito asc;
            "
            );

            if (pg_num_rows($sql1)) {
                $pdf->SetFillColor(216, 216, 231);
                $pdf->SetFont('helvetica', 'B', 9);
                $pdf->Cell(50, 6, utf8_decode(strtoupper($row[1]) . ": " . $row[2]), 0, 0, 'L', true);
                $pdf->Cell(80, 6, utf8_decode("PROVEEDOR: " . maxCaracter($row[3], 20)), 0, 0, 'L', true);
                $pdf->Cell(80, 6, utf8_decode("REPRESENTANTE: " . maxCaracter($row[4], 20)), 0, 1, 'L', true);
                $pdf->Ln(1);
                $pdf->SetFillColor(175, 215, 240);
                $pdf->Cell(30, 6, utf8_decode('N° DOCUMENTO'), 1, 0, 'C', 1);
                $pdf->Cell(22, 6, utf8_decode('EMISIÓN'), 1, 0, 'C', 1);
                $pdf->Cell(25, 6, utf8_decode('VENCIMIENTO'), 1, 0, 'C', 1);
                $pdf->Cell(30, 6, utf8_decode('CADUCA (DÍAS)'), 1, 0, 'C', 1);
                //$pdf->Cell(15, 6, utf8_decode('DIAS'), 1, 0, 'C', 1);
                //$pdf->Cell(15, 6, utf8_decode('GASTO/COMPRA'), 1, 0, 'C', 1);
                $pdf->Cell(25, 6, utf8_decode('COMP./GAST.'), 1, 0, 'C', 1);
                //$pdf->Cell(25, 6, utf8_decode('ADELANTO'), 1, 0, 'C', 1);
                $pdf->Cell(26, 6, utf8_decode('TOTAL'), 1, 0, 'C', 1);
                $pdf->Cell(26, 6, utf8_decode('ABONOS'), 1, 0, 'C', 1);
                $pdf->Cell(26, 6, utf8_decode('SALDO'), 1, 1, 'C', 1);
                //                $pdf->Cell(26, 6, utf8_decode('ESTADO'), 1, 1, 'C', 0);
                while ($row1 = pg_fetch_row($sql1)) {
                    $pdf->SetFont('helvetica', '', 8.5);
                    $pdf->Cell(30, 6, utf8_decode($row1[0]), 0, 0, 'L', 0);
                    $pdf->Cell(22, 6, utf8_decode($row1[2]), 0, 0, 'L', 0);
                    $pdf->Cell(25, 6, utf8_decode($row1[3]), 0, 0, 'L', 0);
                    if($row1[4]>=0){
                        $pdf->Cell(30, 6, $row1[4], 0, 0, 'L', 0);
                    }else{
                        $pdf->Cell(30, 6, utf8_decode("VENCIDA"), 0, 0, 'L', 0);
                    }
                    //$pdf->Cell(15, 6, utf8_decode($row1[4]), 0, 0, 'C', 0);
                    $pdf->Cell(25, 6, ($row1[11]), 0, 0, 'C', 0);
                    //$pdf->Cell(25, 6, number_format($row1[5], 2, ',', '.'), 0, 0, 'R', 0);
                    $pdf->Cell(26, 6, number_format($row1[7], 2, ',', '.'), 0, 0, 'R', 0);
                    $pdf->Cell(26, 6, number_format($row1[8], 2, ',', '.'), 0, 0, 'R', 0);
                    $pdf->Cell(26, 6, number_format($row1[9], 2, ',', '.'), 0, 1, 'R', 0);
                    
                    //                    $pdf->Cell(26, 6, utf8_decode($row1[8]), 0, 1, 'C', 0);
                    $subta += $row1[5];
                    $subto += $row1[7];
                    $subabo += $row1[8];
                    $subsa += $row1[9];
                }
                $pdf->SetFont('helvetica', 'B', 9);
                $pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
                $pdf->Cell(132, 6, utf8_decode("Total Proveedor:"), 0, 0, 'R', 0);
                //$pdf->Cell(26, 6, maxCaracter((number_format($subta, 2, ',', '.')), 20), 0, 0, 'R', 0);
                $pdf->Cell(26, 6, maxCaracter((number_format($subto, 2, ',', '.')), 20), 0, 0, 'R', 0);
                $pdf->Cell(26, 6, maxCaracter((number_format($subabo, 2, ',', '.')), 20), 0, 0, 'R', 0);
                $pdf->Cell(26, 6, maxCaracter((number_format($subsa, 2, ',', '.')), 20), 0, 1, 'R', 0);
                $pdf->Ln(2);
                $totala += $subta;
                $total += $subto;
                $abonos += $subabo;
                $saldo += $subsa;
            }
        }
    }else{
        while ($row = pg_fetch_row($sql)) {
            $filas=obtenerCpIternasExternas($row[0]);
            $sub = 0;
            $subta = 0;
            $subto = 0;
            $subabo = 0;
            $subsa = 0;

            if (!empty($filas)) {
                $pdf->SetFillColor(216, 216, 231);
                $pdf->SetFont('helvetica', 'B', 9);
                $pdf->Cell(50, 6, utf8_decode(strtoupper($row[1]) . ": " . $row[2]), 0, 0, 'L', true);
                $pdf->Cell(80, 6, utf8_decode("PROVEEDOR: " . maxCaracter($row[3], 20)), 0, 0, 'L', true);
                $pdf->Cell(80, 6, utf8_decode("REPRESENTANTE: " . maxCaracter($row[4], 20)), 0, 1, 'L', true);
                $pdf->Ln(1);
                $pdf->SetFillColor(175, 215, 240);
                $pdf->Cell(30, 6, utf8_decode('N° DOCUMENTO'), 1, 0, 'C', 1);
                $pdf->Cell(22, 6, utf8_decode('EMISIÓN'), 1, 0, 'C', 1);
                $pdf->Cell(25, 6, utf8_decode('VENCIMIENTO'), 1, 0, 'C', 1);
                //$pdf->Cell(15, 6, utf8_decode('DIAS'), 1, 0, 'C', 1);
                //$pdf->Cell(15, 6, utf8_decode('GASTO/COMPRA'), 1, 0, 'C', 1);
                $pdf->Cell(25, 6, utf8_decode('TIPO DOC.'), 1, 0, 'C', 1);
                //$pdf->Cell(25, 6, utf8_decode('ADELANTO'), 1, 0, 'C', 1);
                $pdf->Cell(26, 6, utf8_decode('TOTAL'), 1, 0, 'C', 1);
                $pdf->Cell(26, 6, utf8_decode('ABONOS'), 1, 0, 'C', 1);
                $pdf->Cell(26, 6, utf8_decode('SALDO'), 1, 0, 'C', 1);
                $pdf->Cell(30, 6, utf8_decode('CUENTA'), 1, 1, 'C', 1);
                //                $pdf->Cell(26, 6, utf8_decode('ESTADO'), 1, 1, 'C', 0);
                foreach($filas as $row1){
                    $pdf->SetFont('helvetica', '', 8.5);
                    $pdf->Cell(30, 6, utf8_decode($row1["num_doc"]), 0, 0, 'L', 0);
                    $pdf->Cell(22, 6, utf8_decode($row1["emision"]), 0, 0, 'L', 0);
                    $pdf->Cell(25, 6, utf8_decode($row1["caduca"]), 0, 0, 'L', 0);
                    //$pdf->Cell(15, 6, utf8_decode($row1[4]), 0, 0, 'C', 0);
                    $pdf->Cell(25, 6, ($row1["tipo_doc"]), 0, 0, 'C', 0);
                    //$pdf->Cell(25, 6, number_format($row1[5], 2, ',', '.'), 0, 0, 'R', 0);
                    $pdf->Cell(26, 6, number_format($row1["total"], 2, ',', '.'), 0, 0, 'R', 0);
                    $pdf->Cell(26, 6, number_format($row1["abonos"], 2, ',', '.'), 0, 0, 'R', 0);
                    $pdf->Cell(26, 6, number_format($row1["saldo"], 2, ',', '.'), 0, 0, 'R', 0);
                    $pdf->Cell(30, 6, $row1["tipo"], 0, 1, 'C', 0);
                    //                    $pdf->Cell(26, 6, utf8_decode($row1[8]), 0, 1, 'C', 0);
                    //$subta += $row1[5];
                    $subto += $row1["total"];
                    $subabo += $row1["abonos"];
                    $subsa += $row1["saldo"];
                }
                $pdf->SetFont('helvetica', 'B', 9);
                $pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
                $pdf->Cell(102, 6, utf8_decode("Total Proveedor:"), 0, 0, 'R', 0);
                //$pdf->Cell(26, 6, maxCaracter((number_format($subta, 2, ',', '.')), 20), 0, 0, 'R', 0);
                $pdf->Cell(26, 6, maxCaracter((number_format($subto, 2, ',', '.')), 20), 0, 0, 'R', 0);
                $pdf->Cell(26, 6, maxCaracter((number_format($subabo, 2, ',', '.')), 20), 0, 0, 'R', 0);
                $pdf->Cell(26, 6, maxCaracter((number_format($subsa, 2, ',', '.')), 20), 0, 1, 'R', 0);
                $pdf->Ln(2);
                $totala += $subta;
                $total += $subto;
                $abonos += $subabo;
                $saldo += $subsa;
            }
        }
    }
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(210, 0, utf8_decode(""), 1, 1, 'R', 0);
    if(empty($_GET['tipo'])){
        $pdf->Cell(102, 6, utf8_decode("Total:"), 0, 0, 'R', 0);
    }else{
        $pdf->Cell(132, 6, utf8_decode("Total:"), 0, 0, 'R', 0);
    }
    //$pdf->Cell(26, 6, maxCaracter((number_format($totala, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(26, 6, maxCaracter((number_format($total, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(26, 6, maxCaracter((number_format($abonos, 2, ',', '.')), 20), 0, 0, 'R', 0);
    $pdf->Cell(26, 6, maxCaracter((number_format($saldo, 2, ',', '.')), 20), 0, 1, 'R', 0);
}
$pdf->Output();


function obtenerCpIternasExternas($idproveedor)
{
    global $query_fecha, $id_usuario_cp, $query_punto, $id_usuario_fv,$query_punto_fv, $id_usuario_fv_2,$query_punto_fv_2;
    $sql = "
    (
        SELECT cp.num_factura num_doc,
            tc.descripcion tipo_doc,
            fecha_emicion::date emision,
            fecha_vencimiento::date caduca,
            (fecha_vencimiento::date - date(now())) dias_caduca,
            '0' adelanto,
            '0' meses,
            total::numeric,
            (total::numeric - saldo::numeric) as abonos,
            saldo::numeric,
            cp.estado,
            'E'::text tipo
        FROM c_pagarexternas cp
            LEFT JOIN tipo_comprobante tc on cp.tipo_documento = tc.id_tipo_comprobante
            WHERE id_proveedor='$idproveedor' AND cp.fecha_actual $query_fecha '$_GET[fin]' $id_usuario_cp    $query_punto
        ORDER BY fecha_emicion asc
        )
        union all
        (
            SELECT g.num_factura,
                --cp.comprao_gasto,
                cp.tipo_documento,
                fecha_credito,
                fpm.fecha_actual fecha_caduca,
                (fpm.fecha_actual::date - date(now())) dias_vence,
                adelanto,
                meses,
                monto_credito,
                (monto_credito::numeric - saldo::numeric) as abonos,
                saldo,
                cp.estado,
                'I'::text tipo
                --cp.comprao_gasto
                --fpm.forma_pago
            FROM pagos_compra cp
                inner join formas_pago_mixto_g fpm on cp.id_factura_compra = fpm.id_gastos
                inner join gastos g using(id_gastos)
            where fpm.forma_pago = 'CREDITO'
                and cp.comprao_gasto = 'G'
                and cp.id_proveedor='$idproveedor'
                AND cp.fecha_credito $query_fecha '$_GET[fin]' 
                and g.estado='Activo'   $id_usuario_fv_2    $query_punto_fv_2
        )
        union all
        (
            SELECT g.num_serie,
                --cp.comprao_gasto,
                cp.tipo_documento,
                fecha_credito,
                fpm.fecha_actual fecha_caduca,
                (fpm.fecha_actual::date - date(now())) dias_vence,
                adelanto,
                meses,
                monto_credito,
                (monto_credito::numeric - saldo::numeric) as abonos,
                saldo,
                cp.estado,
                'I'::text tipo
                --cp.comprao_gasto
                --fpm.forma_pago
            FROM pagos_compra cp
                inner join formas_pago_mixto_c fpm using(id_factura_compra)
                inner join factura_compra g using(id_factura_compra)
                WHERE cp.comprao_gasto='C'   $id_usuario_fv_2    $query_punto_fv_2
                and fpm.forma_pago='CREDITO' and cp.id_proveedor='$idproveedor'
                AND cp.fecha_credito $query_fecha '$_GET[fin]'   
                and g.estado='Activo' 
        )
        order by emision asc;
    ";

    $res=pg_query($sql);
    $rows=pg_fetch_all($res);
    if(empty($rows)){
        return [];
    }
    return $rows;
}
