<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
//$codigo_barras = $_GET["codigo_barras"];
$codigo=strtoupper($_GET["cod"]);
$codigo_barras=strtoupper($_GET["codigo_barras"]);  
$puntov=$_SESSION["PV"];
$arr_data = array();

if ($codigo_barras != "") {
   $consulta=pg_query("
   select p.*, coalesce(dpb.stock,0) stock_bodega from productos p
   left join detalle_producto_bodega dpb 
   on p.cod_productos=dpb.cod_productos
   and dpb.id_bodega=$puntov 
   where (cod_barras = '$codigo_barras' or codigo='$codigo') and estado = 'Activo'");
  while($row=pg_fetch_assoc($consulta))
   { 
        $arr_data[] = strtoupper($row["codigo"]);
        $arr_data[] = $row["articulo"];
        $arr_data[] = $row["precio_compra"];
        $arr_data[] = $row["id_taimpuesto"];
        $arr_data[] = $row["precio_compra"];
        $arr_data[] = $row["cod_productos"];
        $arr_data[] = $row["incluye_iva"];
        $arr_data[] = $row["iva_minorista"];
        $arr_data[] = $row["stock_bodega"];
    }
}
echo json_encode($arr_data);
?>