<?php

session_start();
include '../../procesos/base.php';
include '../../procesos/configuracion.php';
$conexion = conectarse();
$conf = new Configuracion();
$defecto_iva = $conf->getParametroEmpresa("defecto_iva");
error_reporting(0);

if ($defecto_iva == "No") {
    $consultaimpu = "select codigo_taimpuesto,nombre_taimpuesto from tarifa_impuesto where id_taimpuesto=1 or id_taimpuesto=2 or id_taimpuesto=5 or id_taimpuesto=4 order by  id_taimpuesto ASC";
    while ($row = pg_fetch_row($consultaimpu)) {
        if ($row[0] == 4) {
            echo "<option id=$row[0]  value=$row[0]>$row[1]</option>";
        } else {
            echo "<option id=$row[0] selected value=$row[0]>$row[1]</option>";
        }
    }
} else {

    $consultaimpu = "select codigo_taimpuesto,nombre_taimpuesto from tarifa_impuesto where id_taimpuesto=1 or id_taimpuesto=2 or id_taimpuesto=5 or id_taimpuesto=4 order by  id_taimpuesto ASC";
    while ($row = pg_fetch_row($consultaimpu)) {
        if ($row[0] == 4) {
            echo "<option id=$row[0] selected value=$row[0]>$row[1]</option>";
        } else {
            echo "<option id=$row[0] value=$row[0]>$row[1]</option>";
        }
    }
}




$res = pg_query($consultaimpu);
$rows = pg_fetch_all($res);
if (empty($rows)) {
    $rows = [];
}
echo json_encode($rows);
