<?php

session_start();
include '../../procesos/base.php';
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
$search = $_GET['_search'];

if (!$sidx)
    $sidx = 1;
$result = pg_query("SELECT COUNT(*) AS count FROM transacciones");
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
if ($search == 'false') {
    $SQL = "SELECT id_transacciones, usuario, t.fecha_actual, hora_actual, concepto, abreviatura, num_transaccion from transacciones t inner join usuario u using(id_usuario) inner join tipo_transaccion using(id_tipo_transaccion) ORDER BY $sidx $sord offset $start limit $limit";
} else {
    if ($_GET['searchOper'] == 'eq') {
        $SQL = "SELECT id_transacciones, usuario, t.fecha_actual, hora_actual, concepto, abreviatura, num_transaccion from transacciones t inner join usuario u using(id_usuario) inner join tipo_transaccion using(id_tipo_transaccion) where $_GET[searchField] = '$_GET[searchString]' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'ne') {
        $SQL = "SELECT id_transacciones, usuario, t.fecha_actual, hora_actual, concepto, abreviatura, num_transaccion from transacciones t inner join usuario u using(id_usuario) inner join tipo_transaccion using(id_tipo_transaccion) where $_GET[searchField] != '$_GET[searchString]' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'bw') {
        $SQL = "SELECT id_transacciones, usuario, t.fecha_actual, hora_actual, concepto, abreviatura, num_transaccion from transacciones t inner join usuario u using(id_usuario) inner join tipo_transaccion using(id_tipo_transaccion) where $_GET[searchField] like '$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'bn') {
        $SQL = "SELECT id_transacciones, usuario, t.fecha_actual, hora_actual, concepto, abreviatura, num_transaccion from transacciones t inner join usuario u using(id_usuario) inner join tipo_transaccion using(id_tipo_transaccion) where $_GET[searchField] not like '$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'ew') {
        $SQL = "SELECT id_transacciones, usuario, t.fecha_actual, hora_actual, concepto, abreviatura, num_transaccion from transacciones t inner join usuario u using(id_usuario) inner join tipo_transaccion using(id_tipo_transaccion) where $_GET[searchField] like '%$_GET[searchString]' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'en') {
        $SQL = "SELECT id_transacciones, usuario, t.fecha_actual, hora_actual, concepto, abreviatura, num_transaccion from transacciones t inner join usuario u using(id_usuario) inner join tipo_transaccion using(id_tipo_transaccion) where $_GET[searchField] not like '%$_GET[searchString]' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'cn') {
        $SQL = "SELECT id_transacciones, usuario, t.fecha_actual, hora_actual, concepto, abreviatura, num_transaccion from transacciones t inner join usuario u using(id_usuario) inner join tipo_transaccion using(id_tipo_transaccion) where $_GET[searchField] like '%$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'nc') {
        $SQL = "SELECT id_transacciones, usuario, t.fecha_actual, hora_actual, concepto, abreviatura, num_transaccion from transacciones t inner join usuario u using(id_usuario) inner join tipo_transaccion using(id_tipo_transaccion) where $_GET[searchField] not like '%$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'in') {
        $SQL = "SELECT id_transacciones, usuario, t.fecha_actual, hora_actual, concepto, abreviatura, num_transaccion from transacciones t inner join usuario u using(id_usuario) inner join tipo_transaccion using(id_tipo_transaccion) where $_GET[searchField] like '%$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'ni') {
        $SQL = "SELECT id_transacciones, usuario, t.fecha_actual, hora_actual, concepto, abreviatura, num_transaccion from transacciones t inner join usuario u using(id_usuario) inner join tipo_transaccion using(id_tipo_transaccion) where $_GET[searchField] not like '%$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
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
while ($row = pg_fetch_assoc($result)) {
    $s .= "<row id='" . $row['id_transacciones'] . "'>";
    $s .= "<cell>" . $row['id_transacciones'] . "</cell>";
    $s .= "<cell>" . $row['usuario'] . "</cell>";
    $s .= "<cell>" . $row['fecha_actual'] . "</cell>";
    $s .= "<cell>" . $row['hora_actual'] . "</cell>";
    $s .= "<cell>" . $row['concepto'] . "</cell>";
    $s .= "<cell>" . $row['abreviatura'] . "</cell>";
    $s .= "<cell>" . $row['num_transaccion'] . "</cell>";
    $s .= "</row>";
}
$s .= "</rows>";
echo $s;
?>
