<?php
session_start();
date_default_timezone_set('America/Guayaquil');
include '../../procesos/base.php';
$conexion = conectarse();
$idpagov = $_POST["id_cxc"];
$idpagoc = $_POST["id_pago"];
$valorp = $_POST["valor_p"];
$tipop = $_POST["tipo_p"];
$otrosval = $_POST["otros_val"]? $_POST["otros_val"] : 0;
$ctabanco = $_POST["cuenta_banco"];
$fechanulado = $_POST["fecha_anulado"];
$formapago = $_POST["forma_pago"];
$fecha = date('Y-m-d');
$hora = date('h:i:s A');
$idusuario = $_SESSION["id"];
//error_reporting(0);

echo json_encode(transaccionAnularPago());

function transaccionAnularPago()
{
    global $conexion, $idpagov, $idpagoc, $valorp, $otrosval, $ctabanco, $fechanulado, $formapago;
    $otrosval = !!$otrosval ? $otrosval : 0;

    pg_query($conexion, "BEGIN");
    $anularPago = anularPagoC($idpagoc);
    $revTrans = revertirTransaccion($idpagoc, $otrosval, $fechanulado);
    $revdettrans = revertirDetallesTrans($idpagoc, $revTrans, $otrosval, $ctabanco);
    $update = upadateSaldoCxc($idpagov, $valorp);
    $inscxc = insertCxcCompesarPagoAnulado($idpagov, $valorp + $otrosval, $fechanulado, $formapago, $idpagoc);
    //$inspxc = insertPagoCxcCompesarPagoAnulado($idpagoc, $valorp);
    pg_query($conexion, "COMMIT");
    $anulado =
        !empty($anularPago)
        && !empty($update)
        && !empty($revTrans)
        && !empty($revdettrans)
        && !empty($inscxc);
    //&&!empty($inspxc);

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
function getIdPagoVenta()
{
    global $conexion;
    $sql = "select max(id_pagos_venta) from pagos_venta";
    $res = pg_query($conexion, $sql);
    $rows = pg_fetch_all($res);
    return $rows[0]["max"] + 1;
}
/* function getIdPagoCobrar()
{
    global $conexion;
    $sql = "select max(id_pagos_cobrar) from pagos_cobrar";
    $res = pg_query($conexion, $sql);
    $rows = pg_fetch_all($res);
    return $rows[0]["max"] + 1;
}
function getCompPagoCobrar()
{
    global $conexion;
    $sql = "select max(comprobante::integer) from pagos_cobrar";
    $res = pg_query($conexion, $sql);
    $rows = pg_fetch_all($res);
    return $rows[0]["max"] + 1;
} */

function anularPagoC($idpago)
{
    global $conexion;
    $sql = "update pagos_cobrar set estado='Anulado' where id_pagos_cobrar=$idpago";
    $res = pg_query($conexion, $sql);
    return $res;
}

function upadateSaldoCxc($idpago, $valorp)
{
    global $conexion, $tipop;
    if ($tipop == 'INTERNA') {
        $sql = "update pagos_venta set saldo=saldo+$valorp, estado='Activo' where id_pagos_venta=$idpago";
    } else if ($tipop == 'EXTERNA') {
        $sql = "update c_cobrarexternas set saldo=saldo+$valorp, estado='Activo' where id_c_cobrarexternas=$idpago";
    }

    $res = pg_query($conexion, $sql);
    return $res;
}

function revertirTransaccion($idpago, $otroval, $fechanulado)
{
    global $conexion, $fecha, $hora, $idusuario;
    $id = getIdTransaccion();
    $num = getNumTransaccion();
    $idpv = getIdTransaccionPv();
    $sql = "
    insert into transacciones select
    $id, 
    $idusuario, 
    comprobante, 
    '$fecha', 
    '$hora', 
    'ANULAR PAGO '||concepto, 
    total_debe+$otroval, 
    total_haber+$otroval, 
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
    where concepto ilike 'CUENTA POR COBRAR%'
    and comprobante='$idpago'
    ";
    $res = pg_query($conexion, $sql);
    if ($res == false) {
        return 0;
    }
    return $id;
}
function revertirDetallesTrans($idpago, $idtran, $otroval, $ctabanco)
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
        $credito = $value["credito"];
        $debito = $value["debito"];
        $cta = $value["id_plan_cuentas"];

        if (!empty($ctabanco)) {
            if ($debito > 0) {
                $cta = $ctabanco;
            }
        }

        if ($otroval > 0) {
            if (empty($ctabanco)) {
                if ($credito > 0) {
                    $credito += $otroval;
                } else if ($debito > 0) {
                    $debito += $otroval;
                }
            } else {
                if ($credito > 0) {
                    $credito += $otroval;
                }
            }
        }


        $sql = "
        INSERT INTO detalle_transaccion(
            id_detalle_transaccion, id_transacciones, id_plan_cuentas, debito, 
            credito, estado, conciliado)
            VALUES ($id, $idtran, $cta, $credito, 
            $debito, 'Activo', NULL);
        ";
        $res = pg_query($conexion, $sql);
        if ($res == false) {
            return false;
        }
    }

    if (!empty($ctabanco)) {
        if ($otroval) {
            foreach ($rows as $value) {
                $id = getIdDetTransaccion();
                $debito = 0;
                $credito = 0;
                if ($value["debito"] > 0) {
                    $debito = $otroval;
                    $sql = "
                    INSERT INTO detalle_transaccion(
                    id_detalle_transaccion, id_transacciones, id_plan_cuentas, debito, 
                    credito, estado, conciliado)
                    VALUES ($id, $idtran, $ctabanco, $credito, 
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

function insertCxcCompesarPagoAnulado($idpagov, $valorp, $fechanulado, $formapago, $idpagoc)
{
    global $conexion, $idusuario;
    $id = getIdPagoVenta();
    $sql = "
    insert into pagos_venta SELECT $id, id_cliente, id_factura_venta, $idusuario, '$fechanulado', 
        adelanto,$idpagoc, tipo_documento, $valorp, 0, 'Anulado', 
        fecha_dias, id_empresa
        FROM pagos_venta WHERE id_pagos_venta=$idpagov;
    ";
    $res = pg_query($conexion, $sql);
    if (!$res) {
        return 0;
    }
    return $id;
}
/* function insertPagoCxcCompesarPagoAnulado($idpagoc, $valorp)
{
    global $conexion, $idusuario, $fecha, $hora;
    $id = getIdPagoCobrar();
    $comp = getCompPagoCobrar();
    $sql = "
    insert into pagos_cobrar SELECT $id, id_cliente, $idusuario, '$comp', '$fecha', 
    '$hora', 'PAGO_ANULADO', tipo_pago, num_factura, 'anulacion_pf', 
    fecha_factura, $valorp, $valorp, 0, 'PAGO ANULADO: $idpagoc', 
    'Activo', id_empresa, banco
    FROM pagos_cobrar WHERE id_pagos_cobrar=$idpagoc;
    ";

    $res = pg_query($conexion, $sql);
    if (!$res) {
        return 0;
    }
    return $id;
}
 */