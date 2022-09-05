<?php

session_start();
include 'base.php';
conectarse();
$texto = $_GET['term'];

$consulta = pg_query("select * from clientes where identificacion like '$texto%' or nombres_cli ilike '%$texto%'");
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'value' => $row[3],
        'label' => $row[0]
    );
}
echo $data = json_encode($data);
