<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

$consulta = pg_query("select * from categoria order by nombre_categoria asc");
echo "<option value='' selected>--SIN CATEGORIA--</option>";
while ($row = pg_fetch_row($consulta)) {
    if ($row[0] == $_GET['id']) {
        echo "<option selected id='$row[0]' value='$row[0]'> $row[1]</option>";
    } else {
        echo "<option id='$row[0]' value='$row[0]'> $row[1]</option>";
    }
}
?>
