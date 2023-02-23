<?php
session_start();
include '../../procesos/base.php';
$conexion = conectarse();
error_reporting(0);

echo json_encode(transaccionAnularPago());

function transaccionAnularPago()
{
    global $conexion;
    $idpagov = $_POST["id_cxc"];
    $idpagoc = $_POST["id_pago"];
    $valorp = $_POST["valor_p"];

    pg_query($conexion, "BEGIN");
    $anularPago = anularPagoC($idpagoc);
    $anularAsiento = anularAsiento($idpagoc);
    $update = upadateSaldoCxc($idpagov, $valorp);
    pg_query($conexion, "COMMIT");
    $anulado = !empty($anularPago) && !empty($update) && !empty($anularAsiento);
    return $anulado ? 1 : 0;
}

function anularPagoC($idpago)
{
    global $conexion;
    $sql = "update pagos_cobrar set estado='Pasivo' where id_pagos_cobrar=$idpago";
    $res = pg_query($conexion, $sql);
    return $res;
}

function upadateSaldoCxc($idpago, $valorp)
{
    global $conexion;
    $tipop = $_POST["tipo_p"];
    if ($tipop == 'INTERNA') {
        $sql = "update pagos_venta set saldo=saldo+$valorp, estado='Activo' where id_pagos_venta=$idpago";
    } else if ($tipop == 'EXTERNA') {
        $sql = "update c_cobrarexternas set saldo=saldo+$valorp, estado='Activo' where id_c_cobrarexternas=$idpago";
    }

    $res = pg_query($conexion, $sql);
    return $res;
}

function anularAsiento($idpago)
{
    global $conexion;
    $sql = "update transacciones
    set estado='Pasivo'
    where concepto like 'CUENTA POR COBRAR%'
    and comprobante='$idpago';
    
    update detalle_transaccion
    set estado='Pasivo'
    where id_transacciones in(
        select id_transacciones from transacciones 
        where concepto ilike 'CUENTA POR COBRAR%'
        and comprobante='$idpago'
    );
    ";
    $res = pg_query($conexion, $sql);
    return $res;
}
