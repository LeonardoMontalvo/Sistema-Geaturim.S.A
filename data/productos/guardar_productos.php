<?php

session_start();
include '../../procesos/base.php';
require_once '../../procesos/detalleProductosBodega.php';
require_once '../../procesos/kardexValorizado.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
error_reporting(0);
date_default_timezone_set('UTC');
date_default_timezone_set('America/Guayaquil');
$horap = date("g:ia");

/* * ** contador kardex *** */
$cont_k = 0;
$consulta_k = pg_query("select max(id_kardex) from kardex");
while ($row = pg_fetch_row($consulta_k)) {
    $cont_k = $row[0];
}
$cont_k++;

/* * ** contador kardex valorizado *** */
$cont_v = 0;
$consulta_v = pg_query("select max(id_kardex) from kardex_valorizado");
while ($row = pg_fetch_row($consulta_v)) {
    $cont_v = $row[0];
}
$cont_v++;

/* * ** valores imagen *** */
$extension = explode(".", $_FILES["archivo"]["name"]);

$extension = end($extension);
$type = $_FILES["archivo"]["type"];
$tmp_name = $_FILES["archivo"]["tmp_name"];
$size = $_FILES["archivo"]["size"];
$nombre = basename($_FILES["archivo"]["name"], "." . $extension);

/* * ** guardar productos *** */
$producto = obtenerIdProducto();

if ($nombre == "") {
    $valor = number_format($_POST['precio_compra'], 4, '.', '');
    $valor2 = number_format($_POST['precio_minorista'], 4, '.', '');
    $valor3 = number_format($_POST['precio_mayorista'], 4, '.', '');
    $valor4 = number_format(($_POST['precio_negocio'] == NULL ? 0 : $_POST['precio_negocio']), 4, '.', '');

    //////////////////////////////////////////////////
    guardarProducto($producto, $_POST['cod_prod'], strtoupper($_POST['cod_barras']), $_POST['nombre_art'], obtenerValorIva($_POST['tarifa']), $_POST['series'], $valor, $_POST['utilidad_minorista'], $_POST['utilidad_mayorista'], $valor2, $valor3, $_POST['id_categoria'], $_POST['id_marca'], $_POST['stock'], $_POST['minimo'], $_POST['maximo'], $_POST['fecha_creacion'], $_POST['id_modelo'], $_POST['id_aplicacion'], $_POST['descuento'], 'Activo', $_POST['inventario'], NULL, NULL, '', 1, 'No', $valor4, $_POST['idcontable'], $_POST['proveedor'], $_POST['cantidad_descuento'], $_POST['utilidad_negocio'], $_SESSION['id'], $_POST['iva'], $_POST['tarifa'], $_POST['bien_servicio'], $_POST['cantidad_mayorista'], $_POST['cantidad_negocio']);

    /*     * *** KARDEX ***** */
    $cantidad_total = '0.00';
    $precio_unitario_total = number_format($_POST['precio_compra'], 4, '.', '');
    $precio_total_total = number_format($cantidad_total * $precio_unitario_total, 4, '.', '');

    procesarKardexEntrada($producto, "C.P", '0.00', '0.00', $precio_unitario_total, 5, $_SESSION['PV'], 'C.P', $producto, $precio_total_total, NULL, NULL, '', NULL, NULL, NULL, $_SESSION['id']);
} else {
    $foto = $producto . '.' . $extension;
    move_uploaded_file($_FILES["archivo"]["tmp_name"], "fotos_productos/" . $foto);
    $valor = number_format($_POST['precio_compra'], 4, '.', '');
    $valor2 = number_format($_POST['precio_minorista'], 4, '.', '');
    $valor3 = number_format($_POST['precio_mayorista'], 4, '.', '');
    $valor4 = number_format(($_POST['precio_negocio'] == NULL ? 0 : $_POST['precio_negocio']), 4, '.', '');

    /*     * ** GUARDAR PRODUCTO *** */
    guardarProducto(obtenerIdProducto(), $_POST['cod_prod'], strtoupper($_POST['cod_barras']), $_POST['nombre_art'], obtenerValorIva($_POST['tarifa']), $_POST['series'], $valor, $_POST['utilidad_minorista'], $_POST['utilidad_mayorista'], $valor2, $valor3, $_POST['id_categoria'], $_POST['id_marca'], $_POST['stock'], $_POST['minimo'], $_POST['maximo'], $_POST['fecha_creacion'], $_POST['id_modelo'], $_POST['id_aplicacion'], $_POST['descuento'], 'Activo', $_POST['inventario'], NULL, NULL, $foto, 1, 'No', $valor4, $_POST['idcontable'], $_POST['proveedor'], $_POST['cantidad_descuento'], $_POST['utilidad_negocio'], $_SESSION['id'], $_POST['iva'], $_POST['tarifa'], $_POST['bien_servicio'], $_POST['cantidad_mayorista'], $_POST['cantidad_negocio']);

    /*     * ** GUARDAR KARDEX *** */
    $cantidad_total = '0.00';
    $precio_unitario_total = number_format($_POST['precio_compra'], 4, '.', '');
    $precio_total_total = number_format($cantidad_total * $precio_unitario_total, 4, '.', '');

    procesarKardexEntrada($producto, "C.P", '0.00', '0.00', $precio_unitario_total, 5, $_SESSION['PV'], 'C.P', $producto, $precio_total_total, NULL, NULL, '', NULL, NULL, NULL, $_SESSION['id']);
}

$data = 1;
echo $data;

function guardarProducto($cont, $cod_prod, $cod_barras, $articulo, $iva, $series, $precio_compra, $utilidad_minorista, $utilidad_mayorista, $iva_minorista, $iva_mayorista, $id_categoria, $id_marca, $stock, $stock_minimo, $stock_maximo, $fecha_creacion, $id_generico, $id_aplicacion, $descuento, $estado, $inventariable, $existencia, $diferencia, $imagen, $id_bodega, $incluye_iva, $iva_negocio, $id_plan_cuentas, $id_proveedor, $cantidad_descuento, $utilidad_negocio, $id_usuario, $id_timpu, $id_taimpuesto, $bien_servicios, $cantidad_mayorista, $cantidad_negocio)
{
    $sql = "INSERT INTO productos (cod_productos, codigo, cod_barras, articulo, iva, series, precio_compra, utilidad_minorista, utilidad_mayorista, iva_minorista, iva_mayorista, "
        . "id_categoria, id_marca, stock, stock_minimo, stock_maximo, fecha_creacion, id_generico, id_aplicacion, descuento, estado, inventariable, existencia, diferencia, imagen, "
        . "id_bodega, incluye_iva, iva_negocio, id_plan_cuentas, id_proveedor, cantidad_descuento, utilidad_negocio, id_usuario, id_timpu, id_taimpuesto, bien_servicios, cantidad_mayorista, cantidad_negocio) "
        . "VALUES ($cont, '$cod_prod', '$cod_barras', '" . strtoupper($articulo) . "', '$iva', '$series', " . ($precio_compra == NULL ? "0.0000" : number_format($precio_compra, 4, '.', '')) . ", "
        . "" . ($utilidad_minorista == NULL ? "0.0000" : number_format($utilidad_minorista, 4, '.', '')) . ", " . ($utilidad_mayorista == NULL ? "0.0000" : number_format($utilidad_mayorista, 4, '.', '')) . ", " . ($iva_minorista == NULL ? "0.0000" : number_format($iva_minorista, 4, '.', '')) . ", "
        . "" . ($iva_mayorista == NULL ? "0.0000" : number_format($iva_mayorista, 4, '.', '')) . ", " . ($id_categoria == NULL ? "NULL" : "'$id_categoria'") . ", " . ($id_marca == NULL ? "NULL" : "$id_marca") . ", "
        . "" . ($stock == NULL ? "0.00" : number_format($stock, 2, '.', '')) . ", $stock_minimo, $stock_maximo, " . ($fecha_creacion == NULL ? "NULL" : "'$fecha_creacion'") . ", " . ($id_generico == NULL ? "NULL" : $id_generico) . ", "
        . "" . ($id_aplicacion == NULL ? "NULL" : "$id_aplicacion") . "," . ($descuento == NULL ? '0' : $descuento) . ", '$estado', '$inventariable'," . ($existencia == NULL ? "0.00" : number_format($existencia, 2, '.', '')) . "," . ($diferencia == NULL ? "0.00" : number_format($diferencia, 2, '.', '')) . ",'$imagen', " . ($id_bodega == NULL ? "NULL" : $id_bodega) . ", "
        . "'No', " . ($iva_negocio == NULL ? "0.0000" : number_format($iva_negocio, 4, '.', '')) . ", " . ($id_plan_cuentas == NULL ? "NULL" : $id_plan_cuentas) . ", " . ($id_proveedor == NULL ? "NULL" : $id_proveedor) . ", "
        . "" . ($cantidad_descuento == NULL ? '0' : $cantidad_descuento) . "," . ($utilidad_negocio == NULL ? "0.0000" : number_format($utilidad_negocio, 4, '.', '')) . ", $id_usuario, " . ($id_timpu == NULL ? "NULL" : $id_timpu) . ", " . ($id_taimpuesto == NULL ? "NULL" : $id_taimpuesto) . ", "
        . "'$bien_servicios','$cantidad_mayorista','$cantidad_negocio')";
    pg_query($sql);
    // Auditoria
    insert_registro("CREACION PRODUCTO: $articulo CON CODIGO: $cod_prod COSTO: $precio_compra PRECIO: $iva_minorista ID PROVEEDOR $id_proveedor");
}

/**
 * FUNCIÓN PARA 
 * @param type $valoriva
 * @return string
 */
function obtenerValorIva($valoriva)
{
    $sql = "select valor from tarifa_impuesto where id_taimpuesto=$valoriva";
    $res = pg_query($sql);
    $row = pg_fetch_row($res);
    if (!empty($row[0])) {
        return "Si";
    }
    return "No";
}

/**
 * FUNCIÓN PARA OBTENER EL ULTIMO ID INCREMENTADO
 * @return type
 */
function obtenerIdProducto()
{
    $sql = "select max(cod_productos) from productos";
    $id = (pg_fetch_row(pg_query($sql))[0] + 1);
    return $id;
}
