<?php

function obtenerIdTransaccion() {
    $consulta = pg_query("select max(id_transacciones) from transacciones");
    $id = (pg_fetch_row($consulta)[0] + 1);
    return $id;
}

function guardarTransaccion($id, $usuario, $comprobante, $fechaActual, $horaActual, $concepto, $totalDebe, $totalHaber, $saldo, $tipoTransaccion, $numTransaccion, $estado, $cliente, $deposito, $observacion, $numCuenta, $banco, $identificadorCliPro, $valorConcepto, $empresa) {
    $sql = "INSERT INTO transacciones (id_transacciones, id_usuario, comprobante, fecha_actual, hora_actual, concepto, total_debe, total_haber, "
            . "saldo, id_tipo_transaccion, num_transaccion, estado, id_cliente, deposito, observacion, num_cuenta, banco, identificador_cli_pro, "
            . "valor_concepto, id_empresa) "
            . "VALUES ($id, $usuario, '$comprobante', '$fechaActual', '$horaActual', '$concepto', " . number_format($totalDebe, 4, '.', '') . ", "
            . "" . number_format($totalHaber, 4, '.', '') . ", " . number_format($saldo, 4, '.', '') . ", $tipoTransaccion, $numTransaccion, '$estado', "
            . "" . ($cliente == NULL ? "NULL" : $cliente) . ", '$deposito', '$observacion', '$numCuenta', '$banco', '$identificadorCliPro', " . number_format($valorConcepto, 4, '.', '') . ", "
            . "$empresa)";
    pg_query($sql);
}

function obtenerIdDetalleTransaccion() {
    $consulta = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
    $id = (pg_fetch_row($consulta)[0] + 1);
    return $id;
}

function guardarDetalleTransaccion($transaccion, $planCuenta, $debito, $credito, $estado, $conciliado) {
    $sql = "INSERT INTO detalle_transaccion(id_detalle_transaccion, id_transacciones, id_plan_cuentas, debito, credito, estado, conciliado) "
            . "VALUES (" . obtenerIdDetalleTransaccion() . ", $transaccion," . ($planCuenta = NULL ? "NULL" : $planCuenta) . " , " . number_format($debito, 4, '.', '') . ","
            . "" . number_format($credito, 4, '.', '') . ", '$estado', '$conciliado')";
    pg_query($sql);
}
