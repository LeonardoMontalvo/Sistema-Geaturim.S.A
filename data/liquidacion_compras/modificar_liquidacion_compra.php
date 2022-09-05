<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

    $forma=$_POST['formas'];
    // contador clientes
    $contt = 0;
    $consulta_cli = pg_query("select max(id_cliente) from clientes");
    while ($row = pg_fetch_row($consulta_cli)) {
        $contt = $row[0];
    }
    $contt++;
    // fin

    if ($_POST['id_cliente'] == "") {

        $tipo = $_POST['ruc_ci'];
        if (strlen($tipo) == 10) {
            // modificar clientes
            pg_query("insert into clientes values('$contt','Cedula','$_POST[ruc_ci]','$_POST[nombre_cliente]','natural','$_POST[direccion_cliente]','$_POST[telefono_cliente]','','','','$_POST[correo]','','','Activo','1')");
            // fin
            
            // modificar factura venta
            pg_query("Update liquidacion_compra Set id_cliente = '$contt', id_usuario = '$_SESSION[id]', num_factura = '$_POST[num_factura]', fecha_actual = '$_POST[fecha_actual]', hora_actual = '$_POST[hora_actual]', fecha_cancelacion = '$_POST[cancelacion]', tipo_precio = '$_POST[tipo_precio]', num_autorizacion = '$_POST[autorizacion]', fecha_autorizacion = '$_POST[fecha_auto]', fecha_caducidad = '$_POST[fecha_caducidad]', tarifa0 = '$_POST[tarifa0]', tarifa12 = '$_POST[tarifa12]', iva_venta = '$_POST[iva]', descuento_venta = '$_POST[desc]', total_venta = '$_POST[tot]' where id_liquidacion_compra = '$_POST[id_liquidacion_compra]'");
            // fin
        } else {
            if (strlen($tipo) == 13) {
                // modificar clientes    
                pg_query("insert into clientes values('$contt','Ruc','$_POST[ruc_ci]','$_POST[nombre_cliente]','natural','$_POST[direccion_cliente]','$_POST[telefono_cliente]','','','','$_POST[correo]','','','Activo','1')");
                // fin
                
                // modificar factura venta
                pg_query("Update liquidacion_compra Set id_cliente = '$contt', id_usuario = '$_SESSION[id]', num_factura = '$_POST[num_factura]', fecha_actual = '$_POST[fecha_actual]', hora_actual = '$_POST[hora_actual]', fecha_cancelacion = '$_POST[cancelacion]', tipo_precio = '$_POST[tipo_precio]', num_autorizacion = '$_POST[autorizacion]', fecha_autorizacion = '$_POST[fecha_auto]', fecha_caducidad = '$_POST[fecha_caducidad]', tarifa0 = '$_POST[tarifa0]', tarifa12 = '$_POST[tarifa12]', iva_venta = '$_POST[iva]', descuento_venta = '$_POST[desc]', total_venta = '$_POST[tot]' where id_liquidacion_compra = '$_POST[id_liquidacion_compra]'");
                // fin    
            }
        }
    } else {
        // modificar factura venta
        pg_query("Update liquidacion_compra Set id_cliente = '$_POST[id_cliente]', id_usuario = '$_SESSION[id]', num_factura = '$_POST[num_factura]', fecha_actual = '$_POST[fecha_actual]', hora_actual = '$_POST[hora_actual]', fecha_cancelacion = '$_POST[cancelacion]', tipo_precio = '$_POST[tipo_precio]', num_autorizacion = '$_POST[autorizacion]', fecha_autorizacion = '$_POST[fecha_auto]', fecha_caducidad = '$_POST[fecha_caducidad]', tarifa0 = '$_POST[tarifa0]', tarifa12 = '$_POST[tarifa12]', iva_venta = '$_POST[iva]', descuento_venta = '$_POST[desc]', total_venta = '$_POST[tot]' where id_liquidacion_compra = '$_POST[id_liquidacion_compra]'");
        // fin
    }
    $cont1=$_POST['id_liquidacion_compra'];

    if ($_POST['id_cliente'] != "") {
        $contt=$_POST['id_cliente'];
    }

    if ($forma == "Credito") {
        // variables pagos
        $adelanto = $_POST['adelanto'];
        $meses = $_POST['meses'];
        $total = $_POST['tot'];
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
        if ($adelanto == "") {
            $monto = $total;
            $format = number_format($monto, 2, '.', '');
            $adelanto = 0.00;
        } else {
            $monto = $total - $adelanto;
            $format = number_format($monto, 2, '.', '');
        }

        pg_query("insert into pagos_venta values('$cont2','$contt','$cont1','$_SESSION[id]','$_POST[fecha_actual]','$adelanto','$meses','Factura','$format','$format','Activo')");
           
        // fin
        pg_query("update liquidacion_compra Set forma_pago='Credito' where id_liquidacion_compra='".$cont1."'"); 
        // guardar meses
        if ($meses > 1) {
            for ($i = 1; $i <= $meses - 1; $i++) {
                // contador detalle pagos venta
                $cont3 = 0;
                $consulta8 = pg_query("select max(id_detalle_pagos_venta) from detalle_pagos_venta");
                while ($row = pg_fetch_row($consulta8)) {
                    $cont3 = $row[0];
                }
                $cont3++;
                // fin

                $calcu = $monto / ($meses);
                $nuevaFecha = date('Y-m-d', strtotime(" + $i month"));
                $format_numero = number_format(floor($calcu), 2, '.', '');
                pg_query("insert into detalle_pagos_venta values('$cont3','$cont2','$nuevaFecha','$format_numero','$format_numero','Activo')");
            }

            $cont3++;
            $calcu1 = floor($calcu) * ($meses - 1);
            $ultimaFecha = date('Y-m-d', strtotime(" + $i month"));
            $sal = $monto - $calcu1;
            $format_numero2 = number_format($sal, 2, '.', '');
                pg_query("insert into detalle_pagos_venta values('$cont3','$cont2','$ultimaFecha','$format_numero2','$format_numero2','Activo')");
        } else {
            $cont3 = 0;
            $consulta8 = pg_query("select max(id_detalle_pagos_venta) from detalle_pagos_venta");
            while ($row = pg_fetch_row($consulta8)) {
                $cont3 = $row[0];
            }
            $cont3++;

            $k = 1;
            $format2 = number_format($monto, 2, '.', '');
            $Fecha = date('Y-m-d', strtotime(" + $k month"));
            pg_query("insert into detalle_pagos_venta values('$cont3','$cont2','$Fecha','$format2','$format2','Activo')");
        }
        // fin
    }else{
        pg_query("update liquidacion_compra Set forma_pago='$forma' where id_liquidacion_compra='".$cont1."'");
    }
    // sumar stock productos
    $consulta = pg_query("select * from detalle_liquidacion_compra where id_liquidacion_compra = '$_POST[id_liquidacion_compra]'");
    
    while ($row = pg_fetch_row($consulta)) {
        $canti1 = $row[3];
        $id = $row[2];
        $consulta2 = pg_query("select * from productos where cod_productos = '" .$id. "'"); 
        while ($row = pg_fetch_row($consulta2)) {
            $canti2 = $row[13];
        }
           
        $cal1 = $canti2 + $canti1;
        pg_query("Update productos Set stock='" .$cal1. "' where cod_productos='" .$id. "'");

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
                    
        // consulta kardex valorizado
        $cantidad=0;
        $precio_total=0;
        $precio_unitario=0;
        $consulta2 = pg_query("select * from kardex_valorizado where cod_productos = '$id' order by id_kardex asc");
        while ($row = pg_fetch_row($consulta2)) {
            $cantidad = $row[11];
            $precio_unitario = $row[7];
            $precio_total = $row[8];
        

            $cantidad_salida = $canti1;
            $precio_unitario_salida = number_format($precio_unitario, 4, '.', '');
            $precio_total_salida = number_format($arreglo2[$i] * $precio_unitario, 2, '.', '');

            $cantidad_total = $cantidad + $canti1;
            $precio_total_total = number_format($precio_total - $precio_total_salida, 2, '.', '');
            $precio_unitario_total = number_format($precio_total_total / $cantidad_total, 4, '.', '');
        }

        pg_query("insert into kardex_valorizado values(".$cont_v.",'".$id."','$_POST[fecha_actual]', '" . 'Mod Venta: '. $_POST['num_factura']."','".$cantidad_salida."','','".$cantidad."','".$precio_unitario_salida."','".$precio_total_salida."','','','".$cantidad_total."','4')");
        // fin
                   
        // guardar kardex
        pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '" . 'Mod F.V:' . $_POST['num_factura'] . "' ,'$canti1','$precio_unitario_salida','$precio_total_salida','$id','$cal1','2','','')");
        // fin
    }
    // fin suma
    
    // eliminar detalle productos
    pg_query("DELETE FROM  detalle_liquidacion_compra where id_liquidacion_compra = '$_POST[id_liquidacion_compra]'");
    // fin  

    // datos detalle factura
    $campo1 = $_POST['campo1'];
    $campo2 = $_POST['campo2'];
    $campo3 = $_POST['campo3'];
    $campo4 = $_POST['campo4'];
    $campo5 = $_POST['campo5'];
    $campo6 = $_POST['campo6'];
    // fin 

    // agregar detalle_liquidacion_compra
    $arreglo1 = explode('|', $campo1);
    $arreglo2 = explode('|', $campo2);
    $arreglo3 = explode('|', $campo3);
    $arreglo4 = explode('|', $campo4);
    $arreglo5 = explode('|', $campo5);
    $arreglo6 = explode('|', $campo6);
    $nelem = count($arreglo1);
    /// fin

    // guardar detalle venta
    for ($i = 0; $i <= $nelem; $i++) {
        // contador detalle factura venta
        $cont4 = 0;
        $consulta = pg_query("select max(id_detalle_venta) from detalle_liquidacion_compra");
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
            
        // guardar detalle_factura
        pg_query("insert into detalle_liquidacion_compra values('$cont4','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','Activo','$arreglo6[$i]')");
        // fin
            
        // modificar productos general
        $consulta2 = pg_query("select * from productos where cod_productos = '$arreglo1[$i]'");
        while ($row = pg_fetch_row($consulta2)) {
            $stock = $row[13];
        }
        $cal = $stock - $arreglo2[$i];

        pg_query("Update productos Set stock='" . $cal . "' where cod_productos='" . $arreglo1[$i] . "'");
        // fin

        // consulta kardex valorizado
        $consulta2 = pg_query("select * from kardex_valorizado where cod_productos = '$arreglo1[$i]' order by id_kardex asc");
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

        pg_query("insert into kardex_valorizado values(".$cont_v.",'".$arreglo1[$i]."','$_POST[fecha_actual]', '" . 'N Venta: '. $_POST['num_factura']."','','".$cantidad_salida."','".$cantidad."','".$precio_unitario_salida."','".$precio_total_salida."','','','".$cantidad_total."','4')");
        // fin

        // guardar kardex
        pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '" . 'N F.V:' . $_POST['num_factura'] . "' ,'$arreglo2[$i]','$arreglo3[$i]','$arreglo5[$i]','$arreglo1[$i]','$cal','2','','')");
        // 
    }
    $data = $_POST['id_liquidacion_compra'];

echo $data;
?>
