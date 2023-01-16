<?php

session_start();
include_once '../../procesos/fecha.php';
include '../../procesos/base.php';
include_once '../../procesos/kardexValorizado.php';
require_once '../../procesos/detalleProductosBodega.php';
conectarse();
//error_reporting(0);
date_default_timezone_set('America/Guayaquil');
$dt = new DateTime();
$dt1 = $dt->format('Y-m-d');

$conpuntoresult = $_SESSION['PV'];

if ($_POST["tipo_venta"] == "FACTURA") {
    // datos detalle factura
    $campo1 = $_POST['campo1'];
    $campo2 = $_POST['campo2'];
    $campo3 = $_POST['campo3'];
    $campo4 = $_POST['campo4'];
    $campo5 = $_POST['campo5'];
    $campo6 = $_POST['campo6'];

    $campo7 = $_POST['campo7'];
    $campo8 = $_POST['campo8'];

    // modificar estado factura venta
    pg_query("Update devolucion_venta Set estado = 'Pasivo' where id_devolucion_venta = '$_POST[comprobante]'");

    //
    ////////////modificar cantidades////////
    $arreglo1 = explode('|', $campo1);
    $arreglo2 = explode('|', $campo2);
    $arreglo3 = explode('|', $campo3);
    $arreglo4 = explode('|', $campo4);
    $arreglo5 = explode('|', $campo5);
    $arreglo6 = explode('|', $campo6);

    $arreglo7 = explode('|', $campo7);
    $arreglo8 = explode('|', $campo8);
    $nelem = count($arreglo1);

    for ($i = 1; $i < $nelem; $i++) {
        // consulta productos
        $stock = 0;
        $consulta_v = pg_query("select * from detalle_producto_bodega where id_bodega=$conpuntoresult and cod_productos=$arreglo1[$i]");
        while ($row = pg_fetch_row($consulta_v)) {
            $cod_pro = $row[1];
            $id_bod = $row[2];
            $stock = $row[6];
        }
        // echo '<br>GUARDAR FACTURA VENTA123232: <br>' . $arreglo1[$i], $dt1, 'ANULADA D.V:' . $_POST['num_factura'], $arreglo2[$i], $stock, $stock, 'Activo', $conpuntoresult, 'ADV', $_POST[comprobante];
        //
        if ($arreglo7[$i] != 0) {
            $arreglo2[$i] = $arreglo7[$i];
        } else {
            $arreglo2[$i] = $arreglo2[$i];
        }
        $cliente1 = $_POST['id_cliente'];
        procesarKardexSalida($arreglo1[$i], 'ANULADA D.V.F.V:' . $_POST['num_factura'], $arreglo2[$i], obtenerStock($arreglo1[$i], $_SESSION['PV']), NULL, 'Activo', $conpuntoresult, 'ADVFV', $_POST['comprobante'], $arreglo5[$i], NULL, NULL, $cliente1, '', NULL, NULL, $_SESSION['id']);

//        procesarKardexValorizadoSalida($arreglo1[$i], $dt1, 'ANULADA D.V:' . $_POST['num_factura'], $arreglo2[$i], $stock, $stock, 'Activo', $conpuntoresult, 'ADV', $_POST['comprobante'], 'DEBE', 'HABER');


        $cal = $stock - $arreglo2[$i];

        if ($cod_pro == $arreglo1[$i] && $id_bod == $conpuntoresult) {
            pg_query("Update detalle_producto_bodega Set fecha=' $dt1 ' ,hora='" . obtenerHoraActual() . "', stock='" . $cal . "' where cod_productos='" . $arreglo1[$i] . "' and id_bodega='" . $conpuntoresult . "'");
        }
        $cont_k = 0;
        $consulta_k = pg_query("select max(id_kardex) from kardex");
        while ($row = pg_fetch_row($consulta_k)) {
            $cont_k = $row[0];
        }
        $cont_k++;

        $consulta_v = pg_query("select * from detalle_producto_bodega where id_bodega=$conpuntoresult and cod_productos=$arreglo1[$i]");
        while ($row = pg_fetch_row($consulta_v)) {
            $cod_pro = $row[1];
            $id_bod = $row[2];
            $stock = $row[6];
        }
        if ($arreglo1[$i] != '') {

            //        	 echo '<br>GUARDAR FACTURA VENTA1: <br>' . "insert into kardex values('$cont_k','$dt1', '" . 'ANULADA F.V:' . $_POST['num_factura'] . "' ,'$arreglo2[$i]','" . number_format($arreglo3[$i], 4, ".", "") . "'"
            //                . ",'" . number_format($arreglo5[$i], 4, ".", "") . "','$arreglo1[$i]','$stock','Activo','0','0','$_POST[id_cliente]','$cont1','A','$conpuntoresult','$_POST[anulacionComentario]')";//////////////////////////
            //	 
            //	 
            // guardar kardex
//            pg_query("insert into kardex values('$cont_k','$dt1', '" . 'ANULADA D.V:' . $_POST['num_factura'] . "' ,'$arreglo2[$i]','" . number_format($arreglo3[$i], 4, ".", "") . "'"
//                    . ",'" . number_format($arreglo5[$i], 4, ".", "") . "','$arreglo1[$i]','$stock','Activo','0','0','$_POST[id_cliente]','$_POST[comprobante]','ADV','$conpuntoresult','$_POST[anulacionComentario]')");
            // fin
        }
    }
} elseif ($_POST["tipo_venta"] == "NOTA") {
    // datos detalle factura
    $campo1 = $_POST['campo1'];
    $campo2 = $_POST['campo2'];

    // modificar estado factura venta
    pg_query("Update devolucion_venta Set estado = 'Pasivo' where id_devolucion_venta = '$_POST[comprobante]'");

    // modificar estado factura venta
    // pg_query("Update facturas_novalidas Set estado = 'Pasivo', fecha_actual='$_POST[fecha_anulacion]' where id_facturas_novalidas = '$_POST[comprobante]'");
    // modificar cantidades
    $arreglo1 = explode('|', $campo1);
    $arreglo2 = explode('|', $campo2);
    $nelem = count($arreglo1);

    for ($i = 1; $i < $nelem; $i++) {
        // consulta productos
        $consulta = pg_query("select * from productos where cod_productos = '$arreglo1[$i]'");
        while ($row = pg_fetch_row($consulta)) {
            $stock = $row[13];
        }

        // modificar productos
        $consulta2 = pg_query("select * from productos where cod_productos = '$arreglo1[$i]'");
        while ($row = pg_fetch_row($consulta2)) {
            $stock = $row[13];
        }
         if ($arreglo7[$i] != 0) {
            $arreglo2[$i] = $arreglo7[$i];
        } else {
            $arreglo2[$i] = $arreglo2[$i];
        }
        $cliente1 = $_POST['id_cliente'];
        procesarKardexSalida($arreglo1[$i], 'ANULADA D.V.N.V:' . $_POST['num_factura'], $arreglo2[$i], obtenerStock($arreglo1[$i], $_SESSION['PV']), NULL, 'Activo', $conpuntoresult, 'ADVNV', $_POST['comprobante'], $arreglo5[$i], NULL, NULL, $cliente1, '', NULL, NULL, $_SESSION['id']);

//        procesarKardexValorizadoSalida($arreglo1[$i], $dt1, 'ANULADA D.V:' . $_POST['num_factura'], $arreglo2[$i], $stock, $stock, 'Activo', $conpuntoresult, 'ADV', $_POST['comprobante'], 'DEBE', 'HABER');


        $cal = $stock - $arreglo2[$i];

        if ($cod_pro == $arreglo1[$i] && $id_bod == $conpuntoresult) {
            pg_query("Update detalle_producto_bodega Set fecha=' $dt1 ' ,hora='" . obtenerHoraActual() . "', stock='" . $cal . "' where cod_productos='" . $arreglo1[$i] . "' and id_bodega='" . $conpuntoresult . "'");
        }
        // fin
    }
}


$data = 1;

///////////////////////////////////////////
///////////ASIENTO CONTABLE ANULACION FACTURA
if ($_POST["tipo_venta"] == "FACTURA") {
    //    echo 'factura1::' . "update transacciones set estado='Pasivo' where comprobante='$_POST[comprobante]'  and id_empresa='" . $conpuntoresult . "' and identificador_cli_pro='VEN'  ";
    pg_query("update transacciones set estado='Pasivo' where comprobante='$_POST[comprobante]'  and id_empresa='" . $conpuntoresult . "' and identificador_cli_pro='DVFV'  ");
}

///////////ASIENTO CONTABLE ANULACION NOTA DE VENTA
if ($_POST["tipo_venta"] == "NOTA") {
    //    echo 'nota::' . "update transacciones set estado='Pasivo' where comprobante='$_POST[comprobante]'  and id_empresa='" . $conpuntoresult . "' and identificador_cli_pro='NV' ";
    pg_query("update transacciones set estado='Pasivo' where comprobante='$_POST[comprobante]'  and id_empresa='" . $conpuntoresult . "' and identificador_cli_pro='DVNV' ");
}

echo $data;
