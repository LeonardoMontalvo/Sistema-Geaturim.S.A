<?php
session_start();
include '../../procesos/base.php';
conectarse();
$texto2 = $_GET['term'];
$categoria = $_GET['id_categoria'];
$puntoventa = $_SESSION["PV"];

$texto21 = str_replace(" ", "%", $texto2);

$sql1 = "
select
cod_productos cod_producto,
codigo,
cod_barras codigo_barras,
articulo,
iva,
iva_minorista precio_minorista,
iva_mayorista precio_mayorista,
iva_negocio precio_negocio,
cantidad_mayorista,
cantidad_negocio,
imagen,
inventariable,
coalesce(dpb.stock, 0) stock,
p.stock cant_promo,
bien_servicios,
ti.codigo_timpu cod_impuesto,
tt.codigo_taimpuesto cod_tarifa,
tt.valor tarifa
from productos p
left join detalle_producto_bodega dpb using(cod_productos) 
inner join tipo_impuesto ti using(id_timpu)
inner join tarifa_impuesto tt using(id_taimpuesto)
";
$sql2 = "
where (cod_barras = '$texto2' or codigo='$texto2' or articulo ilike '%$$texto21%') and p.estado = 'Activo'
and dpb.id_bodega=$puntoventa and dpb.stock>0 and p.inventariable='Si'
";
$sql3 = "
where (cod_barras = '$texto2' or codigo='$texto2' or articulo ilike '%$$texto21%') and p.estado = 'Activo'
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
