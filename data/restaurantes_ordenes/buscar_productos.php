<?php
session_start();
include '../../procesos/base.php';
conectarse();
$texto2 = $_GET['term'];
$categoria = $_GET['id_categoria'];
$puntoventa = $_SESSION["PV"];

$sql1 = "
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
";

$sql2 = "
where (cod_barras = '$texto2' or codigo='$texto2' or articulo ilike '%$texto2%') and estado = 'Activo'
and dpb.id_bodega=$puntoventa and dpb.stock>0 and p.inventariable='Si'
";
$sql3 = "
where (cod_barras = '$texto2' or codigo='$texto2' or articulo ilike '%$texto2%') and estado = 'Activo'
and dpb.id_bodega=$puntoventa and p.inventariable='No'
";

if (!empty($categoria)) {
    if ($categoria == -1) {
        $sql2 .= " and id_categoria is null";
        $sql3 .= " and id_categoria is null";
    } else {
        $sql2 .= " and id_categoria=$categoria";
        $sql3 .= " and id_categoria=$categoria";
    }
}

$sql = "
($sql1 $sql2) union all ($sql1 $sql3)
";
$sql .= " order by articulo asc limit 200";

$consulta = pg_query($sql);
$data = [];
if (pg_num_rows($consulta)) {
    $data = pg_fetch_all($consulta);
}
echo $data = json_encode($data);
