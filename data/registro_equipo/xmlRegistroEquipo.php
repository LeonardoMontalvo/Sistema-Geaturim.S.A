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

$tquery = "
    SELECT COUNT(*) AS count 
    from 
    registro_equipo R,
    clientes C,
    tipo_equipo A,
    marcas M,
    color O where R.id_cliente = C.id_cliente and
    R.id_tipo_equipo = A.id_tipo_equipo and
    R.id_marca = M.id_marca  and
    R.id_color = O.id_color
    and R.id_empresa=$conpuntoresult
    and R.estado='Activo'
";
$result = pg_query($tquery);

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
    select R.id_registro,
    R.id_cliente,
    C.identificacion,
    C.nombres_cli,
    R.id_tipo_equipo,
    A.descripcion,
    R.fecha_ingreso,
    R.fecha_salida,
    R.modelo,
    R.nro_serie,
    R.id_marca,
    M.nombre_marca,
    R.id_color,
    O.nombre_color,
    R.observaciones,
    R.detalles,
    R.estado from 
    registro_equipo R,
    clientes C,
    tipo_equipo A,
    marcas M,
    color O where R.id_cliente = C.id_cliente and
    R.id_tipo_equipo = A.id_tipo_equipo and
    R.id_marca = M.id_marca  and
    R.id_color = O.id_color
    and R.estado='Activo'
    and R.id_empresa=$conpuntoresult
    ";
if ($search == 'false') {
    $SQL .= " ORDER BY $sidx $sord offset $start limit $limit";
} else {
    $campo = $_GET['searchField'];
    /* if ($campo == 'ruc_ci') {
        $campo = 'identificacion';
    } */
    if ($_GET['searchOper'] == 'eq') {
        $tquery .= " and $campo = '$_GET[searchString]'";
        $res = pg_query($tquery);
        while ($row = pg_fetch_row($res)) {
            $count = $row[0];
            $total_pages = ceil($count / $limit);
        }
        $SQL .= " and $campo = '$_GET[searchString]' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'cn') {
        $tquery .= " and $campo ilike '%$_GET[searchString]%'";
        $res = pg_query($tquery);
        while ($row = pg_fetch_row($res)) {
            $count = $row[0];
            $total_pages = ceil($count / $limit);
        }
        $SQL .= " and $campo ilike '%$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
    }
}

$result = pg_query($SQL);
header("Content-type: text/xml; charset = utf-8");
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
    $s .= "<cell>" . $row[5] . "</cell>";
    $s .= "<cell>" . $row[6] . "</cell>";
    $s .= "<cell>" . $row[7] . "</cell>";
    $s .= "<cell>" . $row[8] . "</cell>";
    $s .= "<cell>" . $row[9] . "</cell>";
    $s .= "<cell>" . $row[10] . "</cell>";
    $s .= "<cell>" . $row[11] . "</cell>";
    $s .= "<cell>" . $row[12] . "</cell>";
    $s .= "<cell>" . $row[13] . "</cell>";
    $s .= "<cell>" . $row[14] . "</cell>";
    if ($row[15] == "0") {
        $s .= "<cell>" . "Recibido" . "</cell>";
    }
    if ($row[15] == "1") {
        $s .= "<cell>" . "Reparado" . "</cell>";
    }
    if ($row[15] == "2") {
        $s .= "<cell>" . "Entregado" . "</cell>";
    }
    if ($row[15] == "3") {
        $s .= "<cell>" . "En reparación" . "</cell>";
    }

    $s .= "</row>";
}

$s .= "</rows>";
echo $s;
