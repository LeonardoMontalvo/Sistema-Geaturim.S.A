<?php
include_once __DIR__ . '/../../../procesos/base.php';
require_once __DIR__ . '/../../../procesos/fecha.php';
require_once __DIR__ . '/../../../procesos/kardexValorizado.php';
require_once __DIR__ . '/../../../procesos/detalleProductosBodega.php';

date_default_timezone_set('America/Guayaquil');

$conexion = conectarse();


function guardarTransferencia($origen, $destino, $usuario, $idegreso)
{
    global $conexion;
    $fecha = date('Y-m-d H:i:s');
    $id = obtenerIdTransferencia();
    $nro = obtenerNroTransferencia();
    $sql = "INSERT INTO transferencias_bodega(
        id_transferencia_bodega, id_bodega_origen, id_bodega_destino, 
        id_usuario_origen, id_usuario_destino, id_egreso, id_ingreso, 
        comprobante, estado_transferencia, estado, fecha_creacion, fecha_modificacion)
    VALUES (
    $id, 
    $origen, 
    $destino, 
    $usuario, 
    NULL, 
    $idegreso,
    NULL,
    '$nro',
    'pendiente', 
    'Activo', 
    '{$fecha}', 
    NULL);";
    $consulta = pg_query($conexion, $sql);
    if (!!$consulta) {
        return $id;
    }
    return null;
}

function obtenerNroTransferencia()
{
    $cont1 = obtenerIdTransferencia();
    $documento = str_pad($cont1, 9, "0", STR_PAD_LEFT);
    return $documento;
}

function obtenerIdTransferencia()
{
    $consid = pg_query("select max(id_transferencia_bodega) from transferencias_bodega");
    $id = pg_fetch_row($consid)[0] + 1;
    return $id;
}
