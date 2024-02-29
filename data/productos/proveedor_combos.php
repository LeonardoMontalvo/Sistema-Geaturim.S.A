<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$consulta = pg_query("select * from proveedores");
  echo "<option selected id='0' value='0'>Seleccione...</option>"; 
while ($row = pg_fetch_row($consulta)) {
     
    if ($row['id_proveedor'] == $_GET['id']) {
        echo "<option  id='$row[0]' value='$row[0]'> $row[3]</option>";
    } else {
        echo "<option id='$row[0]' value='$row[0]'> $row[3]</option>";
    }
}
?>
