<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
//$codigo_barras = $_GET["codigo_barras"];
$codigo_barras=strtoupper($_GET["codigo_barras"]); 
$codigo=strtoupper($_GET["cod"]);
$arr_data = array();
$conpunto = 1;
$consultapunto = pg_query("select max(id_punto_venta_empresa) from punto_venta_empresa where punto_venta_empresa.id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($consultapunto)) {
    $conpunto = $row[0];
}
$conpuntoresult = 1;
$consultapuntoresult = pg_query("select id_punto_venta from punto_venta_empresa where id_punto_venta_empresa='$conpunto' ");
while ($row = pg_fetch_row($consultapuntoresult)) {
    $conpuntoresult = $row[0];
}
if ($codigo_barras != "") {
    $consulta = pg_query("select * from productos where estado='Activo' and (cod_barras =  '$codigo_barras' or codigo='$codigo')");
    while ($row = pg_fetch_assoc($consulta)) {
        $consulta1 = pg_query("SELECT dpb.stock,dpb.hora FROM productos p left join detalle_producto_bodega dpb on p.cod_productos=dpb.cod_productos "
                . "where p.cod_productos=$row[cod_productos] and dpb.id_bodega=$conpuntoresult");
        $row1 = pg_fetch_assoc($consulta1);
        $arr_data[] = strtoupper($row['codigo']);
        $arr_data[] = $row['articulo'];
        $arr_data[] = $row['precio_compra'];
        $arr_data[] = number_format($row1['stock'], 2, '.', '');
        $arr_data[] = $row['iva_minorista'];
        $arr_data[] = $row['existencia'];
        $arr_data[] = $row['diferencia'];
        $arr_data[] = $row['cod_productos'];
        //$arr_data[] = $row[41];
        $arr_data[] = $row['hora']; //row41
    }
}
echo json_encode($arr_data);
?>