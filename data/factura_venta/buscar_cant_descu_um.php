<?php

session_start();
include '../../procesos/base.php';
conectarse();
$conpunto = 1;
error_reporting(1);
$consultapunto = pg_query("select max(id_punto_venta_empresa) from punto_venta_empresa where punto_venta_empresa.id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($consultapunto)) {
    $conpunto = $row[0];
}
$conpuntoresult = 1;
$consultapuntoresult = pg_query("select id_punto_venta from punto_venta_empresa where id_punto_venta_empresa='$conpunto'");
while ($row = pg_fetch_row($consultapuntoresult)) {
    $conpuntoresult = $row[0];
}
$arr_data = array();
$consulta = pg_query("SELECT pvpmayo_cantidad,pvpnego_cantidad
FROM unidad_medida_productos, productos,unidades_medida 
where unidades_medida.id_unidades=unidad_medida_productos.id_unidades 
and unidad_medida_productos.cod_productos=productos.cod_productos 
and productos.cod_productos='$_GET[id_prod]' and unidad_medida_productos.id_unidades='$_GET[unidad_medida]' order by cantidad asc
");

$row = pg_fetch_row($consulta);

if ($row[0] == "") {
    $row[0] = "0";
}
if ($row[1] == "") {
    $row[1] = "0";
}
$arr_data[] = $row[0];
$arr_data[] = $row[1];

echo json_encode($arr_data);
