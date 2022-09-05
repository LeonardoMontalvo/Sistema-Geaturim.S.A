<?php
session_start();
include 'base.php';
conectarse();
error_reporting(0);
$consulta = pg_query("SELECT id_vendedor, nombre_vendedor from vendedores where estado='Activo'");
echo "<select id=id_vendedor>";
echo "<option selected value='0'>TODOS</option></select>";
while ($row = pg_fetch_assoc($consulta)) {
    echo "<option value='$row[id_vendedor]'>$row[nombre_vendedor]</option>";
}
echo "</select>";