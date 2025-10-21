<?php
date_default_timezone_set('America/Guayaquil');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

//VARIABLES DE PHP
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$Archivo = "total_gastos.xls";

include '../procesos/base.php';
include '../procesos/funciones.php';
include 'util.php';
session_start();
conectarse();

$fechai = $_GET['inicio'];
$fechaf = $_GET['fin'];

// Propiedades del archivo Excel
$spreadsheet->getProperties()
    ->setCreator("P&S Systems")
    ->setLastModifiedBy("P&S Systems")
    ->setTitle("Reporte XLS")
    ->setSubject("DETALLE DE INGRESOS POR CONTRATO")
    ->setDescription("")
    ->setKeywords("")
    ->setCategory("");

// CABECERA
$sheet->setCellValue("A2", 'DETALLE DE INGRESOS POR CONTRATO');
$sheet->mergeCells('A2:M2');
$sheet->getStyle('A2:M2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A2:M2')->getFont()->setBold(true)->setName('Verdana')->setSize(18);

// Empresa / Propietario / Fechas
$sheet->setCellValue("B4", 'Empresa: ' . $_SESSION['empresa']);
$sheet->mergeCells('B4:C4');

$sheet->setCellValue("D4", 'Propietario: ' . $_SESSION['propietario']);
$sheet->mergeCells('D4:E4');

$sheet->setCellValue("B5", 'Desde:');
$sheet->setCellValue("C5", $fechai);
$sheet->setCellValue("G5", 'Hasta:');
$sheet->setCellValue("H5", $fechaf);

// Logo
$logoPath = '../images/' . $_SESSION["parametros_empresa"]["logo_empresa"];
if (file_exists($logoPath)) {
    $drawing = new Drawing();
    $drawing->setName('Logo Empresa');
    $drawing->setPath($logoPath);
    $drawing->setWidth(160);
    $drawing->setHeight(60);
    $drawing->setCoordinates('L2');
    $drawing->setOffsetX(10);
    $drawing->setWorksheet($sheet);
}

// Bordes
$sheet->getStyle('B5:L5')->applyFromArray([
    'borders' => [
        'bottom' => ['style' => Border::BORDER_THIN]
    ]
]);

// Tamaños de columna
foreach (range('A', 'N') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Encabezado de tabla
$encabezados = [
    'A7' => 'NRO_CONTRATO', 'B7' => 'NOMBRE_CLIENTE', 'C7' => 'IDENTIFICACIÓN',
    'D7' => 'FECHA_CONTRATO', 'E7' => 'DÍAS', 'F7' => 'PAX',
    'G7' => 'VALOR_CONTRATO', 'H7' => 'ABONOS', 'I7' => 'FALTANTE',
    'J7' => 'INGRESOS', 'K7' => 'EGRESOS', 'L7' => 'UTILIDAD',
    'M7' => 'CIUDAD_ORIGEN', 'N7' => 'CIUDAD_DESTINO'
];

foreach ($encabezados as $celda => $valor) {
    $sheet->setCellValue($celda, $valor);
}

$sheet->getStyle("A7:N7")->getFont()->setBold(true)->setName('Verdana')->setSize(9);
$sheet->getStyle("A7:N7")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);

// Llenar datos
$contratos = obtenerContratos();
$cellnum = 8;

foreach ($contratos as $value) {
    $sheet->setCellValueExplicit("A$cellnum", $value['nro_contrato'], DataType::TYPE_STRING);
    $sheet->setCellValueExplicit("B$cellnum", $value['nombres_cli'], DataType::TYPE_STRING);
    $sheet->setCellValueExplicit("C$cellnum", $value['identificacion'], DataType::TYPE_STRING);
    $sheet->setCellValueExplicit("D$cellnum", $value['fecha_contrato'], DataType::TYPE_STRING);
    $sheet->setCellValueExplicit("E$cellnum", $value['nro_dias'], DataType::TYPE_NUMERIC);
    $sheet->setCellValueExplicit("F$cellnum", $value['nro_personas'], DataType::TYPE_NUMERIC);
    $sheet->setCellValueExplicit("G$cellnum", $value['valor'], DataType::TYPE_NUMERIC);
    $sheet->setCellValueExplicit("H$cellnum", totalAbonos($value['id_contrato']), DataType::TYPE_NUMERIC);
    $sheet->setCellValueExplicit("I$cellnum", $value['valor'] - totalAbonos($value['id_contrato']), DataType::TYPE_NUMERIC);
    $sheet->setCellValueExplicit("J$cellnum", totalIngresos($value['id_contrato']), DataType::TYPE_NUMERIC);
    $sheet->setCellValueExplicit("K$cellnum", totalEgresos($value['id_contrato']), DataType::TYPE_NUMERIC);
    $sheet->setCellValueExplicit("L$cellnum", totalIngresos($value['id_contrato']) - totalEgresos($value['id_contrato']), DataType::TYPE_NUMERIC);
    $sheet->setCellValueExplicit("M$cellnum", $value['origen'], DataType::TYPE_STRING);
    $sheet->setCellValueExplicit("N$cellnum", $value['destino'], DataType::TYPE_STRING);

    $sheet->getStyle("A$cellnum:N$cellnum")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    $cellnum++;
}

// Totales
$sheet->setCellValue("F$cellnum", "TOTALES");
if (count($contratos) > 0) {
    foreach (range('G', 'L') as $col) {
        $sheet->setCellValue("$col$cellnum", "=SUM($col" . "8:$col" . ($cellnum - 1) . ")");
    }
}
$sheet->getStyle("F$cellnum:L$cellnum")->getFont()->setBold(true);

// Salida
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="' . $Archivo . '"');
header('Cache-Control: max-age=0');

$writer = new Xls($spreadsheet);
$writer->save('php://output');

exit();

// --------- FUNCIONES ----------
function obtenerContratos()
{
    global $fechai, $fechaf;
    $sql = "SELECT cr.id_contrato, fecha_contrato, nro_personas, nro_dias, valor, nro_contrato,  
       id_usuario, fecha_creacion, fecha_modificacion, 
       c.nombres_cli, c.identificacion,
       clo.nombre as origen, cld.nombre as destino
    FROM contrato_alquiler_vehiculo_trasporte cat 
    INNER JOIN clientes c ON cat.id_cliente = c.id_cliente
    INNER JOIN contrato_ruta cr ON cr.id_contrato=cat.id_contrato
    INNER JOIN contrato_lugar clo ON clo.id_lugar=cr.id_lugar_origen
    INNER JOIN contrato_lugar cld ON cld.id_lugar=cr.id_lugar_destino
    WHERE cat.estado='Activo' AND fecha_contrato BETWEEN '$fechai' AND '$fechaf'
    ORDER BY cat.fecha_creacion;";
    $consulta = pg_query($sql);
    if ($consulta) {
        return pg_fetch_all($consulta);
    }
    return [];
}
