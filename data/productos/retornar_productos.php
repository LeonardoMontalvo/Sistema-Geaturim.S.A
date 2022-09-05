<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['com'];
$arr_data = array();

$sql = "SELECT g.id_generico, g.nombre_generico, c.id_categoria, c.nombre_categoria, m.id_marca, m.nombre_marca, a.id_aplicacion,a.nombre_aplicacion, "
        . "p.id_timpu,p.id_taimpuesto "
        . "FROM productos P LEFT JOIN punto_venta PV ON P.id_bodega = PV.id_punto_venta "
        . "LEFT JOIN plan_cuentas PC ON PC.id_plan_cuentas=P.id_plan_cuentas LEFT JOIN proveedores PR ON p.id_proveedor=pr.id_proveedor "
        . "LEFT JOIN detalle_producto_bodega dpb ON p.cod_productos=dpb.cod_productos LEFT JOIN generico g ON P.id_generico = g.id_generico "
        . "LEFT JOIN categoria c ON P.id_categoria = c.id_categoria LEFT JOIN marcas m ON P.id_marca = m.id_marca LEFT JOIN aplicacion a ON P.id_aplicacion = a.id_aplicacion "
        . "WHERE p.cod_productos= $id";

$consulta = pg_query($sql);

while ($row = pg_fetch_assoc($consulta)) {
    $arr_data[] = $row['id_generico'];
    $arr_data[] = $row['nombre_generico'];

    $arr_data[] = $row['id_categoria'];
    $arr_data[] = $row['nombre_categoria'];

    $arr_data[] = $row['id_marca'];
    $arr_data[] = $row['nombre_marca'];

    $arr_data[] = $row['id_aplicacion'];
    $arr_data[] = $row['nombre_aplicacion'];

    $arr_data[] = $row['id_timpu'];
    $arr_data[] = $row['id_taimpuesto'];
}
echo json_encode($arr_data);
?>