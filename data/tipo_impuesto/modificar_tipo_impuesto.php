<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

/////////////////modificar clientes////////////////////
if (pg_query("Update tipo_impuesto Set nombre_timpu='$_POST[nombre_timpu]', codigo_timpu='$_POST[codigo_timpu]', estado_timpu='Activo' where id_timpu='$_POST[id_timpu]'")){

$data = 1;	
}
//////////////////////////////////////////////////////

echo $data;
?>
