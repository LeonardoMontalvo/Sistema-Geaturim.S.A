<?php
session_start();
include '../../procesos/base.php';
conectarse();

$clave = $_GET["clave_acceso"];

echo json_encode(existeFactua($clave));

function existeFactua($clave)
{
    $sql = "select id_factura_compra id from factura_compra where num_autorizacion='$clave'
    union select id_gastos id from gastos where num_autorizacion='$clave'
    union select id_gastos_personales id from gastos_personales where num_autorizacion='$clave'";
    $res = pg_query($sql);
    if (pg_num_rows($res) > 0) {
        return 1;
    }
    return 0;
}
