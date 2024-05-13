<?php

session_start();
include '../../procesos/base.php';
conectarse();
$texto2 = $_GET['term'];
$puntov = $_SESSION["PV"];

$consulta = pg_query("
select p.*, coalesce(dpb.stock,0) stock_bodega from productos p
left join detalle_producto_bodega dpb 
on p.cod_productos=dpb.cod_productos
and dpb.id_bodega=$puntov
where articulo ilike '%$texto2%' and estado='Activo' limit 200");

if (pg_num_rows($consulta) > 0) {
    while ($row = pg_fetch_assoc($consulta)) {
        $data[] = array(
            'value' => $row['articulo'],
            'codigo' => $row['codigo'],
            'codigo_barras' => $row['cod_barras'],
            'precio' => $row["precio_compra"],
            'iva_producto' => $row["id_taimpuesto"],
            'carga_series' => $row["series"],
            'cod_producto' => $row["cod_productos"],
            'incluye' => $row["incluye_iva"],
            'iva_minorista' => $row["iva_minorista"],
            'stock' => $row["stock_bodega"],
            'id_plan' => $row["id_plan_cuentas"]

        );
    }
    echo $data = json_encode($data);
}
