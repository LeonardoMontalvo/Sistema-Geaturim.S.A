<?php

include '../../procesos/base.php';
conectarse();
$texto2 = $_GET['term'];

$consulta = pg_query("select P.cod_productos, P.codigo, P.cod_barras, P.articulo, P.iva_minorista, P.stock, P.descuento, P.iva,  P.series, P.incluye_iva from  productos P where  articulo ilike '%$texto2%'  and P.estado='Activo'");
while ($row = pg_fetch_row($consulta)) {
    $data[] = array(
        'value' => $row[3],
        'codigo_barras' => $row[2],
        'codigo' => $row[1],
        'precio' => $row[4],
        'canti' => $row[5],
        'descuento' => $row[6],
        'iva_producto' => $row[7],
        'carga_series' => $row[8],
        'cod_producto' => $row[0],
        'incluye' => $row[9],
    );
}

echo $data = json_encode($data);
?>
