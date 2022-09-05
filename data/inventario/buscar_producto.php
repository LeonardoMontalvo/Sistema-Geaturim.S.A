<?php

session_start();
include '../../procesos/base.php';
conectarse();
$texto2 = $_GET['term'];
$conpunto = 1;
$conpunto = 1;

$consultapunto = pg_query("select max(id_punto_venta_empresa) from punto_venta_empresa where punto_venta_empresa.id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($consultapunto)) {
    $conpunto = $row[0];
}
$conpuntoresult = 1;
$consultapuntoresult = pg_query("select id_punto_venta from punto_venta_empresa where id_punto_venta_empresa='$conpunto'");
while ($row = pg_fetch_row($consultapuntoresult)) {
    $conpuntoresult = $row[0];
}
$consulta = pg_query("SELECT * FROM productos WHERE estado='Activo' AND LOWER(articulo) LIKE LOWER('%$texto2%') "
        . "ORDER BY cod_productos");
if (pg_num_rows($consulta) > 0) {
    while ($row = pg_fetch_assoc($consulta)) {
        $consulta1 = pg_query("SELECT dpb.stock FROM productos p LEFT JOIN detalle_producto_bodega dpb ON p.cod_productos=dpb.cod_productos "
                . "WHERE p.cod_productos=$row[cod_productos] AND dpb.id_bodega=$conpuntoresult");
        $row1 = pg_fetch_assoc($consulta1);

        if ($row1['stock'] == NULL) {
            $stock = number_format("0", 2, '.', '');
        } else {
            $stock = number_format($row1['stock'], 2, '.', '');
        }
        $data[] = array(
            'value' => $row['articulo'],
            'codigo' => $row['codigo'],
            'codigo_barras' => $row['cod_barras'],
            'precio' => $row['precio_compra'],
            //'stock' => $row1[42],
            'stock' => $stock,
            'p_venta' => $row['iva_minorista'],
            'existencia' => $row['existencia'],
            'diferencia' => $row['diferencia'],
            'cod_producto' => $row['cod_productos']
        );
    }
    echo $data = json_encode($data);
}
?>