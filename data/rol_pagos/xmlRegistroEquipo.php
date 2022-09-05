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
$result = pg_query("SELECT COUNT(*) AS count FROM registro_equipo");
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
    $SQL = "select R.id_registro, R.id_cliente, C.nombres_cli, R.id_tipo_equipo, A.descripcion, R.fecha_ingreso, R.fecha_salida, R.modelo, R.nro_serie, R.id_marca, M.nombre_marca, R.id_color, O.nombre_color, R.observaciones, R.detalles, R.estado from registro_equipo R, clientes C, tipo_equipo A, marcas M, color O where R.id_cliente = C.id_cliente and R.id_tipo_equipo = A.id_tipo_equipo and R.id_marca = M.id_marca  and R.id_color = O.id_color  ORDER BY $sidx $sord offset $start limit $limit";
} else {
    $campo = $_GET['searchField'];
    if ($campo == 'ruc_ci') {
        $campo = 'identificacion';
    }
    if ($_GET['searchOper'] == 'eq') {
        $SQL = "select R.id_registro, R.id_cliente, C.nombres_cli, R.id_tipo_equipo, A.descripcion, R.fecha_ingreso, R.fecha_salida, R.modelo, R.nro_serie, R.id_marca, M.nombre_marca, R.id_color, O.nombre_color, R.observaciones, R.detalles, R.estado from registro_equipo R, clientes C, tipo_equipo A, marcas M, color O where R.id_cliente = C.id_cliente and R.id_tipo_equipo = A.id_tipo_equipo and R.id_marca = M.id_marca  and R.id_color = O.id_color and $campo = '$_GET[searchString]' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'ne') {
        $SQL = "select R.id_registro, R.id_cliente, C.nombres_cli, R.id_tipo_equipo, A.descripcion, R.fecha_ingreso, R.fecha_salida, R.modelo, R.nro_serie, R.id_marca, M.nombre_marca, R.id_color, O.nombre_color, R.observaciones, R.detalles, R.estado from registro_equipo R, clientes C, tipo_equipo A, marcas M, color O where R.id_cliente = C.id_cliente and R.id_tipo_equipo = A.id_tipo_equipo and R.id_marca = M.id_marca  and R.id_color = O.id_color and $campo != '$_GET[searchString]' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'bw') {
        $SQL = "select R.id_registro, R.id_cliente, C.nombres_cli, R.id_tipo_equipo, A.descripcion, R.fecha_ingreso, R.fecha_salida, R.modelo, R.nro_serie, R.id_marca, M.nombre_marca, R.id_color, O.nombre_color, R.observaciones, R.detalles, R.estado from registro_equipo R, clientes C, tipo_equipo A, marcas M, color O where R.id_cliente = C.id_cliente and R.id_tipo_equipo = A.id_tipo_equipo and R.id_marca = M.id_marca  and R.id_color = O.id_color and $campo like '$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'bn') {
        $SQL = "select R.id_registro, R.id_cliente, C.nombres_cli, R.id_tipo_equipo, A.descripcion, R.fecha_ingreso, R.fecha_salida, R.modelo, R.nro_serie, R.id_marca, M.nombre_marca, R.id_color, O.nombre_color, R.observaciones, R.detalles, R.estado from registro_equipo R, clientes C, tipo_equipo A, marcas M, color O where R.id_cliente = C.id_cliente and R.id_tipo_equipo = A.id_tipo_equipo and R.id_marca = M.id_marca  and R.id_color = O.id_color and $campo not like '$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'ew') {
        $SQL = "select R.id_registro, R.id_cliente, C.nombres_cli, R.id_tipo_equipo, A.descripcion, R.fecha_ingreso, R.fecha_salida, R.modelo, R.nro_serie, R.id_marca, M.nombre_marca, R.id_color, O.nombre_color, R.observaciones, R.detalles, R.estado from registro_equipo R, clientes C, tipo_equipo A, marcas M, color O where R.id_cliente = C.id_cliente and R.id_tipo_equipo = A.id_tipo_equipo and R.id_marca = M.id_marca  and R.id_color = O.id_color and $campo like '%$_GET[searchString]' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'en') {
        $SQL = "select R.id_registro, R.id_cliente, C.nombres_cli, R.id_tipo_equipo, A.descripcion, R.fecha_ingreso, R.fecha_salida, R.modelo, R.nro_serie, R.id_marca, M.nombre_marca, R.id_color, O.nombre_color, R.observaciones, R.detalles, R.estado from registro_equipo R, clientes C, tipo_equipo A, marcas M, color O where R.id_cliente = C.id_cliente and R.id_tipo_equipo = A.id_tipo_equipo and R.id_marca = M.id_marca  and R.id_color = O.id_color and $campo not like '%$_GET[searchString]' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'cn') {
        $SQL = "select R.id_registro, R.id_cliente, C.nombres_cli, R.id_tipo_equipo, A.descripcion, R.fecha_ingreso, R.fecha_salida, R.modelo, R.nro_serie, R.id_marca, M.nombre_marca, R.id_color, O.nombre_color, R.observaciones, R.detalles, R.estado from registro_equipo R, clientes C, tipo_equipo A, marcas M, color O where R.id_cliente = C.id_cliente and R.id_tipo_equipo = A.id_tipo_equipo and R.id_marca = M.id_marca  and R.id_color = O.id_color and $campo like '%$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'nc') {
        $SQL = "select R.id_registro, R.id_cliente, C.nombres_cli, R.id_tipo_equipo, A.descripcion, R.fecha_ingreso, R.fecha_salida, R.modelo, R.nro_serie, R.id_marca, M.nombre_marca, R.id_color, O.nombre_color, R.observaciones, R.detalles, R.estado from registro_equipo R, clientes C, tipo_equipo A, marcas M, color O where R.id_cliente = C.id_cliente and R.id_tipo_equipo = A.id_tipo_equipo and R.id_marca = M.id_marca  and R.id_color = O.id_color and $campo not like '%$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'in') {
        $SQL = "select R.id_registro, R.id_cliente, C.nombres_cli, R.id_tipo_equipo, A.descripcion, R.fecha_ingreso, R.fecha_salida, R.modelo, R.nro_serie, R.id_marca, M.nombre_marca, R.id_color, O.nombre_color, R.observaciones, R.detalles, R.estado from registro_equipo R, clientes C, tipo_equipo A, marcas M, color O where R.id_cliente = C.id_cliente and R.id_tipo_equipo = A.id_tipo_equipo and R.id_marca = M.id_marca  and R.id_color = O.id_color and $campo like '%$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
    }
    if ($_GET['searchOper'] == 'ni') {
        $SQL = "select R.id_registro, R.id_cliente, C.nombres_cli, R.id_tipo_equipo, A.descripcion, R.fecha_ingreso, R.fecha_salida, R.modelo, R.nro_serie, R.id_marca, M.nombre_marca, R.id_color, O.nombre_color, R.observaciones, R.detalles, R.estado from registro_equipo R, clientes C, tipo_equipo A, marcas M, color O where R.id_cliente = C.id_cliente and R.id_tipo_equipo = A.id_tipo_equipo and R.id_marca = M.id_marca  and R.id_color = O.id_color and $campo not like '%$_GET[searchString]%' ORDER BY $sidx $sord offset $start limit $limit";
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
?>
