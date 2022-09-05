<?php

session_start();
include '../../procesos/base.php';
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
$search = $_GET['_search'];
$conpunto = 1;
$consultapunto = pg_query("select max(id_punto_venta_empresa) from punto_venta_empresa where punto_venta_empresa.id_usuario='$_SESSION[id]'");
    while ($row = pg_fetch_row($consultapunto)) {
        $conpunto = $row[0];
    }        
$conpuntoresult = 1;
$consultapuntoresult = pg_query("select id_punto_venta from punto_venta_empresa where id_punto_venta_empresa='$conpunto'");
    while ($row = pg_fetch_row($consultapuntoresult)) {
        $conpuntoresult = $row[0];
    }
if (!$sidx)
    $sidx = 1;
$result = pg_query("SELECT COUNT(*) AS count from transacciones where estado='Activo'  and  transacciones.id_empresa='$conpuntoresult'");

$row = pg_fetch_row($result);

//printf($row[0], "");
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
if ($search == 'false') {
    $SQL = "select T.id_transacciones, U.nombre_usuario, U.apellido_usuario, T.fecha_actual, T.concepto, T.total_debe, T.total_haber, TT.descripcion, T.num_transaccion, T.estado, T.deposito, T.observacion, T. num_cuenta, T.banco, T.identificador_cli_pro, T.valor_concepto from transacciones T, usuario U, tipo_transaccion TT where T.id_usuario = U.id_usuario and T.id_tipo_transaccion = TT.id_tipo_transaccion and  T.id_empresa='$conpuntoresult' and identificador_cli_pro <> 'AUD' ORDER BY $sidx $sord offset $start limit $limit";
} else {
    if ($_GET['searchOper'] == 'eq') {
        $SQL = "select T.id_transacciones, U.nombre_usuario, U.apellido_usuario, T.fecha_actual, T.concepto, T.total_debe, T.total_haber, TT.descripcion, T.num_transaccion, T.estado,  T.deposito, T.observacion, T. num_cuenta, T.banco, T.identificador_cli_pro, T.valor_concepto from transacciones T, usuario U, tipo_transaccion TT where T.id_usuario = U.id_usuario and T.id_tipo_transaccion = TT.id_tipo_transaccion and $_GET[searchField] = '$_GET[searchString]' and  T.id_empresa='$conpuntoresult' and identificador_cli_pro <> 'AUD' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'ne') {
        $SQL = "select T.id_transacciones, U.nombre_usuario, U.apellido_usuario, T.fecha_actual, T.concepto, T.total_debe, T.total_haber, TT.descripcion, T.num_transaccion, T.estado, T.deposito, T.observacion, T. num_cuenta, T.banco, T.identificador_cli_pro, T.valor_concepto from transacciones T, usuario U, tipo_transaccion TT where T.id_usuario = U.id_usuario and T.id_tipo_transaccion = TT.id_tipo_transaccion $_GET[searchField] != '$_GET[searchString]' and  T.id_empresa='$conpuntoresult' and identificador_cli_pro <> 'AUD' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'bw') {
        $SQL = "select T.id_transacciones, U.nombre_usuario, U.apellido_usuario, T.fecha_actual, T.concepto, T.total_debe, T.total_haber, TT.descripcion, T.num_transaccion, T.estado, T.deposito, T.observacion, T. num_cuenta, T.banco, T.identificador_cli_pro, T.valor_concepto from transacciones T, usuario U, tipo_transaccion TT where T.id_usuario = U.id_usuario and T.id_tipo_transaccion = TT.id_tipo_transaccion $_GET[searchField] like '$_GET[searchString]%' and  T.id_empresa='$conpuntoresult' and identificador_cli_pro <> 'AUD' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'bn') {
        $SQL = "select T.id_transacciones, U.nombre_usuario, U.apellido_usuario, T.fecha_actual, T.concepto, T.total_debe, T.total_haber, TT.descripcion, T.num_transaccion, T.estado, T.deposito, T.observacion, T. num_cuenta, T.banco, T.identificador_cli_pro, T.valor_concepto from transacciones T, usuario U, tipo_transaccion TT where T.id_usuario = U.id_usuario and T.id_tipo_transaccion = TT.id_tipo_transaccion and $_GET[searchField] not like '$_GET[searchString]%' and  T.id_empresa='$conpuntoresult' and identificador_cli_pro <> 'AUD' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'ew') {
        $SQL = "select T.id_transacciones, U.nombre_usuario, U.apellido_usuario, T.fecha_actual, T.concepto, T.total_debe, T.total_haber, TT.descripcion, T.num_transaccion, T.estado, T.deposito, T.observacion, T. num_cuenta, T.banco, T.identificador_cli_pro, T.valor_concepto from transacciones T, usuario U, tipo_transaccion TT where T.id_usuario = U.id_usuario and T.id_tipo_transaccion = TT.id_tipo_transaccion and $_GET[searchField] like '%$_GET[searchString]' and  T.id_empresa='$conpuntoresult' and identificador_cli_pro <> 'AUD' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'en') {
        $SQL = "select T.id_transacciones, U.nombre_usuario, U.apellido_usuario, T.fecha_actual, T.concepto, T.total_debe, T.total_haber, TT.descripcion, T.num_transaccion, T.estado, T.deposito, T.observacion, T. num_cuenta, T.banco, T.identificador_cli_pro, T.valor_concepto from transacciones T, usuario U, tipo_transaccion TT where T.id_usuario = U.id_usuario and T.id_tipo_transaccion = TT.id_tipo_transaccion and $_GET[searchField] not like '%$_GET[searchString]' and  T.id_empresa='$conpuntoresult' and identificador_cli_pro <> 'AUD' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'cn') {
        $SQL = "select T.id_transacciones, U.nombre_usuario, U.apellido_usuario, T.fecha_actual, T.concepto, T.total_debe, T.total_haber, TT.descripcion, T.num_transaccion, T.estado, T.deposito, T.observacion, T. num_cuenta, T.banco, T.identificador_cli_pro, T.valor_concepto from transacciones T, usuario U, tipo_transaccion TT where T.id_usuario = U.id_usuario and T.id_tipo_transaccion = TT.id_tipo_transaccion and $_GET[searchField] like '%$_GET[searchString]%' and  T.id_empresa='$conpuntoresult' and identificador_cli_pro <> 'AUD' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'nc') {
        $SQL = "select T.id_transacciones, U.nombre_usuario, U.apellido_usuario, T.fecha_actual, T.concepto, T.total_debe, T.total_haber, TT.descripcion, T.num_transaccion, T.estado, T.deposito, T.observacion, T. num_cuenta, T.banco, T.identificador_cli_pro, T.valor_concepto from transacciones T, usuario U, tipo_transaccion TT where T.id_usuario = U.id_usuario and T.id_tipo_transaccion = TT.id_tipo_transaccion and $_GET[searchField] not like '%$_GET[searchString]%' and  T.id_empresa='$conpuntoresult' and identificador_cli_pro <> 'AUD' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'in') {
        $SQL = "select T.id_transacciones, U.nombre_usuario, U.apellido_usuario, T.fecha_actual, T.concepto, T.total_debe, T.total_haber, TT.descripcion, T.num_transaccion, T.estado, T.deposito, T.observacion, T. num_cuenta, T.banco, T.identificador_cli_pro, T.valor_concepto from transacciones T, usuario U, tipo_transaccion TT where T.id_usuario = U.id_usuario and T.id_tipo_transaccion = TT.id_tipo_transaccion and $_GET[searchField] like '%$_GET[searchString]%' and  T.id_empresa='$conpuntoresult' and identificador_cli_pro <> 'AUD' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'ni') {
        $SQL = "select T.id_transacciones, U.nombre_usuario, U.apellido_usuario, T.fecha_actual, T.concepto, T.total_debe, T.total_haber, TT.descripcion, T.num_transaccion, T.estado, T.deposito, T.observacion, T. num_cuenta, T.banco, T.identificador_cli_pro, T.valor_concepto from transacciones T, usuario U, tipo_transaccion TT where T.id_usuario = U.id_usuario and T.id_tipo_transaccion = TT.id_tipo_transaccion and $_GET[searchField] not like '%$_GET[searchString]%' and  T.id_empresa='$conpuntoresult' and identificador_cli_pro <> 'AUD' ORDER BY $sidx $sord offset $start limit $limit";
    }
    //echo $SQL;
}
$result = pg_query($SQL);
header("Content-type: text/xml;charset=utf-8");
$s = "<?xml version='1.0' encoding='utf-8'?>";
$s .= "<rows>";
$s .= "<page>" . $page . "</page>";
$s .= "<total>" . $total_pages . "</total>";
$s .= "<records>" . $count . "</records>";
while ($row = pg_fetch_row($result)) {
    $s .= "<row id='" . $row[0] . "'>";
    $s .= "<cell>" . $row[0] . "</cell>";
    $s .= "<cell>" . $row[1] ." ". $row[2] . "</cell>";
    $s .= "<cell>" . $row[3] . "</cell>";
    $s .= "<cell>" . $row[4] . "</cell>";
    $s .= "<cell>" . $row[5] . "</cell>";
    $s .= "<cell>" . $row[6] . "</cell>";
    $s .= "<cell>" . $row[7] . "</cell>";
    $s .= "<cell>" . $row[8] . "</cell>";
    
    $s .= "<cell>" . $row[10] . "</cell>";
    $s .= "<cell>" . $row[11] . "</cell>";
    $s .= "<cell>" . $row[12] . "</cell>";
    $s .= "<cell>" . $row[13] . "</cell>";
    $s .= "<cell>" . $row[14] . "</cell>";
    $s .= "<cell>" . $row[15] . "</cell>";
    $s .= "</row>";
}
$s .= "</rows>";
echo $s;
?>
