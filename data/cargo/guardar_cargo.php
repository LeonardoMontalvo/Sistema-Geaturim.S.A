<?php

session_start();
include '../../procesos/base.php';
conectarse();
//error_reporting(0);
///////////////////contador clientes////////////////////////
$cont = 0;
$consulta = pg_query("select max(id_cargo) from cargo");
while ($row = pg_fetch_row($consulta)) {
    $cont = $row[0];
}
$cont++;
/////////////////////////////////////////////////////////
$nombre_cargo = '0';

$consulta1 = pg_query("select nombre_cargo from cargo where nombre_cargo='$_POST[nombre_cargo]' and estado='Activo'");
while ($row = pg_fetch_row($consulta1)) {
    $nombre_cargo = $row[0];
}

if ($nombre_cargo == '0') {
//print_r($nombre_cargo);
    if (pg_query("insert into cargo values('$cont','$_POST[nombre_cargo]','$_POST[sueldo_base]','Activo','$_POST[codigo_sectorial]')")) {
        $data = 1;
    }
} else {
    $data = 11;
}
echo $data;
?>
