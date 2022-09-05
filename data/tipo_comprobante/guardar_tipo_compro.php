<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

///////////////////contador clientes////////////////////////
$cont = 0;
$consulta = pg_query("select max(id_tipo_comprobante) from tipo_comprobante");
while ($row = pg_fetch_row($consulta)) {
    $cont = $row[0];
}
$cont++;
/////////////////////////////////////////////////////////

if (pg_query("insert into tipo_comprobante values('$cont','$_POST[descripcion]','$_POST[abreviatura]','Activo','$_POST[codigo]')")) {
    $data = 1;
}

echo $data;
?>
