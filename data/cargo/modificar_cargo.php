<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

/////////////////modificar clientes////////////////////
if (pg_query("Update cargo Set nombre_cargo='$_POST[nombre_cargo]', sueldo_base='$_POST[sueldo_base]', codigo_sectorial='$_POST[codigo_sectorial]', estado='Activo' where id_cargo='$_POST[id_cargo]'")){

$data = 1;	
}
//////////////////////////////////////////////////////

echo $data;
?>
