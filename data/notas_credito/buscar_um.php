<?php
session_start();
include '../../procesos/base.php';
conectarse();

$descripcion = $_GET["descripcion"];
//echo $descripcion;

if (!empty($descripcion)) {
    $sql = "select * from unidades_medida where descripcion='$descripcion' and estado='Activo' limit 1";
    $res = pg_query($sql);
    $row = pg_fetch_assoc($res);
    if (empty($row)) {
        echo json_encode([]);
    }
    echo json_encode($row);
} else {
    echo json_encode([]);
}
