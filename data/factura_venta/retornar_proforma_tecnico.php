<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['id2'];
$arr_data = array();

$pvinv = $_SESSION['PV_INV'];

$conpunto = 1;
$consultapunto = pg_query("select max(id_punto_venta_empresa) from punto_venta_empresa where punto_venta_empresa.id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($consultapunto)) {
    $conpunto = $row[0];
}
$conpuntoresult = 1;
$consultapuntoresult = pg_query("select id_punto_venta from punto_venta_empresa where id_punto_venta_empresa='$conpunto'");
while ($row = pg_fetch_row($consultapuntoresult)) {
    $conpuntoresult = $row[0];
}

$consulta = pg_query("
select 
p.cod_productos,
p.codigo,
p.articulo,
case 
	when dpb.stock is null  then 0	
	else dpb.stock
end stock,
D.cantidad,
D.precio_venta,
D.descuento_venta,
D.total_venta,
p.iva,
p.incluye_iva,
p.inventariable 
from 
proforma_tecnico PR,
productos p
left join detalle_producto_bodega dpb
on p.cod_productos=dpb.cod_productos
and dpb.id_bodega=$pvinv,
detalle_proforma_tecnico D
where P.cod_productos = D.cod_productos 
and PR.id_proforma = D.id_proforma 
and PR.estado ='Activo'  
and D.estado= 'Activo' 
and D.id_proforma=$id
and pr.id_empresa=$conpuntoresult
");

while ($row = pg_fetch_row($consulta)) {
    $arr_data[] = $row[0];
    $arr_data[] = $row[1];
    $arr_data[] = $row[2];
    $arr_data[] = $row[3];
    $arr_data[] = $row[4];
    $arr_data[] = $row[5];
    $arr_data[] = $row[6];
    $arr_data[] = $row[7];
    $arr_data[] = $row[8];
    $arr_data[] = $row[9];
    $arr_data[] = $row[10];
}
echo json_encode($arr_data);