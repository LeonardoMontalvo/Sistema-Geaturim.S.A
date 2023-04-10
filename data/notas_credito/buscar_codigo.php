<?php

include '../../procesos/base.php';
conectarse();
$texto2 = $_GET['term'];
$conpunto = 1;

$sql = "select P.cod_productos, P.codigo, P.cod_barras, P.articulo, D.precio_venta, D.cantidad, D.descuento_producto, P.iva, P.series, D.estado, P.incluye_iva, D.unidad_medida from factura_venta F, detalle_factura_venta D, productos P where D.cod_productos = P.cod_productos and D.id_factura_venta = F.id_factura_venta and F.id_factura_venta='$_GET[ids]' and P.codigo like '%$texto2%' and P.estado='Activo'";

if ($_GET["descuento"] == 1) {
    $sql = "select 
    P.cod_productos, 
    P.codigo, 
    P.cod_barras, 
    P.articulo, 
    P.iva_minorista, 
    0 cantidad, 
    0 descuento_producto, 
    P.iva, 
    P.series, 
    'Activo' estado, 
    P.incluye_iva, 
    '' unidad_medida 
    from productos P 
    where P.codigo like '%$texto2%' and P.estado='Activo'";
}
$consulta = pg_query($sql);
while ($row = pg_fetch_row($consulta)) {

    $data[] = array(
        'cod_producto' => $row[0],
        'value' => $row[1],
        'codigo_barras' => $row[2],
        'producto' => $row[3],
        'precio' => $row[4],
        'canti' => $row[5],
        'descuento' => $row[6],
        'iva_producto' => $row[7],
        'carga_series' => $row[8],
        'estado' => $row[9],
        'incluye' => $row[10],
        'unidad_medida' => $row[11]
    );
}

echo $data = json_encode($data);
