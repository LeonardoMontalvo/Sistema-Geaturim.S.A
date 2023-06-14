<?php
session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();

$id = getIdDescuento();


$descripcion = $_POST["descripcion"];
$fecha_desde = $_POST["fecha_desde"];
$fecha_hasta = $_POST["fecha_hasta"];

$cate = $_POST["cate"];
$id_categoria = $_POST["id_categoria"];
$porcentaje_promo = $_POST["porcentaje_promo"];


$bodega = $_SESSION["PV"];

$sql = "
    INSERT INTO descuentos_producto(
    id_descuento, descripcion, nro_producto, porcentaje_descuento, 
    estado, id_punto_venta)
    VALUES ($id, '$descripcion', $nroproducto, $porcentaje, 
    'Activo',$bodega);
    ";





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
