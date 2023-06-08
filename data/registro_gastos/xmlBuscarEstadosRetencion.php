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
$result = pg_query("SELECT COUNT(*) AS count from retencion_fuente_factura_compra RF, gastos FC, proveedores P where FC.id_gastos=RF.id_factura AND FC.id_proveedor = P.id_proveedor and RF.id_gastos='10'");
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
    $SQL = "SELECT RF.id_retencion_fuente_factura_compra,fc.num_factura,rf.num_serie,RF.fecha, P.empresa_pro, RF.num_autorizacion, RF.valor_compra, RF.estado FROM retencion_fuente_factura_compra RF INNER JOIN gastos FC ON RF.id_factura = FC.id_gastos INNER JOIN proveedores P ON P.id_proveedor=FC.id_proveedor and RF.id_gastos='10'  ORDER BY $sidx $sord offset $start limit $limit";
} else {
    
}

$result = pg_query($SQL);
header("Content-type: text/xml;charset=utf-8");
$s = "<?xml version='1.0' encoding='utf-8'?>";
$s .= "<rows>";
$s .= "<page>" . $page . "</page>";
$s .= "<total>" . $total_pages . "</total>";
$s .= "<records>" . $count . "</records>";
while ($row = pg_fetch_row($result)) {
    $valorTotal = round($row[6], 2);
    $nombre_estado = $row[7];
    if ($nombre_estado == 5) {
        $row[7] = "ERROR.P12";
    }
    if ($nombre_estado == 6) {
        $row[7] = "CONTRA.INCO.P12";
    }

    if ($nombre_estado == 2) {
        $row[7] = "AUTORIZADO";
    }
    if ($nombre_estado == 7) {
        $row[7] = "NO AUTORIZADO";
    }
    if ($nombre_estado == 1) {
        $row[7] = "AUTORI.ENVIADO";
    }
    if ($nombre_estado == 8) {
        $row[7] = "ERROR WEB.SERV";
    }
    if ($nombre_estado == 3) {
        $row[7] = "ERROR CORREO";
    }
    if ($nombre_estado == 0) {
        $row[7] = "NO AUTORIZADO";
    }



    $s .= "<row id='" . $row[0] . "'>";
    $s .= "<cell>" . $row[0] . "</cell>";
    $s .= "<cell>" . $row[1] . "</cell>";
    $s .= "<cell>" . $row[2] . "</cell>";
    $s .= "<cell>" . $row[3] . "</cell>";
    $s .= "<cell>" . $row[4] . "</cell>";
    $s .= "<cell>" . $row[5] . "</cell>";
    $s .= "<cell>" . $valorTotal . "</cell>";
    $s .= "<cell  >" . $row[7] . "</cell>";
    $s .= "<cell></cell>";
    $s .= "<cell></cell>";
    $s .= "<cell></cell>";
    $s .= "</row>";
}
$s .= "</rows>";
echo $s;
?>
