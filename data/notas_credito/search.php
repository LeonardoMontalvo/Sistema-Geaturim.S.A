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
        productos P 
        where D.cod_productos = P.cod_productos 
        and D.id_factura_venta = F.id_factura_venta 
        and F.id_factura_venta='$_GET[ids]' 
        and (P.cod_barras='$codigo_barras'  or P.codigo='$codigo')  
        and P. estado='Activo'";

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
                where (P.cod_barras='$codigo_barras'  or P.codigo='$codigo')  and P. estado='Activo'";
        }
        $consulta = pg_query($sql);
        while ($row = pg_fetch_row($consulta)) {

                $arr_data[] = $row[0];
                $arr_data[] = $row[1];
                $arr_data[] = strtoupper($row[2]);
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
                $arr_data[] = $row[15];
                $arr_data[] = $row[16];
        }
}
echo json_encode($arr_data);
