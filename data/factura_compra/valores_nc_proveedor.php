<?php
session_start();
include '../../procesos/base.php';
$conexion = conectarse();
$idproveedor = $_GET["id_proveedor"];
$sql = "
select 
fpm.id_formas_pago_mixto_nc,
dv.num_serie num_nota,
dv.fecha_actual,
fpm.valor
from formas_pago_mixto_nc fpm
    inner join devolucion_compra dv 
    on dv.id_devolucion_compra = fpm.id_devolucion_compra
where fpm.forma_pago = 'VALOR_FAVOR_EMPRESA'
    and dv.id_proveedor = $idproveedor
    and fpm.estado='Activo'
    and dv.estado <> 'Pasivo'
";

$res = pg_query($sql);
$rows = pg_fetch_all($res);
if (empty($rows)) {
    $rows = [];
}

echo json_encode($rows);
