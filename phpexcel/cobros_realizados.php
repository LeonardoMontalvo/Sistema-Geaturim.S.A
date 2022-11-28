<?php

session_start();
date_default_timezone_set('America/Guayaquil');
require_once "PHPExcel.php";
include '../procesos/base.php';
include '../procesos/funciones.php';
conectarse();


$tipoCuenta = "";
if ($_GET['tipo'] == 'Externas') {
    $tipoCuenta = "EXTERNAS";
} elseif ($_GET['tipo'] == 'Internas') {
    $tipoCuenta = "INTERNAS";
} else {
    $tipoCuenta = "INTERNAS Y EXTERNAS";
}

//VARIABLES DE PHP
$objPHPExcel = new PHPExcel();
$Archivo = "cobros_realizados_cxc.xls";

// Propiedades de archivo Excel
$objPHPExcel->getProperties()->setCreator("P&S Systems")
    ->setLastModifiedBy("P&S Systems")
    ->setTitle("Reporte XLS")
    ->setSubject("COBROS REALIZADOS ($tipoCuenta)")
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
    ->setCellValue("B2", "COBROS REALIZADOS ($tipoCuenta)");
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
    ->setCellValue("B" . $y, 'Comprobante')
    ->setCellValue("C" . $y, 'Nro. Documento')
    ->setCellValue("D" . $y, 'RUC Cliente')
    ->setCellValue("E" . $y, 'Cliente')
    ->setCellValue("F" . $y, 'Emisión')
    ->setCellValue("G" . $y, 'Tipo Doc')
    ->setCellValue("H" . $y, 'Forma Pago')
    ->setCellValue("I" . $y, 'Fecha Pago')
    ->setCellValue("J" . $y, 'Monto Crédito')
    ->setCellValue("K" . $y, 'Valor Pago')
    ->setCellValue("L" . $y, 'Saldo');

$objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":L" . $y)->getFont()->setBold(true)->setName('Verdana')->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":L" . $y)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$inicio = $_GET['inicio'];
$fin = $_GET['fin'];
$query_fecha = "";
// RANGO DE FECHAS O FECHA ACTUAL
$rango = false;
if ($_GET['inicio'] != '') {
    $rango = true;
}
if ($rango) {
    $query_fecha = "BETWEEN '$inicio' AND";
} else {
    $query_fecha = "=";
}

$query_punto = "";
$query_punto_2 = "";
if ($_GET['id_empre'] != '0') {
    $query_punto = "AND id_empresa='$_GET[id_empre]'";
    $query_punto_2 = "AND pv.id_empresa='$_GET[id_empre]'";
}

$id_usuario = "";
$id_usuario_2 = "";
if ($_GET['id'] != '0') {
    $id_usuario = "and id_usuario='$_GET[id]'";
    $id_usuario_2 = "and pv.id_usuario='$_GET[id]'";
}
/* $query_punto = "";

if ($_GET['id_empre'] != '0') {
    $query_punto = "AND p.id_empresa='$_GET[id_empre]'";
}

$id_usuario = "";

if ($_GET['id'] != '0') {
    $id_usuario = "and p.id_usuario='$_GET[id]'";
}
 */
if ($_GET["tipo"] == 'Internas') {
    $sqlcliente = "";
    if (!empty($_GET["id_cliente"])) {
        $sqlcliente = " where id_cliente=" . $_GET["id_cliente"];
    } else {
        $sqlcliente = " where id_cliente in(
            select id_cliente from pagos_venta
            )";
    }
} else {
    $sqlcliente = "";
    if (!empty($_GET["id_cliente"])) {
        $sqlcliente = " where id_cliente=" . $_GET["id_cliente"];
    } else {
        $sqlcliente = " where id_cliente in(
        select id_cliente from c_cobrarexternas
        )";
    }
}


$sql = "
select*from clientes
$sqlcliente";

$res = pg_query($sql);
$rows = pg_fetch_all($res);

$y++;
if (!empty($rows)) {
    if ($_GET["tipo"] == 'Internas') {
        foreach ($rows as $row) {
            $sql = "
            (
                select
                fv.id_factura_venta,
                fv.num_factura num_serie,
                fv.fecha_actual fecha_emision,
                pv.monto_credito,
                pv.tipo_documento
                from
                factura_venta fv
                inner join pagos_venta pv
                on pv.id_factura_venta=fv.id_factura_venta
                where pv.id_cliente=$row[id_cliente] 
                and pv.tipo_documento='Factura'
                AND fv.fecha_actual $query_fecha '$_GET[fin]' 
                $query_punto_2 $id_usuario_2
            )
            order by fecha_actual asc;
            ";

            $res = pg_query($sql);
            $rows1 = pg_fetch_all($res);
            if (!empty($rows1)) {
                foreach ($rows1 as $row1) {
                    $sql = "SELECT * 
                    FROM pagos_cobrar p
                    WHERE num_factura='$row1[num_serie]' AND 
                    p.fecha_actual $query_fecha '$fin'   
                    $query_punto $id_usuario
                    ORDER BY comprobante ASC;";
                    $res = pg_query($sql);
                    $rows2 = pg_fetch_all($res);

                    if (!empty($rows2)) {
                        foreach ($rows2 as $row2) {
                            $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue("B" . $y, $row2["comprobante"])
                                ->setCellValueExplicit("C" . $y, $row1["num_serie"], PHPExcel_Cell_DataType::TYPE_STRING)
                                ->setCellValueExplicit("D" . $y, $row["identificacion"], PHPExcel_Cell_DataType::TYPE_STRING)
                                ->setCellValue("E" . $y, $row["nombres_cli"])
                                ->setCellValue("F" . $y, $row1["fecha_emision"])
                                ->setCellValue("G" . $y, $row1["tipo_documento"])
                                ->setCellValue("H" . $y, $row2["forma_pago"])
                                ->setCellValue("I" . $y, $row2["fecha_actual"])
                                ->setCellValue("J" . $y, $row1["monto_credito"])
                                ->setCellValue("K" . $y, $row2["valor_pagado"])
                                ->setCellValue("L" . $y, $row2["saldo_factura"]);
                            $y++;
                        }
                    } else {
                    }

                    /*  $pdf->Line($pdf->GetX(), $pdf->GetY(), 210, $pdf->GetY());
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->Ln(1);
                    $pdf->Cell(84, 6, utf8_decode("TOTAL:"), 0, 0, 'R', 1);
                    $pdf->Cell(42, 6, number_format($tvalorp, 2, ",", "."), 0, 0, 'R', 1);
                    $pdf->Cell(42, 6, number_format($tsaldo, 2, ",", "."), 0, 1, 'R', 1);*/
                }
            } else {
            }
        }
    } else {
        foreach ($rows as $row) {
            $sql = "
            select 
            num_factura num_serie,
            fecha_emicion fecha_emision,
            total monto_credito,
            abreviatura
            from c_cobrarexternas
            inner join tipo_comprobante
            on id_tipo_comprobante=tipo_documento
            where id_cliente='$row[id_cliente]' 
            AND fecha_actual $query_fecha '$_GET[fin]' 
            $query_punto $id_usuario
            order by id_cliente asc
            ";

            $res = pg_query($sql);
            $rows1 = pg_fetch_all($res);
            if (!empty($rows1)) {
                foreach ($rows1 as $row1) {
                    $sql = "SELECT * 
                    FROM pagos_cobrar p
                    WHERE num_factura='$row1[num_serie]' AND 
                    p.fecha_actual $query_fecha '$fin'   
                    $query_punto $id_usuario
                    ORDER BY comprobante ASC;";
                    $res = pg_query($sql);
                    $rows2 = pg_fetch_all($res);
                    if (!empty($rows2)) {
                        foreach ($rows2 as $row2) {
                            $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue("B" . $y, $row2["comprobante"])
                                ->setCellValueExplicit("C" . $y, $row1["num_serie"], PHPExcel_Cell_DataType::TYPE_STRING)
                                ->setCellValueExplicit("D" . $y, $row["identificacion"], PHPExcel_Cell_DataType::TYPE_STRING)
                                ->setCellValue("E" . $y, $row["nombres_cli"])
                                ->setCellValue("F" . $y, $row1["fecha_emision"])
                                ->setCellValue("G" . $y, $row1["abreviatura"])
                                ->setCellValue("H" . $y, $row2["forma_pago"])
                                ->setCellValue("I" . $y, $row2["fecha_actual"])
                                ->setCellValue("J" . $y, $row1["monto_credito"])
                                ->setCellValue("K" . $y, $row2["valor_pagado"])
                                ->setCellValue("L" . $y, $row2["saldo_factura"]);
                            $y++;
                        }
                    } else {
                    }
                }
            } else {
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
    $objPHPExcel->getActiveSheet()->getStyle('B' . $y . ':L' . $y)->applyFromArray($styleArray);
    unset($styleArray);
}

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="' . $Archivo . '"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
