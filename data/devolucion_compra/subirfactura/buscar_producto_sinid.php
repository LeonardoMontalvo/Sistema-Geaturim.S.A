<?php
session_start();
include '../../../procesos/base.php';
conectarse();
$term = $_GET["term"];
$tipo = $_GET["tipo"];
$data = [];
$limit = "200";

$sql = "select cod_productos,cod_barras,articulo,codigo,iva_minorista,inventariable
from productos where estado='Activo' ";

switch ($tipo) {
    case "articulo":
        if (empty($term)) {
            $data = [];
            break;
        }
        $term = str_replace(" ", "%", $term);
        $sql .= "and articulo ilike '%$term%' limit $limit";
        $res = pg_query($sql);
        $rows = pg_fetch_all($res);
        if (!empty($rows)) {
            $data = $rows;
        }
        break;
    case "codigo_barras":
        $term = mb_strtoupper($term);
        $sql .= "and (cod_barras = '$term' or codigo = '$term') limit $limit";
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
            $sql .= " and cod_productos = $term limit $limit";
            $res = pg_query($sql);
            $rows = pg_fetch_all($res);
            if (!empty($rows)) {
                $data = $rows;
            }
        }

        break;
}

echo json_encode($data);
