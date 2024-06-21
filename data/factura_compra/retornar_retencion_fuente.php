<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_POST['id_factura_compra'];
$s="";
$consulta = pg_query("select 
r.id_factura, 
r.id_retencion_fuente, 
r.valor_retencion 
from retencion_fuente_factura_compra r 
where r.id_factura = '".$id."' 
and r.id_gastos=1
and r.estado='Activo'");
while($row = pg_fetch_row($consulta)){
	$s=$s.$row[0]."-";
	$s=$s.$row[1]."-";
	$s=$s.$row[2];
}
echo $s;
?>
