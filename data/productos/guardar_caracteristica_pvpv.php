<?php
session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();

$id = getIdCaracteristica();
$codprod = $_POST["cod_productos"];
$nombrec = $_POST["editar_pvp"];

$sql = "INSERT INTO pvp_venta_editable( id_pvp_venta_editable, cod_productos, editar_pvp) VALUES ($id, $codprod, '$nombrec');";
$res = pg_query($sql);

if (empty($res)) {
    echo 0;
}
echo $id;

function getIdCaracteristica()
{
    $sql = "select max(id_pvp_venta_editable) from pvp_venta_editable";
    $res = pg_query($sql);
    $row = pg_fetch_row($res);
    if (empty($row)) {
        return 1;
    }
    return $row[0] + 1;
}
