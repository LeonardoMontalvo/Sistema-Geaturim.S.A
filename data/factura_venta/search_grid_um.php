<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$codigo_barras = $_GET["codigo_barras"];
//$codigo = $_GET["cod"];
$codigo = $codigo_barras;
$precio = $_GET["precio"];
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
  $consulta = pg_query("select * from productos where cod_barras = '$codigo_barras' or codigo = '$codigo' and estado = 'Activo'");
  while ($row1 = pg_fetch_row($consulta)) {

    $consulta1 = pg_query(" select pvpmayo_cantidad,pvpnego_cantidad,codigo,articulo,pvpmino,dpb.stock,iva,series,p.cod_productos,descuento,inventariable,incluye_iva,pvpmayo_cantidad,pvpmayo_cantidad,pvpmayo,
pvpnego_cantidad,pvpnego,pvpnego_cantidad

 from productos p 
left join unidad_medida_productos ump on p.cod_productos=ump.cod_productos 
left join unidades_medida um on um.id_unidades=ump.id_unidades 
left join detalle_producto_bodega dpb on p.cod_productos=dpb.cod_productos
  where p.cod_productos=$row1[0] 
  and um.id_unidades=$_GET[unidad_medida] 
  and um.estado='Activo' and ump.estado='Activo' and dpb.id_bodega='$conpuntoresult'  ");
    $row = pg_fetch_assoc($consulta1);
if ($row['pvpmayo_cantidad'] == "") {
    $row['pvpmayo_cantidad'] = "0";//cantidad_mayorista
}
if ( $row['pvpnego_cantidad'] == "") {
     $row['pvpnego_cantidad'] = "0";//cantidad_negocio
}

    if ($precio == "MINORISTA") {
      $arr_data[] = $row['codigo'];//codigo
      $arr_data[] = $row['articulo'];//articulo
      $arr_data[] = $row['pvpmino'];//iva_minorista
      if ($row['iva']== 'Si') {//iva
        $arr_data[] = floatval($row['pvpmino']) ;//iva_minorista
      } else {
        $arr_data[] = floatval($row['pvpmino']);//iva_minorista
      }
      $arr_data[] = $row['stock'];//stock
      $arr_data[] = $row['iva'];//iva
      $arr_data[] = $row['series'];//series
      $arr_data[] = $row['cod_productos'];//cod_productos
      $arr_data[] = $row['descuento'];//descuento
      $arr_data[] = $row['inventariable'];//inventariable
      $arr_data[] = $row['incluye_iva'];//incluye_iva
      $arr_data[] = $row['pvpmayo_cantidad'];//cantidad_minorista
    } elseif ($precio == "MAYORISTA") {
      $arr_data[] = $row['codigo'];//codigo
      $arr_data[] = $row['articulo'];//articulo
      if ($row['pvpmino'] == '0' || $row['pvpmayo_cantidad'] == '0') { //iva_mayorista || cantidad_mayorista 
        $arr_data[] = $row['pvpmino'];//iva_minorista
        if ($row['iva'] == 'Si') {//iva
          $arr_data[] = floatval($row['pvpmino']);//iva_minorista
        } else {
          $arr_data[] = floatval($row['pvpmino']);//iva_minorista
        }
      } else {
        $arr_data[] = $row['pvpmayo'];//iva_mayorista
        if ($row['iva'] == 'Si') {//iva
          $arr_data[] = floatval($row['pvpmayo']) ;//iva_mayorista
        } else {
          $arr_data[] = floatval($row['pvpmayo']);//iva_mayorista
        }
      }
      $arr_data[] = $row['stock'];//stock
      $arr_data[] = $row['iva'];//iva
      $arr_data[] = $row['series'];//series
      $arr_data[] = $row['cod_productos'];//cod_productos
      $arr_data[] = $row['descuento'];//descuento
      $arr_data[] = $row['inventariable'];//inventariable
      $arr_data[] = $row['incluye_iva'];//incluye_iva
      $arr_data[] = $row['pvpmayo_cantidad'];//cantidad_mayotista
    } elseif ($precio == "NEGOCIO") {
      $arr_data[] = $row['codigo'];//codigo
      $arr_data[] = $row['articulo'];//articulo
      if ($row['pvpnego'] == '0' || $row['pvpnego_cantidad'] == '0') { // iva_negocio || //cantidad_negocio
        $arr_data[] = $row['pvpmino'];//iva_minorista
        if ($row['iva'] == 'Si') {
          $arr_data[] = floatval($row['pvpmino']);//iva_minorista
        } else {
          $arr_data[] = floatval($row['pvpmino']);//iva_minorista
        }
      } else {
        $arr_data[] = $row['pvpnego'];// iva_negocio
        if ($row['iva']== 'Si') {//iva
          $arr_data[] = floatval($row['pvpnego']) ;// iva_negocio
        } else {
          $arr_data[] = floatval($row['pvpnego']);// iva_negocio
        }
      }
     $arr_data[] = $row['stock'];//stock
      $arr_data[] = $row['iva'];//iva
      $arr_data[] = $row['series'];//series
      $arr_data[] = $row['cod_productos'];//cod_productos
      $arr_data[] = $row['descuento'];//descuento
      $arr_data[] = $row['inventariable'];//inventariable
      $arr_data[] = $row['incluye_iva'];//incluye_iva
      $arr_data[] = $row['pvpnego_cantidad'];//cantidad_negocio
    }
  }
}
echo json_encode($arr_data);
