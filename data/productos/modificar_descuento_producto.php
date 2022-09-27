<?php
session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();

$id =  $_POST["id_descuento"];
$descripcion = $_POST["descripcion"];
$nroproducto = $_POST["nro_producto"];
$porcentaje = $_POST["porcentaje"];
$bodega = $_SESSION["PV"];

$sql = "
    UPDATE descuentos_producto
    SET 
    descripcion='$descripcion', 
    nro_producto=$nroproducto, 
    porcentaje_descuento=$porcentaje
    WHERE id_descuento=$id;
    ";
$res = pg_query($sql);

if (empty($res)) {
    echo 0;
}
echo $id;
