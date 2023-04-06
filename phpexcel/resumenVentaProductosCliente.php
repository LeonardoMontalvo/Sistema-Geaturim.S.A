<?php

date_default_timezone_set('America/Guayaquil');
require_once "PHPExcel.php";

//VARIABLES DE PHP
$objPHPExcel = new PHPExcel();
$Archivo = "resumen_productos_vendidos.xls";

include '../procesos/base.php';
include '../procesos/funciones.php';
session_start();
conectarse();

// Propiedades de archivo Excel
$objPHPExcel->getProperties()->setCreator("P&S Systems")
    ->setLastModifiedBy("P&S Systems")
    ->setTitle("Reporte XLS")
    ->setSubject("RESUMEN DE PRODUCTOS VENDIDOS")
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
//////////////////////CABECERA DE LA CONSULTA
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue("B2", 'RESUMEN DE PRODUCTOS VENDIDOS');
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

if (!empty($_GET["id_cliente"])) {
    $cliente = getCliente($_GET["id_cliente"]);
    /////////////////////////
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("B6", 'Cliente:');
    $objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('B6:B6');

    $objPHPExcel->getActiveSheet()
        ->getStyle("B6:B6")
        ->getFont()
        ->setBold(false)
        ->setName('Verdana')
        ->setSize(10);
    //////////////////////////
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("C6", $cliente["nombres_cli"]);
    $objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('C6:C6');

    $objPHPExcel->getActiveSheet()
        ->getStyle("C6:C6")
        ->getFont()
        ->setBold(false)
        ->setName('Verdana')
        ->setSize(10);
    //////////////////////////
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("D6", 'RUC/CI Cliente:');
    $objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('D6:D6');

    $objPHPExcel->getActiveSheet()
        ->getStyle("D6:D6")
        ->getFont()
        ->setBold(false)
        ->setName('Verdana')
        ->setSize(10);
    //////////////////////////
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("E6", "'" . $cliente["identificacion"]);
    $objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('E6:E6');

    $objPHPExcel->getActiveSheet()
        ->getStyle("E6:E6")
        ->getFont()
        ->setBold(false)
        ->setName('Verdana')
        ->setSize(10);
    //////////////////////////
}

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
$objPHPExcel->getActiveSheet()->getStyle('B6:K6')->applyFromArray($styleArray);
unset($styleArray);
//////////////////////////////////////////////////////////
$total = 0;
$sub = 0;
$desc = 0;
$ivaT = 0;
$repetido = 0;
$y = 7;

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue("B" . $y, 'CÓDIGO')
    ->setCellValue("C" . $y, 'PRODUCTO')
    ->setCellValue("D" . $y, 'CANTIDAD')
    ->setCellValue("E" . $y, 'PRECIO COMPRA')
    ->setCellValue("F" . $y, 'IVA COMPRA')
    ->setCellValue("G" . $y, 'TOTAL COMPRA')
    ->setCellValue("H" . $y, 'PRECIO VENTA')
    ->setCellValue("I" . $y, 'IVA. VENTA')
    ->setCellValue("J" . $y, 'TOTAL VENTA')
    ->setCellValue("K" . $y, 'UTILIDAD');
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
$objPHPExcel->getActiveSheet()->getStyle('B' . $y . ':K' . $y)->applyFromArray($styleArray);
unset($styleArray);
$y++;

$condcli = "";
if (!empty($_GET["id_cliente"])) {
    $condcli = "and fv.id_cliente=" . $_GET["id_cliente"];
}
$sql = "
        select x.cod_productos,
        x.articulo,
        sum(x.cantidad) as cantidad,
        sum(x.total) as total,
        x.iva,
        x.precio_venta,
        x.incluye_iva,
        x.precio_compra,
        x.cod_barras
        from(
            (
                select dfv.cod_productos,
                p.articulo,
                sum(cantidad::numeric) as cantidad,
                coalesce(round(sum(dfv.total_venta::numeric -(dfv.total_venta::numeric *(round((fv.descuento_venta * 100) / nullif((fv.tarifa0::numeric + fv.tarifa12::numeric), 0),0) / 100))),4),0) as total,
                p.iva,
                dfv.precio_venta,
                p.incluye_iva,
                p.precio_compra,
                p.cod_barras
                from factura_venta fv,
                detalle_factura_venta dfv,
                productos p
                where fv.id_factura_venta = dfv.id_factura_venta
                and p.cod_productos = dfv.cod_productos
                and fv.fecha_actual between '$_GET[inicio]' and '$_GET[fin]'
                and fv.id_empresa=$_GET[id]
                and fv.estado = 'Activo'
                $condcli
                group by dfv.cod_productos,p.articulo,p.iva,dfv.precio_venta,p.incluye_iva,p.precio_compra,p.cod_barras
                order by cantidad desc
            )
            union all
            (
                select dfv.cod_productos,
                p.articulo,
                sum(cantidad::numeric) as cantidad,
                coalesce(round(sum(dfv.total_venta::numeric -(dfv.total_venta::numeric *(round((fv.descuento_venta * 100) / nullif((fv.tarifa0::numeric + fv.tarifa12::numeric), 0),0) / 100))),4),0) as total,
                p.iva,
                dfv.precio_venta,
                p.incluye_iva,
                p.precio_compra,
                p.cod_barras
                from facturas_novalidas fv,
                detalle_facturas_novalidas dfv,
                productos p
                where fv.id_facturas_novalidas = dfv.id_facturas_novalidas
                and p.cod_productos = dfv.cod_productos
                and fv.fecha_actual between '$_GET[inicio]' and '$_GET[fin]'
                and fv.id_empresa=$_GET[id]
                and fv.estado = 'Activo'
                $condcli
                group by dfv.cod_productos,p.articulo,p.iva,dfv.precio_venta,p.incluye_iva,p.precio_compra,p.cod_barras
                order by cantidad desc
            )
        ) as x
        group by x.cod_productos,x.articulo,x.iva,x.precio_venta,x.incluye_iva,x.precio_compra,x.cod_barras
        order by articulo asc
        ";

$res = pg_query($sql);
$rows = pg_fetch_all($res);
if (!$rows) {
    $rows = [];
}

$total = 0;
$cantidad = 0;
$tutilidad = 0;
$totalcompra = 0;

foreach ($rows as $value) {
    $totali = $value["total"];
    $ivapv = 0;
    $ivapc = 0;
    if (mb_strtolower($value["iva"]) == 'si') {
        $iva = obtenerIva();
        $viva = $totali * ($iva / 100);
        $totali += $viva;


        if (mb_strtolower($value["incluye_iva"]) == 'si') {
            $pvsi = $value["precio_venta"] / (1 + ($iva / 100));
            $ivapv = $value["precio_venta"] - $pvsi;
        } else {
            $ivapv = $value["precio_venta"] * ($iva / 100);
        }
        $ivapc = $value["precio_compra"] * ($iva / 100);
    }
    $utilidad = ($value["cantidad"] * ($value["precio_venta"] + $ivapv)) - ($value["cantidad"] * ($value["precio_compra"] + $ivapc));
    $totalc = ($value["precio_compra"] + $ivapc) * $value["cantidad"];

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("B" . $y, $value["cod_barras"])
        ->setCellValue("C" . $y, $value["articulo"])
        ->setCellValue("D" . $y, $value["cantidad"])
        ->setCellValue("E" . $y, $value["precio_compra"])
        ->setCellValue("F" . $y, $ivapc)
        ->setCellValue("G" . $y, $totalc)
        ->setCellValue("H" . $y, $value["precio_venta"])
        ->setCellValue("I" . $y, $ivapv)
        ->setCellValue("J" . $y, $totali)
        ->setCellValue("K" . $y, $utilidad);
    $objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":C" . $y)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $objPHPExcel->getActiveSheet()->getStyle("D" . $y . ":K" . $y)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $y = $y + 1;
}
$y1 = $y + 1;
$objPHPExcel->getActiveSheet()
    ->setCellValue("B$y1", "TOTALES:")
    ->setCellValue("D$y1", "=SUM(D8:D$y)")
    ->setCellValue("G$y1", "=SUM(G8:G$y)")
    ->setCellValue("J$y1", "=SUM(J8:J$y)")
    ->setCellValue("K$y1", "=SUM(K8:K$y)");
$objPHPExcel->getActiveSheet()->getStyle("B" . $y1 . ":K" . $y1)->getFont()->setBold(true)->setName('Verdana')->setSize(10);

$styleArray = array(
    'borders' => array(
        'top' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
        ),
        'bottom' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
        ),
    ),
);
$objPHPExcel->getActiveSheet()->getStyle('B' . $y1 . ':K' . $y1)->applyFromArray($styleArray);

$y++;
//////////////////////////////////////////////////////////
//DATOS DE LA SALIDA DEL EXCEL
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="' . $Archivo . '"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

function getCliente($idcli)
{
    $sql = "select*from clientes where id_cliente=$idcli";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    $cliente = $rows[0];
    return $cliente;
}

function obtenerIva()
{
    $sql = "select valor from parametros where descripcion='IVA'";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (!$rows) {
        return null;
    }
    return $rows[0]["valor"];
}

exit;
