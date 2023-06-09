<?php

session_start();
include '../../procesos/base.php';

conectarse();
//error_reporting(0);
date_default_timezone_set('America/Guayaquil');

$fecha = date("Y-m-d");
$hora = date("H:i:s");
$idusuario = $_SESSION["id"];
$idpv = $_SESSION["PV"];
$idcierre = $_POST["comprobante"];


//echo "insert into cierre_caja values('$cont2','$_POST[fecha_actual]','$_POST[hora_actual]','$cont1','$_SESSION[id]','$_SESSION[PV]','$arreglo1[$i]','$arreglo3[$i]','$arreglo4[$i]','$_POST[total_valor]','$_POST[total_valor]','$_POST[total_valor]','$_POST[observaciones]','Activo')";

/* $cstock = json_encode(getStock());

if (pg_query("insert into cierre_caja values('$cont2','$_POST[fecha_actual]','$_POST[hora_actual]','$cont1','$_SESSION[id]','$idpv'"
    . ",'$_POST[denominacion_cien]','$_POST[cantidad_cien]','$_POST[valor_cien]','$_POST[total_cien]'"
    . ",'$_POST[denominacion_cincuenta]','$_POST[cantidad_cincuenta]','$_POST[valor_cincuenta]','$_POST[total_cincuenta]'"
    . ",'$_POST[denominacion_veinte]','$_POST[cantidad_veinte]','$_POST[valor_veinte]','$_POST[total_veinte]'"
    . ",'$_POST[denominacion_diez]','$_POST[cantidad_diez]','$_POST[valor_diez]','$_POST[total_diez]'"
    . ",'$_POST[denominacion_cinco]','$_POST[cantidad_cinco]','$_POST[valor_cinco]','$_POST[total_cinco]'"
    . ",'$_POST[denominacion_uno]','$_POST[cantidad_uno]','$_POST[valor_uno]','$_POST[total_uno]'"
    . ",'$_POST[denominacion_cero_cincuenta]','$_POST[cantidad_cero_cincuenta]','$_POST[valor_cero_cincuenta]','$_POST[total_cero_cincuenta]'"
    . ",'$_POST[denominacion_cero_veinticinco]','$_POST[cantidad_cero_veinticinco]','$_POST[valor_cero_veinticinco]','$_POST[total_cero_veinticinco]'"
    . ",'$_POST[denominacion_cero_diez]','$_POST[cantidad_cero_diez]','$_POST[valor_cero_diez]','$_POST[total_cero_diez]'"
    . ",'$_POST[denominacion_cero_cinco]','$_POST[cantidad_cero_cinco]','$_POST[valor_cero_cinco]','$_POST[total_cero_cinco]'"
    . ",'$_POST[denominacion_cero_uno]','$_POST[cantidad_cero_uno]','$_POST[valor_cero_uno]','$_POST[total_cero_uno]'"
    . ",'$_POST[total_valor]','$_POST[diario_caja_text]','$_POST[observaciones]','Activo','$_POST[monto_apertura]'"
    . ", '$cstock')")) {
    $data = 1;
} */




echo json_encode(guardarCierreCaja());

function getStock()
{
    global $idpv;
    $sql = "select*from detalle_producto_bodega
    where id_bodega=$idpv;";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (empty($rows)) {
        return [];
    }
    return $rows;
}

function guardarCierreCaja()
{
    global $fecha, $hora, $idcierre;
    $cstock = json_encode(getStock());
    $sql = "
    UPDATE cierre_caja
    SET  
        denominacion_cien='$_POST[denominacion_cien]', 
        cantidad_cien='$_POST[cantidad_cien]', 
        valor_cien='$_POST[valor_cien]',
        total_cantidad_cien='$_POST[total_cien]',
        denominacion_cincuenta='$_POST[denominacion_cincuenta]', 
        cantidad_cincuenta='$_POST[cantidad_cincuenta]',
        valor_cincuenta='$_POST[valor_cincuenta]',
        total_cantidad_cincuenta='$_POST[total_cincuenta]', 
        denominacion_veinte='$_POST[denominacion_veinte]',
        cantidad_veinte='$_POST[cantidad_veinte]',
        valor_veinte='$_POST[valor_veinte]', 
        total_cantidad_veinte='$_POST[total_veinte]', 
        denominacion_diez='$_POST[denominacion_diez]',
        cantidad_diez='$_POST[cantidad_diez]',
        valor_diez='$_POST[valor_diez]',
        total_cantidad_diez='$_POST[total_diez]',
        denominacion_cinco='$_POST[denominacion_cinco]',
        cantidad_cinco='$_POST[cantidad_cinco]',
        valor_cinco='$_POST[valor_cinco]',
        total_cantidad_cinco='$_POST[total_cinco]',
        denominacion_uno='$_POST[denominacion_uno]',
        cantidad_uno='$_POST[cantidad_uno]',
        valor_uno='$_POST[valor_uno]',
        total_cantidad_uno='$_POST[total_uno]',
        denominacion_cero_cincuenta='$_POST[denominacion_cero_cincuenta]',
        cantidad_cero_cincuenta='$_POST[cantidad_cero_cincuenta]',
        valor_cero_cincuenta='$_POST[valor_cero_cincuenta]',
        total_cantidad_cero_cincuenta='$_POST[total_cero_cincuenta]',
        denominacion_cero_veinticinco='$_POST[denominacion_cero_veinticinco]',
        cantidad_cero_veinticinco='$_POST[cantidad_cero_veinticinco]',
        valor_cero_veinticinco='$_POST[valor_cero_veinticinco]',
        total_cantidad_cero_veinticinco='$_POST[total_cero_veinticinco]',
        denominacion_cero_diez='$_POST[denominacion_cero_diez]',
        cantidad_cero_diez='$_POST[cantidad_cero_diez]',
        valor_cero_diez='$_POST[valor_cero_diez]',
        total_cantidad_cero_diez='$_POST[total_cero_diez]',
        denominacion_cero_cinco='$_POST[denominacion_cero_cinco]',
        cantidad_cero_cinco='$_POST[cantidad_cero_cinco]',
        valor_cero_cinco='$_POST[valor_cero_cinco]',
        total_cantidad_cero_cinco='$_POST[total_cero_cinco]',
        denominacion_cero_uno='$_POST[denominacion_cero_uno]',
        cantidad_cero_uno='$_POST[cantidad_cero_uno]',
        valor_cero_uno='$_POST[valor_cero_uno]',
        total_cantidad_cero_uno='$_POST[total_cero_uno]',
        total_valor_ingresado='$_POST[total_valor]',
        totales_dierio_caja='$_POST[diario_caja_text]',
        fecha_cierre='$fecha', 
        hora_cierre='$hora', captura_stock_ciere='$cstock', observacion_cierre='$_POST[observaciones]'
        WHERE id_cierre_caja=$idcierre;
    ";
    $res = pg_query($sql);
    if (empty($res)) {
        return 0;
    }
    return $idcierre;
}
