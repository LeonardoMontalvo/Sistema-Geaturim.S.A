<?php

session_start();
include '../../procesos/base.php';
conectarse();

if (!empty($_POST["pvpmino"])) {
    $sql = "
    UPDATE unidad_medida_productos
    SET 
    pvpmino=$_POST[pvpmino]
    WHERE id_unidad_medida_productos=$_POST[id_umprod];
    ";
    $res = pg_query($sql);
}
if (!empty($_POST["pvpmayo"])) {
    $sql = "
    UPDATE unidad_medida_productos
    SET 
    pvpmayo=$_POST[pvpmayo]
    WHERE id_unidad_medida_productos=$_POST[id_umprod];
    ";
    $res = pg_query($sql);
}
if (!empty($_POST["pvpnego"])) {
    $sql = "
    UPDATE unidad_medida_productos
    SET 
    pvpnego=$_POST[pvpnego]
    WHERE id_unidad_medida_productos=$_POST[id_umprod];
    ";
    $res = pg_query($sql);
}
