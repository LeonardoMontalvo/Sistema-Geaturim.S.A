<?php
session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();

$iddescuento = $_POST["id_descuento"];

$sql = "delete from promocion_venta where id_promocion_venta=$iddescuento";
$res = pg_query($sql);
if (empty($res)) {
    echo 0;
}
echo $iddescuento;
