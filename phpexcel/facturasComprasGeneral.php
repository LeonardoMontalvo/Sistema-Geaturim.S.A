<?php

date_default_timezone_set('America/Guayaquil');
require_once "PHPExcel.php";

//VARIABLES DE PHP
$objPHPExcel = new PHPExcel();
$Archivo = "resumen_compras_general.xls";

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
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(15);
//////////////////////CABECERA DE LA CONSULTA
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("B2", 'RESUMEN DE FACTURAS COMPRAS GENERAL');
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
$objDrawing->setPath('../images/' . $_SESSION["parametros_empresa"]["logo_empresa"]);         // 
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
$objPHPExcel->getActiveSheet()->getStyle('B6:S6')->applyFromArray($styleArray);
unset($styleArray);
//////////////////////////////////////////////////////////
$tsumt0 = 0;
$tsumt5 = 0;
$tsumt12 = 0;
$tsumt15 = 0;
$tiva5 = 0;
$tiva12 = 0;
$tiva15 = 0;

$total = 0;
$sub = 0;
$desc = 0;
$ivaT = 0;
$repetido = 0;
$y = 7;

$condprov = "";
if (!empty($_GET["id_proveedor"])) {
        $condprov = " and factura_compra.id_proveedor=" . $_GET["id_proveedor"];
}

$consulta1 = pg_query("select 
        factura_compra.num_serie,
        factura_compra.fecha_emision,
        factura_compra.hora_actual,
        factura_compra.fecha_cancelacion,
        factura_compra.num_autorizacion,
        factura_compra.forma_pago,
        tarifa0,
        tarifa12,
        factura_compra.iva_compra,
        factura_compra.descuento_compra,
        total_compra,
        empresa_pro,
        identificacion_pro,
        representante_legal,
        id_factura_compra,
        autorizacion,
        retencion_fuente_factura_compra.num_serie,
        retencion_fuente_factura_compra.valor_retencion 
        from factura_compra
        left join retencion_fuente_factura_compra 
        on factura_compra.id_factura_compra=retencion_fuente_factura_compra.id_factura,
        proveedores
        where factura_compra.id_proveedor=proveedores.id_proveedor 
        and factura_compra.estado='Activo' 
        and fecha_emision between  '$_GET[inicio]' 
        and'$_GET[fin]' 
        $condprov
        order by factura_compra.id_factura_compra");
$contador = pg_num_rows($consulta1);
if ($contador > 0) {
        while ($row1 = pg_fetch_row($consulta1)) {
                if ($repetido == 0) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue("B" . $y, 'Comprobante')
                                ->setCellValue("C" . $y, 'Identificación')
                                ->setCellValue("D" . $y, 'Proveedor')
                                ->setCellValue("E" . $y, 'Fecha')
                                ->setCellValue("F" . $y, 'Nro Factura')
                                ->setCellValue("G" . $y, 'Subtotal')
                                ->setCellValue("H" . $y, 'Descuento')
                                ->setCellValue("I" . $y, 'Tarifa 0%')
                                ->setCellValue("J" . $y, 'Tarifa 5%')
                                ->setCellValue("K" . $y, 'Tarifa 12%')
                                ->setCellValue("L" . $y, 'Tarifa 15%')
                                ->setCellValue("M" . $y, 'IVA')
                                ->setCellValue("N" . $y, 'Total')
                                ->setCellValue("O" . $y, 'Fecha Pago')
                                ->setCellValue("P" . $y, 'Tipo Pago')
                                ->setCellValue("Q" . $y, 'Autorizacion')
                                ->setCellValue("R" . $y, 'Num Serie')
                                ->setCellValue("S" . $y, 'Valor Retencion');
                        $objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":S" . $y)->getFont()->setBold(true)->setName('Verdana')->setSize(10);
                        $objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":S" . $y)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $repetido = 1;
                        $styleArray = array(
                                'borders' => array(
                                        'bottom' => array(
                                                'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
                                        ),
                                ),
                        );
                        $objPHPExcel->getActiveSheet()->getStyle('B' . $y . ':S' . $y)->applyFromArray($styleArray);
                        unset($styleArray);
                        $y++;
                }
                $tarifasiva = obtenerTarifasImpuestoFactura($row1[14]);
                $subtarifas = [];
                $valsiva = [];
                foreach ($tarifasiva as $value) {
                        $subtarifas[$value["tarifa"]] = number_format($value["base_imponible"], 2);
                        $valsiva[$value["tarifa"]] = number_format($value["valor_impuesto"], 2);
                        if (empty($totalsubtarifas[$value["tarifa"]])) {
                                $totalsubtarifas[$value["tarifa"]] = 0;
                        }
                        $totalsubtarifas[$value["tarifa"]] += $value["base_imponible"];
                }

                if (empty($tarifasiva)) {
                        if ($row1[1] < '2024-04-01') {
                                if (empty($totalsubtarifas[0])) {
                                        $totalsubtarifas[0] = 0;
                                }
                                if (empty($totalsubtarifas[12])) {
                                        $totalsubtarifas[12] = 0;
                                }
                                $totalsubtarifas[0] += $row1[6];
                                $totalsubtarifas[12] += $row1[7];
                                $subtarifas[0] = number_format($row1[6], 2);
                                $subtarifas[12] = number_format($row1[7], 2);
                                $valsiva[12] = number_format($row1[8], 2);
                        } else {
                                if (empty($totalsubtarifas[0])) {
                                        $totalsubtarifas[0] = 0;
                                }
                                if (empty($totalsubtarifas[12])) {
                                        $totalsubtarifas[15] = 0;
                                }
                                $totalsubtarifas[0] += $row1[6];
                                $totalsubtarifas[15] += $row1[7];
                                $subtarifas[0] = number_format($row1[6], 2);
                                $subtarifas[15] = number_format($row1[7], 2);
                                $valsiva[15] = number_format($row1[8], 2);
                        }
                }

                $sub = $sub + ($row1[10] - $row1[8] + $row1[9]);
                $desc = $desc + $row1[9];
                $ivaT = $ivaT + $row1[8];
                $total = $total + $row1[10];

                $sumt0 = (!empty($subtarifas[0]) ? $subtarifas[0] : "0.00");
                $sumt5 = (!empty($subtarifas[5]) ? $subtarifas[5] : "0.00");
                $sumt12 = (!empty($subtarifas[12]) ? $subtarifas[12] : "0.00");
                $sumt15 = (!empty($subtarifas[15]) ? $subtarifas[15] : "0.00");
                $iva5 = (!empty($valsiva[5]) ? $valsiva[5] : "0.00");
                $iva12 = (!empty($valsiva[12]) ? $valsiva[12] : "0.00");
                $iva15 = (!empty($valsiva[15]) ? $valsiva[15] : "0.00");

                $tsumt0 += $sumt0;
                $tsumt5 += $sumt5;
                $tsumt12 += $sumt12;
                $tsumt15 += $sumt15;
                $tiva5 += $iva5;
                $tiva12 += $iva12;
                $tiva15 += $iva15;

                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue("B" . $y, $row1[14])
                        ->setCellValue("C" . $y, $row1[12])
                        ->setCellValue("D" . $y, $row1[11])
                        ->setCellValue("E" . $y, $row1[1])
                        ->setCellValue("F" . $y, substr($row1[0], 8))
                        ->setCellValue("G" . $y, utf8_decode(truncateFloat(round($row1[10] - $row1[8] + $row1[9], 2, PHP_ROUND_HALF_EVEN), 2)))
                        ->setCellValue("H" . $y, utf8_decode(truncateFloat(round($row1[9], 2, PHP_ROUND_HALF_EVEN), 2)))

                        ->setCellValue("I" . $y, $sumt0)
                        ->setCellValue("J" . $y, $sumt5)
                        ->setCellValue("K" . $y, $sumt12)
                        ->setCellValue("L" . $y, $sumt15)
                        ->setCellValue("M" . $y, $row1[8])

                        ->setCellValue("N" . $y, utf8_decode(truncateFloat(round($row1[10], 2, PHP_ROUND_HALF_EVEN), 2)))
                        ->setCellValue("O" . $y, $row1[3])
                        ->setCellValue("P" . $y, $row1[5])
                        ->setCellValue("Q" . $y, $row1[15])
                        ->setCellValue("R" . $y, $row1[16])
                        ->setCellValue("S" . $y, $row1[17]);
                $objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":S" . $y)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
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
$objPHPExcel->getActiveSheet()->getStyle('B' . ($y - 1) . ':S' . ($y - 1))->applyFromArray($styleArray);
unset($styleArray);
//$y=$y+1;  
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValueExplicit("B" . $y, 'Totales:')
        ->setCellValueExplicit("G" . $y, (number_format($sub, 2, ',', '.')), PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("H" . $y, (number_format($desc, 2, ',', '.')), PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("I" . $y, (number_format($tsumt0, 2, ',', '.')), PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("J" . $y, (number_format($tsumt5, 2, ',', '.')), PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("K" . $y, (number_format($tsumt12, 2, ',', '.')), PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("L" . $y, (number_format($tsumt15, 2, ',', '.')), PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("M" . $y, (number_format($ivaT, 2, ',', '.')), PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("N" . $y, (number_format($total, 2, ',', '.')), PHPExcel_Cell_DataType::TYPE_STRING);

$objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":S" . $y)->getFont()->setBold(true)->setName('Verdana')->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":S" . $y)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$y++;
$y++;
//////////////////////////////////////////////////////////
//DATOS DE LA SALIDA DEL EXCEL
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="' . $Archivo . '"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

function obtenerTarifasImpuestoFactura($id)
{
        $sql = "select
    di.cod_impuesto, 
    di.cod_tarifa, 
    di.tarifa, 
    sum(di.valor_impuesto)valor_impuesto, 
    sum(di.base_imponible)base_imponible
    from
    factura_compra fc
    inner join detalle_factura_compra dfc
    using(id_factura_compra)
    inner join detalle_impuesto_producto_compra di
    using(id_detalle_compra)
    where id_factura_compra=$id
    group by di.cod_tarifa, di.cod_impuesto, di.tarifa";
        $res = pg_query($sql);
        $rows = pg_fetch_all($res);
        if (!empty($rows)) {
                return $rows;
        }
        return [];
}


exit;
