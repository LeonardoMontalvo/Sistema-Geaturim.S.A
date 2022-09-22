<?php
session_start();
date_default_timezone_set('America/Guayaquil');
require_once "PHPExcel.php";
include '../procesos/base.php';
include '../procesos/funciones.php';
conectarse();

//VARIABLES DE PHP
$objPHPExcel = new PHPExcel();
$Archivo = "resumen_facturas_canceladas_cxc.xls";

// Propiedades de archivo Excel
$objPHPExcel->getProperties()->setCreator("P&S Systems")
    ->setLastModifiedBy("P&S Systems")
    ->setTitle("Reporte XLS")
    ->setSubject("FACTURAS CANCELADAS POR CLIENTE")
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
    ->setCellValue("B2", "FACTURAS CANCELADAS POR CLIENTE");
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
    ->setCellValue("B" . $y, 'RUC Cliente')
    ->setCellValue("C" . $y, 'Cliente')
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

$query_fecha = "";
// RANGO DE FECHAS O FECHA ACTUAL
if ($_GET['inicio'] != '') {
    $rango = true;
}
if ($rango) {
    $query_fecha = "BETWEEN '$_GET[inicio]' AND";
} else {
    $query_fecha = "=";
}
$query_punto_fv = "";
if ($_GET['id_empre'] != '0') {
    $query_punto = "AND fv.id_empresa='$_GET[id_empre]'";
}
$query_punto_c = "";
if ($_GET['id_empre'] != '0') {
    $query_punto_c = "AND c.id_empresa='$_GET[id_empre]'";
}



$id_usuario_c = "";
if ($_GET['id'] != '0') {
    $id_usuario_c = "and c.id_usuario='$_GET[id]'";
}
$id_usuario_fv = "";
if ($_GET['id'] != '0') {
    $id_usuario_fv = "and fv.id_usuario='$_GET[id]'";
}

$querycli = "";
if (!empty($_GET['id_cliente'])) {
    $querycli = " where id_cliente='" . $_GET['id_cliente'] . "'";
}


$consulta = pg_query("select * from clientes $querycli order by id_cliente asc");
if (pg_num_rows($consulta)) {
    // RANGO DE FECHAS O FECHA ACTUAL
    $query_fecha = "=";
    if ($rango) {
        $query_fecha = "BETWEEN '$_GET[inicio]' AND";
    }
    $query_punto = "";
    $query_punto_2 = "";
    if ($_GET['id_empre'] != '0') {
        $query_punto = "AND cc.id_empresa='$_GET[id_empre]'";
        $query_punto_2 = "AND pv.id_empresa='$_GET[id_empre]'";
    }

    $id_usuario_fv = "";
    $id_usuario_fv_2 = "";
    if ($_GET['id'] != '0') {
        $id_usuario_fv = "and cc.id_usuario='$_GET[id]'";
        $id_usuario_fv_2 = "and pv.id_usuario='$_GET[id]'";
    }
    while ($row = pg_fetch_assoc($consulta)) {
        $filas = obtenerCuentasInternasExternas($row["id_cliente"]);
        if (!empty($filas)) {
            foreach ($filas as $row1) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValueExplicit("B" . $y, utf8_decode($row['identificacion']), PHPExcel_Cell_DataType::TYPE_STRING)
                    ->setCellValueExplicit("C" . $y, utf8_decode($row['nombres_cli']), PHPExcel_Cell_DataType::TYPE_STRING)
                    ->setCellValueExplicit("D" . $y, utf8_decode($row1["num_factura"]), PHPExcel_Cell_DataType::TYPE_STRING)
                    ->setCellValue("E" . $y, utf8_decode($row1["fecha_emision"]))
                    ->setCellValue("F" . $y, utf8_decode($row1["fecha_vencimiento"]))
                    ->setCellValue("G" . $y, utf8_decode($row1["tipo_documento"]))
                    ->setCellValueExplicit("H" . $y, round($row1["total"], 2, PHP_ROUND_HALF_EVEN),PHPExcel_Cell_DataType::TYPE_NUMERIC)
                    ->setCellValueExplicit("I" . $y, round($row1["abonos"], 2, PHP_ROUND_HALF_EVEN),PHPExcel_Cell_DataType::TYPE_NUMERIC)
                    ->setCellValueExplicit("J" . $y, round($row1["saldo"], 2, PHP_ROUND_HALF_EVEN),PHPExcel_Cell_DataType::TYPE_NUMERIC)
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
}

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="' . $Archivo . '"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

function obtenerCuentasInternasExternas($idcliente)
{
    global $query_fecha, $id_usuario_fv, $query_punto, $id_usuario_fv_2 , $query_punto_2;
    $sql = "
    (
        SELECT num_factura,
            fecha_emicion::date fecha_emision,
            fecha_vencimiento::date,
            tc.abreviatura tipo_documento,
            (fecha_vencimiento::date - fecha_emicion::date) as vence,
            total::numeric,
            (total::numeric-saldo::numeric) as abonos,
            saldo,
            'E'::text tipo
            FROM c_cobrarexternas cc
            inner join clientes c using(id_cliente)
            inner join tipo_comprobante tc on tc.id_tipo_comprobante=cc.tipo_documento
            where c.id_cliente=$idcliente AND fecha_actual $query_fecha '$_GET[fin]' 
            $id_usuario_fv $query_punto and saldo=0
            order by fecha_emision asc
    )
    union all
    (
        SELECT num_factura,
            fecha_actual fecha_emision,
            fecha_dias fecha_vencimiento,
            pv.tipo_documento,
            (fecha_dias::date - fecha_actual::date) as vence,
            monto_credito total,
            (monto_credito::numeric - saldo::numeric) as abonos,
            pv.saldo,
            'I'::text tipo
            FROM factura_venta fv inner join clientes c using(id_cliente) inner join pagos_venta pv using(id_factura_venta)
            where c.id_cliente=$idcliente AND fv.fecha_actual $query_fecha '$_GET[fin]' 
            $id_usuario_fv_2   $query_punto_2 
            and pv.tipo_documento='Factura'
            and saldo=0 order by fecha_actual asc
    )
    union all
    (
        SELECT comprobante num_factura,
            fecha_actual fecha_emision,
            fecha_dias fecha_vencimiento,
            pv.tipo_documento,
            (fecha_dias::date - fecha_actual::date) as vence,
            monto_credito total,
            (monto_credito::numeric - saldo::numeric) as abonos,
            pv.saldo,
            'I'::text tipo
            FROM facturas_novalidas fv 
            inner join clientes c using(id_cliente) 
            inner join pagos_venta pv ON id_factura_venta = fv.id_facturas_novalidas
            where c.id_cliente=$idcliente AND fv.fecha_actual $query_fecha '$_GET[fin]' 
            $id_usuario_fv_2    $query_punto_2 
            and pv.tipo_documento='Nota'
            and saldo=0 order by fecha_actual asc
    )
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}
