<?php

session_start();
include '../../procesos/base.php';
conectarse();
$texto2 = $_GET['term'];

$texto2 = str_replace(" ", "%", $texto2);

$consulta = pg_query("select * from productos where articulo ilike '%$texto2%' and estado = 'Activo'");
if (pg_num_rows($consulta) > 0) {
    while ($row = pg_fetch_row($consulta)) {
        $data[] = array(
            'value' => $row[3],
            'codigo_barras' => $row[2],
            'codigo' => $row[1],
            'cod_producto' => $row[0]
        );
    }
    echo $data = json_encode($data);
}
?>
