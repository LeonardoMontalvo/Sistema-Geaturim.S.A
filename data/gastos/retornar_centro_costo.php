<?php
session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

$idtrans = $_GET["id_transaccion"];

$sql = "
    select cc.* from centro_costos cc
    inner join detalle_centro_costos dcc
    on cc.id_centro_costo=dcc.id_centro_costo
    where tipo_documento='gastos_internos'
    and id_documento=$idtrans
";
$res = pg_query($sql);
$row = pg_fetch_assoc($res);
if (empty($row)) {
    $row = [];
}
echo json_encode($row);
