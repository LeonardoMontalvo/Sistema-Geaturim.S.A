<?php

session_start();
include 'base.php';
conectarse();

$consulta = pg_query("select * from retencion_iva");
echo "<select id=nombre_riva>";
while ($row = pg_fetch_row($consulta)) {
    echo "<option value='$row[0]'>$row[2]"."% - "."$row[1]</option>";
}
echo "</select>";
?>
