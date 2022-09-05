<?php
session_start();
include 'base.php';
conectarse();
$data = [];
$texto = $_GET['term'];
$consulta = pg_query("select id_vehiculo, placa, modelo from contrato_vehiculo where placa like '%$texto%';");
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'modelo' => $row[2],
        'value' => $row[1],
        'id_vehiculo' => $row[0],
    );
}
echo $data = json_encode($data);