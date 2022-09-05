<?php

session_start();
include '../../procesos/base.php';
conectarse();
$tipo = $_GET['tipo_precio'];

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

$consulta = pg_query("SELECT * FROM productos WHERE estado='Activo'");
if (pg_num_rows($consulta) > 0) {
    while ($row = pg_fetch_assoc($consulta)) {
        $consulta1 = pg_query("SELECT dpb.stock FROM productos p LEFT JOIN detalle_producto_bodega dpb ON p.cod_productos=dpb.cod_productos "
                . "WHERE p.cod_productos=$row[cod_productos] AND dpb.id_bodega=$conpuntoresult ");
        $row1 = pg_fetch_assoc($consulta1);

        if ($tipo == "MINORISTA") {
            $data[] = array(
                'value' => $row['articulo'],
                'codigo' => $row['codigo'],
                'codigo_barras' => $row['cod_barras'],
                'p_venta' => $row['iva_minorista'],
                'descuento' => $row['descuento'],
                'disponibles' => $row1['stock'],
                'iva_producto' => $row['iva'],
                'carga_series' => $row['series'],
                'cod_producto' => $row['cod_productos'],
                'des' => $row['cantidad_descuento'],
                'inventar' => $row['inventariable'],
                'incluye' => $row['incluye_iva']
            );
        } else {
            if ($tipo == "MAYORISTA") {
                $data[] = array(
                    'value' => $row['articulo'],
                    'codigo' => $row['codigo'],
                    'codigo_barras' => $row['cod_barras'],
                    'p_venta' => $row['iva_mayorista'],
                    'descuento' => $row['descuento'],
                    'disponibles' => $row1['stock'],
                    'iva_producto' => $row['iva'],
                    'carga_series' => $row['series'],
                    'cod_producto' => $row['cod_productos'],
                    'des' => $row['cantidad_descuento'],
                    'inventar' => $row['inventariable'],
                    'incluye' => $row['incluye_iva']
                );
            } else {
                if ($tipo == "NEGOCIO") {
                    $data[] = array(
                        'value' => $row['articulo'],
                        'codigo' => $row['codigo'],
                        'codigo_barras' => $row['cod_barras'],
                        'p_venta' => $row['iva_negocio'],
                        'descuento' => $row['descuento'],
                        'disponibles' => $row1['stock'],
                        'iva_producto' => $row['iva'],
                        'carga_series' => $row['series'],
                        'cod_producto' => $row['cod_productos'],
                        'des' => $row['cantidad_descuento'],
                        'inventar' => $row['inventariable'],
                        'incluye' => $row['incluye_iva']
                    );
                }
            }
        }
    }
    echo $data = json_encode($data);
}
?>