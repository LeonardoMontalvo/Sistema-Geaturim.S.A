<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$consulta = pg_query("select id_empresa from factura_venta");

while ($row = pg_fetch_row($consulta)) {
    if ($row[0] == '1') {
        
        echo "<option selected id='$row[0]' value='$row[0]'>Local Principal</option>";
    
    } 
     if ($row[0] == '2') {
        echo "<option selected id='$row[0]' value='$row[0]'>Punto de Venta</option>";
        break;
    }
}
?>


   
      