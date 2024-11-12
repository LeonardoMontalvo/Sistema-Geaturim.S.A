<?php

session_start();
include '../../procesos/base.php';
conectarse();
$cont = 0;
$operaicon = $_POST['oper'];
if (isset($operaicon) && !empty($operaicon)) {
    switch ($operaicon) {
        case 'add';
            agregarConductor();
            break;
        case 'edit';
            modificarConductor("id_conductor={$_POST['id']}");
            break;
        case 'del';
            cambiarEstadoConductor('Inactivo', "id_conductor={$_POST['id']}");
            break;
        case 'buscartodopaginado';
            echo json_encode(buscarTodoPaginado());
            break;
    }
}

function agregarConductor() {

    if (existeConductor($_POST['ci'])) {
        http_response_code(400);
        header(utf8_decode(trim("HTTP/1.0 400 No se pudo guardar el registro, la cédula {$_POST['ci']} ya esta registrada.")));
        exit();
    }

    if (existeConductor($_POST['ci'], 'Inactivo')) {
        cambiarEstadoConductor('Activo', "ci='{$_POST['ci']}'");
        modificarConductor("ci='{$_POST['ci']}'");
        exit();
    }

    $consulta = pg_query("select max(id_conductor) from contrato_conductor");
    while ($row = pg_fetch_row($consulta)) {
        $cont = $row[0];
    }
    $cont++;
    $sql = "INSERT INTO contrato_conductor(
        id_conductor, nombres, ci, telefono, estado, apellidos, direccion,correo)
        VALUES ($cont,'" .
            mb_strtoupper(str_replace("'", "''", trim($_POST['nombres'])))
            . "', '" . trim($_POST['ci']) . "', '" . trim($_POST['telefono']) . "', 'Activo', '" .
            mb_strtoupper(str_replace("'", "''", trim($_POST['apellidos'])))
            . "', '" . str_replace("'", "''", trim($_POST['direccion'])) . "','"
            . str_replace("'", "''", trim($_POST['correo'])) . "');
        ";
    $consulta = pg_query($sql);
    if (!$consulta) {
        http_response_code(500);
        header(trim("HTTP/1.0 500 No se pudo guardar el registro."));
    }
}

function modificarConductor($condicion) {
    $sql = "UPDATE contrato_conductor
    SET 
    nombres='" . mb_strtoupper(str_replace("'", "''", trim($_POST['nombres']))) . "', 
    ci='" . trim($_POST['ci']) . "', 
    telefono='" . trim($_POST['telefono']) . "', 
    apellidos='" . mb_strtoupper(str_replace("'", "''", trim($_POST['apellidos']))) . "', 
    direccion='" . str_replace("'", "''", trim($_POST['direccion'])) . "',
    correo='" . str_replace("'", "''", trim($_POST['correo'])) . "'
    WHERE $condicion;
    ";
    $consulta = pg_query($sql);
    if (!$consulta) {
        http_response_code(500);
        header(trim("HTTP/1.0 500 No se pudo modificar el registro."));
        exit();
    }
}

function cambiarEstadoConductor($estado, $condicion) {
    $sql = "UPDATE contrato_conductor
    SET 
    estado='$estado'
    WHERE $condicion;
    ";
    $consulta = pg_query($sql);
    if (!$consulta) {
        http_response_code(500);
        header(trim("HTTP/1.0 500 No se pudo eliminar el registro."));
        exit();
    }
}

function existeConductor($ci, $estado = 'Activo') {
    $sql = "select estado from contrato_conductor 
    where estado='$estado' 
    and ci='$ci'
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

    $consultaTotal = pg_query("select count(*) from contrato_conductor
    where estado='Activo' and (lower(nombres) like lower('%$texto%') or apellidos like lower('%$texto%') or ci like '%$texto%')");

    $totalRegistros = pg_fetch_row($consultaTotal)[0];

    $sql = "select*from contrato_conductor
    where estado='Activo' and (lower(nombres) like lower('%$texto%') or apellidos like lower('%$texto%') or ci like '%$texto%')
    limit 10 offset " . $offset;

    $consulta = pg_query($sql);

    if (pg_num_rows($consulta) > 0) {
        return array('registros' => pg_fetch_all($consulta), 'totalRegistros' => $totalRegistros);
    }
    return array('registros' => [], 'totalRegistros' => 0);
}
