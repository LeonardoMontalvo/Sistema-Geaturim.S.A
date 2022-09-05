<?php

session_start();
include '../../procesos/base.php';
conectarse();
$texto = $_GET['term'];
$consulta = pg_query("select * from aplicacion where nombre_aplicacion like '%$texto%' ");
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'value' => $row[1],
        'id_aplicacion' => $row[0],
    );
}

echo $data = json_encode($data);
?>
