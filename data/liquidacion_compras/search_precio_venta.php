<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$producto_venta = $_GET["producto_venta"];
$precio_tipo = $_GET["precio_tipo"];
$arr_data = array();

if ($producto_venta != "") {
    $consulta = pg_query("select * from productos where articulo = '$producto_venta' and estado = 'Activo'");
    while ($row = pg_fetch_row($consulta)) {
        if ($precio_tipo == "MINORISTA") {
            $arr_data[] = $row[1];
            $arr_data[] = $row[3];
            $arr_data[] = round($row[9],3);
            $arr_data[] = $row[13];
            $arr_data[] = $row[4];
            $arr_data[] = $row[5];
            $arr_data[] = $row[0];
            $arr_data[] = $row[19];
            $arr_data[] = $row[21];
            $arr_data[] = $row[26];
        } else {
            if ($precio_tipo == "MAYORISTA") {
                $arr_data[] = $row[1];
                $arr_data[] = $row[3];
                $arr_data[] = round($row[10],3);
                $arr_data[] = $row[13];
                $arr_data[] = $row[4];
                $arr_data[] = $row[5];
                $arr_data[] = $row[0];
                $arr_data[] = $row[19];
                $arr_data[] = $row[21];
                $arr_data[] = $row[26];
            } else {
                if ($precio_tipo == "NEGOCIO") {
                    $arr_data[] = $row[1];
                    $arr_data[] = $row[3];
                    $arr_data[] = round($row[27],3);
                    $arr_data[] = $row[13];
                    $arr_data[] = $row[4];
                    $arr_data[] = $row[5];
                    $arr_data[] = $row[0];
                    $arr_data[] = $row[19];
                    $arr_data[] = $row[21];
                    $arr_data[] = $row[26];
                }
            }
        }
    }
}
echo json_encode($arr_data);
?>