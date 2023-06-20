<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();

$id = $_POST["id_descuento"];
$descripcion = $_POST["descripcion"];
$fecha_desde = $_POST["fecha_desde"];
$fecha_hasta = $_POST["fecha_hasta"];

$cate = $_POST["cate"];
$id_categoria = $_POST["id_categoria"];
$porcentaje_promo = $_POST["porcentaje_promo"];

$bodega = $_SESSION["PV"];
//echo ''. "
//    UPDATE promocion_venta
//    SET 
//    descripcion='$descripcion', 
//    id_categoria='$id_categoria',    
//    fecha_desde='$fecha_desde', 
//    fecha_hasta='$fecha_hasta',
//    porcentaje_promocion='$porcentaje_promo'
//    WHERE id_promocion_venta=$id;
//    ";
$sql = "
    UPDATE promocion_venta
    SET 
    descripcion='$descripcion', 
    id_categoria='$id_categoria',    
    fecha_desde='$fecha_desde', 
    fecha_hasta='$fecha_hasta',
    porcentaje_promocion='$porcentaje_promo'
    WHERE id_promocion_venta='$id';
    ";
$res = pg_query($sql);

if (empty($res)) {
    echo 0;
}
echo $id;
