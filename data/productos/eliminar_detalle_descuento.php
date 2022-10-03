<?php
session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();

$iddescuento = $_POST["id_descuento"];
$idproducto = $_POST["id_producto"];

$sql = "delete from detalle_descuento where id_descuento=$iddescuento and id_producto=$idproducto";
$res = pg_query($sql);
if (empty($res)) {
    echo 0;
}
echo 1;
