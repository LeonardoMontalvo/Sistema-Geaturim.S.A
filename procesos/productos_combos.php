<?php

session_start();
include 'base.php';
conectarse();
$texto = $_GET['term'];
$consulta = pg_query("select cod_productos, articulo from productos where articulo like '%$texto%'");
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
       
        'value' => $row[1],
        'id_cliente' => $row[0],
    );
}
////
echo $data = json_encode($data);
?>