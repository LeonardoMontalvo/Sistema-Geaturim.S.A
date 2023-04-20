<?php

function conectarse()
{

    if (!($conexion = pg_pconnect("host=localhost port=5432 dbname=districar18042023 user=postgres password=root"))) {

        exit();
    } else {
        if (!empty(obtenerCookie("esquema"))) {
            pg_query("SET search_path TO '" . obtenerCookie("esquema") . "';");
        }
    }
    return $conexion;
}
//function conectarse_p()
//{
//    if (!($conexion = pg_pconnect("host=localhost port=5432 dbname=sysweb_hashem user=postgres password=root"))) {
//        exit();
//    } else {
//        if (!empty(obtenerCookie("esquema"))) {
//            pg_query("SET search_path TO '" . obtenerCookie("esquema") . "';");
//        }
//    }
//    return $conexion;
//}

function obtenerCookie($cookie_name)
{
    if (empty($_COOKIE[$cookie_name])) {
        return null;
    }
    return $_COOKIE[$cookie_name];
}

conectarse();
