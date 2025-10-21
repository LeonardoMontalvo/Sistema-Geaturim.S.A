<?php
session_start();
date_default_timezone_set('America/Guayaquil');
error_reporting(0);
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
$Archivo = "resumen_facturas_pendientes_cxc.xls";

// Propiedades de archivo Excel
$objPHPExcel->getProperties()->setCreator("P&S Systems")
    ->setLastModifiedBy("P&S Systems")
    ->setTitle("Reporte XLS")
    ->setSubject("FACTURAS POR COBRAR ($tipoCuenta)")
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
    ->setCellValue("B2", "FACTURAS POR COBRAR ($tipoCuenta)");
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

if ($_GET['tipo'] == 'Externas') {
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("B" . $y, 'RUC Cliente')
        ->setCellValue("C" . $y, 'Cliente')
        ->setCellValue("D" . $y, 'Nro. Documento')
        ->setCellValue("E" . $y, 'Emisión')
        ->setCellValue("F" . $y, 'Vencimiento')
        ->setCellValue("G" . $y, 'Caduca (Dìas)')
        ->setCellValue("H" . $y, 'Tipo Doc')
        ->setCellValue("I" . $y, 'Total')
        ->setCellValue("J" . $y, 'Abonos')
        ->setCellValue("K" . $y, 'Saldo');

    $objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":K" . $y)->getFont()->setBold(true)->setName('Verdana')->setSize(10);
    $objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":K" . $y)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
} elseif ($_GET['tipo'] == 'Internas') {
    if ($_GET['tipo_documento'] == 'factura') {
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("B" . $y, 'RUC Cliente')
            ->setCellValue("C" . $y, 'Cliente')
            ->setCellValue("D" . $y, 'Nro. Documento')
            ->setCellValue("E" . $y, 'Emisión')
            ->setCellValue("F" . $y, 'Vencimiento')
            ->setCellValue("G" . $y, 'Tipo Doc')
            ->setCellValue("H" . $y, 'Total')
            ->setCellValue("I" . $y, '- R. Fuente')
            ->setCellValue("J" . $y, '- R. Iva')
            ->setCellValue("K" . $y, 'Abonos')
            ->setCellValue("L" . $y, 'Saldo');
        $objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":L" . $y)->getFont()->setBold(true)->setName('Verdana')->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":L" . $y)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    } elseif ($_GET['tipo_documento'] == 'nota1') {
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("B" . $y, 'RUC Cliente')
            ->setCellValue("C" . $y, 'Cliente')
            ->setCellValue("D" . $y, 'Nro. Documento')
            ->setCellValue("E" . $y, 'Emisión')
            ->setCellValue("F" . $y, 'Vencimiento')
            ->setCellValue("G" . $y, 'Caduca (Dìas)')
            ->setCellValue("H" . $y, 'Tipo Doc')
            ->setCellValue("I" . $y, 'Total')
            ->setCellValue("J" . $y, 'Abonos')
            ->setCellValue("K" . $y, 'Saldo');
        $objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":K" . $y)->getFont()->setBold(true)->setName('Verdana')->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":K" . $y)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    }
} else {
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
$y++;

$querycliente = "";
if (!empty($_GET["id_cliente"])) {
    $querycliente = " where id_cliente=" . $_GET["id_cliente"];
}

if(!empty($_GET['id_ruta'])){
    $querycliente=" where credito_cupo='".$_GET['id_ruta']."'";
}

if(!empty($_GET['id_vendedor'])){
    $querycliente=" where
    credito_cupo in (select id_ruta from rutas 
    where id_vendedor=".$_GET['id_vendedor'].")
    ";
}

$consulta = pg_query(
    "SELECT * from clientes $querycliente order by id_cliente asc;"
);
if (pg_num_rows($consulta)) {
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
        $query_punto = "AND fv.id_empresa='$_GET[id_empre]'";
    }


    $id_usuario = "";
    if ($_GET['id'] != '0') {
        $id_usuario = "and c.id_usuario='$_GET[id]'";
    }
    $id_usuario_fv = "";
    if ($_GET['id'] != '0') {
        $id_usuario_fv = "and fv.id_usuario='$_GET[id]'";
    }

    while ($row = pg_fetch_row($consulta)) {
        if ($_GET['tipo'] == 'Externas') {
            $tipo;
            if ($_GET['tipo_documento'] == 'factura') {
                $tipo = 1;
            } elseif ($_GET['tipo_documento'] == 'nota') {
                $tipo = 2;
            }
            $sqltxt = "
            SELECT comprobante, descripcion, num_factura, total, total::numeric-saldo::numeric, saldo, fecha_actual,
            fecha_emicion, fecha_vencimiento, abreviatura, (fecha_vencimiento::date-date(now())) vence
                FROM c_cobrarexternas c left join tipo_comprobante t 
                on c.tipo_documento=t.id_tipo_comprobante 
                where id_cliente='$row[0]' and c.estado='Activo' $id_usuario
                and  fecha_actual $query_fecha '$_GET[fin]' ;
            ";
            $sql = pg_query($sqltxt);
            if (pg_num_rows($sql)) {
                while ($row1 = pg_fetch_row($sql)) {
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValueExplicit("B" . $y, utf8_decode($row[2]), PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValueExplicit("C" . $y, utf8_decode($row[3]), PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValueExplicit("D" . $y, utf8_decode($row1[2]), PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValue("E" . $y, utf8_decode($row1[7]))
                        ->setCellValue("F" . $y, utf8_decode($row1[8]))
                        ->setCellValue("G" . $y, utf8_decode($row1[10]))
                        ->setCellValue("H" . $y, utf8_decode($row1[9]))
                        ->setCellValue("I" . $y, round($row1[3], 2, PHP_ROUND_HALF_EVEN))
                        ->setCellValue("J" . $y, round($row1[4], 2, PHP_ROUND_HALF_EVEN))
                        ->setCellValue("K" . $y, round($row1[5], 2, PHP_ROUND_HALF_EVEN));
                    $objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":K" . $y)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $y = $y + 1;
                }
            }
        } else if ($_GET['tipo'] == 'Internas') {
            if ($_GET['tipo_documento'] == 'factura') {
                $sqltxt = " SELECT pv.id_factura_venta, fpm.tipo_documento, 
                fv.num_factura, total_venta, valor, 
                SUM(drv1.valor_retenido) renta,
                SUM( drv2.valor_retenido) iva,
                monto_credito, 
                monto_credito::numeric-saldo::numeric abonos, 
                saldo, 
                fpm.fecha_actual as fecha_dias
                FROM factura_venta fv 
                LEFT JOIN pagos_venta pv 
                USING(id_factura_venta)
                left JOIN (select id_factura,id_retencion_fuente_factura_venta from retencion_fuente_factura_venta) rf  
                ON pv.id_factura_venta=rf.id_factura 
                 LEFT JOIN formas_pago_mixto fpm
                on fpm.id_factura_venta=fv.id_factura_venta   
                LEFT JOIN detallecomprobanteretencion_v drv1
                on drv1.id_retencion_fuente_factura_venta=rf.id_retencion_fuente_factura_venta    
                and drv1.id_trete=1
                LEFT JOIN detallecomprobanteretencion_v drv2
                on drv2.id_retencion_fuente_factura_venta=rf.id_retencion_fuente_factura_venta    
                and drv2.id_trete=2
                WHERE fv.estado='Activo' and pv.id_cliente='$row[0]' and pv.estado='Activo' and fv.forma_pago='otros' 
                $id_usuario_fv and fv.forma_pago='otros' 
                and pv.fecha_credito $query_fecha '$_GET[fin]' 
                and pv.tipo_documento='Factura' 
                and (fpm.forma_pago='CREDITO' or fpm.forma_pago='CPOSFECHADO') $query_punto
                and fpm.tipo_documento='FACTURA'
                GROUP BY pv.id_pagos_venta, fpm.tipo_documento, 
                fv.num_factura, total_venta, valor, 
                monto_credito, 
                monto_credito::numeric-saldo::numeric, 
                saldo, 
               fpm.fecha_actual
                order by pv.id_pagos_venta asc
               ;";
                $sql = pg_query($sqltxt);
                if (pg_num_rows($sql)) {
                    while ($row1 = pg_fetch_row($sql)) {
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValueExplicit("B" . $y, utf8_decode($row[2]), PHPExcel_Cell_DataType::TYPE_STRING)
                            ->setCellValueExplicit("C" . $y, utf8_decode($row[3]), PHPExcel_Cell_DataType::TYPE_STRING)
                            ->setCellValueExplicit("D" . $y, utf8_decode($row1[2]), PHPExcel_Cell_DataType::TYPE_STRING)
                            ->setCellValue("E" . $y, utf8_decode($row1[10]))
                            ->setCellValue("F" . $y, utf8_decode($row1[10]))
                            ->setCellValue("G" . $y, utf8_decode($row1[1]))
                            ->setCellValue("H" . $y, round($row1[7], 2, PHP_ROUND_HALF_EVEN))
                            ->setCellValue("I" . $y, round($row1[5], 2, PHP_ROUND_HALF_EVEN))
                            ->setCellValue("J" . $y, round($row1[6], 2, PHP_ROUND_HALF_EVEN))
                            ->setCellValue("K" . $y, round($row1[8], 2, PHP_ROUND_HALF_EVEN))
                            ->setCellValue("L" . $y, round($row1[9], 2, PHP_ROUND_HALF_EVEN));
                        $objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":K" . $y)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $y = $y + 1;
                    }
                }
            } elseif ($_GET['tipo_documento'] == 'nota1') {
                $filas = obtenerCCInternasNota($row[0]);
                if (count($filas)) {
                    foreach ($filas as $row1) {
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValueExplicit("B" . $y, utf8_decode($row[2]), PHPExcel_Cell_DataType::TYPE_STRING)
                            ->setCellValueExplicit("C" . $y, utf8_decode($row[3]), PHPExcel_Cell_DataType::TYPE_STRING)
                            ->setCellValueExplicit("D" . $y, utf8_decode($row1["num_factura"]), PHPExcel_Cell_DataType::TYPE_STRING)
                            ->setCellValue("E" . $y, utf8_decode($row1["fecha_actual"]))
                            ->setCellValue("F" . $y, utf8_decode($row1["fecha_vencimiento"]))
                            ->setCellValue("G" . $y, utf8_decode($row1["vence"]))
                            ->setCellValue("H" . $y, utf8_decode($row1["descripcion"]))
                            ->setCellValue("I" . $y, round($row1["total"], 2, PHP_ROUND_HALF_EVEN))
                            ->setCellValue("J" . $y, round($row1["valor_pagado"], 2, PHP_ROUND_HALF_EVEN))
                            ->setCellValue("K" . $y, round($row1["saldo"], 2, PHP_ROUND_HALF_EVEN));
                        $objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":K" . $y)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $y = $y + 1;
                    }
                }
            }
        } else {
            $filas = obtenerCCInternasExternas($row[0]);
            if (count($filas)) {

                foreach ($filas as $row1) {
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValueExplicit("B" . $y, utf8_decode($row[2]), PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValueExplicit("C" . $y, utf8_decode($row[3]), PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValueExplicit("D" . $y, utf8_decode($row1["num_factura"]), PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValue("E" . $y, utf8_decode($row1["fecha_emision"]))
                        ->setCellValue("F" . $y, utf8_decode($row1["fecha_vencimiento"]))
                        ->setCellValue("G" . $y, utf8_decode($row1["descripcion"]))
                        ->setCellValue("H" . $y, round($row1["total"], 2, PHP_ROUND_HALF_EVEN))
                        ->setCellValue("I" . $y, round($row1["valor_pagado"], 2, PHP_ROUND_HALF_EVEN))
                        ->setCellValue("J" . $y, round($row1["saldo"], 2, PHP_ROUND_HALF_EVEN))
                        ->setCellValue("K" . $y, utf8_decode($row1["tipo"]));
                    $objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":K" . $y)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $y = $y + 1;
                }
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
    $y++;
    if ($_GET['tipo'] == 'Externas') {
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("I" . $y, "=SUBTOTAL(109,I8:I" . ($y - 1) . ")")
            ->setCellValue("J" . $y, "=SUBTOTAL(109,J8:J" . ($y - 1) . ")")
            ->setCellValue("K" . $y, "=SUBTOTAL(109,K8:K" . ($y - 1) . ")");
    } elseif ($_GET['tipo'] == 'Internas') {
        if ($_GET['tipo_documento'] == 'factura') {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("H" . $y, "=SUBTOTAL(109,H8:H" . ($y - 1) . ")")
                ->setCellValue("I" . $y, "=SUBTOTAL(109,I8:I" . ($y - 1) . ")")
                ->setCellValue("J" . $y, "=SUBTOTAL(109,J8:J" . ($y - 1) . ")")
                ->setCellValue("K" . $y, "=SUBTOTAL(109,K8:K" . ($y - 1) . ")")
                ->setCellValue("L" . $y, "=SUBTOTAL(109,L8:L" . ($y - 1) . ")");
        } elseif ($_GET['tipo_documento'] == 'nota1') {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("I" . $y, "=SUBTOTAL(109,I8:I" . ($y - 1) . ")")
                ->setCellValue("J" . $y, "=SUBTOTAL(109,J8:J" . ($y - 1) . ")")
                ->setCellValue("K" . $y, "=SUBTOTAL(109,K8:K" . ($y - 1) . ")");
        }
    } else {
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("H" . $y, "=SUBTOTAL(109,H8:H" . ($y - 1) . ")")
            ->setCellValue("I" . $y, "=SUBTOTAL(109,I8:I" . ($y - 1) . ")")
            ->setCellValue("J" . $y, "=SUBTOTAL(109,J8:J" . ($y - 1) . ")");
    }
}

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="' . $Archivo . '"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

function obtenerCCInternasExternas($idcliente)
{
    global $id_usuario_fv, $query_punto, $id_usuario, $query_fecha, $tipo;
    $sql = "(SELECT 
    pv.id_factura_venta::text comprobante, 
    fpm.tipo_documento descripcion, 
    fv.num_factura, 
    total_venta total, 
    monto_credito::numeric-saldo::numeric valor_pagado, 
    saldo, 
    pv.fecha_dias as fecha_pago,
    'I'::text tipo,
    fv.fecha_actual fecha_emision,
    fpm.fecha_actual fecha_vencimiento
    FROM factura_venta fv 
    LEFT JOIN pagos_venta pv USING(id_factura_venta) 
    left JOIN (select id_factura,id_retencion_fuente_factura_venta 
    from retencion_fuente_factura_venta) rf ON pv.id_factura_venta=rf.id_factura 
    LEFT JOIN formas_pago_mixto fpm on fpm.id_factura_venta=fv.id_factura_venta 
    LEFT JOIN detallecomprobanteretencion_v drv1 
    on drv1.id_retencion_fuente_factura_venta=rf.id_retencion_fuente_factura_venta and drv1.id_trete=1 
    LEFT JOIN detallecomprobanteretencion_v drv2 on drv2.id_retencion_fuente_factura_venta=rf.id_retencion_fuente_factura_venta and drv2.id_trete=2 
    WHERE fv.estado='Activo' and pv.id_cliente='$idcliente' and pv.estado='Activo' and fv.forma_pago='otros' 
    $id_usuario_fv $query_punto
    and pv.fecha_credito $query_fecha '$_GET[fin]'  
    and (fpm.forma_pago='CREDITO' or fpm.forma_pago='CPOSFECHADO')
    and fpm.tipo_documento='FACTURA'
    and pv.tipo_documento = 'Factura'
     GROUP BY pv.id_pagos_venta, fpm.tipo_documento, 
    fv.num_factura, total_venta, valor, monto_credito, 
    monto_credito::numeric-saldo::numeric, saldo, 
    fpm.fecha_actual,fv.fecha_actual 
    order by pv.id_pagos_venta asc)
    union all
    (
    SELECT comprobante, descripcion, num_factura, total::numeric, 
    total::numeric-saldo::numeric valor_pagado, saldo, fecha_actual::date,
    'E'::text tipo, fecha_emicion::date, fecha_vencimiento::date
    FROM c_cobrarexternas c 
    left join tipo_comprobante t on c.tipo_documento=t.id_tipo_comprobante 
    where id_cliente='$idcliente' and c.estado='Activo' 
    $id_usuario
    and  fecha_actual $query_fecha '$_GET[fin]' 
    )
    union all
    (
    SELECT pv.id_factura_venta::text comprobante, 
    fpm.tipo_documento descripcion, 
    fv.comprobante num_factura, 
    total_venta total, 
    monto_credito::numeric-saldo::numeric valor_pagado, 
    saldo, 
    pv.fecha_dias as fecha_pago,
    'I'::text tipo,
    fv.fecha_actual fecha_emision,
    fpm.fecha_actual fecha_caducidad
    FROM facturas_novalidas fv 
    LEFT JOIN pagos_venta pv on pv.id_factura_venta=fv.id_facturas_novalidas
    left JOIN (select id_factura,id_retencion_fuente_factura_venta from retencion_fuente_factura_venta) rf ON pv.id_factura_venta=rf.id_factura 
    LEFT JOIN formas_pago_mixto fpm on fpm.id_factura_venta=fv.id_facturas_novalidas 
    WHERE fv.estado='Activo' 
    and pv.id_cliente='$idcliente' 
    and pv.estado='Activo' 
    and fv.forma_pago='otros' 
    $id_usuario_fv $query_punto
    and pv.fecha_credito $query_fecha '$_GET[fin]'  
    and pv.tipo_documento='Nota' 
    and (fpm.forma_pago='CREDITO' or fpm.forma_pago='CPOSFECHADO')  
    and fpm.tipo_documento='NOTA'
    GROUP BY pv.id_pagos_venta, fpm.tipo_documento, fv.comprobante, 
    total_venta, valor, monto_credito, saldo, fpm.fecha_actual, fv.fecha_actual
    order by pv.id_pagos_venta asc
    )";

    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (!$rows) {
        return [];
    }
    return $rows;
}

function obtenerCCInternasNota($idcliente)
{
    global $id_usuario_fv, $query_punto, $query_fecha;
    $sql = "
    SELECT pv.id_factura_venta::text comprobante, 
    fpm.tipo_documento descripcion, 
    fv.comprobante num_factura, 
    total_venta total, 
    monto_credito::numeric-saldo::numeric valor_pagado, 
    saldo, 
    pv.fecha_dias as fecha_pago,
    fv.fecha_actual,
    fpm.fecha_actual fecha_vencimiento,
    (fpm.fecha_actual-date(now())) vence
    FROM facturas_novalidas fv 
    LEFT JOIN pagos_venta pv on pv.id_factura_venta=fv.id_facturas_novalidas
    left JOIN (select id_factura,id_retencion_fuente_factura_venta from retencion_fuente_factura_venta) rf ON pv.id_factura_venta=rf.id_factura 
    LEFT JOIN formas_pago_mixto fpm on fpm.id_factura_venta=fv.id_facturas_novalidas 
    WHERE fv.estado='Activo' 
    and pv.id_cliente='$idcliente' 
    and pv.estado='Activo' 
    and fv.forma_pago='otros' 
    $id_usuario_fv $query_punto
    and pv.fecha_credito $query_fecha '$_GET[fin]'  
    and pv.tipo_documento='Nota' 
    and (fpm.forma_pago='CREDITO' or fpm.forma_pago='CPOSFECHADO')  
    and fpm.tipo_documento='NOTA'
    GROUP BY pv.id_pagos_venta, fpm.tipo_documento, fv.comprobante, 
    total_venta, valor, monto_credito, saldo, fpm.fecha_actual, fv.fecha_actual
    order by pv.id_pagos_venta asc ;
    ";

    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (!$rows) {
        return [];
    }
    return $rows;
}
