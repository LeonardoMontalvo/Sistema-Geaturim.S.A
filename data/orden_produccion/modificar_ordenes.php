<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

   
    // modificar reservacion
    pg_query("Update ordenes_produccion set id_usuario = '$_SESSION[id]', fecha_actual = '$_POST[fecha_actual]',  costo_total = '$_POST[costo]', cantidad='$_POST[cantidad]' where id_ordenes = '$_POST[id_orden]'");
    // fin
    
    
    
    $data = $_POST['id_orden'];

echo $data;
?>
