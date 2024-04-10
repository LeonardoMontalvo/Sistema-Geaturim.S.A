<?php
session_start();
include '../../procesos/base.php';
$conexion = conectarse();

echo modificarCentroCosto($_POST["id"], $_POST["nombre"], $_POST["descripcion"]);

function modificarCentroCosto($id, $nombre, $descripcion)
{
    $sql = "
    UPDATE centro_costos
    SET nombre='$nombre', descripcion='$descripcion'
    WHERE id_centro_costo=$id;
    ";
    $res = pg_query($sql);
    if (empty($res)) {
        return 0;
    }
    return $id;
}
