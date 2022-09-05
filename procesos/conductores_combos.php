<?php
session_start();
include 'base.php';
conectarse();
$data = [];
$texto = $_GET['term'];
$consulta = pg_query("select id_conductor, dni ,nombres from contrato_conductor where dni like '%$texto%';");
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'nombres' => $row[2],
        'value' => $row[1],
        'id_conductor' => $row[0],
    );
}
echo $data = json_encode($data);