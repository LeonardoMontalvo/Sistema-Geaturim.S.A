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
        $consulta = pg_query("select P.cod_productos, P.codigo, P.cod_barras, P.articulo, D.precio_venta, D.cantidad, D.descuento_producto, P.iva, P.series, D.estado, P.incluye_iva, D.unidad_medida from factura_venta F, detalle_factura_venta D, productos P where D.cod_productos = P.cod_productos and D.id_factura_venta = F.id_factura_venta and F.id_factura_venta='$_GET[ids]' and (P.cod_barras='$codigo_barras'  or P.codigo='$codigo')  and P. estado='Activo'");
        while ($row = pg_fetch_row($consulta)) {

                $consulta1 = pg_query("select * from   productos p left join detalle_producto_bodega dpb on p.cod_productos=dpb.cod_productos where p.cod_productos=$row[0] and dpb.id_bodega=$conpuntoresult ");
                $row1 = pg_fetch_row($consulta1);

                $arr_data[] = $row[0];
                $arr_data[] = $row[1];
                $arr_data[] = strtoupper($row[2]);
                $arr_data[] = $row[3];
                $arr_data[] = $row[4];
                $arr_data[] = $row[5];
                $arr_data[] = $row1[39];
                $arr_data[] = $row[7];
                $arr_data[] = $row[8];
                $arr_data[] = $row[9];
                $arr_data[] = $row[10];
                $arr_data[] = $row[11];
        }
}
echo json_encode($arr_data);
