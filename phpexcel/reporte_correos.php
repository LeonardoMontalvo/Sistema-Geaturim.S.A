<?php

date_default_timezone_set('America/Guayaquil');
require_once "PHPExcel.php";

//VARIABLES DE PHP
$objPHPExcel = new PHPExcel();
$Archivo = "reporte_clientes_correos.xls";

include '../procesos/base.php';
session_start();
conectarse();


// Propiedades de archivo Excel
$objPHPExcel->getProperties()->setCreator("P&S Systems")
        ->setLastModifiedBy("P&S Systems")
        ->setTitle("Reporte XLS")
        ->setSubject("Reporte de correos de clientes")
        ->setDescription("")
        ->setKeywords("")
        ->setCategory("");


//PROPIEDADES DEL  LA CELDA
$objPHPExcel->getDefaultStyle()->getFont()->setName('Verdana');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getRowDimension('6')->setRowHeight(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(50);

//CABECERA DE LA CONSULTA
$y = 6;
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("B" . $y, 'Numero')
        ->setCellValue("C" . $y, 'Nombre')
        ->setCellValue("D" . $y, 'Correo');


$objPHPExcel->getActiveSheet()
        ->getStyle('B6:D6')
        ->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFEEEEEE');

$objPHPExcel->getActiveSheet()
        ->getStyle('B6:D6')->getAlignment()
        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$borders = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => 'FF000000'),
        )
    ),
);

$objPHPExcel->getActiveSheet()
        ->getStyle('B6:D6')
        ->applyFromArray($borders);

//////////////////////CABECERA DE LA CONSULTA
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("B2", 'REPORTE DE CORREOS DE CLIENTES');
$objPHPExcel->getActiveSheet()
        ->getStyle('B2:D2')->getAlignment()
        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('B2:D2');

$objPHPExcel->getActiveSheet()
        ->getStyle("B2:D2")
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
        ->setSize(12);
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
        ->setSize(12);
/////////////////////////
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('PHPExcel logo');
$objDrawing->setDescription('PHPExcel logo');
$objDrawing->setPath('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"]);           // 
$objDrawing->setHeight(70);                 // sets the image 
$objDrawing->setCoordinates('H2');    // pins the top-left corner 
$objDrawing->setOffsetX(0);                // pins the top left 
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

//if ($_GET['categoria'] == "") {
//    $sql = pg_query("select p.codigo,p.cod_barras,p.articulo,p.iva_minorista,p.iva_mayorista,p.stock,c.nombre_categoria,p.marca from productos p, categoria c");
//}
//if ($_GET['categoria'] != "") {
    $sql = pg_query("select id_cliente, nombres_cli, correo from clientes order by id_cliente");
//}

while ($row = pg_fetch_row($sql)) {
    $y++;
    //BORDE DE LA CELDA
    $objPHPExcel->setActiveSheetIndex(0)
            ->getStyle('B' . $y . ":D" . $y)
            ->applyFromArray($borders);
    //MOSTRAMOS LOS VALORES
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("B" . $y, $row[0])
            ->setCellValue("C" . $y, $row[1])
            ->setCellValue("D" . $y, $row[2]);

    $objPHPExcel->getActiveSheet()
            ->getStyle('B' . $y)->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()
            ->getStyle('C' . $y)->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()
            ->getStyle('D' . $y)->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
}

//DATOS DE LA SALIDA DEL EXCEL
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="' . $Archivo . '"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

exit;
?>