<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

/////////////////modificar clientes////////////////////
if (pg_query("Update tipo_documento Set nombre_tdocu='$_POST[nombre_tdocu]', codigo_tdocu='$_POST[codigo_tdocu]', estado_tdocu='Activo' where id_tdocu='$_POST[id_tdocu]'")){

$data = 1;	
}
//////////////////////////////////////////////////////

echo $data;
?>
