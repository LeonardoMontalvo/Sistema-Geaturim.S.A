<?php
session_start();
date_default_timezone_set('America/Guayaquil');
include '../../procesos/base.php';
$conexion = conectarse();
//error_reporting(0);

$idpagov = $_POST["id_cxp"];
$idpagoc = $_POST["id_pago"];
$valorp = $_POST["valor_p"];
$tipop = $_POST["tipo_p"];
$idusuario = $_SESSION["id"];
$fecha = date('Y-m-d');
$hora = date('h:i:s A');
echo json_encode(transaccionAnularPago());

function transaccionAnularPago()
{
    global $conexion, $idpagoc, $idpagov, $valorp;

    pg_query($conexion, "BEGIN");
    $anularPago = anularPagoP($idpagoc);
    $revTrans = revertirTransaccion($idpagoc);
    $revdettrans = revertirDetallesTrans($idpagoc, $revTrans);
    $update = upadateSaldoCxp($idpagov, $valorp);
    $inscxc = insertCxpCompesarPagoAnulado($idpagov, $valorp);
    $inspxc = insertPagoCxpCompesarPagoAnulado($idpagoc, $valorp);
    pg_query($conexion, "COMMIT");
    $anulado =
        !empty($anularPago)
        && !empty($update)
        && !empty($revTrans)
        && !empty($revdettrans)
        && !empty($inscxc)
        && !empty($inspxc);
    return $anulado ? 1 : 0;
}

function getIdTransaccion()
{
    global $conexion;
    $sql = "select max(id_transacciones) from transacciones";
    $res = pg_query($conexion, $sql);
    $rows = pg_fetch_all($res);
    return $rows[0]["max"] + 1;
}
function getNumTransaccion()
{
    global $conexion;
    $sql = "select max(num_transaccion) from transacciones";
    $res = pg_query($conexion, $sql);
    $rows = pg_fetch_all($res);
    return $rows[0]["max"] + 1;
}
function getIdTransaccionPv()
{
    global $conexion;
    $sql = "select max(id_transaccion_pv::integer) from transacciones";
    $res = pg_query($conexion, $sql);
    $rows = pg_fetch_all($res);
    return $rows[0]["max"] + 1;
}
function getIdDetTransaccion()
{
    global $conexion;
    $sql = "select max(id_detalle_transaccion) from detalle_transaccion";
    $res = pg_query($conexion, $sql);
    $rows = pg_fetch_all($res);
    return $rows[0]["max"] + 1;
}
function getIdPagoCompra()
{
    global $conexion;
    $sql = "select max(id_pagos_compra) from pagos_compra";
    $res = pg_query($conexion, $sql);
    $rows = pg_fetch_all($res);
    return $rows[0]["max"] + 1;
}
function getIdPagoPagar()
{
    global $conexion;
    $sql = "select max(id_cuentas_pagar) from pagos_pagar";
    $res = pg_query($conexion, $sql);
    $rows = pg_fetch_all($res);
    return $rows[0]["max"] + 1;
}
function getCompPagoPagar()
{
    global $conexion;
    $sql = "select max(comprobante::integer) from pagos_pagar";
    $res = pg_query($conexion, $sql);
    $rows = pg_fetch_all($res);
    return $rows[0]["max"] + 1;
}

function anularPagoP($idpago)
{
    global $conexion;
    $sql = "update pagos_pagar set estado='Pasivo' where id_cuentas_pagar=$idpago";
    $res = pg_query($conexion, $sql);
    return $res;
}

function upadateSaldoCxp($idpago, $valorp)
{
    global $conexion, $tipop;
    if ($tipop == 'INTERNA') {
        $sql = "update pagos_compra set saldo=saldo+$valorp, estado='Activo' where id_pagos_compra=$idpago";
    } else if ($tipop == 'EXTERNA') {
        $sql = "update c_pagarexternas set saldo=saldo+$valorp, estado='Activo' where id_c_pagarexternas=$idpago";
    }

    $res = pg_query($conexion, $sql);
    return $res;
}


function revertirTransaccion($idpago)
{
    global $conexion, $idusuario;
    $id = getIdTransaccion();
    $num = getNumTransaccion();
    $idpv = getIdTransaccionPv();
    $fecha = date('Y-m-d');
    $hora = date('h:i:s A');
    $sql = "
    insert into transacciones select
    $id, 
    $idusuario, 
    comprobante, 
    '$fecha', 
    '$hora', 
    'ANULAR PAGO '||concepto, 
    total_debe, 
    total_haber, 
    saldo, 
    id_tipo_transaccion, 
    $num, 
    'Activo', 
    id_cliente, 
    deposito, 
    '', 
    num_cuenta, 
    banco, 
    identificador_cli_pro, 
    valor_concepto, 
    id_empresa, 
    '$fecha', 
    '$idpv'
    from transacciones 
    where concepto ilike 'CUENTA POR PAGAR%'
    and comprobante='$idpago'
    ";
    $res = pg_query($conexion, $sql);
    if ($res == false) {
        return 0;
    }
    return $id;
}
function revertirDetallesTrans($idpago, $idtran)
{
    global $conexion;
    $sql = "
    select*from detalle_transaccion 
    where id_transacciones in(
        select id_transacciones from transacciones 
        where concepto ilike 'CUENTA POR COBRAR%'
        and comprobante='$idpago'
    ) order by id_detalle_transaccion asc;
    ";
    $res = pg_query($conexion, $sql);
    if ($res == false) {
        return false;
    }
    $rows = pg_fetch_all($res);
    foreach ($rows as $value) {
        $id = getIdDetTransaccion();
        $sql = "
        INSERT INTO detalle_transaccion(
            id_detalle_transaccion, id_transacciones, id_plan_cuentas, debito, 
            credito, estado, conciliado)
            VALUES ($id, $idtran, $value[id_plan_cuentas], $value[credito], 
            $value[debito], 'Activo', NULL);
        ";
        $res = pg_query($conexion, $sql);
        if ($res == false) {
            return false;
        }
    }
    return true;
}

function insertCxpCompesarPagoAnulado($idpagov, $valorp)
{
    global $conexion, $idusuario, $fecha;
    $id = getIdPagoCompra();
    $sql = "
    insert into pagos_compra SELECT $id, id_proveedor, id_factura_compra, $idusuario, 
    '$fecha', adelanto, meses, 'anulacion_pf', $valorp, 
    0, 'Cancelado', comprao_gasto
    FROM pagos_compra WHERE id_pagos_compra=$idpagov;

    ";
    $res = pg_query($conexion, $sql);
    if (!$res) {
        return 0;
    }
    return $id;
}
function insertPagoCxpCompesarPagoAnulado($idpagov, $valorp)
{
    global $conexion, $idusuario, $fecha, $hora;
    $id = getIdPagoPagar();
    $comp = getCompPagoPagar();
    $sql = "
    insert into pagos_pagar SELECT $id, id_proveedor, $idusuario, '$comp', '$fecha', 
    '$hora', 'PAGO_ANULADO', tipo_pago, num_factura, 'anulacion_pf', 
    fecha_factura, $valorp, $valorp, 0, 'PAGO ANULADO: $idpagov', 
    'Activo', id_factura_compra, id_empresa, comprao_gasto
    FROM pagos_pagar WHERE id_cuentas_pagar=$idpagov;
    ";

    $res = pg_query($conexion, $sql);
    if (!$res) {
        return 0;
    }
    return $id;
}
