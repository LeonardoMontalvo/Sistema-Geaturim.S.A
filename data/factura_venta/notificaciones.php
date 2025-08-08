<?php

session_start();
include '../../procesos/base.php';
date_default_timezone_set('America/Guayaquil');
$fecha_time = date('Y-m-d', time());
$valortxt9 = $fecha_time; //FECHA ACTUAL SISTEMA
//echo 'fecha'.$valortxt9;

$consulta = pg_query("select valor_parametro from parametros_empresa where nombre_parametro='fecha_caducidad_firma'");
$fecha_caducidad = "";
while ($row = pg_fetch_row($consulta)) {
    $fecha_caducidad = $row[0];
    $dias_notificacion = 30;

    $fecha_caducidad_menos_dias = strtotime('-' . $dias_notificacion . ' day', strtotime($fecha_caducidad));
    $fecha_caducidad_menos_dias_for = date('Y-m-d', $fecha_caducidad_menos_dias);
//echo 'fecha_caducidad_menos_dias_for'.$fecha_caducidad_menos_dias_for;
    if ($valortxt9 >= $fecha_caducidad_menos_dias_for) {

        $webNotificationPayload['title'] = 'La firma electronica esta caducada o proximo a caducar';
//$webNotificationPayload['url'] = 'http://localhost/syswebfe_farma_familiar/data/lotes_producto/';
        echo json_encode($webNotificationPayload);
        exit();
    }
//$webNotificationPayload['body'] = 'Notificación push web de PHP al navegador.';
//$webNotificationPayload['icon'] = 'https://www.baulphp.com/badge.jpg';
//$webNotificationPayload['url'] = 'https://www.baulphp.com';
}
?>