<?php

session_start();
include '../../procesos/base.php';
require_once '../../procesos/fecha.php';
$conexion = conectarse();
conectarse();
error_reporting(0);

// conectarse_p();
// $sql="select P.cod_productos, P.codigo, P.articulo, dpb.stock from public.productos P,  public.detalle_producto_bodega dpb where  P.cod_productos=dpb.cod_productos  and P.estado ='Activo' and  imagen  ";
// echo '::'."select P.cod_productos, P.codigo, P.articulo, dpb.stock, P.iva, P.incluye_iva, P.inventariable from productos P,  detalle_producto_bodega dpb where  P.cod_productos=dpb.cod_productos  and P.estado ='Activo'   ";
$sql = "SELECT * FROM productos where  imagen='1'";

$resultDPB = pg_query($sql);
if (pg_num_rows($resultDPB) > 0) {
    while ($rowDPB = pg_fetch_assoc($resultDPB)) {
      
        $updateDetProdBod = "INSERT INTO detalle_producto_bodega (id_detalle_productos_bodega, cod_productos, id_bodega, id_usuario, fecha, hora, stock) "
        . "VALUES(" . obtenerId() . ", $rowDPB[cod_productos], '1', " . $_SESSION['id'] . ", '" . obtenerFechaActual() . "', '" . obtenerHoraActual() . "', "
        . "" . number_format($rowDPB[stock], 2, '.', '') . ")";
        pg_query($updateDetProdBod);
        
      
    }
}
function obtenerId()
{
    $conexion = conectarse();

    $consulta = pg_query("select max(id_detalle_productos_bodega) from detalle_producto_bodega");
    $id = (pg_fetch_row($consulta)[0] + 1);
    return $id;
}


?>