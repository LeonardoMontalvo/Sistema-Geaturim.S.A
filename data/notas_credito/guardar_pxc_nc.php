<?php
///guardar pago
function getIdPagoC()
{
    $sql = "select max(id_pagos_cobrar) max from pagos_cobrar";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return 1;
    }
    return $rows[0]["max"] + 1;
}

function getCompPagoC()
{
    $sql = "select max(comprobante::numeric) max from pagos_cobrar";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return 1;
    }
    return $rows[0]["max"] + 1;
}

function getPagoV($idpagov)
{
    $sql = "SELECT  id_pagos_venta,monto_credito,saldo,id_factura_venta FROM pagos_venta where  id_pagos_venta='$idpagov'";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows[0];
}

function getFactura($idfactura)
{
    $sql = "select id_cliente,fecha_actual, num_factura from factura_venta where id_factura_venta=$idfactura";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows[0];
}

function updateSaldoPagosV($idpagov, $saldo)
{
    $sql = "Update pagos_venta Set saldo='" . $saldo . "' where id_pagos_venta='" . $idpagov . "'";
    if ($saldo == 0) {
        $sql = "Update pagos_venta Set saldo='" . $saldo . "', estado='Cancelado' where id_pagos_venta='" . $idpagov . "'";
    }
    pg_query($sql);
}

function guardarPagoC($idpagov, $formap, $tipop, $valorp, $obs, $banco)
{
    global $conpuntoresult;
    $id = getIdPagoC();
    $comprobante = getCompPagoC();
    $usuario = $_SESSION["id"];
    $pagov = getPagoV($idpagov);
    $factura = getFactura($pagov["id_factura_venta"]);
    $fecha = date("Y-m-d");
    $hora = date("h:i:s A");

    $idcliente = $factura["id_cliente"];
    $fechaf = $factura["fecha_actual"];
    $numfac = $factura["num_factura"];
    $montoc = $pagov["monto_credito"];
    $saldoc = $pagov["saldo"];
    $saldo = $saldoc - $valorp;
    $saldo = round($saldo, 2);

    $sql = "
    INSERT INTO pagos_cobrar(
        id_pagos_cobrar, id_cliente, id_usuario, comprobante, fecha_actual, 
        hora_actual, forma_pago, tipo_pago, num_factura, tipo_factura, 
        fecha_factura, total_factura, valor_pagado, saldo_factura, observaciones, 
        estado, id_empresa, banco)
        VALUES ($id, $idcliente, $usuario, $comprobante, '$fecha', 
        '$hora', '$formap', '$tipop', '$numfac', 'Factura', 
        '$fechaf', $montoc, $valorp, $saldo, '$obs', 
        'Activo', $conpuntoresult, '$banco');

    ";

    $res = pg_query($sql);
    if (empty($res)) {
        return 0;
    }
    updateSaldoPagosV($pagov["id_pagos_venta"], $saldo);
    return $id;
}

