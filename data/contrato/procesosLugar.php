<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
$id_lugar = !empty($_POST['id_lugar']) ? $_POST['id_lugar'] : '';
$nombreLugar = !empty($_POST['nombre_lugar']) ? mb_strtoupper(str_replace("'", "''", trim($_POST['nombre_lugar']))) : '';
$operaicon = $_POST['oper'];
if (isset($operaicon) && !empty($operaicon)) {
    switch ($operaicon) {
        case 'add';
            echo agregarLugar($nombreLugar);
            break;
        case 'buscartodopaginado':
            echo json_encode(buscarTodoPaginado());
            break;
        case 'buscarxid':
            echo json_encode(buscarPorId($id_lugar));
            break;
    }
}

function agregarLugar($nombreLugar) {
    $idlugare = existeLugar($nombreLugar);
    if ($idlugare) {
        return $idlugare;
    }

    $consultaid = pg_query("select max(id_lugar) from contrato_lugar");
    while ($row = pg_fetch_row($consultaid)) {
        $cont = $row[0];
    }
    $cont++;

    $sql = "INSERT INTO contrato_lugar(
            id_lugar, nombre, estado)
            VALUES ($cont, '$nombreLugar', 'Activo');
            ";
    $consulta = pg_query($sql);
    // Auditoria
    insert_registro('CREACION LUGAR CON ID: ' . $cont);
    if ($consulta) {
        return $cont;
    }
    return 0;
}

function buscarTodoPaginado() {
    $texto = $_POST['search'];
    $pagina = $_POST['page'];
    $offset = 10 * ($pagina - 1);

    $consultaTotal = pg_query("select count(*) from contrato_lugar
    where estado='Activo' and (lower(nombre) like lower('%$texto%'))");

    $totalRegistros = pg_fetch_row($consultaTotal)[0];

    $sql = "select*from contrato_lugar
    where estado='Activo' and (lower(nombre) like lower('%$texto%'))
    limit 10 offset " . $offset;

    $consulta = pg_query($sql);

    if (pg_num_rows($consulta) > 0) {
        return array('registros' => pg_fetch_all($consulta), 'totalRegistros' => $totalRegistros);
    }
    return array('registros' => [], 'totalRegistros' => 0);
}

function buscarPorId($id) {
    $sql = "SELECT *
    FROM contrato_lugar where id_lugar=$id and estado='Activo';";

    $consulta = pg_query($sql);
    if (pg_num_rows($consulta) > 0) {
        return pg_fetch_all($consulta);
    }
    return [];
}

function existeLugar($nombre, $estado = 'Activo') {
    $sql = "select id_lugar from contrato_lugar
    where estado='$estado' 
    and nombre='$nombre'
    limit 1";
    $consulta = pg_query($sql);
    if (pg_num_rows($consulta) > 0) {
        return pg_fetch_row($consulta)[0];
    }
    return false;
}
