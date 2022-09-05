<?php

date_default_timezone_set('America/Guayaquil');
require_once "PHPExcel.php";

//VARIABLES DE PHP
$objPHPExcel = new PHPExcel();
$Archivo = "reporte_inventario.xls";

include '../procesos/base.php';
include '../procesos/funciones.php';
session_start();
conectarse();


// Propiedades de archivo Excel
$objPHPExcel->getProperties()->setCreator("P&S Systems")
        ->setLastModifiedBy("P&S Systems")
        ->setTitle("Reporte XLS")
        ->setSubject("RESUMEN DE FACTURAS COMPRAS GENERAL")
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
        ->setCellValue("B2", 'INVENTARIO');
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
$objDrawing->setPath('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"]);        // 
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

    $consulta1 = pg_query("SELECT P.codigo, P.articulo, P.precio_compra, P.iva_minorista, P.iva_mayorista, P.iva_negocio, P.stock FROM inventario I, detalle_inventario D, productos P where I.id_inventario =  D.id_inventario and D.cod_productos = P.cod_productos and I.fecha_actual between '$_GET[inicio]' and '$_GET[fin]'");
    $contador = pg_num_rows($consulta1);
    if ($contador > 0) {
        while ($row1 = pg_fetch_row($consulta1)) {
            if ($repetido == 0) {
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue("B" . $y, 'CÓDIGO')
                        ->setCellValue("C" . $y, 'PRODUCTO')
                        ->setCellValue("D" . $y, 'P. COSTO')
                        ->setCellValue("E" . $y, 'P. MINORISTA')
                        ->setCellValue("F" . $y, 'P. MAYORISTA')
                        ->setCellValue("G" . $y, 'P. NEGOCIO')
                        ->setCellValue("H" . $y, 'STOCK');
                $objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":H" . $y)->getFont()->setBold(true)->setName('Verdana')->setSize(10);
                $objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":H" . $y)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $repetido = 1;
                $styleArray = array(
                    'borders' => array(
                        'bottom' => array(
                            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
                        ),
                    ),
                );
                $objPHPExcel->getActiveSheet()->getStyle('B' . $y . ':H' . $y)->applyFromArray($styleArray);
                unset($styleArray);
                $y++;
            }

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue("B" . $y, $row1[0])
                    ->setCellValue("C" . $y, $row1[1])
                    ->setCellValue("D" . $y, $row1[2])
                    ->setCellValue("E" . $y, $row1[3])
                    ->setCellValue("F" . $y, $row1[4])
                    ->setCellValue("G" . $y, $row1[5])
                    ->setCellValue("H" . $y, $row1[6]);
            $objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":H" . $y)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $y = $y + 1;
        }
    }

$styleArray = array(
    'borders' => array(
        'bottom' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
        ),
    ),
);
$objPHPExcel->getActiveSheet()->getStyle('B' . ($y - 1) . ':H' . ($y - 1))->applyFromArray($styleArray);
unset($styleArray);

//////////////////////////////////////////////////////////
//DATOS DE LA SALIDA DEL EXCEL
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="' . $Archivo . '"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>

