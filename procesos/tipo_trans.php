<?php

session_start();
include './base.php';
conectarse();
error_reporting(0);

$consulta = pg_query("select distinct identificador_cli_pro from transacciones");
echo "<option selected id='0' value='0'>Todos</option>";
while ($row = pg_fetch_row($consulta)) {
    echo "<option id='$row[0]' value='$row[0]'> $row[0]</option>";
}
