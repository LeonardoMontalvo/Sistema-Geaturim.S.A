<?php
session_start();
include __DIR__ . "/../../../procesos/base.php";
$term = $_GET["term"];

if (empty($term)) {
    echo json_encode([]);
    exit();
}

$sql = "
select p.articulo,p.iva_minorista,p.iva_mayorista,p.iva_negocio,
utilidad_minorista,utilidad_mayorista,utilidad_negocio,
cantidad_mayorista,cantidad_negocio,cod_barras,
coalesce(dpb.stock,0) stock
from productos p
left join detalle_producto_bodega dpb
using(cod_productos)
where (articulo ilike '%$term%' 
or cod_barras = '$term' 
or codigo='$term') 
and estado = 'Activo' 
and dpb.stock>0
order by articulo asc
limit 100
";

$res = pg_query($sql);
$rows = pg_fetch_all($res);
if (empty($rows)) {
    $rows = [];
}

echo json_encode($rows);
