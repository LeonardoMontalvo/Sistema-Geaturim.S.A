<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

$sql = "select * from cargo_usuario where estado='Activo'";
$res = pg_query($sql);
$rows = pg_fetch_all($res);
$s = '<select  id="s1" style="width:200px;">';
$s = $s . '<option value="0" >Seleccione...</option>';

if (!empty($rows)) {
    foreach ($rows as $value) {
        $s .= "<option value='$value[id_cargo_usuario]'>$value[descripcion]</option>";
    }
}

echo $s . '</select>';
