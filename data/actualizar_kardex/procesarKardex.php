<?php

session_start();
include '../../procesos/base.php';
include_once './funcionesProcesarKardex.php';
conectarse();
error_reporting(0);
date_default_timezone_set('America/Guayaquil');
$fechaActual = date('Y-m-d', time());
$fechaIngreso;
$idKardexTMP;
$bodega;
   
if (empty($_POST['acc'])) {
      
    if (isset($_POST['op'])) {
           
        $bodega = '1';
        if ($_POST['op'] == 'I' || $_POST['op'] == 'INV') {
            if (isset($_POST['comprobante']) && $_POST['comprobante'] != '') {
                echo buscarKardex($_POST['comprobante'], $bodega, $_POST['op']);
            } else {
                echo json_encode(array("data" => 0, "error" => 'Ingrese el número de comprobante'));
            }
        }
        if ($_POST['op'] == 's') {
           
            echo buscarKardexPorProductoSumar($bodega, $_POST['obj']);
        }
        if ($_POST['op'] == 'e') {
               
            echo buscarKardexPorProductoEliminar($bodega, $_POST['obj']);
        }
        if ($_POST['op'] == 'aj') {
                
            echo buscarKardexPorProductoAjuste($bodega, $_POST['obj']);
        }
        if ($_POST['op'] == 'all') {
                
            echo procesarTodoKardex($bodega, $_POST['fIni'], $_POST['fFin']);
        }
    } else {
        echo json_encode(array("data" => 0, "error" => 'Seleccione una opción a procesar'));
    }
} else {
    if (isset($_POST['acc']) && $_POST['acc'] != '') {
        $bodega = '1';
        echo json_encode(array("data" => 1, "lista" => buscarKardexPorProducto($bodega, $_POST['product'])));
    }
}