<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['com'];
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
$consulta = pg_query("select E.fecha_actual, E.hora_actual, U.nombre_usuario, U.apellido_usuario, E.origen, E.destino, E.observaciones, E.tarifa0, E.tarifa12, E.iva_egreso, E.descuento_egreso, E.total_egreso from Egresos E, usuario U where E.id_usuario = U.id_usuario and E.id_egresos='" . $id . "' and  E.id_empresa='$conpuntoresult'");
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
}
echo json_encode($arr_data);
?>
