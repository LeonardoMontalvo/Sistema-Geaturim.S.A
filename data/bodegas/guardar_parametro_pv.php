<?php
include_once __DIR__ . '/../../procesos/base.php';
$conexion = conectarse();

$pv = $_POST["id_pv"];
$nomparam = $_POST["nombre_param"];
$valparam = $_POST["valor"];

if (existeRegistsro($pv)) {
    $val = (empty($valparam) ? null : $valparam);
    $resp = modificarParametro($pv, $nomparam, $val);
    echo $resp;
} else {
    $resp = guardarRegistro($pv);
    if ($resp > 0) {
        $val = (empty($valparam) ? null : $valparam);
        $resp = modificarParametro($pv, $nomparam, $val);
    }
    echo $resp;
}
function existeRegistsro($idpv)
{
    $sql = "
    select id_punto_venta 
    from parametros_punto_venta
    where id_punto_venta=$idpv";
    $res = pg_query($sql);
    if (pg_num_rows($res) > 0) {
        return true;
    }
    return false;
}

function guardarRegistro($idpv)
{
    $sql = "
    INSERT INTO parametros_punto_venta(
        id_punto_venta)
    VALUES ($idpv);
    ";
    $res = pg_query($sql);
    if (empty($res)) {
        return 0;
    }
    return $idpv;
}

function modificarParametro($idpv, $nombreparam, $valor)
{
    $val = (empty($valor) ? 'null' : "'$valor'");
    $sql = "
    UPDATE parametros_punto_venta
    SET $nombreparam=$val
    WHERE id_punto_venta=$idpv;
    ";

    $res = pg_query($sql);
    $res = pg_query($sql);
    if (empty($res)) {
        return 0;
    }
    return $idpv;
}
