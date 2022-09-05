<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

///////////////////contador clientes////////////////////////
$cont = 0;
$consulta = pg_query("select max(id_tdocu) from tipo_documento");
while ($row = pg_fetch_row($consulta)) {
    $cont = $row[0];
}
$cont++;
/////////////////////////////////////////////////////////

if (pg_query("insert into tipo_documento values('$cont','$_POST[nombre_tdocu]','$_POST[codigo_tdocu]','Activo')")) {
    $data = 1;
}

echo $data;
?>
