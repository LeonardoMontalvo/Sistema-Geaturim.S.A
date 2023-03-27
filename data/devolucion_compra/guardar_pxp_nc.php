<?php
///guardar pago
function getIdPagoP()
{
    $sql = "select max(id_cuentas_pagar) max from pagos_pagar";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return 1;
    }
    return $rows[0]["max"] + 1;
}

function getCompPagoP()
{
    $sql = "select max(comprobante::numeric) max from pagos_pagar";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return 1;
    }
    return $rows[0]["max"] + 1;
}

function getPagoC($idpagov)
{
    $sql = "SELECT id_pagos_compra, id_factura_compra, monto_credito, saldo FROM pagos_compra where id_pagos_compra='$idpagov';";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows[0];
}

function getFactura($idfactura)
{
    $sql = "SELECT id_proveedor, fecha_emision, num_serie FROM factura_compra where id_factura_compra=$idfactura;";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows[0];
}

function updateSaldoPagosC($idpagov, $saldo)
{
    $sql = "Update pagos_compra Set saldo='" . $saldo . "' where id_pagos_compra='" . $idpagov . "'";
    if ($saldo == 0) {
        $sql = "Update pagos_compra Set saldo='" . $saldo . "', estado='Cancelado' where id_pagos_compra='" . $idpagov . "'";
    }
    pg_query($sql);
}

function guardarPagoP($idpagov, $formap, $tipop, $valorp, $obs)
{
    global $conpuntoresult;
    $id = getIdPagoP();
    $comprobante = getCompPagoP();
    $usuario = $_SESSION["id"];
    $pagov = getPagoC($idpagov);
    $factura = getFactura($pagov["id_factura_compra"]);
    $fecha = date("Y-m-d");
    $hora = date("h:i:s A");

    $idproveedor = $factura["id_proveedor"];
    $fechaf = $factura["fecha_emision"];
    $numfac = $factura["num_serie"];
    $montoc = $pagov["monto_credito"];
    $saldoc = $pagov["saldo"];
    $saldo = $saldoc - $valorp;
    $saldo = round($saldo, 2);
    $sql = "
    INSERT INTO pagos_pagar(
        id_cuentas_pagar, id_proveedor, id_usuario, comprobante, fecha_actual, 
        hora_actual, forma_pago, tipo_pago, num_factura, tipo_factura, 
        fecha_factura, total_factura, valor_pagado, saldo_factura, observaciones, 
        estado, id_factura_compra, id_empresa, comprao_gasto)
        VALUES ($id, $idproveedor, $usuario, $comprobante, '$fecha', 
        '$hora', '$formap', '$tipop', '$numfac', 'FACTURA', 
        '$fechaf', $montoc, $valorp, $saldo, '$obs', 
        'Activo', $pagov[id_factura_compra], $conpuntoresult, 'C');
    ";

    $res = pg_query($sql);
    if (empty($res)) {
        return 0;
    }
    updateSaldoPagosC($pagov["id_pagos_compra"], $saldo);
    return $id;
}
