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
$consulta = pg_query("select 
                     D.cod_productos,
                     P.codigo,
                     P.articulo,
                     D.cantidad,
                     D.precio_venta,
                     D.descuento_producto,
                     D.total_venta,
                     P.iva,
                     P.incluye_iva,
                     D.cantidad_unidad,
                     D.unidad_medida,
                     di.tarifa,
                     di.valor_impuesto,
                     di.cod_impuesto,
                     di.cod_tarifa,
                     di.base_imponible 
                     from devolucion_venta V, 
                     detalle_devolucion_venta D
                     left join detalle_impuesto_producto_dev_venta di
                     using(id_detalle_deventa),
                     productos P 
                     where D.cod_productos = P.cod_productos 
                     and V.id_devolucion_venta = D.id_devolucion_venta 
                     and D.id_devolucion_venta='" . $id . "' 
                     and  V.id_empresa='$conpuntoresult'");

while ($row = pg_fetch_assoc($consulta)) {
       $arr_data[] = $row["cod_productos"];
       $arr_data[] = $row["codigo"];
       $arr_data[] = $row["articulo"];
       $arr_data[] = $row["cantidad"];
       $arr_data[] = $row["precio_venta"];
       $arr_data[] = $row["descuento_producto"];
       $arr_data[] = $row["total_venta"];
       $arr_data[] = $row["iva"];
       $arr_data[] = $row["incluye_iva"];
       $arr_data[] = $row["cantidad_unidad"];
       $arr_data[] = $row["unidad_medida"];
       $arr_data[] = $row["tarifa"];
       $arr_data[] = $row["valor_impuesto"];
       $arr_data[] = $row["cod_impuesto"];
       $arr_data[] = $row["cod_tarifa"];
       $arr_data[] = $row["base_imponible"];
}
echo json_encode($arr_data);
