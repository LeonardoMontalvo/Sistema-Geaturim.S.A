<?php
session_start();
date_default_timezone_set('America/Guayaquil');
require_once "PHPExcel.php";
include '../procesos/base.php';
include '../procesos/funciones.php';
conectarse();

//VARIABLES DE PHP
$objPHPExcel = new PHPExcel();
$Archivo = "resumen_facturas_canceladas_cxp.xls";

// Propiedades de archivo Excel
$objPHPExcel->getProperties()->setCreator("P&S Systems")
    ->setLastModifiedBy("P&S Systems")
    ->setTitle("Reporte XLS")
    ->setSubject("FACTURAS CANCELADAS POR PROVEEDOR")
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
    ->setCellValue("B2", "FACTURAS CANCELADAS POR PROVEEDOR");
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
$y = 7;

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue("B" . $y, 'RUC Proveedor')
    ->setCellValue("C" . $y, 'Proveedor')
    ->setCellValue("D" . $y, 'Nro. Documento')
    ->setCellValue("E" . $y, 'Emisión')
    ->setCellValue("F" . $y, 'Vencimiento')
    ->setCellValue("G" . $y, 'Tipo Doc')
    ->setCellValue("H" . $y, 'Total')
    ->setCellValue("I" . $y, 'Abonos')
    ->setCellValue("J" . $y, 'Saldo')
    ->setCellValue("K" . $y, 'Cuenta');
$objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":K" . $y)->getFont()->setBold(true)->setName('Verdana')->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":K" . $y)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

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

$queryprov = "";
if (!empty($_GET['id_proveedor'])) {
    $queryprov = " where id_proveedor='" . $_GET['id_proveedor'] . "'";
}

$consulta = pg_query("select * FROM proveedores $queryprov order by id_proveedor asc");

while ($row = pg_fetch_row($consulta)) {
    $query_fecha = "";
    // RANGO DE FECHAS O FECHA ACTUAL
    $rango = false;
    if ($_GET['inicio'] != '') {
        $rango = true;
    }
    if ($rango) {
        $query_fecha = "BETWEEN '$_GET[inicio]' AND";
    } else {
        $query_fecha = "=";
    }
    $query_punto = "";
    if ($_GET['id_empre'] != '0') {
        $query_punto = "AND cp.id_empresa='$_GET[id_empre]'";
    }

    $id_usuario_cp = "";
    if ($_GET['id'] != '0') {
        $id_usuario_cp = "and cp.id_usuario='$_GET[id]'";
    }
    $query_punto_fv = "";
    if ($_GET['id_empre'] != '0') {
        $query_punto_fv = "AND g.id_empresa='$_GET[id_empre]'";
    }
    $id_usuario_fv = "";
    if ($_GET['id'] != '0') {
        $id_usuario_fv = "and g.id_usuario='$_GET[id]'";
    }

    $filas = obtenerCpIternasExternas($row[0]);
    $sub = 0;
    $subta = 0;
    $subto = 0;
    $subabo = 0;
    $subsa = 0;

    if (!empty($filas)) {
        foreach ($filas as $row1) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueExplicit("B" . $y, utf8_decode($row[2]), PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit("C" . $y, utf8_decode($row[3]), PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit("D" . $y, utf8_decode($row1["num_doc"]), PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue("E" . $y, utf8_decode($row1["emision"]))
                ->setCellValue("F" . $y, utf8_decode($row1["caduca"]))
                ->setCellValue("G" . $y, utf8_decode($row1["tipo_doc"]))
                ->setCellValueExplicit("H" . $y, round($row1["total"], 2, PHP_ROUND_HALF_EVEN), PHPExcel_Cell_DataType::TYPE_NUMERIC)
                ->setCellValueExplicit("I" . $y, round($row1["abonos"], 2, PHP_ROUND_HALF_EVEN), PHPExcel_Cell_DataType::TYPE_NUMERIC)
                ->setCellValueExplicit("J" . $y, round($row1["saldo"], 2, PHP_ROUND_HALF_EVEN), PHPExcel_Cell_DataType::TYPE_NUMERIC)
                ->setCellValue("K" . $y, utf8_decode($row1["tipo"]));
            $objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":K" . $y)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
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
$objPHPExcel->getActiveSheet()->getStyle('B' . $y . ':K' . $y)->applyFromArray($styleArray);
unset($styleArray);
$y++;
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue("H" . $y, "=SUBTOTAL(109,H8:H" . ($y - 1) . ")")
    ->setCellValue("I" . $y, "=SUBTOTAL(109,I8:I" . ($y - 1) . ")")
    ->setCellValue("J" . $y, "=SUBTOTAL(109,J8:J" . ($y - 1) . ")");

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="' . $Archivo . '"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

function obtenerCpIternasExternas($idproveedor)
{
    global $query_fecha, $id_usuario_cp, $query_punto, $id_usuario_fv, $query_punto_fv;
    $sql = "
    (
        SELECT cp.num_factura num_doc,
            abreviatura tipo_doc,
            fecha_emicion::date emision,
            fecha_vencimiento::date caduca,
            (fecha_vencimiento::date - date(now())) dias_caduca,
            '0' adelanto,
            '0' meses,
            total::numeric,
            (total::numeric - saldo::numeric) as abonos,
            saldo::numeric,
            cp.estado,
            'E'::text tipo
        FROM c_pagarexternas cp
            LEFT JOIN tipo_comprobante tc on cp.tipo_documento = tc.id_tipo_comprobante
            WHERE id_proveedor='$idproveedor' AND cp.fecha_actual $query_fecha '$_GET[fin]' $id_usuario_cp    $query_punto
            and saldo::numeric=0
        ORDER BY fecha_emicion asc
        )
        union all
        (
            SELECT g.num_factura,
                --cp.comprao_gasto,
                tipo_documento,
                fecha_credito,
                fpm.fecha_actual fecha_caduca,
                (fpm.fecha_actual::date - date(now())) dias_vence,
                adelanto,
                meses,
                monto_credito,
                (monto_credito::numeric - saldo::numeric) as abonos,
                saldo,
                cp.estado,
                'I'::text tipo
                --cp.comprao_gasto
                --fpm.forma_pago
            FROM pagos_compra cp
                inner join formas_pago_mixto_g fpm on cp.id_factura_compra = fpm.id_gastos
                inner join gastos g using(id_gastos)
            where fpm.forma_pago = 'CREDITO'
                and cp.comprao_gasto = 'G'
                and cp.id_proveedor='$idproveedor'
                AND cp.fecha_credito $query_fecha '$_GET[fin]' 
                and g.estado='Activo'   $id_usuario_fv    $query_punto_fv
                and saldo=0
        )
        union all
        (
            SELECT g.num_serie,
                --cp.comprao_gasto,
                tipo_documento,
                fecha_credito,
                fpm.fecha_actual fecha_caduca,
                (fpm.fecha_actual::date - date(now())) dias_vence,
                adelanto,
                meses,
                monto_credito,
                (monto_credito::numeric - saldo::numeric) as abonos,
                saldo,
                cp.estado,
                'I'::text tipo
                --cp.comprao_gasto
                --fpm.forma_pago
            FROM pagos_compra cp
                inner join formas_pago_mixto_c fpm using(id_factura_compra)
                inner join factura_compra g using(id_factura_compra)
                WHERE cp.comprao_gasto='C'  $id_usuario_fv    $query_punto_fv
                and fpm.forma_pago='CREDITO' and cp.id_proveedor='$idproveedor'
                AND cp.fecha_credito $query_fecha '$_GET[fin]'   
                and g.estado='Activo' 
                and saldo=0
        )
        order by emision asc;
    ";
    /* var_dump($sql); */
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}

