<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$data = 0;
$cont = 0;
//echo 'hh'."select * from promociones,productos where promociones.cod_productos=productos.cod_productos and productos.cod_productos='$_POST[codigo]' and promociones.cod_productos_promo='$_POST[id_promocion_pro]' and promociones.estado = 'Activo'   ";

$consulta = pg_query("select * from promociones,productos where promociones.cod_productos=productos.cod_productos and productos.cod_productos='$_POST[codigo]' and promociones.cod_productos_promo='$_POST[id_promocion_pro]' and promociones.estado = 'Activo'  ");
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