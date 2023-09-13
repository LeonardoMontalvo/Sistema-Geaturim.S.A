<?php
session_start();
include '../../procesos/base.php';
conectarse();

$clave = $_GET["clave_acceso"];
$tipodoc = $_GET["tipo_doc"];

switch ($tipodoc) {
    case 'factura':
        echo json_encode(existeFactua($clave));
        break;
    case 'comp_ret':
        echo json_encode(existeRetencionVenta($clave));
        break;
}

function existeFactua($clave)
{
    $sql = "select id_factura_compra id from factura_compra where num_autorizacion='$clave' and estado='Activo'
    union select id_gastos id from gastos where num_autorizacion='$clave' and estado='Activo'
    union select id_gastos_personales id from gastos_personales where num_autorizacion='$clave' and estado='Activo'";
    $res = pg_query($sql);
    if (pg_num_rows($res) > 0) {
        return 1;
    }
    return 0;
}


function existeRetencionVenta($clave)
{
    $sql = "
    select id_retencion_fuente_factura_venta id
    from retencion_fuente_factura_venta
    where autorizacion='$clave'
    and estado='Activo'
    ";
    $res = pg_query($sql);
    if (pg_num_rows($res) > 0) {
        return 1;
    }
    return 0;
}
