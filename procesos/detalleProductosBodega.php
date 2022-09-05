<?php

require_once 'fecha.php';
// Auditoria
include_once 'auditoria.php';

function obtenerId()
{
    $consulta = pg_query("select max(id_detalle_productos_bodega) from detalle_producto_bodega");
    $id = (pg_fetch_row($consulta)[0] + 1);
    return $id;
}

function guardarDetalleProductoBodega($producto, $bodega, $usuario, $stock)
{
    $sql = "INSERT INTO detalle_producto_bodega (id_detalle_productos_bodega, cod_productos, id_bodega, id_usuario, fecha, hora, stock) "
        . "VALUES(" . obtenerId() . ", $producto, $bodega, " . ($usuario == NULL ? "NULL" : $usuario) . ", '" . obtenerFechaActual() . "', '" . obtenerHoraActual() . "', "
        . "" . number_format($stock, 2, '.', '') . ")";
    pg_query($sql);
    // Auditoria
    insert_registro('CREACION DETALLE PRODUCTO BODEGA CON ID: ' . $bodega . ', PRODUCTO CON ID: ' . $producto . ', STOCK: ' . $stock);
}

function actualizarDetalleProductoBodega($producto, $bodega, $stck)
{
    $sql = "UPDATE detalle_producto_bodega SET  fecha = '" . obtenerFechaActual() . "', hora = '" . obtenerHoraActual() . "', stock = " . number_format($stck, 2, '.', '') . " "
        . "WHERE cod_productos =$producto AND id_bodega =$bodega";
    pg_query($sql);
    // Auditoria
    insert_registro('MODIFICACION DETALLE PRODUCTO BODEGA CON ID: ' . $bodega . ', PRODUCTO CON ID: ' . $producto. ', STOCK: ' . $stck);
}

function obtenerStock($producto, $bodega)
{
    $sql = "SELECT stock FROM detalle_producto_bodega WHERE cod_productos=$producto AND id_bodega=$bodega";
    return pg_fetch_row(pg_query($sql))[0];
}
