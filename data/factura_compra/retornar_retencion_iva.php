<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_POST['id_factura_compra'];
$arr_data = array();
$s="";
$consulta = pg_query("select r.id_factura, r.id_retencion_iva, r.valor_retencion from retencion_iva_factura_compra r where r.id_factura = '$_POST[id_factura_compra]'");
while($row = pg_fetch_row($consulta)){
	$s=$s.$row[0]."-";
	$s=$s.$row[1]."-";
	$s=$s.$row[2];
}
echo $s;
?>
