<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
error_reporting(0);
//	 echo '<br>GUARDAR FACTURA VENTA: <br>' . "Update rutas Set nombre_ruta='".strtoupper($_POST['nombre_ruta'])."', descripcion_ruta='$_POST[nombre]', id_vendedor='$_POST[id_vendedor]' where id_ruta='$_POST[id_rutas]'";//////////////////////////
	 
	 
if(pg_query("Update rutas Set nombre_ruta='".strtoupper($_POST['nombre_ruta'])."', descripcion_ruta='$_POST[nombre]', id_vendedor='$_POST[id_vendedor]', frecuencia='$_POST[frecuencia]' where id_ruta='$_POST[id_rutas]'")){
   $data = 1;
}
// Auditoria
insert_registro('MODIFICACION RUTA: ' . $_POST['nombre_ruta'] . ' CON ID VENDEDOR: ' . $_POST['id_vendedor']);
echo $data;
?>
