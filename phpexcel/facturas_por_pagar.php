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
$Archivo = "facturas_por_pagar.xls";

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
    ->setCellValue("B2", "FACTURAS PENDIENTES DE PAGO ($tipoCuenta)");
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

$query_punto = "";
if ($_GET['id_empre'] != '0') {
    $query_punto = "AND cp.id_empresa='$_GET[id_empre]'";
}

$id_usuario_cp = "";
if ($_GET['id'] != '0') {
    $id_usuario_cp = "and cp.id_usuario='$_GET[id]'";
}

////////////////////////////

$query_punto_fv = "";
if ($_GET['id_empre'] != '0') {
    $query_punto_fv = "AND g.id_empresa='$_GET[id_empre]'";
}
$id_usuario_fv = "";
if ($_GET['id'] != '0') {
    $id_usuario_fv = "and g.id_usuario='$_GET[id]'";
}

$rango = false;
if ($_GET['inicio'] != '') {
    $rango = true;
}
$query_fecha = "";
// RANGO DE FECHAS O FECHA ACTUAL
if ($rango) {
    $query_fecha = "BETWEEN '$_GET[inicio]' AND";
} else {
    $query_fecha = "=";
}

//GENERAL O POR PROVEEDOR
if (isset($_GET['id_pro']) && $_GET['id_pro'] != '')
    $sql = pg_query(
        "SELECT id_proveedor, tipo_documento, identificacion_pro, empresa_pro, representante_legal 
        FROM proveedores WHERE estado='Activo' and id_proveedor={$_GET['id_pro']}"
    );
else
    $sql = pg_query(
        "SELECT id_proveedor, tipo_documento, identificacion_pro, empresa_pro, representante_legal 
        FROM proveedores WHERE estado='Activo'"
    );


if (pg_num_rows($sql)) {
    if ($_GET['tipo'] == 'Externas') {
        while ($row = pg_fetch_row($sql)) {
            $filas = obtenerCPExternas($row[0]);
            if (count($filas) > 0) {
                foreach ($filas as $row1) {
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValueExplicit("B" . $y, utf8_decode($row[2]), PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValueExplicit("C" . $y, utf8_decode($row[3]), PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValueExplicit("D" . $y, utf8_decode($row1["num_factura"]), PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValue("E" . $y, utf8_decode($row1["fecha_emicion"]))
                        ->setCellValue("F" . $y, utf8_decode($row1["fecha_vencimiento"]))
                        ->setCellValue("G" . $y, utf8_decode($row1["vence"]))
                        ->setCellValue("H" . $y, utf8_decode($row1["abreviatura"]))
                        ->setCellValue("I" . $y, round($row1["total"], 2, PHP_ROUND_HALF_EVEN))
                        ->setCellValue("J" . $y, round($row1["abonos"], 2, PHP_ROUND_HALF_EVEN))
                        ->setCellValue("K" . $y, round($row1["saldo"], 2, PHP_ROUND_HALF_EVEN));
                    $objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":K" . $y)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $y = $y + 1;
                }
            }
        }
    } else if ($_GET['tipo'] == 'Internas') {
        $total = 0;
        while ($row = pg_fetch_row($sql)) {

            $filas = obtenerFacturas($row[0]);
            if (!empty($filas)) {
                foreach ($filas as $row1) {
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValueExplicit("B" . $y, utf8_decode($row[2]), PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValueExplicit("C" . $y, utf8_decode($row[3]), PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValueExplicit("D" . $y, utf8_decode($row1["num_factura"]), PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValue("E" . $y, utf8_decode($row1["fecha_emision"]))
                        ->setCellValue("F" . $y, utf8_decode($row1["fecha_dias"]))
                        ->setCellValue("G" . $y, utf8_decode($row1["tipo"]))
                        ->setCellValue("H" . $y, round($row1["monto_credito"], 2, PHP_ROUND_HALF_EVEN))
                        ->setCellValue("I" . $y, round($row1["renta"], 2, PHP_ROUND_HALF_EVEN))
                        ->setCellValue("J" . $y, round($row1["iva"], 2, PHP_ROUND_HALF_EVEN))
                        ->setCellValue("K" . $y, round($row1["valor_pagado"], 2, PHP_ROUND_HALF_EVEN))
                        ->setCellValue("L" . $y, round($row1["saldo_factura"], 2, PHP_ROUND_HALF_EVEN));
                    $objPHPExcel->getActiveSheet()->getStyle("B" . $y . ":K" . $y)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $y = $y + 1;
                }
            }
        }
    } else {
        while ($row = pg_fetch_row($sql)) {
            $filas = obtenerCpIternasExternas($row[0]);
            if (count($filas) > 0) {
                foreach ($filas as $row1) {
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValueExplicit("B" . $y, utf8_decode($row[2]), PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValueExplicit("C" . $y, utf8_decode($row[3]), PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValueExplicit("D" . $y, utf8_decode($row1["num_factura"]), PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValue("E" . $y, utf8_decode($row1["fecha_emision"]))
                        ->setCellValue("F" . $y, utf8_decode($row1["fecha_actual"]))
                        ->setCellValue("G" . $y, utf8_decode($row1["tipo_documento"]))
                        ->setCellValue("H" . $y, round($row1["total"], 2, PHP_ROUND_HALF_EVEN))
                        ->setCellValue("I" . $y, round($row1["abonos"], 2, PHP_ROUND_HALF_EVEN))
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
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("H" . $y, "=SUBTOTAL(109,H8:H" . ($y - 1) . ")")
            ->setCellValue("I" . $y, "=SUBTOTAL(109,I8:I" . ($y - 1) . ")")
            ->setCellValue("J" . $y, "=SUBTOTAL(109,J8:J" . ($y - 1) . ")")
            ->setCellValue("K" . $y, "=SUBTOTAL(109,K8:K" . ($y - 1) . ")")
            ->setCellValue("L" . $y, "=SUBTOTAL(109,L8:L" . ($y - 1) . ")");
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

function obtenerFacturas($idproveedor)
{
    global $query_punto_fv, $id_usuario_fv;

    $sql = "
    (select*from(
        select
        row_number() over(partition by g.id_factura_compra order by  pp.id_cuentas_pagar DESC) rn,
        p.identificacion_pro,
        p.empresa_pro,
        p.representante_legal,
        g.comprobante,
        g.num_serie as num_factura,
        fpg.fecha_actual as fecha_dias,
        g.total_compra as total,
        pc.monto_credito,
        round(coalesce(sum(pp.valor_pagado)over(partition by g.id_factura_compra),0),2)valor_pagado,
        round(coalesce(pp.saldo_factura,pc.monto_credito,0),2)saldo_factura,
        pp.fecha_actual,
        'C'::text as tipo,
        SUM(drv1.valor_retenido) as renta,
        SUM( drv2.valor_retenido) as iva,
        g.fecha_emision
        from factura_compra g
        inner join formas_pago_mixto_c fpg
        on g.id_factura_compra=fpg.id_factura_compra
        inner join proveedores p
        on p.id_proveedor=g.id_proveedor
         
        left join pagos_compra pc 
        on pc.id_factura_compra=g.id_factura_compra
        and pc.comprao_gasto='C'         
        left JOIN retencion_fuente_factura_compra rfc 
        ON pc.id_factura_compra=rfc.id_factura and rfc.estado <> 'g' and rfc.id_gastos=1  
        LEFT JOIN detallecomprobanteretencion drv1
                    on drv1.id_retencion_fuente_factura_compra=rfc.id_retencion_fuente_factura_compra
                    and drv1.id_trete=1
                    LEFT JOIN detallecomprobanteretencion drv2
                    on drv2.id_retencion_fuente_factura_compra=rfc.id_retencion_fuente_factura_compra  
                    and drv2.id_trete=2                      
        left join pagos_pagar pp
        on pp.id_factura_compra=g.id_factura_compra
        and pp.comprao_gasto='C'
        where fpg.forma_pago='CREDITO' 
        and g.estado='Activo'
        and g.id_proveedor='$idproveedor' $query_punto_fv $id_usuario_fv
        and pc.fecha_credito BETWEEN '$_GET[inicio]' AND '$_GET[fin]' 
        GROUP BY p.identificacion_pro,p.empresa_pro,p.representante_legal,g.comprobante,g.num_serie,fpg.fecha_actual,g.total_compra,
        pc.monto_credito,pp.valor_pagado,pp.saldo_factura,pp.fecha_actual,pp.id_cuentas_pagar,g.id_factura_compra
      
        )as cuentas_compras
        where rn =1
        and saldo_factura>0 )
        union all
        (select*from(
        select
        row_number() over(partition by g.id_gastos order by  pp.id_cuentas_pagar DESC) rn,
        p.identificacion_pro,
        p.empresa_pro,
        p.representante_legal,
        g.comprobante::text,
        g.num_factura,
        fpg.fecha_actual as fecha_dias,
        g.total,
        pc.monto_credito,
        round(coalesce(sum(pp.valor_pagado)over(partition by g.id_gastos),0),2)valor_pagado,
        round(coalesce(pp.saldo_factura,pc.monto_credito,0),2)saldo_factura,
        pp.fecha_actual,
        'G'::text as tipo,
        SUM(drv1.valor_retenido) as renta,
        SUM( drv2.valor_retenido) as iva,
        g.fecha_emision
        from gastos g
        inner join formas_pago_mixto_g fpg
        on g.id_gastos=fpg.id_gastos
        inner join proveedores p
        on p.id_proveedor=g.id_proveedor
        left join pagos_compra pc 
        on pc.id_factura_compra=g.id_gastos
        and pc.comprao_gasto='G' 
        left JOIN retencion_fuente_factura_compra rfc 
        ON pc.id_factura_compra=rfc.id_factura and rfc.estado <> 'g' and rfc.id_gastos=10
          LEFT JOIN detallecomprobanteretencion drv1
                    on drv1.id_retencion_fuente_factura_compra=rfc.id_retencion_fuente_factura_compra
                    and drv1.id_trete=1
                    LEFT JOIN detallecomprobanteretencion drv2
                    on drv2.id_retencion_fuente_factura_compra=rfc.id_retencion_fuente_factura_compra  
                    and drv2.id_trete=2         
        left join pagos_pagar pp
        on pp.id_factura_compra=g.id_gastos
        and pp.comprao_gasto='G'
        where fpg.forma_pago='CREDITO' 
        and g.estado='Activo'
        and g.id_proveedor='$idproveedor' $query_punto_fv $id_usuario_fv
        and pc.fecha_credito BETWEEN '$_GET[inicio]' AND '$_GET[fin]' 
        GROUP BY p.identificacion_pro,p.empresa_pro,p.representante_legal,g.comprobante,g.num_serie,fpg.fecha_actual,g.total_compra,
        pc.monto_credito,pp.valor_pagado,pp.saldo_factura,pp.fecha_actual,pp.id_cuentas_pagar,g.id_gastos
    
        )as cuentas_gastos
        where rn =1
        and saldo_factura>0 
        )
   
        order by comprobante
    ";
    //var_dump($sql);
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (!$rows) {
        return [];
    }
    return $rows;
}

function obtenerCPExternas($idproveedor)
{
    global $query_fecha, $query_punto,  $id_usuario_cp;
    $sqlext = "SELECT DISTINCT 
        ON (cp.comprobante) cp.comprobante, cp.fecha_actual, descripcion, 
            cp.num_factura, total, (total::numeric - saldo::numeric) as abonos, saldo, pp.fecha_actual as fecha_pago,
            cp.fecha_emicion, cp.fecha_vencimiento, abreviatura,
            (cp.fecha_vencimiento::date-date(now())) vence
            FROM tipo_comprobante tc, c_pagarexternas cp
            LEFT JOIN pagos_pagar pp USING (num_factura)
            WHERE cp.tipo_documento=tc.id_tipo_comprobante 
            AND cp.estado='Activo'
            AND cp.id_proveedor='$idproveedor'
            AND cp.fecha_actual $query_fecha '$_GET[fin]' 
            $query_punto 
            $id_usuario_cp
            ORDER BY cp.comprobante asc, fecha_pago desc;";

    $res = pg_query($sqlext);
    $rows = pg_fetch_all($res);
    if (!$rows) {
        return [];
    }
    return $rows;
}

function obtenerCpIternasExternas($idproveedor)
{
    global $query_punto, $id_usuario_cp, $query_punto_fv, $id_usuario_fv;
    $sql = "
    (
        select*from( 
        select  
        g.comprobante,
        row_number() over(partition by g.id_factura_compra order by pp.id_cuentas_pagar DESC) rn, 
        g.num_serie as num_factura, 
        fpg.fecha_actual,
        pc.monto_credito total, 
        round(coalesce(sum(pp.valor_pagado)over(partition by g.id_factura_compra),0),2)abonos, 
        round(coalesce(pp.saldo_factura,pc.monto_credito,0),2)saldo, 
        pp.fecha_actual fecha_pago, 
        'C'::text as descripcion,
        'I'::text tipo,
        g.fecha_emision,
        pc.tipo_documento
        from factura_compra g inner join formas_pago_mixto_c fpg 
        on g.id_factura_compra=fpg.id_factura_compra 
        inner join proveedores p 
        on p.id_proveedor=g.id_proveedor 
        left join pagos_compra pc 
        on pc.id_factura_compra=g.id_factura_compra 
        and pc.comprao_gasto='C' 
        left JOIN retencion_fuente_factura_compra rfc 
        ON pc.id_factura_compra=rfc.id_factura 
        and rfc.estado <> 'g' and rfc.id_gastos=1 
        LEFT JOIN detallecomprobanteretencion drv1 
        on drv1.id_retencion_fuente_factura_compra=rfc.id_retencion_fuente_factura_compra 
        and drv1.id_trete=1 
        LEFT JOIN detallecomprobanteretencion drv2 
        on drv2.id_retencion_fuente_factura_compra=rfc.id_retencion_fuente_factura_compra 
        and drv2.id_trete=2 
        left join pagos_pagar pp 
        on pp.id_factura_compra=g.id_factura_compra and pp.comprao_gasto='C'
        where fpg.forma_pago='CREDITO' and g.estado='Activo' and g.id_proveedor='$idproveedor' 
        and pc.fecha_credito BETWEEN '$_GET[inicio]' AND '$_GET[fin]' 
        $query_punto_fv $id_usuario_fv
        GROUP BY p.identificacion_pro,p.empresa_pro,p.representante_legal,g.comprobante,g.num_serie,fpg.fecha_actual,
        g.total_compra, pc.monto_credito,pp.valor_pagado,pp.saldo_factura,pp.fecha_actual,pp.id_cuentas_pagar,g.id_factura_compra,
        pc.tipo_documento )
        as cuentas_compras where rn =1 and saldo>0 ) 
        union all (
        select*from( select 
        g.comprobante::text,
        row_number() over(partition by g.id_gastos order by pp.id_cuentas_pagar DESC) rn,  
        g.num_factura, 
        fpg.fecha_actual,  
        pc.monto_credito total, 
        round(coalesce(sum(pp.valor_pagado)over(partition by g.id_gastos),0),2)abonos, 
        round(coalesce(pp.saldo_factura,pc.monto_credito,0),2)saldo, 
        pp.fecha_actual, 'G'::text as descripcion,
        'I'::text tipo,
        g.fecha_emision,
        pc.tipo_documento
        from gastos g inner join formas_pago_mixto_g fpg 
        on g.id_gastos=fpg.id_gastos inner join proveedores p on p.id_proveedor=g.id_proveedor 
        left join pagos_compra pc on pc.id_factura_compra=g.id_gastos and pc.comprao_gasto='G' 
        left JOIN retencion_fuente_factura_compra rfc ON pc.id_factura_compra=rfc.id_factura and rfc.estado <> 'g' and rfc.id_gastos=10 
        LEFT JOIN detallecomprobanteretencion drv1 
        on drv1.id_retencion_fuente_factura_compra=rfc.id_retencion_fuente_factura_compra 
        and drv1.id_trete=1 LEFT JOIN detallecomprobanteretencion drv2 
        on drv2.id_retencion_fuente_factura_compra=rfc.id_retencion_fuente_factura_compra and drv2.id_trete=2 
        left join pagos_pagar pp on pp.id_factura_compra=g.id_gastos and pp.comprao_gasto='G' 
        where fpg.forma_pago='CREDITO' and g.estado='Activo' and g.id_proveedor='$idproveedor' 
        and pc.fecha_credito BETWEEN '$_GET[inicio]' AND '$_GET[fin]' 
        $query_punto_fv $id_usuario_fv
        GROUP BY p.identificacion_pro,p.empresa_pro,p.representante_legal,g.comprobante,g.num_serie,fpg.fecha_actual,
        g.total_compra, pc.monto_credito,pp.valor_pagado,pp.saldo_factura,pp.fecha_actual,pp.id_cuentas_pagar,g.id_gastos, pc.tipo_documento )
        as cuentas_gastos where rn =1 and saldo>0)
        union all(
        SELECT DISTINCT ON (cp.comprobante) cp.comprobante,0 rn,
        cp.num_factura,cp.fecha_actual::date,total::numeric, 
        (total::numeric - saldo::numeric) as abonos, 
        saldo::numeric, pp.fecha_actual as fecha_pago,descripcion,
        'E'::text tipo,
        fecha_emicion::date,
        abreviatura
        FROM tipo_comprobante tc, c_pagarexternas cp 
        LEFT JOIN pagos_pagar pp USING (num_factura) 
        WHERE cp.tipo_documento=tc.id_tipo_comprobante 
        AND cp.estado='Activo' AND cp.id_proveedor='$idproveedor' 
        AND cp.fecha_actual BETWEEN '$_GET[inicio]' AND '$_GET[fin]' 
        $query_punto 
        $id_usuario_cp
        ORDER BY cp.comprobante asc, fecha_pago desc
        )
        order by fecha_emision asc
    ";

    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (!$rows) {
        return [];
    }
    return $rows;
}
