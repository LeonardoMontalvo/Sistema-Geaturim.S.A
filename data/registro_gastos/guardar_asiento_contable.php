<?php

session_start();
include '../../procesos/base.php';
require_once '../../procesos/pagosCompra.php';
conectarse();
error_reporting(0);
$conpuntoresult = $_SESSION['PV'];
//////////contador gastos///////
$conta = 0;
/* $consulta = pg_query("select max(id_gastos) from gastos");
while ($row = pg_fetch_row($consulta)) {
    $conta = $row[0];
} */
$conta = $_POST["id_gastos"];

$total = 0;
$forma = $_POST['formascc'];
///////////////////////////////

$campo1 = $_POST['campo1'];
$campo2 = $_POST['campo2'];
$campo3 = $_POST['campo3'];
$campo4 = $_POST['campo4'];
$campo5 = $_POST['campo5'];
$campo6 = $_POST['campo6'];
$campo7 = $_POST['campo7'];
$arreglo1 = explode('|', $campo1);
$arreglo2 = explode('|', $campo2);
$arreglo3 = explode('|', $campo3);
$arreglo4 = explode('|', $campo4);
$arreglo5 = explode('|', $campo5);
$arreglo6 = explode('|', $campo6);
$arreglo7 = explode('|', $campo7);
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
        $valor_credito1 = $row[0];
    }
    if ($valor_credito1 != "") {
        // variables pagos
        $adelanto = '0.00';
        $meses = $_POST['meses'];
        $total = $valor_credito1;
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
    $fechaEmision = $_POST['fecha_emision'];
    if (empty($fechaEmision)) {
        $fechaEmision = $_POST['fecha_actual'];
    }

    guardarPagosCompra($_POST['proveedor'], $conta, $_SESSION['id'], $fechaEmision, 0, 0, 'FACTURA', $format, $format, 'Activo', 'G');

    /////////////////////////guardar gastos///////////////////
    //    for ($i = 0; $i <= $nelem; $i++) {
    //        if (!empty($arreglo2[$i])) {
    //
    //
    //            $cuenta = pg_query("select tipo_iva,id_cuenta from detalle_gastos,gastos where detalle_gastos.id_gastos=gastos.id_gastos and detalle_gastos.id_cuenta='" . $arreglo2[$i] . "' and detalle_gastos.bien_servicio='B' and gastos.id_gastos='$conta'");
    //            $plan = pg_fetch_row($cuenta);
    //
    //            if ($plan[0] == "Si") {
    //                $sumaSubtotalTarifa12B = $sumaSubtotalTarifa12B + $arreglo6[$i];
    //                $codplanTarifa12B = $plan[1];
    //                $contTarifa12++;
    //            } else if ($plan[0] == "No") {
    //                $sumaSubtotalTarifa0B = $sumaSubtotalTarifa0B + $arreglo6[$i];
    //                $codplanTarifa0B = $plan[1];
    //                $contTarifa0++;
    //            }
    //            //             echo '<br>GUARDAR FACTURA otros s: <br>' . "select tipo_iva,id_cuenta from detalle_gastos,gastos where detalle_gastos.id_gastos=gastos.id_gastos and detalle_gastos.id_cuenta='" . $arreglo2[$i] . "' and detalle_gastos.bien_servicio='S' and gastos.id_gastos='$conta'";//////////////////////////
    //            //	 
    //            $cuenta = pg_query("select tipo_iva,id_cuenta from detalle_gastos,gastos where detalle_gastos.id_gastos=gastos.id_gastos and detalle_gastos.id_cuenta='" . $arreglo2[$i] . "' and detalle_gastos.bien_servicio='S' and gastos.id_gastos='$conta'");
    //            $plan = pg_fetch_row($cuenta);
    //
    //            if ($plan[0] == "Si") {
    //                $sumaSubtotalTarifa12 = $sumaSubtotalTarifa12 + $arreglo6[$i];
    //                $codplanTarifa12 = $plan[1];
    //                $contTarifa12++;
    //            } else if ($plan[0] == "No") {
    //                $sumaSubtotalTarifa0 = $sumaSubtotalTarifa0 + $arreglo6[$i];
    //                $codplanTarifa0 = $plan[1];
    //                $contTarifa0++;
    //            }
    //        }
    //    }
    $data = $conta;
} else if ($forma == "EFECTIVO") {

    //    /////////////////////////guardar gastos///////////////////
    //    for ($i = 0; $i <= $nelem; $i++) {
    //        if (!empty($arreglo2[$i])) {
    //      
    //            $cuenta = pg_query("select tipo_iva,id_cuenta from detalle_gastos,gastos where detalle_gastos.id_gastos=gastos.id_gastos and detalle_gastos.id_cuenta='" . $arreglo2[$i] . "' and detalle_gastos.bien_servicio='B' and gastos.id_gastos='$conta'");
    //            $plan = pg_fetch_row($cuenta);
    //
    //            if ($plan[0] == "Si") {
    //                $sumaSubtotalTarifa12B = $sumaSubtotalTarifa12B + $arreglo6[$i];
    //                $codplanTarifa12B = $plan[1];
    //                $contTarifa12++;
    //            } else if ($plan[0] == "No") {
    //                $sumaSubtotalTarifa0B = $sumaSubtotalTarifa0B + $arreglo6[$i];
    //                $codplanTarifa0B = $plan[1];
    //                $contTarifa0++;
    //            }
    //          	 
    //            $cuenta = pg_query("select tipo_iva,id_cuenta from detalle_gastos,gastos where detalle_gastos.id_gastos=gastos.id_gastos and detalle_gastos.id_cuenta='" . $arreglo2[$i] . "' and detalle_gastos.bien_servicio='S' and gastos.id_gastos='$conta'");
    //            $plan = pg_fetch_row($cuenta);
    //
    //            if ($plan[0] == "Si") {
    //                $sumaSubtotalTarifa12 = $sumaSubtotalTarifa12 + $arreglo6[$i];
    //                $codplanTarifa12 = $plan[1];
    //                $contTarifa12++;
    //            } else if ($plan[0] == "No") {
    //                $sumaSubtotalTarifa0 = $sumaSubtotalTarifa0 + $arreglo6[$i];
    //                $codplanTarifa0 = $plan[1];
    //                $contTarifa0++;
    //            }
    //        }
    //    }
    $data = $conta;
}

////////////////////////////////////////
///////////////////////ASIENTO CONTABLE



$idtran = pg_query("select max(id_transacciones) from transacciones");
$fila = pg_fetch_row($idtran);
$fila[0] = $fila[0] + 1;

$sum = 0;
$bool = true;
$pos = 0;
$vec = 0;


//print_r($auxiliar."ggg1");
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
$saldo = '0.0000';
//$saldo = number_format($saldo, 3, '.', '');
$provee1 = $_POST['proveedor'];
$prove = pg_query("select identificacion_pro from proveedores where id_proveedor='$provee1'");
$p = pg_fetch_row($prove);
$ing = pg_query("select max(num_transaccion) from transacciones where id_tipo_transaccion='1' and id_empresa= '$_SESSION[PV]'");
$res = pg_fetch_row($ing);
$ing_pv = pg_query("select max(id_transaccion_pv::int) from transacciones where  id_empresa= '$_SESSION[PV]'");
$res_pv = pg_fetch_row($ing_pv);

$formaPa = " ";
if ($forma == "EFECTIVO") {
    $formaPa = ", FOR. PAGO:Cont.";
} else if ($forma == "otros") {


    $consulta_mixto = pg_query("select sum(x.sum) from (select formas_pago_mixto_g.forma_pago,sum(formas_pago_mixto_g.valor) from gastos, formas_pago_mixto_g 
where gastos.id_gastos=formas_pago_mixto_g.id_gastos and gastos.id_gastos='$conta' 
and (formas_pago_mixto_g.forma_pago='CREDITO'  ) GROUP BY formas_pago_mixto_g.forma_pago
)x");
    while ($row = pg_fetch_row($consulta_mixto)) {
        //                    $cont2_mixto_contado = $row[0];
        $valor_credito2 = $row[0];
    }
    if ($valor_credito2 != "") {

        $formaPa = ", FOR. PAGO:Crédi.";
    }
    $consulta_mixto = pg_query("select sum(x.sum) from (select formas_pago_mixto_g.forma_pago,sum(formas_pago_mixto_g.valor) from gastos, formas_pago_mixto_g 
where gastos.id_gastos=formas_pago_mixto_g.id_gastos and gastos.id_gastos='$conta' 
and (formas_pago_mixto_g.forma_pago='CHEQUE'  ) GROUP BY formas_pago_mixto_g.forma_pago
)x");
    while ($row = pg_fetch_row($consulta_mixto)) {
        //                    $cont2_mixto_contado = $row[0];
        $valor_cheque = $row[0];
    }
    if ($valor_cheque != "") {

        $formaPa = ", FOR. PAGO:Cheque.";
    }
    $consulta_mixto = pg_query("select sum(x.sum) from (select formas_pago_mixto_g.forma_pago,sum(formas_pago_mixto_g.valor) from gastos, formas_pago_mixto_g 
where gastos.id_gastos=formas_pago_mixto_g.id_gastos and gastos.id_gastos='$conta' 
and (formas_pago_mixto_g.forma_pago='TCREDITO'  ) GROUP BY formas_pago_mixto_g.forma_pago
)x");
    while ($row = pg_fetch_row($consulta_mixto)) {
        //                    $cont2_mixto_contado = $row[0];
        $valor_tcredito = $row[0];
    }
    if ($valor_tcredito != "") {

        $formaPa = ", FOR. PAGO:TCredito.";
    }
    $consulta_mixto = pg_query("select sum(x.sum) from (select formas_pago_mixto_g.forma_pago,sum(formas_pago_mixto_g.valor) from gastos, formas_pago_mixto_g 
where gastos.id_gastos=formas_pago_mixto_g.id_gastos and gastos.id_gastos='$conta' 
and (formas_pago_mixto_g.forma_pago='TRANSFERENCIAS'  ) GROUP BY formas_pago_mixto_g.forma_pago
)x");
    while ($row = pg_fetch_row($consulta_mixto)) {
        //                    $cont2_mixto_contado = $row[0];
        $valor_transferencias = $row[0];
    }
    if ($valor_transferencias != "") {

        $formaPa = ", FOR. PAGO:Transferencias.";
    }
}
//echo '<br>GUARDAR FACTURA VENTA//.: <br>' . "insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '$conta','$_POST[fecha_actual]','$_POST[hora_actual]', 'GASTO FACTURA, PROVEEDOR: " . $p[0] . ",COMPROBANTE: " . $_POST['num_factura'] . "" . $formaPa . "','$_POST[tot]', ' $_POST[tot]', '" . $saldo . "','1','" . ($res[0] + 1) . "' ,'Activo','$provee1','','" . $_POST[descripcion] . "','','','GAS','','$conpuntoresult','$_POST[fecha_emision]','" . ($res_pv[0] + 1) . "').\n"; //////////////////////////


$asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '$conta','$_POST[fecha_actual]','$_POST[hora_actual]', 'GASTO FACTURA, PROVEEDOR: " . $p[0] . ",COMPROBANTE: " . $_POST['num_factura'] . "" . $formaPa . "','$_POST[tot]', ' $_POST[tot]', '" . $saldo . "','1','" . ($res[0] + 1) . "' ,'Activo','$provee1','','" . $_POST["descripcion"] . "','','','GAS','','$conpuntoresult','$_POST[fecha_emision]','" . ($res_pv[0] + 1) . "')");



if (!empty($arreglo2)) {
    $auxiliar = $arreglo2;
}

$iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
$fila1 = pg_fetch_row($iddettran);
////////////////////////////////////////////////////////////
//asiento generico Inventario////////////////////////////////////////
////////////////////////////////////////////////////////////

$cuenta = pg_query("select detalle_gastos.tipo_iva,detalle_gastos.id_cuenta,sum(detalle_gastos.total_compra) from productos,detalle_gastos 
where detalle_gastos.cod_productos=productos.cod_productos and detalle_gastos.id_gastos ='$conta' 
and detalle_gastos.bien_servicio='B'
GROUP BY detalle_gastos.tipo_iva,detalle_gastos.id_cuenta");

while ($plan = pg_fetch_row($cuenta)) {
    if ($plan[0] == "Si" || $plan[0] == "No") {

        $sumaSubtotalTarifa12B = $plan[2];
        $codplanTarifa12B = $plan[1];
        //asiento generico Inventario
        if ($sumaSubtotalTarifa12B > 0) {
            $fila1[0] = $fila1[0] + 1;
            //                echo '<br>GUARDAR FACTURA $sumaSubtotalTarifa12B//..: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa12B','$sumaSubtotalTarifa12B','0.000','Activo').\n"; //////////////////////////

            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa12B','$sumaSubtotalTarifa12B','0.000','Activo')");
        }
    }
}

$cuenta1 = pg_query("select detalle_gastos.tipo_iva,detalle_gastos.id_cuenta,sum(detalle_gastos.total_compra) from productos,detalle_gastos 
where detalle_gastos.cod_productos=productos.cod_productos and detalle_gastos.id_gastos ='$conta' 
and detalle_gastos.bien_servicio='S'
GROUP BY detalle_gastos.tipo_iva,detalle_gastos.id_cuenta");

while ($plan = pg_fetch_row($cuenta1)) {
    if ($plan[0] == "Si" || $plan[0] == "No") {
        $sumaSubtotalTarifa12 = $plan[2];
        $codplanTarifa12 = $plan[1];

        if ($sumaSubtotalTarifa12 > 0) {
            $fila1[0] = $fila1[0] + 1;

            //        echo '//....'."insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa12','$sumaSubtotalTarifa12','0.000','Activo').\n";
            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa12','$sumaSubtotalTarifa12','0.000','Activo')");
        }
    }
}

////////////////////////////////////////////////////////////
//asiento generico Inventario///////////////////////////////
////////////////////////////////////////////////////////////

//if ($sumaSubtotalTarifa12 > 0) {
//    $fila1[0] = $fila1[0] + 1;
//
//    //   	 echo '<br>GUARDAR FACTURA VENTA12: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa12','$sumaSubtotalTarifa12','0.000','Activo')";//////////////////////////
//	
//    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa12','$sumaSubtotalTarifa12','0.000','Activo')");
//}
//if ($sumaSubtotalTarifa0B > 0) {
//    $fila1[0] = $fila1[0] + 1;
//    //        	 echo '<br>GUARDAR FACTURA $sumaSubtotalTarifa0B: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa0B','$sumaSubtotalTarifa0B','0.000','Activo')";//////////////////////////
//
//    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa0B','$sumaSubtotalTarifa0B','0.000','Activo')");
//}
//if ($sumaSubtotalTarifa0 > 0) {
//    $fila1[0] = $fila1[0] + 1;
//    //   	 echo '<br>GUARDAR FACTURA $sumaSubtotalTarifa0: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa0','$sumaSubtotalTarifa0','0.000','Activo')";//////////////////////////
//    ////	
//    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa0','$sumaSubtotalTarifa0','0.000','Activo')");
//}


/* $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
$fila1 = pg_fetch_row($iddettran); */


$planiva = pg_query("select cuenta_debito from parametros where descripcion='IVA'");
$fila2 = pg_fetch_row($planiva);
if ($_POST["iva"] != '0.000') {
    //$fila1[0] = $fila1[0] + 1;

    //    echo '//.....'."insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','$_POST[iva]','0.000','Activo').\n";
    //pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','$_POST[iva]','0.000','Activo')");
    registrarCuentasIvaComprasTransaccion($conta, $fila[0]);
}

$iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
$fila1 = pg_fetch_row($iddettran);
// $fila1[0]=$fila1[0]+1;
//Ya va restado en Subtotal
/*
  ////////////////Añadir Descuento////////////////
  if($_POST['desc']!=="0.000"){
  $fila1[0]=$fila1[0]+1;
  $des=pg_query("select cuenta_debito from parametros where descripcion='DESCUENTOS COMPRAS'");
  $descuento=pg_fetch_row($des);
  // $val=$_POST['tot']-$_POST['desc'];
  pg_query("insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','".$descuento[0]."','0.000','".$_POST['desc']."','Activo')");

  }
 */

//VER SI ES CONTADO O CREDITO
if ($forma == "EFECTIVO") {
    $fila1[0] = $fila1[0] + 1;
    $total = $_POST['tot'];
    $plancaja = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
    $fila2 = pg_fetch_row($plancaja);

    //    echo '//.......'."insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','0.000','" . $total . "','Activo').\n";
    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','0.000','" . $total . "','Activo')");
} else if ($forma == "otros") {
    //    
    //    echo '<br>GUARDAR FACTURA CONTADO12: <br>' . "select sum(x.sum) from (select formas_pago_mixto_g.forma_pago,sum(formas_pago_mixto_g.valor) from gastos, formas_pago_mixto_g 
    //where gastos.id_gastos=formas_pago_mixto_g.id_gastos and gastos.id_gastos='$conta' 
    //and (formas_pago_mixto_g.forma_pago='CONTADO' ) GROUP BY formas_pago_mixto_g.forma_pago
    //)x"; //////////////////////////
    ////	 
    //    
    //    
    $consulta_mixto_contado = pg_query("select formas_pago_mixto_g.forma_pago,formas_pago_mixto_g.valor,formas_pago_mixto_g.id_cuenta from gastos, formas_pago_mixto_g
    where gastos.id_gastos=formas_pago_mixto_g.id_gastos and gastos.id_gastos='$conta' and formas_pago_mixto_g.forma_pago='CONTADO'");
    while ($row = pg_fetch_row($consulta_mixto_contado)) {
        //                    $cont2_mixto_contado = $row[0];
        $cont2_mixto_trans_cont = $row[0];
        $valor_contado_deta = $row[1];
        $id_cuenta_caja = $row[2];
    }
    //    print_r($valor_contado_deta."CONTADO1");
    if ($cont2_mixto_trans_cont != "") {

        $sql = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
        $buscaCuenta = pg_fetch_row($sql);
        $fila1[0] = $fila1[0] + 1;
        //                echo '<br>GUARDAR FACTURA CONTADO1//.........: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $id_cuenta_caja . "','0.000','" . $valor_contado_deta . "','Activo').\n"; //////////////////////////



        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $id_cuenta_caja . "','0.000','" . $valor_contado_deta . "','Activo')");
    }
    //    echo '<br>GUARDAR FACTURA TCREDITO12: <br>' . "select formas_pago_mixto_g.forma_pago,formas_pago_mixto_g.valor,formas_pago_mixto_g.id_cuenta from gastos, formas_pago_mixto_g
    //where gastos.id_gastos=formas_pago_mixto_g.id_gastos and gastos.id_gastos='$conta' and formas_pago_mixto_g.forma_pago='TCREDITO'
    //
    ////"; //////////////////////////
    //	 
    //    

    $consulta_mixto_tcredito = pg_query("select formas_pago_mixto_g.forma_pago,formas_pago_mixto_g.valor,formas_pago_mixto_g.id_cuenta from gastos, formas_pago_mixto_g
where gastos.id_gastos=formas_pago_mixto_g.id_gastos and gastos.id_gastos='$conta' and formas_pago_mixto_g.forma_pago='TCREDITO'

");
    while ($row = pg_fetch_row($consulta_mixto_tcredito)) {
        $cont2_mixto_credito = $row[0];
        $valor_contadocrdito = $row[1];
        $id_cuenta_banco = $row[2];
    }
    if ($cont2_mixto_credito != "") {

        $sql = pg_query("select cuenta_debito from parametros where descripcion='TARJETA DE CREDITO'");
        $buscaCuenta = pg_fetch_row($sql);
        $fila1[0] = $fila1[0] + 1;
        //                  echo '<br>GUARDAR FACTURA TCREDITO1: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$id_cuenta_banco','0.000','" . $valor_contadocrdito . "','Activo').\n"; //////////////////////////

        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$id_cuenta_banco','0.000','" . $valor_contadocrdito . "','Activo')");
    }
    //    echo '<br>GUARDAR FACTURA CHEQUE12: <br>' . "select formas_pago_mixto_g.forma_pago,formas_pago_mixto_g.valor,formas_pago_mixto_g.id_cuenta from gastos, formas_pago_mixto_g
    //where gastos.id_gastos=formas_pago_mixto_g.id_gastos and gastos.id_gastos='$conta' and formas_pago_mixto_g.forma_pago='CHEQUE'"; //////////////////////////
    ////
    //    

    $consulta_mixto_cheque = pg_query("select formas_pago_mixto_g.forma_pago,formas_pago_mixto_g.valor,formas_pago_mixto_g.id_cuenta from gastos, formas_pago_mixto_g
where gastos.id_gastos=formas_pago_mixto_g.id_gastos and gastos.id_gastos='$conta' and formas_pago_mixto_g.forma_pago='CHEQUE'");
    while ($row = pg_fetch_row($consulta_mixto_cheque)) {
        $cont2_mixto_trans_che = $row[0];
        $valor_contadoche = $row[1];
        $id_cuenta_banco = $row[2];
    }
    //    print_r($cont2_mixto_transferencias_che."fff");
    if ($cont2_mixto_trans_che != "") {

        $sql = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
        $buscaCuenta = pg_fetch_row($sql);
        $fila1[0] = $fila1[0] + 1;
        //                echo '<br>GUARDAR FACTURA CHEQUE1: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$id_cuenta_banco','0.000','" . $valor_contadoche . "','Activo').\n"; //////////////////////////

        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$id_cuenta_banco','0.000','" . $valor_contadoche . "','Activo')");
    }


    //    echo '<br>GUARDAR FACTURA TRANSFERENCIAS12: <br>' . "select formas_pago_mixto_g.forma_pago,formas_pago_mixto_g.valor,formas_pago_mixto_g.id_cuenta from gastos, formas_pago_mixto_g where gastos.id_gastos=formas_pago_mixto_g.id_gastos and gastos.id_gastos='$conta' and formas_pago_mixto_g.forma_pago='TRANSFERENCIAS'";


    $consulta_mixto_trans = pg_query("select formas_pago_mixto_g.forma_pago,formas_pago_mixto_g.valor,formas_pago_mixto_g.id_cuenta from gastos, formas_pago_mixto_g
where gastos.id_gastos=formas_pago_mixto_g.id_gastos and gastos.id_gastos='$conta' and formas_pago_mixto_g.forma_pago='TRANSFERENCIAS'

");
    while ($row = pg_fetch_row($consulta_mixto_trans)) {
        $cont2_mixto_trans = $row[0];
        $valor_contadotr = $row[1];
        $id_cuenta_banco = $row[2];
    }
    //    print_r($valor_contadotr."TRANSFERENCIAS1");
    if ($cont2_mixto_trans != "") {

        $sql = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
        $buscaCuenta = pg_fetch_row($sql);
        $fila1[0] = $fila1[0] + 1;
        //               echo '<br>GUARDAR FACTURA TRANSFERENCIAS1: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$id_cuenta_banco','0.000','" . $valor_contadotr . "','Activo').\n"; //////////////////////////

        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$id_cuenta_banco','0.000','" . $valor_contadotr . "','Activo')");
    }

    //    echo '<br>GUARDAR FACTURA CREDITO12: <br>' . "select formas_pago_mixto_g.forma_pago,formas_pago_mixto_g.valor from gastos, formas_pago_mixto_g where gastos.id_gastos=formas_pago_mixto_g.id_gastos and gastos.id_gastos='$conta' and formas_pago_mixto_g.forma_pago='CREDITO'";

    $consulta_mixto = pg_query("select formas_pago_mixto_g.forma_pago,formas_pago_mixto_g.valor from gastos, formas_pago_mixto_g where
gastos.id_gastos=formas_pago_mixto_g.id_gastos and gastos.id_gastos='$conta' and formas_pago_mixto_g.forma_pago='CREDITO'

");
    while ($row = pg_fetch_row($consulta_mixto)) {
        $cont2_mixto_credito = $row[0];
        $valor_contadocredito = $row[1];

        //       print_r($cont2_mixto_credito."credito1");
        if ($cont2_mixto_credito != "") {

            $fila1[0] = $fila1[0] + 1;
            $plancaja4 = pg_query("select cuenta_debito from parametros where descripcion='CUENTAS POR PAGAR'");
            $fila4 = pg_fetch_row($plancaja4);

            //                    echo '<br>GUARDAR FACTURA PAGAR: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila4[0] . "','0.000','" . $valor_contadocredito . "','Activo').\n"; //////////////////////////

            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila4[0] . "','0.000','" . $valor_contadocredito . "','Activo')");
        }
    }

    //    echo '<br>GUARDAR FACTURA PAGOS1: <br>' . "select formas_pago_mixto_g.forma_pago,formas_pago_mixto_g.valor,formas_pago_mixto_g.id_cuenta from gastos, formas_pago_mixto_g where gastos.id_gastos=formas_pago_mixto_g.id_gastos and gastos.id_gastos='$conta' and formas_pago_mixto_g.forma_pago='TRANSFERENCIAS'";


    $consulta_mixto_pago = pg_query("select formas_pago_mixto_g.forma_pago,formas_pago_mixto_g.valor,formas_pago_mixto_g.id_cuenta from gastos, formas_pago_mixto_g
where gastos.id_gastos=formas_pago_mixto_g.id_gastos and gastos.id_gastos='$conta' and formas_pago_mixto_g.forma_pago='PAGOIESS'

");
    while ($row = pg_fetch_row($consulta_mixto_pago)) {
        $cont2_mixto_pagoiess = $row[0];
        $valor_contadotpi = $row[1];
        $id_cuenta_banco = $row[2];
    }
    //    print_r($valor_contadotr."PAGOS1");
    if ($cont2_mixto_pagoiess != "") {

        $sql = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
        $buscaCuenta = pg_fetch_row($sql);
        $fila1[0] = $fila1[0] + 1;
        //                echo '<br>GUARDAR FACTURA GENERAL..: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$id_cuenta_banco','0.000','" . $valor_contadotpi . "','Activo').\n"; //////////////////////////

        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$id_cuenta_banco','0.000','" . $valor_contadotpi . "','Activo')");
    }

    $consulta_mixto_anpro = pg_query("select formas_pago_mixto_g.forma_pago,formas_pago_mixto_g.valor,formas_pago_mixto_g.id_cuenta from gastos, formas_pago_mixto_g
where gastos.id_gastos=formas_pago_mixto_g.id_gastos and gastos.id_gastos='$conta' and formas_pago_mixto_g.forma_pago='ANTICIPO_PROVEEDORES'");
    while ($row = pg_fetch_row($consulta_mixto_anpro)) {
        $cont2_mixto_anpro = $row[0];
        $valor_contadotr = $row[1];
        $id_cuenta_banco_anpro = $row[2];
    }
    //    print_r($valor_contadotr."TRANSFERENCIAS1");
    if ($cont2_mixto_anpro != "") {


        $fila1[0] = $fila1[0] + 1;
        //               echo '<br>GUARDAR FACTURA CAJA1: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$id_cuenta_banco_anpro','0.000','" . $valor_contadotr . "','Activo').\n"; //////////////////////////

        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$id_cuenta_banco_anpro','0.000','" . $valor_contadotr . "','Activo')");
    }
}

///////////////////
////////////////////////////////////////
//function guardarDetallaGasto($factura, $producto, $cantidad, $precioCompra, $descuento, $total, $estado, $bienServicio, $id_cuenta, $centro_costo, $concepto, $cuenta_contable, $tipo_iva)
//{
//    $sql = "INSERT INTO detalle_gastos(id_detalle_gastos, id_gastos, cod_productos, cantidad, precio_compra, descuento_producto, total_compra, estado, bien_servicio,id_cuenta,centro_costo,concepto,cuenta_contable,tipo_iva) "
//        . "VALUES (" . obtenerIdDetalle() . ", $factura, $producto, " . number_format($cantidad, 3, '.', '') . ", " . number_format($precioCompra, 4, '.', '') . ", "
//        . "" . number_format($descuento, 4, '.', '') . ", " . number_format($total, 4, '.', '') . ", '$estado', '$bienServicio','$id_cuenta','$centro_costo','" . strtoupper($concepto) . "','$cuenta_contable','$tipo_iva')";
//    pg_query($sql);
//}

//function obtenerIdDetalle()
//{
//    $consulta = pg_query("select max(id_detalle_gastos) from detalle_gastos");
//    $id = (pg_fetch_row($consulta)[0] + 1);
//    return $id;
//}

echo $data;

/*REGISTRAR CUENTAS ASIENTO IVA*/
function obtenerTarifasImpuestoFactura($id)
{
    $sql = "select
    di.cod_impuesto, 
    di.cod_tarifa, 
    di.tarifa, 
    sum(di.valor_impuesto)valor_impuesto, 
    sum(di.base_imponible)base_imponible
    from
    gastos fc
    inner join detalle_gastos dfc
    using(id_gastos)
    inner join detalle_impuesto_producto_gasto di
    using(id_detalle_gastos)
    where id_gastos=$id
    group by di.cod_tarifa, di.cod_impuesto, di.tarifa";
    $res = pg_query($sql);
    $rows = pg_fetch_all($res);
    if (!empty($rows)) {
        return $rows;
    }
    return [];
}

function obtenerIdCuentaIVACompras($codimpuesto, $codtarifa)
{
    $sql = "select
    pc.id_cuenta_iva_compras
    from tarifa_impuesto
    inner join tipo_impuesto using(id_timpu)
    inner join parametros_cuentas_contables_iva pc using(id_taimpuesto)
    where codigo_taimpuesto='$codtarifa' and codigo_timpu='$codimpuesto'";
    $res = pg_query($sql);
    $row = pg_fetch_row($res);
    if (empty($row)) {
        return 0;
    }
    return $row[0];
}

function insertDetalleTransaccion($idtransaccion, $idcuenta, $debito, $credito)
{
    $id = obtenerSiguienteIdDetTrans();
    $sql = "INSERT INTO detalle_transaccion(
        id_detalle_transaccion, id_transacciones, id_plan_cuentas, debito, 
        credito, estado, conciliado)
        VALUES ($id, $idtransaccion, $idcuenta, $debito, 
        $credito, 'Activo', null);
        ";
    $res = pg_query($sql);
    return $res;
}

function obtenerSiguienteIdDetTrans()
{
    $sql = "select coalesce(max(id_detalle_transaccion),0) max from detalle_transaccion";
    $res = pg_query($sql);
    return pg_fetch_assoc($res)["max"] + 1;
}

function registrarCuentasIvaComprasTransaccion($idfactura, $idtransaccion)
{
    $tarifasfac = obtenerTarifasImpuestoFactura($idfactura);
    foreach ($tarifasfac as $value) {
        $idcuenta = obtenerIdCuentaIVACompras($value["cod_impuesto"], $value["cod_tarifa"]);
        insertDetalleTransaccion($idtransaccion, $idcuenta, $value["valor_impuesto"], 0);
    }
    if (empty($tarifasfac)) {
        $idcuenta = obtenerIdCuentaIVACompras(2, 2);
        if ($_POST['fecha_emision'] >= '2024-04-01') {
            $idcuenta = obtenerIdCuentaIVACompras(2, 4);
        }

        insertDetalleTransaccion($idtransaccion, $idcuenta, $_POST["iva"], 0);
    }
}
