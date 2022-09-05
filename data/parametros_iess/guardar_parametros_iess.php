<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

///////////////////contador clientes////////////////////////
$cont = 0;
$consulta = pg_query("select max(id_parametro_iess) from parametros_iess");
while ($row = pg_fetch_row($consulta)) {
    $cont = $row[0];
}
$cont++;
/////////////////////////////////////////////////////////
$nombre_parametro = '0';

$consulta1 = pg_query("select descripcion from parametros_iess where descripcion='$_POST[descripcion_iess]' and estado='Activo'");
while ($row = pg_fetch_row($consulta1)) {
    $nombre_parametro = $row[0];
}
if ($nombre_cargo == '0') {
if (pg_query("insert into parametros_iess values('$cont','$_POST[descripcion_iess]','$_POST[valor_aporte]','Activo')")) {
    $data = 1;
}
} else {
    $data = 11;
}
echo $data;
?>
