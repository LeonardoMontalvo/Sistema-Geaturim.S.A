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

pg_query("update conciliacion_bancaria set estado='Pasivo'where id_conciliacion_bancaria=".$id_conciliacion);
////////////////////////////////

$data = $cont;
echo $data;
?>
