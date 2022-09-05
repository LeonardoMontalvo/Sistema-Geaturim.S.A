<?php

session_start();
include 'base.php';
conectarse();
//	 echo '<br>GUARDAR FACTURA VENTA: <br>' . "SELECT  establecimiento, punto_emision FROM empresa where id_empresa='$_SESSION[PV]'";//////////////////////////
//	 
$consulta = pg_query("SELECT  establecimiento, punto_emision FROM empresa where id_empresa='$_SESSION[PV]'");
$row = pg_fetch_row($consulta);
echo $row[0]."-".$row[1];
 
?>
