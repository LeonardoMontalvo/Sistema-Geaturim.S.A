<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['com'];
$arr_data = array();

$conpunto = 1;
$consultapunto = pg_query("select max(id_punto_venta_empresa) from punto_venta_empresa where punto_venta_empresa.id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($consultapunto)) {
    $conpunto = $row[0];
}
$conpuntoresult = 1;
$consultapuntoresult = pg_query("select id_punto_venta from punto_venta_empresa where id_punto_venta_empresa='$conpunto'");
while ($row = pg_fetch_row($consultapuntoresult)) {
    $conpuntoresult = $row[0];
}
$consulta = pg_query("
select DISTINCT ON (D.cod_productos) D.cod_productos, P.codigo, P.articulo, D.cantidad, D.precio_compra, D.descuento_producto, D.total_compra, P.iva, P.incluye_iva,D.cantidad_unidad,D.unidad_medida,d.fecha_emision,campo_dijitar
,nombre,cc.id_centro_costo
 from factura_compra F INNER JOIN  detalle_factura_compra D ON  F.id_factura_compra = D.id_factura_compra 
 INNER JOIN  productos P ON  D.cod_productos = P.cod_productos  
 INNER JOIN detalle_centro_costos dcc  ON  D.id_detalle_compra=dcc.id_documento 
 INNER JOIN centro_costos cc ON  cc.id_centro_costo=dcc.id_centro_costo where      F.id_empresa='$conpuntoresult' and D.id_factura_compra='" . $id . "'
");
while ($row = pg_fetch_row($consulta)) {
    $arr_data[] = $row[0];
    $arr_data[] = $row[1];
    $arr_data[] = $row[2];
    $arr_data[] = $row[3];
    $arr_data[] = $row[4];
    $arr_data[] = $row[5];
    $arr_data[] = $row[6];
    $arr_data[] = $row[7];
    $arr_data[] = $row[8];
    $arr_data[] = $row[9];
    $arr_data[] = $row[10];

    $arr_data[] = $row[11];
    $arr_data[] = $row[12];


    $arr_data[] = $row[13];
    $arr_data[] = $row[14];
}
echo json_encode($arr_data);
?>
