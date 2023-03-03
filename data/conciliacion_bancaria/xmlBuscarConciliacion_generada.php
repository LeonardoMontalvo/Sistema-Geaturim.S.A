<?php

session_start();
include '../../procesos/base.php';
include '../../procesos/funciones.php';
$page = $_GET['page'];
$limit = 1000;
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
$search = $_GET['_search'];

if (!$sidx)
    $sidx = 1;
$result = pg_query("SELECT COUNT(*) AS count
  FROM transacciones 
  where identificador_cli_pro='CxC' or identificador_cli_pro='CxP'");
$row = pg_fetch_row($result);

$count = $row[0];
if ($count > 0 && $limit > 0) {
    $total_pages = ceil($count / $limit);
} else {
    $total_pages = 0;
}
if ($page > $total_pages)
    $page = $total_pages;
$start = $limit * $page - $limit;
if ($start < 0)
    $start = 0;

////////////////todo
if ($_GET['id'] != "" && $_GET['f1'] != "" && $_GET['f2'] != "") {
    $plan_cuenta = trim($_GET['id']);
    $id_plan_cuenta = $_GET['id_plan'];
    
    echo ''."((SELECT id_transaccion::int,fecha_transaccion,comprobante_movimiento,identificador,debe::numeric,monto::numeric,concepto
        ,concepto,id_transaccion::int
  FROM detalle_conciliacion,conciliacion
  where detalle_conciliacion.id_conciliacion=conciliacion.id_conciliacion )   
        union all (select  t.id_transacciones,fecha_registro,comprobante,identificador_cli_pro,debito,credito,concepto,
        pc.descripcion, t.id_transacciones
        from transacciones t, detalle_transaccion dt,plan_cuentas pc 
        where t.id_transacciones=dt.id_transacciones and dt.id_plan_cuentas=pc.id_plan_cuentas
        and (identificador_cli_pro='CxC' or identificador_cli_pro='CxP'
        or identificador_cli_pro='EGR' or identificador_cli_pro='GAS' or identificador_cli_pro='OTRO'  or identificador_cli_pro='ANTC' or identificador_cli_pro='ANTP'
        or identificador_cli_pro='VEN' or identificador_cli_pro='ING' or identificador_cli_pro='COM')
        and dt.id_plan_cuentas ='$id_plan_cuenta' and fecha_registro between '$_GET[f1]' and '$_GET[f2]'  and t.estado='Activo' and  t.id_empresa='$_SESSION[PV]'
        ORDER BY  fecha_registro))";
    
      $SQL = "((SELECT id_transaccion::int,fecha_transaccion,comprobante_movimiento,identificador,debe::numeric,monto::numeric,concepto
        ,concepto,id_transaccion::int
  FROM detalle_conciliacion,conciliacion
  where detalle_conciliacion.id_conciliacion=conciliacion.id_conciliacion )   
        union all (select  t.id_transacciones,fecha_registro,comprobante,identificador_cli_pro,debito,credito,concepto,
        pc.descripcion, t.id_transacciones
        from transacciones t, detalle_transaccion dt,plan_cuentas pc 
        where t.id_transacciones=dt.id_transacciones and dt.id_plan_cuentas=pc.id_plan_cuentas
        and (identificador_cli_pro='CxC' or identificador_cli_pro='CxP'
        or identificador_cli_pro='EGR' or identificador_cli_pro='GAS' or identificador_cli_pro='OTRO'  or identificador_cli_pro='ANTC' or identificador_cli_pro='ANTP'
        or identificador_cli_pro='VEN' or identificador_cli_pro='ING' or identificador_cli_pro='COM')
        and dt.id_plan_cuentas ='$id_plan_cuenta' and fecha_registro between '$_GET[f1]' and '$_GET[f2]'  and t.estado='Activo' and  t.id_empresa='$_SESSION[PV]'
        ORDER BY  fecha_registro))";
}
$id_trans11 = "";
$query_detalle11 = "SELECT id_transaccion,fecha_transaccion,comprobante_movimiento,identificador,debe,monto,concepto
  FROM detalle_conciliacion,conciliacion
  where detalle_conciliacion.id_conciliacion=conciliacion.id_conciliacion 
  and detalle_conciliacion.id_conciliacion='$_GET[comprobante]'";
$result_11 = pg_query($query_detalle11);
$result_trans = "";

while ($row11 = pg_fetch_row($result_11)) {
    $id_trans11 = $row11[0];
}

$id_transaccion_movi = "";
$id_trans = "";
$result_2 = pg_query($SQL);
header("Content-type: text/xml;charset=utf-8");
$s = "<?xml version='1.0' encoding='utf-8'?>";
$s .= "<rows>";
$s .= "<page>" . $page . "</page>";
$s .= "<total>" . $total_pages . "</total>";
$s .= "<records>" . $count . "</records>";
while ($row = pg_fetch_row($result_2)) {

    $id_transaccion_movi = $row[0];

//echo ''."SELECT id_transaccion,fecha_transaccion,comprobante_movimiento,identificador,debe,monto,concepto
//  FROM detalle_conciliacion,conciliacion
//  where detalle_conciliacion.id_conciliacion=conciliacion.id_conciliacion 
//  and detalle_conciliacion.id_transaccion::int='$id_transaccion_movi' and detalle_conciliacion.id_conciliacion='$_GET[comprobante]'";
//    
    $query_detalle = "SELECT id_transaccion,fecha_transaccion,comprobante_movimiento,identificador,debe,monto,concepto
  FROM detalle_conciliacion,conciliacion
  where detalle_conciliacion.id_conciliacion=conciliacion.id_conciliacion 
  and detalle_conciliacion.id_transaccion::int='$id_transaccion_movi' and detalle_conciliacion.id_conciliacion='$_GET[comprobante]'";
    $result_1 = pg_query($query_detalle);
    $result_trans = "";

    while ($row1 = pg_fetch_row($result_1)) {
        $id_trans = $row1[0];
    }

//    if ($row[0] == $id_trans) {
//        $result_trans = $row[0];
////         echo '::'.$row[0];
//    }

    if ($row[4] == '0.000') {
        $row[4] = '-';
    } else {
        $row[4] = $row[4];
    }
    if ($row[5] == '0.000') {
        $row[5] = '-';
    } else {
        $row[5] = $row[5];
    }
    if ($id_trans11 != "") {

        if ($row[0] == $id_trans) {
            $result_trans_res = '1'; //igual base de datos
        } else {
            $result_trans_res = '0';
        }
    } else {
        $result_trans_res = '00';
    }

    $s .= "<row id='" . $row[0] . "'>";
//          $s .= "<cell></cell>";
    $s .= "<cell>" . $row[0] . "</cell>"; //ID TRANSACCION
    $s .= "<cell>" . $row[1] . "</cell>"; // FECHA REGISTRO
    $s .= "<cell>" . $row[2] . "</cell>"; // COMPROBANTE TRANSACCION
    $s .= "<cell>" . $row[3] . "</cell>"; // TIPO TRANSACCION
    if ($row[4] == '-') {
        $s .= "<cell>" . $row[4] . "</cell>"; //DEBE
    } else {
        $s .= "<cell>" . number_format($row[4], 2, '.', '') . "</cell>"; //DEBE
    }

    if ($row[5] == '-') {
        $s .= "<cell>" . $row[5] . "</cell>"; //HABER
    } else {
        $s .= "<cell>" . number_format($row[5], 2, '.', '') . "</cell>"; //HABER
    }

    $s .= "<cell>" . $row[6] . "</cell>"; //CONCEPTO
    $s .= "<cell>" . $result_trans_res . "</cell>";
   $s .= "<cell>" . $result_trans_res . "</cell>";

    $s .= "</row>";
}
$s .= "</rows>";
echo $s;
?>