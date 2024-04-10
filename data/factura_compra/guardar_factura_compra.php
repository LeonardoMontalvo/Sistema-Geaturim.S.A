<?php

session_start();
include '../../procesos/base.php';
require_once '../../procesos/pagosCompra.php';
require_once '../../procesos/detalleProductosBodega.php';
require_once '../../procesos/transacciones.php';
require_once '../../procesos/kardexValorizado.php';
require_once '../centro_costos/guardar_detalles.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
error_reporting(0);
$data = 0;
$cont1 = 0;
if ($_POST["id_fac"] == "") {
    //contador factura compra
    $cont1 = obtenerIdfc();
    /* $consulta = pg_query("select max(id_factura_compra) from factura_compra");
      while ($row = pg_fetch_row($consulta)) {
      $cont1 = $row[0];
      }
      $cont1++; */
    // fin
    // guardar factura compra 
    /* $conpunto = 1;
      $consultapunto = pg_query("select max(id_punto_venta_empresa) from punto_venta_empresa where punto_venta_empresa.id_usuario='$_SESSION[id]'");
      while ($row = pg_fetch_row($consultapunto)) {
      $conpunto = $row[0];
      } */

    $conpuntoresult = $_SESSION['PV'];
    /* $consultapuntoresult = pg_query("select id_punto_venta from punto_venta_empresa where id_punto_venta_empresa='$conpunto'");
      while ($row = pg_fetch_row($consultapuntoresult)) {
      $conpuntoresult = $row[0];
      } */

    //pg_query("insert into factura_compra values('$cont1','$conpuntoresult','$_POST[id_proveedor]','$_SESSION[id]','$cont1','$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[fecha_registro]','$_POST[fecha_emision]','$_POST[fecha_caducidad]','$_POST[tipo_comprobante]','$_POST[serie]','$_POST[autorizacion]','$_POST[cancelacion]','$_POST[formas]','$_POST[tarifa0]','$_POST[tarifa12]','$_POST[iva]','$_POST[desc]','$_POST[tot]','Activo', '$_POST[observaciones]', '$_POST[pago_ats]',1)");
    guardarFacturaCompra($cont1, $conpuntoresult, $_POST['id_proveedor'], $_SESSION['id'], $cont1, $_POST['fecha_actual'], $_POST['hora_actual'], $_POST['fecha_registro'], $_POST['fecha_emision'], $_POST['fecha_emision'], $_POST['tipo_comprobante'], $_POST['serie'], $_POST['autorizacion'], $_POST['cancelacion'], $_POST['formas'], $_POST['tarifa0'], $_POST['tarifa12'], $_POST['iva'], $_POST['desc'], $_POST['tot'], 'Activo', $_POST['observaciones'], $_POST['pago_ats'], 1);
    $contice = 0;
    $consulta = pg_query("select max(id_ice_factura_compra) from ice_factura_compra");
    while ($row = pg_fetch_row($consulta)) {
        $contice = $row[0];
    }
    $contice++;
    //    echo 'fff'."insert into ice_factura_compra values('$contice','$cont1','$_POST[ice]','$_POST[irbp]')";
    pg_query("insert into ice_factura_compra values('$contice','$cont1','$_POST[ice]','$_POST[irbp]')");


    $data = $cont1;
    // agregar detalle_factura_compra
    // datos detalle factura
    $campo1 = $_POST['campo1'];
    $campo2 = $_POST['campo2'];
    $campo3 = $_POST['campo3'];
    $campo4 = $_POST['campo4'];
    $campo5 = $_POST['campo5'];
    $campo6 = $_POST['campo6'];
    $campo7 = $_POST['campo7'];
    $campo8 = $_POST['campo8'];
    $campo9 = $_POST['campo9'];
    $campo10 = $_POST['campo10'];

    $arreglo1 = explode('|', $campo1);
    $arreglo2 = explode('|', $campo2);
    $arreglo3 = explode('|', $campo3);
    $arreglo4 = explode('|', $campo4);
    $arreglo5 = explode('|', $campo5);
    $arreglo6 = explode('|', $campo6);
    $arreglo7 = explode('|', $campo7);
    $arreglo8 = explode('|', $campo8);
    $arreglo9 = explode('|', $campo9);
    $arreglo10 = explode('|', $campo10);


    $nelem = count($arreglo1);
    $forma = $_POST['formas'];
    // fin

    if ($forma == "otros") {
        $consulta_mixto = pg_query("select sum(x.sum) from (select formas_pago_mixto_c.forma_pago,sum(formas_pago_mixto_c.valor) from factura_compra, formas_pago_mixto_c
where factura_compra.id_factura_compra=formas_pago_mixto_c.id_factura_compra and factura_compra.id_factura_compra='$cont1' 
and (formas_pago_mixto_c.forma_pago='CREDITO' ) GROUP BY formas_pago_mixto_c.forma_pago
)x");
        while ($row = pg_fetch_row($consulta_mixto)) {
            //                    $cont2_mixto_contado = $row[0];
            $valor_contado_credito = $row[0];
        }
        if ($valor_contado_credito != "") {
            // variables pagos
            $adelanto = '0.00';
            $meses = $_POST['meses'];
            $total = $valor_contado;
        }
        $monto = $total;
        $format = number_format($monto, 2, '.', '');



        //contador pagos compra
        $cont2 = 0;
        $consulta = pg_query("select max(id_pagos_compra) from pagos_compra");
        while ($row = pg_fetch_row($consulta)) {
            $cont2 = $row[0];
        }
        $cont2++;
        // fin
        // variables pagos
        $total = $format;

        // guardar pagos compra
        //pg_query("insert into pagos_compra values('$cont2','$_POST[id_proveedor]','$cont1','$_SESSION[id]','$_POST[fecha_actual]','0','0','$_POST[tipo_comprobante]','$total','$total','Activo')");
        guardarPagosCompra($_POST['id_proveedor'], $cont1, $_SESSION['id'], $_POST['fecha_actual'], 0, 0, $_POST['tipo_comprobante'], $total, $total, 'Activo', 'C');
        // fin

        for ($i = 1; $i < $nelem; $i++) {
            // contador detalle factura compra
            /* $cont4 = 0;
              $consulta = pg_query("select max(id_detalle_compra) from detalle_factura_compra");
              while ($row = pg_fetch_row($consulta)) {
              $cont4 = $row[0];
              }
              $cont4++; */
            // fin
            // contador kardex
            $cont_k = 0;
            $consulta_k = pg_query("select max(id_kardex) from kardex");
            while ($row = pg_fetch_row($consulta_k)) {
                $cont_k = $row[0];
            }
            $cont_k++;
            // fin
            // contador kardex valorizado
            $cont_v = 0;
            $consulta_v = pg_query("select max(id_kardex) from kardex_valorizado");
            while ($row = pg_fetch_row($consulta_v)) {
                $cont_v = $row[0];
            }
            $cont_v++;
            // fin
            $consulta_bien_servi = pg_query(" select bien_servicios,id_plan_cuentas from productos where cod_productos=$arreglo1[$i]");
            while ($row = pg_fetch_row($consulta_bien_servi)) {
                $valor_Servicio = $row[0];
                $val_id_plan_cuentas = $row[1];
            }
            // guardar detalle_factura_compra
            //pg_query("insert into detalle_factura_compra values('$cont4','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','Activo','$valor_Servicio')");
            guardarDetallaFacturaCompra($cont1, $arreglo1[$i], $arreglo2[$i], $arreglo3[$i], $arreglo4[$i], $arreglo5[$i], 'Activo', $valor_Servicio, $arreglo7[$i], $arreglo8[$i], $arreglo9[$i], $arreglo10[$i]);
            // fin 
            //      // modificar productos
            //      $consulta2=pg_query("select * from productos where cod_productos = '$arreglo1[$i]'");
            //      while($row=pg_fetch_row($consulta2))
            //       {
            //        $stock=$row[13];
            //       }
            //      $cal=$stock+$arreglo2[$i];
            //      
            if ($arreglo3[$i] > 0) {
//                pg_query("Update productos Set precio_compra='" . $arreglo3[$i] . "', stock='" . $cal . "' where cod_productos='" . $arreglo1[$i] . "'");
            }
            
            //      // fin
            $contb = 0;
            $consulta = pg_query("select max(id_detalle_productos_bodega) from detalle_producto_bodega");
            while ($row = pg_fetch_row($consulta)) {
                $contb = $row[0];
            }
            $contb++;

            // guardar detalle productos bodega

            $consulta_v = pg_query("select * from detalle_producto_bodega where id_bodega=$conpuntoresult and cod_productos=$arreglo1[$i]");
            while ($row = pg_fetch_row($consulta_v)) {
                $cod_pro = $row[1];
                $id_bod = $row[2];
                $stock = $row[6];
            }
            //$cal = $stock + $arreglo2[$i];
            if ($arreglo7[$i] != 0) {
                $cal = $stock + $arreglo7[$i];
            } else {
                $cal = $stock + $arreglo2[$i];
            }

            if ($cod_pro == $arreglo1[$i] && $id_bod == $conpuntoresult) {
                //pg_query("Update detalle_producto_bodega Set fecha='" . $_POST[fecha_actual] . "' ,hora='" . $_POST[hora_actual] . "', stock='" . $cal . "' where cod_productos='" . $arreglo1[$i] . "' and id_bodega='" . $conpuntoresult . "' ");
                actualizarDetalleProductoBodega($arreglo1[$i], $conpuntoresult, $cal);
            } else {
                $contb = 0;
                $consulta = pg_query("select max(id_detalle_productos_bodega) from detalle_producto_bodega");
                while ($row = pg_fetch_row($consulta)) {
                    $contb = $row[0];
                }
                $contb++;
                $horap = date("g:ia");
                //pg_query("insert into detalle_producto_bodega values('$contb','$arreglo1[$i]','$conpuntoresult','$_SESSION[id]','$_POST[fecha_actual]','$horap','$cal')");
                guardarDetalleProductoBodega($arreglo1[$i], $conpuntoresult, $_SESSION['id'], $cal);
            }
            // consulta kardex valorizado
            $cantidad = 0;
            $precio_total = 0;
            $consulta2 = pg_query("select * from kardex_valorizado where cod_productos = $arreglo1[$i] AND id_empresa= $conpuntoresult order by id_kardex asc");
            while ($row = pg_fetch_row($consulta2)) {
                $cantidad = $row[11];
                $precio_total = $row[8];
                $costo_ven_unitario = $row[13];
                $contR = 1;
            }
            if ($arreglo7[$i] != 0) {
                $cantidad_entrada = $arreglo7[$i];
                $precio_unitario_entrada = $arreglo3[$i];
                $precio_total_entrada = number_format($arreglo5[$i], 4, '.', '');

                $cantidad_total = $cantidad + $arreglo7[$i];
                $precio_total_total = number_format($precio_total + $precio_total_entrada, 4, '.', '');
                $precio_unitario_total = number_format($precio_total_total / $cantidad_total, 4, '.', '');
            } else {
                $cantidad_entrada = $arreglo2[$i];
                $precio_unitario_entrada = $arreglo3[$i];
                $precio_total_entrada = number_format($arreglo5[$i], 4, '.', '');

                $cantidad_total = $cantidad + $arreglo2[$i];
                $precio_total_total = number_format($precio_total + $precio_total_entrada, 4, '.', '');
                $precio_unitario_total = number_format($precio_total_total / $cantidad_total, 4, '.', '');
            }

            if (!empty($arreglo4[$i])) {
                $descpu = $precio_unitario_entrada * ($arreglo4[$i] / 100);
                $precio_unitario_entrada = $precio_unitario_entrada - $descpu;
            }

            if ($contR == 1) {
                $costo_promediounitario = (($cantidad * $costo_ven_unitario) + ($cantidad_entrada * $precio_unitario_entrada)) / ($cantidad + $cantidad_entrada);
            } else {
                $costo_promediounitario = $precio_unitario_entrada;
            }

            if ($arreglo7[$i] != 0) {
                procesarKardexValorizadoEntrada($arreglo1[$i], $_POST['fecha_actual'], 'F.C: ' . $_POST['serie'], $arreglo7[$i], $cantidad, $precio_unitario_entrada, 'Activo', $conpuntoresult, 'C', $cont1, NULL, NULL);
            } else {
                procesarKardexValorizadoEntrada($arreglo1[$i], $_POST['fecha_actual'], 'F.C: ' . $_POST['serie'], $arreglo2[$i], $cantidad, $precio_unitario_entrada, 'Activo', $conpuntoresult, 'C', $cont1, NULL, NULL);
            }

            // fin
            // guardar kardex
            //            pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '" . 'F.C:' . $_POST['serie'] . "' ,'" . number_format($arreglo2[$i], 2, '.', '') . "',"
            //                    . "'" . number_format($arreglo3[$i], 4, '.', '') . "','" . number_format($arreglo5[$i], 3, '.', '') . "',$arreglo1[$i],'" . number_format($cal, 3, '.', '') . "',"
            //                    . "'Activo',NULL,NULL,$_POST[id_proveedor],'$cont1','C',$conpuntoresult,'" . $_POST[observaciones] . "')");
            //            
            //          
            if ($arreglo7[$i] != 0) {
                insertKardex($_POST['fecha_actual'], 'F.C:' . $_POST['serie'], $arreglo7[$i], $arreglo3[$i], $arreglo5[$i], $arreglo1[$i], $cal, 'Activo', NULL, NULL, $_POST['id_proveedor'], $cont1, 'C', $conpuntoresult, $_POST['observaciones']);
            } else {
                insertKardex($_POST['fecha_actual'], 'F.C:' . $_POST['serie'], $arreglo2[$i], $arreglo3[$i], $arreglo5[$i], $arreglo1[$i], $cal, 'Activo', NULL, NULL, $_POST['id_proveedor'], $cont1, 'C', $conpuntoresult, $_POST['observaciones']);
            }
            // fin
            ////////////////////////
            //Asiento Contable 
            //            $cuenta = pg_query("select iva,id_plan_cuentas from productos where cod_productos ='" . $arreglo1[$i] . "' and bien_servicios='B'");
            //            $plan = pg_fetch_row($cuenta);
            //
            //            if ($plan[0] == "Si") {
            //                $sumaSubtotalTarifa12B = $sumaSubtotalTarifa12B + $arreglo5[$i];
            //                $codplanTarifa12B = $plan[1];
            //                $contTarifa12++;
            //            } else if ($plan[0] == "No") {
            //                $sumaSubtotalTarifa0B = $sumaSubtotalTarifa0B + $arreglo5[$i];
            //                $codplanTarifa0B = $plan[1];
            //                $contTarifa0++;
            //            }
            //            $cuenta = pg_query("select iva,id_plan_cuentas from productos where cod_productos ='" . $arreglo1[$i] . "' and bien_servicios='S'");
            //            $plan = pg_fetch_row($cuenta);
            //
            //            if ($plan[0] == "Si") {
            //                $sumaSubtotalTarifa12 = $sumaSubtotalTarifa12 + $arreglo5[$i];
            //                $codplanTarifa12 = $plan[1];
            //                $contTarifa12++;
            //            } else if ($plan[0] == "No") {
            //                $sumaSubtotalTarifa0 = $sumaSubtotalTarifa0 + $arreglo5[$i];
            //                $codplanTarifa0 = $plan[1];
            //                $contTarifa0++;
            //            }
            /////////////////////////   
        }
    } else {
        if ($forma == "Pendiente") {
            for ($i = 1; $i < $nelem; $i++) {
                if (!empty($arreglo1[$i])) {
                    // cont0000000ador detalle factura compra
                    $cont6 = 0;
                    $consulta = pg_query("select max(id_detalle_compra) from detalle_factura_compra");
                    while ($row = pg_fetch_row($consulta)) {
                        $cont6 = $row[0];
                    }
                    $cont6++;
                    // fin
                    // contador kardex
                    $cont_k = 0;
                    $consulta_k = pg_query("select max(id_kardex) from kardex");
                    while ($row = pg_fetch_row($consulta_k)) {
                        $cont_k = $row[0];
                    }
                    $cont_k++;
                    // fin
                    // contador kardex valorizado

                    $cont_v = 0;
                    $consulta_v = pg_query("select max(id_kardex) from kardex_valorizado");
                    while ($row = pg_fetch_row($consulta_v)) {
                        $cont_v = $row[0];
                    }
                    $cont_v++;
                    // fin
                    $bien_servi = "";
                    $bien_servi = $_POST['bien_servi'];

                    $consulta_bien_servi = pg_query(" select bien_servicios,id_plan_cuentas from productos where cod_productos=$arreglo1[$i]");
                    while ($row = pg_fetch_row($consulta_bien_servi)) {
                        $valor_Servicio = $row[0];
                        $val_id_plan_cuentas = $row[1];
                    }

                    //           print_r($valor_Servicio);
                    // guardar detalle_factura
                    //pg_query("insert into detalle_factura_compra values('$cont6','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','Activo','$valor_Servicio')");
                    guardarDetallaFacturaCompra($cont1, $arreglo1[$i], $arreglo2[$i], $arreglo3[$i], $arreglo4[$i], $arreglo5[$i], 'Activo', $valor_Servicio, $arreglo7[$i], $arreglo8[$i], $arreglo9[$i], $arreglo10[$i]);
                    // fin
                    //        // modificar productos
                    //        $consulta2=pg_query("select * from productos where cod_productos = '$arreglo1[$i]'");
                    //        while($row=pg_fetch_row($consulta2))
                    //         {
                    //          $stock=$row[13];
                    //         }
                    //        $cal=$stock+$arreglo2[$i];
                    //        
                    if ($arreglo3[$i] > 0) {
//                        pg_query("Update productos Set precio_compra='" . $arreglo3[$i] . "' where cod_productos='" . $arreglo1[$i] . "'");
                    }
                    
                    //        // fin
                    $contb = 0;
                    $consulta = pg_query("select max(id_detalle_productos_bodega) from detalle_producto_bodega");
                    while ($row = pg_fetch_row($consulta)) {
                        $contb = $row[0];
                    }
                    $contb++;

                    // guardar detalle productos bodega

                    $consulta_v = pg_query("select * from detalle_producto_bodega where id_bodega=$conpuntoresult and cod_productos=$arreglo1[$i]");
                    while ($row = pg_fetch_row($consulta_v)) {
                        $cod_pro = $row[1];
                        $id_bod = $row[2];
                        $stock = $row[6];
                    }
                    //$cal = $stock + $arreglo2[$i];
                    if ($arreglo7[$i] != 0) {
                        $cal = $stock + $arreglo7[$i];
                    } else {
                        $cal = $stock + $arreglo2[$i];
                    }

                    if ($cod_pro == $arreglo1[$i] && $id_bod == $conpuntoresult) {
                        //pg_query("Update detalle_producto_bodega Set fecha='" . $_POST[fecha_actual] . "' ,hora='" . $_POST[hora_actual] . "', stock='" . $cal . "' where cod_productos='" . $arreglo1[$i] . "' and id_bodega='" . $conpuntoresult . "' ");
                        actualizarDetalleProductoBodega($arreglo1[$i], $conpuntoresult, $cal);
                    } else {
                        $contb = 0;
                        $consulta = pg_query("select max(id_detalle_productos_bodega) from detalle_producto_bodega");
                        while ($row = pg_fetch_row($consulta)) {
                            $contb = $row[0];
                        }
                        $contb++;
                        $horap = date("g:ia");
                        //pg_query("insert into detalle_producto_bodega values('$contb','$arreglo1[$i]','$conpuntoresult','$_SESSION[id]','$_POST[fecha_actual]','$horap','$cal')");
                        guardarDetalleProductoBodega($arreglo1[$i], $conpuntoresult, $_SESSION['id'], $cal);
                    }
                    // consulta kardex valorizado
                    $cantidad = 0;
                    $precio_total = 0;
                    $consulta2 = pg_query("select * from kardex_valorizado where cod_productos = $arreglo1[$i] AND id_empresa= $conpuntoresult order by id_kardex asc");
                    while ($row = pg_fetch_row($consulta2)) {
                        $cantidad = $row[11];
                        $precio_total = $row[8];
                        $costo_ven_unitario = $row[13];
                        $contR = 1;
                    }

                    if ($arreglo7[$i] != 0) {
                        $cantidad_entrada = $arreglo7[$i];
                        $precio_unitario_entrada = $arreglo3[$i];
                        $precio_total_entrada = number_format($arreglo5[$i], 4, '.', '');

                        $cantidad_total = $cantidad + $arreglo7[$i];
                        $precio_total_total = number_format($precio_total + $precio_total_entrada, 4, '.', '');
                        $precio_unitario_total = number_format($precio_total_total / $cantidad_total, 4, '.', '');
                    } else {
                        $cantidad_entrada = $arreglo2[$i];
                        $precio_unitario_entrada = $arreglo3[$i];
                        $precio_total_entrada = number_format($arreglo5[$i], 4, '.', '');

                        $cantidad_total = $cantidad + $arreglo2[$i];
                        $precio_total_total = number_format($precio_total + $precio_total_entrada, 4, '.', '');
                        $precio_unitario_total = number_format($precio_total_total / $cantidad_total, 4, '.', '');
                    }

                    if (!empty($arreglo4[$i])) {
                        $descpu = $precio_unitario_entrada * ($arreglo4[$i] / 100);
                        $precio_unitario_entrada = $precio_unitario_entrada - $descpu;
                    }

                    if ($contR == 1) {
                        $costo_promediounitario = (($cantidad * $costo_ven_unitario) + ($cantidad_entrada * $precio_unitario_entrada)) / ($cantidad + $cantidad_entrada);
                    } else {
                        $costo_promediounitario = $precio_unitario_entrada;
                    }

                    // pg_query("insert into kardex_valorizado values('$cont_v','$arreglo1[$i]','$_POST[fecha_actual]', '" . 'Compra: ' . $seriefin . "','" . $cantidad_entrada . "','','" . $cantidad . "','" . $precio_unitario_entrada . "','" . $precio_total_entrada . "','','','" . $cantidad_total . "','2')");
                    /* pg_query("insert into kardex_valorizado values('$cont_v',$arreglo1[$i],'$_POST[fecha_actual]', '" . 'Compra: ' . $_POST['serie'] . "','" . number_format($cantidad_entrada, 2, '.', '') . "'"
                      . ",NULL,'" . number_format($cantidad, 2, '.', '') . "','" . number_format($precio_unitario_entrada, 3, '.', '') . "','" . number_format($precio_total_entrada, 3, '.', '') . "',"
                      . "NULL,NULL,'" . number_format($cantidad_total, 2, '.', '') . "','2')");

                      $sql = "INSERT INTO kardex_valorizado(id_kardex, cod_productos, fecha_transaccion, concepto, entrada, "
                      . "salida, existencia, costo_unitario, costo_promedio, debe, haber,  saldo, estado, "
                      . "costo_prom_unitario, id_empresa, compra_venta,          comprobante)   VALUES (?, ?, ?, ?, ?,            ?, ?, ?, ?, ?, ?,            ?, ?, ?, ?, ?,             ?)";

                      pg_query("insert into kardex_valorizado values('$cont_v','$arreglo1[$i]','$_POST[fecha_actual]', '" . 'Compra: ' . $_POST['serie'] . "','" . $cantidad_entrada . "','','" . $cantidad . "','" . $precio_unitario_entrada . "','" . $precio_total_entrada . "','','','" . $cantidad_total . "','2','" . $costo_promediounitario . "','1','C','$cont1')"); */
                    if ($arreglo7[$i] != 0) {
                        procesarKardexValorizadoEntrada($arreglo1[$i], $_POST['fecha_actual'], 'F.C: ' . $_POST['serie'], $arreglo7[$i], $cantidad, $precio_unitario_entrada, 'Activo', $conpuntoresult, 'C', $cont1, NULL, NULL);
                    } else {
                        procesarKardexValorizadoEntrada($arreglo1[$i], $_POST['fecha_actual'], 'F.C: ' . $_POST['serie'], $arreglo2[$i], $cantidad, $precio_unitario_entrada, 'Activo', $conpuntoresult, 'C', $cont1, NULL, NULL);
                    }


                    // fin
                    // guardar kardex
                    //pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '" . 'F.C:' . $_POST['serie'] . "' ,'$arreglo2[$i]','$arreglo3[$i]','$arreglo5[$i]','$arreglo1[$i]','$cal','1','','','$_POST[id_proveedor]','$cont1','C','$conpuntoresult')");

                    /* pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '" . 'F.C:' . $_POST['serie'] . "' ,'" . number_format($arreglo2[$i], 3, '.', '') . "',"
                      . "'" . number_format($arreglo3[$i], 3, '.', '') . "','" . number_format($arreglo5[$i], 3, '.', '') . "',$arreglo1[$i],'" . number_format($cal, 3, '.', '') . "',"
                      . "'Activo',NULL,NULL,$_POST[id_proveedor],'$cont1','C',$conpuntoresult,'" . $_POST[observaciones] . "')"); */

                    if ($arreglo7[$i] != 0) {
                        insertKardex($_POST['fecha_actual'], 'F.C:' . $_POST['serie'], $arreglo7[$i], $arreglo3[$i], $arreglo5[$i], $arreglo1[$i], $cal, 'Activo', NULL, NULL, $_POST['id_proveedor'], $cont1, 'C', $conpuntoresult, $_POST['observaciones']);
                    } else {
                        insertKardex($_POST['fecha_actual'], 'F.C:' . $_POST['serie'], $arreglo2[$i], $arreglo3[$i], $arreglo5[$i], $arreglo1[$i], $cal, 'Activo', NULL, NULL, $_POST['id_proveedor'], $cont1, 'C', $conpuntoresult, $_POST['observaciones']);
                    }
                    //actualizarPrecioPromedio($arreglo1[$i], $conpuntoresult, $costo);
                    // fin
                    ////////////////////////
                    //Asiento Contable 
                    //                    $cuenta = pg_query("select iva,id_plan_cuentas from productos where cod_productos ='" . $arreglo1[$i] . "' and bien_servicios='B'");
                    //                    $plan = pg_fetch_row($cuenta);
                    //
                    //                    if ($plan[0] == "Si") {
                    //                        $sumaSubtotalTarifa12B = $sumaSubtotalTarifa12B + $arreglo5[$i];
                    //                        $codplanTarifa12B = $plan[1];
                    //                        $contTarifa12++;
                    //                    } else if ($plan[0] == "No") {
                    //                        $sumaSubtotalTarifa0B = $sumaSubtotalTarifa0B + $arreglo5[$i];
                    //                        $codplanTarifa0B = $plan[1];
                    //                        $contTarifa0++;
                    //                    }
                    //                    $cuenta = pg_query("select iva,id_plan_cuentas from productos where cod_productos ='" . $arreglo1[$i] . "' and bien_servicios='S'");
                    //                    $plan = pg_fetch_row($cuenta);
                    //
                    //                    if ($plan[0] == "Si") {
                    //                        $sumaSubtotalTarifa12 = $sumaSubtotalTarifa12 + $arreglo5[$i];
                    //                        $codplanTarifa12 = $plan[1];
                    //                        $contTarifa12++;
                    //                    } else if ($plan[0] == "No") {
                    //                        $sumaSubtotalTarifa0 = $sumaSubtotalTarifa0 + $arreglo5[$i];
                    //                        $codplanTarifa0 = $plan[1];
                    //                        $contTarifa0++;
                    //                    }
                    /////////////////////////
                }
            }
        }
    }

    $data = $cont1;
    // guardar asiento contable
    ////////////////////////////////////////////
    //    // ASIENTO CONTABLE
    //    $idtran = pg_query("select max(id_transacciones) from transacciones");
    //    $fila = pg_fetch_row($idtran);
    //    $fila[0] = $fila[0] + 1;
    //    $sum = 0;
    //    $bool = true;
    //    $pos = 0;
    //    $vec = 0;
    //    $auxiliar = $arreglo1;
    //    while ($bool) {
    //        $cuenta = pg_query("select id_plan_cuentas from productos where cod_productos='" . $auxiliar[0] . "'");
    //        $plan = pg_fetch_row($cuenta);
    //        $nelem = count($auxiliar);
    //        $vec = 0;
    //        for ($i = 0; $i <= $nelem; $i++) {
    //            $cuenta1 = pg_query("select id_plan_cuentas from productos where cod_productos='" . $auxiliar[$i] . "'");
    //            $plan1 = pg_fetch_row($cuenta1);
    //            if ($plan1[$i] == $plan[0]) {
    //                $sum = $arreglo5[$i] + $sum;
    //            } else {
    //                $vec[$pos] = $auxiliar[$i];
    //                $pos++;
    //            }
    //        }
    //        if ($vec == 0) {
    //            $bool = false;
    //        } else {
    //            $auxiliar = $vec;
    //            $pos = 0;
    //        }
    //    }
    //    $sum = number_format($sum, 3, '.', '');
    //    $sum = $sum + $_POST['iva'];
    //    $saldo = $sum - $_POST['tot'];
    //    $saldo = number_format($saldo, 3, '.', '');
    //    $prove = pg_query("select identificacion_pro from proveedores where id_proveedor='$_POST[id_proveedor]'");
    //    $p = pg_fetch_row($prove);
    //    $ing = pg_query("select max(num_transaccion) from transacciones where id_tipo_transaccion='1'");
    //    $res = pg_fetch_row($ing);
    //    $formaPa = " ";
    //    if ($forma == "Contado") {
    //        $formaPa = ", FOR. PAGO:Cont.";
    //    } else if ($forma == "otros") {
    //        
    ////      	 echo '<br>GUARDAR FACTURA VENTARR4: <br>' . "select sum(x.sum) from (select formas_pago_mixto_c.forma_pago,sum(formas_pago_mixto_c.valor) from factura_compra, formas_pago_mixto_c
    ////where factura_compra.id_factura_compra=formas_pago_mixto_c.id_factura_compra and factura_compra.id_factura_compra='$cont1' 
    ////and (formas_pago_mixto_c.forma_pago='CREDITO'  ) GROUP BY formas_pago_mixto_c.forma_pago
    ////)x";//////////////////////////
    ////	   
    //        
    //        
    //        
    //      $consulta_mixto_credito = pg_query("select sum(x.sum) from (select formas_pago_mixto_c.forma_pago,sum(formas_pago_mixto_c.valor) from factura_compra, formas_pago_mixto_c
    //where factura_compra.id_factura_compra=formas_pago_mixto_c.id_factura_compra and factura_compra.id_factura_compra='$cont1' 
    //and (formas_pago_mixto_c.forma_pago='CREDITO'  ) GROUP BY formas_pago_mixto_c.forma_pago
    //)x");
    //    while ($row = pg_fetch_row($consulta_mixto_credito)) {
    ////                    $cont2_mixto_contado = $row[0];
    //        $valor_contado_credi = $row[0];
    //    }
    //    if ($valor_contado_credi != "") {
    //
    //        $formaPa = ", FOR. PAGO:Crédi.";
    //    }
    //    $consulta_mixto_cheque = pg_query("select sum(x.sum) from (select formas_pago_mixto_c.forma_pago,sum(formas_pago_mixto_c.valor) from factura_compra, formas_pago_mixto_c
    //where factura_compra.id_factura_compra=formas_pago_mixto_c.id_factura_compra and factura_compra.id_factura_compra='$cont1' 
    //and (formas_pago_mixto_c.forma_pago='CHEQUE'  ) GROUP BY formas_pago_mixto_c.forma_pago
    //)x");
    //    while ($row = pg_fetch_row($consulta_mixto_cheque)) {
    ////                    $cont2_mixto_contado = $row[0];
    //        $valor_contado_cheque = $row[0];
    //    }
    //    if ($valor_contado_cheque != "") {
    //
    //        $formaPa = ", FOR. PAGO:Cheque.";
    //    }
    //    $consulta_mixto_tcredito = pg_query("select sum(x.sum) from (select formas_pago_mixto_c.forma_pago,sum(formas_pago_mixto_c.valor) from factura_compra, formas_pago_mixto_c
    //where factura_compra.id_factura_compra=formas_pago_mixto_c.id_factura_compra and factura_compra.id_factura_compra='$cont1' 
    //and (formas_pago_mixto_c.forma_pago='TCREDITO'  ) GROUP BY formas_pago_mixto_c.forma_pago
    //)x");
    //    while ($row = pg_fetch_row($consulta_mixto_tcredito)) {
    ////                    $cont2_mixto_contado = $row[0];
    //        $valor_contado_tcredito = $row[0];
    //    }
    //    if ($valor_contado_tcredito != "") {
    //
    //        $formaPa = ", FOR. PAGO:TCredito.";
    //    }
    //    $consulta_mixto_trans = pg_query("select sum(x.sum) from (select formas_pago_mixto_c.forma_pago,sum(formas_pago_mixto_c.valor) from factura_compra, formas_pago_mixto_g 
    //where factura_compra.id_factura_compra=formas_pago_mixto_c.id_factura_compra and factura_compra.id_factura_compra='$cont1' 
    //and (formas_pago_mixto_c.forma_pago='TRANSFERENCIAS'  ) GROUP BY formas_pago_mixto_c.forma_pago
    //)x");
    //    while ($row = pg_fetch_row($consulta_mixto_trans)) {
    ////                    $cont2_mixto_contado = $row[0];
    //        $valor_contado_trans = $row[0];
    //    }
    //    if ($valor_contado_trans != "") {
    //
    //        $formaPa = ", FOR. PAGO:Transferencias.";
    //    }
    //        
    //        
    //    }
    //
    //    $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'COMPRA PRODUCTOS, PROVEEDOR: " . $p[0] . ", COMPROBANTE: " . $_POST['serie'] . "" . $formaPa . "', '" . $sum . "', '$_POST[tot]', '" . $saldo . "','1','" . ($res[0] + 1) . "','Activo','$_POST[id_proveedor]','','','','','COM','',$conpuntoresult,'$_POST[fecha_emision]')");
    //
    //    $auxiliar = $arreglo1;
    //    $suma = 0;
    //    $bool = true;
    //    $aa = 0;
    //    $ab = 0;
    //    $pos = 1;
    //    $vec = "";
    //    $ant = $arreglo5;
    //    $vec1 = "";
    //    while ($bool) {
    //        $cont11 = 0;
    //        $cont2 = 0;
    //        $aa = 0;
    //        $ab = 0;
    //        $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
    //        $fila1 = pg_fetch_row($iddettran);
    //        //  $fila1[0]=$fila1[0]+1;
    //        $cuenta = pg_query("select id_plan_cuentas from productos where cod_productos='" . $auxiliar[1] . "'");
    //        while ($plan = pg_fetch_row($cuenta)) {
    //            $cont2 = $plan[0];
    //        }
    //        if ($cont2 == 0) {
    //            $cuenta = pg_query("select id_plan_cuentas from productos where cod_productos='" . $auxiliar[0] . "'");
    //            while ($plan = pg_fetch_row($cuenta)) {
    //                $cont2 = $plan[0];
    //            }
    //        }
    //        $nelem1 = count($auxiliar);
    //        $suma = 0;
    //        for ($i = 0; $i <= $nelem1; $i++) {
    //            if (!empty($auxiliar[$i])) {
    //                $cuenta1 = pg_query("select id_plan_cuentas from productos where cod_productos='" . $auxiliar[$i] . "'");
    //                while ($plan1 = pg_fetch_row($cuenta1)) {
    //                    $cont11 = $plan1[0];
    //                }
    //                if ($cont11 != 0) {
    //                    if ($cont11 == $cont2) {
    //                        $aa++;
    //                        $suma = $ant[$i] + $suma;
    //                        //$fila1[0]++;
    //                        //pg_query("insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','4','".$cont2."-".$aa."','".$nelem1."-i".$i."-".$cont1."','prueba')");
    //                    } else {
    //                        $ab++;
    //                        $vec[$pos] = $auxiliar[$i];
    //                        $vec1[$pos] = $ant[$i];
    //                        $pos++;
    //                        //$fila1[0]++;
    //                        //pg_query("insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','4','".$cont2."-".$ab."','".$nelem1."-i".$i."-".$cont1."','prueba2')");
    //                    }
    //                }
    //                //$ant=$ant.$cont1."-";
    //            }
    //        }
    //        // $fila1[0]++;
    //        $suma = number_format($suma, 3, '.', '');
    //
    //        // pg_query("insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','".$cont2."','$suma','0.000','Activo')");
    //        if ($vec == "") {
    //            //$ab++;
    //            $bool = false;
    //        } else {
    //            //$fila1[0]++;
    //            //pg_query("insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','4','".$pos."','".$vec[$pos-2]."','otra vez')");
    //            $auxiliar = $vec;
    //            $ant = $vec1;
    //            $vec = "";
    //            $vec1 = "";
    //            $pos = 0;
    //        }
    //    }
    //
    //    ////////////////////
    //    //asiento generico Inventario
    //    if ($sumaSubtotalTarifa12B > 0) {
    //        $fila1[0] = $fila1[0] + 1;
    ////        	 echo '<br>GUARDAR FACTURA VENTA1: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa12B','$sumaSubtotalTarifa12B','0.000','Activo')";//////////////////////////
    //	 
    //        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa12B','$sumaSubtotalTarifa12B','0.000','Activo')");
    //    }
    //    if ($sumaSubtotalTarifa12 > 0) {
    //        $fila1[0] = $fila1[0] + 1;
    ////        	 echo '<br>GUARDAR FACTURA VENTA2: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa12','$sumaSubtotalTarifa12','0.000','Activo')";//////////////////////////
    //	 
    //        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa12','$sumaSubtotalTarifa12','0.000','Activo')");
    //    }
    ////       if ($sumaSubtotalTarifa0B > 0) {
    ////        $fila1[0] = $fila1[0] + 1;
    //////        	 echo '<br>GUARDAR FACTURA VENTA3: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa0B','$sumaSubtotalTarifa0B','0.000','Activo')";//////////////////////////
    ////	 
    ////        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa0B','$sumaSubtotalTarifa0B','0.000','Activo')");
    ////    }
    //    if ($sumaSubtotalTarifa0 > 0) {
    //        $fila1[0] = $fila1[0] + 1;
    ////        	 echo '<br>GUARDAR FACTURA VENTA3: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa0','$sumaSubtotalTarifa0','0.000','Activo')";//////////////////////////
    //	 
    //        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa0','$sumaSubtotalTarifa0','0.000','Activo')");
    //    }
    //    ///////////////////
    //
    //
    //    $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
    //    $fila1 = pg_fetch_row($iddettran);
    //
    //
    //    $planiva = pg_query("select cuenta_debito from parametros where descripcion='IVA'");
    //    $fila2 = pg_fetch_row($planiva);
    //    if ($_POST[iva] != '0.000') {
    //        $fila1[0] = $fila1[0] + 1;
    ////        	 echo '<br>GUARDAR FACTURA VENTA: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','$_POST[iva]','0.000','Activo')";//////////////////////////
    //	 
    //        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','$_POST[iva]','0.000','Activo')");
    //    }
    //    // $fila1[0]=$fila1[0]+1;
    //    //Ya va restado en Subtotal
    //    /*
    //      ////////////////Añadir Descuento////////////////
    //      if($_POST['desc']!=="0.000"){
    //      $fila1[0]=$fila1[0]+1;
    //      $des=pg_query("select cuenta_debito from parametros where descripcion='DESCUENTOS COMPRAS'");
    //      $descuento=pg_fetch_row($des);
    //      // $val=$_POST['tot']-$_POST['desc'];
    //      pg_query("insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','".$descuento[0]."','0.000','".$_POST['desc']."','Activo')");
    //
    //      }
    //     */
    //
    //    //VER SI ES CONTADO O CREDITO
    //    if ($forma == "Contado") {
    //        $fila1[0] = $fila1[0] + 1;
    //        $total = $_POST['tot'];
    //        $plancaja = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
    //        $fila2 = pg_fetch_row($plancaja);
    ////        	 echo '<br>GUARDAR FACTURA VENTA33: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','0.000','" . $total . "','Activo')";//////////////////////////
    //	 
    //        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','0.000','" . $total . "','Activo')");
    //    } else if ($forma == "otros") {
    //        
    ////         echo '<br>GUARDAR FACTURA MIXTOCHEQUECONTADO: <br>' . "select sum(x.sum) from (select formas_pago_mixto_c.forma_pago,sum(formas_pago_mixto_c.valor) from factura_compra, formas_pago_mixto_c 
    ////where factura_compra.id_factura_compra=formas_pago_mixto_c.id_factura_compra and factura_compra.id_factura_compra='$cont1' 
    ////and (formas_pago_mixto_c.forma_pago='CONTADO' ) GROUP BY formas_pago_mixto_c.forma_pago
    ////)x";//////////////////////////
    ////	   
    //               
    //        
    //         $consulta_mixto = pg_query("select sum(x.sum) from (select formas_pago_mixto_c.forma_pago,sum(formas_pago_mixto_c.valor) from factura_compra, formas_pago_mixto_c 
    //where factura_compra.id_factura_compra=formas_pago_mixto_c.id_factura_compra and factura_compra.id_factura_compra='$cont1' 
    //and ( formas_pago_mixto_c.forma_pago='CONTADO' ) GROUP BY formas_pago_mixto_c.forma_pago
    //)x");
    //    while ($row = pg_fetch_row($consulta_mixto)) {
    ////                    $cont2_mixto_contado = $row[0];
    //        $valor_contado_contado = $row[0];
    //    }
    //    if ($valor_contado_contado != "") {
    //
    //        $sql = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
    //        $buscaCuenta = pg_fetch_row($sql);
    //        $fila1[0] = $fila1[0] + 1;
    ////       echo '<br>GUARDAR FACTURA MIXTOCHEQUECONTADO1: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','0.000','" . $valor_contado . "','Activo')"; //////////////////////////
    //
    //        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','0.000','" . $valor_contado . "','Activo')");
    //    }
    ////      	 echo '<br>GUARDAR FACTURA TCREDITO: <br>' . "select formas_pago_mixto_g.forma_pago,formas_pago_mixto_g.valor,formas_pago_mixto_g.id_cuenta from gastos, formas_pago_mixto_g
    ////where gastos.id_gastos=formas_pago_mixto_g.id_gastos and gastos.id_gastos='$cont1' and formas_pago_mixto_g.forma_pago='TCREDITO'
    ////
    ////";//////////////////////////
    ////	 
    ////    
    //    
    //    $consulta_mixto = pg_query("select formas_pago_mixto_g.forma_pago,formas_pago_mixto_g.valor,formas_pago_mixto_g.id_cuenta from gastos, formas_pago_mixto_g
    //where gastos.id_gastos=formas_pago_mixto_g.id_gastos and gastos.id_gastos='$cont1' and formas_pago_mixto_g.forma_pago='TCREDITO'
    //
    //");
    //    while ($row = pg_fetch_row($consulta_mixto)) {
    // $cont2_mixto_credito = $row[0];
    //        $valor_contadocrdito = $row[1];
    //        $id_cuenta_banco = $row[2];
    //    }
    //    if ($cont2_mixto_credito != "") {
    //
    //        $sql = pg_query("select cuenta_debito from parametros where descripcion='TARJETA DE CREDITO'");
    //        $buscaCuenta = pg_fetch_row($sql);
    //        $fila1[0] = $fila1[0] + 1;
    ////          echo '<br>GUARDAR FACTURA TCREDITO1: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$id_cuenta_banco','0.000','" . $valor_contadocrdito . "','Activo')"; //////////////////////////
    //
    //        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$id_cuenta_banco','0.000','" . $valor_contadocrdito . "','Activo')");
    //    }
    ////          echo '<br>GUARDAR FACTURA TRANSFERENCIAS: <br>' . "
    ////select formas_pago_mixto_c.forma_pago,formas_pago_mixto_c.valor,formas_pago_mixto_c.id_cuenta from factura_compra, formas_pago_mixto_c 
    ////where factura_compra.id_factura_compra=formas_pago_mixto_c.id_factura_compra and factura_compra.id_factura_compra='$cont1' and formas_pago_mixto_c.forma_pago='TRANSFERENCIAS'"; //////////////////////////
    ////
    ////    
    //    
    //    $consulta_mixto_transfer = pg_query("
    //select formas_pago_mixto_c.forma_pago,formas_pago_mixto_c.valor,formas_pago_mixto_c.id_cuenta from factura_compra, formas_pago_mixto_c 
    //where factura_compra.id_factura_compra=formas_pago_mixto_c.id_factura_compra and factura_compra.id_factura_compra='$cont1' and formas_pago_mixto_c.forma_pago='TRANSFERENCIAS'");
    //    while ($row = pg_fetch_row($consulta_mixto_transfer)) {
    //        $cont2_mixto_transferencias = $row[0];
    //        $valor_contadotr = $row[1];
    //        $id_cuenta_banco_trans = $row[2];
    //    }
    //    if ($cont2_mixto_transferencias != "") {
    //
    //        $sql = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
    //        $buscaCuenta = pg_fetch_row($sql);
    //        $fila1[0] = $fila1[0] + 1;
    ////                    echo '<br>GUARDAR FACTURA TRANSFERENCIAS1: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$id_cuenta_banco_trans','0.000','" . $valor_contadotr . "','Activo')"; //////////////////////////
    //
    //        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$id_cuenta_banco_trans','0.000','" . $valor_contadotr . "','Activo')");
    //    }
    ////              echo '<br>GUARDAR FACTURA CHEQUE: <br>' . "
    ////select formas_pago_mixto_c.forma_pago,formas_pago_mixto_c.valor,formas_pago_mixto_c.id_cuenta from factura_compra, formas_pago_mixto_c 
    ////where factura_compra.id_factura_compra=formas_pago_mixto_c.id_factura_compra and factura_compra.id_factura_compra='$cont1' and formas_pago_mixto_c.forma_pago='TRANSFERENCIAS'"; //////////////////////////
    ////
    ////    
    //        $consulta_mixto = pg_query("
    //select formas_pago_mixto_c.forma_pago,formas_pago_mixto_c.valor,formas_pago_mixto_c.id_cuenta from factura_compra, formas_pago_mixto_c 
    //where factura_compra.id_factura_compra=formas_pago_mixto_c.id_factura_compra and factura_compra.id_factura_compra='$cont1' and formas_pago_mixto_c.forma_pago='CHEQUE'");
    //    while ($row = pg_fetch_row($consulta_mixto)) {
    //        $cont2_mixto_transferencias_cheq = $row[0];
    //        $valor_contadotr = $row[1];
    //        $id_cuenta_banco = $row[2];
    //    }
    //    if ($cont2_mixto_transferencias_cheq != "") {
    //
    //        $sql = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
    //        $buscaCuenta = pg_fetch_row($sql);
    //        $fila1[0] = $fila1[0] + 1;
    ////                    echo '<br>GUARDAR FACTURA CHEQUE1: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$id_cuenta_banco','0.000','" . $valor_contadotr . "','Activo')"; //////////////////////////
    //
    //        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$id_cuenta_banco','0.000','" . $valor_contadotr . "','Activo')");
    //    }
    //
    //// echo '<br>GUARDAR FACTURA CREDITO: <br>' . "select formas_pago_mixto_c.forma_pago,formas_pago_mixto_c.valor from factura_compra, formas_pago_mixto_c where
    //// factura_compra.id_factura_compra=formas_pago_mixto_c.id_factura_compra and factura_compra.id_factura_compra='$cont1' and formas_pago_mixto_c.forma_pago='CREDITO'";//////////////////////////
    ////	 
    //
    //
    //    $consulta_mixto = pg_query("select formas_pago_mixto_c.forma_pago,formas_pago_mixto_c.valor from factura_compra, formas_pago_mixto_c where
    // factura_compra.id_factura_compra=formas_pago_mixto_c.id_factura_compra and factura_compra.id_factura_compra='$cont1' and formas_pago_mixto_c.forma_pago='CREDITO'");
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
    ////              echo '<br>GUARDAR FACTURA CREDITO1: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila4[0] . "','0.000','" . $valor_contadocredito . "','Activo')";//////////////////////////
    ////	
    //        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila4[0] . "','0.000','" . $valor_contadocredito . "','Activo')");
    //    }
    //        
    //        
    //        
    //        
    //        
    //        
    //        
    //        $fila1[0] = $fila1[0] + 1;
    //        $total = $_POST['tot'];
    //        $plancaja = pg_query("select cuenta_debito from parametros where descripcion='CUENTAS POR PAGAR'");
    //        $fila2 = pg_fetch_row($plancaja);
    ////        	 echo '<br>GUARDAR FACTURA VENTA44: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','0.000','" . $total . "','Activo')";//////////////////////////
    //	 
    //        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','0.000','" . $total . "','Activo')");
    //    }
    ////////////////////////////////////////////  
} else if ($_POST["id_fac"] != 0) {

    $cont1 = $_POST['id_fac'];
    // fin
    $sql = pg_query("update factura_compra set temporal=1 where id_factura_compra=$cont1");

    $campo1 = $_POST['campo1'];
    $campo2 = $_POST['campo2'];
    $campo3 = $_POST['campo3'];
    $campo4 = $_POST['campo4'];
    $campo5 = $_POST['campo5'];
    // fin
    // agregar detalle_factura_compra
    $arreglo1 = explode('|', $campo1);
    $arreglo2 = explode('|', $campo2);
    $arreglo3 = explode('|', $campo3);
    $arreglo4 = explode('|', $campo4);
    $arreglo5 = explode('|', $campo5);
    $nelem = count($arreglo1);
    $forma = $_POST['formas'];
    // fin

    $canti2 = 0;
    $canti1 = 0;
    // restar stock productos
    $consulta = pg_query("select * from detalle_factura_compra where id_factura_compra = '$cont1'");
    while ($row = pg_fetch_row($consulta)) {
        $canti1 = $row[3];
        $id = $row[2];
        //        $consulta2 = pg_query("select * from productos where cod_productos = '" . $id . "'");
        //        while ($row = pg_fetch_row($consulta2)) {
        //            $canti2 = $row[13];
        //        }
        //        $cal1 = $canti2 - $canti1;
        //        pg_query("Update productos Set stock='" . $cal1 . "' where cod_productos='" . $id . "'");
        // contador kardex
        $contb = 0;
        $consulta = pg_query("select max(id_detalle_productos_bodega) from detalle_producto_bodega");
        while ($row = pg_fetch_row($consulta)) {
            $contb = $row[0];
        }
        $contb++;

        // guardar detalle productos bodega

        $consulta_v = pg_query("select * from detalle_producto_bodega where id_bodega=$conpuntoresult and cod_productos=$arreglo1[$i]");
        while ($row = pg_fetch_row($consulta_v)) {
            $cod_pro = $row[1];
            $id_bod = $row[2];

            $stock = $row[6];
        }
        $cal = $stock + $arreglo2[$i];

        if ($cod_pro == $arreglo1[$i] && $id_bod == $conpuntoresult) {
            pg_query("Update detalle_producto_bodega Set fecha='" . $_POST[fecha_actual] . "' ,hora='" . $_POST[hora_actual] . "', stock='" . $cal . "' where cod_productos='" . $arreglo1[$i] . "' and id_bodega='" . $conpuntoresult . "' ");
        } else {
            $contb = 0;
            $consulta = pg_query("select max(id_detalle_productos_bodega) from detalle_producto_bodega");
            while ($row = pg_fetch_row($consulta)) {
                $contb = $row[0];
            }
            $contb++;
            $horap = date("g:ia");
            pg_query("insert into detalle_producto_bodega values('$contb','$arreglo1[$i]','$conpuntoresult','$_SESSION[id]','$_POST[fecha_actual]','$horap','$cal')");
        }
        $cont_k = 0;
        $consulta_k = pg_query("select max(id_kardex) from kardex");
        while ($row = pg_fetch_row($consulta_k)) {
            $cont_k = $row[0];
        }
        $cont_k++;
        // fin
        // contador kardex valorizado
        $cont_v = 0;
        $consulta_v = pg_query("select max(id_kardex) from kardex_valorizado");
        while ($row = pg_fetch_row($consulta_v)) {
            $cont_v = $row[0];
        }
        $cont_v++;
        // fin
        // consulta kardex valorizado
        $cantidad = 0;
        $precio_total = 0;
        $consulta2 = pg_query("select * from kardex_valorizado where cod_productos =" . $id . " order by id_kardex asc");
        while ($row = pg_fetch_row($consulta2)) {
            $cantidad = $row[11];
            $precio_total = $row[8];
            $cantidad_entrada = $canti1;
            $precio_unitario_entrada = number_format($row[7], 3, '.', '');
            $precio_total_entrada = number_format($row[7] * $canti1, 3, '.', '');

            $cantidad_total = $cantidad - $canti1;
            $precio_total_total = number_format($precio_total + $precio_total_entrada, 3, '.', '');
            $precio_unitario_total = number_format($precio_total_total / $cantidad_total, 3, '.', '');
        }
        //guardar kardex valorizado
        pg_query("insert into kardex_valorizado values('$cont_v','$id','$_POST[fecha_actual]', '" . 'MF Compra: ' . $_POST['serie'] . "','','" . $cantidad_entrada . "','" . $cantidad . "','" . $precio_unitario_entrada . "','" . $precio_total_entrada . "','','','" . $cantidad_total . "','2')");

        // fin
        // guardar kardex
        pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '" . 'MF F.C:' . $_POST['serie'] . "' ,'$canti1','$precio_unitario_entrada','$precio_total_entrada','$id','$cal1','1','','','$_POST[id_proveedor]','$cont1','C','$conpuntoresult')");
        // fin
    }
    // fin suma
    $nelem = count($arreglo1);
    // eliminar detalle productos
    pg_query("DELETE FROM  detalle_factura_compra where id_factura_compra = '$cont1'");
    // fin  

    for ($i = 1; $i < $nelem; $i++) {
        if (!empty($arreglo1[$i])) {
            // contador detalle factura compra
            $cont4 = 0;
            $consulta = pg_query("select max(id_detalle_compra) from detalle_factura_compra");
            while ($row = pg_fetch_row($consulta)) {
                $cont4 = $row[0];
            }
            $cont4++;
            // fin
            // contador kardex
            $cont_k = 0;
            $consulta_k = pg_query("select max(id_kardex) from kardex");
            while ($row = pg_fetch_row($consulta_k)) {
                $cont_k = $row[0];
            }
            $cont_k++;
            // fin
            // contador kardex valorizado
            $cont_v = 0;
            $consulta_v = pg_query("select max(id_kardex) from kardex_valorizado");
            while ($row = pg_fetch_row($consulta_v)) {
                $cont_v = $row[0];
            }
            $cont_v++;
            // fin

            $consulta_bien_servi = pg_query(" select bien_servicios,id_plan_cuentas from productos where cod_productos=$arreglo1[$i]");
            while ($row = pg_fetch_row($consulta_bien_servi)) {
                $valor_Servicio = $row[0];
                $val_id_plan_cuentas = $row[1];
            }

            // guardar detalle_factura_compra
            pg_query("insert into detalle_factura_compra values('$cont4','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','Activo','$valor_Servicio','$val_id_plan_cuentas')");
            // fin 
            //    // modificar productos
            //    $consulta2=pg_query("select * from productos where cod_productos = '$arreglo1[$i]'");
            //    while($row=pg_fetch_row($consulta2))
            //     {
            //      $stock=$row[13];
            //     }
            //    $cal=$stock+$arreglo2[$i];
            //    
            //    pg_query("Update productos Set precio_compra='" . $arreglo3[$i] . "', stock='" . $cal . "' where cod_productos='" . $arreglo1[$i] . "'");
            //    // fin
            $contb = 0;
            $consulta = pg_query("select max(id_detalle_productos_bodega) from detalle_producto_bodega");
            while ($row = pg_fetch_row($consulta)) {
                $contb = $row[0];
            }
            $contb++;

            // guardar detalle productos bodega

            $consulta_v = pg_query("select * from detalle_producto_bodega where id_bodega=$conpuntoresult and cod_productos=$arreglo1[$i]");
            while ($row = pg_fetch_row($consulta_v)) {
                $cod_pro = $row[1];
                $id_bod = $row[2];

                $stock = $row[6];
            }
            $cal = $stock + $arreglo2[$i];

            if ($cod_pro == $arreglo1[$i] && $id_bod == $conpuntoresult) {
                pg_query("Update detalle_producto_bodega Set fecha='" . $_POST[fecha_actual] . "' ,hora='" . $_POST[hora_actual] . "', stock='" . $cal . "' where cod_productos='" . $arreglo1[$i] . "' and id_bodega='" . $conpuntoresult . "' ");
            } else {
                $contb = 0;
                $consulta = pg_query("select max(id_detalle_productos_bodega) from detalle_producto_bodega");
                while ($row = pg_fetch_row($consulta)) {
                    $contb = $row[0];
                }
                $contb++;
                $horap = date("g:ia");
                pg_query("insert into detalle_producto_bodega values('$contb','$arreglo1[$i]','$conpuntoresult','$_SESSION[id]','$_POST[fecha_actual]','$horap','$cal')");
            }
            // consulta kardex valorizado
            $cantidad = 0;
            $precio_total = 0;
            $consulta2 = pg_query("select * from kardex_valorizado where cod_productos = '$arreglo1[$i]' order by id_kardex asc");
            while ($row = pg_fetch_row($consulta2)) {
                $cantidad = $row[11];
                $precio_total = $row[8];
            }

            $cantidad_entrada = $arreglo2[$i];
            $precio_unitario_entrada = number_format($arreglo3[$i], 3, '.', '');
            $precio_total_entrada = number_format($arreglo2[$i] * $arreglo3[$i], 3, '.', '');
            $cantidad_total = $cantidad + $arreglo2[$i];
            $precio_total_total = number_format($precio_total + $precio_total_entrada, 3, '.', '');
            $precio_unitario_total = number_format($precio_total_total / $cantidad_total, 3, '.', '');

            pg_query("insert into kardex_valorizado values('$cont_v','$arreglo1[$i]','$_POST[fecha_actual]', '" . 'FCompra: ' . $_POST['serie'] . "','" . $cantidad_entrada . "','','" . $cantidad . "','" . $precio_unitario_entrada . "','" . $precio_total_entrada . "','','','" . $cantidad_total . "','2')");
            // fin
            // guardar kardex
            pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '" . 'F F.C:' . $_POST['serie'] . "' ,'$arreglo2[$i]','$arreglo3[$i]','$arreglo5[$i]','$arreglo1[$i]','$cal','1','','$_POST[id_proveedor]','$cont1','C','$conpuntoresult')");
            // fin
        }
    }
    $data = $cont1;
    //    // guardar asiento contable
    //    $idtran = pg_query("select max(id_transacciones) from transacciones");
    //    $fila = pg_fetch_row($idtran);
    //    $fila[0] = $fila[0] + 1;
    //    $sum = 0;
    //    $bool = true;
    //    $pos = 0;
    //    $vec = 0;
    //    $auxiliar = $arreglo1;
    //    while ($bool) {
    //        $cuenta = pg_query("select id_plan_cuentas from productos where cod_productos='" . $auxiliar[1] . "'");
    //        $plan = pg_fetch_row($cuenta);
    //        $nelem = count($auxiliar);
    //        $vec = 0;
    //        for ($i = 0; $i <= $nelem; $i++) {
    //            $cuenta1 = pg_query("select id_plan_cuentas from productos where cod_productos='" . $auxiliar[$i] . "'");
    //            $plan1 = pg_fetch_row($cuenta1);
    //            if ($plan1[$i] == $plan[0]) {
    //                $sum = $arreglo5[$i] + $sum;
    //            } else {
    //                $vec[$pos] = $auxiliar[$i];
    //                $pos++;
    //            }
    //        }
    //        if ($vec == 0) {
    //            $bool = false;
    //        } else {
    //            $auxiliar = $vec;
    //            $pos = 0;
    //        }
    //    }
    //    $sum = number_format($sum, 3, '.', '');
    //    $sum = $sum + $_POST['iva'];
    //    $saldo = $sum - $_POST['tot'];
    //    $saldo = number_format($saldo, 3, '.', '');
    //    $prove = pg_query("select identificacion_pro from proveedores where id_proveedor='$_POST[id_proveedor]'");
    //    $p = pg_fetch_row($prove);
    //    $ing = pg_query("select max(num_transaccion) from transacciones where id_tipo_transaccion='1'");
    //    $res = pg_fetch_row($ing);
    ////    $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'COMPRA PRODUCTOS, PROVEEDOR: " . $p[0] . ", COMPROBANTE: " . $_POST['serie'] . "', '" . $sum . "', '$_POST[tot]', '" . $saldo . "','1','" . ($res[0] + 1) . "','Activo' )");
    //     $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'COMPRA PRODUCTOS, PROVEEDOR: " . $p[0] . ", COMPROBANTE: " . $_POST['serie'] . "" . $formaPa . "', '" . $sum . "', '$_POST[tot]', '" . $saldo . "','1','" . ($res[0] + 1) . "','Activo','$_POST[id_proveedor]','','','','','COM','',$conpuntoresult,'$_POST[fecha_emision]')");
    //
    //    $auxiliar = $arreglo1;
    //    $suma = 0;
    //    $bool = true;
    //    $aa = 0;
    //    $ab = 0;
    //    $pos = 1;
    //    $vec = "";
    //    $ant = $arreglo5;
    //    $vec1 = "";
    //    while ($bool) {
    //        $cont1 = 0;
    //        $cont2 = 0;
    //        $aa = 0;
    //        $ab = 0;
    //        $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
    //        $fila1 = pg_fetch_row($iddettran);
    //        $fila1[0] = $fila1[0] + 1;
    //        $cuenta = pg_query("select id_plan_cuentas from productos where cod_productos='" . $auxiliar[1] . "'");
    //        while ($plan = pg_fetch_row($cuenta)) {
    //            $cont2 = $plan[0];
    //        }
    //        if ($cont2 == 0) {
    //            $cuenta = pg_query("select id_plan_cuentas from productos where cod_productos='" . $auxiliar[0] . "'");
    //            while ($plan = pg_fetch_row($cuenta)) {
    //                $cont2 = $plan[0];
    //            }
    //        }
    //        $nelem1 = count($auxiliar);
    //        $suma = 0;
    //        for ($i = 0; $i <= $nelem1; $i++) {
    //            if (!empty($auxiliar[$i])) {
    //                $cuenta1 = pg_query("select id_plan_cuentas from productos where cod_productos='" . $auxiliar[$i] . "'");
    //                while ($plan1 = pg_fetch_row($cuenta1)) {
    //                    $cont1 = $plan1[0];
    //                }
    //                if ($cont1 != 0) {
    //                    if ($cont1 == $cont2) {
    //                        $aa++;
    //                        $suma = $ant[$i] + $suma;
    //                        //$fila1[0]++;
    //                        //pg_query("insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','4','".$cont2."-".$aa."','".$nelem1."-i".$i."-".$cont1."','prueba')");
    //                    } else {
    //                        $ab++;
    //                        $vec[$pos] = $auxiliar[$i];
    //                        $vec1[$pos] = $ant[$i];
    //                        $pos++;
    //                        //$fila1[0]++;
    //                        //pg_query("insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','4','".$cont2."-".$ab."','".$nelem1."-i".$i."-".$cont1."','prueba2')");
    //                    }
    //                }
    //                //$ant=$ant.$cont1."-";
    //            }
    //        }
    //        // $fila1[0]++;
    //        $suma = number_format($suma, 3, '.', '');
    //        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $cont2 . "','$suma','0.000','Activo')");
    //        if ($vec == "") {
    //            //$ab++;
    //            $bool = false;
    //        } else {
    //            //$fila1[0]++;
    //            //pg_query("insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','4','".$pos."','".$vec[$pos-2]."','otra vez')");
    //            $auxiliar = $vec;
    //            $ant = $vec1;
    //            $vec = "";
    //            $vec1 = "";
    //            $pos = 0;
    //        }
    //    }
    //    $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
    //    $fila1 = pg_fetch_row($iddettran);
    //
    //
    //    $planiva = pg_query("select cuenta_debito from parametros where descripcion='IVA'");
    //    $fila2 = pg_fetch_row($planiva);
    //    if ($_POST[iva] != '0.000') {
    //        $fila1[0] = $fila1[0] + 1;
    //        pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','$_POST[iva]','0.000','Activo')");
    //    }
    //    $fila1[0] = $fila1[0] + 1;
    //    $plancaja = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
    //    $fila2 = pg_fetch_row($plancaja);
    //    pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','0.000','$_POST[tot]','Activo')");
}


//DEVOLVER VALORES///

echo $data;

function obtenerIdfc() {
    $consulta = pg_query("select max(id_factura_compra) from factura_compra");
    $id = (pg_fetch_row($consulta)[0] + 1);
    return $id;
}

function guardarFacturaCompra($id, $bodega, $proveedor, $usuario, $comprobante, $fechaActual, $horaActual, $fechaRegistro, $fechaEmision, $fechaCaducidad, $tipoComprobante, $numSerie, $numAutoriz, $fechaCancela, $formaPago, $tarifa0, $tarifa12, $ivaCompra, $descuento, $total, $estado, $observacion, $pagoATS, $temporal) {
    $sql = "INSERT INTO factura_compra(id_factura_compra, id_empresa, id_proveedor, id_usuario, comprobante, fecha_actual, hora_actual, fecha_registro, "
        . "fecha_emision, fecha_caducidad, tipo_comprobante, num_serie, num_autorizacion, fecha_cancelacion, forma_pago, tarifa0, tarifa12, iva_compra, descuento_compra, "
        . "total_compra, estado, observaciones, pago_ats, temporal) "
        . "VALUES ($id,$bodega, $proveedor, $usuario, '$comprobante', '$fechaActual', '$horaActual', '$fechaRegistro', '$fechaEmision', '$fechaCaducidad', '$tipoComprobante', "
        . "'$numSerie', '$numAutoriz', '$fechaCancela', '$formaPago', " . number_format($tarifa0, 4, '.', '') . ", " . number_format($tarifa12, 4, '.', '') . ", "
        . "" . number_format($ivaCompra, 3, '.', '') . ", " . number_format($descuento, 3, '.', '') . ", " . number_format($total, 4, '.', '') . ", '$estado', '$observacion', '$pagoATS', $temporal);";
    $res = pg_query($sql);
    // Auditoria
    insert_registro('CREACION ' . $tipoComprobante . ' COMPRA: ' . $comprobante . ', DEL PROVEEDOR CON ID: ' . $proveedor . ', CON FORMA DE PAGO: ' . $formaPago . ' Y TOTAL DE: ' . $total);
    return $res;
}

function obtenerIdDetalle() {
    $consulta = pg_query("select max(id_detalle_compra) from detalle_factura_compra");
    $id = (pg_fetch_row($consulta)[0] + 1);
    return $id;
}

function guardarDetallaFacturaCompra($factura, $producto, $cantidad, $precioCompra, $descuento, $total, $estado, $bienServicio, $cantidadunidad, $unidadmedida, $idcentroc, $val_id_plan_cuentas) {
    $id = obtenerIdDetalle();
    
   /*  echo ''."INSERT INTO detalle_factura_compra(id_detalle_compra, id_factura_compra, cod_productos, cantidad, precio_compra, descuento_producto, total_compra, estado, bien_servicio, cantidad_unidad,unidad_medida,id_cuenta) "
            . "VALUES (" . $id . ", $factura, $producto, " . number_format($cantidad, 3, '.', '') . ", " . number_format($precioCompra, 4, '.', '') . ", "
            . "" . number_format($descuento, 4, '.', '') . ", " . number_format($total, 4, '.', '') . ", '$estado', '$bienServicio','$cantidadunidad','$unidadmedida','$val_id_plan_cuentas')"; */
    $sql = "INSERT INTO detalle_factura_compra(id_detalle_compra, id_factura_compra, cod_productos, cantidad, precio_compra, descuento_producto, total_compra, estado, bien_servicio, cantidad_unidad,unidad_medida,id_cuenta) "
            . "VALUES (" . $id . ", $factura, $producto, " . number_format($cantidad, 3, '.', '') . ", " . number_format($precioCompra, 4, '.', '') . ", "
            . "" . number_format($descuento, 4, '.', '') . ", " . number_format($total, 4, '.', '') . ", '$estado', '$bienServicio','$cantidadunidad','$unidadmedida','$val_id_plan_cuentas')";
    $res = pg_query($sql);
    if (!empty($res) && !empty($idcentroc)) {
        guardarDetalleCentroCosto($id, $idcentroc, "detalle_factura_compra");
    }
    // Auditoria
    if ($cantidad >= 1) {
        insert_registro('CREACION DETALLE DE LA COMPRA CON ID: ' . $factura . ' Y ' . $cantidad . ' PRODUCTO/S CON ID: ' . $producto . ', CON PRECIO DE: ' . $total);
    }
}
