<?php

session_start();
include '../../procesos/base.php';
conectarse();
$texto2 = $_GET['term'];

$consulta = pg_query("select id_usuario, nombre_usuario, apellido_usuario from usuario where ci_usuario ilike '%$texto2%' or nombre_usuario ilike '%$texto2%' or apellido_usuario ilike '%$texto2%' and estado = 'Activo'");
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'id_usuario' => $row[0],
        'nombre' => $row[1]." ".$row[2]
    );
}

echo $data = json_encode($data);
?>
