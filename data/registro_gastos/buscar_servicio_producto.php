
<?php

session_start();
include '../../procesos/base.php';
conectarse();

$consulta = pg_query("select SUM(total_compra::FLOAT) from detalle_gastos where  id_gastos ='$_POST[id]' and bien_servicio='S'");
$row = pg_fetch_row($consulta);
echo $row[0];
?>
