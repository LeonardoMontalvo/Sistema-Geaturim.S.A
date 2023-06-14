<?php
session_start();
include '../../../procesos/base.php';
$conexion = conectarse();

$codigosfactura = $_POST["codigos_factura"];
$idproveedor = $_POST["id_proveedor"];

error_reporting(0);

registrarCodigosFactua();

//guardar codigos factura en codigos_productos_proveedor
function registrarCodigosFactua()
{
    global $idproveedor, $codigosfactura;
    $codigos = buscarCodigosProductos();
    foreach ($codigosfactura as $val) {
        foreach ($codigos as $val1) {
            if ($val == $val1["codigo"]) {
                guardarCodigo($idproveedor, $val, $val1["cod_productos"]);
                unset($val1);
            }
        }
    }
}

//buscar codigo principal de factura en codigo de productos
function buscarCodigosProductos()
{
    global $conexion;
    $sql = "select cod_productos,codigo from productos where estado='Activo'";
    $res = pg_query($conexion, $sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}

//guardar código producto proveedor
function guardarCodigo($idproveedor, $codproveedor, $idproducto)
{
    if (existeCodigo($idproveedor, $codproveedor) == 0) {
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
function existeCodigo($idproveedor, $codproveedor)
{
    global $conexion;
    $sql = "select * from codigos_productos_proveedor where id_proveedor=$idproveedor 
    and cod_prod_proveedor='$codproveedor'";
    $res = pg_query($conexion, $sql);
    return pg_num_rows($res);
}
