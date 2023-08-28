<?php
session_start();
include '../../procesos/base.php';
conectarse();
$idgasto = $_GET["id_gasto"];

$sql = "select gastos_personales.*, 
proveedores.identificacion_pro, proveedores.tipo_documento,
usuario.nombre_usuario,
usuario.apellido_usuario 
from gastos_personales
inner join proveedores 
using(id_proveedor)
inner join usuario
using(id_usuario) where id_gastos_personales=$idgasto";
$res = pg_query($sql);
$row = pg_fetch_assoc($res);

$sql = "select*from detalle_gastos_personales where id_gastos_personales=$idgasto";
$res = pg_query($sql);
$rows = pg_fetch_all($res);


echo json_encode([
    "cabecera" => $row,
    "detalles" => $rows
]);
