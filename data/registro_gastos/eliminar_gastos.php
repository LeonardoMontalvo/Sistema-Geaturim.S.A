<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

//////////////eliminar series///////////
pg_query("Update gastos Set estado='Pasivo' where id_gastos='$_POST[comprobante]'");
$data = 1;


// RESTAR stock productos
   
    // fin resta
/////////////////////////////////

$asiento=pg_query("select id_transacciones from transacciones where comprobante='$_POST[comprobante]' and concepto like 'GASTO%'");
$row=pg_fetch_row($asiento);
if($row[0] != ""){
  pg_query("update transacciones set estado='Pasivo' where id_transacciones=$row[0]");
  pg_query("update detalle_transaccion set estado='Pasivo' where id_transacciones=$row[0]");
}

//$retIva=pg_query("select id_retencion_iva_factura_compra from retencion_iva_factura_compra where id_factura='$_POST[id_factura_compra]'");
//$row1=pg_fetch_row($retIva);
//if($row1[0]!= ""){
//    pg_query("update retencion_iva_factura_compra set estado='Pasivo' where id_retencion_iva_factura_compra=$row1[0]");
//}

//$retFuente=pg_query("select id_retencion_fuente_factura_compra from retencion_fuente_factura_compra where id_factura='$_POST[id_factura_compra]' and id_gastos='10'");
//$row1=pg_fetch_row($retFuente);
//if($row1[0]!= ""){
//    pg_query("update retencion_fuente_factura_compra set estado='Pasivo' where id_retencion_fuente_factura_compra=$row1[0] id_gastos='10'");
//}
        echo $data;
?>
