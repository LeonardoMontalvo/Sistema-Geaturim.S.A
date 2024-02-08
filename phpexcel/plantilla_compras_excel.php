<?php

date_default_timezone_set('America/Guayaquil');
require_once "PHPExcel.php";

//VARIABLES DE PHP
$objPHPExcel = new PHPExcel();
$Archivo = "plantilla_compras.xlsx";

$inicio = $_GET['inicio'];
$fin = $_GET['fin'];

include '../procesos/base.php';
include '../procesos/funciones.php';
session_start();
conectarse();

// Propiedades de archivo Excel
$objPHPExcel->getProperties()->setCreator("P&S Systems")
        ->setLastModifiedBy("P&S Systems")
        ->setTitle("Reporte XLS")
        ->setSubject("PANTILLA DE COMPRAS")
        ->setDescription("")
        ->setKeywords("")
        ->setCategory("");

//PROPIEDADES DEL  LA CELDA
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
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
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(15);

//////////////////////CABECERA DE LA CONSULTA
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("A2", 'PANTILLA DE COMPRAS');
/* $objPHPExcel->getActiveSheet()
  ->getStyle('A2:B2')->getAlignment()
  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); */

$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('A2:B2');

$objPHPExcel->getActiveSheet()
        ->getStyle("A2:B2")
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
        ->setCellValue("B5", 'Propietario: ' . $_SESSION['propietario'] . '');
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('B5:C5');

$objPHPExcel->getActiveSheet()
        ->getStyle("B5:C5")
        ->getFont()
        ->setBold(false)
        ->setName('Verdana')
        ->setSize(10);
//////////////////////////
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("B6", 'Fecha Inicio: ' . $inicio . '');
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('B6:C6');

$objPHPExcel->getActiveSheet()
        ->getStyle("B6:C6")
        ->getFont()
        ->setBold(false)
        ->setName('Verdana')
        ->setSize(10);
//////////////////////////
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("B7", 'Fecha Fin: ' . $fin . '');
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('B7:C7');

$objPHPExcel->getActiveSheet()
        ->getStyle("B7:C7")
        ->getFont()
        ->setBold(false)
        ->setName('Verdana')
        ->setSize(10);
/////////////////////////
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('PHPExcel logo');
$objDrawing->setDescription('PHPExcel logo');
$objDrawing->setPath('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"]);          // 
$objDrawing->setWidth(160);                 // sets the image 
$objDrawing->setHeight(60);
$objDrawing->setCoordinates('A4');    // pins the top-left corner 
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
$objPHPExcel->getActiveSheet()->getStyle('B7')->applyFromArray($styleArray);
unset($styleArray);

//////////////////////////////////////////////////////////
///DATOS FACTURAS Y RETENCIONES

$objPHPExcel
        ->getActiveSheet()
        ->getStyle('A10:Y10')
        ->getFont()
        ->getColor()
        ->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

$objPHPExcel
        ->getActiveSheet()
        ->getStyle('A10:Y10')
        ->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()->setARGB('00000000');

$objPHPExcel->getActiveSheet()
        ->getStyle('A10:Y10')
        ->getAlignment()
        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("A10", 'RUC')
        ->getComment('A10')
        ->getText()
        ->createTextRun('Ruc Proveedor 13 Dígitos');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("B10", 'Razón Social')
        ->getComment("B10")->getText()
        ->createTextRun("Nombre de la Empresa (Proveedor)");
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("C10", 'Serie')
        ->getComment('C10')
        ->getText()
        ->createTextRun('Serie del Doc. Proveedor');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("D10", 'Autorización')
        ->getComment('D10')
        ->getText()
        ->createTextRun('Autorización Factura Proveedor');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("E10", 'No. Factura')
        ->getComment('E10')
        ->getText()
        ->createTextRun('No.Fact. Proveedor');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("F10", 'Fecha')
        ->getComment('F10')
        ->getText()
        ->createTextRun('Fecha de la Compra año-mm-dd');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("G10", 'Fecha Cad.')
        ->getComment('G10')
        ->getText()
        ->createTextRun('Fecha Caducidad Doc. Proveedor  año-mm-dd');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("H10", 'Tipo. Doc')
        ->getComment('H10')
        ->getText()
        ->createTextRun('Númento 01 Factura -- 02 Nota de Venta -- 03 Liquidación --09 Tickets Maquinas registradoras -- 11 Pasajes Aéreos -- 12 Doc. de Inst. Financieras -- 15 Comp. de Venta emitido en el Exteriror -- 41 Reembolso de Gastos');

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("I10", 'Sustento Trib.')
        ->getComment('I10')
        ->getText()
        ->createTextRun('Sustento Tributario  Tabla 5 SRI Anexos  >>> 01-02-03-04-05-06-07-08-09-10-00 ');

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("J10", 'Subtotal 12%')
        ->getComment('J10')
        ->getText()
        ->createTextRun('Total valor Factura con IVA');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("K10", 'Subtotal 0%')
        ->getComment('K10')
        ->getText()
        ->createTextRun('Total valor Factura 0%');

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("L10", 'Desc. 12%')
        ->getComment('L10')
        ->getText()
        ->createTextRun('Total valor Descuento con IVA');

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("M10", 'Desc. 0%')
        ->getComment('M10')
        ->getText()
        ->createTextRun('Total valor Descuento con IVA');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("N10", 'Total');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("O10", 'Forma Pago')
        ->getComment('O10')
        ->getText()
        ->createTextRun('Forma de Pago tabla 13 SRI, solo poner los dos digitos>>> 01(Efectivo)-10(Tarj. Cred.)- 99(Crédito)-17(Dinero Electronico) - 18(Tarj. Prepago) - 19(Tarj. Cred.) - 20 (Utilizancion del sistema financiero)');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("P10", 'Serie Ret.')
        ->getComment('P10')
        ->getText()
        ->createTextRun('Serie Retención propia');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("Q10", 'Autor. Ret')
        ->getComment('Q10')
        ->getText()
        ->createTextRun('Autorización Retención propia');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("R10", 'Sec. Ret')
        ->getComment('R10')
        ->getText()
        ->createTextRun('Secuencia de la Retención propia');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("S10", 'Base IR')
        ->getComment('S10')
        ->getText()
        ->createTextRun('Base de Retención IR');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("T10", 'Impuesto')
        ->getComment('T10')
        ->getText()
        ->createTextRun('Código Aplicado a la Retención Tabla 3.2  >>> 303-304-307-308-309-310-312-319-320-322-323-323a-323b1-323b2-323c-323d-323e-323f-323g-323h-323i-323j-323k-325-327-328-332-332-332A-332B-332C-332D-332F-332G-332H-333-334-336-337-340-341-342-343-344-345-346-347-348-349-403-405-421-427-427a-401-401aa-401ab-401ac-401ad-401ae-401af-401ag-401ah-401ai-401aj-401ak-401al-401am-401an-401ao-401ba-401bb-401bc-401bf.....');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("U10", 'IR%')
        ->getComment('U10')
        ->getText()
        ->createTextRun('Porcentaje de Retención IR en Número no porcentaje >>>> ej 1-2-8-10');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("V10", 'Valor IR')
        ->getComment('V10')
        ->getText()
        ->createTextRun('Valor Total del IVA  a ser Retenido');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("W10", 'Base IVA')
        ->getComment('W10')
        ->getText()
        ->createTextRun('Valor Total del IVA  a ser Retenido');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("X10", '% IVA')
        ->getComment('X10')
        ->getText()
        ->createTextRun('Porcentaje de Retención IVA en número no porcentaje  ej. 0-30-70-100');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("Y10", 'Valor IVA')
        ->getComment('Y10')
        ->getText()
        ->createTextRun('Valor Total Retenido por IVA');

$slq = "
        with x as(
    select
    id_factura_compra,
    identificacion_pro,
    empresa_pro,
    fc.num_serie,
    num_autorizacion,
    fecha_emision,
    fecha_cancelacion,
    tarifa12,
    tarifa0,
    descuento_compra,
    total_compra,
    fc.forma_pago,
    fc.fecha_actual,
    iva_compra
    from 
    factura_compra fc, 
    proveedores p
    where 
  fc.id_proveedor=p.id_proveedor and fc.estado='Activo'
    ),
    y as(
    select 
    distinct on (id_factura)id_factura, 
    valor_retencion, 
    num_serie num_serie_rt, 
    num_autorizacion num_autorizacion_rt
    from 
    retencion_fuente_factura_compra rffc
    where  
    id_gastos=1
    order by id_factura asc, num_autorizacion
    ),
    z as(
    select 
    *
    from 
    aux_factura_retencion afr 
    )
    select x.*,y.*,z.* from 
    x full outer join y
    on x.id_factura_compra=y.id_factura
    full outer join z
    on y.id_factura=z.id_factura
    where x.fecha_emision between '{$inicio}' and '{$fin}'
    ";

$consulta = pg_query($slq);
if (pg_num_rows($consulta) > 0) {
    $registros = pg_fetch_all($consulta);
    $initcell = 10;
    foreach ($registros as $key => $reg) {
        $initcell++;
        $objPHPExcel->setActiveSheetIndex(0)
                ->getCell('A' . $initcell)
                ->setValueExplicit($reg['identificacion_pro'], PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->setActiveSheetIndex(0)
                ->getCell('B' . $initcell)
                ->setValueExplicit($reg['empresa_pro'], PHPExcel_Cell_DataType::TYPE_STRING);
        $arrnumfc = empty($reg['num_serie']) ? '' : explode('-', $reg['num_serie']);
        if (empty($arrnumfc)) {
            $arrnumfc = array('', '', '');
        }
        $objPHPExcel->setActiveSheetIndex(0)
                ->getCell('C' . $initcell)
                ->setValueExplicit($arrnumfc[0] . $arrnumfc[1], PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->setActiveSheetIndex(0)
                ->getCell('D' . $initcell)
                ->setValueExplicit($reg['num_autorizacion'], PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->setActiveSheetIndex(0)
                ->getCell('E' . $initcell)
                ->setValueExplicit($arrnumfc[2], PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("F" . $initcell, $reg['fecha_emision']);
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("G" . $initcell, $reg['fecha_cancelacion']);
        $objPHPExcel->setActiveSheetIndex(0)
                ->getCell('H' . $initcell)
                ->setValueExplicit('01', PHPExcel_Cell_DataType::TYPE_STRING);

        if ($reg['tipo_ret'] == 'b') {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->getCell('I' . $initcell)
                    ->setValueExplicit('01', PHPExcel_Cell_DataType::TYPE_STRING);
        } else if ($reg['tipo_ret'] == 's') {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->getCell('I' . $initcell)
                    ->setValueExplicit('06', PHPExcel_Cell_DataType::TYPE_STRING);
        }


        $sql1 = "SELECT id_detalle_compra, 
id_factura_compra, 
dfc.cod_productos, 
cantidad, 
dfc.precio_compra,
dfc.descuento_producto, 
total_compra, 
bien_servicio,
p.iva,
dfc.descuento_producto
FROM detalle_factura_compra dfc,
productos p
where dfc.cod_productos=p.cod_productos
and id_factura_compra={$reg['id_factura_compra']}
        ";
        $consulta1 = pg_query($sql1);
        if (pg_num_rows($consulta) > 0) {
            $biva = 0;
            $bnoiva = 0;
            $siva = 0;
            $snoiva = 0;
            $dbiva = 0;
            $dbnoiva = 0;
            $dsiva = 0;
            $dsnoiva = 0;
            foreach (pg_fetch_all($consulta1) as $item) {
                if ($item['bien_servicio'] == 'B') {
                    if ($item['iva'] == 'Si') {
                        $biva += $item['total_compra'];
                        $dbiva += $item['total_compra'] * ($item['descuento_producto'] / 100);
                    } elseif ($item['iva'] == 'No') {
                        $bnoiva += $item['total_compra'];
                        $dbnoiva += $item['total_compra'] * ($item['descuento_producto'] / 100);
                    }
                } elseif ($item['bien_servicio'] == 'S') {
                    if ($item['iva'] == 'Si') {
                        $siva += $item['total_compra'];
                        $dsiva += $item['total_compra'] * ($item['descuento_producto'] / 100);
                    } elseif ($item['iva'] == 'No') {
                        $snoiva += $item['total_compra'];
                        $dsnoivaiva += $item['total_compra'] * ($item['descuento_producto'] / 100);
                    }
                }
            }

            if ($reg['tipo_ret'] == 'b') {
                $objPHPExcel->setActiveSheetIndex(0)
                        ->getCell('J' . $initcell)
                        ->setValueExplicit($biva, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                $objPHPExcel->setActiveSheetIndex(0)
                        ->getCell('K' . $initcell)
                        ->setValueExplicit($bnoiva, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                $objPHPExcel->setActiveSheetIndex(0)
                        ->getCell('L' . $initcell)
                        ->setValueExplicit($dbiva, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                $objPHPExcel->setActiveSheetIndex(0)
                        ->getCell('M' . $initcell)
                        ->setValueExplicit($dbnoiva, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                
            } elseif ($reg['tipo_ret'] == 's') {
                $objPHPExcel->setActiveSheetIndex(0)
                        ->getCell('J' . $initcell)
                        ->setValueExplicit($siva, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                $objPHPExcel->setActiveSheetIndex(0)
                        ->getCell('K' . $initcell)
                        ->setValueExplicit($snoiva, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                $objPHPExcel->setActiveSheetIndex(0)
                        ->getCell('L' . $initcell)
                        ->setValueExplicit($dsiva, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                $objPHPExcel->setActiveSheetIndex(0)
                        ->getCell('M' . $initcell)
                        ->setValueExplicit($dsnoiva, PHPExcel_Cell_DataType::TYPE_NUMERIC);

            }
        }

        $objPHPExcel->getActiveSheet()->setCellValue('N' . $initcell, "=SUM(J" . $initcell . ",K" . $initcell . ",W$initcell)");
        $objPHPExcel->setActiveSheetIndex(0)
                ->getCell('O' . $initcell)
                ->setValueExplicit(($reg['forma_pago'] == 'Contado' ? '01' : '99'), PHPExcel_Cell_DataType::TYPE_STRING);
        $arrnumrt = empty($reg['num_serie_rt']) ? '' : explode('-', $reg['num_serie_rt']);
        if (empty($arrnumrt)) {
            $arrnumrt = array('', '', '');
        }
        $objPHPExcel->setActiveSheetIndex(0)
                ->getCell('P' . $initcell)
                ->setValueExplicit($arrnumrt[0] . $arrnumrt[1], PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->setActiveSheetIndex(0)
                ->getCell('Q' . $initcell)
                ->setValueExplicit($reg['num_autorizacion_rt'], PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->setActiveSheetIndex(0)
                ->getCell('R' . $initcell)
                ->setValueExplicit($arrnumrt[2], PHPExcel_Cell_DataType::TYPE_STRING);
        //$objPHPExcel->getActiveSheet()->setCellValue('P' . $initcell, "=SUM(H" . $initcell . ",I" . $initcell . ")");
        //$objPHPExcel->getActiveSheet()->setCellValue('S' . $initcell, "=PRODUCT(P" . $initcell . ",(R" . $initcell . "/100))");
        //$objPHPExcel->getActiveSheet()->setCellValue('T' . $initcell, "=PRODUCT(P" . $initcell . ",(12/100))");
        /* $objPHPExcel->setActiveSheetIndex(0)
          ->getCell('R' . $initcell)
          ->setValueExplicit(0, PHPExcel_Cell_DataType::TYPE_NUMERIC); */

        /* $objPHPExcel->getActiveSheet()->setCellValue('T' . $initcell, "=PRODUCT(Q" . $initcell . ",(S" . $initcell . "/100))");
          $objPHPExcel->getActiveSheet()->setCellValue('W' . $initcell, "=PRODUCT(U" . $initcell . ",(V" . $initcell . "/100))"); */



        if (!empty($reg['id_retencion_fuente'])) {
            $sqlrf = "select 
                    rf.codigo_formulario, 
                    rf.valor 
                    from 
                    retencion_fuentes rf 
                    where  
                    rf.id_retencion_fuentes={$reg['id_retencion_fuente']} ";
            $consultarf = pg_query($sqlrf);
            if (pg_num_rows($consultarf) > 0) {
                $regrf = pg_fetch_all($consultarf);
                $objPHPExcel->setActiveSheetIndex(0)
                        ->getCell('S' . $initcell)
                        ->setValueExplicit($reg['base_imponible_fuente'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
                $objPHPExcel->getActiveSheet()->setCellValue('T' . $initcell, $regrf[0]['codigo_formulario']);
                $objPHPExcel->getActiveSheet()->setCellValue('U' . $initcell, $regrf[0]['valor']);
                $objPHPExcel->setActiveSheetIndex(0)
                        ->getCell('V' . $initcell)
                        ->setValueExplicit($reg['valor_retenido_fuente'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
            }
        }
        if (!empty($reg['id_retencion_iva'])) {
            $sqlrf = "select 
                    ri.codigo_formulario, 
                    ri.valor 
                    from 
                    retencion_iva ri 
                    where  
                    ri.id_retencion_iva={$reg['id_retencion_iva']} ";
            $consultarf = pg_query($sqlrf);
            if (pg_num_rows($consultarf) > 0) {
                $regrf = pg_fetch_all($consultarf);
                $regrf = pg_fetch_all($consultarf);
                $objPHPExcel->setActiveSheetIndex(0)
                        ->getCell('W' . $initcell)
                        ->setValueExplicit($reg['base_imponible_iva'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
                //$objPHPExcel->getActiveSheet()->setCellValue('R' . $initcell, $regrf[0]['codigo_formulario']);
                $objPHPExcel->getActiveSheet()->setCellValue('X' . $initcell, $regrf[0]['valor']);
                $objPHPExcel->setActiveSheetIndex(0)
                        ->getCell('Y' . $initcell)
                        ->setValueExplicit($reg['valor_retenido_iva'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
            }
        }
    }
    //totales
    $initcell2 = $initcell + 1;
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $initcell2, "=SUM(J10:J" . $initcell . ")");
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $initcell2, "=SUM(K10:K" . $initcell . ")");
    
    $objPHPExcel->getActiveSheet()->setCellValue('N' . $initcell2, "=SUM(N10:N" . $initcell . ")");

    $objPHPExcel->getActiveSheet()->setCellValue('V' . $initcell2, "=SUM(V10:V" . $initcell . ")");
    $objPHPExcel->getActiveSheet()->setCellValue('Y' . $initcell2, "=SUM(Y10:Y" . $initcell . ")");

    $arr = array('J', 'K','N', 'V', 'Y');
    foreach ($arr as $item) {
        $objPHPExcel
                ->getActiveSheet()
                ->getStyle("$item$initcell2")
                ->getFont()
                ->setBold(true)
                ->getColor()
                ->setARGB(PHPExcel_Style_Color::COLOR_RED);
        $styleArray = array(
            'borders' => array(
                'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
                ),
            ),
        );
        $objPHPExcel->getActiveSheet()->getStyle("$item$initcell2")->applyFromArray($styleArray);
        unset($styleArray);
    }

    //////////////////////////////////////////////////////////
    $styleArray = array(
        'borders' => array(
            'bottom' => array(
                'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
            ),
        ),
    );
    $objPHPExcel->getActiveSheet()->getStyle("A$initcell:Y$initcell")->applyFromArray($styleArray);
    unset($styleArray);

    $objPHPExcel->getActiveSheet()->freezePane('C11');
}

//////////////////////////////////////////////////////////
//DATOS DE LA SALIDA DEL EXCEL
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="' . $Archivo . '"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');

exit;
