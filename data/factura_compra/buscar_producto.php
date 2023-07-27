<?php

session_start();
include '../../procesos/base.php';
conectarse();
$texto2 = $_GET['term'];
$puntov=$_SESSION["PV"];

$consulta = pg_query("
select p.*, coalesce(dpb.stock,0) stock_bodega from productos p
left join detalle_producto_bodega dpb 
on p.cod_productos=dpb.cod_productos
and dpb.id_bodega=$puntov
where articulo ilike '%$texto2%' and estado='Activo' limit 200");

if (pg_num_rows($consulta) > 0) {
    while ($row = pg_fetch_row($consulta)) {
        $data[] = array(
            'value' => $row[3],
            'codigo' => $row[1],
            'codigo_barras' => $row[2],
            'precio' => $row[6],
            'iva_producto' => $row[4],
            'carga_series' => $row[5],
            'cod_producto' => $row[0],
            'incluye' => $row[26],
            'iva_minorista' => $row[9],
            'stock' => $row[40], 
            'id_plan' => $row[28]
            
        );
    }
    echo $data = json_encode($data);
}
?>