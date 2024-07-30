<?php

include '../../procesos/base.php';
conectarse();
$texto2 = $_GET['term'];
$texto2 = str_replace(" ", "%", $texto2);
$sql = "select 
P.cod_productos,
P.codigo,
P.cod_barras,
P.articulo,
D.precio_venta,
D.cantidad,
D.descuento_producto,
P.iva,
P.series,
D.estado,
P.incluye_iva,
D.unidad_medida,
di.cod_impuesto,
di.cod_tarifa,
di.tarifa,
di.valor_impuesto,
di.base_imponible  
from factura_venta F,
detalle_factura_venta D
left join detalle_impuesto_producto_venta di
using (id_detalle_venta),
productos P where D.cod_productos = P.cod_productos 
and D.id_factura_venta = F.id_factura_venta 
and F.id_factura_venta='$_GET[ids]' 
and articulo ilike '%$texto2%' 
and P.estado='Activo'";

if ($_GET['descuento'] == 1) {
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
            '' unidad_medida,
            ti.codigo_timpu cod_impuesto,
            tai.codigo_taimpuesto cod_tarifa,
            tai.valor tarifa,
            0 valor_impuesto,
            0 base_imponible 
            from productos P
            inner join tipo_impuesto ti
            using(id_timpu)
            inner join tarifa_impuesto tai
            using(id_taimpuesto) 
            where articulo ilike '%$texto2%' and P.estado='Activo'";
}
$consulta = pg_query($sql);
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'value' => $row[3],
        'codigo_barras' => $row[2],
        'codigo' => $row[1],
        'precio' => $row[4],
        'canti' => $row[5],
        'descuento' => $row[6],
        'iva_producto' => $row[7],
        'carga_series' => $row[8],
        'estado' => $row[9],
        'cod_producto' => $row[0],
        'incluye' => $row[10],
        'unidad_medida' => $row[11],
        'cod_impuesto' => $row[12],
        'cod_tarifa' => $row[13],
        'tarifa' => $row[14],
        'valor_impuesto' => $row[15],
        'base_imponible' => $row[16]
    );
}

echo $data = json_encode($data);
