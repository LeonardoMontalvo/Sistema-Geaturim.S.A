<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['com'];
$arr_data = array();



$consulta = pg_query("SELECT denominacion_cien, cantidad_cien, valor_cien, total_cantidad_cien, 
       denominacion_cincuenta, cantidad_cincuenta, valor_cincuenta,total_cantidad_cincuenta,
       denominacion_veinte, cantidad_veinte,  valor_veinte, total_cantidad_veinte, 
       denominacion_diez, cantidad_diez, valor_diez, total_cantidad_diez, 
       denominacion_cinco, cantidad_cinco,  valor_cinco, total_cantidad_cinco,
       denominacion_uno, cantidad_uno,  valor_uno, total_cantidad_uno, 
       denominacion_cero_cincuenta, cantidad_cero_cincuenta,  valor_cero_cincuenta, total_cantidad_cero_cincuenta,
       denominacion_cero_veinticinco,  cantidad_cero_veinticinco, valor_cero_veinticinco, total_cantidad_cero_veinticinco,        
       denominacion_cero_diez,   cantidad_cero_diez, valor_cero_diez,  total_cantidad_cero_diez, 
       denominacion_cero_cinco, cantidad_cero_cinco,  valor_cero_cinco, total_cantidad_cero_cinco, 
       denominacion_cero_uno,  cantidad_cero_uno, valor_cero_uno, total_cantidad_cero_uno,
       total_valor_ingresado, totales_dierio_caja, observacion_cierre, estado,monto_apertura, valor_transferencia
  FROM cierre_caja cc where  comprobante= '" . $id . "' ");
while ($row = pg_fetch_row($consulta)) {
  $arr_data[] = $row[0];
  $arr_data[] = $row[1];
  $arr_data[] = $row[2];
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
  $arr_data[] = $row[17];
  $arr_data[] = $row[18];
  $arr_data[] = $row[19];

  $arr_data[] = $row[20];
  $arr_data[] = $row[21];
  $arr_data[] = $row[22];
  $arr_data[] = $row[23];

  $arr_data[] = $row[24];
  $arr_data[] = $row[25];
  $arr_data[] = $row[26];
  $arr_data[] = $row[27];

  $arr_data[] = $row[28];
  $arr_data[] = $row[29];
  $arr_data[] = $row[30];
  $arr_data[] = $row[31];

  $arr_data[] = $row[32];
  $arr_data[] = $row[33];
  $arr_data[] = $row[34];
  $arr_data[] = $row[35];

  $arr_data[] = $row[36];
  $arr_data[] = $row[37];
  $arr_data[] = $row[38];
  $arr_data[] = $row[39];

  $arr_data[] = $row[40];
  $arr_data[] = $row[41];
  $arr_data[] = $row[42];
  $arr_data[] = $row[43];

  $arr_data[] = $row[44];
  $arr_data[] = $row[45];
  $arr_data[] = $row[46];
  $arr_data[] = $row[47];
  $arr_data[] = $row[48];
  $arr_data[] = $row[49];
}
echo json_encode($arr_data);
