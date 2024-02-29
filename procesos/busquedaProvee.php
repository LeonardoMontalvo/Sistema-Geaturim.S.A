<?php

session_start();
include 'base.php';
conectarse();
$texto = $_GET['term'];

$consulta = pg_query("select * from proveedores where identificacion_pro ilike '$texto%' or empresa_pro ilike '%$texto%' limit 200");
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'value' => $row[3],
        'label' => $row[0]
    );
}
echo $data = json_encode($data);
