<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$data = "";
$data1 = "";
$data2 = "";
$pro_promo = $_GET['cod_producto'];

$empresa = pg_query("select cod_productos,stock from productos  where productos.estado='Activo' and cod_productos='$pro_promo'");
    while ($row1 = pg_fetch_row($empresa)) {
        $data = $data . $row1[0];       
        $data = $data . '*' . $row1[1];
    }

////////////////////////////////
echo $data;
?>
