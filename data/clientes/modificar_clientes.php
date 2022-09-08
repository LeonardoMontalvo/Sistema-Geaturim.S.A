<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
error_reporting(0);
$var=$_POST[ruc_ci];
$ejemplo = strlen($var);
if ($ejemplo == 10) {
    $tipo_docuu = 'Cedula';
    $id_tdocu = 2;
} else if ($ejemplo == 13) {
    $tipo_docuu = 'Ruc';
    $id_tdocu = 1;
}else {
    $tipo_docuu = 'Pasaporte';
    $id_tdocu = 3;
}

/////////////////modificar clientes////////////////////
if (pg_query("Update clientes Set tipo_documento='$tipo_docuu', identificacion='$_POST[ruc_ci]', nombres_cli='".strtoupper($_POST['nombres_cli'])."', tipo_cliente='$_POST[cupo_credito]', direccion_cli='$_POST[direccion_cli]', telefono='$_POST[nro_telefono]', celular='$_POST[nro_celular]', pais='".strtoupper($_POST['pais_cli'])."', ciudad='".strtoupper($_POST['ciudad_cli'])."' ,correo='$_POST[email]', credito_cupo='$_POST[id_ruta]', notas='$_POST[notas_cli]', estado='Activo',id_tdocu='$id_tdocu' where id_cliente='$_POST[id_cliente]'")){
$data = 1;
 // Auditoria
    insert_registro('MODIFICACION CLIENTE: ' . $_POST['nombres_cli'] . ' CON RUC/CI: ' . $_POST['ruc_ci']);
}
//////////////////////////////////////////////////////

echo $data;
?>
