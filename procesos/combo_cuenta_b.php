<?php

session_start();
include './base.php';
conectarse();
error_reporting(0);

$consulta = pg_query(
    "SELECT id_cuenta_banco, descripcion, numero_cuenta FROM cuentas_bancos c 
    INNER JOIN bancos b ON c.id_banco=b.id_bancos
    WHERE c.estado='Activo';"
);
echo "<option selected id='0' value='0'>Todas</option>";
while ($row = pg_fetch_row($consulta)) {
    echo "<option id='$row[0]' value='$row[0]'> $row[1] : $row[2]</option>";
}
