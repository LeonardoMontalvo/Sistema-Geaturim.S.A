<?php

include '../../procesos/base.php';
conectarse();
$cont = 0;
$consulta = pg_query("select * from registro_equipo order by id_registro asc");
while ($row = pg_fetch_row($consulta)) {
    $cont = $row[0];
}

$cont = $cont + 1;
echo $cont;
?>
