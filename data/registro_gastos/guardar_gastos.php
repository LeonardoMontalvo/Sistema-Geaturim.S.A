<?php

session_start();
include '../../procesos/base.php';
require_once '../../procesos/pagosCompra.php';
require_once '../centro_costos/guardar_detalles.php';
conectarse();
error_reporting(0);
$conpuntoresult = $_SESSION['PV'];
//////////contador gastos///////
$conta = 0;
$consulta = pg_query("select max(id_gastos) from gastos");
while ($row = pg_fetch_row($consulta)) {
    $conta = $row[0];
}
$conta++;
$cal = $_POST[valor];
$valor = number_format($cal, 2, '.', '');
$cal = $_POST[subtotal];
$subtotal = number_format($cal, 2, '.', '');
$cal = $_POST[iva];
$iva = number_format($cal, 2, '.', '');
$forma = $_POST['formascc'];
///////////////////////////////
//$cuenta = $_POST[idCuenta];
// echo '<br>GUARDAR FACTURA VENTA: <br>' . "insert into gastos values('$conta','$_SESSION[id]','$_POST[num_factura]','$conta','$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[fecha_emision]','$_POST[descripcion]','$subtotal','$iva','$valor','Activo','$_POST[proveedor]','$_POST[deposito]','$_POST[banco]','$_POST[num_cuenta]','$_POST[num_autorizacion]','1','FACTURA','$_POST[serie]','$_POST[formas]','$_POST[tarifa0]','$_POST[tarifa12]','$_POST[iva]','$_POST[desc]','$_POST[tot]','$_POST[observaciones]','$_POST[pago_ats]',1,'1','1','1','1','$_POST[formascc]', '$_POST[idCuenta]','$conpuntoresult')";//////////////////////////

if ($forma == "otros") {
} else {
    pg_query("insert into gastos values('$conta','$_SESSION[id]','$_POST[num_factura]','$conta','$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[fecha_emision]','$_POST[descripcion]','$subtotal','$iva','$valor','Activo','$_POST[proveedor]','$_POST[deposito]','$_POST[banco]','$_POST[num_cuenta]','$_POST[num_autorizacion]','1','$_POST[tipo_comprobante]','$_POST[serie]','$_POST[formas]','$_POST[tarifa0]','$_POST[tarifa12]','$_POST[iva]','$_POST[desc]','$_POST[tot]','$_POST[observaciones]','$_POST[pago_ats]',1,'1','1','1','1','$_POST[formascc]', '1','$conpuntoresult')");
}
$campo1 = $_POST['campo1'];
$campo2 = $_POST['campo2'];
$campo3 = $_POST['campo3'];
$campo4 = $_POST['campo4'];
$campo5 = $_POST['campo5'];
$campo6 = $_POST['campo6'];
$campo7 = $_POST['campo7'];
$campo8 = $_POST['campo8'];
$arreglo1 = explode('|', $campo1);
$arreglo2 = explode('|', $campo2);
$arreglo3 = explode('|', $campo3);
$arreglo4 = explode('|', $campo4);
$arreglo5 = explode('|', $campo5);
$arreglo6 = explode('|', $campo6);
$arreglo7 = explode('|', $campo7);
$arreglo8 = explode('|', $campo8);
$nelem = count($arreglo1);
$sumaSubtotalTarifa12 = 0;
$sumaSubtotalTarifa0 = 0;
$contTarifa12 = 0;
$contTarifa0 = 0;

if ($forma == "otros") {
    $consulta_mixto = pg_query("select sum(x.sum) from (select formas_pago_mixto_g.forma_pago,sum(formas_pago_mixto_g.valor) from gastos, formas_pago_mixto_g 
where gastos.id_gastos=formas_pago_mixto_g.id_gastos and gastos.id_gastos='$conta' 
and (formas_pago_mixto_g.forma_pago='CREDITO' ) GROUP BY formas_pago_mixto_g.forma_pago
)x");
    while ($row = pg_fetch_row($consulta_mixto)) {
        //                    $cont2_mixto_contado = $row[0];
        $valor_contado = $row[0];
    }
    if ($valor_contado != "") {
        // variables pagos
        $adelanto = '0.00';
        $meses = $_POST['meses'];
        $total = $valor_contado;
    }
    $monto = $total;
    $format = number_format($monto, 2, '.', '');




    $cont2 = 0;
    $consulta = pg_query("select max(id_pagos_compra) from pagos_compra");
    while ($row = pg_fetch_row($consulta)) {
        $cont2 = $row[0];
    }
    $cont2++;
    // fin
    // variables pagos
    $total = $format;
    guardarPagosCompra($_POST['proveedor'], $conta, $_SESSION['id'], $_POST['fecha_actual'], 0, 0, 'FACTURA', $total, $total, 'Activo', 'G');

    /////////////////////////guardar gastos///////////////////
    for ($i = 1; $i < $nelem; $i++) {



        //        $consulta_bien_servi = pg_query(" select bien_servicios from productos where cod_productos=$arreglo1[$i]");
        //        while ($row = pg_fetch_row($consulta_bien_servi)) {
        //            $valor_Servicio = $row[0];
        //        }
        //        guardarDetallaGasto($conta, '1', '0', $arreglo6[$i], 0, $arreglo6[$i], 'Activo', $arreglo7[$i], $arreglo2[$i], $arreglo5[$i], $arreglo1[$i], $arreglo3[$i], $arreglo4[$i]);
        ////////////////////////
        //Asiento Contable 

        //
        //        $cuenta = pg_query("select tipo_iva,id_cuenta from detalle_gastos,gastos where detalle_gastos.id_gastos=gastos.id_gastos and detalle_gastos.id_cuenta='" . $arreglo2[$i] . "' and detalle_gastos.bien_servicio='B' and gastos.id_gastos='$conta'");
        //        $plan = pg_fetch_row($cuenta);
        //
        //        if ($plan[0] == "Si") {
        //            $sumaSubtotalTarifa12B = $sumaSubtotalTarifa12B + $arreglo6[$i];
        //            $codplanTarifa12B = $plan[1];
        //            $contTarifa12++;
        //        } else if ($plan[0] == "No") {
        //            $sumaSubtotalTarifa0B = $sumaSubtotalTarifa0B + $arreglo6[$i];
        //            $codplanTarifa0B = $plan[1];
        //            $contTarifa0++;
        //        }
        //        $cuenta = pg_query("select tipo_iva,id_cuenta from detalle_gastos,gastos where detalle_gastos.id_gastos=gastos.id_gastos and detalle_gastos.id_cuenta='" . $arreglo2[$i] . "' and detalle_gastos.bien_servicio='S' and gastos.id_gastos='$conta'");
        //        $plan = pg_fetch_row($cuenta);
        //
        //        if ($plan[0] == "Si") {
        //            $sumaSubtotalTarifa12 = $sumaSubtotalTarifa12 + $arreglo6[$i];
        //            $codplanTarifa12 = $plan[1];
        //            $contTarifa12++;
        //        } else if ($plan[0] == "No") {
        //            $sumaSubtotalTarifa012 = $sumaSubtotalTarifa012 + $arreglo6[$i];
        //            $codplanTarifa0 = $plan[1];
        //            $contTarifa0++;
        //        }
    }
    $data = $conta;
}
if ($forma == "EFECTIVO") {

    /////////////////////////guardar gastos///////////////////
    for ($i = 1; $i < $nelem; $i++) {
        if (!empty($arreglo2[$i])) {
            //            $consulta_bien_servi = pg_query(" select bien_servicios from productos where cod_productos=$arreglo1[$i]");
            //            while ($row = pg_fetch_row($consulta_bien_servi)) {
            //                $valor_Servicio = $row[0];
            //            }
            //            guardarDetallaGasto($conta, $arreglo1[$i], $arreglo2[$i], $arreglo3[$i], $arreglo4[$i], $arreglo5[$i], 'Activo', $valor_Servicio);
            guardarDetallaGasto($conta, '1', '1', $arreglo6[$i], 0, $arreglo6[$i], 'Activo', $arreglo7[$i], $arreglo2[$i], $arreglo5[$i], $arreglo1[$i], $arreglo3[$i], $arreglo4[$i], $arreglo8[$i]);
            ////////////////////////
            //Asiento Contable 
            // echo '<br>GUARDAR FACTURA VENTA1: <br>' . "select tipo_iva,id_cuenta from detalle_gastos,gastos where detalle_gastos.id_gastos=gastos.id_gastos and detalle_gastos.id_cuenta='" . $arreglo2[$i] . "' and detalle_gastos.bien_servicio='B' and gastos.id_gastos='$conta'";//////////////////////////
            //	 
            //                $cuenta = pg_query("select tipo_iva,id_cuenta from detalle_gastos,gastos where detalle_gastos.id_gastos=gastos.id_gastos and detalle_gastos.id_cuenta='" . $arreglo2[$i] . "' and detalle_gastos.bien_servicio='B' and gastos.id_gastos='$conta'");
            //                $plan = pg_fetch_row($cuenta);
            //
            //                if ($plan[0] == "Si") {
            //                    $sumaSubtotalTarifa12B = $sumaSubtotalTarifa12B + $arreglo6[$i];
            //                    $codplanTarifa12B = $plan[1];
            //                    $contTarifa12++;
            //                } else if ($plan[0] == "No") {
            //                    $sumaSubtotalTarifa0 = $sumaSubtotalTarifa0 + $arreglo6[$i];
            //                    $codplanTarifa0B = $plan[1];
            //                    $contTarifa0++;
            //                }
            ////             echo '<br>GUARDAR FACTURA VENTA1: <br>' . "select tipo_iva,id_cuenta from detalle_gastos,gastos where detalle_gastos.id_gastos=gastos.id_gastos and detalle_gastos.id_cuenta='" . $arreglo2[$i] . "' and detalle_gastos.bien_servicio='S' and gastos.id_gastos='$conta'";//////////////////////////
            ////	 
            //                $cuenta = pg_query("select tipo_iva,id_cuenta from detalle_gastos,gastos where detalle_gastos.id_gastos=gastos.id_gastos and detalle_gastos.id_cuenta='" . $arreglo2[$i] . "' and detalle_gastos.bien_servicio='S' and gastos.id_gastos='$conta'");
            //                $plan = pg_fetch_row($cuenta);
            //
            //                if ($plan[0] == "Si") {
            //                    $sumaSubtotalTarifa12 = $sumaSubtotalTarifa12 + $arreglo6[$i];
            //                    $codplanTarifa12 = $plan[1];
            //                    $contTarifa12++;
            //                } else if ($plan[0] == "No") {
            //                    $sumaSubtotalTarifa0 = $sumaSubtotalTarifa012 + $arreglo6[$i];
            //                    $codplanTarifa0 = $plan[1];
            //                    $contTarifa0++;
            //                }
        }
    }
    $data = $conta;
}

////////////////////////////////////////
///////////////////////ASIENTO CONTABLE

//
//
//$idtran = pg_query("select max(id_transacciones) from transacciones");
//$fila = pg_fetch_row($idtran);
//$fila[0] = $fila[0] + 1;
//
//$sum = 0;
//$bool = true;
//$pos = 0;
//$vec = 0;
//$auxiliar = $arreglo2;
////print_r($auxiliar."ggg1");
//while ($bool) {
//
//
////    	 echo '<br>GUARDAR FACTURA VENTA2: <br>' . "select id_plan_cuentas from plan_cuentas where id_plan_cuentas='" . $auxiliar[0] . "'";//////////////////////////
//////	 
//    $cuenta = pg_query("select id_plan_cuentas from plan_cuentas where id_plan_cuentas='" . $auxiliar[0] . "'");
//    $plan = pg_fetch_row($cuenta);
//    $nelem = count($auxiliar);
//    $vec = 0;
//    for ($i = 0; $i <= $nelem; $i++) {
////            	 echo '<br>GUARDAR FACTURA VENTA3: <br>' . "select id_plan_cuentas from plan_cuentas where id_plan_cuentas='" . $auxiliar[$i] . "'";//////////////////////////
//////	
//        $cuenta1 = pg_query("select id_plan_cuentas from plan_cuentas where id_plan_cuentas='" . $auxiliar[$i] . "'");
//        $plan1 = pg_fetch_row($cuenta1);
//        if ($plan1[$i] == $plan[0]) {
////            print_r($plan1[$i]."ggg4");
////                        print_r($plan[0]."ggg5");
//            $sum = $arreglo6[$i] + $sum;
////            print_r($auxiliar."ggg3");
//        } else {
//            $vec[$pos] = $auxiliar[$i];
//            $pos++;
//        }
//    }
//    if ($vec == 0) {
//        $bool = false;
//    } else {
//        $auxiliar = $vec;
//        $pos = 0;
//    }
//}
//$sum = number_format($sum, 3, '.', '');
//$sum = $sum + $_POST['iva'];
//$saldo = $sum - $_POST['tot'];
//$saldo = number_format($saldo, 3, '.', '');
//$provee1 = $_POST['proveedor'];
//$prove = pg_query("select identificacion_pro from proveedores where id_proveedor='$provee1'");
//$p = pg_fetch_row($prove);
//$ing = pg_query("select max(num_transaccion) from transacciones where id_tipo_transaccion='1'");
//$res = pg_fetch_row($ing);
//$formaPa = " ";
//if ($forma == "EFECTIVO") {
//    $formaPa = ", FOR. PAGO:Cont.";
//} else if ($forma == "otros") {
//    $consulta_mixto = pg_query("select sum(x.sum) from (select formas_pago_mixto_g.forma_pago,sum(formas_pago_mixto_g.valor) from gastos, formas_pago_mixto_g 
//where gastos.id_gastos=formas_pago_mixto_g.id_gastos and gastos.id_gastos='$conta' 
//and (formas_pago_mixto_g.forma_pago='CREDITO'  ) GROUP BY formas_pago_mixto_g.forma_pago
//)x");
//    while ($row = pg_fetch_row($consulta_mixto)) {
////                    $cont2_mixto_contado = $row[0];
//        $valor_contado = $row[0];
//    }
//    if ($valor_contado != "") {
//
//        $formaPa = ", FOR. PAGO:Crédi.";
//    }
//    $consulta_mixto = pg_query("select sum(x.sum) from (select formas_pago_mixto_g.forma_pago,sum(formas_pago_mixto_g.valor) from gastos, formas_pago_mixto_g 
//where gastos.id_gastos=formas_pago_mixto_g.id_gastos and gastos.id_gastos='$conta' 
//and (formas_pago_mixto_g.forma_pago='CHEQUE'  ) GROUP BY formas_pago_mixto_g.forma_pago
//)x");
//    while ($row = pg_fetch_row($consulta_mixto)) {
////                    $cont2_mixto_contado = $row[0];
//        $valor_contado = $row[0];
//    }
//    if ($valor_contado != "") {
//
//        $formaPa = ", FOR. PAGO:Cheque.";
//    }
//    $consulta_mixto = pg_query("select sum(x.sum) from (select formas_pago_mixto_g.forma_pago,sum(formas_pago_mixto_g.valor) from gastos, formas_pago_mixto_g 
//where gastos.id_gastos=formas_pago_mixto_g.id_gastos and gastos.id_gastos='$conta' 
//and (formas_pago_mixto_g.forma_pago='TCREDITO'  ) GROUP BY formas_pago_mixto_g.forma_pago
//)x");
//    while ($row = pg_fetch_row($consulta_mixto)) {
////                    $cont2_mixto_contado = $row[0];
//        $valor_contado = $row[0];
//    }
//    if ($valor_contado != "") {
//
//        $formaPa = ", FOR. PAGO:TCredito.";
//    }
//    $consulta_mixto = pg_query("select sum(x.sum) from (select formas_pago_mixto_g.forma_pago,sum(formas_pago_mixto_g.valor) from gastos, formas_pago_mixto_g 
//where gastos.id_gastos=formas_pago_mixto_g.id_gastos and gastos.id_gastos='$conta' 
//and (formas_pago_mixto_g.forma_pago='TRANSFERENCIAS'  ) GROUP BY formas_pago_mixto_g.forma_pago
//)x");
//    while ($row = pg_fetch_row($consulta_mixto)) {
////                    $cont2_mixto_contado = $row[0];
//        $valor_contado = $row[0];
//    }
//    if ($valor_contado != "") {
//
//        $formaPa = ", FOR. PAGO:Transferencias.";
//    }
//}
// echo '<br>GUARDAR FACTURA VENTA: <br>' . "insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '$conta','$_POST[fecha_actual]','$_POST[hora_actual]', 'GASTO FACTURA, PROVEEDOR: " . $p[0] . ",COMPROBANTE: " . $_POST['num_factura'] . "" . $formaPa . "','" . $sum . "', ' $_POST[tot]', '" . $saldo . "','1','" . ($res[0] + 1) . "' ,'Activo','$provee1','','" . $_POST[descripcion] . "','','','GAS','','$conpuntoresult','$_POST[fecha_emision]')";//////////////////////////
//	 
//
//$asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '$conta','$_POST[fecha_actual]','$_POST[hora_actual]', 'GASTO FACTURA, PROVEEDOR: " . $p[0] . ",COMPROBANTE: " . $_POST['num_factura'] . "" . $formaPa . "','" . $sum . "', ' $_POST[tot]', '" . $saldo . "','1','" . ($res[0] + 1) . "' ,'Activo','$provee1','','" . $_POST[descripcion] . "','','','GAS','','$conpuntoresult','$_POST[fecha_emision]')");
//
//
//$auxiliar = $arreglo2;
//$suma = 0;
//$bool = true;
//$aa = 0;
//$ab = 0;
//$pos = 1;
//$vec = "";
//$ant = $arreglo6;
//$vec1 = "";
//while ($bool) {
//    $cont11 = 0;
//    $cont2 = 0;
//    $aa = 0;
//    $ab = 0;
//    $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
//    $fila1 = pg_fetch_row($iddettran);
//
///////DETALLES TRANSACCION
//    $cuenta = pg_query("select id_plan_cuentas from plan_cuentas where id_plan_cuentas='" . $auxiliar[1] . "'");
//    while ($plan = pg_fetch_row($cuenta)) {
//        $cont2 = $plan[0];
//    }
//    if ($cont2 == 0) {
//        $cuenta = pg_query("select id_plan_cuentas from plan_cuentas where id_plan_cuentas='" . $auxiliar[0] . "'");
//        while ($plan = pg_fetch_row($cuenta)) {
//            $cont2 = $plan[0];
//        }
//    }
//    $nelem1 = count($auxiliar);
//    $suma = 0;
//    for ($i = 0; $i <= $nelem1; $i++) {
//        if (!empty($auxiliar[$i])) {
//            $cuenta1 = pg_query("select id_plan_cuentas from plan_cuentas where id_plan_cuentas='" . $auxiliar[$i] . "'");
//            while ($plan1 = pg_fetch_row($cuenta1)) {
//                $cont11 = $plan1[0];
//            }
//            if ($cont11 != 0) {
//                if ($cont11 == $cont2) {
//                    $aa++;
//                    $suma = $ant[$i] + $suma;
//                    //$fila1[0]++;
//                    //pg_query("insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','4','".$cont2."-".$aa."','".$nelem1."-i".$i."-".$conta."','prueba')");
//                } else {
//                    $ab++;
//                    $vec[$pos] = $auxiliar[$i];
//                    $vec1[$pos] = $ant[$i];
//                    $pos++;
//                    //$fila1[0]++;
//                    //pg_query("insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','4','".$cont2."-".$ab."','".$nelem1."-i".$i."-".$conta."','prueba2')");
//                }
//            }
//            //$ant=$ant.$conta."-";
//        }
//    }
//    // $fila1[0]++;
//    $suma = number_format($suma, 3, '.', '');
//
//    // pg_query("insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','".$cont2."','$suma','0.000','Activo')");
//    if ($vec == "") {
//        //$ab++;
//        $bool = false;
//    } else {
//        //$fila1[0]++;
//        //pg_query("insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','4','".$pos."','".$vec[$pos-2]."','otra vez')");
//        $auxiliar = $vec;
//        $ant = $vec1;
//        $vec = "";
//        $vec1 = "";
//        $pos = 0;
//    }
//}
//
//////////////////////
////asiento generico Inventario
//if ($sumaSubtotalTarifa12B > 0) {
//    $fila1[0] = $fila1[0] + 1;
////        	 echo '<br>GUARDAR FACTURA VENTA12B: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa12B','$sumaSubtotalTarifa12B','0.000','Activo')";//////////////////////////
//////	
//    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa12B','$sumaSubtotalTarifa12B','0.000','Activo')");
//}
//if ($sumaSubtotalTarifa12 > 0) {
//    $fila1[0] = $fila1[0] + 1;
//
////   	 echo '<br>GUARDAR FACTURA VENTA12: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa12','$sumaSubtotalTarifa12','0.000','Activo')";//////////////////////////
//////	
//    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa12','$sumaSubtotalTarifa12','0.000','Activo')");
//}
//if ($sumaSubtotalTarifa0 > 0) {
//    $fila1[0] = $fila1[0] + 1;
////    	 echo '<br>GUARDAR FACTURA VENTA0: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa0','$sumaSubtotalTarifa0','0.000','Activo')";//////////////////////////
//////	
//    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa0','$sumaSubtotalTarifa0','0.000','Activo')");
//}
//
//
//$iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
//$fila1 = pg_fetch_row($iddettran);
//
//
//$planiva = pg_query("select cuenta_debito from parametros where descripcion='IVA'");
//$fila2 = pg_fetch_row($planiva);
//if ($_POST[iva] != '0.000') {
//    $fila1[0] = $fila1[0] + 1;
//    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','$_POST[iva]','0.000','Activo')");
//}
//// $fila1[0]=$fila1[0]+1;
////Ya va restado en Subtotal
///*
//  ////////////////Añadir Descuento////////////////
//  if($_POST['desc']!=="0.000"){
//  $fila1[0]=$fila1[0]+1;
//  $des=pg_query("select cuenta_debito from parametros where descripcion='DESCUENTOS COMPRAS'");
//  $descuento=pg_fetch_row($des);
//  // $val=$_POST['tot']-$_POST['desc'];
//  pg_query("insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','".$descuento[0]."','0.000','".$_POST['desc']."','Activo')");
//
//  }
// */
//
////VER SI ES CONTADO O CREDITO
//if ($forma == "EFECTIVO") {
//    $fila1[0] = $fila1[0] + 1;
//    $total = $_POST['tot'];
//    $plancaja = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
//    $fila2 = pg_fetch_row($plancaja);
//    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','0.000','" . $total . "','Activo')");
//} else if ($forma == "otros") {
//    
//    	 echo '<br>GUARDAR FACTURA CHEQUE: <br>' . "select sum(x.sum) from (select formas_pago_mixto_g.forma_pago,sum(formas_pago_mixto_g.valor) from gastos, formas_pago_mixto_g 
//where gastos.id_gastos=formas_pago_mixto_g.id_gastos and gastos.id_gastos='$conta' 
//and (  formas_pago_mixto_g.forma_pago='CONTADO' ) GROUP BY formas_pago_mixto_g.forma_pago
//)x";//////////////////////////
//	 
//    
//    
//    $consulta_mixto_contado = pg_query("select sum(x.sum) from (select formas_pago_mixto_g.forma_pago,sum(formas_pago_mixto_g.valor) from gastos, formas_pago_mixto_g 
//where gastos.id_gastos=formas_pago_mixto_g.id_gastos and gastos.id_gastos='$conta' 
//and (formas_pago_mixto_g.forma_pago='CONTADO' ) GROUP BY formas_pago_mixto_g.forma_pago
//)x");
//    while ($row = pg_fetch_row($consulta_mixto_contado)) {
////                    $cont2_mixto_contado = $row[0];
//        $valor_contado = $row[0];
//    }
//    if ($valor_contado != "") {
//
//        $sql = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
//        $buscaCuenta = pg_fetch_row($sql);
//        $fila1[0] = $fila1[0] + 1;
//       echo '<br>GUARDAR FACTURA CHEQUE1: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','0.000','" . $valor_contado . "','Activo')"; //////////////////////////
//
//        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','0.000','" . $valor_contado . "','Activo')");
//    }
//	 echo '<br>GUARDAR FACTURA TCREDITO: <br>' . "select formas_pago_mixto_g.forma_pago,formas_pago_mixto_g.valor,formas_pago_mixto_g.id_cuenta from gastos, formas_pago_mixto_g
//where gastos.id_gastos=formas_pago_mixto_g.id_gastos and gastos.id_gastos='$conta' and formas_pago_mixto_g.forma_pago='TCREDITO'
//
//";//////////////////////////
//	 
//    
//    
//    $consulta_mixto_tcredito = pg_query("select formas_pago_mixto_g.forma_pago,formas_pago_mixto_g.valor,formas_pago_mixto_g.id_cuenta from gastos, formas_pago_mixto_g
//where gastos.id_gastos=formas_pago_mixto_g.id_gastos and gastos.id_gastos='$conta' and formas_pago_mixto_g.forma_pago='TCREDITO'
//
//");
//    while ($row = pg_fetch_row($consulta_mixto_tcredito)) {
// $cont2_mixto_credito = $row[0];
//        $valor_contadocrdito = $row[1];
//        $id_cuenta_banco = $row[2];
//    }
//    if ($cont2_mixto_credito != "") {
//
//        $sql = pg_query("select cuenta_debito from parametros where descripcion='TARJETA DE CREDITO'");
//        $buscaCuenta = pg_fetch_row($sql);
//        $fila1[0] = $fila1[0] + 1;
//          echo '<br>GUARDAR FACTURA TCREDITO1: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$id_cuenta_banco','0.000','" . $valor_contadocrdito . "','Activo')"; //////////////////////////
//
//        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$id_cuenta_banco','0.000','" . $valor_contadocrdito . "','Activo')");
//    }
//           echo '<br>GUARDAR FACTURA CHEQUE: <br>' . "select formas_pago_mixto_g.forma_pago,formas_pago_mixto_g.valor,formas_pago_mixto_g.id_cuenta from gastos, formas_pago_mixto_g
//where gastos.id_gastos=formas_pago_mixto_g.id_gastos and gastos.id_gastos='$conta' and formas_pago_mixto_g.forma_pago='CHEQUE'
//
//"; //////////////////////////
//
//    
//    
//    $consulta_mixto_cheque = pg_query("select formas_pago_mixto_g.forma_pago,formas_pago_mixto_g.valor,formas_pago_mixto_g.id_cuenta from gastos, formas_pago_mixto_g
//where gastos.id_gastos=formas_pago_mixto_g.id_gastos and gastos.id_gastos='$conta' and formas_pago_mixto_g.forma_pago='CHEQUE'
//
//");
//    while ($row = pg_fetch_row($consulta_mixto_cheque)) {
//        $cont2_mixto_transferencias_che = $row[0];
//        $valor_contadotr = $row[1];
//        $id_cuenta_banco = $row[2];
//    }
//    if ($cont2_mixto_transferencias_che != "") {
//
//        $sql = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
//        $buscaCuenta = pg_fetch_row($sql);
//        $fila1[0] = $fila1[0] + 1;
//                    echo '<br>GUARDAR FACTURA CHEQUE1: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$id_cuenta_banco','0.000','" . $valor_contadotr . "','Activo')"; //////////////////////////
//
//        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$id_cuenta_banco','0.000','" . $valor_contadotr . "','Activo')");
//    }
//
//         echo '<br>GUARDAR FACTURA CREDITO: <br>' . "select formas_pago_mixto_g.forma_pago,formas_pago_mixto_g.valor from gastos, formas_pago_mixto_g where
//gastos.id_gastos=formas_pago_mixto_g.id_gastos and gastos.id_gastos='$conta' and formas_pago_mixto_g.forma_pago='CREDITO'
//
//"; //////////////////////////
//         echo '<br>GUARDAR FACTURA TRANSFERENCIAS: <br>' . "select formas_pago_mixto_g.forma_pago,formas_pago_mixto_g.valor,formas_pago_mixto_g.id_cuenta from gastos, formas_pago_mixto_g
//where gastos.id_gastos=formas_pago_mixto_g.id_gastos and gastos.id_gastos='$conta' and formas_pago_mixto_g.forma_pago='TRANSFERENCIAS'
//
//"; //////////////////////////
//
//    
//    
//    $consulta_mixto_trans = pg_query("select formas_pago_mixto_g.forma_pago,formas_pago_mixto_g.valor,formas_pago_mixto_g.id_cuenta from gastos, formas_pago_mixto_g
//where gastos.id_gastos=formas_pago_mixto_g.id_gastos and gastos.id_gastos='$conta' and formas_pago_mixto_g.forma_pago='TRANSFERENCIAS'
//
//");
//    while ($row = pg_fetch_row($consulta_mixto_trans)) {
//        $cont2_mixto_transferencias_trans = $row[0];
//        $valor_contadotr = $row[1];
//        $id_cuenta_banco = $row[2];
//    }
//    if ($cont2_mixto_transferencias_trans != "") {
//
//        $sql = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
//        $buscaCuenta = pg_fetch_row($sql);
//        $fila1[0] = $fila1[0] + 1;
//                    echo '<br>GUARDAR FACTURA TRANSFERENCIAS1: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$id_cuenta_banco','0.000','" . $valor_contadotr . "','Activo')"; //////////////////////////
//
//        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$id_cuenta_banco','0.000','" . $valor_contadotr . "','Activo')");
//    }
//
//         echo '<br>GUARDAR FACTURA CREDITO: <br>' . "select formas_pago_mixto_g.forma_pago,formas_pago_mixto_g.valor from gastos, formas_pago_mixto_g where
//gastos.id_gastos=formas_pago_mixto_g.id_gastos and gastos.id_gastos='$conta' and formas_pago_mixto_g.forma_pago='CREDITO'
//
//"; //////////////////////////
//
//
//
//    $consulta_mixto = pg_query("select formas_pago_mixto_g.forma_pago,formas_pago_mixto_g.valor from gastos, formas_pago_mixto_g where
//gastos.id_gastos=formas_pago_mixto_g.id_gastos and gastos.id_gastos='$conta' and formas_pago_mixto_g.forma_pago='CREDITO'
//
//");
//    while ($row = pg_fetch_row($consulta_mixto)) {
//        $cont2_mixto_credito = $row[0];
//        $valor_contadocredito = $row[1];
//    }
//    if ($cont2_mixto_credito != "") {
//
//        $fila1[0] = $fila1[0] + 1;
//        $plancaja4 = pg_query("select cuenta_debito from parametros where descripcion='CUENTAS POR PAGAR'");
//        $fila4 = pg_fetch_row($plancaja4);
//
//
//                    echo '<br>GUARDAR FACTURA credito1: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila4[0] . "','0.000','" . $valor_contadocredito . "','Activo')"; //////////////////////////
//
//        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila4[0] . "','0.000','" . $valor_contadocredito . "','Activo')");
//    }
//}

///////////////////
////////////////////////////////////////
function guardarDetallaGasto($factura, $producto, $cantidad, $precioCompra, $descuento, $total, $estado, $bienServicio, $id_cuenta, $centro_costo, $concepto, $cuenta_contable, $tipo_iva, $idcentroc)
{
    $id = obtenerIdDetalle();
    $sql = "INSERT INTO detalle_gastos(id_detalle_gastos, id_gastos, cod_productos, cantidad, precio_compra, descuento_producto, total_compra, estado, bien_servicio,id_cuenta,centro_costo,concepto,cuenta_contable,tipo_iva) "
        . "VALUES (" . $id . ", $factura, $producto, " . number_format($cantidad, 3, '.', '') . ", " . number_format($precioCompra, 4, '.', '') . ", "
        . "" . number_format($descuento, 4, '.', '') . ", " . number_format($total, 4, '.', '') . ", '$estado', '$bienServicio','$id_cuenta','$centro_costo','" . strtoupper($concepto) . "','$cuenta_contable','$tipo_iva')";


    $res = pg_query($sql);
    if (!empty($res) && !empty($idcentroc)) {
        guardarDetalleCentroCosto($id, $idcentroc, "detalle_gastos");
    }
}

function obtenerIdDetalle()
{
    $consulta = pg_query("select max(id_detalle_gastos) from detalle_gastos");
    $id = (pg_fetch_row($consulta)[0] + 1);
    return $id;
}

echo $data;
