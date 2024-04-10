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
$result = pg_query("SELECT COUNT(*) AS count from factura_venta, clientes ,usuario, empresa where  factura_venta.id_cliente=clientes.id_cliente and factura_venta.id_usuario=usuario.id_usuario  and  factura_venta.id_empresa='$conpuntoresult' and factura_venta.estado = 'Activo'");
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

    $SQL = "select DISTINCT id_factura_venta, identificacion,nombres_cli,(num_serie || '-' || num_factura) AS num_factura,total_venta,factura_venta.fecha_actual from factura_venta,clientes,usuario,empresa where  factura_venta.id_cliente=clientes.id_cliente and factura_venta.id_usuario=usuario.id_usuario and factura_venta.estado = 'Activo'  and  factura_venta.id_empresa='$conpuntoresult'  ORDER BY $sidx $sord offset $start limit $limit";
} else {
    if ($_GET['searchOper'] == 'eq') {
        $SQL = "select id_factura_venta,identificacion,nombres_cli,(num_serie || '-' || num_factura) AS num_factura,total_venta,factura_venta.fecha_actual from factura_venta,clientes,usuario,empresa where  factura_venta.id_cliente=clientes.id_cliente and factura_venta.id_usuario=usuario.id_usuario and factura_venta.estado = 'Activo'  and  factura_venta.id_empresa='$conpuntoresult'  and $_GET[searchField] = '$_GET[searchString]' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'ne') {
        $SQL = "select id_factura_venta,identificacion,nombres_cli,(num_serie || '-' || num_factura) AS num_factura,total_venta,factura_venta.fecha_actual from factura_venta,clientes,usuario,empresa where   factura_venta.id_cliente=clientes.id_cliente and factura_venta.id_usuario=usuario.id_usuario and factura_venta.estado = 'Activo'  and  factura_venta.id_empresa='$conpuntoresult'  and $_GET[searchField] != '$_GET[searchString]' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'bw') {
        $SQL = "select id_factura_venta,identificacion,nombres_cli,(num_serie || '-' || num_factura) AS num_factura,total_venta,factura_venta.fecha_actual from factura_venta,clientes,usuario,empresa  where   factura_venta.id_cliente=clientes.id_cliente and factura_venta.id_usuario=usuario.id_usuario and factura_venta.estado = 'Activo'  and  factura_venta.id_empresa='$conpuntoresult'  and $_GET[searchField] like '$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'bn') {
        $SQL = "select id_factura_venta,identificacion,nombres_cli,(num_serie || '-' || num_factura) AS num_factura,total_venta,factura_venta.fecha_actual from factura_venta,clientes,usuario,empresa where  factura_venta.id_cliente=clientes.id_cliente and factura_venta.id_usuario=usuario.id_usuario and factura_venta.estado = 'Activo'  and  factura_venta.id_empresa='$conpuntoresult'  and $_GET[searchField] not like '$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'ew') {
        $SQL = "select id_factura_venta,identificacion,nombres_cli,(num_serie || '-' || num_factura) AS num_factura,total_venta,factura_venta.fecha_actual from factura_venta,clientes,usuario,empresa where  factura_venta.id_cliente=clientes.id_cliente and factura_venta.id_usuario=usuario.id_usuario and factura_venta.estado = 'Activo'  and  factura_venta.id_empresa='$conpuntoresult'  and $_GET[searchField] like '%$_GET[searchString]' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'en') {
        $SQL = "select id_factura_venta,identificacion,nombres_cli,(num_serie || '-' || num_factura) AS num_factura,total_venta,factura_venta.fecha_actual from factura_venta,clientes,usuario,empresa where  factura_venta.id_cliente=clientes.id_cliente and factura_venta.id_usuario=usuario.id_usuario and factura_venta.estado = 'Activo'  and  factura_venta.id_empresa='$conpuntoresult'  and $_GET[searchField] not like '%$_GET[searchString]' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'cn') {
        $SQL = "select id_factura_venta,identificacion,nombres_cli,(num_serie || '-' || num_factura) AS num_factura,total_venta,factura_venta.fecha_actual from factura_venta,clientes,usuario,empresa where    factura_venta.id_cliente=clientes.id_cliente and factura_venta.id_usuario=usuario.id_usuario and factura_venta.estado = 'Activo'  and  factura_venta.id_empresa='$conpuntoresult'  and $_GET[searchField] like '%$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'nc') {
        $SQL = "select id_factura_venta,identificacion,nombres_cli,(num_serie || '-' || num_factura) AS num_factura,total_venta,factura_venta.fecha_actual from factura_venta,clientes,usuario,empresa   where factura_venta.id_cliente=clientes.id_cliente and factura_venta.id_usuario=usuario.id_usuario and factura_venta.estado = 'Activo' and    and  factura_venta.id_empresa='$conpuntoresult'  and $_GET[searchField] not like '%$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'in') {
        $SQL = "select id_factura_venta,identificacion,nombres_cli,(num_serie || '-' || num_factura) AS num_factura,total_venta,factura_venta.fecha_actual from factura_venta,clientes,usuario,empresa  where factura_venta.id_cliente=clientes.id_cliente and factura_venta.id_usuario=usuario.id_usuario and factura_venta.estado = 'Activo' and    and  factura_venta.id_empresa='$conpuntoresult'  and $_GET[searchField] like '%$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'ni') {
        $SQL = "select id_factura_venta,identificacion,nombres_cli,(num_serie || '-' || num_factura) AS num_factura,total_venta,factura_venta.fecha_actual from factura_venta,clientes,usuario,empresa  where factura_venta.id_cliente=clientes.id_cliente and factura_venta.id_usuario=usuario.id_usuario and factura_venta.estado = 'Activo' and    and  factura_venta.id_empresa='$conpuntoresult'  and $_GET[searchField] not like '%$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
    }
    //echo $SQL;
}
$result = pg_query($SQL);
header("Content-type: text/xml;charset=utf-8");
$s = "<?xml version='1.0' encoding='utf-8'?>"; $s .= "
<rows>"; $s .= "
    <page>" . $page . "</page>"; $s .= "
    <total>" . $total_pages . "</total>"; $s .= "
    <records>" . $count . "</records>"; while ($row = pg_fetch_row($result)) { $s .= "
    <row id='" . $row[0] . "'>"; $s .= "
        <cell>" . $row[0] . "</cell>"; $s .= "
        <cell>" . $row[1] . "</cell>"; $s .= "
        <cell>" . $row[2] . "</cell>"; $s .= "
        <cell>" . $row[3] . "</cell>"; $s .= "
        <cell>" .  round($row[4], 4) . "</cell>"; $s .= "
           
        <cell>" . $row[5] . "</cell>"; $s .= "
    </row>"; } $s .= "
</rows>"; echo $s; ?>