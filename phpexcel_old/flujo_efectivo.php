<?php
require_once "PHPExcel.php";
include '../procesos/base.php';
include '../procesos/funciones.php';
require_once __DIR__ . "/../data/reporte_flujo_efectivo/UtilJsonFile.php";
conectarse();
date_default_timezone_set('America/Guayaquil');
session_start();

$filename = __DIR__ . "/../data/reporte_flujo_efectivo/cuentas.json";
$cuentas = [];
UtilJsonFile::cargarJson($cuentas, $filename);
$cuentasefectivo = implode(",", $cuentas["efectivo"]);

$inicio = $_GET["inicio"];
$fin = $_GET["fin"];

$objPHPExcel = new PHPExcel();
$Archivo = "flujo_efectivo.xls";

$objPHPExcel->getProperties()->setCreator("COLEGIO DE CONTADORES IMBABURA")
    ->setLastModifiedBy("P&S Systems")
    ->setTitle("Reporte XLS")
    ->setSubject("FLUJO EFECTIVO")
    ->setDescription("")
    ->setKeywords("")
    ->setCategory("");

$objPHPExcel->getDefaultStyle()->getFont()->setName('Verdana');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);

mostrarEncabezado();
$coli = 0; //0 columna A
$rowi = $rowf = 10; //1 columna 1
$colt = mostrarCabezeraMeses($coli + 1, $rowf - 1);
$res = mostrarMatrizCuentasEfectivoEntrada($coli, $rowi, $rowf);
$rowi = $rowf += 1;
$res1 = mostrarMatrizCuentasEfectivoSalida($coli, $rowi, $rowf);
$rowi = $rowf += 1;
$resfn = mostrarFlujoNetoEfectivo(
    $coli,
    $rowf,
    $res["totales"]["columnas"],
    $res["totales"]["celdas"],
    $res1["totales"]["celdas"]
);
$rowi = $rowfsi = $rowf += 1;
$ressi = mostrarSaldoInicial($coli, $rowf, $res["totales"]["columnas"], array());
$rowi = $rowf += 1;
$saldos = array("neto" => $resfn["totales"]["celdas"], "inicial" => $ressi["totales"]["celdas"]);
$ressf = mostrarSaldoFinal($coli, $rowf, $res["totales"]["columnas"], $saldos);
$ressi = mostrarSaldoInicial($coli, $rowfsi, $res["totales"]["columnas"], array_merge(array(saldoInicial($cuentasefectivo, $inicio)), $ressf["totales"]["celdas"]));

$objPHPExcel
    ->getActiveSheet()
    ->getColumnDimensionByColumn($coli)
    ->setWidth(35);
$objPHPExcel
    ->getActiveSheet()
    ->getStyle("A$rowi:A$rowf")
    ->getAlignment()
    ->setWrapText(true);

$filas["entradas"] = [$res["fila_inicio"], $res["fila_fin"]];
$filas["salidas"] = [$res1["fila_inicio"], $res1["fila_fin"]];
//mostrarTotalesFilas(10, $rowf, $colt, $res["totales"]["columnas"]);
mostrarTotalesFilas($filas, $colt, $res["totales"]["columnas"]);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="' . $Archivo . '"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

function obtenerIntervalosFecha($inicio, $fin)
{
    $start    = (new DateTime($inicio))->modify('first day of this month');
    $end      = (new DateTime($fin))->modify('first day of next month');
    $interval = DateInterval::createFromDateString('1 month');
    $period   = new DatePeriod($start, $interval, $end);

    $rangos = [];
    foreach ($period as $dt) {
        array_push($rangos, [$dt->format("Y-m-d"), $dt->format("Y-m-t")]);
    }
    $rangos[0][0] = (new DateTime($inicio))->format("Y-m-d");
    $rangos[count($rangos) - 1][1] = (new DateTime($fin))->format("Y-m-d");

    return $rangos;
}

function obtenerCuentasEfectivoEntrada($fechainicio, $fechafin, $cuentas)
{
    $sql = "
    with x as(select
    distinct on(t.id_transacciones)t.id_transacciones
    from transacciones t
    inner join detalle_transaccion dt
    on t.id_transacciones=dt.id_transacciones
    where dt.id_plan_cuentas in($cuentas)
    and t.estado='Activo'
    and dt.debito>0
    and t.fecha_registro between '$fechainicio' and '$fechafin'
    order by t.id_transacciones asc),
    y as(
    select
    distinct on(dt.id_transacciones)dt.id_transacciones,
    first_value(dt.id_plan_cuentas)over(
    partition by dt.id_transacciones 
    order by dt.id_transacciones asc,dt.credito desc)id_plan_cuentas
    from transacciones t
    inner join detalle_transaccion dt
    on t.id_transacciones=dt.id_transacciones
    where t.id_transacciones in(select * from x)
    and dt.credito>0
    group by dt.id_transacciones,dt.id_plan_cuentas,dt.debito,dt.credito
    order by dt.id_transacciones asc,dt.credito desc),
    z as(
    select
    dt.id_transacciones,
    dt.debito
    from transacciones t
    inner join detalle_transaccion dt
    on t.id_transacciones=dt.id_transacciones
    where t.id_transacciones in(select * from x)
    and dt.id_plan_cuentas in($cuentas)
    and dt.debito>0
    group by dt.id_transacciones,dt.id_plan_cuentas,dt.debito,dt.credito
    order by dt.id_transacciones asc,dt.credito desc)
    select
    pc.id_plan_cuentas,
    pc.descripcion,
    sum(z.debito)
    from y
    inner join z
    on y.id_transacciones=z.id_transacciones
    inner join plan_cuentas pc using(id_plan_cuentas)
    where pc.id_plan_cuentas not in ($cuentas)
    group by pc.id_plan_cuentas
    order by pc.codigo_plan
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    return (!$rows ? [] : $rows);
}

function obtenerCuentasEfectivoSalida($fechainicio, $fechafin, $cuentas)
{
    $sql = "
    with x as(select
    distinct on(t.id_transacciones)t.id_transacciones
    from transacciones t
    inner join detalle_transaccion dt
    on t.id_transacciones=dt.id_transacciones
    where dt.id_plan_cuentas in($cuentas)
    and t.estado='Activo'
    and dt.credito>0
    and t.fecha_registro between '$fechainicio' and '$fechafin'
    order by t.id_transacciones asc),
    y as(
    select
    distinct on(dt.id_transacciones)dt.id_transacciones,
    first_value(dt.id_plan_cuentas)over(
    partition by dt.id_transacciones 
    order by dt.id_transacciones asc,dt.debito desc)id_plan_cuentas
    from transacciones t
    inner join detalle_transaccion dt
    on t.id_transacciones=dt.id_transacciones
    where t.id_transacciones in(select * from x)
    and dt.debito>0
    group by dt.id_transacciones,dt.id_plan_cuentas,dt.debito,dt.credito
    order by dt.id_transacciones asc,dt.debito desc),
    z as(
    select
    dt.id_transacciones,
    dt.credito
    from transacciones t
    inner join detalle_transaccion dt
    on t.id_transacciones=dt.id_transacciones
    where t.id_transacciones in(select * from x)
    and dt.id_plan_cuentas in($cuentas)
    and dt.credito>0
    group by dt.id_transacciones,dt.id_plan_cuentas,dt.debito,dt.credito
    order by dt.id_transacciones asc,dt.credito desc)
    select
    pc.id_plan_cuentas,
    pc.descripcion,
    sum(z.credito)
    from y
    inner join z
    on y.id_transacciones=z.id_transacciones
    inner join plan_cuentas pc using(id_plan_cuentas)
    where pc.id_plan_cuentas not in ($cuentas)
    group by pc.id_plan_cuentas
    order by pc.codigo_plan
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    return (!$rows ? [] : $rows);
}

function llenarMatrizCuentasEfectivoEntrada(&$matriz, $inicio, $fin)
{
    global $cuentasefectivo;
    foreach (obtenerCuentasEfectivoEntrada($inicio, $fin, $cuentasefectivo) as $cnta) {
        $matriz[$cnta["id_plan_cuentas"]]["descripcion"] = $cnta["descripcion"];
        foreach (obtenerIntervalosFecha($inicio, $fin) as $fecha) {
            $matriz[$cnta["id_plan_cuentas"]][date("n", strtotime($fecha[0]))] = 0;
        }
    }
    foreach (obtenerIntervalosFecha($inicio, $fin) as $fecha) {
        $cntas = obtenerCuentasEfectivoEntrada($fecha[0], $fecha[1], $cuentasefectivo);
        foreach ($cntas as $cnta) {
            $matriz[$cnta["id_plan_cuentas"]][date("n", strtotime($fecha[0]))] = $cnta["sum"];
        }
    }
}

function llenarMatrizCuentasEfectivoSalida(&$matriz, $inicio, $fin)
{
    global $cuentasefectivo;
    foreach (obtenerCuentasEfectivoSalida($inicio, $fin, $cuentasefectivo) as $cnta) {
        $matriz[$cnta["id_plan_cuentas"]]["descripcion"] = $cnta["descripcion"];
        foreach (obtenerIntervalosFecha($inicio, $fin) as $fecha) {
            $matriz[$cnta["id_plan_cuentas"]][date("n", strtotime($fecha[0]))] = 0;
        }
    }
    foreach (obtenerIntervalosFecha($inicio, $fin, $cuentasefectivo) as $fecha) {
        $cntas = obtenerCuentasEfectivoSalida($fecha[0], $fecha[1], $cuentasefectivo);
        foreach ($cntas as $cnta) {
            $matriz[$cnta["id_plan_cuentas"]][date("n", strtotime($fecha[0]))] = $cnta["sum"];
        }
    }
}

function mostrarMatrizCuentasEfectivoEntrada(&$coli, &$rowi, &$rowf)
{
    global $inicio, $fin, $objPHPExcel;
    $menormes = date("n", strtotime($inicio));
    $offset = ($menormes == 0 ? $menormes : $menormes - 1);
    $cuentasent = [];
    llenarMatrizCuentasEfectivoEntrada($cuentasent, $inicio, $fin);
    $objPHPExcel
        ->getActiveSheet()
        ->getStyleByColumnAndRow($coli, $rowf)
        ->getFont()
        ->setBold(true);
    $objPHPExcel
        ->getActiveSheet()
        ->getCellByColumnAndRow($coli, $rowf)
        ->setValue("ENTRADAS DE EFECTIVO");
    $rowf += 1;
    $colsm = [];
    foreach ($cuentasent as $cnta) {
        foreach ($cnta as $key => $val) {
            if ($key == "descripcion") {
                $objPHPExcel
                    ->getActiveSheet()
                    ->getCellByColumnAndRow($coli, $rowf)
                    ->setValue($val);
            } else {
                $objPHPExcel
                    ->getActiveSheet()
                    ->getCellByColumnAndRow($coli + ($key - $offset), $rowf)
                    ->setValue($val);
                array_push($colsm, $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($coli + ($key - $offset), $rowf)->getColumn());
            }
        }

        $rowf += 1;
    }
    $colsm = array_values(array_unique($colsm, SORT_REGULAR));
    sort($colsm);
    $objPHPExcel
        ->getActiveSheet()
        ->getStyleByColumnAndRow($coli, $rowf)
        ->getFont()
        ->setBold(true);
    $objPHPExcel
        ->getActiveSheet()
        ->getCellByColumnAndRow($coli, $rowf)
        ->setValue("TOTAL ENTRADAS DE EFECTIVO");

    $rowf1 = $rowf - 1;
    $celltotales = [];
    foreach ($colsm as $col) {
        array_push($celltotales, "$col$rowf");
        $objPHPExcel
            ->getActiveSheet()
            ->getStyle("$col$rowf")
            ->getFont()
            ->setBold(true);
        $objPHPExcel
            ->getActiveSheet()
            ->getCell("$col$rowf")
            ->setValue("=SUM($col$rowi:$col$rowf1)");
    }

    return array("totales" => array("celdas" => $celltotales, "columnas" => $colsm), "matriz" => $cuentasent, "fila_inicio" => $rowi, "fila_fin" => $rowf);
}

function mostrarMatrizCuentasEfectivoSalida(&$coli, &$rowi, &$rowf)
{
    global $inicio, $fin, $objPHPExcel;
    $cuentasent = [];
    $menormes = date("n", strtotime($inicio));
    $offset = ($menormes == 0 ? $menormes : $menormes - 1);
    llenarMatrizCuentasEfectivoSalida($cuentasent, $inicio, $fin);
    $objPHPExcel
        ->getActiveSheet()
        ->getStyleByColumnAndRow($coli, $rowf)
        ->getFont()
        ->setBold(true);
    $objPHPExcel
        ->getActiveSheet()
        ->getCellByColumnAndRow($coli, $rowf)
        ->setValue("SALIDAS DE EFECTIVO");
    $rowf += 1;
    $colsm = [];
    foreach ($cuentasent as $cnta) {
        foreach ($cnta as $key => $val) {
            if ($key == "descripcion") {
                $objPHPExcel
                    ->getActiveSheet()
                    ->getCellByColumnAndRow($coli, $rowf)
                    ->setValue($val);
            } else {
                $objPHPExcel
                    ->getActiveSheet()
                    ->getCellByColumnAndRow($coli + ($key - $offset), $rowf)
                    ->setValue($val);
                array_push($colsm, $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($coli + ($key - $offset), $rowf)->getColumn());
            }
        }

        $rowf += 1;
    }
    $colsm = array_values(array_unique($colsm, SORT_STRING));
    sort($colsm);

    $objPHPExcel
        ->getActiveSheet()
        ->getStyleByColumnAndRow($coli, $rowf)
        ->getFont()
        ->setBold(true);
    $objPHPExcel
        ->getActiveSheet()
        ->getCellByColumnAndRow($coli, $rowf)
        ->setValue("TOTAL SALIDAS DE EFECTIVO");

    $rowf1 = $rowf - 1;
    $celltotales = [];
    foreach ($colsm as $col) {
        array_push($celltotales, "$col$rowf");
        $objPHPExcel
            ->getActiveSheet()
            ->getStyle("$col$rowf")
            ->getFont()
            ->setBold(true);
        $objPHPExcel
            ->getActiveSheet()
            ->getCell("$col$rowf")
            ->setValue("=SUM($col$rowi:$col$rowf1)");
    }

    return array("totales" => array("celdas" => $celltotales, "columnas" => $colsm), "matriz" => $cuentasent, "fila_inicio" => $rowi, "fila_fin" => $rowf);
}

function mostrarFlujoNetoEfectivo($coli, $rowf, $columnast, $celdase, $celdass)
{
    global $objPHPExcel;
    $objPHPExcel
        ->getActiveSheet()
        ->getStyleByColumnAndRow($coli, $rowf)
        ->getFont()
        ->setBold(true);
    $objPHPExcel
        ->getActiveSheet()
        ->getCellByColumnAndRow($coli, $rowf)
        ->setValue("FLUJO NETO DE EFECTIVO");
    $celltotales = [];
    foreach ($columnast as $key => $col) {
        array_push($celltotales, "$col$rowf");
        $objPHPExcel
            ->getActiveSheet()
            ->getStyle("$col$rowf")
            ->getFont()
            ->setBold(true);
        $objPHPExcel
            ->getActiveSheet()
            ->getCell("$col$rowf")
            ->setValue("=SUM(" . $celdase[$key] . ",-" . $celdass[$key] . ")");
    }
    return array("totales" => array("celdas" => $celltotales, "columnas" => $columnast));
}

function mostrarSaldoInicial($coli, $rowf, $columnast, $saldos)
{
    global $objPHPExcel;
    $objPHPExcel
        ->getActiveSheet()
        ->getStyleByColumnAndRow($coli, $rowf)
        ->getFont()
        ->setBold(true);
    $objPHPExcel
        ->getActiveSheet()
        ->getCellByColumnAndRow($coli, $rowf)
        ->setValue("SALDO INICIAL");
    $celltotales = [];
    foreach ($columnast as $key => $col) {
        array_push($celltotales, "$col$rowf");
        if (count($saldos) > 0) {
            $objPHPExcel
                ->getActiveSheet()
                ->getStyle("$col$rowf")
                ->getFont()
                ->setBold(true);
            if ($key == 0) {
                $objPHPExcel
                    ->getActiveSheet()
                    ->getCell("$col$rowf")
                    ->setValueExplicit($saldos[$key], PHPExcel_Cell_DataType::TYPE_NUMERIC);
            } else {
                $objPHPExcel
                    ->getActiveSheet()
                    ->getCell("$col$rowf")
                    ->setValue("=" . $saldos[$key]);
            }
        }
    }
    return array("totales" => array("celdas" => $celltotales, "columnas" => $columnast));
}

function mostrarSaldoFinal($coli, $rowf, $columnast, $saldos)
{
    global $objPHPExcel;
    $objPHPExcel
        ->getActiveSheet()
        ->getStyleByColumnAndRow($coli, $rowf)
        ->getFont()
        ->setBold(true);
    $objPHPExcel
        ->getActiveSheet()
        ->getCellByColumnAndRow($coli, $rowf)
        ->setValue("FLUJO FINAL DE EFECTIVO");
    $celltotales = [];
    foreach ($columnast as $key => $col) {
        array_push($celltotales, "$col$rowf");
        $objPHPExcel
            ->getActiveSheet()
            ->getStyle("$col$rowf")
            ->getFont()
            ->setBold(true);
        $objPHPExcel
            ->getActiveSheet()
            ->getCell("$col$rowf")
            ->setValue("=SUM(" . $saldos["neto"][$key] . "," . $saldos["inicial"][$key] . ")");
    }
    return array("totales" => array("celdas" => $celltotales, "columnas" => $columnast));
}

function mostrarCabezeraMeses($col, $row)
{
    global $objPHPExcel, $inicio, $fin;
    $meses = [
        "ENERO", "FEBRERO", "MARZO", "ABRIL",
        "MAYO", "JUNIO", "JULIO", "AGOSTO",
        "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE"
    ];
    $menormes = date("n", strtotime($inicio));
    $mayormes = date("n", strtotime($fin));
    $styleArray = array(
        'borders' => array(
            'outline' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('argb' => '0000000'),
            ),
        ),
    );

    for ($i = $menormes - 1; $i < $mayormes; $i++) {
        $objPHPExcel
            ->getActiveSheet()
            ->getStyleByColumnAndRow($col, $row)
            ->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()
            ->getStyleByColumnAndRow($col, $row)->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel
            ->getActiveSheet()
            ->getColumnDimensionByColumn($col)
            ->setWidth(15);
        $objPHPExcel
            ->getActiveSheet()
            ->getStyleByColumnAndRow($col, $row)
            ->getFont()
            ->setBold(true);
        $objPHPExcel
            ->getActiveSheet()
            ->getCellByColumnAndRow($col, $row)
            ->setValue($meses[$i]);
        $col += 1;
    }
    $objPHPExcel
        ->getActiveSheet()
        ->getColumnDimensionByColumn($col)
        ->setWidth(15);
    $objPHPExcel
        ->getActiveSheet()
        ->getStyleByColumnAndRow($col, $row)
        ->getFont()
        ->setBold(true);
    $objPHPExcel
        ->getActiveSheet()
        ->getCellByColumnAndRow($col, $row)
        ->setValue("TOTALES");
    return $col;
}

function mostrarEncabezado()
{
    global $objPHPExcel;
    //////////////////////CABECERA DE LA CONSULTA
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("B2", 'ESTADO DE FLUJO DE EFECTIVO');
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
        ->setCellValue("B5", 'DESDE:');
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
    $objDrawing->setPath('../images/'.$_SESSION["parametros_empresa"]["logo_empresa"]);           // 
    $objDrawing->setWidth(45);                 // sets the image 
    //$objDrawing->setHeight(20);
    $objDrawing->setCoordinates('B1');    // pins the top-left corner 
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
    $objPHPExcel->getActiveSheet()->getStyle('B6:O6')->applyFromArray($styleArray);
    unset($styleArray);
    //////////////////////////////////////////////////////////
}

function mostrarTotalesFilas($filas, $coli, $columnas)
{
    if(empty($columnas)){
        return;
    }
    global $objPHPExcel;
    for ($i = $filas["entradas"][0] + 1; $i <= $filas["entradas"][1]; $i++) {
        if ($i == $filas["entradas"][1]) {
            $objPHPExcel
                ->getActiveSheet()
                ->getStyleByColumnAndRow($coli, $i)
                ->getFont()
                ->setBold(true);
        }
        $objPHPExcel
            ->getActiveSheet()
            ->getCellByColumnAndRow($coli, $i)
            ->setValue("=SUM(" . $columnas[0] . "$i:" . $columnas[count($columnas) - 1] . "$i" . ")");
    }
    for ($i = $filas["salidas"][0] + 1; $i <= $filas["salidas"][1] + 3; $i++) {
        if ($i >= $filas["salidas"][1]) {
            $objPHPExcel
                ->getActiveSheet()
                ->getStyleByColumnAndRow($coli, $i)
                ->getFont()
                ->setBold(true);
        }
        $objPHPExcel
            ->getActiveSheet()
            ->getCellByColumnAndRow($coli, $i)
            ->setValue("=SUM(" . $columnas[0] . "$i:" . $columnas[count($columnas) - 1] . "$i" . ")");
    }
}

function saldoInicial($cuentas, $fechainicio)
{
    $end    = (new DateTime($fechainicio))->modify('yesterday')->format("Y-m-d");
    $sql = "
    select
    coalesce((sum(dt.debito)-sum(dt.credito)),0) saldo
    from transacciones t
    inner join detalle_transaccion dt
    on t.id_transacciones=dt.id_transacciones
    where dt.id_plan_cuentas in ($cuentas)
    and t.estado='Activo'
    and t.fecha_registro 
    between (select fecha_registro from transacciones t
    where estado='Activo'
    group by id_transacciones,fecha_registro
    order by id_transacciones asc
    limit 1) and '$end';
    ";
    $res = pg_query($sql);
    $row = pg_fetch_row($res);
    return floatval($row[0]);
}
