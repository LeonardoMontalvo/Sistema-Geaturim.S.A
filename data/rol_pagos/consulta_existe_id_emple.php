<?php

session_start();
include '../../procesos/base.php';

conectarse();
error_reporting(0);
date_default_timezone_set('America/Guayaquil');

$conpuntoresult = $_SESSION['PV'];

$conpunto = 0;
$consultapunto = pg_query("select * from rol_pagos,detalle_rol where rol_pagos.id_rol_pagos =detalle_rol.id_rol_pagos and mes='$_POST[select_mes]' and anio='$_POST[slct_anio_cf]' and id_empresa='$conpuntoresult'  and detalle_rol.id_empleado='$_POST[id_empleado]'");
while ($row = pg_fetch_row($consultapunto)) {
    $conpunto = $row[0];
}
if ($conpunto == 0) {

    $data = 12; // no hay datos pueden agregar
} else {
    $data = 13; //si hay datos  
}








echo $data;
?>