<?php

session_start();
include '../../procesos/base.php';
conectarse();

$consulta = pg_query("SELECT id_servicio,productos.cod_productos,articulo, monto_m3, monto_fijo, rango_min, rango_max FROM rubro, productos where rubro.id_servicio=productos.cod_productos  and rubro.id_clase='$_GET[id_clase]'");
$row = pg_fetch_row($consulta);
echo $row[0];
?>
