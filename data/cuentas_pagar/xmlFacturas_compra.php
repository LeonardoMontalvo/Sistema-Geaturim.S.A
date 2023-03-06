<?php

include '../../procesos/base.php';
error_reporting(0);
$page = $_GET['page'];
$limit = $_GET['rows'];

if ($_GET['tipo'] == "EXTERNA") {
    $result = pg_query("SELECT COUNT(*) AS count FROM c_pagarexternas");
} else {
    if ($_GET['tipo'] == "INTERNA") {
        $result = pg_query("SELECT COUNT(*) AS count FROM pagos_compra");
    }
}

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
if ($_GET['tipo'] == "EXTERNA") {
    $SQL = "select CE.id_c_pagarexternas, num_factura, CE.tipo_documento, CE.fecha_actual, CE.total, CE.saldo  from c_pagarexternas CE where CE.id_proveedor='$_GET[id_proveedor]' and CE.estado='Activo' offset $start limit $limit";
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
        $s .= "<cell>" . $row[2] . "</cell>";
        $s .= "<cell>" . $row[3] . "</cell>";
        $s .= "<cell>" . $row[4] . "</cell>";
        $s .= "<cell></cell>";
        $s .= "<cell>" . $row[5] . "</cell>";
        $s .= "</row>";
    }
    $s .= "</rows>";
} else {
    if ($_GET['tipo'] == "INTERNA") {
        $SQL = " (SELECT cp.id_pagos_compra, g.num_factura, cp.tipo_documento, g.fecha_emision, cp.monto_credito, cp.saldo  ,cp.comprao_gasto
                FROM pagos_compra cp 
                inner join formas_pago_mixto_g fpm
                on cp.id_factura_compra=fpm.id_gastos
                and fpm.forma_pago='CREDITO'
                and cp.comprao_gasto='G'
                inner join gastos g
                on g.id_gastos=fpm.id_gastos
                WHERE cp.id_proveedor='$_GET[id_proveedor]' and cp.estado='Activo'
             
                ORDER BY id_pagos_compra)
                union all
                (SELECT cP.id_pagos_compra, g.num_serie, cP.tipo_documento, g.fecha_emision, cP.monto_credito, cP.saldo  ,cp.comprao_gasto
                FROM pagos_compra cp 
                inner join formas_pago_mixto_c fpm
                on cp.id_factura_compra=fpm.id_factura_compra
                and cp.comprao_gasto='C'
                and fpm.forma_pago='CREDITO'
                inner join factura_compra g
                on g.id_factura_compra=fpm.id_factura_compra
                WHERE cp.id_proveedor='$_GET[id_proveedor]' and cp.estado='Activo'
                ORDER BY id_pagos_compra) 
                union all
                (SELECT cP.id_pagos_compra, g.num_serie, cP.tipo_documento, g.fecha_actual fecha_emision, cP.monto_credito, cP.saldo  ,cp.comprao_gasto
                FROM pagos_compra cp 
                inner join formas_pago_mixto_nv fpm
                on cp.id_factura_compra=fpm.id_devolucion_venta
                and cp.comprao_gasto='NC'
                and fpm.forma_pago='CXP'
                inner join devolucion_venta g
                on g.id_devolucion_venta=fpm.id_devolucion_venta
                WHERE cp.id_proveedor='$_GET[id_proveedor]' and cp.estado='Activo'
                ORDER BY id_pagos_compra) 
                offset $start limit $limit";    
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
            $s .= "<cell>" . $row[2] . "</cell>";
            $s .= "<cell>" . $row[3] . "</cell>";
            $s .= "<cell>" . $row[4] . "</cell>";
            $s .= "<cell></cell>";
            $s .= "<cell>" . $row[5] . "</cell>";
             $s .= "<cell>" . $row[6] . "</cell>";
            $s .= "</row>";
        }
        $s .= "</rows>";
    }
}
echo $s;
?>
