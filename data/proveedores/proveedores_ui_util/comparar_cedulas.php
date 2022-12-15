<?php

session_start();
include '../../../procesos/base.php';
conectarse();
error_reporting(0);
$data = 0;
$cont = 0;

$consulta = pg_query("select * from proveedores where id_tdocu = '$_POST[tipo_docu]' and identificacion_pro='$_POST[cedula]' and estado = 'Activo'");
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