<?php

/**
 * OBTIENE ID DE BODEGA VENTA
 * @return type - ID BODEGA
 */
/* * **************************************************************************************************
 * ARREGLAR KARDEX A PARTIR DE INVENTARIO
 */

/**
 * FUNCION PARA REALIZAR LA BUSQUEDA DEL KARDEX POR COMPROBANTE A SER PUESTO EN PASIVO 
 * 
 * @param type $comprobante - NÚMERO DE COMPROBANTE
 * @param type $bodega - ID DE BODEGA EJ: 1,2,3
 * @return type
 */
function buscarKardex($comprobante, $bodega, $op) {
    $sql = "SELECT * FROM kardex WHERE comprobante = '$comprobante' AND compra_venta='$op' AND estado!='' ";
           
    $result = pg_query($sql);
    if (pg_num_rows($result) > 0) {
        while ($row = pg_fetch_assoc($result)) {
            updateKardexEstado($row['id_kardex']);
            ajustarKardexInicio($bodega, $row['cod_productos']);
        }
        return json_encode(array("data" => 1, "error" => 'Datos procesados correspondiente al comprobante: ' . $comprobante));
    } else {
        return json_encode(array("data" => 0, "error" => 'No existe registros correspondiente al comprobante: ' . $comprobante));
    }
}

/**
 * FUNCION PARA BUSCAR TRANSACCIONES DE KARDEX TOMANDO EN REFERENCIA MAYOR AL ID DE KARDEX QUE SE PASA POR PARAMETRO
 * 
 * @param type $idKardex - ID KARDEX A SER COMPARADO >
 * @param type $producto - ID DEL PRODUCTO
 * @param type $bodega - ID BODEGA EJ: 1,2,3
 * @param type $saldoReal - VALOR DEL SALDO INICIAL REAL
 */
function buscarTrasaccionesKardex($idKardex, $producto, $bodega, $saldoReal) {
    $sql = "SELECT * FROM kardex WHERE estado!='' AND cod_productos=$producto "
            . "AND id_kardex>1 ORDER BY id_kardex";
    $result = pg_query($sql);
    while ($row = pg_fetch_assoc($result)) {
        $saldoReal = calculosKardex($row['compra_venta'], $row['cantidad'], $saldoReal);
        updateKardexSaldos($row['id_kardex'], $saldoReal);
    }
    updateDetBodega($bodega, $producto, $saldoReal);
}

/**
 * FUNCION PARA ACTUALIZAR SALDO DE KARDEX RESPECTO AL ID
 * 
 * @param type $idKardex - ID KARDEX A SER ACTUALIZADO
 * @param type $saldo - SALDO A SER ACTUALIZADO
 */
function updateKardexSaldos($idKardex, $saldo) {
    $update = "UPDATE kardex SET saldo=$saldo WHERE id_kardex=$idKardex";
    pg_query($update);
}

/**
 * FUNCION PARA ACTUALIZAR ESTADO DE KARDEX RESPECTO AL ID
 * 
 * @param type $idKardex - ID KARDEX A SER ACTUALIZADO
 */
function updateKardexEstado($idKardex) {
    $update = "UPDATE kardex SET estado='' WHERE id_kardex=$idKardex";
    pg_query($update);
}

/**
 * FUNCION PARA BUSCAR INVENTARIO
 * 
 * @param type $comprobante - NÚMERO DE COMPROBANTE
 * @param type $producto - ID PRODUCTO
 * @param type $bodega - ID BODEGA EJ: 1,2,3
 */
function buscarInventarios($comprobante, $producto, $bodega) {
    $sql = "SELECT * FROM inventario INV INNER JOIN detalle_inventario DINV ON DINV.id_inventario=INV.id_inventario "
            . "WHERE comprobante='$comprobante' AND DINV.cod_productos=$producto AND id_empresa=$bodega";
    $result = pg_query($sql);
    if (pg_num_rows($result) > 0) {
        while ($row = pg_fetch_assoc($result)) {
            updateInventario($row['id_inventario']);
            updateDetalleInventario($row['id_detalle_inventario']);
        }
    }
}

/**
 * FUNCION PARA ACTUALIZAR INVENTARIO
 * 
 * @param type $idInventario - ID INVENTARIO A SER ACTUALIZADO
 */
function updateInventario($idInventario) {
    $update = "UPDATE inventario SET estado='Pasivo' WHERE id_inventario=$idInventario";
    pg_query($update);
}

/**
 * FUNCION PARA ACTUALIZAR DETALLE INVENTARIO
 * 
 * @param type $idDetalle - ID DETALLE INVENTARIO A SER ACTUALIZADO
 */
function updateDetalleInventario($idDetalle) {
    $update = "UPDATE detalle_inventario SET estado='Pasivo' WHERE id_detalle_inventario=$idDetalle";
    pg_query($update);
}

/**
 * ACTUALIZAR DETALLE BODEGA
 * 
 * @param type $bodega - ID DE LA BODEGA EJ: 1,2,3
 * @param type $producto - ID DEL PRODUCTO A SER MODIFICADO
 * @param type $saldoReal - CANTIDAD A SER MODIFICADA
 */
function updateDetBodega($bodega, $producto, $saldoReal) {
    $sql = "SELECT * FROM productos WHERE   cod_productos=$producto";
    $resultDPB = pg_query($sql);
    if (pg_num_rows($resultDPB) > 0) {
        while ($rowDPB = pg_fetch_assoc($resultDPB)) {
            //$actualizarStock = ($rowDPB['stock'] - $cantidad);
            $updateDetProdBod = "UPDATE productos SET stock= $saldoReal "
                    . "WHERE cod_productos=$rowDPB[cod_productos]";
            pg_query($updateDetProdBod);
        }
    }
}

/**
 * FUNCION PARA REALIZAR AJUSTE DE KARDEX DESDE EL INICIO, RESPECTO A PRODUCTO Y BODEGA
 * 
 * @param type $bodega - ID BODEGA EJ:1,2,3
 * @param type $producto - ID PRODUCTO
 */
function ajustarKardexInicio($bodega, $producto) {
    $sql = "SELECT * FROM kardex WHERE id_empresa=$bodega AND cod_productos=$producto AND estado!='' ORDER BY id_kardex asc limit 1";
    $result = pg_query($sql);
    if (pg_num_rows($result) > 0) {
        while ($row = pg_fetch_assoc($result)) {
            buscarTrasaccionesKardex($row['id_kardex'], $row['cod_productos'], $bodega, $row['saldo']);
        }
    }
}

//////////////////////// FUNCIONES PARA ELIMINAR Y SUMAR POR 1 PRODUCTO SELECCIONADO ////////////////////////

/**
 * FUNCION PARA OBTENER EL LISTADO POR PRODUCTO Y BODEGA
 * 
 * @param type $bodega - ID BODEGA EJ:1,2,3
 * @param type $producto - ID PRODUCTO
 * @return type - LIST DE REGISTROS DEL KARDEX
 */
function buscarKardexPorProducto($bodega, $producto) {
    //$sql = "SELECT * FROM kardex WHERE id_empresa=$bodega AND cod_productos=$producto AND estado!='' ORDER BY id_kardex asc limit 1";
    $sql = "SELECT * FROM kardex WHERE cod_productos=$producto  AND estado!='' ORDER BY id_kardex";
    $result = pg_query($sql);
    if (pg_num_rows($result) > 0) {
        while ($row = pg_fetch_all($result)) {
            return $row;
        }
    }
}

/**
 * FUNCION PARA BUSCAR KARDEX POR PRODUCTOA ELIMINAR REGISTRO
 * 
 * @param type $bodega - ID BODEGA EJ: 1,2,3
 * @param type $obj - OBJETO CONTIENE TODA EL REGISTRO SELECCIONADO
 * @return type - RETORNA DATA 0 o 1 Y MENSAJE
 */
function buscarKardexPorProductoEliminar($bodega, $obj) {
    if (sizeof($obj) > 0) {
        for ($i = 0; $i < sizeof($obj); $i++) {
            updateKardexEstado($obj[$i]['id_kardex']);
            ajustarKardexInicio($bodega, $obj[$i]['cod_productos']);
        }
        return json_encode(array("data" => 1, "error" => 'Datos procesados correspondiente '));
    } else {
        return json_encode(array("data" => 0, "error" => 'No existe registros seleccionado para eliminar'));
    }
}

/**
 * FUNCION PARA SUMAR VALORES DEL PRODUCTO
 * 
 * @param type $bodega - ID BODEGA EJ: 1,2,3
 * @param type $obj - ARARY DE 2 PRODUCTOS
 * @return type - RETORNA 1 O 0 Y MENSAJE 
 */
function buscarKardexPorProductoSumar($bodega, $obj) {
    if (sizeof($obj) == 2) {
        $saldo = 0;
        for ($i = 0; $i < sizeof($obj); $i++) {
            if ($i == 1) {
                $saldo += $obj[$i]['cantidad'];
                updateKardexSaldos($obj[$i]['id_kardex'], $saldo);
                buscarTrasaccionesKardex($obj[$i]['id_kardex'], $obj[$i]['cod_productos'], $bodega, $saldo);
            } else {
                $saldo = $obj[$i]['saldo'];
            }
        }
        return json_encode(array("data" => 1, "error" => 'Datos procesados correspondiente '));
    } else {
        return json_encode(array("data" => 0, "error" => 'No existe registros seleccionado para sumar'));
    }
}

/**
 * FUNCION PARA BUSCAR KARDEX POR PRODUCTO PARA AJUSTE
 * 
 * @param type $bodega - ID DE BODEGA EJ: 1,2,3
 * @param type $obj - ARRAY DE PRODUCTO
 * @return type - RETORNA 1 O 0 Y MENSAJE
 */
function buscarKardexPorProductoAjuste($bodega, $obj) {
    if (sizeof($obj) > 0) {
        for ($i = 0; $i < sizeof($obj); $i++) {
            updateKardexSaldos($obj[$i]['id_kardex'], $obj[$i]['cantidad']);
            ajustarKardexInicio($bodega, $obj[$i]['cod_productos']);
        }
        return json_encode(array("data" => 1, "error" => 'Datos procesados correspondiente '));
    } else {
        return json_encode(array("data" => 0, "error" => 'No existe registros seleccionado para realizar el ajuste'));
    }
}

/**
 * FUNCION PARA OBTENER DATOS DE UN ARRAY MULTIPLE Y OBTENER DATO EXISTENTE
 * 
 * @param type $param - VALOR A SER COMPARADO
 * @param type $array - ARRAY DE PRODUCTOS
 * @param type $field - NOMBRE DEL CAMPO
 * @return boolean - RETORNA TRUE O FALSE
 */
function in_arra_field($param, $array, $field) {
    foreach ($array as $key) {
        foreach ($key as $item => $val) {
            if ($item == $field && $val == $param) {
                return TRUE;
            }
        }
    }
    return FALSE;
}

function arrayRecursivo($array) {
    foreach ($array as $key => $data) {
        if (is_array($data)) {
            arrayRecursivo($data);
        } elseif (is_object($data)) {
            arrayRecursivo($data);
        } else {
            if ($item == $field && $val == $param) {
                return TRUE;
            }
        }
    }
    return FALSE;
}

////////////////////////////// PROCESAR TODO EL KARDEX //////////////////////////////

/**
 * FUNCION PARA PROCESAR TODO EL KARDEX
 * 
 * @param type $bodega - ID DE LA BODEGA EJ: 1,2,3
 * @param type $fIni - FECHA DE INICIO
 * @param type $fFin - FECHA FIN
 * @return type RETORNA JASON ARRAY
 */
function procesarTodoKardex($bodega, $fIni, $fFin) {
    $registrosArray = array();
    $sql = "SELECT * FROM kardex WHERE id_empresa=$bodega AND estado!='' AND fecha_kardex BETWEEN '$fIni' AND '$fFin' ORDER BY id_kardex ";
    $result = pg_query($sql);
    if (pg_num_rows($result) > 0) {
        while ($row = pg_fetch_assoc($result)) {
            if (empty($registrosArray)) {
                array_push($registrosArray, $row);
            } else {
                if (!in_arra_field($row['cod_productos'], $registrosArray, "cod_productos")) {
                    array_push($registrosArray, $row);
                }
            }
        }
        for ($i = 0; $i < sizeof($registrosArray); $i++) {
            $saldoAnterior = buscarRegistroAnterior($bodega, $registrosArray[$i]['cod_productos'], $registrosArray[$i]['id_kardex']);
            if ($saldoAnterior != NULL) {
                $saldoAnterior += $registrosArray[$i]['cantidad'];
            } else {
                $saldoAnterior = $registrosArray[$i]['cantidad'];
            }
            updateKardexSaldos($registrosArray[$i]['id_kardex'], $saldoAnterior);
            ajustarKardexInicio($bodega, $registrosArray[$i]['cod_productos']);
        }
        return json_encode(array("data" => 1, "error" => 'Datos procesados correspondiente '));
    } else {
        return json_encode(array("data" => 0, "error" => 'No se realizo el ajuste'));
    }
}

/**
 * FUNCION PARA SABER EL REGISTRO ANTERIOR
 * 
 * @param type $bodega - ID DE BODEGA EJ: 1,2,3
 * @param type $producto - ID DEL PRODUCTO
 * @param type $idKardex - ID DE KARDEX
 * @return type RETORNA NULL O SALDO CORRESPONDIENTE
 */
function buscarRegistroAnterior($bodega, $producto, $idKardex) {
    //AND compra_venta ='INV'
    $sql = "SELECT * FROM kardex WHERE  estado!='' AND cod_productos=$producto AND id_kardex<$idKardex ORDER BY id_kardex DESC LIMIT 1";
    $result = pg_query($sql);
    if (pg_num_rows($result) > 0) {
        $row = pg_fetch_all($result);
        return $row[0]['saldo'];
    } else {
        return null;
    }
}

/**
 * FUNCION PARA PROCESAR TODO EL KARDEX POR PRODUCTO ESPECIFICO
 * 
 * @param type $bodega - ID DE BODEGA EJ: 1,2,3
 * @param type $producto - ID PRODUCTO
 * @param type $fIni - FECHA INICIO
 * @param type $fFin - FECHA FIN
 * @return boolean - RETORNA TRUE OR FALSE
 */
function procesarTodoKardexProducto($bodega, $producto, $fIni, $fFin) {
    $registrosArray = array();
    $sql = "SELECT * FROM kardex WHERE  estado!='' AND cod_productos=$producto AND fecha_kardex BETWEEN '$fIni' AND '$fFin' ORDER BY id_kardex limit 1 ";
    $result = pg_query($sql);
    if (pg_num_rows($result) > 0) {
        while ($row = pg_fetch_assoc($result)) {
            $saldoAnterior = buscarRegistroAnterior($bodega, $producto, $row['id_kardex']);
            if ($saldoAnterior != NULL) {
                $saldoAnterior = calculosKardex($row['compra_venta'], $row['cantidad'], $saldoAnterior);
            } else {
                $saldoAnterior = $row['cantidad'];
            }
            updateKardexSaldos($row['id_kardex'], $saldoAnterior);
            buscarTrasaccionesKardex($row['id_kardex'], $producto, $bodega, $saldoAnterior);
        }
        return TRUE;
    }
    return FALSE;
}

/**
 * FUNCION PARA REALIZAR CALCULOS DEL KARDEX
 * 
 * @param type $compra_venta - IDENTIFICADOR A,V,INV,I,E
 * @param type $cantidad - CANTIDAD DEL PRODUCTO
 * @param type $saldo - SALDO DEL PRODUCTO
 * @return type - RETORNA SALDO CALCULADO
 */
function calculosKardex($compra_venta, $cantidad, $saldo) {
    if ($compra_venta == "V" || $compra_venta == "E" || $compra_venta == "AI" || $compra_venta == "AE" ) {
        $saldo = $saldo - $cantidad;
        return $saldo;
    }
    if ($compra_venta == "I" || $compra_venta == "A"  || $compra_venta == "NC"  ||  $compra_venta == "INVS" ||  $compra_venta == "C") {
        $saldo = $saldo + $cantidad;
        return $saldo;
    }
    if ($compra_venta == "INV") {
        $saldo = $cantidad;
        return $saldo;
    } if ($compra_venta == "P") {
        $saldo = $cantidad;
        return $saldo;
    }
    
}
