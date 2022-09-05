<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

/////////////////modificar clientes////////////////////
if (pg_query("Update tarifa_impuesto Set id_timpu='$_POST[id_timpu]', codigo_taimpuesto='$_POST[codigo_taimpuesto]',nombre_taimpuesto='$_POST[nombre_taimpuesto]' ,descripcion_taimpuesto='$_POST[descripcion_taimpuesto]' where id_taimpuesto='$_POST[id_taimpuesto]'")){

$data = 1;	
}
//////////////////////////////////////////////////////

echo $data;
?>
