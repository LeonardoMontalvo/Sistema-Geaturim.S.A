<?php

session_start();
include 'base.php';
conectarse();
$texto = $_GET['term'];
$data = array();

$consulta = pg_query("select * from clientes where nombres_cli ilike '%$texto%'");
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'value' => $row[3],
        'id_cliente' => $row[0]
    );
}
echo $data = json_encode($data);
