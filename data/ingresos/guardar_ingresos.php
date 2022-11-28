<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
require_once '../../procesos/kardexValorizado.php';
require_once '../../procesos/detalleProductosBodega.php';
require_once '../../procesos/fecha.php';
conectarse();
error_reporting(0);
/////datos detalle factura/////
$campo1 = $_POST['campo1'];
$campo2 = $_POST['campo2'];
$campo3 = $_POST['campo3'];
$campo4 = $_POST['campo4'];
$campo5 = $_POST['campo5'];
$campo6 = $_POST['campo6'];
///////////////////////////////
$conpuntoresult = $_SESSION['PV'];

/////////////////contador ingresos///////////
$cont1 = 0;
$consulta = pg_query("select max(id_ingresos) from ingresos");
while ($row = pg_fetch_row($consulta)) {
    $cont1 = $row[0];
}
$cont1++;
///////////////////////////////////////////////
if($_POST['tipo_persona']==3){
    $_POST['id_cliente']=1;
}else{
    $_POST['id_cliente']=$_POST['id_cliente'];
}
////////////guardar ingresos////////
$guardari = guardarIngreso($cont1, $conpuntoresult, $_SESSION['id'], $cont1, $_POST['origen'], $_POST['destino'], $_POST['tarifa0'], $_POST['tarifa12'], $_POST['iva'], $_POST['desc'], $_POST['tot'], $_POST['observaciones'], $_POST['id_cliente'], $_POST['tipo_persona']);
////////////////////////////////////
//
////////////agregar ingresos////////
$arreglo1 = explode('|', $campo1);
$arreglo2 = explode('|', $campo2);
$arreglo3 = explode('|', $campo3);
$arreglo4 = explode('|', $campo4);
$arreglo5 = explode('|', $campo5);
$arreglo6 = explode('|', $campo6);
$nelem = count($arreglo1);
$docu = str_pad($cont1, 9, "0", STR_PAD_LEFT);

/////////////////////////////////////
for ($i = 1; $i < $nelem; $i++) {
    if (!empty($arreglo1[$i])) {
        guardarDetalleIngreso($cont1, $arreglo1[$i], $arreglo2[$i], $arreglo3[$i], $arreglo4[$i], $arreglo5[$i]);
        ///////////////////////////////////////77
        if ($_POST['origen'] != NULL && $_POST['destino'] != NULL) {
            procesarKardexEntrada($arreglo1[$i], 'T.I:' . $docu, $arreglo2[$i], obtenerStock($arreglo1[$i], $_POST['destino']), $arreglo3[$i], 'Activo', $_POST['destino'], 'I', $cont1, $arreglo5[$i], $_POST['origen'], $_POST['destino'], '', NULL, NULL, NULL, $_SESSION['id']);
            procesarKardexSalida($arreglo1[$i], 'T.E:' . $docu, $arreglo2[$i], obtenerStock($arreglo1[$i], $_POST['origen']), $arreglo3[$i], 'Activo', $_POST['origen'], 'E.I', $cont1, $arreglo5[$i], $_POST['origen'], $_POST['destino'], NULL, '', NULL, NULL, $_SESSION['id']);
        } else {
            // guardar detalle productos bodega
            $cod_pro = 0;
            $id_bod = 0;
            $stock = 0;
            $consulta_v = pg_query("select * from detalle_producto_bodega where id_bodega=$conpuntoresult and cod_productos=$arreglo1[$i]");
            while ($row = pg_fetch_row($consulta_v)) {
                $cod_pro = $row[1];
                $id_bod = $row[2];
                $stock = $row[6];
            }
            $cal = $stock + $arreglo2[$i];

            if ($cod_pro == $arreglo1[$i] && $id_bod == $conpuntoresult) {
                procesarKardexEntrada($arreglo1[$i], 'T.I:' . $docu, $arreglo2[$i], obtenerStock($arreglo1[$i], $conpuntoresult), $arreglo3[$i], 'Activo', $conpuntoresult, 'I', $cont1, $arreglo5[$i], NULL, NULL, '', NULL, NULL, '', $_SESSION['id']);
            } else {
                procesarKardexEntrada($arreglo1[$i], 'T.I:' . $docu, $arreglo2[$i], obtenerStock($arreglo1[$i], $conpuntoresult), $arreglo3[$i], 'Activo', $conpuntoresult, 'I', $cont1, $arreglo5[$i], NULL, NULL, '', NULL, NULL, '', $_SESSION['id']);
            }
        }
    }
}

$data = $cont1;
if (!!!$guardari) {
    $data = "error";
}
echo $data;

function guardarIngreso($id, $bodega, $usuario, $comprobante, $origen, $destino, $tarifa0, $tarifa12, $iva, $descuento, $total, $observacion) {
    $sql = "INSERT INTO ingresos(id_ingresos, id_empresa, id_usuario, comprobante, fecha_actual, hora_actual, origen, destino, tarifa0, tarifa12, "
            . "iva_ingreso, descuento_ingreso, total_ingreso, observaciones, estado) "
            . "VALUES ($id, $bodega, $usuario, '$comprobante','" . obtenerFechaActual() . "','" . obtenerHoraActual() . "'," . ($origen == NULL ? "NULL" : $origen) . ", "
            . "" . ($destino == NULL ? "NULL" : $destino) . "," . number_format($tarifa0, 4, '.', '') . ", " . number_format($tarifa12, 4, '.', '') . ", "
            . "" . number_format($iva, 4, '.', '') . ", $descuento, $total, '$observacion', 'Activo')";
    return pg_query($sql);
    // Auditoria
    insert_registro('CREACION INGRESO CON ID: ' . $id . ', CON UN TOTAL DE: ' . $total);
}

function guardarDetalleIngreso($ingreso, $producto, $cantidad, $costo, $descuento, $total) {
    $sql = "INSERT INTO detalle_ingreso(id_detalle_ingreso, id_ingresos, cod_productos, cantidad, precio_costo, descuento, total, estado) "
            . "VALUES (" . obtenerIdDetalleIngreso() . ", $ingreso, $producto, " . number_format($cantidad, 2, '.', '') . ", " . number_format($costo, 4, '.', '') . ""
            . ", " . number_format($descuento, 4, '.', '') . ", " . number_format($total, 4, '.', '') . ", 'Activo')";
    pg_query($sql);
    // Auditoria
    insert_registro('CREACION DETALLE DEL INGRESO CON ID: ' . $ingreso . ', DE ' . $cantidad . ' PRODUCTO/S: ' . $producto . ', CON UN TOTAL DE: ' . $total);
}

function obtenerIdDetalleIngreso() {
    $consulta = pg_query("select max(id_detalle_ingreso) from detalle_ingreso");
    $id = (pg_fetch_row($consulta)[0] + 1);
    return $id;
}



?>