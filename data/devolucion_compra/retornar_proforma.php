<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['id2'];
$arr_data = array();
if ($_GET["tipo"] == "FACTURA") {

    $consulta = pg_query("
    select 
    P.cod_productos,
    P.codigo,
    P.articulo,
    D.cantidad,
    D.precio_compra,
    D.descuento_producto,
    D.total_compra,
    P.iva,
    P.incluye_iva,
    P.inventariable,
    D.cantidad_unidad,
    D.unidad_medida,
    case when dic.cod_impuesto is null then ti.codigo_timpu else dic.cod_impuesto end cod_impuesto,
    case when dic.cod_tarifa is null then tri.codigo_taimpuesto else dic.cod_tarifa end cod_tarifa,
    case when dic.tarifa is null then tri.valor else dic.tarifa end tarifa,
    dic.valor_impuesto
    from productos P
    inner join tipo_impuesto ti using(id_timpu)
    inner join tarifa_impuesto tri using(id_taimpuesto),
    detalle_factura_compra D
    left join detalle_impuesto_producto_compra dic using(id_detalle_compra),
    factura_compra PR
    where P.cod_productos = D.cod_productos
    and PR.id_factura_compra = D.id_factura_compra
    and PR.estado = 'Activo'
    and D.estado = 'Activo'
    and tipo_comprobante = 'FACTURA'
    and PR.id_factura_compra = '$id'");

    while ($row = pg_fetch_assoc($consulta)) {
        $arr_data[] = $row["cod_productos"];
        $arr_data[] = $row["codigo"];
        $arr_data[] = $row["articulo"];
        $arr_data[] = $row["cantidad"];
        $arr_data[] = $row["cantidad"];
        $arr_data[] = $row["precio_compra"];
        $arr_data[] = $row["descuento_producto"];
        $arr_data[] = $row["total_compra"];
        $arr_data[] = $row["iva"];
        $arr_data[] = $row["incluye_iva"];
        $arr_data[] = $row["inventariable"];
        $arr_data[] = $row["cantidad_unidad"];
        $arr_data[] = $row["unidad_medida"];
        $arr_data[] = $row["cod_impuesto"];
        $arr_data[] = $row["cod_tarifa"];
        $arr_data[] = $row["tarifa"];
        $arr_data[] = $row["valor_impuesto"];
    }
} else {
    $consulta = pg_query("
    select P.cod_productos,
    P.codigo,
    P.articulo,
    D.cantidad,
    D.precio_compra,
    D.descuento_producto,
    D.total_compra,
    P.iva,
    P.incluye_iva,
    P.inventariable,
    D.cantidad_unidad,
    case when dic.cod_impuesto is null then ti.codigo_timpu else dic.cod_impuesto end cod_impuesto,
    case when dic.cod_tarifa is null then tri.codigo_taimpuesto else dic.cod_tarifa end cod_tarifa,
    case when dic.tarifa is null then tri.valor else dic.tarifa end tarifa,
    dic.valor_impuesto
    from productos P
    inner join tipo_impuesto ti using(id_timpu)
    inner join tarifa_impuesto tri using(id_taimpuesto),
    detalle_factura_compra D
    left join detalle_impuesto_producto_compra dic using(id_detalle_compra),
    factura_compra PR
    where P.cod_productos = D.cod_productos
    and PR.id_factura_compra = D.id_factura_compra
    and PR.estado = 'Activo'
    and D.estado = 'Activo'
    and tipo_comprobante = 'NOTA VENTA'
    and PR.id_factura_compra = '$id'");
    while ($row = pg_fetch_assoc($consulta)) {
        $arr_data[] = $row["cod_productos"];
        $arr_data[] = $row["codigo"];
        $arr_data[] = $row["articulo"];
        $arr_data[] = $row["cantidad"];
        $arr_data[] = $row["cantidad"];
        $arr_data[] = $row["precio_compra"];
        $arr_data[] = $row["descuento_producto"];
        $arr_data[] = $row["total_compra"];
        $arr_data[] = $row["iva"];
        $arr_data[] = $row["incluye_iva"];
        $arr_data[] = $row["inventariable"];
        $arr_data[] = $row["cantidad_unidad"];
        $arr_data[] = $row["unidad_medida"];
        $arr_data[] = $row["cod_impuesto"];
        $arr_data[] = $row["cod_tarifa"];
        $arr_data[] = $row["tarifa"];
        $arr_data[] = $row["valor_impuesto"];
    }
}
echo json_encode($arr_data);
