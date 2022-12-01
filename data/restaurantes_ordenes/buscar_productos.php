<?php
session_start();
include '../../procesos/base.php';
conectarse();
$texto2 = $_GET['term'];
$categoria = $_GET['id_categoria'];
$puntoventa = $_SESSION["PV"];

/* $sql = "
select
cod_productos,
codigo,
cod_barras,
articulo,
iva,
iva_minorista,
imagen
from productos where (cod_barras = '$texto2' or articulo ilike '%$texto2%') and estado = 'Activo'"; */
$sql = "
select
cod_productos cod_producto,
codigo,
cod_barras codigo_barras,
articulo,
iva,
iva_minorista precio,
imagen,
inventariable,
coalesce(dpb.stock, 0) stock,
p.stock cant_promo,
bien_servicios
from productos p
left join detalle_producto_bodega dpb
using(cod_productos) 
where (cod_barras = '$texto2' or articulo ilike '%$texto2%') and estado = 'Activo'
and dpb.id_bodega=$puntoventa
";

if (!empty($categoria)) {
    if ($categoria == -1) {
        $sql .= " and id_categoria is null";
    } else {
        $sql .= " and id_categoria=$categoria";
    }
}
$sql.=" order by articulo asc";

$consulta = pg_query($sql);
$data = [];
if (pg_num_rows($consulta)) {
    $data = pg_fetch_all($consulta);
}
echo $data = json_encode($data);
