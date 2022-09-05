<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

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


//////////////////////////  
//
//modificar detalle_factura/////
$total = $_POST['total'];
$format_numero = number_format($total, 2, '.', '');
pg_query("update gastos_internos set estado='Anulado' where comprobante='$_POST[comprobante]'  and id_empresa=$conpuntoresult ");
////////////////////////////////

$data = 1;
echo $data;
?>
