<?php

//session_start();
include_once __DIR__ . '/../../../procesos/base.php';
require_once __DIR__ . '/../../../procesos/kardexValorizado.php';
require_once __DIR__ . '/../../../procesos/detalleProductosBodega.php';
require_once __DIR__ . '/../../../procesos/fecha.php';
$conexion = conectarse();
$conpuntoresult = $_SESSION['PV'];
error_reporting(0);

function procesosGuardarIngreso($bodega, $usuario, $origen, $destino, $tarifa0, $tarifa12, $iva, $descuento, $total, $observacion, $detalles)
{
    $conpuntoresult = $_SESSION['PV'];
    $idingreso = obtenerIdIngreso();
    $docu = str_pad($idingreso, 9, "0", STR_PAD_LEFT);
    $gingreso = guardarIngreso($idingreso, $bodega, $usuario, $idingreso, $origen, $destino, $tarifa0, $tarifa12, $iva, $descuento, $total, $observacion);
    if (!$gingreso) {
        return null;
    }
    foreach ($detalles as $value) {
        $gdetetalle = guardarDetalleIngreso($idingreso, $value["cod_productos"], $value["cantidad"], $value["precio_costo"], $value["descuento"], $value["total"]);
        if (!$gdetetalle) {
            return null;
        }
        // guardar detalle productos bodega
        $cod_pro = 0;
        $id_bod = 0;
        $consulta_v = pg_query("select * from detalle_producto_bodega where id_bodega=$bodega and cod_productos=" . $value["cod_productos"]);
        while ($row = pg_fetch_row($consulta_v)) {
            $cod_pro = $row[1];
            $id_bod = $row[2];
        }

        if ($cod_pro == $value["cod_productos"] && $id_bod == $bodega) {
            procesarKardexEntrada($value["cod_productos"], 'T.I: T. Aceptada - ' . $docu, $value["cantidad"], obtenerStock($value["cod_productos"], $bodega), $value["costo_prom_unitario"], 'Activo', $bodega, 'I', $idingreso, $value["total"], NULL, NULL, '', NULL, NULL, '', $usuario, false);
        } else {
            procesarKardexEntrada($value["cod_productos"], 'T.I: T. Aceptada - ' . $docu, $value["cantidad"], obtenerStock($value["cod_productos"], $bodega), $value["costo_prom_unitario"], 'Activo', $bodega, 'I', $idingreso, $value["total"], NULL, NULL, '', NULL, NULL, '', $usuario, false);
        }
        ////////////////////////////////////////////////////////////////////////////ASI9ENTO CONTABLE/////////////////
        $costoVenta = 0;
        $costoVenta1 = 0;

        $cantidad = 0;
        $precio_total = 0;
        $precio_unitario = 0;
        $costoVenta = 0;
        //        echo 'conto venta' . "select * from kardex_valorizado where cod_productos = '" . $value["cod_productos"] . "' order by id_kardex desc limit 1";
        $consulta2 = pg_query("select * from kardex_valorizado where cod_productos = '" . $value["cod_productos"] . "' order by id_kardex desc limit 1");
        while ($row = pg_fetch_row($consulta2)) {
            $cantidad = $row[11];
            $precio_unitario = round($row[7], 4);
            $precio_total = round($row[8], 4);
            $costoVenta = round($row[13], 4);
        }
        if ($costoVenta == "0.0000") {
            $costoVenta1 = $costoVenta1 + ($value["cantidad"]);
        } else {
            $costoVenta1 = $costoVenta1 + ($costoVenta * $value["cantidad"]);
        }


        //Asiento Contable 
        $cuenta = pg_query("select iva,id_plan_cuentas from productos where cod_productos ='" . $value["cod_productos"] . "'  and bien_servicios='B'");
        $plan = pg_fetch_row($cuenta);

        if ($plan[0] == "Si") {
            if ($costoVenta == "0.0000") {
                $inventario12B = $inventario12B + ($value["cantidad"]);
            } else {
                $inventario12B = $inventario12B + ($costoVenta * $value["cantidad"]);
            }
            //            echo 'vvv' . $value["cantidad"];
            $sumaSubtotalTarifa12B = $sumaSubtotalTarifa12B + $value["total"];
            $codplanTarifa12B = $plan[1];
            $contTarifa12++;
        } else if ($plan[0] == "No") {
            if ($costoVenta == "0.0000") {
                $inventario0B = $inventario0B + ($value["cantidad"]);
            } else {
                $inventario0B = $inventario0B + ($costoVenta * $value["cantidad"]);
            }

            $sumaSubtotalTarifa0B = $sumaSubtotalTarifa0B + $value["total"];
            $codplanTarifa0B = $plan[1];
            $contTarifa0++;
        }
        /////////////////////////
        //Asiento Contable 
        $cuenta = pg_query("select iva,id_plan_cuentas from productos where cod_productos ='" . $value["cod_productos"] . "'  and bien_servicios='S'");
        $plan = pg_fetch_row($cuenta);

        if ($plan[0] == "Si") {
            if ($costoVenta == "0.0000") {
                $inventario12 = $inventario12 + ($value["cantidad"]);
            } else {
                $inventario12 = $inventario12 + ($costoVenta * $value["cantidad"]);
            }

            $sumaSubtotalTarifa12 = $sumaSubtotalTarifa12 + $value["total"];
            $codplanTarifa12 = $plan[1];
            $contTarifa12++;
        } else if ($plan[0] == "No") {
            if ($costoVenta == "0.0000") {
                $inventario0 = $inventario0 + ($value["cantidad"]);
            } else {
                $inventario0 = $inventario0 + ($costoVenta * $value["cantidad"]);
            }

            $sumaSubtotalTarifa0 = $sumaSubtotalTarifa0 + $value["total"];
            $codplanTarifa0 = $plan[1];
            $contTarifa0++;
        }
        ////////////////////////////CREACION ASIENTO CONTADO - CHEQUE
        // guardar asiento contable

        $idtran = pg_query("select max(id_transacciones) from transacciones");
        $fila = pg_fetch_row($idtran);
        $fila[0] = $fila[0] + 1;
        $sum = 0;
        $bool = true;
        $pos = 0;
        $vec = 0;
        $tieneiva;
        $ivafin = 0;
        //$auxiliar = $arreglo1;
        $abc = 0;
        $xy = 0;
        $iva = pg_query("select valor from parametros where descripcion='IVA'");
        while ($ivavalor = pg_fetch_row($iva)) {
            $ivafin = $ivavalor[0];
        }
        $abc = ($ivafin + 100) / 100;

        $cont1 = $idingreso;

        $ing = pg_query("select max(num_transaccion) from transacciones where id_tipo_transaccion='1' and id_empresa= '$_SESSION[PV]'");
        $res = pg_fetch_row($ing);
        $ing_pvt = pg_query("select max(id_transaccion_pv::int) from transacciones where id_empresa= '$_SESSION[PV]'");
        $res_pvt = pg_fetch_row($ing_pvt);
        //                  print_r("trans2".$costoVenta1);
        //echo '<br>GUARDAR FACTURA VENTA12F: <br>' . "insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont . "','$_POST[fecha_actual]','$_POST[hora_actual]', 'ANTICIPO CLIENTES, CLIENTE: " . $p[0] . ", COMPROBANTE: " . $_POST['secuencial'] . ", : " . $_POST['marca_vehiculo'] . "', '" . $_POST[monto] . "', '$_POST[monto]', '" . $_POST[monto] . "','2','" . ($res[0] + 1) . "','Activo','$cliente1','','','','','ANTC','',$conpuntoresult,'$_POST[fecha_actual]')"; //////////////////////////
        $valor_Servicio1_iva = '';
        //echo '<br>GUARDAR FACTURA VENTA1 ggggg: <br>' . "SELECT  sum(precio_costo)FROM detalle_ingreso where id_ingresos='$cont1' ";
        $consulta_bien_servi_iva = pg_query("SELECT  sum(precio_costo)FROM detalle_ingreso where id_ingresos='$cont1' ");
        while ($row = pg_fetch_row($consulta_bien_servi_iva)) {
            $valor_Servicio1_iva = $row[0];
        }
        //////////////////////////////////////////////////////////////////
        $punto_venta_origen = "";
        $punto_venta = pg_query("SELECT nombre_punto
  FROM punto_venta where id_punto_venta=" . $origen . "");


        while ($row = pg_fetch_row($punto_venta)) {
            $punto_venta_origen = $row[0];
        }

        $res_pv_destino = "";
        $res_pv = pg_query("SELECT nombre_punto
  FROM punto_venta where id_punto_venta=" . $destino . "");

        while ($row = pg_fetch_row($res_pv)) {
            $res_pv_destino = $row[0];
        }

        if ($inventario12B > 0) {
            $total0total12B = $inventario12B + $inventario0B;


            //            echo '<br>GUARDAR FACTURA transaccionEE1: <br>' . "insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','" . obtenerFechaActual() . "','" . obtenerHoraActual() . "', 'TRANSFERENCIA INGRESO, :  , COMPROBANTE: " . $cont1 . ", DEL PUNTO " . $punto_venta_origen . ", AL PUNTO " . $res_pv_destino . "', '" . $total0total12B . "', '$total0total12B', '0.000','2','" . ($res[0] + 1) . "','Activo',NULL,'','','','','I','',$conpuntoresult,'" . obtenerFechaActual() . "','" . ($res_pv[0] + 1) . "')";
            $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','" . obtenerFechaActual() . "','" . obtenerHoraActual() . "', 'TRANSFERENCIA INGRESO, :  , COMPROBANTE: " . $cont1 . ", DEL PUNTO " . $punto_venta_origen . ", AL PUNTO " . $res_pv_destino . "', '" . $total0total12B . "', '$total0total12B', '0.000','2','" . ($res[0] + 1) . "','Activo',NULL,'','','','','I','',$conpuntoresult,'" . obtenerFechaActual() . "','" . ($res_pvt[0] + 1) . "')");
        } else {
            $total0total12 = $inventario12B + $inventario0B;
            //            echo '<br>GUARDAR FACTURA transaccionEE2: <br>' . "insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','" . obtenerFechaActual() . "','" . obtenerHoraActual() . "', 'TRANSFERENCIA INGRESO, :  , COMPROBANTE: " . $cont1 . ", DEL PUNTO " . $punto_venta_origen . ", AL PUNTO " . $res_pv_destino . "', '" . $total0total12 . "', '$total0total12', '0.000','2','" . ($res[0] + 1) . "','Activo',NULL,'','','','','I','',$conpuntoresult,'" . obtenerFechaActual() . "','" . ($res_pv[0] + 1) . "')";
            $asiento = pg_query("insert into transacciones values('" . $fila[0] . "', '$_SESSION[id]', '" . $cont1 . "','" . obtenerFechaActual() . "','" . obtenerHoraActual() . "', 'TRANSFERENCIA INGRESO, :  , COMPROBANTE: " . $cont1 . ", DEL PUNTO " . $punto_venta_origen . ", AL PUNTO " . $res_pv_destino . "', '" . $total0total12 . "', '$total0total12', '0.000','2','" . ($res[0] + 1) . "','Activo',NULL,'','','','','I','',$conpuntoresult,'" . obtenerFechaActual() . "','" . ($res_pvt[0] + 1) . "')");
        }
        $iddettran = pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
        $fila1 = pg_fetch_row($iddettran);

        if ($inventario12B > 0) {
            $total0total12B = $inventario12B + $inventario0B;


            $fila1[0] = $fila1[0] + 1;
            $sql = pg_query("SELECT id_plan_cuentas FROM plan_cuentas where descripcion like '%INVENTARIO DE MERCADERIA%'");
            $buscaCuenta = pg_fetch_row($sql);
            $fila1[0] = $fila1[0] + 1;
            //            echo '<br>GUARDAR FACTURA DT1: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','" . $total0total12B . "','0.000','Activo')"; //////////////////////////
            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','" . $total0total12B . "','0.000','Activo')");
        } else if ($inventario0B > 0) {
            $total0total12 = $inventario12B + $inventario0B;



            $sql = pg_query("SELECT id_plan_cuentas FROM plan_cuentas where descripcion like '%INVENTARIO DE MERCADERIA%'");
            $buscaCuenta = pg_fetch_row($sql);
            $fila1[0] = $fila1[0] + 1;
            //            echo '<br>GUARDAR FACTURA DT2: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','" . $total0total12 . "','0.000','Activo')"; //////////////////////////
            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','" . $total0total12 . "','0.000','Activo')");
        }
        ///////////////////////////////////////////////////////////////////////////////////
        $buscaCuenta = 0;
        ////////////////////MATRIZ A PUNTO A 
        if ($origen == '1' && $destino == '2') {
            //    echo "1 a 2";
            $sql = pg_query("
  SELECT id_plan_cuentas, codigo_plan, descripcion, cuenta, estado
  FROM plan_cuentas where codigo_plan like '%2.1.03.01.03%'");
            $buscaCuenta = pg_fetch_row($sql);
        }
        ////////////////////MATRIZ A PUNTO b
        if ($origen == '1' && $destino == '3') {
            //    echo "1 a 3";
            $sql = pg_query("
  SELECT id_plan_cuentas, codigo_plan, descripcion, cuenta, estado
 FROM plan_cuentas where codigo_plan like '%2.1.03.01.03%'");
            $buscaCuenta = pg_fetch_row($sql);
        }
        ////////////////////A A PUNTO MATRIZ 
        if ($origen == '2' && $destino == '1') {
            //    echo "2 a 1";
            $sql = pg_query("
  SELECT id_plan_cuentas, codigo_plan, descripcion, cuenta, estado
  FROM plan_cuentas where codigo_plan like '%1.1.03.01.02%'");
            $buscaCuenta = pg_fetch_row($sql);
        }
        ////////////////////A A PUNTO B 
        if ($_POST["origen"] == '2' && $_POST["destino"] == '3') {
            //      echo "2 a 3";
            $sql = pg_query("
  SELECT id_plan_cuentas, codigo_plan, descripcion, cuenta, estado
  FROM plan_cuentas where codigo_plan like '%1.1.03.01.02%'");
            $buscaCuenta = pg_fetch_row($sql);
        }
        ////////////////////A B PUNTO MATRIZ
        if ($origen == '3' && $destino == '1') {
            //    echo "3 a 1";
            $sql = pg_query("
  SELECT id_plan_cuentas, codigo_plan, descripcion, cuenta, estado
  FROM plan_cuentas where codigo_plan like '%1.1.03.01.03%'");
            $buscaCuenta = pg_fetch_row($sql);
        }
        ////////////////////////////////////////SOLO PUNTO
        ////////////////////SOLO PUNTO A
        if ($origen == '' && $destino == '') {
            if ($bodega == '1') {
                echo "PUNTO 1";
                $sql = pg_query("
  SELECT id_plan_cuentas, codigo_plan, descripcion, cuenta, estado
  FROM plan_cuentas where codigo_plan like '%2.1.03.01.03%'");
                $buscaCuenta = pg_fetch_row($sql);
            }
        }
        ////////////////////SOLO PUNTO B
        if ($origen == '' && $destino == '') {
            if ($bodega == '2') {
                echo "PUNTO 2";
                $sql = pg_query("
  SELECT id_plan_cuentas, codigo_plan, descripcion, cuenta, estado
  FROM plan_cuentas where codigo_plan like '%1.1.03.01.02%'");
                $buscaCuenta = pg_fetch_row($sql);
            }
        }
        ////////////////////SOLO PUNTO C
        if ($origen == '' && $destino == '') {
            if ($bodega == '3') {
                echo "PUNTO 3";
                $sql = pg_query("
  SELECT id_plan_cuentas, codigo_plan, descripcion, cuenta, estado
  FROM plan_cuentas where codigo_plan like '%1.1.03.01.03%'");
                $buscaCuenta = pg_fetch_row($sql);
            }
        }

        if ($inventario12B > 0) {
            $total0total12B = $inventario12B + $inventario0B;
            $fila1[0] = $fila1[0] + 1;
            //            echo '<br>GUARDAR FACTURA PUNTOS A1: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','0.000','" . $total0total12B . "','Activo')"; //////////////////////////

            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','0.000','" . $total0total12B . "','Activo')");
        } else if ($inventario0B > 0) {
            $total0total12 = $inventario12B + $inventario0B;
            $fila1[0] = $fila1[0] + 1;
            //            echo '<br>GUARDAR FACTURA PUNTOS A2: <br>' . "insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','0.000','" . $total0total12 . "','Activo')"; //////////////////////////

            pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $buscaCuenta[0] . "','0.000','" . $total0total12 . "','Activo')");
        }
    }
    return $idingreso;
}

//////////////////////////////////////////
function guardarIngreso($id, $bodega, $usuario, $comprobante, $origen, $destino, $tarifa0, $tarifa12, $iva, $descuento, $total, $observacion)
{
    $sql = "INSERT INTO ingresos(id_ingresos, id_empresa, id_usuario, comprobante, fecha_actual, hora_actual, origen, destino, tarifa0, tarifa12, "
        . "iva_ingreso, descuento_ingreso, total_ingreso, observaciones, estado) "
        . "VALUES ($id, $bodega, $usuario, '$comprobante','" . obtenerFechaActual() . "','" . obtenerHoraActual() . "'," . ($origen == NULL ? "NULL" : $origen) . ", "
        . "" . ($destino == NULL ? "NULL" : $destino) . "," . number_format($tarifa0, 4, '.', '') . ", " . number_format($tarifa12, 4, '.', '') . ", "
        . "" . number_format($iva, 4, '.', '') . ", $descuento, $total, '$observacion', 'Activo')";
    return pg_query($sql);
}

function guardarDetalleIngreso($ingreso, $producto, $cantidad, $costo, $descuento, $total)
{
    $sql = "INSERT INTO detalle_ingreso(id_detalle_ingreso, id_ingresos, cod_productos, cantidad, precio_costo, descuento, total, estado) "
        . "VALUES (" . obtenerIdDetalleIngreso() . ", $ingreso, $producto, " . number_format($cantidad, 2, '.', '') . ", " . number_format($costo, 4, '.', '') . ""
        . ", " . number_format($descuento, 4, '.', '') . ", " . number_format($total, 4, '.', '') . ", 'Activo')";
    return pg_query($sql);
}

function obtenerIdDetalleIngreso()
{
    $consulta = pg_query("select max(id_detalle_ingreso) from detalle_ingreso");
    $id = (pg_fetch_row($consulta)[0] + 1);
    return $id;
}

function obtenerIdIngreso()
{
    $consid = pg_query("select max(id_ingresos) from ingresos");
    $id = pg_fetch_row($consid)[0] + 1;
    return $id;
}