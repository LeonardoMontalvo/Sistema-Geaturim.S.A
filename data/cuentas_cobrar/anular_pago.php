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
    $anular = anularPagoC($idpagoc);
    $update = upadatePagoV($idpagov, $valorp);
    pg_query($conexion, "COMMIT");
    $anulado = !empty($anular) && !empty($update);
    return $anulado ? 1 : 0;
}

function anularPagoC($idpago)
{
    global $conexion;
    $sql = "update pagos_cobrar set estado='Pasivo' where id_pagos_cobrar=$idpago";
    $res = pg_query($conexion, $sql);
    return $res;
}

function upadatePagoV($idpago, $valorp)
{
    global $conexion;
    $sql = "update pagos_venta set saldo=saldo+$valorp where id_pagos_venta=$idpago";
    $res = pg_query($conexion, $sql);
    return $res;
}
