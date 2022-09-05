<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['com'];
$arr_data = array();
$conpunto = 1;
$consultapunto = pg_query("select max(id_punto_venta_empresa) from punto_venta_empresa where punto_venta_empresa.id_usuario='$_SESSION[id]'");
while ($row = pg_fetch_row($consultapunto)) {
    $conpunto = $row[0];
}
$conpuntoresult = 1;
$consultapuntoresult = pg_query("select id_punto_venta from punto_venta_empresa where id_punto_venta_empresa='$conpunto'");
while ($row = pg_fetch_row($consultapuntoresult)) {
    $conpuntoresult = $row[0];
}

$SQL = "select  DR.id_detalle_rol, C.id_empleado,c.identificacion,c.nombres_empleado,ca.nombre_cargo, dias_laborados, ca.sueldo_base,
       sueldo_percibido, horas_extras, otros, empleados, aportacion_patronal, 
       tercer_sueldo, cuarto_sueldo, total_nomina, aporte_personal, 
       total_anticipos, faltante_caja, total_multas, 
       prestamo_iess, comisariato, otros_descuentos, total_deduccion, 
       liquido_recivir
 from rol_pagos F, detalle_rol DR, empleado C, usuario U,cargo ca where c.id_cargo=ca.id_cargo and F.id_rol_pagos=DR.id_rol_pagos AND  F.id_usuario = U.id_usuario and DR.id_empleado = C.id_empleado   and  F.id_empresa='$conpuntoresult' and F.id_rol_pagos = '" . $id . "'";


$result = pg_query($SQL);
header("Content-type: text/xml;charset=utf-8");
$s = "<?xml version='1.0' encoding='utf-8'?>";
$s .= "<rows>";
$s .= "<page>" . $page . "</page>";
$s .= "<total>" . $total_pages . "</total>";
$s .= "<records>" . $count . "</records>";
while ($row = pg_fetch_row($result)) {


    $s .= "<row id='" . $row[0] . "'>";
    $s .= "<cell></cell>";
    $s .= "<cell></cell>";
    $s .= "<cell>" . $row[0] . "</cell>";
    $s .= "<cell>" . $row[1] . "</cell>";
    $s .= "<cell>" . $row[2] . "</cell>";
    $s .= "<cell>" . $row[3] . "</cell>";
    $s .= "<cell>" . $row[4] . "</cell>";
    $s .= "<cell>" . $row[5] . "</cell>";


    $s .= "<cell>" . $row[6] . "</cell>";
    $s .= "<cell>" . $row[7] . "</cell>";
    $s .= "<cell>" . $row[8] . "</cell>";
    $s .= "<cell>" . $row[9] . "</cell>";
    $s .= "<cell>" . $row[10] . "</cell>";
    $s .= "<cell>" . $row[11] . "</cell>";

    $s .= "<cell>" . $row[12] . "</cell>";
    $s .= "<cell>" . $row[13] . "</cell>";
    $s .= "<cell>" . $row[14] . "</cell>";
    $s .= "<cell>" . $row[15] . "</cell>";
    $s .= "<cell>" . $row[16] . "</cell>";
    $s .= "<cell>" . $row[17] . "</cell>";

    $s .= "<cell>" . $row[18] . "</cell>";
    $s .= "<cell>" . $row[19] . "</cell>";
    $s .= "<cell>" . $row[20] . "</cell>";
    $s .= "<cell>" . $row[21] . "</cell>";
    $s .= "<cell>" . $row[22] . "</cell>";
    $s .= "<cell>" . $row[23] . "</cell>";

    $s .= "</row>";
}
$s .= "</rows>";
echo $s;
?>