<?php

session_start();
include '../../procesos/base.php';
require_once '../../procesos/kardexValorizado.php';
require_once '../../procesos/detalleProductosBodega.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
error_reporting(0);
date_default_timezone_set('America/Guayaquil');

$conpunto = 1;
$consultapunto = pg_query("select max(id_punto_venta_empresa) from punto_venta_empresa where punto_venta_empresa.id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($consultapunto)) {
    $conpunto = $row[0];
}
$conpuntoresult = 1;
$consultapuntoresult = pg_query("select id_punto_venta from punto_venta_empresa where id_punto_venta_empresa='$conpunto'");
while ($row = pg_fetch_row($consultapuntoresult)) {
    $conpuntoresult = $row[0];
}
//////////////eliminar series///////////
pg_query("Update factura_compra Set estado='Pasivo' where id_factura_compra='$_POST[id_factura_compra]'");

pg_query("Update pagos_compra Set estado = 'Pasivo' where id_factura_compra = '$_POST[id_factura_compra]' and comprao_gasto='C'");
// Auditoria
insert_registro('ELIMINACION FACTURA COMPRA CON ID: ' . $_POST['id_factura_compra']);
$data = 1;
$bodega = $_SESSION['PV'];
$pvinv = $_SESSION['PV_INV'];

$detalleCompra = obtenerDetalleCompra($_POST['id_factura_compra'], $bodega);

foreach ($detalleCompra as $key) {
    $cant = $key["cantidad_unidad"];
    $cantidad = $key["cantidad"];
    if ($cant != 0) {
        $cantidad = $cant;
    } else {
        $cantidad = $cantidad;
    }

    $documento = "Anulación F.C: " . $key['num_serie'];
    $stock = obtenerStock($key['cod_productos'], $pvinv); //
    $total = number_format(($cantidad * $key['precio_compra']), 4, '.', ''); //FRANCIS
    updateKardex($key['comprobante'], $pvinv, $key['cod_productos'], 'Inactivo', 'C', NULL, NULL);
    updateKardexValorizado($key['comprobante'], $pvinv, $key['cod_productos'], 'Inactivo', 'C');
    procesarKardexSalida($key['cod_productos'], $documento, $cantidad, $stock, $key['precio_compra'], 'Activo',$pvinv, 'AC', $key['comprobante'], $total, NULL, NULL, $key['id_proveedor'], $_POST['observacion'], NULL, NULL, NULL);
}

//////////////////////////////////
/////////////////////////////////
/////// ASIENTO CONTABLE ///////
///////////////////////////////
//////////////////////////////

$asiento = pg_query("select id_transacciones from transacciones where comprobante='$_POST[id_factura_compra]' and concepto like 'COMPRA%'");
$row = pg_fetch_row($asiento);
if ($row[0] != "") {
    pg_query("update transacciones set estado='Pasivo' where id_transacciones=$row[0] and id_empresa='" . $conpuntoresult . "'");
    pg_query("update detalle_transaccion set estado='Pasivo' where id_transacciones=$row[0]");
}

$retIva = pg_query("select id_retencion_iva_factura_compra from retencion_iva_factura_compra where id_factura='$_POST[id_factura_compra]'");
$row1 = pg_fetch_row($retIva);
if ($row1[0] != "") {
    pg_query("update retencion_iva_factura_compra set estado='Pasivo' where id_retencion_iva_factura_compra=$row1[0]");
}

$retFuente = pg_query("select id_retencion_fuente_factura_compra from retencion_fuente_factura_compra where id_factura='$_POST[id_factura_compra]'");
$row1 = pg_fetch_row($retFuente);
if ($row1[0] != "") {
    pg_query("update retencion_fuente_factura_compra set estado='Pasivo' where id_retencion_fuente_factura_compra=$row1[0]");
}

activarValoresCruzadosNCC($_POST["id_factura_compra"]);
//////////////////////////////////////////
/////////////////////////////////////////
echo $data;

function obtenerDetalleCompra($idFactura, $bodega)
{
    $sql = "SELECT cod_productos,cantidad,precio_compra,comprobante,DFC.total_compra,id_proveedor,FC.observaciones,FC.num_serie,DFC.cantidad_unidad "
        . "FROM detalle_factura_compra DFC "
        . "INNER JOIN factura_compra FC ON FC.id_factura_compra = DFC.id_factura_compra "
        . "WHERE FC.id_empresa=$bodega AND FC.id_factura_compra=$idFactura";
    $row = pg_fetch_all(pg_query($sql));
    return $row;
}

function updateFormasPagoNCC($id)
{
    $sql = "
    update formas_pago_mixto_nc
    set estado='Activo'
    where id_formas_pago_mixto_nc=$id";
    $res = pg_query($sql);
}

function obtenerFormasPagoNC($idcompra)
{
    $sql = "select*from formas_pago_mixto_c
    where id_factura_compra=$idcompra
    and forma_pago='NOTA_CREDITO'
    and estado='Activo'";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}

function activarValoresCruzadosNCC($idcompra)
{
    $fp = obtenerFormasPagoNC($idcompra);
    foreach ($fp as $value) {
        updateFormasPagoNCC($value["numero_documento"]);
    }
}
