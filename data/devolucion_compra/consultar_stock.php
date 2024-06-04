<?php
session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$pv = $_SESSION['PV_INV'];
$idprod = $_GET["id_producto"];

$sql = "
select coalesce(dpb.stock,0)stock
from productos p
left join detalle_producto_bodega dpb
using (cod_productos)
where dpb.id_bodega=$pv
and p.cod_productos=$idprod
";
$res = pg_query($sql);
$rows = pg_fetch_all($res);
if (empty($rows)) {
    echo json_encode([]);
} else {
    echo json_encode($rows[0]);
}
