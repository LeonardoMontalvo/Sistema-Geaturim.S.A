<?php
include_once __DIR__ . '/../../procesos/base.php';
$conexion = conectarse();

$idpv = $_GET["id_pv"];

$sql = "
select 
formato_imperesion_factura, formato_imperesion_nota, formato_imperesion_nota_credito, 
formato_imperesion_factura_compra, formato_imperesion_retencion_compra, 
formato_imperesion_retencion_gasto
from parametros_punto_venta
where id_punto_venta=$idpv
";
$res = pg_query($sql);
$row = pg_fetch_assoc($res);
if (empty($row)) {
    echo json_encode([]);
} else {
    echo json_encode($row);
}
