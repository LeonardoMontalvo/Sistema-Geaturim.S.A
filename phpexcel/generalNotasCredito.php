<?php

date_default_timezone_set('America/Guayaquil');
require_once "PHPExcel.php";

//VARIABLES DE PHP
$objPHPExcel = new PHPExcel();
$Archivo = "reporte_general_notas_credito.xls";

include '../procesos/base.php';
include '../procesos/funciones.php';
session_start();
conectarse();


// Propiedades de archivo Excel
$objPHPExcel->getProperties()->setCreator("P&S Systems")
    ->setLastModifiedBy("P&S Systems")
    ->setTitle("Reporte XLS")
    ->setSubject("REPORTE DE GENERAL NOTAS DE CRÉDITO")
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
//////////////////////CABECERA DE LA CONSULTA
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue("B2", 'RESUMEN GENERAL DE NOTAS DE CRÉDITO');
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
    $condciente = " and dv.id_cliente=" . $_GET["id_cliente"];
}

$consulta1 = pg_query("SELECT 
dv.fecha_actual, 
hora_actual, '', 
tipo_comprobante, 
tarifa0, tarifa12, 
iva_venta, descuento_venta,
total_venta, identificacion, 
nombres_cli, num_serie, num_nota_credito
FROM devolucion_venta dv, clientes c, usuario u 
WHERE dv.id_cliente=c.id_cliente and u.id_usuario=dv.id_usuario
and dv.id_empresa='$_GET[id]' 
and dv.fecha_actual between '$_GET[inicio]' and '$_GET[fin]'  
$condciente
ORDER BY dv.id_devolucion_venta asc;");
$contador = pg_num_rows($consulta1);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue("B" . $y, 'Comprobante')
    ->setCellValue("C" . $y, 'Nro. Factura')
    ->setCellValue("D" . $y, 'RUC. C.')
    ->setCellValue("E" . $y, 'Nombre C.')
    ->setCellValue("F" . $y, 'Subtotal')
    ->setCellValue("G" . $y, 'Descuento')
    ->setCellValue("H" . $y, 'Tarifa 0%')
    ->setCellValue("I" . $y, 'Tarifa IVA')
    ->setCellValue("J" . $y, 'Iva ..%')
    ->setCellValue("K" . $y, 'Total')
    ->setCellValue("L" . $y, 'Tipo Docu.')
    ->setCellValue("M" . $y, 'Fecha');
$objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":P" . $y)->getFont()->setBold(true)->setName('Verdana')->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":P" . $y)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$repetido = 1;
$styleArray = array(
    'borders' => array(
        'bottom' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
        ),
    ),
);
$objPHPExcel->getActiveSheet()->getStyle('B' . $y . ':P' . $y)->applyFromArray($styleArray);
unset($styleArray);
$y++;
if ($contador > 0) {
    while ($row1 = pg_fetch_row($consulta1)) {
        //$pdf->SetTextColor(0,0,0);                                                 
        //$pdf->SetX(1);
        $sub = $sub + ($row1[8] - $row1[6] + $row1[7]);
        $desc = $desc + $row1[7];
        $ivaT = $ivaT + $row1[6];
        $total = $total + $row1[8];
        $t0 = $t0 + $row1[4];
        //$t12 = $t12 + $row1[7];
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValueExplicit("B" . $y, utf8_decode(substr($row1[12], 0, 9)), PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit("C" . $y, utf8_decode(substr($row1[11], 0, 9)), PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit("D" . $y, utf8_decode(substr($row1[9], 0, 9)), PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue("E" . $y, utf8_decode($row1[10]))
            ->setCellValue("F" . $y, utf8_decode(round($row1[8] - $row1[6] + $row1[7], 2, PHP_ROUND_HALF_EVEN)))
            ->setCellValue("G" . $y, utf8_decode(round($row1[7], 2, PHP_ROUND_HALF_EVEN)))
            ->setCellValue("H" . $y, utf8_decode(round($row1[4], 2, PHP_ROUND_HALF_EVEN)))
            ->setCellValue("I" . $y, utf8_decode(round($row1[5], 2, PHP_ROUND_HALF_EVEN)))
            ->setCellValue("J" . $y, utf8_decode(round($row1[6], 2, PHP_ROUND_HALF_EVEN)))
            ->setCellValue("K" . $y, $row1[8])
            ->setCellValue("L" . $y, $row1[3])
            ->setCellValue("M" . $y, $row1[0]);
        $objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":L" . $y)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $y = $y + 1;
    }
    $t12 = $sub - $desc;
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("D" . $y, utf8_decode(""))
        ->setCellValue("E" . $y, utf8_decode("Totales"))
        ->setCellValue("F" . $y, maxCaracter((number_format($sub, 2, ',', '.')), 20))
        ->setCellValue("G" . $y, maxCaracter((number_format($desc, 2, ',', '.')), 20))
        ->setCellValue("H" . $y, maxCaracter((number_format($t0, 2, ',', '.')), 20))
        ->setCellValue("I" . $y, maxCaracter((number_format($t12, 2, ',', '.')), 20))
        ->setCellValue("J" . $y, maxCaracter((number_format($ivaT, 2, ',', '.')), 20))
        ->setCellValue("K" . $y, maxCaracter((number_format($total, 2, ',', '.')), 20));
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
