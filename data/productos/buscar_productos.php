<?php

session_start();
include '../../procesos/base.php';
conectarse();
$texto2 = $_GET['term'];

if (!empty($_GET["tipo"])) {
    if ($_GET["tipo"] == 'codigo') {
        $texto2=mb_strtoupper($texto2);
        $consulta = pg_query("select cod_productos,codigo,cod_barras,articulo from productos 
        where cod_barras = '$texto2' and estado = 'Activo'");
        while ($row = pg_fetch_row($consulta)) {
            $data[] = array(
                'value' => $row[3],
                'cod_producto' => $row[0],
                'codigo' => $row[1],
                'cod_barras' => $row[2],
            );
        }
    }
} else {
    $consulta = pg_query("select cod_productos,codigo,cod_barras,articulo from productos 
    where (articulo ilike '%$texto2%' or cod_barras = '$texto2') and estado = 'Activo' limit 100");
    while ($row = pg_fetch_row($consulta)) {
        $data[] = array(
            'value' => $row[3],
            'cod_producto' => $row[0],
            'codigo' => $row[1],
            'cod_barras' => $row[2],
        );
    }
}



echo $data = json_encode($data);
