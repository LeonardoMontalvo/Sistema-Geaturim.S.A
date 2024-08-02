<?php
session_start();
include '../../procesos/base.php';

$comp = $_GET["comprobante"];

$sql = "select
    pp.comprobante,
    p.identificacion_pro,
    p.empresa_pro,
    sum(pp.valor_pagado)valor_pagado,
    pp.fecha_actual,
    pp.hora_actual,
    u.nombre_usuario,
    u.apellido_usuario,
    pp.id_proveedor,
    p.tipo_documento
    from pagos_pagar pp
    inner join proveedores p using(id_proveedor) 
    inner join usuario u using(id_usuario)
    where pp.estado<>'Anulado'
    group by pp.comprobante,
    pp.id_proveedor,
    p.identificacion_pro,
    p.empresa_pro,
    pp.fecha_actual,
    pp.hora_actual,
    u.nombre_usuario,
    u.apellido_usuario,
    p.tipo_documento 
    ";

$res = pg_query($sql);
$rows = pg_fetch_assoc($res);
if (empty($rows)) {
    $rows = [];
}
echo json_encode($rows);
