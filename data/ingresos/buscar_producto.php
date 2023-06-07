<?php

session_start();
include '../../procesos/base.php';
conectarse();
$texto2 = $_GET['term'];

$consulta = pg_query("select * from productos where  estado='Activo' and articulo ilike '%$texto2%' limit 200");
if (pg_num_rows($consulta) > 0) {
    while ($row = pg_fetch_row($consulta)) {
        if ($row[37] == "" || $row[37] == "0") {
            $row[37] = $row[6];
        } else {
            $row[37] = $row[37];
        }
        $data[] = array(
            'value' => $row[3],
            'codigo_barras' => $row[2],
            'codigo' => $row[1],
            'precio' => $row[37],
            'p_venta' => $row[9],
            'iva_producto' => $row[4],
            'cod_producto' => $row[0],
            'incluye' => $row[26]
        );
    }
    echo $data = json_encode($data);
}
?>