<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$data = "";
$data1 = "";
$data2 = "";
$pro_promo = $_GET['cod_producto'];

$empresa = pg_query("select promociones.cod_productos_promo,stock from productos inner join promociones
on productos.cod_productos=promociones.cod_productos where productos.estado='Activo' and promociones.estado='Activo' and promociones.cod_productos='$pro_promo'");
while ($row = pg_fetch_row($empresa)) {
    $data1 = $row[0];
    $data2 = $row[1];
//echo '<br>GUARDAR FACTURA VENTA: <br>' . "select productos.cod_productos,productos.codigo,articulo,cantidad_promocion,pvp_promocion,iva,productos.incluye_iva,productos.stock
//from productos inner join promociones
//on productos.cod_productos=promociones.cod_productos_promo where productos.estado='Activo' 
//and promociones.estado='Activo'
// and promociones.cod_productos_promo='$data1'
// and promociones.cod_productos='$pro_promo'";

    $empresa1 = pg_query("select productos.cod_productos,productos.codigo,articulo,cantidad_promocion,pvp_promocion,iva,productos.incluye_iva,productos.stock
from productos inner join promociones
on productos.cod_productos=promociones.cod_productos_promo where productos.estado='Activo' 
and promociones.estado='Activo'
 and promociones.cod_productos_promo='$data1'
 and promociones.cod_productos='$pro_promo'");
    while ($row = pg_fetch_row($empresa1)) {
        $arr_data[] = $row[0]; //COD_PRODUCTOS
        $arr_data[] = $row[1]; //CODIGO
        $arr_data[] = $row[2]; //NOMBRE ARTICULO
        $arr_data[] = $row[3]; //CANTIDAD PROMOCION

        if ($row[4] != "" || $row[4] != "0.00" ) {
            $arr_data[] = $row[4]; //PVP_PROMOCION
        } else {
            $arr_data[] = $row[4]; //PVP_PROMOCION
        }


        $arr_data[] = $row[5]; //IVA
        $arr_data[] = $row[6]; //INCLUYE IVA
        $arr_data[] = data2; //STOKC
    }
}
////////////////////////////////
echo json_encode($arr_data);
?>
