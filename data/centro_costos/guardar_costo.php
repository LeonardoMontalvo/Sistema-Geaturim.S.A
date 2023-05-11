<?php
session_start();
include '../../procesos/base.php';
$conexion = conectarse();

echo guardarCentroCosto(mb_strtoupper($_POST["nombre"]), $_POST["descripcion"]);


function getIdCentroCosto()
{
    $sql = "select coalesce(max(id_centro_costo),0) max from centro_costos";
    $res = pg_query($sql);
    return pg_fetch_assoc($res)["max"] + 1;
}

function guardarCentroCosto($nombre, $descripcion)
{
    $id = getIdCentroCosto();
    $sql = "
    INSERT INTO centro_costos(
        id_centro_costo, nombre, descripcion, estado)
    VALUES ($id, '$nombre', '$descripcion', 'Activo');
    ";
    $res = pg_query($sql);
    if (empty($res)) {
        return 0;
    }
    return $id;
}
