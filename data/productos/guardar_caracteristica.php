<?php
session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();

$id = getIdCaracteristica();
$codprod = $_POST["cod_productos"];
$nombrec = $_POST["nombre"];

$sql = "INSERT INTO producto_caracteristicas(
    id_caracteristica, cod_productos, nombre)
    VALUES ($id, $codprod, '$nombrec');
    ";
$res = pg_query($sql);

if (empty($res)) {
    echo 0;
}
echo $id;

function getIdCaracteristica()
{
    $sql = "select max(id_caracteristica) from producto_caracteristicas";
    $res = pg_query($sql);
    $row = pg_fetch_row($res);
    if (empty($row)) {
        return 1;
    }
    return $row[0] + 1;
}
