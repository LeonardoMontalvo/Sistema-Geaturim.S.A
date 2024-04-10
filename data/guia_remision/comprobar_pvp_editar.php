<?php
session_start();
include '../../procesos/base.php';
conectarse();
$data = 0;
error_reporting(0);
//////////////////////////////////////////////////CONSULTA DE TODOS /////////////////

  
  $consulta = pg_query("select editar_pvp from pvp_venta_editable p where p.cod_productos='$_GET[prod]' ");  
    


while ($row = pg_fetch_row($consulta)) {
    $arr_data[] = $row[0]; //PORCENTAJE
  
}
////////////////////////////////
echo json_encode($arr_data);
