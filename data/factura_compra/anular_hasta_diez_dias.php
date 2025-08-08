<?php

session_start();
include '../../procesos/base.php';
date_default_timezone_set('America/Guayaquil');
$fecha_time = date('Y-m-d', time());
$valortxt9 = $fecha_time; //FECHA ACTUAL SISTEMA
//echo 'fecha'.$valortxt9;
$fecha_emision_factura = $_POST['fecha_emision_factura'];
//echo 'fecha emision' . $fecha_emision_factura . "\n";
$mes_notificacion = 1;

$fecha_caducidad_menos_dias = strtotime('+' . $mes_notificacion . ' month', strtotime($fecha_emision_factura));
$fecha_caducidad_menos_dias_for = date('Y-m-d', $fecha_caducidad_menos_dias);
$partes_hora = explode("-", $fecha_caducidad_menos_dias_for);
$anio = $partes_hora[0];
$mes = $partes_hora[1];
$dia = '10';
$result = $anio . '-' . $mes . '-' . $dia;

//echo "Día 10 del mes siguiente: " . $result . "\n";
if ($valortxt9 > $result) {

    $data = 22; // es mayor que el dia diez del sieguiente mes //ERROR
} else {
    $data = 60; // PUEDE ANULAR
}


echo $data;
