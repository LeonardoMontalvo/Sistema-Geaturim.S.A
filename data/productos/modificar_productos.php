<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
error_reporting(0);

obtenerNombreArchivo($_FILES["archivo"]);
$update;
$updateStock;

if ($nombre == "") {
    $update = actualizarProductos($_POST['cod_productos'], $_POST['cod_prod'], $_POST['cod_barras'], $_POST['nombre_art'], obtenerValorIva($_POST['iva']), $_POST['series'], 
            $_POST['precio_compra'], $_POST['utilidad_minorista'], $_POST['utilidad_mayorista'], $_POST['precio_minorista'], $_POST['precio_mayorista'], $_POST['id_categoria'], 
            $_POST['id_marca'], $_POST['stock'], $_POST['minimo'], $_POST['maximo'], $_POST['fecha_creacion'], $_POST['id_modelo'], $_POST['id_aplicacion'], $_POST['descuento'], 
            'Activo', $_POST['inventario'], 'NULL', 'NULL', '', $_SESSION['PV'], 'No', $_POST['precio_negocio'], $_POST['idcontable'], $_POST['proveedor'], 
            $_POST['cantidad_descuento'], $_POST['utilidad_negocio'], $_SESSION['id'], $_POST['iva'], obtenerValorTarifa($_POST['tarifa']), $_POST['bien_servicio'], $_POST['cantidad_mayorista'], $_POST['cantidad_negocio']);
} else {
    $foto = $_POST['cod_productos'] . '.' . $extension;
    move_uploaded_file($_FILES["archivo"]["tmp_name"], "fotos_productos/" . $foto);

    $update = actualizarProductos($_POST['cod_productos'], $_POST['cod_prod'], $_POST['cod_barras'], $_POST['nombre_art'], obtenerValorIva($_POST['iva']), $_POST['series'], 
            $_POST['precio_compra'], $_POST['utilidad_minorista'], $_POST['utilidad_mayorista'], $_POST['precio_minorista'], $_POST['precio_mayorista'], $_POST['id_categoria'], 
            $_POST['id_marca'], $_POST['stock'], $_POST['minimo'], $_POST['maximo'], $_POST['fecha_creacion'], $_POST['id_modelo'], $_POST['id_aplicacion'], $_POST['descuento'], 
            'Activo', $_POST['inventario'], 'NULL', 'NULL', $foto, $_SESSION['PV'], 'No', $_POST['precio_negocio'], $_POST['idcontable'], $_POST['proveedor'], 
            $_POST['cantidad_descuento'], $_POST['utilidad_negocio'], $_SESSION['id'], $_POST['iva'], obtenerValorTarifa($_POST['tarifa']), $tarifavalor, $_POST['bien_servicio']);
}

if ($update) {
    echo 1;
} else {
    echo 0;
}

/**
 * FUNCIÓN PARA ACTUALIZAR PRODUCTO
 *  
 * @param type $producto
 * @param type $codigo
 * @param type $barras
 * @param type $articulo
 * @param type $iva
 * @param type $series
 * @param type $precioCompra
 * @param type $utiliMinorista
 * @param type $utilMayorista
 * @param type $ivaMinorista
 * @param type $ivaMayorista
 * @param type $id_categoria
 * @param type $id_marca
 * @param type $stock
 * @param type $stockMin
 * @param type $stockMax
 * @param type $fechaCreación
 * @param type $id_generico
 * @param type $id_aplicacion
 * @param type $descuento
 * @param type $estado
 * @param type $inventariable
 * @param type $existencia
 * @param type $diferencia
 * @param type $imagen
 * @param type $id_bodega
 * @param type $incluyeIva
 * @param type $ivaNegocio
 * @param type $id_plan_cuentas
 * @param type $id_proveedor
 * @param type $cantidad_descuento
 * @param type $utilidad_negocio
 * @param type $id_usuario
 * @param type $id_timpu
 * @param type $id_taimpuesto
 * @param type $bien_servicios
 * @return boolean
 */
function actualizarProductos($producto, $codigo, $barras, $articulo, $iva, $series, $precioCompra, $utiliMinorista, $utilMayorista, $ivaMinorista, $ivaMayorista, $id_categoria, 
        $id_marca, $stock, $stockMin, $stockMax, $fechaCreación, $id_generico, $id_aplicacion, $descuento, $estado, $inventariable, $existencia, $diferencia, $imagen, $id_bodega, 
        $incluyeIva, $ivaNegocio, $id_plan_cuentas, $id_proveedor, $cantidad_descuento, $utilidad_negocio, $id_usuario, $id_timpu, $id_taimpuesto, $bien_servicios,$cantidad_mayorista,$cantidad_negocio) {

    $update = "UPDATE productos SET codigo = '$codigo', cod_barras = '$barras', articulo = '$articulo', iva = '$iva', series = '$series', "
            . "precio_compra = " . ($precioCompra==NULL?"0.0000":number_format($precioCompra, 4, '.', '')) . ", utilidad_minorista = " . ($utiliMinorista==NULL?"0.0000":number_format($utiliMinorista, 4, '.', '')) . ", "
            . "utilidad_mayorista = " . ($utilMayorista==NULL?"0.0000":number_format($utilMayorista, 4, '.', '')) . ", iva_minorista = " . ($ivaMinorista==NULL?"0.0000":number_format($ivaMinorista, 4, '.', '')) . ", "
            . "iva_mayorista = " . number_format($ivaMayorista, 4, '.', '') . ", id_categoria =" . ($id_categoria == NULL ? "NULL" : $id_categoria) . ", "
            . "id_marca = " . ($id_marca == NULL ? "NULL" : "'$id_marca'") . ", stock = " . ($stock==NULL?"0.00":number_format($stock, 2, '.', '')) . ", stock_minimo = $stockMin, "
            . "stock_maximo = $stockMax, fecha_creacion = '$fechaCreación', id_generico =" . ($id_generico == NULL ? "NULL" : $id_generico) . ", id_aplicacion =" . ($id_aplicacion == NULL ? "NULL" : $id_aplicacion) . ", "
            . "descuento = $descuento, estado = '$estado', inventariable = '$inventariable', existencia = $existencia, diferencia = $diferencia, imagen = '$imagen', "
            . "id_bodega = " . ($id_bodega == NULL ? "NULL" : $id_bodega) . ", incluye_iva = '$incluyeIva', iva_negocio = " . ($ivaNegocio=NULL?"0.0000":number_format($ivaNegocio, 4, '.', '')) . ", "
            . "id_plan_cuentas = " . ($id_plan_cuentas == NULL ? "NULL" : $id_plan_cuentas) . ", id_proveedor = " . ($id_proveedor == NULL ? "NULL" : $id_proveedor) . ", "
            . "cantidad_descuento = $cantidad_descuento, utilidad_negocio = " . ($utilidad_negocio==NULL?"0.0000":number_format($utilidad_negocio, 4, '.', '')) . ", id_usuario = " . ($id_usuario == NULL ? "NULL" : $id_usuario) . ", "
            . "id_timpu = " . ($id_timpu == NULL ? "NULL" : $id_timpu) . ", id_taimpuesto = " . ($id_taimpuesto == NULL ? "NULL" : $id_taimpuesto) . ", bien_servicios = '$bien_servicios', cantidad_mayorista = '$cantidad_mayorista', cantidad_negocio = '$cantidad_negocio' "
            . "WHERE cod_productos = $producto";
    
    /*echo '<br>GMODIFICAR PRODUCTO<br>';
    echo $update.'<br>';*/
    
    if (pg_query($update)) {
         // Auditoria
        insert_registro("MODIFICACION PRODUCTO: $articulo CON CODIGO: $codigo COSTO: $precioCompra PRECIO: $ivaMinorista ID PROVEEDOR $id_proveedor");
        return TRUE;
    }
    return FALSE;
}

/**
 * FUNCION PARA OBTENER NOBRE Y EXTENCION DE ARCHIVO
 * 
 * @param type $archivo - ARCHIVO
 */
function obtenerNombreArchivo($archivo) {
    $extensionTmp = explode(".", $archivo["name"]);
    $extensionTmp = end($extensionTmp);
    $type = $archivo["type"];
    $tmp_name = $archivo["tmp_name"];
    $size = $archivo["size"];
    $GLOBALS['nombre'] = basename($archivo["name"], "." . $extensionTmp);
    $GLOBALS['extension'] = $extensionTmp;
}

/**
 * FUNCIÓN PARA 
 * @param type $valoriva
 * @return string
 */
function obtenerValorIva($valoriva) {
    //echo '<br>VALOR IVA: ' . $valoriva;
    if ($valoriva == 1) {
        return "Si";
    } else if ($valoriva == 4) {
        return "No";
    }
}

/**
 * FUNCIÓN PARA OBTENER ID DE VALOR IVA
 * @param type $valortarifa
 * @return int
 */
function obtenerValorTarifa($valortarifa) {
    if ($valortarifa == "12%") {
        return 2;
    } else {
        return $valortarifa;
    }
}

?>
