<?php

session_start();
include '../../procesos/base.php';
conectarse();

$consulta = pg_query("select fecha_actual, valor_pago, saldo from pago_reservacion where id_reservacion= '$_POST[id]' and estado = 'Activo' order by id_pago_reservacion asc ");
while ($row = pg_fetch_row($consulta)) {
    $lista[] = $row[0];
    $lista[] = $row[1];
    $lista[] = $row[2];
}

echo $lista = json_encode($lista);
?>
