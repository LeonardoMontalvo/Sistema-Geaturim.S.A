<?php

session_start();
include '../../procesos/base.php';
require_once '../../procesos/fecha.php';
include '../../procesos/funciones.php';
$conexion_ori = conectarse_ori();
conectarse_ori();
error_reporting(0);

//////////////////////////////////////////INSERTAR TABLA PRODUCTOS////////////
//$sql = "SELECT cod_productos, codigo, cod_barras, articulo, iva, series, precio_compra, 
//       utilidad_minorista, utilidad_mayorista, iva_minorista, iva_mayorista, 
//       categoria, marca, stock, stock_minimo, stock_maximo, fecha_creacion, 
//       caracteristicas, observaciones, descuento, estado, inventariable, 
//       existencia, diferencia, imagen, id_bodega, incluye_iva, iva_negocio, 
//       id_plan_cuentas, proveedor, cantidad_descuento, utilidad_negocio, 
//       id_usuario, bien_servicios
//  FROM productos where estado='Activo' order by cod_productos;
//;
// ";
//
//
//$conexion_ori = conectarse_ori();
//$resultDPB = pg_query($conexion_ori,$sql);
//if (pg_num_rows($resultDPB) > 0) {
//    while ($rowDPB = pg_fetch_assoc($resultDPB)) {
//
//        $conexion = conectarse();
//        
//        
//        if($rowDPB[iva]=='Si'){
//            $id_timpu='1';
//            $id_taimpuesto='2';           
//            
//        }
//        if($rowDPB[iva]=='No'){
//            $id_timpu='4';
//            $id_taimpuesto='1';           
//            
//        }
//        
//        
//        
//        
//
//        $sql="insert into productos values(". obtenerId_id() . ",'$rowDPB[codigo]','$rowDPB[cod_barras]','$rowDPB[articulo]','$rowDPB[iva]','$rowDPB[series]','$rowDPB[precio_compra]'," . ($rowDPB[utilidad_minorista] == NULL ? "0.0000" : $rowDPB[utilidad_minorista]) . "," . ($rowDPB[utilidad_mayorista] == NULL ? "0.0000" : $rowDPB[utilidad_mayorista]) . ",'$rowDPB[iva_minorista]','$rowDPB[iva_mayorista]','$rowDPB[categoria]','$rowDPB[marca]','$rowDPB[stock]','$rowDPB[stock_minimo]','$rowDPB[stock_maximo]','$rowDPB[fecha_creacion]','$rowDPB[caracteristicas]','$rowDPB[observaciones]','0','$rowDPB[estado]','$rowDPB[inventariable]','$rowDPB[existencia]','$rowDPB[diferencia]','$rowDPB[imagen]','$rowDPB[id_bodega]','$rowDPB[incluye_iva]'," . ($rowDPB[iva_negocio] == NULL ? "0.0000" : $rowDPB[iva_negocio]) . ",'$rowDPB[id_plan_cuentas]','$rowDPB[proveedor]','0','0.0000','1','$id_timpu','$id_taimpuesto','$rowDPB[bien_servicios]','0','0','0','0')";
//  
//        
////             echo '//'."insert into productos values(". obtenerId_id() . ",'$rowDPB[codigo]','$rowDPB[cod_barras]','$rowDPB[articulo]','$rowDPB[iva]','$rowDPB[series]','$rowDPB[precio_compra]'," . ($rowDPB[utilidad_minorista] == NULL ? "0.0000" : $rowDPB[utilidad_minorista]) . "," . ($rowDPB[utilidad_mayorista] == NULL ? "0.0000" : $rowDPB[utilidad_mayorista]) . ",'$rowDPB[iva_minorista]','$rowDPB[iva_mayorista]','$rowDPB[categoria]','$rowDPB[marca]','$rowDPB[stock]','$rowDPB[stock_minimo]','$rowDPB[stock_maximo]','$rowDPB[fecha_creacion]','$rowDPB[caracteristicas]','$rowDPB[observaciones]','$rowDPB[descuento]','$rowDPB[estado]','$rowDPB[inventariable]','$rowDPB[existencia]','$rowDPB[diferencia]','$rowDPB[imagen]','$rowDPB[id_bodega]','$rowDPB[incluye_iva]'," . ($rowDPB[iva_negocio] == NULL ? "0.0000" : $rowDPB[iva_negocio]) . ",'$rowDPB[id_plan_cuentas]','$rowDPB[proveedor]','0','0.0000','1','$id_timpu','$id_taimpuesto','$rowDPB[bien_servicios]','0','0','0','0')";
////      
//        
//        
//          $conexion = conectarse();
//        
//              $guardar = guardarSql($conexion, $sql);
//       
//            if ($guardar == 'true') {
//               
//                
//            } else {   
//                
//                 echo '//error'."insert into productos values(". obtenerId_id() . ",'$rowDPB[codigo]','$rowDPB[cod_barras]','$rowDPB[articulo]','$rowDPB[iva]','$rowDPB[series]','$rowDPB[precio_compra]'," . ($rowDPB[utilidad_minorista] == NULL ? "0.0000" : $rowDPB[utilidad_minorista]) . "," . ($rowDPB[utilidad_mayorista] == NULL ? "0.0000" : $rowDPB[utilidad_mayorista]) . ",'$rowDPB[iva_minorista]','$rowDPB[iva_mayorista]','$rowDPB[categoria]','$rowDPB[marca]','$rowDPB[stock]','$rowDPB[stock_minimo]','$rowDPB[stock_maximo]','$rowDPB[fecha_creacion]','$rowDPB[caracteristicas]','$rowDPB[observaciones]','0','$rowDPB[estado]','$rowDPB[inventariable]','$rowDPB[existencia]','$rowDPB[diferencia]','$rowDPB[imagen]','$rowDPB[id_bodega]','$rowDPB[incluye_iva]'," . ($rowDPB[iva_negocio] == NULL ? "0.0000" : $rowDPB[iva_negocio]) . ",'$rowDPB[id_plan_cuentas]','$rowDPB[proveedor]','0','0.0000','1','$id_timpu','$id_taimpuesto','$rowDPB[bien_servicios]','0','0','0','0')";
//                
//                
//            } 
//             
//             
//             
//             
//        }
//}
//  $conexion = conectarse();
//function obtenerId_id() {
//    $conexion = conectarse();
//
//    $consulta = pg_query($conexion,"select max(cod_productos) from productos");
//    $id = (pg_fetch_row($consulta)[0] + 1);
//    return $id;
//}

// conectarse_p();
// $sql="select P.cod_productos, P.codigo, P.articulo, dpb.stock from public.productos P,  public.detalle_producto_bodega dpb where  P.cod_productos=dpb.cod_productos  and P.estado ='Activo' and  imagen  ";
// echo '::'."select P.cod_productos, P.codigo, P.articulo, dpb.stock, P.iva, P.incluye_iva, P.inventariable from productos P,  detalle_producto_bodega dpb where  P.cod_productos=dpb.cod_productos  and P.estado ='Activo'   ";
  conectarse_ori();
  $sql="select P.cod_productos, P.codigo, P.articulo, dpb.stock from productos P,  detalle_producto_bodega dpb where  P.cod_productos=dpb.cod_productos  and P.estado ='Activo' order by dpb.cod_productos   ";
//$sql = "SELECT * FROM productos  ";

$resultDPB = pg_query($conexion_ori,$sql);
if (pg_num_rows($resultDPB) > 0) {
    while ($rowDPB = pg_fetch_assoc($resultDPB)) {
         $conexion = conectarse();
        $updateDetProdBod = "INSERT INTO detalle_producto_bodega (id_detalle_productos_bodega, cod_productos, id_bodega, id_usuario, fecha, hora, stock) "
        . "VALUES(" . obtenerId() . ", $rowDPB[cod_productos], '1', " . $_SESSION['id'] . ", '" . obtenerFechaActual() . "', '" . obtenerHoraActual() . "', "
        . "" . number_format($rowDPB[stock], 2, '.', '') . ")";
        pg_query($updateDetProdBod);
        
      
    }
}
function obtenerId()
{
    $conexion = conectarse();

    $consulta = pg_query($conexion,"select max(id_detalle_productos_bodega) from detalle_producto_bodega");
    $id = (pg_fetch_row($consulta)[0] + 1);
    return $id;
}
?>