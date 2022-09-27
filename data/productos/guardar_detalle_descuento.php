<?php
session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();

$iddescuento = $_POST["id_descuento"];
$idproducto = $_POST["id_producto"];

$sql = "
    INSERT INTO detalle_descuento(
        id_descuento, id_producto)
    VALUES ($iddescuento, $idproducto);
    ";
$res = pg_query($sql);

if (empty($res)) {
    echo 0;
}
echo 1;
