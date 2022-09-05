<?php

session_start();
include '../../procesos/base.php';
require_once '../../procesos/fecha.php';
require_once '../../procesos/kardexValorizado.php';
require_once '../../procesos/detalleProductosBodega.php';
conectarse();
error_reporting(0);

/////datos detalle factura/////
$campo1 = $_POST['campo1'];
$campo2 = $_POST['campo2'];
$campo3 = $_POST['campo3'];
$campo4 = $_POST['campo4'];
$campo5 = $_POST['campo5'];
///////////////////////////////
/////////////////contador ingresos///////////
/* $cont1 = 0;
  $consulta = pg_query("select max(id_egresos) from egresos");
  while ($row = pg_fetch_row($consulta)) {
  $cont1 = $row[0];
  } */
$cont1 = obtenerIdEgreso();
$documento = str_pad($cont1, 9, "0", STR_PAD_LEFT);
//////////////////////////////////////////////////
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
////////////guardar egresos////////
//pg_query("insert into egresos values('$cont1','$conpuntoresult','$_SESSION[id]','$cont1','$_POST[fecha_actual]','$_POST[hora_actual]','$_POST[origen]','$_POST[destino]','$_POST[tarifa0]','$_POST[tarifa12]','$_POST[iva]','$_POST[desc]','$_POST[tot]','$_POST[observaciones]','Activo')");
guardarEgreso($cont1, $conpuntoresult, $_SESSION['id'], $cont1, $_POST['origen'], $_POST['destino'], $_POST['tarifa0'], $_POST['tarifa12'], $_POST['iva'], $_POST['desc'], $_POST['tot'], $_POST['observaciones'], 'Activo');
////////////////////////////////////////
//
////////////agregar ingresos////////
$arreglo1 = explode('|', $campo1);
$arreglo2 = explode('|', $campo2);
$arreglo3 = explode('|', $campo3);
$arreglo4 = explode('|', $campo4);
$arreglo5 = explode('|', $campo5);
$nelem = count($arreglo1);

///////////////////////////////////////////
for ($i = 0; $i <= $nelem; $i++) {
    if (!empty($arreglo1[$i])) {
        /////////////////contador detalle egreso/////////////
        $cont2 = 0;
        $consulta = pg_query("select max(id_detalle_egreso) from detalle_egreso");
        while ($row = pg_fetch_row($consulta)) {
            $cont2 = $row[0];
        }
        $cont2++;
        //////////////////////////  
        //////////////guardar detalle egreso////////
        //DESBLOQUEAR pg_query("insert into detalle_egreso values('$cont2','$cont1','$arreglo1[$i]','$arreglo2[$i]','$arreglo3[$i]','$arreglo4[$i]','$arreglo5[$i]','Activo')");

        guardarDetalleEgreso($cont1, $arreglo1[$i], $arreglo2[$i], $arreglo3[$i], $arreglo4[$i], $arreglo5[$i], 'Activo');

        ////////////////////////////////////////////
        //
//  //////////////modificar productos///////////
//  $consulta2=pg_query("select * from productos where cod_productos = '$arreglo1[$i]'");
//  while($row=pg_fetch_row($consulta2))
//   {
//    $stock=$row[13];
//   }
//  $cal=$stock-$arreglo2[$i];
//  ////////////////////////////////////////
//  
//  pg_query("Update productos Set stock='" . $cal . "' where cod_productos='" . $arreglo1[$i] . "'");
        ///////////////////////////////////////// 
        $contb = 0;
        $consulta = pg_query("select max(id_detalle_productos_bodega) from detalle_producto_bodega");
        while ($row = pg_fetch_row($consulta)) {
            $contb = $row[0];
        }
        $contb++;

        ///////////////////////////////////////77
        if ($_POST[origen] != 0 && $_POST[destino] != 0) {
            /*$contb = 0;
            $consulta = pg_query("select max(id_detalle_productos_bodega) from detalle_producto_bodega");
            while ($row = pg_fetch_row($consulta)) {
                $contb = $row[0];
            }
            $contb++;

            // guardar detalle productos bodega

            $consulta_v = pg_query("select * from detalle_producto_bodega where id_bodega=$_POST[origen] and cod_productos=$arreglo1[$i]");
            while ($row = pg_fetch_row($consulta_v)) {
                $cod_pro = $row[1];
                $id_bod = $row[2];
                $stockori = $row[6];
            }

            $consulta_v = pg_query("select * from detalle_producto_bodega where id_bodega=$_POST[destino] and cod_productos=$arreglo1[$i]");
            while ($row = pg_fetch_row($consulta_v)) {
                $cod_pro = $row[1];
                $id_bod = $row[2];
                $stockdes = $row[6];
            }

            $caldes = $arreglo2[$i] + $stockdes;
            $calori = $stockori - $arreglo2[$i];*/
            
            $costoPromedio = obtenerCostoPromedioUnitario($arreglo1[$i], $_POST['origen'])[0]['costo_prom_unitario'];
                                                                                                                                                                                   //TOTAL
            procesarKardexSalida($arreglo1[$i], "T.E - " . $documento, $arreglo2[$i], obtenerStock($arreglo1[$i], $_POST['origen']), NULL, 'Activo', $_POST['origen'], 'EI', $cont1, NULL, $_POST['origen'], $_POST['destino'], NULL, $_POST['observaciones'], NULL, NULL, $_SESSION['id']);
                                                                                                                                                                                              //TOTAL
            procesarKardexEntrada($arreglo1[$i], "T.E - " . $documento, $arreglo2[$i], obtenerStock($arreglo1[$i], $_POST['destino']), $costoPromedio, 'Activo', $_POST['destino'], 'TEI', $cont1, NULL, $_POST['origen'], $_POST['destino'], $_POST['observaciones'], NULL, NULL, NULL, $_SESSION['id']);

            /*
             * DESBLOQUEAR
             * 
             * if ($cod_pro == $arreglo1[$i] && $id_bod == $_POST[destino]) {
             */
            //DESBLOQUEAR pg_query("Update detalle_producto_bodega Set fecha='" . $_POST[fecha_actual] . "' ,hora='" . $_POST[hora_actual] . "', stock='" . $caldes . "' where cod_productos='" . $arreglo1[$i] . "' and id_bodega='" . $_POST[destino] . "' ");
            //DESBLOQUEAR pg_query("Update detalle_producto_bodega Set fecha='" . $_POST[fecha_actual] . "' ,hora='" . $_POST[hora_actual] . "', stock='" . $calori . "' where cod_productos='" . $arreglo1[$i] . "' and id_bodega='" . $_POST[origen] . "' ");
            ///////////////////contador kardex////////
            $cont_k = 0;
            $consulta_k = pg_query("select max(id_kardex) from kardex");
            while ($row = pg_fetch_row($consulta_k)) {
                $cont_k = $row[0];
            }
            $cont_k++;
            //////////////////////////////////////////
            //
              ///guardar kardex/////
            //DESBLOQUEAR pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '  T.E:  ' ,'$arreglo2[$i]','$arreglo3[$i]','$arreglo5[$i]','$arreglo1[$i]','$caldes','4','$_POST[origen]','$_POST[destino]','0','$cont1','E','$_POST[destino]')");
            ///////////////////////////////
            ///////////////////contador kardex////////
            $cont_k = 0;
            $consulta_k = pg_query("select max(id_kardex) from kardex");
            while ($row = pg_fetch_row($consulta_k)) {
                $cont_k = $row[0];
            }
            $cont_k++;
            //////////////////////////////////////////
            //
              ///guardar kardex/////
            //DESBLOQUEAR pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '  T.E:  ' ,'$arreglo2[$i]','$arreglo3[$i]','$arreglo5[$i]','$arreglo1[$i]','$calori','4','$_POST[origen]','$_POST[destino]','0','$cont1','E','$_POST[origen]')");
            /////////////////////////////
            /* } else {
              $contb = 0;
              $consulta = pg_query("select max(id_detalle_productos_bodega) from detalle_producto_bodega");
              while ($row = pg_fetch_row($consulta)) {
              $contb = $row[0];
              }
              $contb++;
              $horap = date("g:ia");
              //DESBLOQUEAR pg_query("insert into detalle_producto_bodega values('$contb','$arreglo1[$i]','$conpuntoresult','$_SESSION[id]','$_POST[fecha_actual]','$horap','$cal')");
              ///////////////////contador kardex////////
              $cont_k = 0;
              $consulta_k = pg_query("select max(id_kardex) from kardex");
              while ($row = pg_fetch_row($consulta_k)) {
              $cont_k = $row[0];
              }
              $cont_k++;
              //////////////////////////////////////////
              //
              ///guardar kardex/////
              //DESBLOQUEAR pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '  T.E:  ' ,'$arreglo2[$i]','$arreglo3[$i]','$arreglo5[$i]','$arreglo1[$i]','$calori','4','$_POST[origen]','$_POST[destino]','0','$cont1','E','$conpuntoresult')");
              /////////////////////////////
              } */
        } else {
            // guardar detalle productos bodega                                                                                                                                     TOTAL
            procesarKardexSalida($arreglo1[$i], "T.E.L - " . $documento, $arreglo2[$i], obtenerStock($arreglo1[$i], $conpuntoresult), NULL, 'Activo', $conpuntoresult, 'E', $cont1, null, NULL, NULL, NULL, $_POST['observaciones'], NULL, NULL, $_SESSION['id']);

            /*
             * DESBLOQUEAR
             * $consulta_v = pg_query("select * from detalle_producto_bodega where id_bodega=$conpuntoresult and cod_productos=$arreglo1[$i]");
              while ($row = pg_fetch_row($consulta_v)) {
              $cod_pro = $row[1];
              $id_bod = $row[2];
              $stock = $row[6];
              }
              $cal = $stock - $arreglo2[$i];

              if ($cod_pro == $arreglo1[$i] && $id_bod == $conpuntoresult) {
              //DESBLOQUEAR pg_query("Update detalle_producto_bodega Set fecha='" . $_POST[fecha_actual] . "' ,hora='" . $_POST[hora_actual] . "', stock='" . $cal . "' where cod_productos='" . $arreglo1[$i] . "' and id_bodega='" . $conpuntoresult . "' ");
              } else {
              $contb = 0;
              $consulta = pg_query("select max(id_detalle_productos_bodega) from detalle_producto_bodega");
              while ($row = pg_fetch_row($consulta)) {
              $contb = $row[0];
              }
              $contb++;
              $horap = date("g:ia");
              //DESBLOQUEAR pg_query("insert into detalle_producto_bodega values('$contb','$arreglo1[$i]','$conpuntoresult','$_SESSION[id]','$_POST[fecha_actual]','$horap','$cal')");
              }
              ///////////////////contador kardex////////
              $cont_k = 0;
              $consulta_k = pg_query("select max(id_kardex) from kardex");
              while ($row = pg_fetch_row($consulta_k)) {
              $cont_k = $row[0];
              }
              $cont_k++; */
            //////////////////////////////////////////
            //
  ///guardar kardex/////
            //DESBLOQUEAR pg_query("insert into kardex values('$cont_k','$_POST[fecha_actual]', '  T.E:  ' ,'$arreglo2[$i]','$arreglo3[$i]','$arreglo5[$i]','$arreglo1[$i]','$cal','4','$_POST[origen]','$_POST[destino]','0','$cont1','E','$conpuntoresult')");
            ///////////////////////////// 
        }
    }
}

$data = 1;
echo $data;

function obtenerIdEgreso() {
    $sql = "SELECT max(e.id_egresos) FROM egresos e";
    $id = (pg_fetch_row(pg_query($sql))[0] + 1);
    return $id;
}

function obtenerIdDetalleEgreso() {
    $sq = "SELECT max(de.id_detalle_egreso) FROM detalle_egreso de";
    $id = (pg_fetch_row(pg_query($sq))[0] + 1);
    return $id;
}

function guardarEgreso($id, $bodega, $usuario, $comprobante, $origen, $destino, $tarifa0, $tarifa12, $iva, $descuento, $total, $observaciones, $estado) {
    $sql = "INSERT INTO egresos(id_egresos, id_empresa, id_usuario, comprobante, fecha_actual, hora_actual, origen, destino, tarifa0, tarifa12, iva_egreso, descuento_egreso, "
            . "total_egreso, observaciones, estado) "
            . "VALUES ($id, $bodega, $usuario,'$comprobante','" . obtenerFechaActual() . "','" . obtenerHoraActual() . "' ," . ($origen == NULL ? "NULL" : $origen) . " "
            . ", " . ($destino == NULL ? "NULL" : $destino) . ", " . number_format($tarifa0, 4, '.', '') . ", " . number_format($tarifa12, 4, '.', '') . ""
            . ", " . number_format($iva, 4, '.', '') . ", " . number_format($descuento, 4, '.', '') . ", " . number_format($total, 4, '.', '') . ", '$observaciones', '$estado')";
    pg_query($sql);
}

function guardarDetalleEgreso($egreso, $producto, $cantidad, $precio, $descuento, $total, $estado) {
    $sql = "INSERT INTO detalle_egreso(id_detalle_egreso, id_egresos, cod_productos, cantidad, precio_costo, descuento, total, estado) "
            . "VALUES (" . obtenerIdDetalleEgreso() . ", $egreso, $producto, " . number_format($cantidad, 2, '.', '') . ", " . number_format($precio, 4, '.', '') . ""
            . ", " . number_format($descuento, 4, '.', '') . ", " . number_format($total, 4, '.', '') . ", '$estado');";
    pg_query($sql);
}

?>