<?php
session_start();
include_once __DIR__ . '/../../../procesos/base.php';
require_once __DIR__ . '/../../../procesos/fecha.php';
require_once __DIR__ . '/../../../procesos/kardexValorizado.php';
require_once __DIR__ . '/../../../procesos/detalleProductosBodega.php';
//error_reporting(0);
date_default_timezone_set('America/Guayaquil');

$conexion = conectarse();
$conpuntoresult = $_SESSION['PV'];

echo transaccionAnular($_POST["id"], $_SESSION["id"]);

function transaccionAnular($idtransferencia, $usuario)
{
    global $conexion;
    $trans = obtenerTransferencia($idtransferencia);

    if (!empty($trans["id_usuario_destino"])) {
        return "La transferencia ya fue procesada.";
    }

    pg_query($conexion, "BEGIN");
    guardarEntradaKardex($idtransferencia, $usuario);
    $atrans = anularTransferencia($idtransferencia);
    $aegreso = anularEgreso($trans["id_egreso"]);
    if (empty($atrans)) {
        pg_query($conexion, "ROLLBACK");
        return "Error al anular transferencia.";
    }
    if (empty($aegreso)) {
        pg_query($conexion, "ROLLBACK");
        return "Error al anular egreso.";
    }
    pg_query($conexion, "COMMIT");
    return $atrans;
}

function anularTransferencia($idtransferencia)
{
    global $conexion;
    $sql = "
    UPDATE transferencias_bodega
    SET estado='Pasivo'
    WHERE id_transferencia_bodega=$idtransferencia;
    ";
    $res = pg_query($conexion, $sql);
    if (!!$res) {
        return $idtransferencia;
    }
    return null;
}

function guardarEntradaKardex($idtransferencia, $usuario)
{
    global $conpuntoresult;
    $tras = obtenerTransferencia($idtransferencia);
    $dets = obtenerDetallesEgreso($tras["id_egreso"]);
    foreach ($dets as $value) {
        $costoPromedio = obtenerCostoPromedioUnitario($value["cod_productos"], $tras['id_bodega_origen'])[0]['costo_prom_unitario'];
        procesarKardexEntrada($value["cod_productos"], "T.E: T. Anulada - " . $tras['comprobante'], $value["cantidad"], obtenerStock($value["cod_productos"], $tras["id_bodega_origen"]), $costoPromedio, 'Activo',  $conpuntoresult, 'TEI', $idtransferencia, NULL, $tras['id_bodega_origen'], $tras['id_bodega_destino'], '', NULL, NULL, NULL, $usuario);
    }
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

////////////////////////////////////////
function obtenerDetallesEgreso($idegreso)
{
    global $conexion;
    $sql = "
    select * from detalle_egreso  
    where id_egresos=$idegreso
    ";
    $res = pg_query($conexion, $sql);
    $rows = pg_fetch_all($res);
    if (!$res) {
        return [];
    }
    return $rows;
}

function anularEgreso($idegreso)
{
    global $conexion;
    $sql = "
    update egresos
    set estado='Pasivo' 
    where id_egresos=$idegreso
    ";
    $res = pg_query($conexion, $sql);
    if (!$res) {
        return null;
    }
    return $idegreso;
}
