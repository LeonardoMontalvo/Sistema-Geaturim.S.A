<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

/////////////////modificar clientes////////////////////
if (pg_query("Update tipo_comprobante Set descripcion='$_POST[descripcion]', abreviatura='$_POST[abreviatura]', estado='Activo', codigo='$_POST[codigo]' where id_tipo_comprobante='$_POST[id_tipo_comprobante]'")){
$data = 1;	
}
//////////////////////////////////////////////////////

echo $data;
?>
