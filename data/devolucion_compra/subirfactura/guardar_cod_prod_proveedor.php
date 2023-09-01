<?php
session_start();
include '../../../procesos/base.php';
$conexion = conectarse();

$idproveedor = $_POST["id_proveedor"];
$codproveedor = $_POST["cod_prod_proveedor"];
$idproducto = $_POST["id_producto"];

guardarCodigo($idproveedor, $codproveedor, $idproducto);

function guardarCodigo($idproveedor, $codproveedor, $idproducto)
{
    if (existeCodigo($idproveedor, $codproveedor) > 0) {
        updateCodigo($idproveedor, $codproveedor, $idproducto);
    } else {
        insertarCodigo($idproveedor, $codproveedor, $idproducto);
    }
}

function insertarCodigo($idproveedor, $codproveedor, $idproducto)
{
    global $conexion;
    $sql = "INSERT INTO codigos_productos_proveedor(
        id_proveedor, cod_prod_proveedor, cod_productos)
        VALUES ($idproveedor, '$codproveedor', $idproducto);
        ";
    $res = pg_query($conexion, $sql);
}
function updateCodigo($idproveedor, $codproveedor, $idproducto)
{
    global $conexion;
    $sql = "UPDATE codigos_productos_proveedor
    SET cod_productos=$idproducto
    WHERE id_proveedor=$idproveedor and cod_prod_proveedor='$codproveedor'";
    $res = pg_query($conexion, $sql);
}
function existeCodigo($idproveedor, $codproveedor)
{
    global $conexion;
    $sql = "select * from codigos_productos_proveedor where id_proveedor=$idproveedor 
    and cod_prod_proveedor='$codproveedor'";
    $res = pg_query($conexion, $sql);
    return pg_num_rows($res);
}
