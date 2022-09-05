<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
$cont = 0;
$nombreM = !empty($_POST['nombre_m']) ? mb_strtoupper(str_replace("'", "''", trim($_POST['nombre_m']))) : '';

$operaicon = $_POST['oper'];
if (isset($operaicon) && !empty($operaicon)) {
    switch ($operaicon) {
        case 'buscartodo':
            echo json_encode(buscarTodo());
            break;
        case 'buscarxid':
            echo json_encode(buscarPorId($_POST['id_marca']));
            break;
        case 'add';
            echo agregarMarcaVehiculo($nombreM);
            break;
    }
}

function buscarTodo() {
    $sql = "SELECT id_marca, nombre
    FROM contrato_marca_vehiculo order by nombre;";

    $consulta = pg_query($sql);

    return pg_fetch_all($consulta);
}

function buscarPorId($id) {
    $sql = "SELECT id_marca, nombre
    FROM contrato_marca_vehiculo where id_marca=$id;";

    $consulta = pg_query($sql);

    return pg_fetch_all($consulta);
}

function agregarMarcaVehiculo($nombreM) {
    $idtv = existeTipoVehiculo($nombreM);
    if ($idtv) {
        return $idtv;
    }

    $consulta = pg_query("select max(id_marca) from contrato_marca_vehiculo");
    while ($row = pg_fetch_row($consulta)) {
        $cont = $row[0];
    }
    $cont++;

    $sql = "INSERT INTO contrato_marca_vehiculo(
            id_marca, nombre)
            VALUES ($cont, '$nombreM');
            ";
    $consulta = pg_query($sql);
    // Auditoria
    insert_registro('CREACION MARCA VEHICULO: ' . $nombreM);
    if ($consulta) {
        return $cont;
    }
    return 0;
}

function existeTipoVehiculo($nombre) {
    $sql = "select id_marca from contrato_marca_vehiculo
    where nombre='$nombre'
    limit 1";
    $consulta = pg_query($sql);
    if (pg_num_rows($consulta) > 0) {
        return pg_fetch_row($consulta)[0];
    }
    return false;
}
