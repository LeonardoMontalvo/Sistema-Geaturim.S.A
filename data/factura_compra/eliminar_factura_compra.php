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
// Auditoria
insert_registro('ELIMINACION FACTURA COMPRA CON ID: ' . $_POST['id_factura_compra']);
$data = 1;
$bodega = $_SESSION['PV'];
$detalleCompra = obtenerDetalleCompra($_POST['id_factura_compra'], $bodega);

foreach ($detalleCompra as $key) {
    $documento = "Anulación F.C: " . $key['num_serie'];
    $stock = obtenerStock($key['cod_productos'], $_SESSION['PV']);
    updateKardex($key['comprobante'], $bodega, $key['cod_productos'], 'Inactivo', 'C',NULL, NULL);
    updateKardexValorizado($key['comprobante'], $bodega, $key['cod_productos'], 'Inactivo', 'C');
    procesarKardexSalida($key['cod_productos'], $documento, $key['cantidad'], $stock, $key['precio_compra'], 'Activo', $bodega, 'AC', 
            $key['comprobante'], $key['total_compra'], NULL, NULL, $key['id_proveedor'], $_POST['observacion'], NULL, NULL, NULL);
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
//////////////////////////////////////////
/////////////////////////////////////////
echo $data;

function obtenerDetalleCompra($idFactura, $bodega) {
    $sql = "SELECT cod_productos,cantidad,precio_compra,comprobante,DFC.total_compra,id_proveedor,FC.observaciones,FC.num_serie "
            . "FROM detalle_factura_compra DFC "
            . "INNER JOIN factura_compra FC ON FC.id_factura_compra = DFC.id_factura_compra "
            . "WHERE FC.id_empresa=$bodega AND FC.id_factura_compra=$idFactura";
    $row = pg_fetch_all(pg_query($sql));
    return $row;
}

?>
