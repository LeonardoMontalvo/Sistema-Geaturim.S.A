<?php

session_start();
include '../../procesos/base.php';
require_once '../../procesos/detalleProductosBodega.php';
require_once '../../procesos/kardexValorizado.php';
include '../../procesos/funciones.php';
conectarse();
//error_reporting(0);
$costoVenta1 = 0;
$forma = $_POST['formaspago'];
// contador clientes
$conexion = conectarse();
$conpuntoresult = $_SESSION['PV'];
$guardarnv = !!pg_query("Update facturas_novalidas Set id_cliente = '$_POST[id_cliente]', id_usuario = '$_SESSION[id]', comprobante = '$_POST[num_factura]', fecha_actual = '$_POST[fecha_actual]', hora_actual = '$_POST[hora_actual]', tipo_precio = '$_POST[tipo_precio]', tarifa0 = '$_POST[tarifa0]', tarifa12 = '$_POST[tarifa12]', iva_venta = '$_POST[iva]', descuento_venta = '$_POST[desc]', total_venta = '$_POST[tot]', forma_pago='$forma' where id_facturas_novalidas = '$_POST[id_fac]'");
// fin
//    }
$cont1 = $_POST['id_fac'];
$cliente1 = $_POST['id_cliente'];

function error_log_fv($errno, $errstr, $errfile, $errline) {
    $ddf = fopen('../../error.log', 'a');
    $errfile = explode('/', $errfile);
    $errfile = $errfile[count($errfile) - 1];
    fwrite($ddf, "[" . date("r") . "] Error $errno-$errfile-$errline: $errstr\r\n");
    fclose($ddf);
}

if ($forma == "otros") {

//        $datos_pagos_venta = 0;
//                $consulta = pg_query("select * from pagos_venta where id_factura_venta='$cont1'");
//                while ($row = pg_fetch_row($consulta)) {
//                    $datos_pagos_venta = $row[0];
//                }
//            if($datos_pagos_venta!=0){                           
//                                
//                    pg_query("Update formas_pago_mixto Set estado='Pasivo' where id_factura_venta = '$_POST[comprobante]' and tipo_documento='NOTA'");
//                        
//                    pg_query("Update pagos_venta Set estado='Pasivo' where id_factura_venta = '$_POST[comprobante]' and tipo_documento='Nota'");
//               
//                
//            }    
//    
    //////FACTURA
    /*  echo '::'."select sum(x.sum) from (select formas_pago_mixto.forma_pago,sum(formas_pago_mixto.valor) from factura_venta, formas_pago_mixto 
      where factura_venta.id_factura_venta=formas_pago_mixto.id_factura_venta and factura_venta.id_factura_venta='$cont1'
      and (formas_pago_mixto.forma_pago='CREDITO' or formas_pago_mixto.forma_pago='CPOSFECHADO')  and formas_pago_mixto.tipo_documento='FACTURA' GROUP BY formas_pago_mixto.forma_pago
      )x"; */
    $consulta_mixto = pg_query("select sum(x.sum) from (select formas_pago_mixto.forma_pago,sum(formas_pago_mixto.valor) from facturas_novalidas, formas_pago_mixto 
                where facturas_novalidas.id_facturas_novalidas=formas_pago_mixto.id_factura_venta  and facturas_novalidas.id_facturas_novalidas='$cont1' and formas_pago_mixto.estado='Activo' 
                and (formas_pago_mixto.forma_pago='CREDITO' or formas_pago_mixto.forma_pago='CPOSFECHADO')  and formas_pago_mixto.tipo_documento='NOTA' GROUP BY formas_pago_mixto.forma_pago
                )x");
    $valor_contado = "";
    while ($row = pg_fetch_row($consulta_mixto)) {
        //                    $cont2_mixto_contado = $row[0];
        $valor_contado = $row[0];
    }
    if ($valor_contado != "") {
        // variables pagos
        $adelanto = '0.00';
        $meses = $_POST['meses'];
        //                        $total = $valor_contado;
    }
    // fin
    // contador pagos venta
    $cont2 = 0;
    $consulta = pg_query("select max(id_pagos_venta) from pagos_venta");
    while ($row = pg_fetch_row($consulta)) {
        $cont2 = $row[0];
    }
    $cont2++;
    // fin
    // guardar pagos venta
    //                if ($adelanto == "") {
    //                   
    //                    $adelanto = 0.00;
    //                } else {
    //                    $monto = $total - $adelanto;
    //                    $format = number_format($monto, 2, '.', '');
    //                }
    //                $monto = $total;
    //                $format = number_format($monto, 2, '.', '');

    $cliente1 = $_POST['id_cliente'];
    pg_query("Update clientes Set  nombres_cli='" . strtoupper($_POST['nombre_cliente']) . "',direccion_cli='" . strtoupper($_POST['direccion_cliente']) . "', telefono='$_POST[telefono_cliente]', correo='$_POST[correo]' where id_cliente='$cliente1'");

    //                        echo '<br>GUARDAR NOTA VENTA1tt NV: <br>' . "insert into pagos_venta values('$cont2','$_POST[id_cliente]','$cont1','$_SESSION[id]','$_POST[fecha_actual]','0.00','1','Nota','$format','$format','Activo','$_POST[fecha_dias]','$conpuntoresult')"; //////////////////////////

    $sql = "insert into pagos_venta values('$cont2','$cliente1','$cont1','$_SESSION[id]','$_POST[fecha_actual]','0.00','1','Nota','$valor_contado','$valor_contado','Activo','$_POST[fecha_dias]','$conpuntoresult')";
//                      echo ''."insert into pagos_venta values('$cont2','$cliente1','$cont1','$_SESSION[id]','$_POST[fecha_actual]','0.00','1','Nota','$valor_contado','$valor_contado','Activo','$_POST[fecha_dias]','$conpuntoresult')";


    $guardar_nv = guardarSql($conexion, $sql);
    if ($guardar_nv == 'true') {
        //                            $data = 22;
    } else {
        error_log_fv(0, "id_factura_novalida=$cont2", "guardar_factura_venta.php", 343);
        error_log_fv(0, pg_last_error($conexion), "guardar_factura_venta.php", 360);
        error_log_fv(0, pg_last_error($guardar_nv), "guardar_factura_venta.php", 360);
    }

    // guardar meses
    if ($meses > 1) {
        //                    for ($i = 1; $i <= $meses - 1; $i++) {
        //                        // contador detalle pagos venta
        //                        $cont3 = 0;
        //                        $consulta8 = pg_query("select max(id_detalle_pagos_venta) from detalle_pagos_venta");
        //                        while ($row = pg_fetch_row($consulta8)) {
        //                            $cont3 = $row[0];
        //                        }
        //                        $cont3++;
        //                        // fin
        //                        $calcu = $monto / ($meses);
        //                        $nuevaFecha = date('Y-m-d', strtotime(" + $i month"));
        //                        $format_numero = number_format(floor($calcu), 4, '.', '');
        //                          echo '<br>GUARDAR FACTURA VENTA4F: <br>' . "insert into detalle_pagos_venta values('$cont3','$cont2','$nuevaFecha','$format_numero','$format_numero','Activo')";
        //                        pg_query("insert into detalle_pagos_venta values('$cont3','$cont2','$nuevaFecha','$format_numero','$format_numero','Activo')");
        //                    }
        //
                    //                    $cont3++;
        //                    $calcu1 = floor($calcu) * ($meses - 1);
        //                    $ultimaFecha = date('Y-m-d', strtotime(" + $i month"));
        //                    $sal = $monto - $calcu1;
        //                    $format_numero2 = number_format($sal, 4, '.', '');
        //                      echo '<br>GUARDAR FACTURA VENTA5G: <br>' . "insert into detalle_pagos_venta values('$cont3','$cont2','$ultimaFecha','$format_numero2','$format_numero2','Activo')";
        //                    pg_query("insert into detalle_pagos_venta values('$cont3','$cont2','$ultimaFecha','$format_numero2','$format_numero2','Activo')");
    } else {
//                            $cont3 = 0;
//                        $consulta8 = pg_query("select max(id_detalle_pagos_venta) from detalle_pagos_venta");
//                        while ($row = pg_fetch_row($consulta8)) {
//                            $cont3 = $row[0];
//                        }
//                        $cont3++;
//
//                        $k = 1;
//                        $format2 = number_format($monto, 2, '.', '');
//                        $Fecha = date('Y-m-d', strtotime(" + $k month"));
//                        if ($guardarnv) {
//                            //                            echo '<br>GUARDAR NOTA VENTArrggffff: <br>' . "insert into detalle_pagos_venta values('$cont3','$cont2','$Fecha','$format2','$format2','Activo')"; //////////////////////////
//                            //TODO pagos_venta
//                            pg_query("insert into detalle_pagos_venta values('$cont3','$cont2','$Fecha','$format2','$format2','Activo')");
//                        } else {
//                            $data = 60; /// error al guardar
//                            $item = array('estado' => $data);
//                        }
    }
} else {


    pg_query("Update pagos_venta Set estado = 'Pasivo' where id_factura_venta = '$_POST[comprobante]' and tipo_documento='Nota'");
}
// fin
// eliminar detalle productos
// fin  
// datos detalle factura
$campo1 = $_POST['campo1'];
$campo2 = $_POST['campo2'];
$campo3 = $_POST['campo3'];
$campo4 = $_POST['campo4'];
$campo5 = $_POST['campo5'];
$campo6 = $_POST['campo6'];
$campo8 = $_POST['campo8'];
$campo9 = $_POST['campo9'];
$campo10 = $_POST['campo10'];

// agregar detalle_facturas_novalidas
$arreglo1 = explode('|', $campo1);
$arreglo2 = explode('|', $campo2);
$arreglo3 = explode('|', $campo3);
$arreglo4 = explode('|', $campo4);
$arreglo5 = explode('|', $campo5);
$arreglo6 = explode('|', $campo6);
$arreglo8 = explode('|', $campo8);
$arreglo9 = explode('|', $campo9);
$arreglo10 = explode('|', $campo10);
$nelem = count($arreglo1);
$forma = $_POST['formaspago'];
/// fin
// guardar detalle venta
//for ($i = 1; $i < $nelem; $i++) {



$stok_ant_cantidad = 0;
$stok_ant_um = 0;
$cod_prod_ant = 0;
$precio_vventa = 0;
$consulta_prod = pg_query("SELECT cantidad, cantidad_unidad,cod_productos,precio_venta FROM detalle_facturas_novalidas where  id_facturas_novalidas=$cont1 and  estado='Activo'");
while ($row = pg_fetch_row($consulta_prod)) {
    $stok_ant_cantidad = $row[0];
    $stok_ant_um = $row[1];
    $cant = $stok_ant_um;
    $cantidad = $stok_ant_cantidad;
    $cod_prod_ant = $row[2];
    $precio_vventa =$row[3];
 
    if ($cant != 0) {
        $cantidad = $cant;
    } else {
        $cantidad = $cantidad;
    }
    $documento = 'Devolución N.V: ' . $_POST['comprobante'];
    $stock = obtenerStock($cod_prod_ant, $conpuntoresult);
    $total = number_format(($cantidad * $precio_vventa), 4, '.', '');
//        updateKardex($_POST['comprobante'], $conpuntoresult, $item['cod_productos'], 'Inactivo', 'NV', NULL, NULL);
//        updateKardexValorizado($_POST['comprobante'], $conpuntoresult, $item['cod_productos'], 'Inactivo', 'NV');
    $costoPromedio = obtenerCostoPromedioUnitarioAnular($cod_prod_ant, $conpuntoresult, $_POST['comprobante'], 'DNV');
    procesarKardexEntrada($cod_prod_ant, $documento, $cantidad, $stock, $costoPromedio, 'Activo', $conpuntoresult, 'DNV', $_POST['comprobante'], $total, NULL, NULL, '', NULL, NULL, $cliente1, $_SESSION['id']);
}





pg_query("update detalle_facturas_novalidas set estado='Pasivo' where id_facturas_novalidas = '$_POST[id_fac]'");



for ($i = 1; $i < $nelem; $i++) {


    $cont6 = 0;
    $consulta = pg_query("select  max(id_detalle_facturas_novalidas) from detalle_facturas_novalidas");
    while ($row = pg_fetch_row($consulta)) {
        $cont6 = $row[0];
    }
    $cont6++;
    // fin  

    $consulta_bien_servi = pg_query(" select bien_servicios from productos where cod_productos=$arreglo1[$i]");
    while ($row = pg_fetch_row($consulta_bien_servi)) {
        $valor_Servicio = $row[0];
    }

    if ($guardarnv) {
        //echo '<br>GUARDAR NOTA VENTArrggfffbbbf1: <br>' . "insert into detalle_facturas_novalidas values('$cont6','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','Activo','$arreglo6[$i]','$valor_Servicio','$arreglo8[$i]','$arreglo9[$i]')"; //////////////////////////

        $sql = "insert into detalle_facturas_novalidas 
                                    (
                                        id_detalle_facturas_novalidas, id_facturas_novalidas, cod_productos, 
                                        cantidad, precio_venta, descuento_producto, total_venta, estado, 
                                        pendientes, bien_servicio, cantidad_unidad, unidad_medida, detalle_producto)
                                    values('$cont6','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','Activo','$arreglo6[$i]','$valor_Servicio','$arreglo8[$i]','$arreglo9[$i]','$arreglo10[$i]')";
        $guardar = guardarSql($conexion, $sql);

        if ($guardar == 'true') {
            $data = 22;
        } else {
            error_log_fv(0, "id_factura=$cont1", "facturas_novalidas.php", 343);
            error_log_fv(0, pg_last_error($conexion), "facturas_novalidas.php", 360);
            error_log_fv(0, pg_last_error($guardar), "facturas_novalidas.php", 360);
            error_log_fv(0, pg_last_error($sql), "facturas_novalidas.php", 360);
            //                                    echo '<br>GUARDAR FACTURA DETALLE: <br>' . "insert into detalle_facturas_novalidas values('$cont6','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]',"
            //                                    . "'$arreglo4[$i]','$arreglo5[$i]','Activo','$arreglo6[$i]','$_POST[fecha_actual]','$valor_Servicio')"; //////////////////////////

            pg_query("DELETE FROM facturas_novalidas WHERE id_facturas_novalidas='$cont1';");

            //
            //                                    echo '<br>GUARDAR FACTURA delete: <br>' . "DELETE FROM facturas_novalidas WHERE id_facturas_novalidas='$cont1';";

            $data = 60; /// error al guardar
            $item = array('estado' => $data);
        } // fin
    } else {
        $data = 60; /// error al guardar
        $item = array('estado' => $data);
    }




//
//    $contb = 0;
//    $consulta = pg_query("select max(id_detalle_productos_bodega) from detalle_producto_bodega");
//    while ($row = pg_fetch_row($consulta)) {
//        $contb = $row[0];
//    }
//    $contb++;
    // guardar detalle productos bodega
//    $consulta_v = pg_query("select * from detalle_producto_bodega where id_bodega=$conpuntoresult and cod_productos=$arreglo1[$i]");
//    while ($row = pg_fetch_row($consulta_v)) {
//        $cod_pro = $row[1];
//        $id_bod = $row[2];
//        $stock = $row[6];
//    }
//    $cal = $stock - $arreglo2[$i];
//    if ($cod_pro == $arreglo1[$i] && $id_bod == $conpuntoresult) {
//        /* DESBLOQUEAR pg_query("Update detalle_producto_bodega Set fecha='" . $_POST[fecha_actual] . "' ,hora='" . $_POST[hora_actual] . "', stock='" . $cal . "' "
//          . "where cod_productos='" . $arreglo1[$i] . "' and id_bodega='" . $conpuntoresult . "' "); */
//    } else {
//        $contb = 0;
//        $consulta = pg_query("select max(id_detalle_productos_bodega) from detalle_producto_bodega");
//        while ($row = pg_fetch_row($consulta)) {
//            $contb = $row[0];
//        }
//        $contb++;
//        $horap = date("g:ia");
//        /* DESBLOQUEAR pg_query("insert into detalle_producto_bodega values('$contb','$arreglo1[$i]','$conpuntoresult','$_SESSION[id]',"
//          . "'$_POST[fecha_actual]','$horap','$cal')"); */
//    }
    // contador kardex valorizado
    // contador kardex
    $cont_k = 0;
    $consulta_k = pg_query("select max(id_kardex) from kardex");
    while ($row = pg_fetch_row($consulta_k)) {
        $cont_k = $row[0];
    }
    $cont_k++;
    // fin
    $cont_v = 0;
    $consulta_v = pg_query("select max(id_kardex) from kardex_valorizado");
    while ($row = pg_fetch_row($consulta_v)) {
        $cont_v = $row[0];
    }
    $cont_v++;
    // fin
    $cantidad = 0;
    $precio_total = 0;
    $precio_unitario = 0;
    $consulta2 = pg_query("select * from kardex_valorizado where cod_productos = '$arreglo1[$i]' order by id_kardex desc limit 1");
    while ($row = pg_fetch_row($consulta2)) {
        $cantidad = $row[11];
        $precio_unitario = $row[7];
        $precio_total = $row[8];
    }

    $cantidad_salida = $arreglo2[$i];
    $precio_unitario_salida = number_format($precio_unitario, 4, '.', '');
    $precio_total_salida = number_format($arreglo2[$i] * $precio_unitario, 2, '.', '');

    $cantidad_total = $cantidad - $arreglo2[$i];
    $precio_total_total = number_format($precio_total - $precio_total_salida, 2, '.', '');
    $precio_unitario_total = number_format($precio_total_total / $cantidad_total, 4, '.', '');

    //pg_query("insert into kardex_valorizado values(" . $cont_v . ",'" . $arreglo1[$i] . "','$_POST[fecha_actual]', '" . 'Nota Venta: ' . $_POST['num_factura'] . "','','" . $cantidad_salida . "','" . $cantidad . "','" . $precio_unitario_salida . "','" . $precio_total_salida . "','','','" . $cantidad_total . "','4')");
    // fin
    //////// COSTO DE VENTA OTROS NOTAS DE VENTA//////////////////////////                        
    // consulta kardex valorizado
    $cantidad = 0;
    $precio_total = 0;
    $precio_unitario = 0;
    $costoVenta = 0;
    $consulta2 = pg_query("select * from kardex_valorizado where cod_productos = '$arreglo1[$i]' order by id_kardex desc limit 1");
    while ($row = pg_fetch_row($consulta2)) {
        $cantidad = $row[11];
        $precio_unitario = $row[7]; //round($row[7], 4);
        $precio_total = $row[8]; //round($row[8], 4);
        $costoVenta = $row[13]; //round($row[13], 4);
    }
    if ($costoVenta == "0.0000") {
        $costoVenta1 = $costoVenta1 + ($arreglo2[$i]);
    } else {
        $costoVenta1 = $costoVenta1 + ($costoVenta * $arreglo2[$i]);
    }
    //////////////////////////////////////////////////////////////////////
//        pg_query("Update clientes Set nombres_cli='" . strtoupper($_POST['nombre_cliente']) . "', direccion_cli='" . strtoupper($_POST['direccion_cliente']) . "', telefono='$_POST[telefono_cliente]', correo='$_POST[correo]' where id_cliente='$cliente1'");
    //pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '" . 'N.V:' . $_POST['num_factura'] . "' ,'$arreglo2[$i]','$arreglo3[$i]','$arreglo5[$i]','$arreglo1[$i]','$cal','2','','','$cliente1','$cont1','NV','$conpuntoresult')");

    /* DESBLOQUEAR CODIGO ESTEBAN procesarKardexSalida($arreglo1[$i], 'N.V:' . $_POST['num_factura'], $arreglo2[$i], obtenerStock($arreglo1[$i], $_SESSION['PV']), 
      obtenerValoresPromedios($arreglo1[$i])[0]['venta_promedio'], 'Activo', $conpuntoresult, 'V', $cont1, $arreglo5[$i], NULL, NULL,
      $_POST['id_cliente'], '', NULL, NULL, $_SESSION['id']); */
    if ($data == 22) {
        if ($arreglo8[$i] != 0) {
            $arreglo2[$i] = $arreglo8[$i];
        } else {
            $arreglo2[$i] = $arreglo2[$i];
        }
        procesarKardexSalida($arreglo1[$i], 'N.V:' . $_POST['num_factura'], $arreglo2[$i], obtenerStock($arreglo1[$i], $_SESSION['PV']), NULL, 'Activo', $conpuntoresult, 'V', $cont1, $arreglo5[$i], NULL, NULL, $_POST['id_cliente'], '', NULL, NULL, $_SESSION['id']);
    }
}

function obtenerDetallaNota($idFactura, $bodega) {
    $sql = "SELECT * FROM detalle_facturas_novalidas DFV INNER JOIN facturas_novalidas FV ON FV.id_facturas_novalidas = DFV.id_facturas_novalidas 
            WHERE FV.id_facturas_novalidas=$idFactura AND FV.id_empresa=$bodega";
    return pg_fetch_all(pg_query($sql));
}

$data = $_POST['id_fac'];

echo $data;
?>
