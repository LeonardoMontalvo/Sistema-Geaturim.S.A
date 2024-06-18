<?php

session_start();
include '../../procesos/base.php';
include '../../procesos/configuracion.php';
$conexion = conectarse();
$conf = new Configuracion();
$defecto_iva = $conf->getParametroEmpresa("defecto_iva");
error_reporting(0);



$consultatarifa = pg_query("select * from tarifa_impuesto where estado='Activo' ORDER BY id_taimpuesto  ASC");
while ($row = pg_fetch_assoc($consultatarifa)) {
    $opt = "<option data-valor='$row[valor]' value='$row[id_taimpuesto]'>$row[nombre_taimpuesto]</option>";
    if (trim($defecto_iva) == "No" && $row["id_taimpuesto"] == 1) {
        $opt = "<option data-valor='$row[valor]' selected value='$row[id_taimpuesto]'>$row[nombre_taimpuesto]</option>";
    } else if (trim($defecto_iva) == "Si" && $row["id_taimpuesto"] == 6) {
        $opt = "<option data-valor='$row[valor]' selected value='$row[id_taimpuesto]'>$row[nombre_taimpuesto]</option>";
    }
    echo $opt;
}




$res = pg_query($consultaimpu);
$rows = pg_fetch_all($res);
if (empty($rows)) {
    $rows = [];
}
echo json_encode($rows);
