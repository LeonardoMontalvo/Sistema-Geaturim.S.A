<?php

session_start();
include '../../procesos/base.php';
conectarse();
$texto2 = $_GET['term'];

$consulta = pg_query("select cod_productos,codigo,cod_barras,articulo from productos where articulo like '%$texto2%' and estado = 'Activo'");
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'value' => $row[3],       
        'cod_producto' => $row[0],
         'codigo' => $row[1],
         'cod_barras' => $row[2],
    );
}

echo $data=json_encode($data);
?>
