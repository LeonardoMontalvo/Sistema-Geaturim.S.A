<?php
session_start();
include '../../../procesos/base.php';
$conexion = conectarse();

$idproveedor = $_GET["id_proveedor"];
$codproveedor = $_GET["cod_prod_proveedor"];

echo json_encode(getCodigo($idproveedor, $codproveedor));

function getCodigo($idproveedor, $codproveedor)
{
    global $conexion;
    $sql = "select * from codigos_productos_proveedor where id_proveedor=$idproveedor 
    and cod_prod_proveedor='$codproveedor'";
    $res = pg_query($conexion, $sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows[0];
}
