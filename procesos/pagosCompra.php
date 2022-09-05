<?php


function guardarPagosCompra($proveedor, $factura, $usuario, $fechaCredito, $adelanto, $meses, $tipoDoc, $montoCredit, $saldo, $estado, $compra_gasto) {
 
    
    $sql = "INSERT INTO pagos_compra(id_pagos_compra, id_proveedor, id_factura_compra, id_usuario, fecha_credito, adelanto, meses, tipo_documento, monto_credito, saldo, estado,comprao_gasto) "
            . "VALUES (" . obtenerIdPagosCompra() . ", $proveedor, $factura, $usuario, '$fechaCredito', " . number_format($adelanto, 4, '.', '') . ", $meses, '$tipoDoc', " . number_format($montoCredit, 4, '.', '') . ", "
            . "" . number_format($saldo, 4, '.', '') . ", '$estado','$compra_gasto')";
// echo '<br>GUARDAR FACTURA VENTAddddd: <br>' . $sql;
    pg_query($sql);
}

function obtenerIdPagosCompra() {
    $consulta = pg_query("select max(id_pagos_compra) from pagos_compra");
    $id = (pg_fetch_row($consulta)[0] + 1);
    return $id;
}
