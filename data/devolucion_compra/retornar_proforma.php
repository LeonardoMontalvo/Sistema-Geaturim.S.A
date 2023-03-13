<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['id2'];
$arr_data = array();
if ($_GET[tipo] == "FACTURA") {

    $consulta = pg_query("select P.cod_productos, P.codigo, P.articulo, D.cantidad, D.cantidad, D.precio_compra, D.descuento_producto, D.total_compra, P.iva, P.incluye_iva, P.inventariable,
D.cantidad_unidad,D.unidad_medida from productos P, detalle_factura_compra D, factura_compra PR where P.cod_productos = D.cod_productos 
and PR.id_factura_compra = D.id_factura_compra and PR.estado ='Activo'  and D.estado= 'Activo' and tipo_comprobante='FACTURA' and PR.id_factura_compra='" . $id . "'");
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
    $consulta = pg_query("select P.cod_productos, P.codigo, P.articulo, D.cantidad, D.cantidad, D.precio_compra, D.descuento_producto, D.total_compra, P.iva, P.incluye_iva, P.inventariable,
D.cantidad_unidad,D.unidad_medida from productos P, detalle_factura_compra D, factura_compra PR where P.cod_productos = D.cod_productos 
and PR.id_factura_compra = D.id_factura_compra and PR.estado ='Activo'  and D.estado= 'Activo' and tipo_comprobante='NOTA VENTA' and PR.id_factura_compra='" . $id . "'");
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
