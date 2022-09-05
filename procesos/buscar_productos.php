<?php

session_start();
include 'base.php';
conectarse();
$texto2 = $_GET['term'];

$consulta = pg_query("select * from productos where articulo ilike '%$texto2%' and estado= 'Activo'");
while ($row = pg_fetch_row($consulta)) {
        $data[] = array(
            'value' => $row[3],
            'codigo' => $row[1],
            'disponibles' => $row[13],
            'iva_producto' => $row[4],
            'carga_series' => $row[5],
            'cod_producto' => $row[0],
            'des' => $row[19],
            'inventar' => $row[21]
        );
}

echo $data = json_encode($data);
?>
