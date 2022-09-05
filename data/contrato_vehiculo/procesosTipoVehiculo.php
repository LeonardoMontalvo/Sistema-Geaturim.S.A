<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
$cont = 0;
$nombreTV = !empty($_POST['nombre_tv']) ? mb_strtoupper(str_replace("'", "''", trim($_POST['nombre_tv']))) : '';

$operaicon = $_POST['oper'];
if (isset($operaicon) && !empty($operaicon)) {
    switch ($operaicon) {
        case 'buscartodo':
            echo json_encode(buscarTodo());
            break;
        case 'buscarxid':
            echo json_encode(buscarPorId($_POST['id_tipo_vehiculo']));
            break;
        case 'add';
            echo agregarTipoVehiculo($nombreTV);
            break;
    }
}

function buscarTodo() {
    $sql = "SELECT id_tipo_vehiculo, nombre
    FROM contrato_tipo_vehiculo order by nombre;";

    $consulta = pg_query($sql);

    return pg_fetch_all($consulta);
}

function buscarPorId($id) {
    $sql = "SELECT id_tipo_vehiculo, nombre
    FROM contrato_tipo_vehiculo where id_tipo_vehiculo=$id;";

    $consulta = pg_query($sql);

    return pg_fetch_all($consulta);
}

function agregarTipoVehiculo($nombreTV) {
    $idtv = existeTipoVehiculo($nombreTV);
    if ($idtv) {
        return $idtv;
    }

    $consulta = pg_query("select max(id_tipo_vehiculo) from contrato_tipo_vehiculo");
    while ($row = pg_fetch_row($consulta)) {
        $cont = $row[0];
    }
    $cont++;

    $sql = "INSERT INTO contrato_tipo_vehiculo(
            id_tipo_vehiculo, nombre)
            VALUES ($cont, '$nombreTV');
            ";
    $consulta = pg_query($sql);
    // Auditoria
    insert_registro('CREACION TIPO VEHICULO: ' . $nombreTV);
    if ($consulta) {
        return $cont;
    }
    return 0;
}

function existeTipoVehiculo($nombre) {
    $sql = "select id_tipo_vehiculo from contrato_tipo_vehiculo
    where nombre='$nombre'
    limit 1";
    $consulta = pg_query($sql);
    if (pg_num_rows($consulta) > 0) {
        return pg_fetch_row($consulta)[0];
    }
    return false;
}
