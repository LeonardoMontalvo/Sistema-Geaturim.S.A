<?php
function getIdDetalleCentroCosto()
{
    $sql = "select coalesce(max(id_detalle_centro_costo),0) max from detalle_centro_costos";
    $res = pg_query($sql);
    return pg_fetch_assoc($res)["max"] + 1;
}

function guardarDetalleCentroCosto($iddetalled, $idcentroc, $tipod)
{
    $id = getIdDetalleCentroCosto();

    $sql = "INSERT INTO detalle_centro_costos(
        id_detalle_centro_costo, id_documento, tipo_documento, 
        id_centro_costo)
        VALUES ($id, $iddetalled, '$tipod', 
        $idcentroc);";

    $res = pg_query($sql);
    if (empty($res)) {
        return 0;
    }
    return $id;
}
