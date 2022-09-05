<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$data = "";
$data1 = "";
$data2 = "";
$pro_promo = $_GET['cod_producto'];



//////////////////////////  
//guardar cuentas contables/////
$empresa = pg_query("select promociones.cod_productos_promo,stock from productos inner join promociones
on productos.cod_productos=promociones.cod_productos where productos.estado='Activo' and promociones.estado='Activo' and promociones.cod_productos='$pro_promo'");
while ($row = pg_fetch_row($empresa)) {
    $data1 = $row[0];
    $data2 = $row[1];
  

//	 echo '<br>GUARDAR FACTURA VENTA: <br>' . "select productos.cod_productos,productos.codigo,articulo,cantidad_promocion,pvp_promocion,iva
//
//from productos inner join promociones
//on productos.cod_productos=promociones.cod_productos where productos.estado='Activo' and promociones.estado='Activo' and productos.cod_productos='$data1'";//////////////////////////
//	 
$empresa1 = pg_query("select productos.cod_productos,productos.codigo,articulo,cantidad_promocion,pvp_promocion,iva,productos.incluye_iva,productos.stock
from productos inner join promociones
on productos.cod_productos=promociones.cod_productos_promo where productos.estado='Activo' 
and promociones.estado='Activo'
 and promociones.cod_productos_promo='$data1'
 and promociones.cod_productos='$pro_promo'");
while ($row1 = pg_fetch_row($empresa1)) {
    $data = $data . $row1[0];
    $data = $data . '*' . $row1[1];
    $data = $data . '*' . $row1[2];
    $data = $data . '*' . $row1[3];
    $data = $data . '*' . $row1[4];
    $data = $data . '*' . $row1[5];
     $data = $data . '*' . $row1[6];
        $data = $data . '*' . $data2;

}
}
////////////////////////////////
echo $data;
?>
