<?php

include '../../procesos/base.php';
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
$search = $_GET['_search'];


if (!$sidx)
    $sidx = 1;
$result = pg_query("SELECT COUNT(*) AS count FROM contrato_alquiler_vehiculo_trasporte where estado='Activo'");
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
    $SQL = "SELECT id_contrato, fecha_contrato, fecha_salida, fecha_retorno, 
        nro_personas, valor, c.nombres_cli, c.identificacion, nro_contrato
        FROM contrato_alquiler_vehiculo_trasporte cavt 
        inner join clientes c on cavt.id_cliente=c.id_cliente 
        where cavt.estado='Activo' ORDER BY  $sidx $sord offset $start limit $limit";
} else {
    $campo = $_GET['searchField'];
    $valor = $_GET['searchString'];
    if ($campo == 'nombres_cli') {
        $campo = "upper($campo)";
        $valor = mb_strtoupper($valor);
    }


    if ($_GET['searchOper'] == 'eq') {
        $trquery = pg_query("SELECT count(*)
        FROM contrato_alquiler_vehiculo_trasporte cavt 
        inner join clientes c on cavt.id_cliente=c.id_cliente 
        where cavt.estado='Activo' and $campo = '$valor'");

        while ($row = pg_fetch_row($trquery)) {
            $count = $row[0];
            $total_pages = ceil($count / $limit);
        }
        $SQL = "SELECT id_contrato, fecha_contrato, fecha_salida, fecha_retorno, 
        nro_personas, valor, c.nombres_cli, c.identificacion, nro_contrato
        FROM contrato_alquiler_vehiculo_trasporte cavt 
        inner join clientes c on cavt.id_cliente=c.id_cliente 
        where cavt.estado='Activo' and $campo = '$valor' ORDER BY $sidx $sord offset $start limit $limit";
    }

    if ($_GET['searchOper'] == 'cn') {
        $trquery = pg_query("SELECT count(*)
        FROM contrato_alquiler_vehiculo_trasporte cavt 
        inner join clientes c on cavt.id_cliente=c.id_cliente 
        where cavt.estado='Activo' and $campo = '%$valor%'");

        while ($row = pg_fetch_row($trquery)) {
            $count = $row[0];
            $total_pages = ceil($count / $limit);
        }
        $SQL = "SELECT id_contrato, fecha_contrato, fecha_salida, fecha_retorno, 
        nro_personas, valor, c.nombres_cli, c.identificacion, nro_contrato 
        FROM contrato_alquiler_vehiculo_trasporte cavt 
        inner join clientes c on cavt.id_cliente=c.id_cliente 
        where cavt.estado='Activo' and $campo like '%$valor%' ORDER BY $sidx $sord offset $start limit $limit";
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
    $s .= "<row id='" . $row['id_contrato'] . "'>";
    $s .= "<cell>" . $row['nro_contrato'] . "</cell>";
    $s .= "<cell>" . $row['nombres_cli'] . "</cell>";
    $s .= "<cell>" . $row['identificacion'] . "</cell>";
    $s .= "<cell>" . $row['fecha_contrato'] . "</cell>";
    $s .= "<cell>" . $row['valor'] . "</cell>";
    $s .= "</row>";
}
$s .= "</rows>";
echo $s;
