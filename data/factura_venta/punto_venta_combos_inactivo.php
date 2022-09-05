<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

$id_cargo = '';
$consulta1 = pg_query("SELECT id_cargo_usuario
  FROM usuario where id_usuario=$_SESSION[id] ");
while ($row1 = pg_fetch_row($consulta1)) {
    $id_cargo = $row1[0];
}

if ($id_cargo == '1') {
    $consulta = pg_query("select * from punto_venta  ");
    echo "<option selected id='0' value='0'>Todos</option>";
    while ($row = pg_fetch_row($consulta)) {
        echo "<option id='$row[0]' value='$row[0]'> $row[1]</option>";
    }
} else {
    $consulta = pg_query("select * from punto_venta where id_punto_venta=$_SESSION[PV] ");

    while ($row = pg_fetch_row($consulta)) {

        echo "<option id='$row[0]' value='$row[0]'> $row[1]</option>";
    }
}
?>
