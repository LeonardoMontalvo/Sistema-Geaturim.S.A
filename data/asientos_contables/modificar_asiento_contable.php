<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$conpuntoresult = $_SESSION['PV'];
    // modificar asiento contable


//	 echo '<br>GUARDAR FACTURA VENTA: <br>' . "Update transacciones Set id_usuario = '$_SESSION[id]', fecha_actual = '$_POST[fecha_actual]', hora_actual = '$_POST[hora_actual]', 
//    concepto = '$_POST[concepto]', total_debe = '$_POST[total_debe]', total_haber = '$_POST[total_haber]', saldo = '$_POST[diferencia]', 
//    id_tipo_transaccion = '$_POST[id_tipo_transaccion]', num_transaccion = '$_POST[num_transaccion]', estado = 'Activo' where id_transacciones='$_POST[id_asiento_contable]' and id_empresa='$conpuntoresult'";//////////////////////////
//	 
//	 

    pg_query("Update transacciones Set id_usuario = '$_SESSION[id]', fecha_actual = '$_POST[fecha_actual]', hora_actual = '$_POST[hora_actual]', 
    concepto = '$_POST[concepto]', total_debe = '$_POST[total_debe]', total_haber = '$_POST[total_haber]', saldo = '$_POST[diferencia]', 
    id_tipo_transaccion = '$_POST[id_tipo_transaccion]', num_transaccion = '$_POST[num_transaccion]', estado = 'Activo', fecha_registro = '$_POST[fecha_registro]' where id_transacciones='$_POST[id_asiento_contable]' and id_empresa='$conpuntoresult'");
    // fin
//  	 echo '<br>GUARDAR FACTURA VENTA cuentas por cobrar: <br>' . "DELETE FROM  detalle_transaccion where id_transacciones = '$_POST[id_asiento_contable]'  and id_empresa='$conpuntoresult'";//////////////////////////
//	 
    // eliminar detalle asiento contable
    pg_query("DELETE FROM  detalle_transaccion where id_transacciones = '$_POST[id_asiento_contable]' ");
    // fin  

    
//DETALLE ASIENTO CONTABLE
$campo1 = $_POST['campo1'];
$campo2 = $_POST['campo2'];
$campo3 = $_POST['campo3'];
$campo4 = $_POST['campo4'];
$campo5 = $_POST['campo5'];

$arreglo1 = explode('|', $campo1);
$arreglo2 = explode('|', $campo2);
$arreglo3 = explode('|', $campo3);
$arreglo4 = explode('|', $campo4);
$arreglo5 = explode('|', $campo5);

$nelem = count($arreglo1);

for ($i = 0; $i <= $nelem; $i++) {
    $iddettran=pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
    $fila1=pg_fetch_row($iddettran);
    $fila1[0]=$fila1[0]+1;
    
    
    
    pg_query("insert into detalle_transaccion values('".$fila1[0]."','$_POST[id_asiento_contable]','".$arreglo1[$i]."','".$arreglo4[$i]."','".$arreglo5[$i]."','Activo')");
}

$data=1;

echo $_POST['id_asiento_contable'];
?>
