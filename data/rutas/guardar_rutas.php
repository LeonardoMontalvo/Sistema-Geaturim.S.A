<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
error_reporting(0);

$cont = 0;
$consulta = pg_query("select max(id_ruta) from rutas");
while ($row = pg_fetch_row($consulta)) {
    $cont = $row[0];
}
$cont++;

pg_query("insert into rutas values('$cont','$_POST[nombre_ruta]','".strtoupper($_POST[nombre])."','$_POST[id_vendedor]','Activo')");
// Auditoria
insert_registro('CREACION RUTA: ' . $_POST['nombre_ruta'] . ' CON ID VENDEDOR: ' . $_POST['id_vendedor']);
$data = 1;
echo $data;
?>
