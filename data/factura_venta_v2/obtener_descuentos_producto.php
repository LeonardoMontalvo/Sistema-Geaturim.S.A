<?php
session_start();
include '../../procesos/base.php';
conectarse();

$productoid=$_GET["id_producto"];

$sql = "
select dp.id_descuento, descripcion, nro_producto,porcentaje_descuento
from detalle_descuento dd
inner join descuentos_producto dp
on dd.id_descuento=dp.id_descuento
where dd.id_producto=$productoid
";

$res = pg_query($sql);
$rows = pg_fetch_all($res);
if (empty($rows)) {
    $rows = [];
}
echo json_encode($rows);
