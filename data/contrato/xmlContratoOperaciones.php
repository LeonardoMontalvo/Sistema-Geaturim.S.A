<?php

include '../../procesos/base.php';
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
$search = $_GET['_search'];

$idcontrato = !empty($_GET['id_contrato']) ? $_GET['id_contrato'] : 0;

if (!$sidx)
    $sidx = 1;
$result = pg_query("SELECT COUNT(*) AS count FROM flete_operacion where estado='Activo' and id_flete=$idcontrato");
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
    $SQL = "SELECT * from flete_operacion
        WHERE estado='Activo' and id_flete=$idcontrato ORDER BY  $sidx $sord offset $start limit $limit";
} else {
    
}

$result = pg_query($SQL);

if (pg_num_rows($result) > 0) {
    $rows = pg_fetch_all($result);
} else {
    $rows = [];
}


header("Content-type: text/xml;charset=utf-8");
$s = "<?xml version='1.0' encoding='utf-8'?>";
$s .= "<rows>";
$s .= "<page>" . $page . "</page>";
$s .= "<total>" . $total_pages . "</total>";
$s .= "<records>" . $count . "</records>";
foreach ($rows as $row) {
    $s .= "<row id='" . $row['id_operacion'] . "'>";
    $s .= "<cell>" . $row['id_operacion'] . "</cell>";
    $s .= "<cell>" . (($row['accion'] == 'i'||$row['accion']=='a') ? mb_strtoupper('INGRESO') : ($row['accion'] == 'e' ? mb_strtoupper('EGRESO') : mb_strtoupper('NO ESPECIFICADA'))) . "</cell>";
    $s .= "<cell>" . ($row['tipo_documento'] == 'f' ? mb_strtoupper("factura") : ($row['tipo_documento'] == 'n' ? mb_strtoupper("nota de venta") : mb_strtoupper("recibo"))) . "</cell>";
    $s .= "<cell>" . $row['valor'] . "</cell>";
    $s .= "<cell>" . mb_strtoupper($row['descripcion']) . "</cell>";
    $s .= "<cell>" . $row['nro_documento'] . "</cell>";
    $s .= "<cell>" . $row['fecha_creacion'] . "</cell>";
    $s .= "<cell>" . $row['accion'] . "</cell>";
    $s .= "</row>";
}
$s .= "</rows>";
echo $s;
