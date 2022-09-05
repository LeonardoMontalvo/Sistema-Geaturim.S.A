<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$data = 0;
$cont = 0;

$consulta = pg_query("select * from retencion_fuente_factura_compra where num_serie ='$_POST[num_reten]' where  estado = 'g'");
while ($row = pg_fetch_row($consulta)) {
    $cont++;
}


if ($cont == 0) {
    $data = 0;
} else {
    $consulta = pg_query("select max(num_serie) from retencion_fuente_factura_compra where estado = 'g' ");
	while ($row = pg_fetch_row($consulta)) {
	    $data = $row[0];

	}
}
echo $data;
?>