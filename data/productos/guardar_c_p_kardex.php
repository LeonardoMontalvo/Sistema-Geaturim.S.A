<?php

session_start();
include '../../procesos/base.php';
require_once '../../procesos/fecha.php';
require_once '../../procesos/kardexValorizado.php';
$conexion = conectarse();
conectarse();
//error_reporting(0);



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sql = "select cod_productos,precio_compra from productos  where estado ='Activo'  and  imagen='1' order by cod_productos ";


$resultDPB = pg_query($sql);
if (pg_num_rows($resultDPB) > 0) {
    while ($rowDPB = pg_fetch_assoc($resultDPB)) {
        $conexion = conectarse();

        $cantidad_total = '0.00';
        $precio_unitario_total = number_format($rowDPB[precio_compra], 4, '.', '');
        $precio_total_total = number_format($cantidad_total * $precio_unitario_total, 4, '.', '');
        procesarKardexEntrada($rowDPB['cod_productos'], "C.P", '0.00', '0.00', $precio_unitario_total, 5, $_SESSION['PV'], 'C.P', $rowDPB[cod_productos], $precio_total_total, NULL, NULL, '', NULL, NULL, NULL, $_SESSION['id']);
    }
}

function obtenerId() {
    $conexion = conectarse();

    $consulta = pg_query("select max(id_kardex) from kardex");
    $id = (pg_fetch_row($consulta)[0] + 1);
    return $id;
}

///////////////////////////////////////////////////////
?>