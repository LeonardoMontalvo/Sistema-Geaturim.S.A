<?php
session_start();
include '../../procesos/base.php';
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
$search = $_GET['_search'];

$tipo_pago = $_GET["tipo_pago"];
$num_factura = $_GET["num_factura"];
$tipo_docu = $_GET["tipo_docu"];

if (!$sidx)
    $sidx = 1;

$queryc = "select count(*) from pagos_cobrar
where tipo_pago='$tipo_pago'
and num_factura='$num_factura'
and tipo_factura='$tipo_docu'
and estado='Activo'
";

$result = pg_query($queryc);
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

$SQL = "
select 
id_pagos_cobrar,
fecha_actual,
forma_pago,
valor_pagado,
observaciones 
from pagos_cobrar
where tipo_pago='$tipo_pago'
and num_factura='$num_factura'
and tipo_factura='$tipo_docu'
and estado='Activo'

";
if ($search == 'false') {
    //$SQL = "SELECT PC.id_pagos_cobrar, C.identificacion, PC.comprobante, C.nombres_cli, PC.num_factura, PC.valor_pagado, PC.fecha_factura FROM pagos_cobrar PC, clientes C, usuario U where PC.id_cliente = C.id_cliente and PC.id_usuario = U.id_usuario ORDER BY $sidx $sord offset $start limit $limit";
}

$SQL .= " ORDER BY $sidx $sord offset $start limit $limit";

$result = pg_query($SQL);
header("Content-type: text/xml;charset=utf-8");
$s = "<?xml version='1.0' encoding='utf-8'?>";
$s .= "<rows>";
$s .= "<page>" . $page . "</page>";
$s .= "<total>" . $total_pages . "</total>";
$s .= "<records>" . $count . "</records>";
while ($row = pg_fetch_assoc($result)) {
    $s .= "<row id='" . $row["id_pagos_cobrar"] . "'>";
    $s .= "<cell>" . $row["id_pagos_cobrar"] . "</cell>";
    $s .= "<cell>" . $row["fecha_actual"] . "</cell>";
    $s .= "<cell>" . mb_strtoupper($row["forma_pago"]) . "</cell>";
    $s .= "<cell>" . $row["valor_pagado"] . "</cell>";
    $s .= "<cell>" . utf8_decode($row["observaciones"]) . "</cell>";
    $s .= "<cell>" . $row["id_pagos_cobrar"] . "</cell>";
    $s .= "</row>";
}
$s .= "</rows>";
echo $s;
