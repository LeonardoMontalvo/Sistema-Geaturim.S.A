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
$result = pg_query("SELECT COUNT(*) AS count from ordenes_produccion");
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
    $SQL = "select id_ordenes, articulo, costo_total, fecha_modificacion from ordenes_produccion, recetas, productos where ordenes_produccion.id_receta=CAST(recetas.id_receta as text) and recetas.cod_productos=productos.cod_productos ORDER BY $sidx $sord offset $start limit $limit";
} else {
    if ($_GET['searchOper'] == 'eq') {
        $SQL = "select id_ordenes, articulo, costo_total, fecha_modificacion from ordenes_produccion, recetas, productos where ordenes_produccion.id_receta=CAST(recetas.id_receta as text) and recetas.cod_productos=productos.cod_productos and $_GET[searchField] = '$_GET[searchString]' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'ne') {
        $SQL = "select id_ordenes, articulo, costo_total, fecha_modificacion from ordenes_produccion, recetas, productos where ordenes_produccion.id_receta=CAST(recetas.id_receta as text) and recetas.cod_productos=productos.cod_productos and $_GET[searchField] != '$_GET[searchString]' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'bw') {
        $SQL = "select id_ordenes, articulo, costo_total, fecha_modificacion from ordenes_produccion, recetas, productos where ordenes_produccion.id_receta=CAST(recetas.id_receta as text) and recetas.cod_productos=productos.cod_productos and $_GET[searchField] like '$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'bn') {
        $SQL = "select id_ordenes, articulo, costo_total, fecha_modificacion from ordenes_produccion, recetas, productos where ordenes_produccion.id_receta=CAST(recetas.id_receta as text) and recetas.cod_productos=productos.cod_productos and $_GET[searchField] not like '$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'ew') {
        $SQL = "select id_ordenes, articulo, costo_total, fecha_modificacion from ordenes_produccion, recetas, productos where ordenes_produccion.id_receta=CAST(recetas.id_receta as text) and recetas.cod_productos=productos.cod_productos and $_GET[searchField] like '%$_GET[searchString]' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'en') {
        $SQL = "select id_ordenes, articulo, costo_total, fecha_modificacion from ordenes_produccion, recetas, productos where ordenes_produccion.id_receta=CAST(recetas.id_receta as text) and recetas.cod_productos=productos.cod_productos and $_GET[searchField] not like '%$_GET[searchString]' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'cn') {
        $SQL = "select id_ordenes, articulo, costo_total, fecha_modificacion from ordenes_produccion, recetas, productos where ordenes_produccion.id_receta=CAST(recetas.id_receta as text) and recetas.cod_productos=productos.cod_productos and $_GET[searchField] like '%$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'nc') {
        $SQL = "select id_ordenes, articulo, costo_total, fecha_modificacion from ordenes_produccion, recetas, productos where ordenes_produccion.id_receta=CAST(recetas.id_receta as text) and recetas.cod_productos=productos.cod_productos and $_GET[searchField] not like '%$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'in') {
        $SQL = "select id_ordenes, articulo, costo_total, fecha_modificacion from ordenes_produccion, recetas, productos where ordenes_produccion.id_receta=CAST(recetas.id_receta as text) and recetas.cod_productos=productos.cod_productos and $_GET[searchField] like '%$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'ni') {
        $SQL = "select id_ordenes, articulo, costo_total, fecha_modificacion from ordenes_produccion, recetas, productos where ordenes_produccion.id_receta=CAST(recetas.id_receta as text) and recetas.cod_productos=productos.cod_productos and $_GET[searchField] not like '%$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
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
    $s .= "<cell>" . $row[1] . "</cell>";
    $s .= "<cell>" . number_format($row[2],2,'.','') . "</cell>";
    $s .= "<cell>" . $row[3] . "</cell>";
    $s .= "</row>";
}
$s .= "</rows>";
echo $s;
?>
