<?php

session_start();
include '../../procesos/base.php';
require_once '../../procesos/detalleProductosBodega.php';
require_once '../../procesos/kardexValorizado.php';
require_once '../../procesos/transacciones.php';
conectarse();
error_reporting(0);
date_default_timezone_set('America/Guayaquil');
// datos detalle factura
$campo1 = $_POST['campo1'];
$campo2 = $_POST['campo2'];
$campo3 = $_POST['campo3'];
$campo4 = $_POST['campo4'];
$campo5 = $_POST['campo5'];
$campo6 = $_POST['campo6'];
$campo7 = $_POST['campo7'];

$cont1 = obtenerIdInventario();

$conpuntoresult = $_SESSION['PV'];
// guardar factura compra
//DESBLOQUEAR pg_query("insert into inventario values('$cont1','$_SESSION[id]','$conpuntoresult','$cont1','$_POST[fecha_actual]','$_POST[hora_actual]','Activo')");
$documento = str_pad($cont1, 9, "0", STR_PAD_LEFT);
guardarInventario($cont1, $_SESSION['id'], $conpuntoresult, $cont1, $_POST['fecha_actual'], $_POST['hora_actual'], 'Activo', $_POST['observacion'], $documento);
// // fin
// agregar detalle inventario
$arreglo1 = explode('|', $campo1);
$arreglo2 = explode('|', $campo2);
$arreglo3 = explode('|', $campo3);
$arreglo4 = explode('|', $campo4);
$arreglo5 = explode('|', $campo5);
$arreglo6 = explode('|', $campo6);
$arreglo7 = explode('|', $campo7);
$nelem = count($arreglo1);
// fin
for ($i = 1; $i < $nelem; $i++) {
    if (!empty($arreglo1[$i])) {
        // contador detalle inventario
        /* $cont2 = 0;
          $consulta = pg_query("select max(id_detalle_inventario) from detalle_inventario");
          while ($row = pg_fetch_row($consulta)) {
          $cont2 = $row[0];
          }
          $cont2++; */
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
        $var_anterior = 0;
        $varinicial = 0;
        $varcero = 0;
        $consulta_v = pg_query("select disponibles,cod_productos from detalle_inventario where cod_productos='$arreglo1[$i]'");
        while ($row = pg_fetch_row($consulta_v)) {
            $varinicial = $row[1];
            $varcero = $row[0];
        }

        if ($varinicial != "" || $varinicial != 0) {
            $var_anterior = $varcero + $arreglo4[$i];
            $contb_valor = 0;
//         pg_query("Update detalle_inventario Set  disponibles= '$var_anterior' ,existencia='$var_anterior', diferencia='$var_anterior' where cod_productos='" . $arreglo1[$i] . "' ");

            $consulta = pg_query("select stock from detalle_producto_bodega where cod_productos='$arreglo1[$i]' and id_bodega=' $conpuntoresult'");
            $varpdb = 0;
            while ($row1 = pg_fetch_row($consulta)) {
                $varpdb = $row1[0];
            }
            $contb_valor = $varpdb + $arreglo4[$i];
//         pg_query("Update detalle_producto_bodega Set fecha='" . $_POST[fecha_actual] . "' ,hora='" . $_POST[hora_actual] . "', stock='$contb_valor' where cod_productos='" . $arreglo1[$i] . "' and id_bodega='" . $conpuntoresult . "' ");   
            $stocki = 0;
            $cali = 0;
            $consulta_i = pg_query("select * from detalle_inventario where  cod_productos=$arreglo1[$i]");
            while ($rowi = pg_fetch_row($consulta_i)) {

                $stocki = $rowi[5];
            }
            $cali = $stocki + $arreglo4[$i];
            $total = ($arreglo2[$i] * $arreglo4[$i]);
            if ($arreglo7[$i] == "sumar") {
                //DESBLOQUEAR pg_query("insert into detalle_inventario values('$cont2','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','$cali','Activo')");
                guardarDetalleInventario($cont1, $arreglo1[$i], $arreglo2[$i], $arreglo3[$i], $arreglo4[$i], $arreglo5[$i], $cali, 'Activo');
                procesarKardexEntrada($arreglo1[$i], "INVS - " . $documento, $arreglo4[$i], obtenerStock($arreglo1[$i], $_SESSION['PV']), 
                        $arreglo2[$i], 'Activo', $_SESSION['PV'], 'INV', $cont1, $total, '', '', $_POST['observacion'], NULL, NULL, NULL, $_SESSION['id']);
            } else {
                //DESBLOQUEAR pg_query("insert into detalle_inventario values('$cont2','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','$arreglo6[$i]','Activo')");
                guardarDetalleInventario($cont1, $arreglo1[$i], $arreglo2[$i], $arreglo3[$i], $arreglo4[$i], $arreglo5[$i], $arreglo6[$i], 'Activo');
                procesarKardexEntradaActualizar($arreglo1[$i], "INV - REMP - " . $documento, $arreglo4[$i], /*obtenerStock($arreglo1[$i], $_SESSION['PV'])*/0, 
                        $arreglo2[$i], 'Activo', $_SESSION['PV'], 'INV', $cont1, $total, '', '', $_POST['observacion'], NULL, NULL, NULL, $_SESSION['id']);
            }
        } else {
            $stocki = 0;
            $consulta_i = pg_query("select * from detalle_inventario where  cod_productos=$arreglo1[$i]");
            while ($rowi = pg_fetch_row($consulta_i)) {
                $stocki = $rowi[5];
            }
            $cali = $stocki + $arreglo4[$i];
            $total = ($arreglo2[$i] * $arreglo4[$i]);
            if ($arreglo7[$i] == "sumar") {
                //DESBLOQUEAR pg_query("insert into detalle_inventario values('$cont2','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','$cali','Activo')");
                guardarDetalleInventario($cont1, $arreglo1[$i], $arreglo2[$i], $arreglo3[$i], $arreglo4[$i], $arreglo5[$i], $cali, 'Activo');
                procesarKardexEntrada($arreglo1[$i], "INVS - " . $documento, $arreglo4[$i], obtenerStock($arreglo1[$i], $_SESSION['PV']), $arreglo2[$i], 'Activo', $_SESSION['PV'], 'INV', $cont1, $total, '', '', $_POST['observacion'], NULL, NULL, NULL, $_SESSION['id'],true,$_POST['fecha_actual']);
            } else {
                //DESBLOQUEAR pg_query("insert into detalle_inventario values('$cont2','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','$arreglo6[$i]','Activo')");
                guardarDetalleInventario($cont1, $arreglo1[$i], $arreglo2[$i], $arreglo3[$i], $arreglo4[$i], $arreglo5[$i], $arreglo6[$i], 'Activo');
                procesarKardexEntradaActualizar($arreglo1[$i], "INV - REMP - " . $documento, $arreglo4[$i], /*obtenerStock($arreglo1[$i], $_SESSION['PV'])*/0, $arreglo2[$i], 'Activo', $_SESSION['PV'], 'INV', $cont1, $total, '', '', $_POST['observacion'], NULL, NULL, NULL, $_SESSION['id'],$_POST['fecha_actual']);
            }
            //pg_query("Update productos Set existencia='" . $arreglo4[$i] . "', diferencia='" . $arreglo6[$i] . "' where cod_productos='" . $arreglo1[$i] . "'");
            actualizarExistenciaDiferenciaProducto($arreglo1[$i], $arreglo6[$i], $arreglo4[$i]);
        }
        // fin
        // guardar inventario
        // modificar productos general
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
        }

        if ($cod_pro == $arreglo1[$i] && $id_bod == $conpuntoresult) {

            $consulta = pg_query("select stock from detalle_producto_bodega where cod_productos='$arreglo1[$i]' and id_bodega=' $conpuntoresult'");
            $varpdb = 0;
            while ($row1 = pg_fetch_row($consulta)) {
                $varpdb = $row1[0];
            }
            $contb_valor = $varpdb + $arreglo4[$i];

            if ($arreglo7[$i] == "sumar") {
                //DESBLOQUEAR pg_query("Update detalle_producto_bodega Set fecha='" . $_POST[fecha_actual] . "' ,hora='" . $_POST[hora_actual] . "', stock='$contb_valor' where cod_productos='" . $arreglo1[$i] . "' and id_bodega='" . $conpuntoresult . "' ");
                //DESBLOQUEAR actualizarDetalleProductoBodega($arreglo1[$i], $conpuntoresult, $contb_valor);
            } else {
                //DESBLOQUEAR pg_query("Update detalle_producto_bodega Set fecha='" . $_POST[fecha_actual] . "' ,hora='" . $_POST[hora_actual] . "', stock='" . $arreglo4[$i] . "' where cod_productos='" . $arreglo1[$i] . "' and id_bodega='" . $conpuntoresult . "' ");
                //actualizarDetalleProductoBodega($arreglo1[$i], $conpuntoresult, $arreglo4[$i]);
            }
//    pg_query("Update detalle_producto_bodega Set fecha='" . $_POST[fecha_actual] . "' ,hora='" . $_POST[hora_actual] . "', stock='" . $arreglo4[$i] . "' where cod_productos='" . $arreglo1[$i] . "' and id_bodega='" . $conpuntoresult . "' ");   
        } else {
            $contb = 0;
            $consulta = pg_query("select max(id_detalle_productos_bodega) from detalle_producto_bodega");
            while ($row = pg_fetch_row($consulta)) {
                $contb = $row[0];
            }
            $contb++;
            $horap = date("g:ia");
            //DESBLOQUEAR pg_query("insert into detalle_producto_bodega values('$contb','$arreglo1[$i]','$conpuntoresult','$_SESSION[id]','$_POST[fecha_actual]','$horap','$arreglo4[$i]')");
            // guardarDetalleProductoBodega($arreglo1[$i], $conpuntoresult, $_SESSION['id'], $arreglo4[$i]);
        }

        /* $contR = 0;
          $cantidad = 0;
          $precio_total = 0;
          $consulta2 = pg_query("select max(id_kardex),* from kardex_valorizado where cod_productos = '$arreglo1[$i]'  GROUP BY id_kardex ");
          while ($row = pg_fetch_row($consulta2)) {
          $cantidad = $row[11];
          $precio_total = $row[8];
          $costo_ven_unitario = $row[13];
          $costoVenta = $row[13];
          $contR = 1;
          }

          if ($arreglo7[$i] == "sumar") {
          echo '<br>PROCESAR CARDEX SUMA<br>';
          $cantidad_entrada = $arreglo4[$i];
          $precio_unitario_entrada = $arreglo2[$i];
          $precio_total_entrada = $cantidad_entrada * $precio_unitario_entrada;

          $cantidad_total = $cantidad + $arreglo4[$i];

          $precio_total_total = number_format($precio_total + $precio_total_entrada, 2, '.', '');
          $precio_unitario_total = number_format($precio_total_total / $cantidad_total, 2, '.', '');

          if ($contR == 1) {
          $costo_promediounitario = (($cantidad * $costo_ven_unitario) + ($cantidad_entrada * $precio_unitario_entrada)) / ($cantidad + $cantidad_entrada);
          } else {
          $costo_promediounitario = $precio_unitario_entrada;
          }


          //DESBLOQUEAR pg_query("insert into kardex_valorizado values('$cont_v','$arreglo1[$i]','$_POST[fecha_actual]', '" . 'SALDO INICIAL IP ' . $_POST['serie'] . "','" . $cantidad_entrada . "','','" . $cantidad . "','" . $precio_unitario_entrada . "','" . $precio_total_entrada . "','','','" . $cantidad_total . "','6','" . $costo_promediounitario . "','$conpuntoresult','E')");
          //DESBLOQUEAR procesarKardexEntrada($arreglo1[$i], "SALDO INICIAL IP " . $_POST['serie'], $arreglo4[$i], $cantidad, $arreglo2[$i], 6, $_SESSION['PV'], 'INVS', $cont1, $total, '', '', $comentario);
          } else {
          echo '<br>PROCESAR CARDEX REMPLAZO<br>';
          $cantidad_entrada = $arreglo4[$i];
          $precio_unitario_entrada = $arreglo2[$i];
          $precio_total_entrada = $cantidad_entrada * $precio_unitario_entrada;

          $cantidad_total = $arreglo4[$i];

          $precio_total_total = number_format($precio_total + $precio_total_entrada, 2, '.', '');
          $precio_unitario_total = number_format($precio_total_total / $cantidad_total, 2, '.', '');

          if ($contR == 1) {
          $costo_promediounitario = (($cantidad * $costo_ven_unitario) + ($cantidad_entrada * $precio_unitario_entrada)) / ($cantidad + $cantidad_entrada);
          } else {
          $costo_promediounitario = $precio_unitario_entrada;
          }

          //DESBLOQUEAR pg_query("insert into kardex_valorizado values('$cont_v','$arreglo1[$i]','$_POST[fecha_actual]', '" . 'SALDO INICIAL IP ' . $_POST['serie'] . "','" . $cantidad_entrada . "','','" . $cantidad . "','" . $precio_unitario_entrada . "','" . $precio_total_entrada . "','','','" . $cantidad_total . "','6','" . $costo_promediounitario . "','$conpuntoresult','E')");
          //DESBLOQUEAR procesarKardexEntrada($arreglo1[$i], "SALDO INICIAL IP " . $_POST['serie'], $arreglo4[$i], $cantidad, $arreglo2[$i], 6, $_SESSION['PV'], 'INV', $cont1, $total, '', '', $comentario);
          } */


        /* $stockk = 0;
          $calk = 0;
          $consulta_v = pg_query("select * from kardex where  cod_productos='$arreglo1[$i]' ");
          while ($row = pg_fetch_row($consulta_v)) {

          $stockk = $row[7];
          }
          $calk = $stockk + $arreglo4[$i];
          // guardar kardex

          if ($arreglo7[$i] == "sumar") {
          $calk = $stockk + $arreglo4[$i];
          //DESBLOQUEAR pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', 'INV.S', '" . $arreglo4[$i] . "','','','" . $arreglo1[$i] . "','$calk', '6','','','0','$cont1','INV','$conpuntoresult')");
          } else {

          //DESBLOQUEAR  pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', 'INV.V.A', '" . $arreglo4[$i] . "','','','" . $arreglo1[$i] . "', '" . $arreglo4[$i] . "', '6','','','0','$cont1','INV','$conpuntoresult')");
          } */

        /*         * *** REVISAR PENDIENTE **** */
        //Asiento Contable 
        $cuenta = pg_query("select iva,id_plan_cuentas from productos where cod_productos ='" . $arreglo1[$i] . "'");
        $plan = pg_fetch_row($cuenta);
        $sumaSubtotalTarifa12 = 0;
        $sumaSubtotalTarifa0 = 0;
        $codplanTarifa12 = 0;
        $codplanTarifa0 = 0;
        $contTarifa12 = 0;
        $contTarifa0 = 0;
        if ($plan[0] == "Si") {
            $sumaSubtotalTarifa12 = $sumaSubtotalTarifa12 + $_POST['total_costo'];
            $codplanTarifa12 = $plan[1];
            $contTarifa12++;
        } else if ($plan[0] == "No") {
            $sumaSubtotalTarifa0 = $sumaSubtotalTarifa0 + $_POST['total_costo'];
            $codplanTarifa0 = $plan[1];
            $contTarifa0++;
        }
        /////////////////////////
    }
}

////////////////////////////////////////
///////////////////////ASIENTO CONTABLE
/*
  $fila[0] = obtenerIdTransaccion();
  guardarTransaccion($fila[0], $_SESSION['id'], $cont1, $_POST['fecha_actual'], $_POST['hora_actual'], 'INVENTARIO: ', $_POST['total_costo'], $_POST['total_costo'], 0.0000, 2, $fila[0], 'Activo', NULL, '', $_POST['observacion'], '', '', 'INV', 0.0000, $conpuntoresult);

  if ($sumaSubtotalTarifa12 > 0) {
  //pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa12','$sumaSubtotalTarifa12','0.000','Activo')");
  guardarDetalleTransaccion($fila[0], $codplanTarifa12, $sumaSubtotalTarifa12, 0.0000, 'Activo', '');
  }
  if ($sumaSubtotalTarifa0 > 0) {
  //pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','$codplanTarifa0','$sumaSubtotalTarifa0','0.000','Activo')");
  guardarDetalleTransaccion($fila[0], $codplanTarifa0, $sumaSubtotalTarifa0, 0.0000, 'Activo', '');
  }

  if ($_POST['iva'] > 0) {
  $fila1[0] = $fila1[0] + 1;
  $planiva = pg_query("select cuenta_debito from parametros where descripcion='IVA'");
  $fila2 = pg_fetch_row($planiva);
  //pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','$_POST[total_costo]','0.000','Activo')");
  guardarDetalleTransaccion($fila[0], $fila2[0], $_POST['total_costo'], 0.0000, 'Activo', '');
  }

  echo '<br>FILA 2: ' . $fila2[0];

  $total = $_POST['total_costo'];
  $plancaja = pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
  $fila2 = pg_fetch_row($plancaja);
  //pg_query("insert into detalle_transaccion values('" . $fila1[0] . "','" . $fila[0] . "','" . $fila2[0] . "','0.000','" . $total . "','Activo')");
  guardarDetalleTransaccion($fila[0], $fila2[0], 0.0000, $total, 'Activo', '');
 */

////////////////////////////////////////
///////////////////////////////////////
$data = 1;
echo $data;

function obtenerIdInventario() {
    $consulta = pg_query("select max(id_inventario) from inventario");
    $id = (pg_fetch_row($consulta)[0] + 1);
    return $id;
}

function obtenerIdDetalleInventario() {
    $consulta = pg_query("select max(id_detalle_inventario) from detalle_inventario");
    $id = (pg_fetch_row($consulta)[0] + 1);
    return $id;
}

function guardarInventario($id, $user, $bodega, $comprobante, $fechaAltual, $horaActual, $estado, $observacion, $documento) {
    $sql = "INSERT INTO inventario(id_inventario, id_usuario, id_empresa, comprobante, fecha_actual, hora_actual, estado, observaciones, documento) "
            . "VALUES ($id, $user, $bodega, $comprobante, " . ($fechaAltual == NULL ? "NULL" : "'$fechaAltual'") . ", '$horaActual', '$estado', '$observacion', '$documento')";
    pg_query($sql);
}

function guardarDetalleInventario($inv, $producto, $costo, $venta, $disponible, $existencia, $diferencia, $estado) {
    $sql = "INSERT INTO detalle_inventario (id_detalle_inventario, id_inventario, cod_productos, p_costo, p_venta, disponibles, existencia, diferencia, estado) "
            . "VALUES(" . obtenerIdDetalleInventario() . ", $inv, $producto, " . ($costo == NULL ? "0.0000" : number_format($costo, 4, '.', '')) . ", "
            . "" . ($venta == NULL ? "0.0000" : number_format($venta, 4, '.', '')) . ", " . ($disponible == NULL ? "0.00" : number_Format($disponible, 2, '.', '')) . ", "
            . "" . ($existencia == NULL ? "0.00" : number_Format($existencia, 2, '.', '')) . ", " . ($diferencia == NULL ? "0.00" : number_Format($diferencia, 2, '.', '')) . ", "
            . "'$estado')";
    pg_query($sql);
}

function actualizarExistenciaDiferenciaProducto($producto, $diferencia, $existencia) {
    $sql = "UPDATE productos Set existencia=" . number_format($existencia, 2, '.', '') . ", diferencia= " . number_format($diferencia, 2, '.', '') . " WHERE cod_productos=$producto";
    pg_query($sql);
}

?>