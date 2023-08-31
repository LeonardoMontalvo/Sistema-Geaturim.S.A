<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
error_reporting(0);
$var=$_POST[ruc_ci];

if ($_POST[tipo_docu] == '1') {
    $tipo = 'Ruc';
} else {
    if ($_POST[tipo_docu] == '2') {
        $tipo = 'Cedula';
    } else {
        if ($_POST[tipo_docu] == '3') {
            $tipo = 'Pasaporte';
        } else {
            if ($_POST[tipo_docu] == '5') {
                $tipo = 'Identificacion del Exterior';
            }
        }
    }
}

/////////////////modificar clientes////////////////////
if (pg_query("Update clientes Set tipo_documento='$tipo', identificacion='$_POST[ruc_ci]', nombres_cli='".strtoupper($_POST['nombres_cli'])."', tipo_cliente='$_POST[cupo_credito]', direccion_cli='$_POST[direccion_cli]', telefono='$_POST[nro_telefono]', celular='$_POST[nro_celular]', pais='".strtoupper($_POST['pais_cli'])."', ciudad='".strtoupper($_POST['ciudad_cli'])."' ,correo='$_POST[email]', credito_cupo='$_POST[id_ruta]', notas='$_POST[notas_cli]', estado='Activo',id_tdocu='$_POST[tipo_docu]' where id_cliente='$_POST[id_cliente]'")){
$data = 1;
 // Auditoria
    insert_registro('MODIFICACION CLIENTE: ' . $_POST['nombres_cli'] . ' CON RUC/CI: ' . $_POST['ruc_ci']);
}
//////////////////////////////////////////////////////

echo $data;
?>
