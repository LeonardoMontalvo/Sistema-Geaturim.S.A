<?php
session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$data = 0;
$cont = 0;
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
$consulta = pg_query("select * from factura_venta where num_factura ='$_POST[num_fac]' and  num_chasis='1' and id_empresa='$conpuntoresult' ");
if ($_POST['tipo_venta'] == "NOTA") {
  $consulta = pg_query("select id_facturas_novalidas from facturas_novalidas where comprobante::text='$_POST[num_fac]'::int::text and id_empresa='$conpuntoresult'");
}
while ($row = pg_fetch_row($consulta)) {
  $cont++;
}
if ($cont == 0) {
  $data = 0;
} else {
  $consulta = pg_query("select max(num_factura) from factura_venta,  punto_venta_empresa where    id_empresa='$conpuntoresult' and    num_chasis='1' ");
  // $consulta=pg_query("select max(num_factura) from factura_venta where   id_usuario = '$_SESSION[id]' and  id_empresa='$conpuntoresult'");
  if ($_POST['tipo_venta'] == "NOTA") {
    $consulta = pg_query("select to_char(max(id_facturas_novalidas), 'fm000000000') as num_factura from facturas_novalidas,  punto_venta_empresa where punto_venta_empresa.id_usuario='$_SESSION[id]' and  id_empresa='$conpuntoresult'");
  }
  while ($row = pg_fetch_row($consulta)) {
    $data = $row[0];
  }
}
echo $data;
