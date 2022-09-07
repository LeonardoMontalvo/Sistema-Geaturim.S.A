<?php


$accion = (isset($_GET['accion'])) ? $_GET['accion'] : "";
session_start();
if ($accion == "salir") {
    session_unset();
    session_destroy();
    $vacookie = $_COOKIE["valores_app"];

    if (!empty($vacookie)) {
        $valores = json_decode($vacookie,true);
        if (!empty($valores["url_esquema"])) {
            header("Location: ".$valores["url_esquema"]);
            exit();
        }
    }
    header("Location: ../data");
}
