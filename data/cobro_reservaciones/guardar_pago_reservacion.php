<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

/////////////////contador  pagos///////////
$cont1 = 0;
$consulta = pg_query("select max(id_pago_reservacion) from pago_reservacion");
while ($row = pg_fetch_row($consulta)) {
    $cont1 = $row[0];
}
$cont1++;
$valor=number_format($_POST['valor'],2,'.','');
$saldo=number_format($_POST['saldo'],2,'.','');
////////////////////////////////////////////
pg_query("insert into pago_reservacion values('$cont1', '$_POST[ids]', '$_SESSION[id]', '$_POST[fecha_actual]', '$_POST[hora_actual]', '$valor', '$saldo', 'Activo')");

pg_query("update reservaciones set saldo='$saldo' where id_reservacion='$_POST[ids]'");
$data=$cont1;
echo $data;
?>