<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['id2'];
$arr_data = array();
if ($_GET[tipo] == "FACTURA") {
    $consulta = pg_query("select P.cod_productos, P.codigo, P.articulo, D.cantidad, D.cantidad, D.precio_venta, D.descuento_producto, D.total_venta, P.iva, P.incluye_iva, P.inventariable,D.cantidad_unidad,D.unidad_medida from productos P, detalle_factura_venta D, factura_venta PR where P.cod_productos = D.cod_productos and PR.id_factura_venta = D.id_factura_venta and PR.estado ='Activo'  and D.estado= 'Activo' and PR.id_factura_venta='" . $id . "'");
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
    }
} else {
    $consulta = pg_query("select P.cod_productos, P.codigo, P.articulo, D.cantidad, D.cantidad, D.precio_venta, D.descuento_producto,
 D.total_venta, P.iva, P.incluye_iva, P.inventariable,D.cantidad_unidad,D.unidad_medida from productos P, detalle_facturas_novalidas D, facturas_novalidas PR 
 where P.cod_productos = D.cod_productos and PR.id_facturas_novalidas = D.id_facturas_novalidas and PR.estado ='Activo'  and D.estado= 'Activo' and PR.id_facturas_novalidas='" . $id . "'");
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
    }
}
echo json_encode($arr_data);
?>
