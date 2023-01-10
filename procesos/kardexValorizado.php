<?php

include_once 'fecha.php';
// Auditoria
include_once 'auditoria.php';

/**
 * FUNCION PARA UBTENER EL ULTIMO ID DE KARDEX VALORIZADO AUMENTADO 1
 * 
 * @return type - NUEVO ID AUMENTADO
 */
function obtenerIdMaxKardexValorizado()
{
    $sql = "SELECT max(id_kardex) FROM kardex_valorizado";
    $id = (pg_fetch_array(pg_query($sql))[0] + 1);
    return $id;
}

/**
 * FUNCION PARA PROCESAR EL KARDEX VALORIZADO A SER INGRESADO
 * 
 * @param type $producto - ID PRODUCTO
 * @param type $fecha - FECHA DE REGISTO
 * @param type $detalle - DETALLE DE LA TRANSACCION
 * @param type $entrada - CANTIDAD A INGRESAR
 * @param type $existencia - ESXISTENCIA DEL PRODUCTO
 * @param type $costUnit - COSTO UNITARIO
 * @param type $estado - ESTADO DE LA TRANSACCION EJ: 1,2,3,4,5,ETC
 * @param type $bodega - ID BODEGA EJ: 1,2,3
 * @param type $compraVenta - DESCRIPOCION: INV, INVS, I, E, A, AI, CP, P, ETC
 * @param type $comprobante - ID DE LA TRASSACCION
 */
function procesarKardexValorizadoEntrada($producto, $fecha, $detalle, $entrada, $existencia, $costUnit, $estado, $bodega, $compraVenta, $comprobante, $debe, $haber, $caldularcp = true)
{
    if (!empty($producto)) {
        $precio_entrada_total = ($entrada * $costUnit);
        $saldo = $existencia + $entrada;
        $costoPromedio = obtenerPrecioTotalKardexValorizadoProducto($producto, $bodega);
        $totalSaldoAnteriorPromedio = ($existencia * $costoPromedio);
        $neto = ($totalSaldoAnteriorPromedio + $precio_entrada_total);

        //if ($saldo > 0) {
        //echo '<br>IF SALDO >0';
        $promedioFinal = ($neto / $saldo);

        if (!$caldularcp) {
            $promedioFinal = $costUnit;
            /* $cprom = obtenerCostoPromedioUnitario($producto, $bodega)[0]['costo_prom_unitario'];
            if (empty($cprom)) {
                $promedioFinal = $costUnit;
            } else {
                $promedioFinal = $cprom;
            } */
        }

        //echo '<br>PROMEDIO FINAL= NETO/SALDO: ' . $promedioFinal;
        /* } else {
          echo '<br>ELSE SALDO >0';
          $promedioFinal = 0.0000;
          echo '<br>PROMEDIO FINAL: ' . $promedioFinal;
          } */
        insertEntradaKardexValorizado($producto, $fecha, $detalle, $entrada, $existencia, $costUnit, $precio_entrada_total, $saldo, $estado, $promedioFinal, $bodega, $compraVenta, $comprobante, $debe, $haber);
        if ($compraVenta == 'C') {
            actualizarPrecioPromedio($producto, $bodega, $promedioFinal);
            actualizarVentaPromedio($producto, $bodega, $promedioFinal);
        }
        if ($compraVenta == 'V' || $compraVenta == 'DV') {
            actualizarVentaPromedio($producto, $bodega, $promedioFinal);
        }
    }
}

function procesarKardexValorizadoEntradaActualizar($producto, $fecha, $detalle, $entrada, $existencia, $costUnit, $estado, $bodega, $compraVenta, $comprobante, $debe, $haber)
{
    if (!empty($producto)) {
        $precio_entrada_total = ($entrada * $costUnit);
        $saldo = $entrada;
        $costoPromedio = obtenerPrecioTotalKardexValorizadoProducto($producto, $bodega);
        $totalSaldoAnteriorPromedio = ($existencia * $costoPromedio);
        $neto = ($totalSaldoAnteriorPromedio + $precio_entrada_total);
        //if ($saldo > 0) {

        $promedioFinal = ($neto / $saldo);
        /* } else {
          $promedioFinal = 0.0000;
          } */
        insertEntradaKardexValorizado($producto, $fecha, $detalle, $entrada, $existencia, $costUnit, $precio_entrada_total, $saldo, $estado, $promedioFinal, $bodega, $compraVenta, $comprobante, $debe, $haber);
        if ($compraVenta == 'C') {
            actualizarPrecioPromedio($producto, $bodega, $promedioFinal);
            actualizarVentaPromedio($producto, $bodega, $promedioFinal);
        }
        if ($compraVenta == 'V' || $compraVenta == 'DV') {
            actualizarVentaPromedio($producto, $bodega, $promedioFinal);
        }
    }
}

/**
 * FUNCION PARA PROCESAR EL KARDEX VALORIZADO DE SALIDAS
 * 
 * @param type $producto - ID PRODUCTO
 * @param type $fecha - FECHA DE REGISTO
 * @param type $detalle - DETALLE DE LA TRANSACCION
 * @param type $salida - CANTIDAD DE SALIDA
 * @param type $existencia - ESXISTENCIA DEL PRODUCTO
 * @param type $estado - ESTADO DE LA TRANSACCION EJ: 1,2,3,4,5,ETC
 * @param type $bodega - ID BODEGA EJ: 1,2,3
 * @param type $compraVenta - DESCRIPOCION: INV, INVS, I, E, A, AI, CP, P, ETC
 * @param type $comprobante - ID DE LA TRASSACCION
 */
function procesarKardexValorizadoSalida($producto, $fecha, $detalle, $salida, $costUnit, $existencia, $estado, $bodega, $compraVenta, $comprobante, $debe, $haber)
{
    if (!empty($producto)) {
        /* if ($compraVenta == 'V') {
          $precio_unitario_salida = obtenerCostoUnitarioProducto($producto);
          } else { */
        //$precio_unitario_salida = $costUnit;
        //}
        $precio_salida_total = ($salida * $costUnit);
        $saldo = $existencia - $salida;
        $costoPromedio = obtenerPrecioTotalKardexValorizadoProducto($producto, $bodega);
        $totalSaldoAnteriorPromedio = ($existencia * $costoPromedio);
        if ($totalSaldoAnteriorPromedio > $precio_salida_total) {
            $neto = ($totalSaldoAnteriorPromedio - $precio_salida_total);
        } else {
            $neto = ($precio_salida_total - $totalSaldoAnteriorPromedio);
        }
        if ($saldo > 0) {
            /*  echo '<BR>EXISTENCIA: ' . $existencia;
              echo '<BR>SALIDA: ' . $salida;
              echo '<BR>TOTAL SALDO ANTERIOR PROMEDIO: ' . $totalSaldoAnteriorPromedio;
              echo '<BR>PRECIO SALIDA TOTAL: ' . $precio_salida_total;
              echo '<BR>NETOS: ' . $neto;
              echo '<BR>SALDO: ' . $saldo; */
            $promedioFinal = ($neto / $saldo);
            //   echo '<BR>PROMEDIO FINAL: ' . $promedioFinal;
        } else {
            $promedioFinal = $costUnit;
        }
        insertSalidaKardexValorizado(
            $producto,
            $fecha,
            $detalle,
            $salida,
            $existencia,
            $costUnit,
            $precio_salida_total,
            $debe,
            $haber,
            $saldo,
            $estado,
            $promedioFinal,
            $bodega,
            $compraVenta,
            $comprobante
        );
        if ($compraVenta == 'C' || $compraVenta == 'AC') {
            actualizarPrecioPromedio($producto, $bodega, $promedioFinal);
            actualizarVentaPromedio($producto, $bodega, $promedioFinal);
        }
        if ($compraVenta == 'V' || $compraVenta == 'AV') {
            actualizarVentaPromedio($producto, $bodega, $promedioFinal);
        }
    }
}

/**
 * FUNCION PARA INGRESAR ENTRADAR A KARDEX VALORIZADO
 * 
 * @param type $producto - ID PRODUCTO
 * @param type $fecha - FECHA DE REGISTRO
 * @param type $detalle - DESCRIPCION DE LA TRANSACCIÓN
 * @param type $entrada - CANTIDAD QUE ENTRA
 * @param type $existencia - EXISTECIA DEL PRODUCTO
 * @param type $costUnit - COSTO UNITARIO
 * @param type $costProm - COSTO PROMEDIO
 * @param type $saldo - SALDO DE LA CANTIDAD DEL PRODUCTO
 * @param type $estado - ESTADO
 * @param type $costPromUnit - COSTO PROMEDIO UNITARIO
 * @param type $bodega - ID BODEGA EJ: 1,2,3
 * @param type $compraVenta - DESCRIPOCION: INV, INVS, I, E, A, AI, CP, P, ETC
 * @param type $comprobante - ID DE LA TRASSACCION
 */
function insertEntradaKardexValorizado($producto, $fecha, $detalle, $entrada, $existencia, $costUnit, $costProm, $saldo, $estado, $costPromUnit, $bodega, $compraVenta, $comprobante, $debe, $haber)
{

    $sql = "INSERT INTO kardex_valorizado(id_kardex, cod_productos, fecha_transaccion, concepto, entrada, existencia, costo_unitario, costo_promedio, debe, haber,saldo, estado, "
        . "costo_prom_unitario, id_empresa, compra_venta, comprobante) "
        . "VALUES (" . obtenerIdMaxKardexValorizado() . ", $producto, " . ($fecha == NULL ? "NULL" : "'$fecha'") . ", '$detalle', " . ($entrada == NULL ? "0.00" : $entrada) . ", "
        . "" . ($existencia == NULL ? "0.00" : $existencia) . " , " . ($costUnit == NULL ? "0.0000" : $costUnit) . ", "
        . "" . ($costProm == NULL ? "0.0000" : $costProm) . "," . ($debe == NULL ? "0.0000" : $debe) . ","
        . "" . ($haber == NULL ? "0.0000" : $haber) . "," . ($saldo == NULL ? "0.0000" : $saldo) . ", "
        . "'$estado', " . ($costPromUnit == NULL ? "0.0000" : $costPromUnit) . ", $bodega, '$compraVenta', '$comprobante')";

    pg_query($sql);
    // Auditoria
    insert_registro('CREACION KARDEX VALORIZADO: ' . $detalle . ', DEL PRODUCTO CON ID: ' . $producto);



    //    	 echo '<br>GUARDAR FACTURA VENTA: <br>' .  "INSERT INTO kardex_valorizado(id_kardex, cod_productos, fecha_transaccion, concepto, entrada, existencia, costo_unitario, costo_promedio, debe, haber,saldo, estado, "
    //            . "costo_prom_unitario, id_empresa, compra_venta, comprobante) "
    //            . "VALUES (" . obtenerIdMaxKardexValorizado() . ", $producto, " . ($fecha == NULL ? "NULL" : "'$fecha'") . ", '$detalle', " . ($entrada == NULL ? "0.00" : $entrada) . ", "
    //            . "" . ($existencia == NULL ? "0.00" : $existencia) . " , " . ($costUnit == NULL ? "0.0000" : $costUnit) . ", "
    //            . "" . ($costProm = NULL ? "0.0000" : $costProm) . "," . ($debe == NULL ? "0.0000" : $debe) . ","
    //            . "" . ($haber == NULL ? "0.0000" : $haber) . "," . ($saldo == NULL ? "0.0000" : $saldo) . ", "
    //            . "'$estado', " . ($costPromUnit == NULL ? "0.0000" : $costPromUnit) . ", $bodega, '$compraVenta', '$comprobante')";//////////////////////////
    //	 
    //	 
}

/**
 * FUNCION PARA INGRESAR SALIDAS A KARDEX VALORIZADO
 * 
 * @param type $producto - ID PRODUCTO
 * @param type $fecha - FECHA DE REGISTRO
 * @param type $detalle - DESCRIPCION DE LA TRANSACCIÓN
 * @param type $salida - CANTIDAD QUE SALE
 * @param type $existencia - EXISTECIA DEL PRODUCTO
 * @param type $costUnit - COSTO UNITARIO
 * @param type $costProm - COSTO PROMEDIO
 * @param type $saldo - SALDO DE LA CANTIDAD DEL PRODUCTO
 * @param type $estado - ESTADO
 * @param type $costPromUnit - COSTO PROMEDIO UNITARIO
 * @param type $bodega - ID BODEGA EJ: 1,2,3
 * @param type $compraVenta - DESCRIOCION: INV, INVS, I, E, A, AI, CP, P, ETC
 * @param type $comprobante - ID DE LA TRASSACCION
 */
function insertSalidaKardexValorizado($producto, $fecha, $detalle, $salida, $existencia, $costUnit, $costProm, $debe, $haber, $saldo, $estado, $costPromUnit, $bodega, $compraVenta, $comprobante)
{
    $sql = "INSERT INTO kardex_valorizado(id_kardex, cod_productos, fecha_transaccion, concepto, salida, existencia, costo_unitario, costo_promedio,debe, haber, saldo, estado, "
        . "costo_prom_unitario, id_empresa, compra_venta, comprobante) "
        . "VALUES (" . obtenerIdMaxKardexValorizado() . ", $producto, " . ($fecha == NULL ? "NULL" : "'$fecha'") . ", '$detalle', " . ($salida == NULL ? "0.00" : $salida) . ", "
        . "" . ($existencia == NULL ? "0.00" : $existencia) . ", " . ($costUnit == NULL ? "0.0000" : $costUnit) . ", " . ($costProm == NULL ? "0.0000" : $costProm) . ","
        . "" . ($debe == NULL ? "0.0000" : $debe) . "," . ($haber == NULL ? "0.0000" : $haber) . ", " . ($saldo == NULL ? "0.00" : $saldo) . ", '$estado', "
        . "" . ($costPromUnit == NULL ? "0.0000" : $costPromUnit) . ", $bodega, '$compraVenta', '$comprobante')";
    pg_query($sql);
    // Auditoria
    insert_registro('CREACION KARDEX VALORIZADO: ' . $detalle . ', DEL PRODUCTO CON ID: ' . $producto);
}

/**
 * FUNCION PARA OBTENER EL COSTO DEL PRODUCTO
 * 
 * @param type $producto - ID DEL PRODUCTO
 * @return type - COSTO REDONDEADO A 4 DECIMAS
 */
function obtenerCostoUnitarioProducto($producto)
{
    $sql = "SELECT precio_compra FROM productos WHERE cod_productos=$producto";
    return round(pg_fetch_array(pg_query($sql))[0], 4);
}

/**
 * FUNCIONP PARA OBTENER PRECIO PROMEDIO DEL PRODUCTO RESPECTO A LA BODEGA
 * 
 * @param type $producto - ID DEL PRODUCTO
 * @param type $bodega - ID BODEGA EJ: 1,2,3
 * @return type - COSTO REDONDEADO A 4 DECIMAS
 */
function obtenerPrecioTotalKardexValorizadoProducto($producto, $bodega)
{
    $sql = "SELECT costo_prom_unitario FROM kardex_valorizado WHERE cod_productos = $producto AND id_empresa = $bodega order by id_kardex desc limit 1";
    return pg_fetch_array(pg_query($sql))[0];
}

/**
 * FUNCION PARA ACTUALIZAR COSTO DEL PRODUCTO
 * 
 * @param type $producto - ID DEL PRODUCTO
 * @param type $precioPromedio - PRECIO PROMEDIO DEL PRODCUTO
 */
function actualizarCostoPromedio($producto, $precioPromedio)
{
    $sql = "UPDATE productos SET precio_compra='" . number_format($precioPromedio, 4, ".", "") . "', iva_minorista='" . number_format($precioPromedio, 4, ".", "") . "'"
        . ",iva_mayorista='" . number_format($precioPromedio, 4, ".", "") . "' WHERE cod_productos=$producto";
    pg_query($sql);
}

/**
 * FUNCION PARA ACTUALIZAR KARDEX
 * 
 * @param type $comprobante - ID DE LA TRANSACCION
 * @param type $bodega - ID DE LA BODEGA EJ: 1,2,3,ETC
 * @param type $producto - ID DEL PRODUCTO
 * @param type $estado - ESTADO ASIGNADO EJ: Activo, Pasico, Inactivo, etc.
 * @param type $compra_venta - INICIAL DE LA TRANSACCION EJ: I, E, FV, A, CP, etc.
 */
function updateKardex($comprobante, $bodega, $producto, $estado, $compra_venta, $origen, $destino)
{
    $sql = "Update kardex Set estado='$estado' where cod_productos=$producto and compra_venta='$compra_venta' and id_empresa=$bodega and comprobante='$comprobante' ";
    if (!empty($origen) && !empty($destino)) {
        $sql .= "and origen=$origen  and destino=$destino ";
    }
    pg_query($sql);
    // Auditoria
    insert_registro('MODIFICACION KARDEX CON ID: ' . $comprobante);
}

/**
 * 
 * @param type $comprobante - ID DE LA TRANSACCION
 * @param type $bodega - ID DE LA BODEGA EJ: 1,2,3,ETC
 * @param type $producto - ID DEL PRODUCTO
 * @param type $estado - ESTADO ASIGNADO EJ: Activo, Pasico, Inactivo, etc.
 * @param type $compra_venta - INICIAL DE LA TRANSACCION EJ: I, E, FV, A, CP, etc.
 */
function updateKardexValorizado($comprobante, $bodega, $producto, $estado, $compra_venta)
{
    $sql = "UPDATE kardex_valorizado SET estado='$estado' WHERE cod_productos=$producto AND id_empresa=$bodega AND comprobante='$comprobante' AND compra_venta='$compra_venta'";
    pg_query($sql);
    // Auditoria
    insert_registro('MODIFICACION KARDEX VALORIZADO CON ID: ' . $comprobante);
}

/**
 * FUNCIÓN PARA INSERTAR KARDEX
 * 
 * @param type $fecha - FECHA ACTUAL
 * @param type $detalle - DETALLE DE LA TRANSACCION EJ: I: ING-000001, EGRE-00001, ETC.
 * @param type $cantidad - CANTIDAD DEL PRODUCTO A SER INGRESADA
 * @param type $valUnit - VALOR UNITARIO DEL PRODUCTO
 * @param type $total - TOTAL MONETARIO DEL PRODUCTO, PRECIO x CANTIDAD
 * @param type $producto - ID DEL PRODUCTO
 * @param type $stock - STOCK DISPONIBLE DEL PRODUCTO
 * @param type $estado - ESTADO DE LA TRANSACCIÓN
 * @param type $origen - ID DEL ORIGEN
 * @param type $destino - ID DEL DESTINO
 * @param type $comprobante - ID DEL COMPROBANTE DE LA TRANSACCIÓN
 * @param type $compra_venta - INICIAL DE LA TRANSACCION EJ: I, E, FV, A, CP, etc.
 * @param type $bodega - ID DE LA BODEGA
 * @param type $comentario - COMENTARIO
 */
function insertKardex($fecha, $detalle, $cantidad, $valUnit, $total, $producto, $stock, $estado, $origen, $destino, $cliente, $comprobante, $compraVenta, $bodega, $comentario)
{
    $sql = "INSERT INTO kardex(id_kardex, fecha_kardex, detalle, cantidad, valor_unitario, total, cod_productos, saldo, estado, origen, destino,id_cliente, comprobante, 
        compra_venta, id_empresa, comentario)
	VALUES (" . obtenerIdMaxKardex() . ", " . ($fecha == NULL ? "NULL" : "'$fecha'") . ", '$detalle', " . ($cantidad == NULL ? "0.00" : $cantidad) . ", "
        . " " . ($valUnit == NULL ? "0.0000" : $valUnit) . ", " . ($total == NULL ? "0.0000" : $total) . ", $producto, "
        . "" . ($stock == NULL ? "0.00" : $stock) . ", '$estado', " . ($origen == NULL ? "NULL" : $origen) . ", " . ($destino == NULL ? "NULL" : $destino) . ","
        . "" . ($cliente == NULL ? "NULL" : $cliente) . ",'$comprobante', '$compraVenta', $bodega, '$comentario')";
    pg_query($sql);
    // Auditoria
    insert_registro('CREACION KARDEX: ' . $detalle . ', DEL PRODUCTO CON ID: ' . $producto);
}

/**
 * FUNCION PARA OBTENER EL ULTIMO REGISTRO AUMENTADO 1
 * 
 * @return type - NÚMERO DE REGISTRO
 */
function obtenerIdMaxKardex()
{
    $sql = "SELECT MAX(id_kardex) FROM kardex";
    $id = (pg_fetch_array(pg_query($sql))[0] + 1);
    return $id;
}

/**
 * FUNCÓN PARA PROCESAR KARDEX DE ENTRADA
 * 
 * @param type $producto - ID PRODUCTO
 * @param type $detalle - DETALLE DE LA TRANSACCION EJ: I: ING-000001, EGRE-00001, ETC.
 * @param type $cantidad - CANTIDAD DEL PRODUCTO A SER INGRESADA
 * @param type $stock - STOCK DISPONIBLE DEL PRODUCTO
 * @param type $costUnit - VALOR UNITARIO DEL PRODUCTO SI ES NULL SE ASIGNA VALOR PROMEDIADO, DE LOCONTRARIO VA EL VALOR QUE SE SEFINE
 * @param type $estadoKV - ESTADO PARA KARDEX VALORIZADO
 * @param type $estadoK - ESTADO PARA KARDEX
 * @param type $bodega - ID DE LA BODEGA
 * @param type $compraVenta - DESCRIOCION: INV, INVS, I, E, A, AI, CP, P, ETC
 * @param type $comprobante - ID DEL COMPROBANTE DE LA TRANSACCIÓN
 * @param type $total - TOTAL MONETARIO DEL PRODUCTO, PRECIO x CANTIDAD
 * @param type $origen - ID DEL ORIGEN
 * @param type $destino - ID DEL DESTINO
 * @param type $comentario - COMENTARIO
 */
function procesarKardexEntrada($producto, $detalle, $cantidad, $stock, $costUnit, $estadoKV, $bodega, $compraVenta, $comprobante, $total, $origen, $destino, $comentario, $debe, $haber, $cliente, $usuario, $calcularcp = true, $fecha='')
{
    if(empty($fecha)){
        $fecha=obtenerFechaActual();
    }
    if (empty($costUnit)) {
        $costUnit = obtenerCostoPromedioUnitario($producto, $bodega)[0]['costo_prom_unitario'];
    }
    procesarKardexValorizadoEntrada($producto, $fecha, $detalle, $cantidad, $stock, $costUnit, $estadoKV, $bodega, $compraVenta, $comprobante, $debe, $haber, $calcularcp);
    $stock = $stock + $cantidad;
    if ($total == NULL) {
        $total = ($cantidad * $costUnit);
    }
    insertKardex($fecha, $detalle, $cantidad, $costUnit, $total, $producto, $stock, $estadoKV, $origen, $destino, $cliente, $comprobante, $compraVenta, $bodega, $comentario);
    updateDetBodega($bodega, $producto, $stock, $usuario);
}

function procesarKardexEntradaActualizar($producto, $detalle, $cantidad, $stock, $costUnit, $estadoKV, $bodega, $compraVenta, $comprobante, $total, $origen, $destino, $comentario, $debe, $haber, $cliente, $usuario,$fecha='')
{
    if(empty($fecha)){
        $fecha=obtenerFechaActual();
    }

    $costUnitK = obtenerCostoPromedioUnitario($producto, $bodega)[0]['costo_prom_unitario'];
    if (!empty(floatval($costUnitK))) {
        $costUnit = $costUnitK;
    }

    procesarKardexValorizadoEntradaActualizar($producto, $fecha, $detalle, $cantidad, $stock, $costUnit, $estadoKV, $bodega, $compraVenta, $comprobante, $debe, $haber);
    $stock = $cantidad;
    if ($total == NULL) {
        $total = ($salida * $costUnit);
    }
    insertKardex($fecha, $detalle, $cantidad, $costUnit, $total, $producto, $stock, $estadoKV, $origen, $destino, $cliente, $comprobante, $compraVenta, $bodega, $comentario);
    updateDetBodega($bodega, $producto, $stock, $usuario);
}

function procesarKardexSalida($producto, $detalle, $salida, $stock, $costUnit, $estado, $bodega, $compraVenta, $comprobante, $total, $origen, $destino, $cliente, $comentario, $debe, $haber, $usuario)
{
    if (empty($costUnit)) {
        $costUnit = obtenerCostoPromedioUnitario($producto, $bodega)[0]['costo_prom_unitario'];
    }
    procesarKardexValorizadoSalida($producto, obtenerFechaActual(), $detalle, $salida, $costUnit, $stock, $estado, $bodega, $compraVenta, $comprobante, $debe, $haber);
    //    print_r("salida_o_cantidad".$salida);
    if ($stock > $salida) {
        $stock = $stock - $salida;

        $stock = str_replace('-', '', $stock);
    } else {
        $stock = $salida - $stock;
        $stock = str_replace('-', '', $stock);
    }
    if ($total == NULL) {
        $total = ($salida * $costUnit);
    }
    insertKardex(obtenerFechaActual(), $detalle, $salida, $costUnit, $total, $producto, $stock, $estado, $origen, $destino, $cliente, $comprobante, $compraVenta, $bodega, $comentario);
    updateDetBodega($bodega, $producto, $stock, $usuario);
}

/**
 * ACTUALIZAR DETALLE BODEGA
 * 
 * @param type $bodega - ID DE LA BODEGA EJ: 1,2,3
 * @param type $producto - ID DEL PRODUCTO A SER MODIFICADO
 * @param type $saldoReal - CANTIDAD A SER MODIFICADA
 */
function updateDetBodega($bodega, $producto, $saldoReal, $usuario)
{
    $sql = "SELECT * FROM detalle_producto_bodega WHERE id_bodega=$bodega AND cod_productos=$producto";
    $resultDPB = pg_query($sql);
    if (pg_num_rows($resultDPB) > 0) {
        while ($rowDPB = pg_fetch_assoc($resultDPB)) {
            //$actualizarStock = ($rowDPB['stock'] - $cantidad);
            $updateDetProdBod = "UPDATE detalle_producto_bodega SET stock= " . number_format($saldoReal, 2, '.', '') . " ,fecha='" . obtenerFechaActual() . "', hora='" . obtenerHoraActual() . "' "
                . "WHERE id_detalle_productos_bodega=$rowDPB[id_detalle_productos_bodega]";

            pg_query($updateDetProdBod);
            // Auditoria
            insert_registro('MODIFICACION DETALLE PRODUCTO BODEGA CON ID: ' . $bodega . ' PRODUCTO: ' . $producto);
        }
    } else {
        guardarDetalleProductoBodega($producto, $bodega, $usuario, $saldoReal);
    }
}

/**
 * FUNCION PARA ACTUALIZAR EL PRECIO DE COMPRA PROMEDIADO
 * @param type $producto
 * @param type $bodega
 * @param type $costo
 */
function actualizarPrecioPromedio($producto, $bodega, $costo)
{
    $sql = "UPDATE productos SET costo_promedio= $costo WHERE cod_productos=$producto AND id_bodega=1 ";
    pg_query($sql);
}

function actualizarVentaPromedio($producto, $bodega, $costo)
{
    $sql = "UPDATE productos SET venta_promedio= $costo WHERE cod_productos=$producto AND id_bodega=1 ";
    pg_query($sql);
}

function obtenerValoresPromedios($producto)
{
    $sql = "SELECT P.costo_promedio, P.venta_promedio FROM productos P WHERE P.cod_productos=$producto";
    return pg_fetch_all(pg_query($sql));
}

function obtenerCostoPromevioVendido($comprobante, $compraVenta, $bodega)
{
    $sql = "SELECT kv.costo_unitario FROM kardex_valorizado kv "
        . "WHERE kv.compra_venta='$compraVenta' AND kv.comprobante='$comprobante' AND kv.id_empresa=$bodega";
    return pg_fetch_row(pg_query($sql))[0];
}

function obtenerCostoPromedioUnitario($producto, $bodega)
{
    $sql = "SELECT * FROM kardex_valorizado kv "
        . "WHERE kv.cod_productos=$producto AND kv.id_empresa=$bodega "
        . "ORDER BY kv.id_kardex DESC LIMIT 1";
    return pg_fetch_all(pg_query($sql));
}

/**
 * @param type $producto
 * @param type $bodega
 * @param type $comprobante
 * @param type $compraVenta - EJ: A,AE,AI,ETC
 */
function obtenerCostoPromedioUnitarioAnular($producto, $bodega, $comprobante, $compraVenta)
{
    $sql = "SELECT kv.costo_prom_unitario FROM kardex_valorizado kv "
        . "WHERE kv.cod_productos=$producto AND kv.id_empresa=$bodega AND kv.comprobante='$comprobante' "
        . "AND kv.compra_venta='$compraVenta' ORDER BY kv.id_kardex DESC LIMIT 1";
    return pg_fetch_row(pg_query($sql))[0];
}
