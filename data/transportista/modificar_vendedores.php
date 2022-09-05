<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

if(pg_query("Update transportista Set  identificacion='$_POST[identificacion]',nombres_trans='".strtoupper($_POST['nombres_trans'])."', direccion_trans='".strtoupper($_POST['direccion_trans'])."',  telefono='$_POST[telefono]', celular='$_POST[celular]', num_placa='$_POST[num_placa]'  where identificacion='$_POST[identificacion]'")){


   $data = 1;
}
echo $data;
?>
