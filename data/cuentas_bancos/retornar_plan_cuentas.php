<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
//$id = $_POST['id_factura_compra'];
$s='<select  id="s1" style="width:200px;">';
$s=$s.'<option value="0" >Seleccione...</option>';
//$i=1;
$consulta = pg_query("select id_plan_cuentas, codigo_plan, descripcion from plan_cuentas where cuenta='M' and descripcion like '%anco%' and codigo_plan like '1.%' order by id_plan_cuentas");
while($row = pg_fetch_row($consulta)){
	$s=$s.'<option value='.$row[0].'>'.$row[1].'-'.$row[2].'</option>';
	//$s=$s.$row[0].":'".$row[1]."-".$row[2]."', ";
}
//$x=substr($s, 0, -2);
echo $s.'</select>';
?>
