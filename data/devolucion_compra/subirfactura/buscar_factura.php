<?php
session_start();
include '../../../procesos/base.php';
conectarse();
$idpv = $_SESSION["PV"];
$ruccomprador = $_POST["ruc_comprador"];
$rucproveedor = $_POST["ruc_proveedor"];
$nrofactura = $_POST["nro_factura"];

if (comprobarIdentificacionEmpresa($ruccomprador)) {
    $idfact = buscarFactura($nrofactura, $rucproveedor);
    if ($idfact > 0) {
        echo json_decode($idfact);
    } else {
        echo json_decode(-2);
    }
} else {
    echo json_decode(-1);
}

function comprobarIdentificacionEmpresa($identificacion)
{
    $sql = "select id_empresa from empresa where ruc_empresa='$identificacion'";
    $res = pg_query($sql);
    if (pg_num_rows($res) > 0) {
        return true;
    }
    return false;
}

function buscarFactura($nrofactura, $rucproveedor)
{
    $idproveedor = buscarProveedor($rucproveedor);
    $sql = "
    select id_factura_compra from factura_compra
    where num_serie='$nrofactura' and id_proveedor=$idproveedor
    and estado ='Activo';
    ";

    $res = pg_query($sql);
    if (pg_num_rows($res) > 0) {
        return pg_fetch_assoc($res)["id_factura_compra"];
    }
    return 0;
}

function buscarProveedor($rucproveedor)
{
    $sql = "
    select id_proveedor from proveedores
    where identificacion_pro='$rucproveedor';
    ";
    $res = pg_query($sql);
    if (pg_num_rows($res) > 0) {
        return pg_fetch_assoc($res)["id_proveedor"];
    }
    return 0;
}
