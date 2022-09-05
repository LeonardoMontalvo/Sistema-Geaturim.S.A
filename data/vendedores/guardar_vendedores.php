<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
error_reporting(0);

$cont = 0;
$consulta = pg_query("select max(id_vendedor) from vendedores");
while ($row = pg_fetch_row($consulta)) {
    $cont = $row[0];
}
$cont++;

pg_query("insert into vendedores values('$cont','".strtoupper($_POST[nombre])."','$_POST[ruc_ci]','$_POST[nro_telefono]','$_POST[nro_celular]','$_POST[correo]','$_POST[direccion_vend]','Activo')");
$data = 1;
// Auditoria
insert_registro('CREACION VENDEDOR: ' . $_POST['nombre'] . ' CON CI: ' . $_POST['ruc_ci']);
echo $data;
?>
