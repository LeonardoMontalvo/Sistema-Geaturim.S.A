<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
error_reporting(0);
///////////////////contador clientes////////////////////////
$cont = 0;
$valorid = 0;

$consulta = pg_query("select max(id_cliente) from clientes");
while ($row = pg_fetch_row($consulta)) {
    $cont = $row[0];
}
$cont++;
/////////////////////////////////////////////////////////

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
if($_POST[id_ruta]=="")
{
  $_POST[id_ruta]="1";  
}else {
  $_POST[id_ruta]=$_POST[id_ruta];  
}
if (pg_query("insert into clientes values('$cont','$tipo','$_POST[ruc_ci]','" . strtoupper($_POST['nombres_cli']) . "','$_POST[cupo_credito]','$_POST[direccion_cli]','$_POST[nro_telefono]','$_POST[nro_celular]','" . strtoupper($_POST['pais_cli']) . "','" . strtoupper($_POST['ciudad_cli']) . "','$_POST[email]','$_POST[id_ruta]','$_POST[notas_cli]','Activo','1','$_POST[tipo_docu]')")) {
    $data = 1;
    // Auditoria
    insert_registro('CREACION CLIENTE: ' . $_POST['nombres_cli'] . ' CON RUC/CI: ' . $_POST['ruc_ci']);
}
echo $data;
?>
