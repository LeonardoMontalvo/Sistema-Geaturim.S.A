<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
date_default_timezone_set('America/Guayaquil');

// datos detalle factura
$campo1 = $_POST['campo1'];
$campo2 = $_POST['campo2'];
$campo3 = $_POST['campo3'];
$campo4 = $_POST['campo4'];
$campo5 = $_POST['campo5'];
// fin
//contador factura compra
$cont1 = 0;
$consulta = pg_query("select max(id_retencion_fuente_factura_compra) from retencion_fuente_factura_compra");
while ($row = pg_fetch_row($consulta)) {
    $cont1 = $row[0];
}
$cont1++;
// fin
//fecha actual
/* $Digital = new Date();
  $year = Digital.getYear();
  $month = Digital.getMonth();
  $day = Digital.getDay();
  $fecha_actual=$year+":"+$month+":"+$day;
  //hora actual
  $Digital = new Date();
  $hours = Digital.getHours();
  $minutes = Digital.getMinutes();
  $seconds = Digital.getSeconds();
  $hora_actual=$hours+":"+$minutes+":"+$seconds; */
$data = 0;
$valoreten = 0;
$resultreten = 0;
$fecha = date('Y-m-d', time());
$hora = date('h:i:s A', time());
$comprobar = pg_query("select * from retencion_fuente_factura_compra");


//while ($row2 = pg_fetch_row($comprobar)) {
//	if($row2[1]==$_POST[id_gastos] ){  
//		$data=2;
//          
//                
//                
//	}
//}


if ($data != 2) {
 
	
//	
            $contre = 0;
            $consultare = pg_query("select max(id_detalles_compro_reten) from detallecomprobanteretencion");
            while ($row = pg_fetch_row($consultare)) {
                $contre = $row[0];
            }
            $contre++;

    pg_query("insert into retencion_fuente_factura_compra values('" . $cont1 . "', '$_POST[id_factura]', '$_POST[id_retencion_fuente]','$_POST[fecha_actual]','" . $hora . "','$_POST[valor_factura]','$_POST[iva_factura]','$_POST[valor_retencion]', '$_POST[autorizacion_ret]','$_POST[serie_sinretencion]','g' ,'1','c','2','','Activo')");
    pg_query("insert into detallecomprobanteretencion values('$contre','$cont1' ,'$_POST[id_retencion_fuente]','$_POST[valor_factura]','1','0','$_POST[valor_retencion]')");
  $data = 1;
    
    
     $itemuno = array(
        'estado' => $data,
        'id' => $_POST["id_factura"], 'id_reten' => $cont1
    );
    
    
    
    
//     echo '<br>GUARDAR FACTURA VENTA: <br>' . "insert into detallecomprobanteretencion values('$contre','$cont1' ,'$_POST[id_retencion_fuente]','$_POST[valor_factura]','1','0','$_POST[valor_retencion]')";//////////////////////////
//          print_r($data);
//        $valreten=$_POST['valor_retencion'];
//        
//        $valfac=pg_query("select * from factura_compra where id_factura_compra ='$_POST[id_factura]'");
//	$valfacresult=pg_fetch_row($valfac);
//        $resultreten=$valfacresult[19] - $valreten;
//         
////        pg_query("update factura_compra set total_compra='".$resultreten."'  where id_factura_compra='$_POST[id_factura]'");
//        
//        pg_query("update pagos_compra set monto_credito='".$resultreten."' , saldo='".$resultreten."' where id_factura_compra='$_POST[id_factura]'");
//                  
//        
//        pg_query("update detalle_transaccion set credito='".$caja."' where id_detalle_transaccion='".$s[1]."'");
//        
//	$tran=pg_query("select * from transacciones where comprobante='$_POST[id_factura]' and id_tipo_transaccion='1' and concepto like 'COM%'");
//	$fila=pg_fetch_row($tran);
//	$iddettran=pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
//	$fila1=pg_fetch_row($iddettran);
//	$fila1[0]=$fila1[0]+1;
//	$cons=pg_query("select cuenta_credito from retencion_fuentes where id_retencion_fuentes='$_POST[id_retencion_fuente]'");
//	$cont2=pg_fetch_row($cons);
////	pg_query("insert into detalle_transaccion values('".$fila1[0]."','$fila1','".$cont2[0]."','$_POST[valor_retencion]','0.000','Activo')");
//	$x=$_POST['valor_retencion'];
//	$plancaja=pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
//	$caja=pg_fetch_row($plancaja);
//	$tot=pg_query("select credito, id_detalle_transaccion from detalle_transaccion where id_transacciones='".$fila[0]."' and id_plan_cuentas='".$caja[0]."'");
//	$s=pg_fetch_row($tot);
//	$caja=$s[0]-$x;
//	pg_query("update detalle_transaccion set credito='".$caja."' where id_detalle_transaccion='".$s[1]."'");
  
}


 echo $data = json_encode($itemuno);
?>