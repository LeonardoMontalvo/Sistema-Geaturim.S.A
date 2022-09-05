<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
//$id = $_POST['id_factura_compra'];
$s='<select  id="s1" style="width:200px;">';
$s=$s.'<option value="0" >Seleccione...</option>';
//$i=1;
$consulta = pg_query("select id_bancos, descripcion from bancos id_bancos");
while($row = pg_fetch_row($consulta)){
	$s=$s.'<option value='.$row[0].'>'.$row[1].'</option>';
	//$s=$s.$row[0].":'".$row[1]."-".$row[2]."', ";
}
//$x=substr($s, 0, -2);
echo $s.'</select>';
?>
