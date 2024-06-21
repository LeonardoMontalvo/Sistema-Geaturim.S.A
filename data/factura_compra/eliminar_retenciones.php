<?php

session_start();
include '../../procesos/base.php';
require_once '../../procesos/kardexValorizado.php';
require_once '../../procesos/detalleProductosBodega.php';
// Auditoria
require_once '../../procesos/auditoria.php';
require_once __DIR__ . "/guardar_pxp_retencion.php";

conectarse();
//error_reporting(0);
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
$valorp = $_POST["total_retencion_oculto"];
//echo ':1:'."Update pagos_pagar Set estado = 'Anulado' where id_factura_compra = '$_POST[id_factura_compra]' and comprao_gasto='C'.\n";

//pg_query("Update pagos_pagar Set estado = 'Anulado' where id_factura_compra = '$_POST[id_factura_compra]' and comprao_gasto='C'");
if (isFacturaCredito($_POST["id_factura_compra"])) {
    anularPagoPRetencion($_POST["id_factura_compra"]);
}

//echo ':2:'."Update pagos_compra set saldo=saldo+$valorp , estado='Activo' where id_factura_compra = '$_POST[id_factura_compra]' and comprao_gasto='C'.\n";
//pg_query("Update pagos_compra set saldo=saldo+$valorp , estado='Activo' where id_factura_compra = '$_POST[id_factura_compra]' and comprao_gasto='C'");
// Auditoria
insert_registro('ELIMINACION RETENCION FACTURA COMPRA CON ID: ' . $_POST['id_factura_compra']);
$data = 1;
$bodega = $_SESSION['PV'];

//////////////////////////////////
/////////////////////////////////
/////// ASIENTO CONTABLE ///////
///////////////////////////////
//////////////////////////////
//echo ':3:'."select id_transacciones from transacciones where comprobante='$_POST[id_comprobante_serie]' and concepto like 'RETEN%'.\n";
$asiento = pg_query("select id_transacciones from transacciones where comprobante='$_POST[id_comprobante_serie]' and concepto like 'RETEN%'");
$row = pg_fetch_row($asiento);
if ($row[0] != "") {
    //    echo ':4:'."update transacciones set estado='Pasivo' where id_transacciones=$row[0] and id_empresa='" . $conpuntoresult . "'.\n";
    pg_query("update transacciones set estado='Pasivo' where id_transacciones=$row[0] and id_empresa='" . $conpuntoresult . "'");
    //        echo ':5:'."update detalle_transaccion set estado='Pasivo' where id_transacciones=$row[0].\n";
    pg_query("update detalle_transaccion set estado='Pasivo' where id_transacciones=$row[0]");
}
// echo ':6:'."select id_retencion_iva_factura_compra from retencion_iva_factura_compra where id_factura='$_POST[id_factura_compra].'\n";
$retIva = pg_query("select id_retencion_iva_factura_compra from retencion_iva_factura_compra where id_factura='$_POST[id_factura_compra]'");
$row1 = pg_fetch_row($retIva);
if ($row1[0] != "") {
    //    echo ':7:'."update retencion_iva_factura_compra set estado='Pasivo' where id_retencion_iva_factura_compra=$row1[0].'\n";
    pg_query("update retencion_iva_factura_compra set estado='Pasivo' where id_retencion_iva_factura_compra=$row1[0]");
}

//  echo ':8:'."select id_retencion_fuente_factura_compra from retencion_fuente_factura_compra where id_factura='$_POST[id_factura_compra]' and id_gastos=1.'\n";
$retFuente = pg_query("select id_retencion_fuente_factura_compra from retencion_fuente_factura_compra where id_factura='$_POST[id_factura_compra]' and id_gastos=1");
$row1 = pg_fetch_row($retFuente);
if ($row1[0] != "") {
    //      echo ':9:'."select id_retencion_fuente_factura_compra from retencion_fuente_factura_compra where id_factura='$_POST[id_factura_compra]' and id_gastos=1.'\n";
    pg_query("update retencion_fuente_factura_compra set estado_reten='Pasivo' where id_factura='$_POST[id_factura_compra]' and id_gastos=1");
}


//////////////////////////////////////////
/////////////////////////////////////////
echo $data;
