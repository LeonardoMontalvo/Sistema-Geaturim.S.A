<?php

date_default_timezone_set('America/Guayaquil');
setlocale(LC_TIME, "spanish"); // PARA WINDOWS
//setlocale(LC_ALL, 'es_ES'); //PARA LINUX

/**
 * FUNCION PARA OBTENER LA FECHA Y HORA ACTUAL
 * 
 * @return type - FECHA Y HORA AAAA-MM-DD hh:mm:ss
 */
function obtenerFechaHoraActual() {
    //date_default_timezone_set('America/Guayaquil');
    $dt = new DateTime();
    return $dt->format('Y-m-d H:i:s');
}

/**
 * FUNCIÓN PARA OBTENER FECHA ACTUAL
 * 
 * @return type - FECHA  AAAA-MM-DD
 */
function obtenerFechaActual() {
    //date_default_timezone_set('America/Guayaquil');
    $dt = new DateTime();
    return $dt->format('Y-m-d');
}

/**
 * FUNCIÓN PARA OBTENER HORA ACTUAL
 * 
 * @return type - HORA 12:01:01
 */
function obtenerHoraActual() {
    //date_default_timezone_set('America/Guayaquil');
    return date("H:i:s ");
}

/**
 * FUNCIÓN PARA OBTENER EL NMBRE DEL MES
 * 
 * @param type $numMes
 * @return type
 */
function obtenerNombreMes($numMes) {
    /* date_default_timezone_set('America/Guayaquil');
      setlocale(LC_TIME, "spanish"); // PARA WINDOW */
    //setlocale(LC_ALL, 'es_ES'); //PARA LINUX
    $objDate = new DateTime();
    $formatDate = $objDate->createFromFormat('!m', $numMes);
    return strtoupper(strftime('%B', $formatDate->getTimestamp()));
}

/**
 * OBTENER MES
 * 
 * @param type $fecha - FECHA AAA-MM-DD
 * @return type - NUMERO DEL MES
 */
function obtenerMes($fecha) {
    $objDate = new DateTime($fecha);
    $formatDate = $objDate->format('m');
    return $formatDate;
}

/**
 * OBTENER ULTIMO DÍA DEL MES
 * 
 * @param DateTime $fecha - FECHA
 * @return type - ULTIMO DÍA
 */
function obtenerUltimoDiaMes($fecha) {
    $fecha = new DateTime($fecha);
    $fecha->modify('last day of this month');
    return $fecha->format('d');
}

/**
 * OBTENER DIFERENCIA ENTRE FECHAS
 * 
 * @param DateTime $fechaIni
 * @param DateTime $fechaFin
 * @return type - NÚMERO DE DIFERENCIAS
 */
function diferenciaFechas($fechaIni, $fechaFin) {
    //date_default_timezone_set('America/Guayaquil');
    $fechaIni = new DateTime($fechaIni);
    $fechaFin = new DateTime($fechaFin);
    $diferencia = $fechaIni->diff($fechaFin);
    $mult = $diferencia->format("%y") * 12;
    $dif = $mult + ($diferencia->m + 1);
    return $dif;
}

/**
 * FUNCIÓN PARA OBTENER TODOS LOS MESDE DEL 1 AL 12
 * @return array - ARRAY CON NOMBRE DE MESES
 */
function obtenerTodosMeses() {
    $meses = array();
    for ($i = 1; $i <= 12; $i++) {
        array_push($meses, obtenerNombreMes($i));
    }
    return $meses;
}
