<?php

date_default_timezone_set('America/Guayaquil');
require_once "PHPExcel.php";

//VARIABLES DE PHP
$objPHPExcel = new PHPExcel();
$Archivo = "reporte_gasto_acumulado.xls";

include '../procesos/base.php';
include '../procesos/funciones.php';
session_start();
conectarse();


// Propiedades de archivo Excel
$objPHPExcel->getProperties()->setCreator("P&S Systems")
        ->setLastModifiedBy("P&S Systems")
        ->setTitle("Reporte XLS")
        ->setSubject("REPORTE DE GASTO ACUMULADO")
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
        ->setCellValue("B2", 'GASTO ACUMULADO');
$objPHPExcel->getActiveSheet()
        ->getStyle('B2:J2')->getAlignment()
        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('B2:J2');

$objPHPExcel->getActiveSheet()
        ->getStyle("B2:J2")
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
$objDrawing->setPath('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"]);           // 
$objDrawing->setWidth(160);                 // sets the image 
$objDrawing->setHeight(60);
$objDrawing->setCoordinates('J2');    // pins the top-left corner 
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
$tot=0;

    $consulta1 = pg_query("SELECT DISTINCT(id_factura_venta) FROM gastos where fecha_actual between '$_GET[inicio]' and '$_GET[fin]' order by id_factura_venta asc;");
    $contador = pg_num_rows($consulta1);
    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue("B" . $y, 'NRO. FACTURA')
                        ->setCellValue("C" . $y, 'F.FACTURA')
                        ->setCellValue("D" . $y, 'CLIENTE')
                        ->setCellValue("E" . $y, 'T. VENTA')
                        ->setCellValue("F" . $y, 'DESCRIPCIÓN')
                        ->setCellValue("G" . $y, 'F.PAGO')
                        ->setCellValue("H" . $y, 'V.PAGO')
                        ->setCellValue("I" . $y, 'SALDO')
                        ->setCellValue("J" . $y, 'ACUMULADO');
                $objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":J" . $y)->getFont()->setBold(true)->setName('Verdana')->setSize(10);
                $objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":J" . $y)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $repetido = 1;
                $styleArray = array(
                    'borders' => array(
                        'bottom' => array(
                            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
                        ),
                    ),
                );
                $objPHPExcel->getActiveSheet()->getStyle('B' . $y . ':J' . $y)->applyFromArray($styleArray);
                unset($styleArray);
                $y++;
    if ($contador > 0) {
        while ($row = pg_fetch_row($consulta1)) {
            if ($repetido == 0) {
                $sql1=pg_query("select id_factura_venta,num_factura,nombres_cli,total_venta,fecha_actual from factura_venta,clientes where factura_venta.id_cliente=clientes.id_cliente and id_factura_venta='$row[0]'");
                 while($row1=pg_fetch_row($sql1)) {
                     $id_fac=$row1[0];
                        $num_fac=$row1[1];
                        $total=$row1[3];
                        $fecha=$row1[4];
                     $cliente=$row1[2];
                    }
                    $sql2=pg_query("SELECT * FROM gastos where fecha_actual between '$_GET[inicio]' and '$_GET[fin]' and id_factura_venta='$id_fac' order by id_factura_venta asc");
                    while($row2=pg_fetch_row($sql2)) {
                        $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue("B" . $y, substr($num_fac,8,30))
                        ->setCellValue("C" . $y, utf8_decode($fecha))
                        ->setCellValue("D" . $y, maxCaracter(utf8_decode($cliente),18))
                        ->setCellValue("E" . $y, utf8_decode($total))
                        ->setCellValue("F" . $y, maxCaracter(utf8_decode($row2[6]),20))
                        ->setCellValue("G" . $y, utf8_decode($row2[4]))
                        ->setCellValue("H" . $y, utf8_decode($row2[7]))
                        ->setCellValue("I" . $y, utf8_decode($row2[8]))
                        ->setCellValue("J" . $y, utf8_decode($row2[9]));
                        $tot=$tot+$total;
                        $acumlado=$acumlado+$row2[7];
                    }  
            }                                    
            $objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":J" . $y)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $y = $y + 1;
   
        }
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("C" . $y, utf8_decode(""))
        ->setCellValue("D" . $y, utf8_decode("Totales Venta"))
        ->setCellValue("E" . $y, maxCaracter((number_format(($tot),2,',','.')),20))
        ->setCellValue("F" . $y, utf8_decode("Totales Gastos"))
        ->setCellValue("G" . $y, maxCaracter((number_format(($acumlado),2,',','.')),20));
        $objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":J" . $y)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
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

