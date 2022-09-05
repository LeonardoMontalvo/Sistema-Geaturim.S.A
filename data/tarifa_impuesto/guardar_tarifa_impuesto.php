<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

///////////////////contador clientes////////////////////////
$cont = 0;
$consulta = pg_query("select max(id_taimpuesto) from tarifa_impuesto");
while ($row = pg_fetch_row($consulta)) {
    $cont = $row[0];
}
$cont++;
/////////////////////////////////////////////////////////

if (pg_query("insert into tarifa_impuesto values('$cont','$_POST[id_timpu]','$_POST[codigo_taimpuesto]','$_POST[nombre_taimpuesto]','$_POST[descripcion_taimpuesto]')")) {
    $data = 1;
}

echo $data;
?>
