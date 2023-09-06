<?php
session_start();
include '../../../procesos/base.php';
conectarse();
$term = $_GET["term"];
$tipo = $_GET["tipo"];
$idfac = $_GET["id_factura"];
$data = [];
$limit = "200";

$sql = "
select p.cod_productos,p.cod_barras,p.articulo,p.codigo,p.iva_minorista
from factura_compra fc
inner join detalle_factura_compra dfc
using(id_factura_compra)
inner join productos p
using(cod_productos)
where p.estado='Activo'
and fc.id_factura_compra=$idfac ";

switch ($tipo) {
    case "articulo":
        if (empty($term)) {
            $data = [];
            break;
        }
        $sql .= "and p.articulo ilike '%$term%' limit $limit";
        $res = pg_query($sql);
        $rows = pg_fetch_all($res);
        if (!empty($rows)) {
            $data = $rows;
        }
        break;
    case "codigo_barras":
        $term = mb_strtoupper($term);
        $sql .= "and (p.cod_barras = '$term' or p.codigo = '$term') limit $limit";
        $res = pg_query($sql);
        $rows = pg_fetch_all($res);
        if (!empty($rows)) {
            $data = $rows;
        }
        break;
    case "cod_productos":
        if (!is_numeric($term)) {
            $data = [];
            break;
        }
        if (!empty($term)) {
            $sql .= " and p.cod_productos = $term limit $limit";
            $res = pg_query($sql);
            $rows = pg_fetch_all($res);
            if (!empty($rows)) {
                $data = $rows;
            }
        }

        break;
}

echo json_encode($data);
