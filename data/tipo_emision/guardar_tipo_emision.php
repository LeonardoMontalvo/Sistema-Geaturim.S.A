<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

///////////////////contador clientes////////////////////////
$cont = 0;
$consulta = pg_query("select max(id_temision) from tipo_emision");
while ($row = pg_fetch_row($consulta)) {
    $cont = $row[0];
}
$cont++;
/////////////////////////////////////////////////////////

if (pg_query("insert into tipo_emision values('$cont','$_POST[nombre_temision]','$_POST[codigo_temision]','$_POST[estado_tipo_emision]')")) {
    $data = 1;
}

echo $data;
?>
