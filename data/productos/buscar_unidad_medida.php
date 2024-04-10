<?php

session_start();
include '../../procesos/base.php';
conectarse();
$texto2 = $_GET['term'];

$consulta = pg_query("select * from unidades_medida where descripcion ilike '%$texto2%' and estado = 'Activo'");
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'value' => $row[1],       
        'articulo' => $row[0],
         'cantidad' => $row[3],
    );
}

echo $data=json_encode($data);
?>
