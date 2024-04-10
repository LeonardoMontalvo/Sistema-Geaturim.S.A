<?php
session_start();
include '../../procesos/base.php';
$conexion = conectarse();
$idcliente = $_GET["id_cliente"];
$sql = "
select 
fpm.id_formas_pago_mixto_nv,
dv.num_nota_serie||'-'||dv.num_nota_credito num_nota,
dv.fecha_actual,
fpm.valor
from formas_pago_mixto_nv fpm
    inner join devolucion_venta dv 
    on dv.id_devolucion_venta = fpm.id_devolucion_venta
where fpm.forma_pago = 'VALOR_FAVOR_CLIENTE'
    and dv.id_cliente = $idcliente
    and fpm.estado='Activo'
    and dv.estado <> 'Pasivo'
";
$res = pg_query($sql);
$rows = pg_fetch_all($res);
if (empty($rows)) {
    $rows = [];
}

echo json_encode($rows);
