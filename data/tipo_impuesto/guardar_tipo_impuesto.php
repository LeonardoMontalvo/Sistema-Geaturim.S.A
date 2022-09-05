<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

///////////////////contador clientes////////////////////////
$cont = 0;
$consulta = pg_query("select max(id_timpu) from tipo_impuesto");
while ($row = pg_fetch_row($consulta)) {
    $cont = $row[0];
}
$cont++;
/////////////////////////////////////////////////////////

if (pg_query("insert into tipo_impuesto values('$cont','$_POST[nombre_timpu]','$_POST[codigo_timpu]','Activo')")) {
    $data = 1;
}

echo $data;
?>
