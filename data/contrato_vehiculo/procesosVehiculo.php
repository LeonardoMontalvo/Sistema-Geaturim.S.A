<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
$cont = 0;
$operaicon = $_POST['oper'];
if (isset($operaicon) && !empty($operaicon)) {
    switch ($operaicon) {
        case 'add';
            agregarVehiculo();
            break;
        case 'edit';
            modificarVehiculo("id_vehiculo={$_POST['id']}");
            break;
        case 'del';
            cambiarEstadoVehiculo('Inactivo', "id_vehiculo={$_POST['id']}");
            break;
        case 'buscartodopaginado':
            echo json_encode(buscarTodoPaginado());
            break;
    }
}

function agregarVehiculo() {

    if (existeVehiculo(mb_strtoupper(trim($_POST['placa'])))) {
        http_response_code(400);
        header(utf8_decode(trim("HTTP/1.0 400 No se pudo guardar el registro, la placa {$_POST['placa']} ya esta registrada.")));
        exit();
    }

    if (existeVehiculo(mb_strtoupper(trim($_POST['placa'])), 'Inactivo')) {
        cambiarEstadoVehiculo('Activo', "placa='{$_POST['placa']}'");
        modificarVehiculo("placa='{$_POST['placa']}'");
        exit();
    }

    $consulta = pg_query("select max(id_vehiculo) from contrato_vehiculo");
    while ($row = pg_fetch_row($consulta)) {
        $cont = $row[0];
    }
    $cont++;
    $sql = "INSERT INTO contrato_vehiculo(
        id_vehiculo, placa, estado, capacidad_pasajeros, id_tipo_vehiculo, id_marca , anio, modelo)
        VALUES ($cont,'" .
            str_replace("'", "''", mb_strtoupper(trim($_POST['placa'])))
            . "', 'Activo', '" . trim($_POST['capacidad_pasajeros']) . "'," .
            $_POST['id_tipo_vehiculo'].", ".$_POST['id_marca'].""
            . "," . trim($_POST['anio']) . ",'" . str_replace("'", "''", trim($_POST['modelo'])) . "');
    ";
    $consulta = pg_query($sql);
    // Auditoria
    insert_registro('CREACION VEHICULO: ' . $_POST['modelo'] . ' CON PLACA: ' . $_POST['placa']);
    if (!$consulta) {
        http_response_code(500);
        header(trim("HTTP/1.0 500 No se pudo guardar el registro."));
    }
}

function modificarVehiculo($condicion) {
    $sql = "UPDATE contrato_vehiculo
    SET 
    placa='" . str_replace("'", "''", mb_strtoupper(trim($_POST['placa']))) . "', 
    capacidad_pasajeros='" . trim($_POST['capacidad_pasajeros']) . "', 
    id_tipo_vehiculo=" . $_POST['id_tipo_vehiculo'] . ",
    anio=". trim($_POST['anio']) .",
    modelo='".str_replace("'", "''", trim($_POST['modelo']))."'
    WHERE $condicion;
    ";
    $consulta = pg_query($sql);
    // Auditoria
    insert_registro('MODIFICACION VEHICULO: ' . $_POST['modelo'] . ' CON PLACA: ' . $_POST['placa']);
    if (!$consulta) {
        http_response_code(500);
        header(trim("HTTP/1.0 500 No se pudo modificar el registro."));
        exit();
    }
}

function cambiarEstadoVehiculo($estado, $condicion) {
    $sql = "UPDATE contrato_vehiculo
    SET 
    estado='$estado'
    WHERE $condicion;
    ";
    $consulta = pg_query($sql);
    // Auditoria
    insert_registro(strtoupper($estado) . ' VEHICULO DONDE: ' . $condicion);
    if (!$consulta) {
        http_response_code(500);
        header(trim("HTTP/1.0 500 No se pudo eliminar el registro."));
        exit();
    }
}

function existeVehiculo($placa, $estado = 'Activo') {
    $sql = "select estado from contrato_vehiculo 
    where estado='$estado' 
    and placa='$placa'
    limit 1";
    $consulta = pg_query($sql);
    if (pg_num_rows($consulta) > 0) {
        return true;
    }
    return false;
}

function buscarTodoPaginado() {
    $texto = $_POST['search'];
    $pagina = $_POST['page'];
    $offset = 10 * ($pagina - 1);

    $consultaTotal = pg_query("select count(*) from contrato_vehiculo
    where estado='Activo' and (lower(placa) like lower('%$texto%'))");

    $totalRegistros = pg_fetch_row($consultaTotal)[0];

    $sql = "select*from contrato_vehiculo
    where estado='Activo' and (lower(placa) like lower('%$texto%'))
    limit 10 offset " . $offset;

    $consulta = pg_query($sql);

    if (pg_num_rows($consulta) > 0) {
        return array('registros' => pg_fetch_all($consulta), 'totalRegistros' => $totalRegistros);
    }
    return array('registros' => [], 'totalRegistros' => 0);
}
