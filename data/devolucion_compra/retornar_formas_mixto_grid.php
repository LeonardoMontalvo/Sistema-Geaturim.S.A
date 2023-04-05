<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['com'];
$arr_data = array();

$consulta = pg_query("
SELECT dcr.forma_pago,dcr.tarjeta_credito,dcr.numero_documento,dcr.valor,pc.descripcion,dcr.fecha_actual
FROM devolucion_compra r
inner JOIN formas_pago_mixto_nc dcr
ON r.id_devolucion_compra=dcr.id_devolucion_compra
left JOIN plan_cuentas pc
ON dcr.id_cuenta::int=pc.id_plan_cuentas

where dcr.tipo_documento='FACTURA' and r.id_devolucion_compra ='".$id."'");
while ($row = pg_fetch_row($consulta)) {
    
    $arr_data[] = $row[0];   
    $arr_data[] = $row[1];    
    $arr_data[] = $row[2];
    $arr_data[] = $row[3];
    $arr_data[] = $row[4];
      $arr_data[] = $row[5];
 
   
}
echo json_encode($arr_data);
?>
