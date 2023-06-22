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


if($id_categoria == "0"){
   $sql = "INSERT INTO promocion_venta(id_promocion_venta, id_categoria, descripcion, fecha_desde, fecha_hasta,porcentaje_promocion,estado,todos_id_categoria)
    VALUES ('$id', '$id_categoria', '$descripcion', '$fecha_desde','$fecha_hasta','$porcentaje_promo','Activo','TODOS');";    
}else{    
       $sql = "INSERT INTO promocion_venta(id_promocion_venta, id_categoria, descripcion, fecha_desde, fecha_hasta,porcentaje_promocion,estado,todos_id_categoria)
    VALUES ('$id', '$id_categoria', '$descripcion', '$fecha_desde','$fecha_hasta','$porcentaje_promo','Activo','ID CATEGORIA');";    
}



$res = pg_query($sql);
if (empty($res)) {
    echo 0;
}
echo $id;
function getIdDescuento()
{
    $sql = "select max(id_promocion_venta) from promocion_venta";
    $res = pg_query($sql);
    $row = pg_fetch_row($res);
    if (empty($row)) {
        return 1;
    }
    return $row[0] + 1;
}
