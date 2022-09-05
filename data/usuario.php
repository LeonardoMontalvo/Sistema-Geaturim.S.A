<?php


$accion = (isset($_GET['accion'])) ? $_GET['accion'] : "";
session_start(); 
if($accion == "salir"){
    session_unset();
    session_destroy();
    header ("Location: ../data");
}

?>
