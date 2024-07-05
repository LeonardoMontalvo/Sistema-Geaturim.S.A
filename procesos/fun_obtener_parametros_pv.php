<?php
function obtenerParametrosPv($idpv)
{
    $sql = "
    select*from parametros_punto_venta
    where id_punto_venta=$idpv
    ";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    $data = [];
    if (!empty($rows)) {
        $data = $rows[0];
    }
    return $data;
}
