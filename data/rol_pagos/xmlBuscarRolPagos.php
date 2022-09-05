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
$result = pg_query("SELECT COUNT(*) AS count from rol_pagos, empleado ,usuario,detalle_rol where detalle_rol.id_rol_pagos=rol_pagos.id_rol_pagos and detalle_rol.id_empleado=empleado.id_empleado and rol_pagos.id_usuario=usuario.id_usuario and rol_pagos.id_empresa='$conpuntoresult' and rol_pagos.estado = 'Activo'");
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

    $SQL = "select DISTINCT rol_pagos.id_rol_pagos, mes,anio,total,rol_pagos.fecha_actual from rol_pagos,empleado,usuario,empresa,detalle_rol where  detalle_rol.id_empleado=empleado.id_empleado and rol_pagos.id_usuario=usuario.id_usuario and rol_pagos.estado = 'Activo'  and  rol_pagos.id_empresa='$conpuntoresult'   ORDER BY $sidx $sord offset $start limit $limit";
} else {
    if ($_GET['searchOper'] == 'eq') {
        $SQL = "select DISTINCT rol_pagos.id_rol_pagos, mes,anio,total,rol_pagos.fecha_actual from rol_pagos,empleado,usuario,empresa,detalle_rol where  detalle_rol.id_empleado=empleado.id_empleado and rol_pagos.id_usuario=usuario.id_usuario and rol_pagos.estado = 'Activo'  and  rol_pagos.id_empresa='$conpuntoresult'  and $_GET[searchField] = '$_GET[searchString]' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'ne') {
        $SQL = "select DISTINCT rol_pagos.id_rol_pagos, mes,anio,total,rol_pagos.fecha_actual from rol_pagos,empleado,usuario,empresa,detalle_rol where  detalle_rol.id_empleado=empleado.id_empleado and rol_pagos.id_usuario=usuario.id_usuario and rol_pagos.estado = 'Activo'  and  rol_pagos.id_empresa='$conpuntoresult'   and $_GET[searchField] != '$_GET[searchString]' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'bw') {
        $SQL = "select DISTINCT rol_pagos.id_rol_pagos, mes,anio,total,rol_pagos.fecha_actual from rol_pagos,empleado,usuario,empresa,detalle_rol where  detalle_rol.id_empleado=empleado.id_empleado and rol_pagos.id_usuario=usuario.id_usuario and rol_pagos.estado = 'Activo'  and  rol_pagos.id_empresa='$conpuntoresult'  and $_GET[searchField] like '$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'bn') {
        $SQL = "select DISTINCT rol_pagos.id_rol_pagos, mes,anio,total,rol_pagos.fecha_actual from rol_pagos,empleado,usuario,empresa,detalle_rol where  detalle_rol.id_empleado=empleado.id_empleado and rol_pagos.id_usuario=usuario.id_usuario and rol_pagos.estado = 'Activo'  and  rol_pagos.id_empresa='$conpuntoresult'   and $_GET[searchField] not like '$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'ew') {
        $SQL = "select DISTINCT rol_pagos.id_rol_pagos, mes,anio,total,rol_pagos.fecha_actual from rol_pagos,empleado,usuario,empresa,detalle_rol where  detalle_rol.id_empleado=empleado.id_empleado and rol_pagos.id_usuario=usuario.id_usuario and rol_pagos.estado = 'Activo'  and  rol_pagos.id_empresa='$conpuntoresult'   and $_GET[searchField] like '%$_GET[searchString]' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'en') {
        $SQL = "select DISTINCT rol_pagos.id_rol_pagos, mes,anio,total,rol_pagos.fecha_actual from rol_pagos,empleado,usuario,empresa,detalle_rol where  detalle_rol.id_empleado=empleado.id_empleado and rol_pagos.id_usuario=usuario.id_usuario and rol_pagos.estado = 'Activo'  and  rol_pagos.id_empresa='$conpuntoresult'   and $_GET[searchField] not like '%$_GET[searchString]' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'cn') {
        $SQL = "select DISTINCT rol_pagos.id_rol_pagos, mes,anio,total,rol_pagos.fecha_actual from rol_pagos,empleado,usuario,empresa,detalle_rol where  detalle_rol.id_empleado=empleado.id_empleado and rol_pagos.id_usuario=usuario.id_usuario and rol_pagos.estado = 'Activo'  and  rol_pagos.id_empresa='$conpuntoresult'  and $_GET[searchField] like '%$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'nc') {
        $SQL = "select DISTINCT rol_pagos.id_rol_pagos, mes,anio,total,rol_pagos.fecha_actual from rol_pagos,empleado,usuario,empresa,detalle_rol where  detalle_rol.id_empleado=empleado.id_empleado and rol_pagos.id_usuario=usuario.id_usuario and rol_pagos.estado = 'Activo'  and  rol_pagos.id_empresa='$conpuntoresult'   and $_GET[searchField] not like '%$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'in') {
        $SQL = "select DISTINCT rol_pagos.id_rol_pagos, mes,anio,total,rol_pagos.fecha_actual from rol_pagos,empleado,usuario,empresa,detalle_rol where  detalle_rol.id_empleado=empleado.id_empleado and rol_pagos.id_usuario=usuario.id_usuario and rol_pagos.estado = 'Activo'  and  rol_pagos.id_empresa='$conpuntoresult'  and $_GET[searchField] like '%$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'ni') {
        $SQL = "select DISTINCT rol_pagos.id_rol_pagos, mes,anio,total,rol_pagos.fecha_actual from rol_pagos,empleado,usuario,empresa,detalle_rol where  detalle_rol.id_empleado=empleado.id_empleado and rol_pagos.id_usuario=usuario.id_usuario and rol_pagos.estado = 'Activo'  and  rol_pagos.id_empresa='$conpuntoresult'  and $_GET[searchField] not like '%$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
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
        <cell>" . $row[4] . "</cell>"; $s .= "
      
    </row>"; } $s .= "
</rows>"; echo $s; ?>