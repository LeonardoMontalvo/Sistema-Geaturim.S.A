<?php

session_start();
include '../../procesos/base.php';

conectarse();
error_reporting(0);
date_default_timezone_set('America/Guayaquil');
$fecha = date("Y-m-d");
$hora = date("H:i:s");
$idusuario = $_SESSION["id"];
$idpv = $_SESSION["PV"];

echo json_encode(guardarApertura($_POST["monto"], $_POST["observacion"]));

function getStock()
{
    global $idpv;
    $sql = "select*from detalle_producto_bodega
    where id_bodega=$idpv;";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}
function getIdCierre()
{
    $cont1 = 0;
    $consulta = pg_query("select max(id_cierre_caja) from cierre_caja");
    while ($row = pg_fetch_row($consulta)) {
        $cont1 = $row[0];
    }
    $cont1++;
    return $cont1;
}

function guardarApertura($monto, $observacion)
{
    global $fecha, $hora, $idusuario, $idpv;
    $id = getIdCierre();
    $stockc = json_encode(getStock());
    $sql = "
    INSERT INTO cierre_caja(
        id_cierre_caja, fecha_actual, hora_actual, comprobante, id_usuario, 
        id_empresa, observacion, estado, monto_apertura, captura_stock)
        VALUES (
        $id, '$fecha', '$hora', $id, $idusuario, 
        $idpv, '$observacion', 'Activo', $monto, '$stockc');
    ";
    $res = pg_query($sql);
    if (empty($res)) {
        return 0;
    }
    return $id;
}
