<?php
session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();

$id = getIdDescuento();
$descripcion = mb_strtoupper($_POST["descripcion"]);
$nroproducto = $_POST["nro_producto"];
$porcentaje = $_POST["porcentaje"];
$bodega = $_SESSION["PV"];

$sql = "
    INSERT INTO descuentos_producto(
    id_descuento, descripcion, nro_producto, porcentaje_descuento, 
    estado, id_punto_venta)
    VALUES ($id, '$descripcion', $nroproducto, $porcentaje, 
    'Activo',$bodega);
    ";
$res = pg_query($sql);

if (empty($res)) {
    echo 0;
}
echo $id;

function getIdDescuento()
{
    $sql = "select max(id_descuento) from descuentos_producto";
    $res = pg_query($sql);
    $row = pg_fetch_row($res);
    if (empty($row)) {
        return 1;
    }
    return $row[0] + 1;
}
