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

$pvinv = $_SESSION['PV_INV'];

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
  while ($row = pg_fetch_row($consulta)) {

    $consulta1 = pg_query("select * from productos p left join detalle_producto_bodega dpb on p.cod_productos=dpb.cod_productos where p.cod_productos=$row[0] and dpb.id_bodega=$pvinv ");
    $row1 = pg_fetch_row($consulta1);
if ($row[38] == "") {
    $row[38] = "0";
}
if ($row[39] == "") {
    $row[39] = "0";
}

    if ($precio == "MINORISTA") {
      $arr_data[] = $row[1];
      $arr_data[] = $row[3];
      $arr_data[] = $row[9];
      if ($row[4] == 'Si') {
        $arr_data[] = floatval($row[9]) ;
      } else {
        $arr_data[] = floatval($row[9]);
      }
      $arr_data[] = $row1[46];
      $arr_data[] = $row[4];
      $arr_data[] = $row[5];
      $arr_data[] = $row[0];
      $arr_data[] = $row[19];
      $arr_data[] = $row[21];
      $arr_data[] = $row[26];
      $arr_data[] = $row[38];
    } elseif ($precio == "MAYORISTA") {
      $arr_data[] = $row[1];
      $arr_data[] = $row[3];
      if ($row[10] == '0' || $row[38] == '0') { //mayorista y can mayo
        $arr_data[] = $row[9];
        if ($row[4] == 'Si') {
          $arr_data[] = floatval($row[9]);
        } else {
          $arr_data[] = floatval($row[9]);
        }
      } else {
        $arr_data[] = $row[10];
        if ($row[4] == 'Si') {
          $arr_data[] = floatval($row[10]) ;
        } else {
          $arr_data[] = floatval($row[10]);
        }
      }
      $arr_data[] = $row1[46];
      $arr_data[] = $row[4];
      $arr_data[] = $row[5];
      $arr_data[] = $row[0];
      $arr_data[] = $row[19];
      $arr_data[] = $row[21];
      $arr_data[] = $row[26];
      $arr_data[] = $row[38];
    } elseif ($precio == "NEGOCIO") {
      $arr_data[] = $row[1];
      $arr_data[] = $row[3];
      if ($row[27] == '0' || $row[39] == '0') { // negocio y 
        $arr_data[] = $row[9];
        if ($row[4] == 'Si') {
          $arr_data[] = floatval($row[9]);
        } else {
          $arr_data[] = floatval($row[9]);
        }
      } else {
        $arr_data[] = $row[27];
        if ($row[4] == 'Si') {
          $arr_data[] = floatval($row[27]) ;
        } else {
          $arr_data[] = floatval($row[27]);
        }
      }
      $arr_data[] = $row1[46];
      $arr_data[] = $row[4];
      $arr_data[] = $row[5];
      $arr_data[] = $row[0];
      $arr_data[] = $row[19];
      $arr_data[] = $row[21];
      $arr_data[] = $row[26];
      $arr_data[] = $row[39];
    }
  }
}
echo json_encode($arr_data);
