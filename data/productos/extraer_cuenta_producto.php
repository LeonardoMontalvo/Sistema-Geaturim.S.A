<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$consulta = pg_query("select max(cod_productos) from productos");
$row = pg_fetch_row($consulta);
$a="";
if($row[0]>0){
	$consulta1=pg_query("select p.id_plan_cuentas, pc.codigo_plan, pc.descripcion from plan_cuentas pc, productos p where p.id_plan_cuentas=pc.id_plan_cuentas and p.cod_productos=".$row[0]);
	$row1 = pg_fetch_row($consulta1);
	$a=$row1[0]."/".$row1[1]."/".$row1[2];
}
echo $a;
?>
