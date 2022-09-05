<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

    // datos detalle reservacion
    $campo1 = $_POST['campo1'];
    $campo2 = $_POST['campo2'];

    // suspender reservacion
    pg_query("Update reservaciones Set estado = 'Pasivo', fecha_suspension='$_POST[fecha_anulacion]', motivo='$_POST[motivo]' where id_reservacion = '$_POST[comprobante]'");
    

$data = 1;

echo $data;
?>
