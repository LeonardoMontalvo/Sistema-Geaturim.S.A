<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
error_reporting(0);

if(pg_query("Update vendedores Set nombre_vendedor='".strtoupper($_POST['nombre'])."', ci_vendedor='$_POST[ruc_ci]', telefono_vendedor='$_POST[nro_telefono]', celular_vendedor='$_POST[nro_celular]', email_vendedor='$_POST[correo]', direccion_vendedor='".strtoupper($_POST['direccion_vend'])."', estado='Activo' where ci_vendedor='$_POST[ruc_ci]'")){


   $data = 1;
    // Auditoria
   insert_registro('MODIFICACION VENDEDOR: ' . $_POST['nombre'] . ' CON CI: ' . $_POST['ruc_ci']);
}
echo $data;
?>
