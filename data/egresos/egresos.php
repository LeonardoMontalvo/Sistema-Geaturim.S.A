<?php

include_once __DIR__ . '/../../procesos/base.php';
require_once __DIR__ . '/../../procesos/fecha.php';
require_once __DIR__ . '/../../procesos/kardexValorizado.php';
require_once __DIR__ . '/../../procesos/detalleProductosBodega.php';
$conexion = conectarse();

function procesoGuardarEgreso($bodega, $usuario, $origen, $destino, $tarifa0, $tarifa12, $iva, $descuento, $total, $observaciones, $campos) {

    $cont1 = obtenerIdEgreso();
    $docu = str_pad($cont1, 9, "0", STR_PAD_LEFT);

    $gegreso = guardarEgreso(
            $cont1, $bodega, $usuario, $docu, $origen, $destino, $tarifa0, $tarifa12, $iva, $descuento, $total, $observaciones, 'Activo'
    );

    if (!$gegreso) {
        return "Error al guardar egreso";
    }
    for ($i = 1; $i < count($campos[0]); $i++) {
        if (!verificarStock($campos[0][$i], $bodega, $campos[1][$i])) {
            return "La cantidad del producto " . obtenerProducto($campos[0][$i])["articulo"] . " sobrepasa el stock disponible.";
        }
        $gdetalle = guardarDetalleEgreso($cont1, $campos[0][$i], $campos[1][$i], $campos[2][$i], $campos[3][$i], $campos[4][$i], 'Activo', $campos[6][$i], $campos[7][$i]);

        if ($campos[5][$i] != 0) {
            $campos[1][$i] = $campos[5][$i];
        } else {
            $campos[1][$i] = $campos[1][$i];
        }

        procesarKardexSalida($campos[0][$i], "T.E.L - " . $docu, $campos[1][$i], obtenerStock($campos[0][$i], $bodega), NULL, 'Activo', $bodega, 'E', $cont1, null, NULL, NULL, NULL, $observaciones, NULL, NULL, $usuario);

        if (!$gdetalle) {
            return "Error al guardar detalle";
        }
    }
    return $cont1;
}

///////////////////////////////
function obtenerIdEgreso() {
    $sql = "SELECT max(e.id_egresos) FROM egresos e";
    $id = (pg_fetch_row(pg_query($sql))[0] + 1);
    return $id;
}

function obtenerIdDetalleEgreso() {
    $sq = "SELECT max(de.id_detalle_egreso) FROM detalle_egreso de";
    $id = (pg_fetch_row(pg_query($sq))[0] + 1);
    return $id;
}

function guardarEgreso($id, $bodega, $usuario, $comprobante, $origen, $destino, $tarifa0, $tarifa12, $iva, $descuento, $total, $observaciones, $estado) {
    $sql = "INSERT INTO egresos(id_egresos, id_empresa, id_usuario, comprobante, fecha_actual, hora_actual, origen, destino, tarifa0, tarifa12, iva_egreso, descuento_egreso, "
            . "total_egreso, observaciones, estado) "
            . "VALUES ($id, $bodega, $usuario,'$comprobante','" . obtenerFechaActual() . "','" . obtenerHoraActual() . "' ," . ($origen == NULL ? "NULL" : $origen) . " "
            . ", " . ($destino == NULL ? "NULL" : $destino) . ", " . number_format($tarifa0, 4, '.', '') . ", " . number_format($tarifa12, 4, '.', '') . ""
            . ", " . number_format($iva, 4, '.', '') . ", " . number_format($descuento, 4, '.', '') . ", " . number_format($total, 4, '.', '') . ", '$observaciones', '$estado')";
    return pg_query($sql);
}

function guardarDetalleEgreso($egreso, $producto, $cantidad, $precio, $descuento, $total, $estado, $cantidad_unidad, $unidad_medida) {
    $sql = "INSERT INTO detalle_egreso(id_detalle_egreso, id_egresos, cod_productos, cantidad, precio_costo, descuento, total, estado,cantidad_unidad,unidad_medida) "
            . "VALUES (" . obtenerIdDetalleEgreso() . ", $egreso, $producto, " . number_format($cantidad, 2, '.', '') . ", " . number_format($precio, 4, '.', '') . ""
            . ", " . number_format($descuento, 4, '.', '') . ", " . number_format($total, 4, '.', '') . ", '$estado', '$cantidad_unidad', '$unidad_medida');";
    return pg_query($sql);
}

function verificarStock($codprod, $bodega, $cantidadsalida) {
    $producto = obtenerProducto($codprod);
    if ($producto["inventariable"] == 'Si') {
        $stock = obtenerStock($codprod, $bodega);
        if ($stock < $cantidadsalida) {
            return false;
        }
        return true;
    }
    return true;
}

function obtenerProducto($codprod) {
    global $conexion;
    $sql = "select * from productos where cod_productos=$codprod;";
    $res = pg_query($conexion, $sql);
    $rows = pg_fetch_all($res);
    if (!$rows) {
        return null;
    }
    return $rows[0];
}
