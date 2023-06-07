<?php

session_start();
include '../../procesos/base.php';
conectarse();
$tipo = $_GET['tipo_precio'];
$data = [];
$producto_nombre = htmlspecialchars($_GET['articulo']);
$consulta = pg_query("select * from productos P where articulo ilike '%$producto_nombre%' limit 200");

while ($row = pg_fetch_row($consulta)) {
    if ($tipo == "MINORISTA") {
        $data[] = array(
            'value' => $row[3],
            'codigo_barras' => $row[2],
            'codigo' => $row[1],
            'p_venta' => $row[9],
            'descuento' => $row[19],
            'disponibles' => $row[13],
            'des' => $row[19],
            'iva_producto' => $row[4],
            'cod_producto' => $row[0],
            'incluye' => $row[26],
            'punto_venta' => $row[33],
            'precio' => $row[6],
        );
    } else {
        if ($tipo == "MAYORISTA") {
            $data[] = array(
                'value' => $row[3],
                'codigo_barras' => $row[2],
                'codigo' => $row[1],
                'p_venta' => $row[10],
                'descuento' => $row[19],
                'disponibles' => $row[13],
                'des' => $row[19],
                'iva_producto' => $row[4],
                'cod_producto' => $row[0],
                'incluye' => $row[26],
                'punto_venta' => $row[33],
                'precio' => $row[6],
            );
        } else {
            if ($tipo == "NEGOCIO") {
                $data[] = array(
                    'value' => $row[3],
                    'codigo_barras' => $row[2],
                    'codigo' => $row[1],
                    'p_venta' => $row[27],
                    'descuento' => $row[19],
                    'disponibles' => $row[13],
                    'des' => $row[19],
                    'iva_producto' => $row[4],
                    'cod_producto' => $row[0],
                    'incluye' => $row[26],
                    'punto_venta' => $row[33],
                    'precio' => $row[6],
                );
            }
        }
    }
}

echo $data = json_encode($data);
?>
