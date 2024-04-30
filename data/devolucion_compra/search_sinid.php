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
        P.iva_minorista,
        P.descuento,
        P.iva,
        P.series,
        P.estado,
        P.incluye_iva,
        P.id_taimpuesto,
        dpb.stock
    from productos P
    left join detalle_producto_bodega dpb using(cod_productos)
    where (UPPER(P.cod_barras) = '$codigo_barras' or UPPER(P.codigo) = '$codigo') and P.estado = 'Activo' 
    and dpb.id_bodega=1");
        while ($row = pg_fetch_assoc($consulta)) {
                $arr_data[] = $row["cod_productos"];
                $arr_data[] = $row["codigo"];
                $arr_data[] = strtoupper($row["cod_barras"]);
                $arr_data[] = $row["articulo"];
                $arr_data[] = $row["iva_minorista"];
                $arr_data[] = $row["stock"];
                $arr_data[] = "";
                $arr_data[] = $row["iva"];
                $arr_data[] = $row["series"];
                $arr_data[] = $row["estado"];
                $arr_data[] = $row["incluye_iva"];
                $arr_data[] = $row["id_taimpuesto"];
        }
}
echo json_encode($arr_data);
