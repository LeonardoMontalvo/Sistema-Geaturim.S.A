<?php

date_default_timezone_set('America/Guayaquil');
require_once "PHPExcel.php";

//VARIABLES DE PHP
$objPHPExcel = new PHPExcel();
$Archivo = "reporte_general_facturas_ventas.xls";

include '../procesos/base.php';
include '../procesos/funciones.php';
session_start();
conectarse();


// Propiedades de archivo Excel
$objPHPExcel->getProperties()->setCreator("P&S Systems")
    ->setLastModifiedBy("P&S Systems")
    ->setTitle("Reporte XLS")
    ->setSubject("REPORTE DE GENERAL FACTURAS DE VENTAS")
    ->setDescription("")
    ->setKeywords("")
    ->setCategory("");


//PROPIEDADES DEL  LA CELDA
$objPHPExcel->getDefaultStyle()->getFont()->setName('Verdana');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getRowDimension('6')->setRowHeight(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(35);
$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(25);
//////////////////////CABECERA DE LA CONSULTA
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue("B2", 'RESUMEN GENERAL DE FACTURAS');
$objPHPExcel->getActiveSheet()
    ->getStyle('B2:M2')->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->setActiveSheetIndex(0)
    ->mergeCells('B2:M2');

$objPHPExcel->getActiveSheet()
    ->getStyle("B2:M2")
    ->getFont()
    ->setBold(true)
    ->setName('Verdana')
    ->setSize(18);
//////////////////////////
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue("B4", 'Empresa: ' . $_SESSION['empresa'] . '');
$objPHPExcel->setActiveSheetIndex(0)
    ->mergeCells('B4:C4');

$objPHPExcel->getActiveSheet()
    ->getStyle("B4:C4")
    ->getFont()
    ->setBold(false)
    ->setName('Verdana')
    ->setSize(10);
//////////////////////////
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue("D4", 'Propietario: ' . $_SESSION['propietario'] . '');
$objPHPExcel->setActiveSheetIndex(0)
    ->mergeCells('D4:E4');

$objPHPExcel->getActiveSheet()
    ->getStyle("D4:E4")
    ->getFont()
    ->setBold(false)
    ->setName('Verdana')
    ->setSize(10);

$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('PHPExcel logo');
$objDrawing->setDescription('PHPExcel logo');
$objDrawing->setPath('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"]);
$objDrawing->setWidth(160);                 // sets the image 
$objDrawing->setHeight(60);
$objDrawing->setCoordinates('L2');    // pins the top-left corner 
$objDrawing->setOffsetX(10);                // pins the top left 
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

//////////////////////////////////////////////////////////
$styleArray = array(
    'borders' => array(
        'bottom' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
        ),
    ),
);
$objPHPExcel->getActiveSheet()->getStyle('B5:H5')->applyFromArray($styleArray);
unset($styleArray);
//////////////////////////////////////////////////////////
$total = 0;
$sub = 0;
$desc = 0;
$ivaT = 0;
$repetido = 0;
$y = 7;
$tot = 0;
$t0 = 0;
$t12 = 0;
$costov = 0;

$condciente = "";
if ($_GET["id_cliente"]) {
    $condciente = " and factura_venta.id_cliente=" . $_GET["id_cliente"];
}

$consulta1 = pg_query("select num_factura,
factura_venta.fecha_actual,
hora_actual,
fecha_cancelacion,
tipo_precio,
forma_pago,
tarifa0,
tarifa12,
iva_venta,
descuento_venta,
total_venta,
identificacion,
nombres_cli,
nombre_empresa,
id_factura_venta,
factura_venta.estado,
num_autorizacion,
fecha_autorizacion 
from factura_venta,
clientes,
empresa,
usuario where factura_venta.id_cliente=clientes.id_cliente  
and usuario.id_usuario=factura_venta.id_usuario 
and factura_venta.id_empresa='$_GET[id]' 
and factura_venta.fecha_actual between '$_GET[inicio]' and '$_GET[fin]'  
and empresa.id_empresa='$_GET[id]' 
$condciente
order by factura_venta.id_factura_venta asc");
$contador = pg_num_rows($consulta1);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue("B" . $y, 'Comprobante')
    ->setCellValue("C" . $y, 'Fecha')
    ->setCellValue("D" . $y, 'Nro Factura')
    ->setCellValue("E" . $y, 'Subtotal')
    ->setCellValue("F" . $y, 'Descuento')
    ->setCellValue("G" . $y, 'Tarifa 0%')
    ->setCellValue("H" . $y, 'Tarifa 12%')
    ->setCellValue("I" . $y, 'Iva 12%')
    ->setCellValue("J" . $y, 'Total')
    ->setCellValue("K" . $y, 'Fecha Pago')
    ->setCellValue("L" . $y, 'Tipo Pago')
    ->setCellValue("M" . $y, 'Estado')
    ->setCellValue("N" . $y, 'Costo Venta')
    ->setCellValue("O" . $y, 'Cèdula')
    ->setCellValue("P" . $y, 'Nombres')
    ->setCellValue("Q" . $y, 'Número Autorización')
    ->setCellValue("R" . $y, 'Fecha Autorización');
$objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":R" . $y)->getFont()->setBold(true)->setName('Verdana')->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":R" . $y)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$repetido = 1;
$styleArray = array(
    'borders' => array(
        'bottom' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
        ),
    ),
);
$objPHPExcel->getActiveSheet()->getStyle('B' . $y . ':R' . $y)->applyFromArray($styleArray);
unset($styleArray);
$y++;
if ($contador > 0) {
    while ($row1 = pg_fetch_row($consulta1)) {
        if ($row1[15] == "Activo") {
            //$pdf->SetTextColor(0,0,0);                                                 
            //$pdf->SetX(1);
            $sub = $sub + ($row1[6] + $row1[7]);
            $desc = $desc + $row1[9];
            $ivaT = $ivaT + $row1[8];
            $total = $total + $row1[10];
            $t0 = $t0 + $row1[6];
            $t12 = $t12 + $row1[7];
            $costov += obtenerCostoVenta($row1[14]);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B" . $y, utf8_decode($row1[14]))
                ->setCellValue("C" . $y, utf8_decode($row1[1]))
                //->setCellValue("D" . $y, utf8_decode(substr($row1[0], 8)))
                ->setCellValueExplicit("D" . $y, utf8_decode(substr($row1[0], 0, 9)), PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue("E" . $y, utf8_decode(round($row1[6] + $row1[7], 2, PHP_ROUND_HALF_EVEN)))
                ->setCellValue("F" . $y, utf8_decode(round($row1[9], 2, PHP_ROUND_HALF_EVEN)))
                ->setCellValue("G" . $y, utf8_decode(round($row1[6], 2, PHP_ROUND_HALF_EVEN)))
                ->setCellValue("H" . $y, utf8_decode(round($row1[7], 2, PHP_ROUND_HALF_EVEN)))
                ->setCellValue("I" . $y, utf8_decode(round($row1[8], 2, PHP_ROUND_HALF_EVEN)))
                ->setCellValue("J" . $y, utf8_decode(round($row1[10], 2, PHP_ROUND_HALF_EVEN)))
                ->setCellValue("K" . $y, $row1[3])
                ->setCellValue("L" . $y, $row1[5])
                ->setCellValue("M" . $y, utf8_decode("VALIDA"))
                ->setCellValue("N" . $y, obtenerCostoVenta($row1[14]))
                ->setCellValue("O" . $y, $row1[11])
                ->setCellValue("P" . $y, $row1[12])
                ->setCellValue("Q" . $y, "'" . $row1[16])
                ->setCellValue("R" . $y, explode(" ", $row1[17])[0]);
            $objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":L" . $y)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $y = $y + 1;
        } /* else {
            if ($row1[15] == "Pasivo") {
                //$pdf->SetTextColor(208,17,52);
                //$pdf->SetX(1); 

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue("B" . $y, utf8_decode($row1[14]))
                    ->setCellValue("C" . $y, utf8_decode($row1[1]))
                    ->setCellValueExplicit("D" . $y, utf8_decode(substr($row1[0], 0, 9)), PHPExcel_Cell_DataType::TYPE_STRING)
                    ->setCellValue("E" . $y, utf8_decode(round($row1[10] - $row1[8] + $row1[9], 2, PHP_ROUND_HALF_EVEN)))
                    ->setCellValue("F" . $y, utf8_decode(round($row1[9], 2, PHP_ROUND_HALF_EVEN)))
                    ->setCellValue("G" . $y, utf8_decode(round($row1[6], 2, PHP_ROUND_HALF_EVEN)))
                    ->setCellValue("H" . $y, utf8_decode(round($row1[7], 2, PHP_ROUND_HALF_EVEN)))
                    ->setCellValue("I" . $y, utf8_decode(round($row1[8], 2, PHP_ROUND_HALF_EVEN)))
                    ->setCellValue("J" . $y, utf8_decode(round($row1[10], 2, PHP_ROUND_HALF_EVEN)))
                    ->setCellValue("K" . $y, $row1[3], 0, 0, 'C', 0)
                    ->setCellValue("L" . $y, $row1[5], 0, 0, 'C', 0)
                    ->setCellValue("M" . $y, utf8_decode("ANULADA"))
                    ->setCellValue("N" . $y, obtenerCostoVenta($row1[14]))
                    ->setCellValue("O" . $y, $row1[11])
                    ->setCellValue("P" . $y, $row1[12])
                    ->setCellValue("Q" . $y, "'" .$row1[16])
                    ->setCellValue("R" . $y, explode(" ", $row1[17])[0]);
                $objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":L" . $y)->getFont()->getColor()->setRGB('6F6F6F');
            }
        } */
        //$objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":L" . $y)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //$y = $y + 1;
    }
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("C" . $y, utf8_decode(""))
        ->setCellValue("D" . $y, utf8_decode("Totales"))
        ->setCellValue("E" . $y, maxCaracter((number_format($sub, 2, ',', '.')), 20))
        ->setCellValue("F" . $y, maxCaracter((number_format($desc, 2, ',', '.')), 20))
        ->setCellValue("G" . $y, maxCaracter((number_format($t0, 2, ',', '.')), 20))
        ->setCellValue("H" . $y, maxCaracter((number_format($t12, 2, ',', '.')), 20))
        ->setCellValue("I" . $y, maxCaracter((number_format($ivaT, 2, ',', '.')), 20))
        ->setCellValue("J" . $y, maxCaracter((number_format($total, 2, ',', '.')), 20))
        ->setCellValue("N" . $y, maxCaracter((number_format($costov, 2, ',', '.')), 20));
    $objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":N" . $y)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
}

$styleArray = array(
    'borders' => array(
        'bottom' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
        ),
    ),
);
$objPHPExcel->getActiveSheet()->getStyle('B' . ($y - 1) . ':P' . ($y - 1))->applyFromArray($styleArray);
unset($styleArray);

//////////////////////////////////////////////////////////
//DATOS DE LA SALIDA DEL EXCEL
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="' . $Archivo . '"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

function obtenerCostoVenta($idfacturaventa)
{
    $sql = "select sum(costo_prom_unitario::numeric*salida::numeric) from kardex_valorizado
    where compra_venta='V'
    and comprobante::integer=$idfacturaventa";
    $res = pg_query($sql);
    $row = pg_fetch_row($res);
    if (empty($row)) {
        return 0;
    }
    return $row[0];
}
