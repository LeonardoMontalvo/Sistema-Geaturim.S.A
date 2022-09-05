<?php
include 'base.php';

echo json_encode(buscarPuntoVenta($_GET['usuario']));

function buscarPuntoVenta($usuario)
{
    $usuario = trim($usuario);
    $sql = "
    select
    pv.id_punto_venta, pv.nombre_punto
    from punto_venta pv
    inner join usuario u
    on pv.id_punto_venta=u.id_empresa
    where u.usuario='$usuario' and pv.estado='Activo'";
    if ($usuario == "Admin") {
        $sql = "
        select
        pv.id_punto_venta, pv.nombre_punto
        from punto_venta pv where pv.estado='Activo'
        ";
    }
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}
