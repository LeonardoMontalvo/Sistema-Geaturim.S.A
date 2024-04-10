<?php

session_start();
include '../../procesos/base.php';
conectarse();
$texto2 = $_GET['term'];

$consulta = pg_query("select cod_productos,codigo,cod_barras,articulo from productos where  codigo ilike '$texto2%' or cod_barras ilike '$texto2%' and estado = 'Activo' order by codigo");
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'value' => $row[2],       
        'cod_producto' => $row[0],
         'codigo' => $row[1],
         'articulo' => $row[3],
    );
}

echo $data=json_encode($data);
?>
