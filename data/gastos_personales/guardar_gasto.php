<?php
session_start();
date_default_timezone_set("America/Guayaquil");
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$pv = $_SESSION['PV'];
$idusuario = $_SESSION["id"];
$fecha = date("Y-m-d");
$hora = date("H:i:s");

function obtenerIdGasto()
{
    $sql = "select max(id_gastos_personales) from gastos_personales";
    $res = pg_query($sql);
    if (pg_num_rows($res) > 0) {
        return pg_fetch_row($res)[0] + 1;
    }
    return 0;
}
function  guardarGasto(
    $idusuario,
    $idproveedor,
    $idcomprador,
    $rscomprador,
    $numfact,
    $numaut,
    $fechaem,
    $descuento,
    $subtotal,
    $tarifa0,
    $tarifa12,
    $iva,
    $total,
    $tipoc
) {
    global $fecha, $hora;
    $id = obtenerIdGasto();
    $sql = "
    INSERT INTO gastos_personales(
        id_gastos_personales, id_usuario, id_proveedor, identificacion_comprador, 
        razon_social_comprador, num_factura, num_autorizacion, fecha_emision, 
        descuento, subtotal, tarifa0, tarifa12, iva, total, comprobante, 
        tipo_comprobante, fecha_actual, hora_actual, estado)
        VALUES ($id, $idusuario, $idproveedor, '$idcomprador', 
        '$rscomprador', '$numfact', '$numaut', '$fechaem', 
        $descuento, $subtotal, $tarifa0, $tarifa12, $iva, $total, $id, 
        '$tipoc', '$fecha', '$hora', 'Activo');

    ";
}
