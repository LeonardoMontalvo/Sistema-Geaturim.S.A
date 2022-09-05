<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$data = 0;
$cont = 0;

$consulta = pg_query("select * from rutas where  nombre_ruta='$_POST[nombre_ruta]' and estado = 'Activo'");
while ($row = pg_fetch_row($consulta)) {
    $cont++;
}

if ($cont == 0) {
    $data = 0;
} else {
    $data = 1;
}
echo $data;
?>