<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

/////////////////modificar clientes////////////////////
// echo '<br>GUARDAR FACTURA VENTA: <br>' . "Update parametros_iess Set descripcion='$_POST[descripcion_iess]', valor='$_POST[valor_aporte]', estado='Activo' where id_parametro_iess='$_POST[id_parametro_iess]'";

if (pg_query("Update parametros_iess Set descripcion='$_POST[descripcion_iess]', valor='$_POST[valor_aporte]', estado='Activo' where id_parametro_iess='$_POST[id_parametro_iess]'")){

$data = 1;	
}
//////////////////////////////////////////////////////

echo $data;
?>
