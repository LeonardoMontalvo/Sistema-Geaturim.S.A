

<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$data = "";
$producto = $_GET['id'];
$pvinv = $_SESSION['PV_INV'];
//////////////////////////  
//guardar cuentas contables/////
//echo ''."SELECT dpb.stock,dpb.cod_productos,p.inventariable FROM productos p left join detalle_producto_bodega dpb on p.cod_productos=dpb.cod_productos  WHERE dpb.cod_productos=$producto AND dpb.id_bodega=$_SESSION[PV]";
$empresa = pg_query("SELECT dpb.stock,dpb.cod_productos,p.inventariable FROM productos p left join detalle_producto_bodega dpb on p.cod_productos=dpb.cod_productos  WHERE dpb.cod_productos=$producto AND dpb.id_bodega=$pvinv");
while ($row = pg_fetch_row($empresa)) {
    $data = $data . $row[0];  
    $data = $data . ',' . $row[1];
       $data = $data . ',' . $row[2];
}
echo json_encode($data);
?>

