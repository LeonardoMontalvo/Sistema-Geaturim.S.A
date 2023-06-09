<?php

// Direccion IP 
function getClientIp() {
    $ip = 'not found';
    // Sea que la Ip venga:
    // Desde el internet compartido
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    // Desde un proxy
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    // Desde una direccion remota
    else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

// Insertar registro *parametro concepto de la transaccion
function insert_registro($concepto = '') {
    require_once __DIR__ . '/configuracion.php';
    $conexion = conectarse();
    $campo_punto_venta = '1';
    $consulta7 = pg_query("select * from punto_venta_empresa  left join punto_venta  on punto_venta_empresa.id_punto_venta=punto_venta.id_punto_venta  where  
punto_venta_empresa.id_usuario='$_SESSION[id]' ORDER BY id_punto_venta_empresa DESC LIMIT 1");
    while ($row = pg_fetch_row($consulta7)) {
        $campo_punto_venta = $row[5];
    }


    // Get Fecha y Hora Actual
    date_default_timezone_set("America/Guayaquil");
    $fecha_actual = date('o-m-d');
    $hora_actual = date('h:i:s A');
    // Get Parametros adicionales
    $ip = getClientIp();
    $id = pg_fetch_row(pg_query("SELECT max(id_transacciones)+1 from transacciones"))[0];
    $id_tipo = 0;
    $id_tipo = pg_fetch_row(pg_query(
                            "SELECT id_tipo_transaccion FROM tipo_transaccion 
        where descripcion ilike 'AUDITORIA' and estado='Activo';"
            ))[0];
    if ($id_tipo > 0) {
//        	 echo '<br>GUARDAR FACTURA VENTARR: <br>' .  "INSERT INTO transacciones(id_transacciones, id_usuario, fecha_actual, hora_actual, concepto, 
//            id_tipo_transaccion, num_transaccion, estado, observacion, identificador_cli_pro, id_empresa)
//            VALUES ($id, $_SESSION[id], '$fecha_actual', '$hora_actual', '$concepto, DESDE LA IP: $ip', $id_tipo, $id, 
//            'Activo', '', 'AUD', $_SESSION[PV]);";//////////////////////////
//	 
        pg_query("INSERT INTO transacciones(id_transacciones, id_usuario, fecha_actual, hora_actual, concepto, 
            id_tipo_transaccion, num_transaccion, estado, observacion, identificador_cli_pro, id_empresa)
            VALUES ('$id', '$_SESSION[id]', '$fecha_actual', '$hora_actual', '$concepto, DESDE LA IP: $ip', $id_tipo, $id, 
            'Activo', '', 'AUD', '$campo_punto_venta');"
        );
    }
}
