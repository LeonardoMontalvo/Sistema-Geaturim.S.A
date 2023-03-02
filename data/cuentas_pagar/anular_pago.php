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
$otrosval = $_POST["otros_val"]? $_POST["otros_val"] : 0;;
$fechanulado = $_POST["fecha_anulado"];
$formapago = $_POST["forma_pago"];
$idusuario = $_SESSION["id"];
$fecha = date('Y-m-d');
$hora = date('h:i:s A');
echo json_encode(transaccionAnularPago());

function transaccionAnularPago()
{
    global $conexion, $idpagoc, $idpagov, $valorp, $otrosval, $fechanulado, $formapago;

    pg_query($conexion, "BEGIN");
    $anularPago = anularPagoP($idpagoc);
    $revTrans = revertirTransaccion($idpagoc, $otrosval, $fechanulado);
    $revdettrans = revertirDetallesTrans($idpagoc, $revTrans, $otrosval, $formapago);
    $update = upadateSaldoCxp($idpagov, $valorp);
    $inscxc = insertCxpCompesarPagoAnulado($idpagov, $valorp + $otrosval, $fechanulado, $formapago, $idpagoc);
    //$inspxc = insertPagoCxpCompesarPagoAnulado($idpagoc, $valorp);
    pg_query($conexion, "COMMIT");
    $anulado =
        !empty($anularPago)
        && !empty($update)
        && !empty($revTrans)
        && !empty($revdettrans)
        && !empty($inscxc);
    //&& !empty($inspxc);
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
/* function getIdPagoPagar()
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
} */

function anularPagoP($idpago)
{
    global $conexion;
    $sql = "update pagos_pagar set estado='Anulado' where id_cuentas_pagar=$idpago";
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


function revertirTransaccion($idpago, $otrosval, $fechanulado)
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
    total_debe+$otrosval, 
    total_haber+$otrosval, 
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
    '$fechanulado', 
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
function revertirDetallesTrans($idpago, $idtran, $otroval, $formap)
{
    global $conexion;
    $sql = "
    select*from detalle_transaccion 
    where id_transacciones in(
        select id_transacciones from transacciones 
        where concepto ilike 'CUENTA POR PAGAR%'
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
        $credito = $value["credito"];
        $debito = $value["debito"];


        /*  if ($formap=='CHEQUE') {
            if ($debito > 0) {
                $cta = $ctabanco;
            }
        } */

        if ($otroval > 0) {
            if ($formap == 'CHEQUE') {
                if ($debito > 0) {
                    $debito += $otroval;
                }
            } else {
                if ($credito > 0) {
                    $credito += $otroval;
                } else if ($debito > 0) {
                    $debito += $otroval;
                }
            }
        }

        $sql = "
        INSERT INTO detalle_transaccion(
            id_detalle_transaccion, id_transacciones, id_plan_cuentas, debito, 
            credito, estado, conciliado)
            VALUES ($id, $idtran, $value[id_plan_cuentas], $credito, 
            $debito, 'Activo', NULL);
        ";
        $res = pg_query($conexion, $sql);
        if ($res == false) {
            return false;
        }
    }
    if ($formap == 'CHEQUE') {
        if ($otroval) {
            foreach ($rows as $value) {
                $id = getIdDetTransaccion();
                $debito = 0;
                $credito = 0;
                if ($value["credito"] > 0) {
                    $credito = $otroval;
                    $sql = "
                    INSERT INTO detalle_transaccion(
                    id_detalle_transaccion, id_transacciones, id_plan_cuentas, debito, 
                    credito, estado, conciliado)
                    VALUES ($id, $idtran, 611, $credito, 
                    $debito, 'Activo', NULL);
                ";
                    $res = pg_query($conexion, $sql);
                    if ($res == false) {
                        return false;
                    }
                }
            }
        }
    }
    return true;
}

function insertCxpCompesarPagoAnulado($idpagov, $valorp, $fechanulado, $formapago, $idpagoc)
{
    global $conexion, $idusuario;
    $id = getIdPagoCompra();
    $sql = "
    insert into pagos_compra SELECT $id, id_proveedor, id_factura_compra, $idusuario, 
    '$fechanulado', adelanto, $idpagoc, tipo_documento, $valorp, 
    0, 'Anulado', comprao_gasto
    FROM pagos_compra WHERE id_pagos_compra=$idpagov;

    ";
    $res = pg_query($conexion, $sql);
    if (!$res) {
        return 0;
    }
    return $id;
}


/* function insertPagoCxpCompesarPagoAnulado($idpagov, $valorp)
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
 */