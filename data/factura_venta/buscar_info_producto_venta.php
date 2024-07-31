<?php
session_start();
include '../../procesos/base.php';
conectarse();
//error_reporting(0);
$idprod = $_GET["id_producto"];
$idcli = $_GET["id_cliente"];

$sql = "
(select 
fv.fecha_actual,
p.articulo,
p.cod_barras,
dfv.cantidad, 
div.* 
from factura_venta fv
inner join detalle_factura_venta dfv
using(id_factura_venta)
inner join detalle_impuesto_producto_venta div
using(id_detalle_venta)
inner join productos p
using(cod_productos)
where id_cliente=$idcli
and dfv.cod_productos=$idprod
and fv.estado='Activo'
order by id_factura_venta desc)
union
(select 
fv.fecha_actual,
p.articulo,
p.cod_barras,
dfv.cantidad, 
div.* 
from facturas_novalidas fv
inner join detalle_facturas_novalidas dfv
using(id_facturas_novalidas)
inner join detalle_impuesto_producto_notaventa div
using(id_detalle_facturas_novalidas)
inner join productos p
using(cod_productos)
where id_cliente=$idcli
and dfv.cod_productos=$idprod
and fv.estado='Activo'
order by id_facturas_novalidas desc)
order by fecha_actual
limit 1
";

$res = pg_query($sql);
$row = pg_fetch_assoc($res);
if (empty($row)) {
    $row = [];
}
echo json_encode($row);
