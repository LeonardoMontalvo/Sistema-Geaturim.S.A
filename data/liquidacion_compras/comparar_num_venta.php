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
$consulta = pg_query("select * from liquidacion_compra where num_factura ='$_POST[num_fac]' and id_empresa='$conpuntoresult'");
while ($row = pg_fetch_row($consulta)) {
    $cont++;
}
if ($cont == 0) {
    $data = 0;
} else {
    $consulta = pg_query("select max(num_factura) from liquidacion_compra");
	while ($row = pg_fetch_row($consulta)) {
	    $data = $row[0];
	}
}
echo $data;
?>