<?php

include '../../procesos/base.php';
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
$search = $_GET['_search'];


if (!$sidx)
    $sidx = 1;
$result = pg_query("SELECT COUNT(*) AS count FROM contrato_vehiculo where estado='Activo'");
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
    $SQL = "select contrato_vehiculo.*,contrato_tipo_vehiculo.nombre as nombre_tipo_vehiculo,
    contrato_marca_vehiculo.nombre as nombre_marca from 
contrato_vehiculo  
inner join contrato_tipo_vehiculo
on contrato_vehiculo.id_tipo_vehiculo=contrato_tipo_vehiculo.id_tipo_vehiculo
inner join contrato_marca_vehiculo
on contrato_vehiculo.id_marca=contrato_marca_vehiculo.id_marca
where estado='Activo' ORDER BY  $sidx $sord offset $start limit $limit";
} else {
    $campo = $_GET['searchField'];

    if ($_GET['searchOper'] == 'eq') {
        $trquery = pg_query("select count(*) 
        from contrato_vehiculo 
        where estado='Activo' and upper($campo) = upper('$_GET[searchString]')");

        while ($row = pg_fetch_row($trquery)) {
            $count = $row[0];
            $total_pages = ceil($count / $limit);
        }
        $SQL = "select contrato_vehiculo.*,contrato_tipo_vehiculo.nombre as nombre_tipo_vehiculo,
        contrato_marca_vehiculo.nombre as nombre_marca from 
contrato_vehiculo  
inner join contrato_tipo_vehiculo
on contrato_vehiculo.id_tipo_vehiculo=contrato_tipo_vehiculo.id_tipo_vehiculo
inner join contrato_marca_vehiculo
on contrato_vehiculo.id_marca=contrato_marca_vehiculo.id_marca
where estado='Activo' and upper($campo) = upper('$_GET[searchString]') ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'cn') {
        $trquery = pg_query("select count(*) 
        from contrato_vehiculo 
        where estado='Activo' and upper($campo) like upper('%$_GET[searchString]%')");

        while ($row = pg_fetch_row($trquery)) {
            $count = $row[0];
            $total_pages = ceil($count / $limit);
        }
        $SQL = "select contrato_vehiculo.*,contrato_tipo_vehiculo.nombre as nombre_tipo_vehiculo,
        contrato_marca_vehiculo.nombre as nombre_marca from 
contrato_vehiculo  
inner join contrato_tipo_vehiculo
on contrato_vehiculo.id_tipo_vehiculo=contrato_tipo_vehiculo.id_tipo_vehiculo
inner join contrato_marca_vehiculo
on contrato_vehiculo.id_marca=contrato_marca_vehiculo.id_marca
where estado='Activo' and upper($campo) like upper('%$_GET[searchString]%') ORDER BY $sidx $sord offset $start limit $limit";
    }
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
    $s .= "<row id='" . $row['id_vehiculo'] . "'>";
    $s .= "<cell>" . $row['id_vehiculo'] . "</cell>";
    $s .= "<cell>" . $row['placa'] . "</cell>";
    $s .= "<cell>" . $row['nombre_tipo_vehiculo'] . "</cell>";
    $s .= "<cell>" . $row['nombre_marca'] . "</cell>";
    $s .= "<cell>" . $row['anio'] . "</cell>";
    $s .= "<cell>" . $row['modelo'] . "</cell>";
    $s .= "<cell>" . $row['capacidad_pasajeros'] . "</cell>";
    $s .= "</row>";
}
$s .= "</rows>";
echo $s;


