<?php

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

function getPagoC($idfactura)
{
    $sql = "SELECT  id_pagos_compra,monto_credito,saldo FROM pagos_compra where  (estado='Activo' or estado='Cancelado') and id_factura_compra='$idfactura' and tipo_documento='FACTURA' and comprao_gasto='C'";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows[0];
}

function getFactura($idfactura)
{
    $sql = "select id_proveedor,fecha_actual, num_serie from factura_compra where id_factura_compra=$idfactura";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows[0];
}

function isFacturaCredito($idfactura)
{
    $sql = "
    select fv.id_factura_compra from factura_compra fv
    inner join formas_pago_mixto_c fp
    on fv.id_factura_compra=fp.id_factura_compra
    where fp.forma_pago='CREDITO'
    and fv.id_factura_compra=$idfactura
    ";
    $res = pg_query($sql);
    return pg_num_rows($res) > 0;
}

function tieneCuentaPagos($idfactura)
{
    $sql = "SELECT * FROM pagos_pagar 
    where estado='Activo' 
    and id_factura_compra='$idfactura' 
    and forma_pago <> 'RETENCION'
    and tipo_factura='FACTURA' 
    and comprao_gasto='C'";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return false;
    }
    return true;
}

function updateSaldoPagosC($idpagop, $saldo)
{
    $sql = "Update pagos_compra Set saldo='" . $saldo . "', estado='Activo' where id_pagos_compra='" . $idpagop . "'";
    if ($saldo == 0) {
        $sql = "Update pagos_compra Set saldo='" . $saldo . "', estado='Cancelado' where id_pagos_compra='" . $idpagop . "'";
    }
    pg_query($sql);
}

function guardarPagoP($idfactura, $formap, $tipop, $valorp, $obs, $banco, $fecha)
{
    global $conpuntoresult;
    $id = getIdPagoP();
    $comprobante = getCompPagoP();
    $usuario = $_SESSION["id"];
    $factura = getFactura($idfactura);
    $pagov = getPagoC($idfactura);
    //$fecha = date("Y-m-d");
    $hora = date("h:i:s A");

    $idproveedor = $factura["id_proveedor"];
    $fechaf = $factura["fecha_actual"];
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
            'Activo', $idfactura, $conpuntoresult, 'C');
    ";

    $res = pg_query($sql);
    if (empty($res)) {
        return;
    }
    updateSaldoPagosC($pagov["id_pagos_compra"], $saldo);
}

function getPagoPRetencion($idfactura)
{
    $sql = "SELECT * FROM pagos_pagar 
    where  estado='Activo' 
    and id_factura_compra='$idfactura' 
    and forma_pago = 'RETENCION'
    and tipo_factura='FACTURA' 
    and comprao_gasto='C'";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows[0];
}

function anularPagoPRetencion($idfactura)
{
    $pagor = getPagoPRetencion($idfactura);
    $pagoc = getPagoC($idfactura);
    $saldo = $pagoc["saldo"] + $pagor["valor_pagado"];

    $sql = "update pagos_pagar set estado='Anulado' where id_cuentas_pagar=$pagor[id_cuentas_pagar]";
    pg_query($sql);
    updateSaldoPagosC($pagoc["id_pagos_compra"], $saldo);
}
