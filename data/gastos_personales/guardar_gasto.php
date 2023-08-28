<?php
session_start();
date_default_timezone_set("America/Guayaquil");
include '../../procesos/base.php';
$conexion = conectarse();
error_reporting(0);
$idpv = $_SESSION["PV"];
$idusuario = $_SESSION["id"];
$fecha = date("Y-m-d");
$hora = date("H:i:s");

$cabecera = $_POST["cabecera"];
$detalles = $_POST["detalles"];

if (existeFactura($cabecera["id_proveedor"], $cabecera["num_factura"])) {
    echo json_encode(-1);
    exit();
}

echo json_encode(transaccionGuardarGasto());

function transaccionGuardarGasto()
{
    global $conexion, $cabecera, $detalles,$idusuario,$idpv;
    $ok = 1;
    pg_query($conexion, "BEGIN;");
    $res = guardarCabeceraGastop(
        $idpv,
        $idusuario,
        $cabecera["id_proveedor"],
        $cabecera["identificacion_comprador"],
        $cabecera["razon_social_comprador"],
        $cabecera["num_factura"],
        $cabecera["num_autorizacion"],
        $cabecera["fecha_emision"],
        $cabecera["descuento"],
        $cabecera["subtotal"],
        $cabecera["tarifa0"],
        $cabecera["tarifa12"],
        $cabecera["iva"],
        $cabecera["total"],
        $cabecera["tipo_comprobante"]
    );
    if (!empty($res)) {
        foreach ($detalles as $val) {
            $res1 = guardarDetalleGastop(
                $res,
                $val["producto"],
                $val["bien_serivicio"],
                $val["iva"],
                $val["descuento"],
                $val["p_unitario"],
                $val["cantidad"],
                $val["id_tipo_gasto"],
                $val["total"]
            );
            if (empty($res1)) {
                $ok = 0;
                break;
            }
        }
    } else {
        $ok = 0;
    }
    if ($ok == 1) {
        pg_query($conexion, "COMMIT;");
    } else {
        pg_query($conexion, "ROLLBACK;");
    }

    return $ok;
}

function obtenerIdGastop()
{
    global $conexion;
    $sql = "select max(id_gastos_personales) from gastos_personales";
    $res = pg_query($conexion, $sql);
    if (pg_num_rows($res) > 0) {
        return pg_fetch_row($res)[0] + 1;
    }
    return 0;
}
function obtenerIdDetalleGastop()
{
    global $conexion;
    $sql = "select max(id_detalle_gastos_personales) from detalle_gastos_personales";
    $res = pg_query($conexion, $sql);
    if (pg_num_rows($res) > 0) {
        return pg_fetch_row($res)[0] + 1;
    }
    return 0;
}
function  guardarCabeceraGastop(
    $idpv,
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
    global $fecha, $hora, $conexion;
    $id = obtenerIdGastop();
    $sql = "
    INSERT INTO gastos_personales(
        id_gastos_personales, id_usuario, id_proveedor, identificacion_comprador, 
        razon_social_comprador, num_factura, num_autorizacion, fecha_emision, 
        descuento, subtotal, tarifa0, tarifa12, iva, total, comprobante, 
        tipo_comprobante, fecha_actual, hora_actual, estado,id_empresa)
        VALUES ($id, $idusuario, $idproveedor, '$idcomprador', 
        '$rscomprador', '$numfact', '$numaut', '$fechaem', 
        $descuento, $subtotal, $tarifa0, $tarifa12, $iva, $total, $id, 
        '$tipoc', '$fecha', '$hora', 'Activo',$idpv);

    ";
    $res = pg_query($conexion, $sql);
    if (!$res) {
        return 0;
    }
    return $id;
}
function guardarDetalleGastop(
    $idgastosp,
    $producto,
    $bienservicio,
    $iva,
    $valdescuento,
    $preciou,
    $cantidad,
    $idtipogasto,
    $total
) {
    global $conexion;
    $id = obtenerIdDetalleGastop();
    $sql = "INSERT INTO detalle_gastos_personales(
        id_detalle_gastos_personales, id_gastos_personales, producto, 
        bien_servicio, iva, valor_descuento, precio_u, cantidad, id_tipo_gasto,total)
        VALUES ($id, $idgastosp, '$producto', 
                '$bienservicio', '$iva', $valdescuento, $preciou, $cantidad, $idtipogasto,$total);
        ";
    $res = pg_query($conexion, $sql);
    if (!$res) {
        return 0;
    }
    return $id;
}

function existeFactura($idproveedor, $nrofactura)
{
    $sql = "select * from gastos_personales where id_proveedor=$idproveedor
    and num_factura='$nrofactura' and estado = 'Activo'";
    $res = pg_query($sql);

    if (pg_num_rows($res) > 0) {
        return true;
    }
    return false;
}
