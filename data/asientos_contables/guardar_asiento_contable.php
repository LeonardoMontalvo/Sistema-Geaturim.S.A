<?php

session_start();
include '../../procesos/base.php';
require_once '../centro_costos/guardar_detalles.php';
conectarse();
//error_reporting(0);

if (!comprobarCreditoDebito($_POST['campo4'], $_POST['campo5'])) {
    exit("<b>Los valores de debe y haber no coinciden.<b>");
}

$campo1 = $_POST['campo1'];
$campo2 = $_POST['campo2'];
$campo3 = $_POST['campo3'];
$campo4 = $_POST['campo4'];
$campo5 = $_POST['campo5'];
$conpuntoresult = $_SESSION['PV'];
$iddettran = pg_query("select max(id_transacciones) from transacciones");
$cont = pg_fetch_row($iddettran);
$cont[0] = $cont[0] + 1;
// echo '<br>GUARDAR FACTURA VENTA: <br>' . "insert into transacciones values('" . $cont[0] . "', '$_SESSION[id]','00','$_POST[fecha_actual]','$_POST[hora_actual]', '$_POST[concepto]', '$_POST[total_debe]', '$_POST[total_haber]', '$_POST[diferencia]','$_POST[id_tipo_transaccion]','$_POST[num_transaccion]','Activo','1','$_POST[deposito]','$_POST[observaciones]','$_POST[cuentanum]','$_POST[banco]','$_POST[identificador_cli_pro]','$_POST[valorconcepto]','$conpuntoresult','$_POST[fecha_registro]')";//////////////////////////

$asiento = pg_query("insert into transacciones values('" . $cont[0] . "', '$_SESSION[id]','00','$_POST[fecha_actual]','$_POST[hora_actual]', '$_POST[concepto]', '$_POST[total_debe]', '$_POST[total_haber]', '$_POST[diferencia]','$_POST[id_tipo_transaccion]','$_POST[num_transaccion]','Activo','1','$_POST[deposito]','$_POST[observaciones]','$_POST[cuentanum]','$_POST[banco]','$_POST[identificador_cli_pro]','$_POST[valorconcepto]','$conpuntoresult','$_POST[fecha_registro]')");

if (!empty($asiento) && !empty($_POST['id_centro_costo'])) {
    guardarDetalleCentroCosto($cont[0], $_POST['id_centro_costo'], "transacciones");
}

//DETALLE ASIENTO CONTABLE

$arreglo1 = explode('|', $campo1);
$arreglo2 = explode('|', $campo2);
$arreglo3 = explode('|', $campo3);
$arreglo4 = explode('|', $campo4);
$arreglo5 = explode('|', $campo5);
$nelem = count($arreglo1);

for ($i = 1; $i < $nelem; $i++) {
    $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
    $fila1 = pg_fetch_row($iddettran);
    $fila1[0] = $fila1[0] + 1;
    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $cont[0] . "','" . $arreglo1[$i] . "','" . $arreglo4[$i] . "','" . $arreglo5[$i] . "','Activo')");
}

if ($_POST['id_tipo_transaccion'] == "3") {

    if ($_POST['tipo_pago'] == "EXTERNA") {
        ///////////////////////////////////////////
        for ($i = 1; $i < $nelem; $i++) {
            /////////////////contador  pagos///////////
//            $cont1 = 0;
//            $consulta = pg_query("select max(id_cuentas_pagar) from pagos_pagar");
//            while ($row = pg_fetch_row($consulta)) {
//                $cont1 = $row[0];
//            }
//            $cont1++;
//            ////////////guardar pagos//////// 
////            pg_query("insert into pagos_pagar values('$cont1','1','$_SESSION[id]','" . $cont[0] . "','$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[forma_pago]','$_POST[tipo_pago]','$_POST[num_factura]','FACTURA','$_POST[fecha_factura]','$_POST[totalcxc]','$_POST[valor_pagado]','$_POST[saldo2]','$_POST[observaciones]','Activo','$_POST[ids_fac_cp]')");
//
//            //        pg_query("insert into pagos_pagar values('$cont1','$_POST[id_proveedor]','$_SESSION[id]','$_POST[comprobante]','$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[forma_pago]','$_POST[tipo_pago]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','$arreglo6[$i]','$arreglo7[$i]','$_POST[observaciones]','Activo')");
//            ////////////////////////////////////////
//            //
//            ////////////modificar pagos////////
//            $consulta2 = pg_query("select * from c_pagarexternas where id_c_pagarexternas = '$_POST[ids_fac_cp]'");
//            while ($row = pg_fetch_row($consulta2)) {
//                $saldo = $row[10];
//            }
//
//            $cal = $_POST[saldo2] - $saldo;
//            $format_numero = number_format($cal, 2, '.', '');
//            if ($format_numero == 0.00) {
//                pg_query("Update c_pagarexternas Set saldo='" . $format_numero . "', estado='Cancelado' where id_c_pagarexternas='$_POST[ids_fac_cp]'");
//            } else {
//                pg_query("Update c_pagarexternas Set saldo='" . $format_numero . "' where id_c_pagarexternas='$_POST[ids_fac_cp]'");
//                //////////////////////////////
//            }
        }
        $data = 1;
    } else {
        ///////////////////////////////////////////
        /////////////////contador  pagos///////////
//        $cont1 = 0;
//        $consulta = pg_query("select max(id_cuentas_pagar) from pagos_pagar");
//        while ($row = pg_fetch_row($consulta)) {
//            $cont1 = $row[0];
//        }
//        $cont1++;

        //	 echo '<br>GUARDAR FACTURA VENTA1: <br>' . "insert into pagos_pagar values('$cont1','1','$_SESSION[id]','" . $cont[0] . "','$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[forma_pago]','$_POST[tipo_pago]','$_POST[num_factura]','FACTURA','$_POST[fecha_factura]','$_POST[totalcxc]','$_POST[valor_pagado]','$_POST[saldo2]','$_POST[observaciones]','Activo','$_POST[ids_fac_cp]')";//////////////////////////


        ////////////guardar pagos////////
//        pg_query("insert into pagos_pagar values('$cont1','1','$_SESSION[id]','" . $cont[0] . "','$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[forma_pago]','$_POST[tipo_pago]','$_POST[num_factura]','FACTURA','$_POST[fecha_factura]','$_POST[totalcxc]','$_POST[valor_pagado]','$_POST[saldo2]','$_POST[observaciones]','Activo','$_POST[ids_fac_cp]')");
        ////////////////////////////////////////
        //
        ////////modificar los pagos///
//        $consulta2 = pg_query("select * from pagos_compra where id_pagos_compra = '$_POST[ids_fac_cp]'");
//        while ($row = pg_fetch_row($consulta2)) {
//            $saldo = $row[9];
//        }
//
//        $cal = $saldo - $_POST[valor_pagado];
//        $format_numero = number_format($cal, 2, '.', '');
//
//        if ($format_numero == 0.00) {
//            //         echo '<br>GUARDAR FACTURA VENTA2: <br>' . "Update pagos_compra Set saldo='" . $format_numero . "', estado='Cancelado' where id_pagos_compra = '$_POST[ids_fac_cp]'";//////////////////////////
//
////            pg_query("Update pagos_compra Set saldo='" . $format_numero . "', estado='Cancelado' where id_pagos_compra = '$_POST[ids_fac_cp]'");
//        } else {
//            //         echo '<br>GUARDAR FACTURA VENTA3: <br>' . "Update pagos_compra Set saldo='" . $format_numero . "' where id_pagos_compra = '$_POST[ids_fac_cp]'";//////////////////////////
//
////            pg_query("Update pagos_compra Set saldo='" . $format_numero . "' where id_pagos_compra = '$_POST[ids_fac_cp]'");
//        }





        $data = 1;
    }
} else if ($_POST['id_tipo_transaccion'] == "2") {

    if ($_POST['tipo_pago'] == "EXTERNA") {
        ///////////////////////////////////////////
        for ($i = 1; $i < $nelem; $i++) {
            /////////////////contador  pagos///////////
//            $cont1 = 0;
//            $consulta = pg_query("select max(id_pagos_cobrar) from pagos_cobrar");
//            while ($row = pg_fetch_row($consulta)) {
//                $cont1 = $row[0];
//            }
//            $cont1++;
            ////////////////////////////////////////////
            ////////////guardar pagos////////
            //        pg_query("insert into pagos_pagar values('$cont1','1','$_SESSION[id]','" . $cont[0] . "','$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[forma_pago]','$_POST[tipo_pago]','$_POST[num_factura]','FACTURA','$_POST[fecha_factura]','$_POST[totalcxc]','$_POST[valor_pagado]','$_POST[saldo2]','$_POST[observaciones]','Activo','$_POST[ids_fac_cp]')");
//            pg_query("insert into pagos_cobrar values('$cont1','1','$_SESSION[id]','" . $cont[0] . "','$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[forma_pago]','$_POST[tipo_pago]','$_POST[num_factura]','FACTURA','$_POST[fecha_factura]','$_POST[totalcxc]','$_POST[valor_pagado]','$_POST[saldo2]','$_POST[observaciones]','Activo',$conpuntoresult, '')");
            ////////////////////////////////////////
            //
            ////////////modificar pagos////////
//            $consulta2 = pg_query("select * from c_cobrarexternas where id_c_cobrarexternas = '$_POST[ids_fac_cp]'");
//            while ($row = pg_fetch_row($consulta2)) {
//                $saldo = $row[10];
//            }
//            //////////////////////////////////
//            // $cal =   $arreglo7[$i] - $saldo;
//            //       $format_numero = number_format($cal, 2, '.', '');
//            // $format_numero = $cal;
//            $format_numero = $_POST[saldo2];
//
//            if ($format_numero == 0.00) {
//                pg_query("Update c_cobrarexternas Set saldo='" . $format_numero . "', estado='Cancelado' where id_c_cobrarexternas='$_POST[ids_fac_cp]'");
//            } else {
//                pg_query("Update c_cobrarexternas Set saldo='" . $format_numero . "' where id_c_cobrarexternas='$_POST[ids_fac_cp]'");
//                //////////////////////////////
//            }
        }
        $data = 1;
    } else {
        ///////////////////////////////////////////
//        $variable = 0;
//        $contComprobante = 0;
//        $consulta = pg_query("select max(id_pagos_cobrar) from pagos_cobrar");
//        while ($row = pg_fetch_row($consulta)) {
//            $variable = $row[0];
//        }
        //METODO PARA BUSCAR COMPROBANTE MAYOR
//        $consultaComprobantantemayor = pg_query("select comprobante from pagos_cobrar where id_pagos_cobrar = '$variable'");
//        while ($rowComprobante = pg_fetch_row($consultaComprobantantemayor)) {
//            $contComprobante = $rowComprobante[0];
//        }
//        $contComprobante++;

        for ($i = 1; $i < $nelem; $i++) {

            /////////////////contador  pagos///////////
//            $cont1 = 0;
//            $cont2 = 0;
//
//            $consulta = pg_query("select max(id_pagos_cobrar) from pagos_cobrar");
//            while ($row = pg_fetch_row($consulta)) {
//                $cont1 = $row[0];
//            }
//
//            $cont1++;
            ////////////guardar pagos////////
            //        	 echo '<br>GUARDAR FACTURA VENTA: <br>' . "insert into pagos_cobrar values('$cont1','1','$_SESSION[id]','$contComprobante','$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[forma_pago]','$_POST[tipo_pago]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','$arreglo6[$i]','$arreglo7[$i]','$_POST[observaciones]','Activo',$conpuntoresult)";//////////////////////////
            //	 

//            pg_query("insert into pagos_cobrar values('$cont1','1','$_SESSION[id]','" . $cont[0] . "','$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[forma_pago]','$_POST[tipo_pago]','$_POST[num_factura]','FACTURA','$_POST[fecha_factura]','$_POST[totalcxc]','$_POST[valor_pagado]','$_POST[saldo2]','$_POST[observaciones]','Activo',$conpuntoresult, '')");

            //        pg_query("insert into pagos_cobrar values('$cont1','1','$_SESSION[id]','$contComprobante','$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[forma_pago]','$_POST[tipo_pago]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','$arreglo6[$i]','$arreglo7[$i]','$_POST[observaciones]','Activo',$conpuntoresult, $_POST[bancos]')");
            ////////////////////////////////////////
            //
            ////////modificar los pagos///
//            $consulta2 = pg_query("select * from pagos_venta where id_pagos_venta = '$_POST[ids_fac_cp]'");
//            while ($row = pg_fetch_row($consulta2)) {
//                $saldo = $row[9];
//            }
//
//            $cal = $saldo - $_POST[valor_pagado];
//            $format_numero = number_format($cal, 2, '.', '');
//
//            if ($format_numero == 0) {
//                pg_query("Update pagos_venta Set saldo='" . $format_numero . "', estado='Cancelado' where id_pagos_venta='$_POST[ids_fac_cp]'");
//            } else {
//                pg_query("Update pagos_venta Set saldo='" . $format_numero . "' where id_pagos_venta='$_POST[ids_fac_cp]'");
//            }
            //////////////////////////////
            //
            ///////////modificar detalles pagos //////// 
            /////////////contador pagos////////////////     
//            $consulta4 = pg_query("select max(id_detalles_pagos_interna) from detalles_pagos_internos");
//            while ($row = pg_fetch_row($consulta4)) {
//                $cont2 = $row[0];
//            }
//            $cont2++;
//            //
//            //
//            //////procedimiento 1///////
//            $consulta3 = pg_query("select * from detalle_pagos_venta where id_pagos_venta = '$_POST[ids_fac_cp]' and estado='Activo' order by id_detalle_pagos_venta desc");
//            while ($row = pg_fetch_row($consulta3)) {
//                $id = $row[0];
//                $saldo2 = $row[4];
//                $fecha_mes = $row[2];
//            }
//
//            $cal2 = $saldo2 - $_POST[saldo2];
//            $format_numero2 = number_format($cal2, 2, '.', '');

//            if ($cal2 <= 0) {
////                pg_query("Update detalle_pagos_venta Set saldo='0.00', estado='Cancelado'  where id_detalle_pagos_venta = '" . $id . "' and id_pagos_venta='$_POST[ids_fac_cp]' and estado='Activo' ");
////                pg_query("insert into detalles_pagos_internos values('$cont2','$cont1','$fecha_mes','$saldo2','0.00','Pasivo')");
////                $cont2++;
//            } else {
////                pg_query("Update detalle_pagos_venta Set saldo='" . $format_numero2 . "' where id_detalle_pagos_venta = '" . $id . "' and id_pagos_venta='$_POST[ids_fac_cp]' and estado='Activo' ");
////                pg_query("insert into detalles_pagos_internos values('$cont2','$cont1','$fecha_mes','" . abs(($saldo2 - $cal2)) . "','$cal2','Activo')");
////                $cont2++;
//            }
            ////////////////////////////////////////
            //
            //////procedimiento 2///////
//            if ($cal2 < 0) {
//                $consulta3 = pg_query("select * from detalle_pagos_venta where id_pagos_venta = '$_POST[ids_fac_cp]' and estado='Activo' order by id_detalle_pagos_venta desc");
//                while ($row = pg_fetch_row($consulta3)) {
//                    $ids = $row[0];
//                    $saldo3 = $row[4];
//                    $fecha_mes2 = $row[2];
//                }
//
//                $cal3 = ($saldo3 - (abs($cal2)));
//                $format_numero3 = number_format($cal3, 2, '.', '');
//
//                if ($cal3 <= 0) {
////                    pg_query("Update detalle_pagos_venta Set saldo='0.00' , estado='Cancelado'  where id_detalle_pagos_venta = '" . $ids . "' and id_pagos_venta='$_POST[ids_fac_cp]' and estado='Activo' ");
////                    pg_query("insert into detalles_pagos_internos values('$cont2','$cont1','$fecha_mes2','$saldo3','0.00','Pasivo')");
////                    $cont2++;
//                } else {
////                    pg_query("Update detalle_pagos_venta Set saldo='" . $format_numero3 . "' where id_detalle_pagos_venta = '" . $ids . "' and id_pagos_venta='$_POST[ids_fac_cp]' and estado='Activo' ");
////                    pg_query("insert into detalles_pagos_internos values('$cont2','$cont1','$fecha_mes2','" . abs($cal2) . "','$cal3','Activo')");
////                    $cont2++;
//                }
//            }
            ////////////////////////////////
            //
            //////procedimiento 3///////
//            if ($cal3 < 0) {
//                $consulta3 = pg_query("select * from detalle_pagos_venta where id_pagos_venta = '$_POST[ids_fac_cp]' and estado='Activo' order by id_detalle_pagos_venta desc");
//                while ($row = pg_fetch_row($consulta3)) {
//                    $idss = $row[0];
//                    $saldo4 = $row[4];
//                    $fecha_mes3 = $row[2];
//                }
//                //////procedimiento///////
//                $cal4 = ($saldo4 - (abs($cal3)));
//                $format_numero4 = number_format($cal4, 2, '.', '');
//
//                if ($cal4 <= 0.00) {
//
////                    pg_query("Update detalle_pagos_venta Set saldo='0.00' , estado='Cancelado'  where id_detalle_pagos_venta = '" . $idss . "' and id_pagos_venta='$_POST[ids_fac_cp]' and estado='Activo' ");
////                    pg_query("insert into detalles_pagos_internos values('$cont2','$cont1','$fecha_mes3','$saldo4','0.00','Pasivo')");
////                    $cont2++;
//                } else {
////                    pg_query("Update detalle_pagos_venta Set saldo='" . $format_numero4 . "' where id_detalle_pagos_venta = '" . $idss . "' and id_pagos_venta='$_POST[ids_fac_cp]' and estado='Activo' ");
////                    pg_query("insert into detalles_pagos_internos values('$cont2','$cont1','$fecha_mes3','" . abs($cal3) . "','$cal4','Activo')");
////                    $cont2++;
//                }
//            }
        }
        $data = 1;
    }
}



$data = 1;

echo $cont[0];

function comprobarCreditoDebito($creditostr, $debitostr)
{

    $credito = explode("|", $creditostr);
    $credito = array_splice($credito, 1, count($credito));
    $debito = explode("|", $debitostr);
    $debito = array_splice($debito, 1, count($debito));

    $sumad = array_sum($debito);
    $sumac = array_sum($credito);

    $dif = round($sumad, 2) - round($sumac, 2);
    if ($dif == 0) {
        return true;
    }
    return false;
}
