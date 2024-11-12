<?php

date_default_timezone_set('America/Guayaquil');
require_once "PHPExcel.php";

//VARIABLES DE PHP
$objPHPExcel = new PHPExcel();
$Archivo = "total_gastos.xls";

include '../procesos/base.php';
include '../procesos/funciones.php';
include 'util.php';
session_start();
conectarse();

$fechai = $_GET['inicio'];
$fechaf = $_GET['fin'];

// Propiedades de archivo Excel
$objPHPExcel->getProperties()->setCreator("P&S Systems")
        ->setLastModifiedBy("P&S Systems")
        ->setTitle("Reporte XLS")
        ->setSubject("DETALLE DE INGRESOS POR CONTRATO")
        ->setDescription("")
        ->setKeywords("")
        ->setCategory("");

//////////////////////CABECERA DE LA CONSULTA
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("A2", 'DETALLE DE INGRESOS POR CONTRATO');
$objPHPExcel->getActiveSheet()
        ->getStyle('A2:M2')->getAlignment()
        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('A2:M2');
$objPHPExcel->getActiveSheet()
        ->getStyle("A2:M2")
        ->getFont()
        ->setBold(true)
        ->setName('Verdana')
        ->setSize(18);
///////////////////////////////////////////
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
        ->setCellValue("C5", $fechai);
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
        ->setCellValue("G5", 'Hasta:');
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
        ->setCellValue("H5", $fechaf);
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
$objDrawing->setPath('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"]);       // 
$objDrawing->setWidth(160);                 // sets the image 
$objDrawing->setHeight(60);
$objDrawing->setCoordinates('L2');    // pins the top-left corner 
$objDrawing->setOffsetX(10);                // pins the top left 
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

//////////////////////////////////////////////////////////
$styleArray = array(
    'borders' => array(
        'bottom' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
    ),
);
$objPHPExcel->getActiveSheet()->getStyle('B5:L5')->applyFromArray($styleArray);
unset($styleArray);
//////////////////////////
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(17);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
$objPHPExcel->getActiveSheet()
        ->getStyle("A7:N7")
        ->getFont()
        ->setBold(true)
        ->setName('Verdana')
        ->setSize(9);

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("A7", 'NRO_CONTRATO');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("B7", 'NOMBRE_CLIENTE');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("C7", 'IDENTIFICACIÒN');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("D7", 'FECHA_CONTRATO');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("E7", 'DÍAS');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("F7", 'PAX');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("G7", 'VALOR_CONTRATO');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("H7", 'ABONOS');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("I7", 'FALTANTE');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("J7", 'INGRESOS');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("K7", 'EGRESOS');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("L7", 'UTILIDAD');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("M7", 'CIUDAD_ORIGEN');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("N7", 'CIUDAD_DESTINO');


$objPHPExcel->getActiveSheet()->getStyle("A" . 7 . ":N" . 7)->getBorders()->applyFromArray(
        array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
                'color' => array(
                    'rgb' => '000000'
                )
            )
        )
);
///////////LLENAR CELDAS CON DATOS//////////
$contratos = obtenerContratos();
$cellnum = 8;
foreach ($contratos as $key => $value) {
    $objPHPExcel->setActiveSheetIndex(0)
            ->getCell('A' . $cellnum)
            ->setValueExplicit($value['nro_contrato'], PHPExcel_Cell_DataType::TYPE_STRING);
    $objPHPExcel->setActiveSheetIndex(0)
            ->getCell('B' . $cellnum)
            ->setValueExplicit($value['nombres_cli'], PHPExcel_Cell_DataType::TYPE_STRING);
    $objPHPExcel->setActiveSheetIndex(0)
            ->getCell('C' . $cellnum)
            ->setValueExplicit($value['identificacion'], PHPExcel_Cell_DataType::TYPE_STRING);
    $objPHPExcel->setActiveSheetIndex(0)
            ->getCell('D' . $cellnum)
            ->setValueExplicit($value['fecha_contrato'], PHPExcel_Cell_DataType::TYPE_STRING);
    $objPHPExcel->setActiveSheetIndex(0)
            ->getCell('E' . $cellnum)
            ->setValueExplicit($value['nro_dias'], PHPExcel_Cell_DataType::TYPE_STRING);
    $objPHPExcel->setActiveSheetIndex(0)
            ->getCell('F' . $cellnum)
            ->setValueExplicit($value['nro_personas'], PHPExcel_Cell_DataType::TYPE_STRING);
    $objPHPExcel->setActiveSheetIndex(0)
            ->getCell('G' . $cellnum)
            ->setValueExplicit($value['valor'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $objPHPExcel->setActiveSheetIndex(0)
            ->getCell('H' . $cellnum)
            ->setValueExplicit(totalAbonos($value['id_contrato']), PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $objPHPExcel->setActiveSheetIndex(0)
            ->getCell('I' . $cellnum)
            ->setValueExplicit($value['valor'] - totalAbonos($value['id_contrato']), PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $objPHPExcel->setActiveSheetIndex(0)
            ->getCell('J' . $cellnum)
            ->setValueExplicit(totalIngresos($value['id_contrato']), PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $objPHPExcel->setActiveSheetIndex(0)
            ->getCell('K' . $cellnum)
            ->setValueExplicit(totalEgresos($value['id_contrato']), PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $objPHPExcel->setActiveSheetIndex(0)
            ->getCell('L' . $cellnum)
            ->setValueExplicit(totalIngresos($value['id_contrato']) - totalEgresos($value['id_contrato']), PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $objPHPExcel->setActiveSheetIndex(0)
            ->getCell('M' . $cellnum)
            ->setValueExplicit($value['origen'], PHPExcel_Cell_DataType::TYPE_STRING);
    $objPHPExcel->setActiveSheetIndex(0)
            ->getCell('N' . $cellnum)
            ->setValueExplicit($value['destino'], PHPExcel_Cell_DataType::TYPE_STRING);
    $objPHPExcel->getActiveSheet()->getStyle("A" . $cellnum . ":N" . $cellnum)->getBorders()->applyFromArray(
            array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
                    'color' => array(
                        'rgb' => '000000'
                    )
                )
            )
    );
    $cellnum++;
}
$objPHPExcel->getActiveSheet()->setCellValue('F' . ($cellnum), "TOTALES");
if (count($contratos) > 0) {
    $objPHPExcel->getActiveSheet()->setCellValue('G' . ($cellnum), "=SUM(G8:G" . ($cellnum - 1) . ")");
    $objPHPExcel->getActiveSheet()->setCellValue('H' . ($cellnum), "=SUM(H8:H" . ($cellnum - 1) . ")");
    $objPHPExcel->getActiveSheet()->setCellValue('I' . ($cellnum), "=SUM(I8:I" . ($cellnum - 1) . ")");
    $objPHPExcel->getActiveSheet()->setCellValue('J' . ($cellnum), "=SUM(J8:J" . ($cellnum - 1) . ")");
    $objPHPExcel->getActiveSheet()->setCellValue('K' . ($cellnum), "=SUM(K8:K" . ($cellnum - 1) . ")");
    $objPHPExcel->getActiveSheet()->setCellValue('L' . ($cellnum), "=SUM(L8:L" . ($cellnum - 1) . ")");
}

$objPHPExcel->getActiveSheet()
        ->getStyle("F" . ($cellnum + 1) . ":L" . ($cellnum + 1))
        ->getFont()
        ->setBold(true)
        ->setName('Verdana')
        ->setSize(9);
//////////////////////////////////////////////////////////
$styleArray = array(
    'borders' => array(
        'bottom' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
        ),
    ),
);
$objPHPExcel->getActiveSheet()->getStyle("A" . ($cellnum) . ":N" . ($cellnum))->applyFromArray($styleArray);
unset($styleArray);


//////////////////////////////////////////////////////////
//DATOS DE LA SALIDA DEL EXCEL
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="' . $Archivo . '"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

function obtenerContratos() {
    global $fechai, $fechaf;
    $sql = "SELECT cr.id_contrato, fecha_contrato, nro_personas
       nro_personas, nro_dias, valor, nro_contrato,  
       id_usuario, fecha_creacion, fecha_modificacion, 
	   c.nombres_cli, c.identificacion,
	   clo.nombre as origen,cld.nombre as destino
    FROM contrato_alquiler_vehiculo_trasporte cat 
    inner join clientes c 
    on cat.id_cliente = c.id_cliente
	inner join contrato_ruta cr
	on cr.id_contrato=cat.id_contrato
	inner join contrato_lugar clo
	on clo.id_lugar=cr.id_lugar_origen
	inner join contrato_lugar cld
	on cld.id_lugar=cr.id_lugar_destino
        where cat.estado='Activo' and fecha_contrato between '$fechai' and '$fechaf'
        order by cat.fecha_creacion;";
    $consulta = pg_query($sql);
    if ($consulta) {
        return pg_fetch_all($consulta);
    }
    return [];
}
