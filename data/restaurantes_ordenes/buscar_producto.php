<?php
session_start();
include '../../procesos/base.php';
conectarse();
$productoid = $_GET['id_producto'];
$puntoventa = $_SESSION["PV"];

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
p.stock cant_promo
from productos p
left join detalle_producto_bodega dpb
using(cod_productos) 
where cod_productos=$productoid
and dpb.id_bodega=$puntoventa
";

$consulta = pg_query($sql);
$data = [];
if (pg_num_rows($consulta)) {
    $data = pg_fetch_all($consulta)[0];
}
echo $data = json_encode($data);
