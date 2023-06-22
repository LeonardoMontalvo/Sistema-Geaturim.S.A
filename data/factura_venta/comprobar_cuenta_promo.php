<?php
session_start();
include '../../procesos/base.php';
conectarse();
$data = 0;
error_reporting(0);

//echo '' ."  select porcentaje_promocion,p.cod_productos,c.id_categoria,fecha_desde,fecha_hasta from productos p 
//  inner join categoria c on p.id_categoria=c.id_categoria 
//  inner join promocion_venta  pv on  pv.id_categoria=c.id_categoria 
//  where p.cod_productos='$_GET[prod]'  and ('$_GET[fecha_actual]' >= fecha_desde and '$_GET[fecha_actual]' <=fecha_hasta) ";
$consulta = pg_query("  select porcentaje_promocion,p.cod_productos,c.id_categoria,fecha_desde,fecha_hasta from productos p 
  inner join categoria c on p.id_categoria=c.id_categoria 
  inner join promocion_venta  pv on  pv.id_categoria=c.id_categoria 
  where p.cod_productos='$_GET[prod]'  and ('$_GET[fecha_actual]' >= fecha_desde and '$_GET[fecha_actual]' <=fecha_hasta) ");

while ($row = pg_fetch_row($consulta)) {
    $arr_data[] = $row[0]; //PORCENTAJE
    $arr_data[] = $row[1]; //COD PRODUCTOS
    $arr_data[] = $row[2]; //ID CATEGORIA
    $arr_data[] = $row[3]; //FECHA DESDE
    $arr_data[] = $row[4]; //FECHA HASTA
}
////////////////////////////////
echo json_encode($arr_data);
