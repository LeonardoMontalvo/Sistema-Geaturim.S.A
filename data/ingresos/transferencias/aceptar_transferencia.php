<?php
session_start();
include_once __DIR__ . '/../../../procesos/base.php';
require_once __DIR__ . '/../../../procesos/fecha.php';
require_once __DIR__ . '/../../../procesos/kardexValorizado.php';
require_once __DIR__ . '/../../../procesos/detalleProductosBodega.php';
require_once __DIR__ . '/guardar_ingreso.php';
//error_reporting(0);
date_default_timezone_set('America/Guayaquil');

$conexion = conectarse();
$conpuntoresult = $_SESSION['PV'];

//pg_query("BEGIN");
echo aceptarTransferencia($_POST["id"], $_SESSION["id"]);
//pg_query("COMMIT");
function obtenerIdIngreso1()
{
    $consid = pg_query("select max(id_ingresos) from ingresos");
    $id = pg_fetch_row($consid)[0];
    return $id;
}
function  aceptarTransferencia($idtransferencia, $usuario)
{
if (!validarTransferencia($idtransferencia)) {
        exit("La transferencia ya fue aceptada.");
    }
    global $conpuntoresult;
    $trans = obtenerTransferencia($idtransferencia);

    if ($trans["estado"] == 'Pasivo') {
        return "La transferencia fue anulada.";
    }

    $egreso = obtenerEgreso($trans["id_egreso"]);
    $detallest = obtenerDetallesEgreso($trans["id_egreso"]);

    $guardado = procesosGuardarIngreso(
        $conpuntoresult,
        $usuario,
        $trans["id_bodega_origen"],
        $trans["id_bodega_destino"],
        $egreso["tarifa0"],
        $egreso["tarifa12"],
        $egreso["iva_egreso"],
        $egreso["descuento_egreso"],
        $egreso["total_egreso"],
        $egreso["observaciones"],
        $detallest
    );
    if (empty($guardado)) {
        return "Error al guardar Ingreso.";
    }
    $cabmiare = cambiarEstadoTransferncia($idtransferencia, $guardado, $usuario);
    if (empty($cabmiare)) {
        return "Error al cambiar estado de transferencia.";
    }



    return $idtransferencia;
}

function obtenerTransferencia($idtransferencia)
{
    global $conexion;
    $sql = "select*from transferencias_bodega where id_transferencia_bodega=$idtransferencia";
    $res = pg_query($conexion, $sql);
    $rows = pg_fetch_all($res);
    if (!$rows) {
        return [];
    }
    return $rows[0];
}

function cambiarEstadoTransferncia($idtransferencia, $idingreso, $usuario)
{
    global $conexion;
    $sql = "
    update transferencias_bodega 
    set estado_transferencia='aceptado',
    fecha_modificacion ='" . date("Y-m-d") . "',
    id_usuario_destino =$usuario,
    id_ingreso=$idingreso
    where  id_transferencia_bodega=$idtransferencia
    ";
    $res = pg_query($conexion, $sql);
    if (!$res) {
        return null;
    }
    return $idtransferencia;
}

function obtenerDetallesEgreso($idegreso)
{
    global $conexion;
    $sql = "
    select 
    de.*,
    kv.costo_prom_unitario
    from detalle_egreso de  
    inner join kardex_valorizado kv
    on kv.comprobante::integer=de.id_egresos
    and kv.compra_venta='E'
    and kv.cod_productos=de.cod_productos
    where id_egresos=$idegreso
    ";
    $res = pg_query($conexion, $sql);
    $rows = pg_fetch_all($res);
    if (!$res) {
        return [];
    }
    return $rows;
}

function obtenerEgreso($idegreso)
{
    global $conexion;
    $sql = "
    select * from egresos where
    id_egresos=$idegreso;
    ";
    $res = pg_query($conexion, $sql);
    $rows = pg_fetch_all($res);
    if (!$rows) {
        return [];
    }
    return $rows[0];
}

function validarTransferencia($idtransferencia)
{
    global $conexion;
    $sql = "select*from transferencias_bodega where id_transferencia_bodega=$idtransferencia";
    $res = pg_query($conexion, $sql);
    $row = pg_fetch_assoc($res);
    if (!empty($row)) {
        if ($row["estado_transferencia"] == 'aceptado') {
            return false;
        }
        return true;
    }
    return true;
}
