<?php

require('../fpdf/fpdf.php');
include '../procesos/base.php';
include '../procesos/funciones.php';
conectarse();
date_default_timezone_set('America/Guayaquil');
session_start();
$conpunto = 1;
$consultapunto = pg_query("select max(id_punto_venta_empresa) from punto_venta_empresa where punto_venta_empresa.id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($consultapunto)) {
    $conpunto = $row[0];
}
$conpuntoresult = 1;
$consultapuntoresult = pg_query("select id_punto_venta from punto_venta_empresa where id_punto_venta_empresa='$conpunto'");
while ($row = pg_fetch_row($consultapuntoresult)) {
    $conpuntoresult = $row[0];
}

/* echo obtenerSaldoInicial($_GET["id"], $_GET["inicio"]);
exit();
 */
class PDF extends FPDF
{

    var $widths;
    var $aligns;
    var $ciudad;
    var $ruc;
    var $telf;

    function SetWidths($w)
    {
        $this->widths = $w;
    }

    function Header()
    {
        $this->obtenerDatosEmpresa();
        $this->AddFont('Amble-Regular', '', 'Amble-Regular.php');
        $this->SetFont('Amble-Regular', '', 10);
        $this->SetX(1);
        $this->SetY(4);
        $this->SetFont('Arial', 'B', 14);
        $this->SetX(60);
        $this->Cell(70, 8, utf8_decode($_SESSION['nombre_empresa']), 0, 0, 'L', 0);
        $this->SetX(210);
        $this->SetFont('Arial', '', 9);
        $this->Image('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"], 5, 8, 45, 14);
        $this->SetFont('Amble-Regular', '', 10);
        $this->SetX(60);
        $this->ln();
        $this->SetX(60);
        $this->Cell(90, 8, utf8_decode($_SESSION['empresa'] . "-" . $this->ciudad), 0, 1, 'L', 0);
        $this->SetX(60);
        $this->Cell(90, 5, "320 - " . utf8_decode($this->ruc), 0, 1, 'L', 0);
        $this->SetX(60);
        $this->Cell(110, 5, "IMBABURA - " . utf8_decode($_SESSION['direccion'] . " " . $this->telf), 0, 1, 'L', 0);
        $this->Ln(2);
        $this->SetX(0);
        $this->Cell(300, 0, "", 1, 1, 'L', 0); //linea de separacion
        $this->SetFont('Arial', 'B', 12);
        $this->Ln(1);
        $this->SetX(4);
        $this->Cell(70, 5, utf8_decode("KARDEX - VALORADO"), 0, 1, 'L', 0);
        $this->SetX(4);
        $this->SetFont('Amble-Regular', '', 10);
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Amble-Regular', '', 10);

        if ($_GET['id'] == "") {
            $this->SetX(4);
            $this->Cell(85, 6, utf8_decode('Desde el: ' . $_GET['inicio']), 0, 0, 'L', 1);
            $this->Cell(120, 6, utf8_decode('Hasta el: ' . $_GET['fin']), 0, 1, 'L', 1);
            $this->SetX(4);
            $this->Cell(85, 6, utf8_decode('Código: '), 0, 0, 'L', 1);
            $this->Cell(120, 6, utf8_decode('Descripción: '), 0, 1, 'L', 1);
        } else {
            $sql = pg_query("select P.codigo, P.articulo from productos P where P.cod_productos = '$_GET[id]'");
            $this->SetLineWidth(0.2);
            while ($row = pg_fetch_row($sql)) {
                $this->SetX(4);
                $this->Cell(85, 6, utf8_decode('Desde el: ' . $_GET['inicio']), 0, 0, 'L', 1);
                $this->Cell(120, 6, utf8_decode('Hasta el: ' . $_GET['fin']), 0, 1, 'L', 1);
                $this->SetX(4);
                $this->Cell(85, 6, utf8_decode('Código: ' . $row[0]), 0, 0, 'L', 1);
                $this->Cell(120, 6, utf8_decode('Descripción: ' . $row[1]), 0, 1, 'L', 1);
            }
        }
        $this->SetX(0);
        $this->Cell(300, 0, "", 1, 1, 'L', 0); //linea de separacion
        $this->Ln(2);
        $this->SetX(4);

        $this->SetFont('Amble-Regular', '', 8);
        $this->Cell(105, 5, '', 0, 0, 'L', 0);
        $this->Cell(60, 5, utf8_decode("ENTRADAS"), 1, 0, 'C', 0);
        $this->Cell(60, 5, utf8_decode("SALIDAS"), 1, 0, 'C', 0);
        $this->Cell(60, 5, utf8_decode("SALDOS"), 1, 0, 'C', 0);

        $this->Ln(5);
        $this->SetX(4);
        $this->SetFont('Amble-Regular', '', 8);
        $this->Cell(10, 5, utf8_decode("COM"), 1, 0, 'C', 0);
        $this->Cell(25, 5, utf8_decode("FECHA"), 1, 0, 'C', 0);
        $this->Cell(70, 5, utf8_decode("DETALLE"), 1, 0, 'C', 0);
        $this->Cell(20, 5, utf8_decode("CANT."), 1, 0, 'C', 0);
        $this->Cell(20, 5, utf8_decode("P.U."), 1, 0, 'C', 0);
        $this->Cell(20, 5, utf8_decode("P.T."), 1, 0, 'C', 0);
        $this->Cell(20, 5, utf8_decode("CANT."), 1, 0, 'C', 0);
        $this->Cell(20, 5, utf8_decode("P.U."), 1, 0, 'C', 0);
        $this->Cell(20, 5, utf8_decode("P.T."), 1, 0, 'C', 0);
        $this->Cell(20, 5, utf8_decode("CANT."), 1, 0, 'C', 0);
        $this->Cell(20, 5, utf8_decode("P.U."), 1, 0, 'C', 0);
        $this->Cell(20, 5, utf8_decode("P.T."), 1, 0, 'C', 0);
        $this->Ln(5);
    }

    function Footer()
    {
        $this->obtenerFechaHora();
        $this->SetY(-15);
        $this->SetX(6);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(25, 4, $this->hora, 0, 0, 'C');
        $this->Cell(25, 4, $this->fecha, 0, 0, 'C');
        $this->Cell(180, 4, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    function obtenerDatosEmpresa()
    {
        $sql = "SELECT * FROM empresa";
        $result = pg_query($sql);
        if (pg_num_rows($result) > 0) {
            while ($row = pg_fetch_assoc($result)) {
                $this->ciudad = $row['ciudad_empresa'];
                $this->ruc = $row['ruc_empresa'];
                $this->telf = $row['telefono_empresa'];
            }
        }
    }

    function obtenerFechaHora()
    {
        $this->fecha = date('Y-m-d', time());
        $this->hora = date("h") . ":" . date("i") . ":" . date("s") . " " . date("A");
    }
}

$pdf = new PDF('L', 'mm', 'a4');
$pdf->AddPage();
$pdf->SetMargins(0, 0, 0, 0);
$pdf->AliasNbPages();
$pdf->AddFont('Amble-Regular', '', 'Amble-Regular.php');
$pdf->SetFont('Amble-Regular', '', 10);
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetX(4);
$pdf->SetFont('Amble-Regular', '', 9);
$cantidad_entrada = 0;
$cantidad_entrada_total = 0;
$cantidad_total_salida = 0;
$cantidad_salida = 0;
$precio_total_salidas = 0;
$precio_total_salida = 0;
$precio_total_entradas = obtenerSaldoInicial($_GET["id"], $_GET["inicio"]);
$cantidad_total = 0;
$precio_total_total = 0;

$CANT = 0;
$PU = 0;
$PT = 0;
$sql = pg_query("select * from kardex_valorizado K where K.cod_productos = '$_GET[id]'  AND K.id_empresa='$conpuntoresult' order by id_kardex asc ");
while ($row = pg_fetch_row($sql)) {
    $cantida_inicial = $row[9];
}
$cantidad_inicial = 0;
//$cantidad_inicial=$cantida_inicial;
// if($_GET['id'] == "") {
//     $sql = pg_query("SELECT * from kardex_valorizado K where k.fecha_kardex between '$_GET[inicio]' and '$_GET[fin]'  order by k.id_kardex_valorizado asc");
//     while($row=pg_fetch_row($sql)) {
//         $pdf->SetX(1);
//         $pdf->Cell(25, 5, maxCaracter(utf8_decode($row[1]),15),0,0, 'L',0);
//         $pdf->Cell(48, 5, maxCaracter(utf8_decode($row[2]),20),0,0, 'L',0);
//         $pdf->Cell(15, 5, maxCaracter(utf8_decode($row[3]),20),0,0, 'L',0);
//         $pdf->Cell(15, 5, maxCaracter(utf8_decode($row[4]),20),0,0, 'L',0);
//         $pdf->Cell(15, 5, maxCaracter(utf8_decode($row[5]),20),0,0, 'L',0);
//         $pdf->Cell(15, 5, maxCaracter(utf8_decode($row[6]),20),0,0, 'L',0);
//         $pdf->Cell(15, 5, maxCaracter(utf8_decode($row[7]),20),0,0, 'L',0);
//         $pdf->Cell(15, 5, maxCaracter(utf8_decode($row[8]),20),0,0, 'L',0);
//         $pdf->Cell(15, 5, maxCaracter(utf8_decode($row[9]),20),0,0, 'L',0);
//         $pdf->Cell(15, 5, maxCaracter(utf8_decode($row[10]),20),0,0, 'L',0);
//         $pdf->Cell(15, 5, maxCaracter(utf8_decode($row[11]),20),0,0, 'L',0);
//         $pdf->Ln(5);
//     }
// } else {


$sql = pg_query("SELECT * from kardex_valorizado K where k.fecha_transaccion between '$_GET[inicio]' and '$_GET[fin]'  and k.cod_productos = '$_GET[id]'  AND K.id_empresa='$conpuntoresult' order by k.id_kardex asc");
while ($row = pg_fetch_row($sql)) {

    if ($row[15] == 'I' || $row[15] == 'INV' || $row[15] == 'INVS'  || $row[15] == 'C' || $row[15] == 'C.P' || $row[15] == 'A') {

        $remp = strpos($row[3], '- REMP -');

        $pdf->SetX(4);
        $pdf->Cell(10, 5, maxCaracter(utf8_decode($row[16]), 15), "L", 0, 'L', 0); // ID COMPROBANTE

        $fecha = obtenerFechaEmisionDoc($row[15], $row[16]);
        if (empty($fecha)) {
            $pdf->Cell(25, 5, maxCaracter(utf8_decode($row[2]), 28), 0, 0, 'L', 0); // FECHA
        } else {
            $pdf->Cell(25, 5, maxCaracter(utf8_decode($fecha), 28), 0, 0, 'L', 0); // FECHA
        }

        $cantidad_entrada = $cantidad_entrada + $row[4];
        $cantidad_total_salida = $cantidad_total_salida + $row[5];
        //$pdf->Cell(5, 5, maxCaracter(utf8_decode($row[5]), 20), 1, 0, 'L', 0);
        //$cantidad_salida = $cantidad_salida + $row[6];
        $pdf->Cell(70, 5, maxCaracter(utf8_decode($row[3]), 50), 0, 0, 'L', 0); // CONCEPTO
        $pdf->Cell(20, 5, maxCaracter(number_format($row[4], 2, '.', ','), 20), "L", 0, 'L', 0); // CANTIDAD
        $pdf->Cell(20, 5, maxCaracter(number_format($row[7], 4, ".", ","), 20), 0, 0, 'L', 0); // PRECIO
        $pdf->Cell(20, 5, maxCaracter(number_format($row[8], 4, ".", ","), 20), "R", 0, 'L', 0); // PRECIO TOTAL
        $pdf->SetTextColor(0, 0, 0);
        if (!empty($remp) || $row[15] == 'C.P') {
            $precio_total_entradas = round($row[8], 4);
            $precio_total_salidas = 0;
        } else {
            $precio_total_entradas = round($precio_total_entradas + $row[8], 4);
        }
        //$precio_total_entradas = round($precio_total_entradas + $row[8], 4);    

        //$pdf->Cell(15, 5, maxCaracter(utf8_decode($row[9]), 20), 1, 0, 'L', 0);//DEBE
        //$pdf->Cell(45, 5, maxCaracter(utf8_decode($row[10]), 20), 1, 0, 'L', 0);//HABER
        $pdf->Cell(60, 5, "", 0, 0, '', 0);
        $pdf->Cell(20, 5, maxCaracter(number_format($row[11], 4, '.', ','), 20), "L", 0, 'L', 0); // CANTIDAD SALDOS
        $pdf->Cell(20, 5, maxCaracter(number_format($row[13], 4, ".", ","), 20), 0, 0, 'L', 0); // PRECIO UNITARIO SALDOS

        $pdf->Cell(20, 5, maxCaracter(number_format($precio_total_entradas - $precio_total_salidas, 4, ".", ","), 20), "R", 0, 'L', 0); // PRECIO TOTAL SALDOS  

    }

    if ($row[15] == 'E' || $row[15] == 'V' || $row[15] == 'NV') {
        $pdf->SetX(4);
        $pdf->Cell(10, 5, maxCaracter(utf8_decode($row[16]), 15), "L", 0, 'L', 0); // ID COMPROBANTE
        $fecha = obtenerFechaEmisionDoc($row[15], $row[16]);
        if (empty($fecha)) {
            $pdf->Cell(25, 5, maxCaracter(utf8_decode($row[2]), 28), 0, 0, 'L', 0); // FECHA
        } else {
            $pdf->Cell(25, 5, maxCaracter(utf8_decode($fecha), 28), 0, 0, 'L', 0); // FECHA
        }
        $cantidad_entrada = round(($cantidad_entrada + $row[4]), 2);
        $cantidad_total_salida = round(($cantidad_total_salida + $row[5]), 4);
        $cantidad_salida = round(($cantidad_salida + $row[6]), 2);
        $pdf->Cell(70, 5, maxCaracter(utf8_decode($row[3]), 50), 0, 0, 'L', 0); // CONCEPTO
        $pdf->Cell(60, 5, "", "L", 0, '', 0);
        $pdf->Cell(20, 5, maxCaracter(number_format($row[5], 2, '.', ','), 20), "L", 0, 'L', 0); //CANTIDAD SALIDA
        $pdf->Cell(20, 5, maxCaracter(number_format($row[7], 4, ".", ","), 20), 0, 0, 'L', 0); // PRECIO
        $pdf->Cell(20, 5, maxCaracter(number_format($row[8], 4, ".", ","), 20), "R", 0, 'L', 0); // PRECIO TOTAL
        $pdf->SetTextColor(0, 0, 0);
        $precio_total_salidas = round(($precio_total_salidas + $row[8]), 4);
        //$pdf->Cell(15, 5, maxCaracter(utf8_decode($row[9]), 20), 1, 0, 'L', 0); //DEBE
        //$pdf->Cell(1, 5, maxCaracter(utf8_decode($row[10]), 20), 1, 0, 'L', 0); //HABER
        $pdf->Cell(20, 5, maxCaracter(number_format($row[11], 4, '.', ','), 20), "L", 0, 'L', 0); //SALDO
        $pdf->Cell(20, 5, maxCaracter(number_format($row[13], 4, ".", ","), 20), 0, 0, 'L', 0); //COSTO PROMEDIO UNIT
        //$pdf->Cell(20, 5, maxCaracter(number_format(($row[13] * $row[11]), 4, ",", "."), 20), "R", 0, 'L', 0);
        $pdf->Cell(20, 5, maxCaracter(number_format($precio_total_entradas - $precio_total_salidas, 4, ".", ","), 20), "R", 0, 'L', 0);
    }

    if ($row[15] == 'AC' || $row[15] == 'AI' || $row[15] == 'AINV'  || $row[15] == 'DC') {
        $pdf->SetTextColor(256, 0, 0);
        $pdf->SetX(4);
        $pdf->Cell(10, 5, maxCaracter(utf8_decode($row[16]), 15), "L", 0, 'L', 0); // ID COMPROBANTE
        $fecha = obtenerFechaEmisionDoc($row[15], $row[16]);
        if (empty($fecha)) {
            $pdf->Cell(25, 5, maxCaracter(utf8_decode($row[2]), 28), 0, 0, 'L', 0); // FECHA
        } else {
            $pdf->Cell(25, 5, maxCaracter(utf8_decode($fecha), 28), 0, 0, 'L', 0); // FECHA
        }
        $cantidad_entrada = $cantidad_entrada - $row[5];
        //$cantidad_total_salida = $cantidad_total_salida + $row[5];
        //$pdf->Cell(5, 5, maxCaracter(utf8_decode($row[5]), 20), 1, 0, 'L', 0);
        //$cantidad_salida = $cantidad_salida + $row[6];
        $pdf->Cell(70, 5, maxCaracter(utf8_decode($row[3]), 50), 0, 0, 'L', 0); // CONCEPTO
        $pdf->Cell(20, 5, maxCaracter(number_format($row[5] * 1, 2, '.', ','), 20), "L", 0, 'L', 0); // CANTIDAD
        $pdf->Cell(20, 5, maxCaracter(number_format($row[7], 4, ".", ","), 20), 0, 0, 'L', 0); // PRECIO
        $pdf->Cell(20, 5, maxCaracter(number_format($row[8], 4, ".", ","), 20), "R", 0, 'L', 0); // PRECIO TOTAL
        $pdf->SetTextColor(0, 0, 0);
        $precio_total_entradas = round($precio_total_entradas - $row[8], 4);
        //$pdf->Cell(15, 5, maxCaracter(utf8_decode($row[9]), 20), 1, 0, 'L', 0);//DEBE
        //$pdf->Cell(45, 5, maxCaracter(utf8_decode($row[10]), 20), 1, 0, 'L', 0);//HABER
        $pdf->Cell(60, 5, "", 0, 0, '', 0);
        $pdf->Cell(20, 5, maxCaracter(number_format($row[11], 4, '.', ','), 20), "L", 0, 'L', 0); // CANTIDAD SALDOS
        $pdf->Cell(20, 5, maxCaracter(number_format($row[13], 4, ".", ","), 20), 0, 0, 'L', 0); // PRECIO UNITARIO SALDOS
        $pdf->Cell(20, 5, maxCaracter(number_format($precio_total_entradas - $precio_total_salidas, 4, ".", ","), 20), "R", 0, 'L', 0); // PRECIO TOTAL SALDOS
        $pdf->SetTextColor(0, 0, 0);
    }

    if ($row[15] == 'DV' || $row[15] == 'AV' ||  $row[15] == 'NC'  || $row[15] == 'ANV' || $row[15] == 'TEI') {
        $pdf->SetTextColor(256, 0, 0);
        $pdf->SetX(4);
        $pdf->Cell(10, 5, maxCaracter(utf8_decode($row[16]), 15), "L", 0, 'L', 0); // ID COMPROBANTE
        $pdf->Cell(25, 5, maxCaracter(utf8_decode($row[2]), 28), 0, 0, 'L', 0); // FECHA
        //$cantidad_entrada = round(($cantidad_entrada + $row[4]), 2);
        $cantidad_total_salida = round(($cantidad_total_salida - $row[4]), 4);
        $cantidad_salida = round(($cantidad_salida + $row[6]), 2);
        $pdf->Cell(70, 5, maxCaracter(utf8_decode($row[3]), 50), 0, 0, 'L', 0); // CONCEPTO
        $pdf->Cell(60, 5, "", "L", 0, '', 0);
        $pdf->Cell(20, 5, maxCaracter(number_format($row[4] * 1, 2, '.', ','), 20), "L", 0, 'L', 0); //CANTIDAD SALIDA
        $pdf->Cell(20, 5, maxCaracter(number_format($row[7], 4, ".", ","), 20), 0, 0, 'L', 0); // PRECIO
        $pdf->Cell(20, 5, maxCaracter(number_format($row[8], 4, ".", ","), 20), "R", 0, 'L', 0); // PRECIO TOTAL
        $pdf->SetTextColor(0, 0, 0);
        $precio_total_salidas = round(($precio_total_salidas - $row[8]), 4);
        //$pdf->Cell(15, 5, maxCaracter(utf8_decode($row[9]), 20), 1, 0, 'L', 0); //DEBE
        //$pdf->Cell(1, 5, maxCaracter(utf8_decode($row[10]), 20), 1, 0, 'L', 0); //HABER
        $pdf->Cell(20, 5, maxCaracter(number_format($row[11], 4, '.', ','), 20), "L", 0, 'L', 0); //SALDO
        $pdf->Cell(20, 5, maxCaracter(number_format($row[13], 4, ".", ","), 20), 0, 0, 'L', 0); //COSTO PROMEDIO UNIT
        //$pdf->Cell(20, 5, maxCaracter(number_format(($row[13] * $row[11]), 4, ",", "."), 20), "R", 0, 'L', 0);
        $pdf->Cell(20, 5, maxCaracter(number_format($precio_total_entradas - $precio_total_salidas, 4, ".", ","), 20), "R", 0, 'L', 0);
    }

    $CANT = round(($CANT + $row[11]), 2);
    $PU = round($row[13], 4);
    //$PT = round(($row[13] * $row[11]), 4);
    $PT = $precio_total_entradas - $precio_total_salidas;
    $pdf->Ln(5);
}
/* $cantidad_entrada_total = round(($cantidad_entrada + $cantidad_inicial), 2);
$cantidad_total = round(($cantidad_entrada_total - $cantidad_salida), 2);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetX(4); */
//$pdf->Cell(300, 0, utf8_decode(""), 1, 1, 'R', 0);
/* $pdf->Cell(105, 5, utf8_decode("T-O-T-A-L-E-S"), 'T', 0, 'R', 0);
$pdf->Cell(30, 5, maxCaracter(number_format($cantidad_entrada_total, 2, ',', '.'), 20), 'T', 0, 'L', 0);
$pdf->Cell(30, 5, maxCaracter((number_format($precio_total_entradas, 4, ',', '.')), 20), 'T', 0, 'C', 0);
$pdf->Cell(30, 5, maxCaracter((number_format($cantidad_total_salida, 2, ',', '.')), 20), 'T', 0, 'L', 0);
$pdf->Cell(30, 5, maxCaracter((number_format($precio_total_salidas, 4, ',', '.')), 20), 'T', 0, 'C', 0);
$pdf->Cell(20, 5, maxCaracter((number_format(($cantidad_entrada_total - $cantidad_total_salida), 2, ',', '.')), 20), 'T', 0, 'L', 0);
$pdf->Cell(20, 5, maxCaracter((number_format($PU, 4, ',', '.')), 20), 'T', 0, 'L', 0);
$pdf->Cell(20, 5, maxCaracter((number_format($PT, 4, ',', '.')), 20), 'T', 0, 'L', 0); */
//$pdf->Cell(15, 5, maxCaracter((number_format($precio_total_total, 2, ',', '.')), 20), 0, 0, 'C', 0); 

$pdf->SetTextColor(0, 0, 0);
$pdf->SetX(4);
//$pdf->Cell(300, 0, utf8_decode(""), 1, 1, 'R', 0);
$pdf->Cell(105, 5, "", 'T', 0, 'R', 0);
$pdf->Cell(30, 5, "", 'T', 0, 'L', 0);
$pdf->Cell(30, 5, "", 'T', 0, 'C', 0);
$pdf->Cell(30, 5, "", 'T', 0, 'L', 0);
$pdf->Cell(30, 5, "", 'T', 0, 'C', 0);
$pdf->Cell(20, 5, "", 'T', 0, 'L', 0);
$pdf->Cell(20, 5, "", 'T', 0, 'L', 0);
$pdf->Cell(20, 5, "", 'T', 0, 'L', 0);

$pdf->Output();

function obtenerFechaEmisionDoc($documento, $comprobante)
{
    switch ($documento) {
        case 'INV':
            $sql = "select fecha_actual from inventario where comprobante='$comprobante'";
            $res = pg_query($sql);
            $row = pg_fetch_row($res);
            return $row[0];
        case 'C':
            $sql = "select fecha_emision from factura_compra where comprobante='$comprobante'";
            $res = pg_query($sql);
            $row = pg_fetch_row($res);
            return $row[0];
        case 'DC':
            $sql = "select num_autorizacion_sri from devolucion_compra where comprobante='$comprobante'";
            $res = pg_query($sql);
            $row = pg_fetch_row($res);
            return $row[0];
        default:
            return false;
    }
}

function obtenerSaldoInicial($codprod, $fechahasta)
{
    global $conpuntoresult;
    $ffin = date("d-m-Y", strtotime($fechahasta . "- 1 days"));
    $sql = "select fecha_transaccion from kardex_valorizado order by id_kardex asc limit 1";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    $fini = $rows[0]["fecha_transaccion"];

    if (strtotime($ffin) < strtotime($fini)) {
        /*$ffinaux = $ffin;
        $ffin = $fini;
        $fini = $ffinaux;*/
        $fini==$ffin;
    }


    $sql = "
    select*from kardex_valorizado
    where cod_productos=$codprod
    and fecha_transaccion between '$fini' and '$ffin'
    AND id_empresa='$conpuntoresult';
    ";

    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        $rows = [];
    }

    $precio_total_entradas = 0;
    foreach ($rows as $row) {
        if ($row["compra_venta"] == 'I' || $row["compra_venta"] == 'INV' || $row["compra_venta"] == 'INVS'  || $row["compra_venta"] == 'C' || $row["compra_venta"] == 'C.P' || $row["compra_venta"] == 'A') {
            $remp = strpos($row["concepto"], '- REMP -');

            if (!empty($remp) || $row["compra_venta"] == 'C.P') {
                $precio_total_entradas = $row["costo_promedio"];
            } else {
                $precio_total_entradas = $precio_total_entradas + $row["costo_promedio"];
            }
        }
        if ($row["compra_venta"] == 'E' || $row["compra_venta"] == 'V' || $row["compra_venta"] == 'NV') {
            $precio_total_entradas = $precio_total_entradas - $row["costo_promedio"];
        }
        if ($row["compra_venta"] == 'AC' || $row["compra_venta"] == 'AI' || $row["compra_venta"] == 'AINV'  || $row["compra_venta"] == 'DC') {
            $precio_total_entradas = $precio_total_entradas + $row["costo_promedio"];
        }
        if ($row["compra_venta"] == 'DV' || $row["compra_venta"] == 'AV' ||  $row["compra_venta"] == 'NC'  || $row["compra_venta"] == 'ANV' || $row["compra_venta"] == 'TEI') {
            $precio_total_entradas = $precio_total_entradas + $row["costo_promedio"];
        }
    }
    return $precio_total_entradas;
}
