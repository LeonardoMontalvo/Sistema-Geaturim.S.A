<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
//$codigo_barras = $_GET["codigo_barras"];

$codigo = strtoupper($_GET["cod"]);
$codigo_barras = strtoupper($_GET["codigo_barras"]);
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
if ($codigo_barras != "") {
        $consulta = pg_query("select P.cod_productos,
        P.codigo,
        P.cod_barras,
        P.articulo,
        D.precio_compra,
        D.cantidad,
        D.descuento_producto,
        P.iva,
        P.series,
        D.estado,
        P.incluye_iva,
        D.unidad_medida,
        P.id_taimpuesto
    from factura_compra F,
        detalle_factura_compra D,
        productos P
    where D.cod_productos = P.cod_productos
        and D.id_factura_compra = F.id_factura_compra
        and F.id_factura_compra = '$_GET[ids]'
        and (
            upper(P.cod_barras) = '$codigo_barras'
            or upper(P.codigo) = '$codigo'
        )
        and P.estado = 'Activo'");
        while ($row = pg_fetch_assoc($consulta)) {
                $arr_data[] = $row["cod_productos"];
                $arr_data[] = $row["codigo"];
                $arr_data[] = strtoupper($row["cod_barras"]);
                $arr_data[] = $row["articulo"];
                $arr_data[] = $row["precio_compra"];
                $arr_data[] = $row["cantidad"];
                $arr_data[] = $row["descuento_producto"];
                $arr_data[] = $row["iva"];
                $arr_data[] = $row["series"];
                $arr_data[] = $row["estado"];
                $arr_data[] = $row["incluye_iva"];
                $arr_data[] = $row["unidad_medida"];
                $arr_data[] = $row["id_taimpuesto"];
        }
}
echo json_encode($arr_data);
