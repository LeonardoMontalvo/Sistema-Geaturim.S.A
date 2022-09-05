<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$texto2 = $_GET['term'];

$sql = "
  select * from productos where estado='Activo' and substr(articulo,0,50) ILIKE '$texto2%' OR substr(codigo,0,50) ILIKE '$texto2%'    ORDER BY cod_productos";

$consulta = pg_query($sql);
if (pg_num_rows($consulta) > 0) {
    while ($row = pg_fetch_assoc($consulta)) {
        if ($_GET['op'] == "c" && isset($_GET['op'])) {
            $data[] = array(
                'value' => $row['codigo'],
                'articulo' => $row['articulo'],
                'codigo' => $row['codigo'],
                'cod_producto' => $row['cod_productos'],
            );
        } else {
            $data[] = array(
                'value' => $row['articulo'],
                'articulo' => $row['articulo'],
                'codigo' => $row['codigo'],
                'cod_producto' => $row['cod_productos'],
            );
        }
    }
}
echo $data = json_encode($data);

