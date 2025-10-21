<?php

date_default_timezone_set('America/Guayaquil');
require_once "PHPExcel.php";

//VARIABLES DE PHP
$objPHPExcel = new PHPExcel();
$Archivo = "cobros_realizados_internos.xls";

include '../procesos/base.php';
include '../procesos/funciones.php';
session_start();
conectarse();

// Propiedades de archivo Excel
$objPHPExcel->getProperties()->setCreator("P&S Systems")
        ->setLastModifiedBy("P&S Systems")
        ->setTitle("Reporte XLS")
        ->setSubject("COBROS REALIZADOS INTERNOS EXCEL")
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

//////////////////////CABECERA DE LA CONSULTA
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("B2", 'COBROS REALIZADOS INTERNOS POR CLIENTE');
$objPHPExcel->getActiveSheet()
        ->getStyle('B2:L2')->getAlignment()
        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('B2:L2');

$objPHPExcel->getActiveSheet()
        ->getStyle("B2:L2")
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
/////////////////////////
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("B5", 'Desde:');
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('B5:B5');

$objPHPExcel->getActiveSheet()
        ->getStyle("B5:B5")
        ->getFont()
        ->setBold(false)
        ->setName('Verdana')
        ->setSize(10);
//////////////////////////
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("C5", $_GET['inicio']);
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('C5:C5');

$objPHPExcel->getActiveSheet()
        ->getStyle("C5:C5")
        ->getFont()
        ->setBold(false)
        ->setName('Verdana')
        ->setSize(10);
//////////////////////////
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("G5", 'HASTA:');
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('G5:G5');

$objPHPExcel->getActiveSheet()
        ->getStyle("G5:G5")
        ->getFont()
        ->setBold(false)
        ->setName('Verdana')
        ->setSize(10);
//////////////////////////
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("H5", $_GET['fin']);
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('H5:H5');

$objPHPExcel->getActiveSheet()
        ->getStyle("H5:H5")
        ->getFont()
        ->setBold(false)
        ->setName('Verdana')
        ->setSize(10);
//////////////////////////
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('PHPExcel logo');
$objDrawing->setDescription('PHPExcel logo');
$objDrawing->setPath('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"]);       // 
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
$objPHPExcel->getActiveSheet()->getStyle('B5:L5')->applyFromArray($styleArray);
unset($styleArray);
//////////////////////////////////////////////////////////
    $total = 0;
    $y = 7;
    $sub=0;
    $desc=0;
    $ivaT=0;
    $repetido=0;   
    $contador=0;     
    $consulta=pg_query("select * from clientes where identificacion= '$_GET[id]'");
while ($row = pg_fetch_row($consulta)) {
            $total=0;
        $sub=0;
        $saldo=0;
        $repetido=0; 
        $contador=0;   
        $num_fact=0; 
        $id_clienteid=$row[0];
    $consulta1 = pg_query(" select comprobante,valor_pagado,fecha_factura,tipo_factura,num_factura,forma_pago,saldo_factura from pagos_cobrar where num_factura='$row1[5]' and  fecha_actual between '$_GET[inicio]' and '$_GET[fin]' and id_cliente= '$id_clienteid' ");
    $contador = pg_num_rows($consulta1);
    if ($contador > 0) {
        while ($row1 = pg_fetch_row($consulta1)) {
            if ($repetido == 0) {
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValueExplicit("B" . $y, 'RUC/CI: ')
                        ->setCellValueExplicit("C" . $y, $row1[12])
                        ->setCellValueExplicit("F" . $y, $row1[13], PHPExcel_Cell_DataType::TYPE_STRING);
                $y++;
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue("B" . $y, 'Sección: ')
                        ->setCellValue("C" . $y, $row1[11]);
                $y++;
                $y++;
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
                        ->setCellValue("L" . $y, 'Tipo Pago');
                $objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":L" . $y)->getFont()->setBold(true)->setName('Verdana')->setSize(10);
                $objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":L" . $y)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $repetido = 1;
                $styleArray = array(
                    'borders' => array(
                        'bottom' => array(
                            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
                        ),
                    ),
                );
                $objPHPExcel->getActiveSheet()->getStyle('B' . $y . ':L' . $y)->applyFromArray($styleArray);
                unset($styleArray);
                $y++;
            }
            $sub = $sub + ($row1[10] - $row1[8] - $row1[9]);
            $desc = $desc + $row1[9];
            $ivaT = $ivaT + $row1[8];
            $total = $total + $row1[10];
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue("B" . $y, $row1[14])
                    ->setCellValue("C" . $y, $row1[1])
                    ->setCellValue("D" . $y, substr($row1[0], 8))
                    ->setCellValue("E" . $y, utf8_decode(truncateFloat(round($row1[10]-$row1[8]-$row1[9],2, PHP_ROUND_HALF_EVEN),2)))
                    ->setCellValue("F" . $y, utf8_decode(truncateFloat(round($row1[9],2, PHP_ROUND_HALF_EVEN),2)))
                    ->setCellValue("G" . $y, utf8_decode(truncateFloat(round($row1[6],2, PHP_ROUND_HALF_EVEN),2)))
                    ->setCellValue("H" . $y, utf8_decode(truncateFloat(round($row1[7],2, PHP_ROUND_HALF_EVEN),2)))
                    ->setCellValue("I" . $y, utf8_decode(truncateFloat(round($row1[8],2, PHP_ROUND_HALF_EVEN),2)))
                    ->setCellValue("J" . $y, utf8_decode(truncateFloat(round($row1[10],2, PHP_ROUND_HALF_EVEN),2)))
                    ->setCellValue("K" . $y, $row1[3])
                    ->setCellValue("L" . $y, $row1[5]);
            $objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":L" . $y)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $y = $y + 1;
        }
    }
}
$styleArray = array(
    'borders' => array(
        'bottom' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
        ),
    ),
);
$objPHPExcel->getActiveSheet()->getStyle('B' . ($y - 1) . ':L' . ($y - 1))->applyFromArray($styleArray);
unset($styleArray);
//$y=$y+1;  
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValueExplicit("B" . $y, 'Totales:')
        ->setCellValueExplicit("E" . $y, (number_format($sub, 2, ',', '.')), PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("F" . $y, (number_format($desc, 2, ',', '.')), PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("I" . $y, (number_format($ivaT, 2, ',', '.')), PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("J" . $y, (number_format($total, 2, ',', '.')), PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":J" . $y)->getFont()->setBold(true)->setName('Verdana')->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":J" . $y)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$y++;
$y++;
//////////////////////////////////////////////////////////
//DATOS DE LA SALIDA DEL EXCEL
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="' . $Archivo . '"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

exit;
?>

