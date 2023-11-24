<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id = $_GET['com'];
$arr_data = array();

//echo 'retornar_mixto_nota'."SELECT dcr.forma_pago,dcr.tarjeta_credito,dcr.numero_documento,dcr.valor,pc.descripcion FROM facturas_novalidas r inner JOIN formas_pago_mixto dcr ON r.id_facturas_novalidas=dcr.id_factura_venta left JOIN plan_cuentas pc ON dcr.id_cuenta::int=pc.id_plan_cuentas where  dcr.tipo_documento='NOTA' and r.id_facturas_novalidas ='".$id."'";
$consulta = pg_query("SELECT dcr.forma_pago,dcr.tarjeta_credito,dcr.numero_documento,dcr.valor,pc.descripcion FROM facturas_novalidas r inner JOIN formas_pago_mixto dcr ON r.id_facturas_novalidas=dcr.id_factura_venta left JOIN plan_cuentas pc ON dcr.id_cuenta::int=pc.id_plan_cuentas where  dcr.tipo_documento='NOTA' and r.id_facturas_novalidas ='".$id."' and dcr.estado='Activo'  ");
while ($row = pg_fetch_row($consulta)) {
    
    $arr_data[] = $row[0];   
    $arr_data[] = $row[1];    
    $arr_data[] = $row[2];
    $arr_data[] = $row[3];
    $arr_data[] = $row[4];
 
   
}
echo json_encode($arr_data);
?>
