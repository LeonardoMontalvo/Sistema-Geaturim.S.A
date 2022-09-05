<?php
session_start();
include 'base.php';
conectarse();
$data = [];
$texto = $_GET['term'];
$consulta = pg_query("select id_cliente, identificacion,nombres_cli from clientes where identificacion like '%$texto%';");
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'nombres_cli' => $row[2],
        'value' => $row[1],
        'id_cliente' => $row[0],
    );
}
echo $data = json_encode($data);