<?php
session_start();
include '../../procesos/base.php';

$idtarifa = $_POST["id_tarifa"];
$nomparam = $_POST["nombre_param"];
$valparam = $_POST["valor"];

if (existeRegistsro($idtarifa)) {
    $val = (empty($valparam) ? null : $valparam);
    $resp = modificarParametro($idtarifa, $nomparam, $val);
    echo $resp;
} else {
    $resp = guardarRegistro($idtarifa);
    if ($resp > 0) {
        $val = (empty($valparam) ? null : $valparam);
        $resp = modificarParametro($idtarifa, $nomparam, $val);
    }
    echo $resp;
}


function existeRegistsro($idtarifa)
{
    $sql = "
    select id_taimpuesto
    from parametros_cuentas_contables_iva
    where id_taimpuesto=$idtarifa";
    $res = pg_query($sql);
    if (pg_num_rows($res) > 0) {
        return true;
    }
    return false;
}

function guardarRegistro($idtarifa)
{
    $sql = "
    INSERT INTO parametros_cuentas_contables_iva(
    id_taimpuesto)
    VALUES ($idtarifa);
    ";
    $res = pg_query($sql);
    if (empty($res)) {
        return 0;
    }
    return $idtarifa;
}

function modificarParametro($idtarifa, $nombreparam, $valor)
{
    $val = (empty($valor) ? 'null' : $valor);
    $sql = "
    UPDATE parametros_cuentas_contables_iva
    SET $nombreparam=$val
    WHERE id_taimpuesto=$idtarifa
    ";

    $res = pg_query($sql);
    $res = pg_query($sql);
    if (empty($res)) {
        return 0;
    }
    return $idtarifa;
}
