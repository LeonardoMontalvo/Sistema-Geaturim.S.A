<?php
session_start();
include '../../procesos/base.php';

conectarse();
error_reporting(0);

$id = getIdUmProducto();

$sql = "
INSERT INTO unidad_medida_productos(
    id_unidad_medida_productos, cod_productos, id_unidades, pvpmino, 
    pvpmayo, pvpnego, estado,por_defecto,pvpmayo_cantidad,pvpnego_cantidad)
VALUES ($id, $_POST[cod_producto], $_POST[id_unidad], $_POST[pvpmino], $_POST[pvpmayo], $_POST[pvpnego], 'Activo', 'f', $_POST[pvpmayo_cantidad], $_POST[pvpnego_cantidad]);
";
//echo ''.$sql;
$res = pg_query($sql);
if (empty($res)) {
    echo json_encode(0);
} else {
    echo json_encode($id);
}

function getIdUmProducto()
{
    $sql = "select max(id_unidad_medida_productos) from unidad_medida_productos";
    $res = pg_query($sql);
    $row = pg_fetch_row($res);
    if (empty($row)) {
        return 1;
    }
    return $row[0] + 1;
}
