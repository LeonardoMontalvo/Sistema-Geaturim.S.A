<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

$cont = 0;
$consulta = pg_query("select max(id_transportista) from transportista");
while ($row = pg_fetch_row($consulta)) {
    $cont = $row[0];
}
$cont++;



pg_query("insert into transportista values('$cont','$_POST[identificacion]','".strtoupper($_POST[nombres_trans])."', '$_POST[direccion_trans]', '$_POST[telefono]','$_POST[celular]','$_POST[num_placa]','$_POST[tipo_docu]')");
$data = 1;
echo $data;
?>
