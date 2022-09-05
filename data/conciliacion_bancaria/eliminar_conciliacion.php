<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

//////////////////////////  
$id_conciliacion=$_POST['idConciliacion'];
//id conciliacion bancaria/////
$id=pg_query("select max(id_conciliacion_bancaria) from conciliacion_bancaria");
while ($row=pg_fetch_row($id)) {
	$cont=$row[0];
}
$cont=$cont+1;
//	 echo '<br>GUARDAR FACTURA VENTA: <br>' . "update conciliacion set estado='Pasivo' where id_conciliacion_bancaria=".$id_conciliacion;//////////////////////////
	 
pg_query("update conciliacion set estado='Pasivo' where id_conciliacion=".$id_conciliacion);

pg_query("update detalle_conciliacion set estado='Pasivo' where id_conciliacion=".$id_conciliacion);
////////////////////////////////

$data = $cont;
echo $data;
?>
