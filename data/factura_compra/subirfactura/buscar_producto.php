<?php
session_start();
include '../../../procesos/base.php';
conectarse();
$term = $_GET["term"];
$tipo = $_GET["tipo"];
$data = [];
$limit = "300";
switch ($tipo) {
    case "articulo":
        if (empty($term)) {
            return [];
        }
        $sql = "
        select cod_productos,cod_barras,articulo, codigo, iva_minorista
        from productos where estado='Activo' 
        and articulo ilike '%$term%' limit $limit";
        $res = pg_query($sql);
        $rows = pg_fetch_all($res);
        if (!empty($rows)) {
            $data = $rows;
        }
        break;
    case "codigo_barras":
        $term = mb_strtoupper($term);
        $sql = "
            select cod_productos,cod_barras,articulo,codigo, iva_minorista
            from productos where estado='Activo' 
            and cod_barras = '$term' limit $limit";
        $res = pg_query($sql);
        $rows = pg_fetch_all($res);
        if (!empty($rows)) {
            $data = $rows;
        }
        break;
    case "cod_productos":
        $term = mb_strtoupper($term);
        if (!empty($term)) {
            $sql = "
            select cod_productos,cod_barras,articulo,codigo, iva_minorista
            from productos where estado='Activo' 
            and cod_productos = $term limit $limit";
            //var_dump($sql);
            $res = pg_query($sql);
            $rows = pg_fetch_all($res);
            if (!empty($rows)) {
                $data = $rows;
            }
        }

        break;
}

echo json_encode($data);
