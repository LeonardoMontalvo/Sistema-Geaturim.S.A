<?php
session_start();
include '../../../procesos/base.php';
conectarse();
$idpv = $_SESSION["PV"];
$idsujetoret = $_POST["id_sujeto_ret"];
$estafact = $_POST["estab"];
$ptoemifact = $_POST["ptoemi"];
$secuencialfact = $_POST["secuencial"];

if (comprobarIdentificacionEmpresa($idsujetoret)) {
    $idfact = buscarFactura($estafact, $ptoemifact, $secuencialfact);
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

function buscarFactura($estab, $ptoemi, $secuencial)
{
    $numserie = $estab . "-" . $ptoemi;
    $sql = "
    select id_factura_venta from factura_venta
    where num_serie='$numserie' and num_factura='$secuencial'
    and estado ='Activo';
    ";
    $res = pg_query($sql);
    if (pg_num_rows($res) > 0) {
        return pg_fetch_assoc($res)["id_factura_venta"];
    }
    return 0;
}
