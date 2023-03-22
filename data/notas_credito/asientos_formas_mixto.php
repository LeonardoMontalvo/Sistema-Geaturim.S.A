<?php
/* function getIdTransaccion()
{
    $sql = "SELECT COALESCE(max(id_transacciones),0) FROM transacciones;";
    $res = pg_query($sql);
    $row = pg_fetch_row($res);
    $id = $row[0] + 1;
    return $id;
}
function getNumTransaccion($idpuntov)
{
    $sql = "select COALESCE(max(num_transaccion)) from transacciones where id_tipo_transaccion='1' and id_empresa= '$idpuntov'";
    $res = pg_query($sql);
    $row = pg_fetch_row($res);
    $id = $row[0] + 1;
    return $id;
}
function getIdTransaccionPv($idpuntov)
{
    $sql = "select COALESCE(max(id_transaccion_pv::int)) from transacciones where  id_empresa= '$idpuntov'";
    $res = pg_query($sql);
    $row = pg_fetch_row($res);
    $id = $row[0] + 1;
    return $id;
} */

/* function insertAsiento(
    $idusuario,
    $comprobante,
    $fecha,
    $hora,
    $concepto,
    $total,
    $idcli,
    $obs,
    $idpuntov,
    $fecharegistro
) {
    $id = getIdTransaccion();
    $numtrans = getNumTransaccion();
    $idtranspv = getIdtransaccionPv();
    $sql = "insert into transacciones values(
        '$id',
        '$idusuario',
        '$comprobante',
        '$fecha',
        '$hora',
        '$concepto',
        '$total',
        '$total',
        '0.000',
        '1',
        '$numtrans',
        'Activo',
        '$idcli',
        '',
        '$obs',
        '',
        '',
        'DVFV',
        '',
        $idpuntov,
        '$fecharegistro',
        '$idtranspv')";
    $res = pg_query($sql);
    if (empty($res)) {
        return 0;
    }
    return $id;
}
 */

function getIdDetTransaccion()
{
    $sql = "SELECT COALESCE(max(id_detalle_transaccion),0) FROM detalle_transaccion;";
    $res = pg_query($sql);
    $row = pg_fetch_row($res);
    $id = $row[0] + 1;
    return $id;
}


function insertDetallesAsiento($idtrans, $idcuenta, $debito, $credito)
{
    $id = getIdDetTransaccion();
    $sql = "insert into detalle_transaccion values('$id','$idtrans','$idcuenta','$debito','" . $credito . "','Activo')";
    $res = pg_query($sql);
    if (empty($res)) {
        return 0;
    }
    return $id;
}

function insertDetallesTransaccionFPMixto($idtrans, $iddev, $ctacontado, $ctacheque, $ctatransf)
{
    $sql = "
    select fpm.forma_pago,
    fpm.valor
    from devolucion_venta dv,
    formas_pago_mixto_nv fpm
    where dv.id_devolucion_venta = fpm.id_devolucion_venta
    and dv.id_devolucion_venta = '$iddev'
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    foreach ($rows as $value) {
        if ($value["forma_pago"] = 'Contado') {
            insertDetallesAsiento($idtrans, $ctacontado, "0.000", $value["valor"]);
        } else if ($value["forma_pago"] = 'Cheque') {
            insertDetallesAsiento($idtrans, $ctacheque, "0.000", $value["valor"]);
        } else if ($value["forma_pago"] = 'Transferencias') {
            insertDetallesAsiento($idtrans, $ctatransf, "0.000", $value["valor"]);
        }
    }
}
