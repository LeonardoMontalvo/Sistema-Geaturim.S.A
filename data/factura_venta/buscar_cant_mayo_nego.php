<?php

session_start();
include '../../procesos/base.php';
conectarse();
$conpunto = 1;
$consultapunto = pg_query("select max(id_punto_venta_empresa) from punto_venta_empresa where punto_venta_empresa.id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($consultapunto)) {
    $conpunto = $row[0];
}
$data = "";
$conpuntoresult = 1;
$consultapuntoresult = pg_query("select id_punto_venta from punto_venta_empresa where id_punto_venta_empresa='$conpunto'");
while ($row = pg_fetch_row($consultapuntoresult)) {
    $conpuntoresult = $row[0];
}
//echo '' ."select cod_productos,cantidad_mayorista,cantidad_negocio from productos where codigo='$_POST[id]'"; 
$consulta = pg_query("select cod_productos,cantidad_mayorista,cantidad_negocio from productos where codigo='$_POST[id]'");


while ($row = pg_fetch_row($consulta)) {
    $data = $data . $row[0];
    $data = $data . ',' . $row[1];
    $data = $data . ',' . $row[2];
}
////////////////////////////////
echo json_encode($data);
?>





