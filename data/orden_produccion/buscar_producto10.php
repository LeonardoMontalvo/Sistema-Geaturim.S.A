<?php

session_start();
include '../../procesos/base.php';
conectarse();
$tipo = $_GET['tipo_precio'];

$sql=pg_query("select cod_productos, id_receta, costo_producto from recetas where estado='Activo'");

while ($row1=pg_fetch_row($sql)) {
    $consulta = pg_query("select * from productos where cod_productos=$row1[0]");
    while ($row = pg_fetch_row($consulta)) {
        if ($tipo == "MINORISTA") {
            $data[] = array(
                'value' => $row[3],
                'codigo' => $row[1],
                'codigo_barras' => $row[2],
                'p_venta' => $row[6],
                'descuento' => $row[19],
                'disponibles' => $row[13],
                'iva_producto' => $row[4],
                'carga_series' => $row[5],
                'cod_producto' => $row[0],
                'des' => $row[19],
                'inventar' => $row[21],
                'incluye' => $row[26],
                'id_receta' => $row1[1],
                'costo_producto' => $row1[2],
            );
        }
    }
}
echo $data = json_encode($data);
?>
