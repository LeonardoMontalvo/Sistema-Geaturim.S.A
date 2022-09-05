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
$consulta = pg_query("select max(id_retencion_iva_factura_compra) from retencion_iva_factura_compra");
while ($row = pg_fetch_row($consulta)) {
    $cont1 = $row[0];
}
$cont1++;
// fin
//fecha actual
/*$Digital = new Date();
$year = Digital.getYear();
$month = Digital.getMonth();
$day = Digital.getDay();
$fecha_actual=$year+":"+$month+":"+$day;
//hora actual
$Digital = new Date();
$hours = Digital.getHours();
$minutes = Digital.getMinutes();
$seconds = Digital.getSeconds();
$hora_actual=$hours+":"+$minutes+":"+$seconds;*/
$data=0;
$fecha = date('Y-m-d', time());
$hora=date('h:i:s A', time());
$comprobar = pg_query("select id_factura from retencion_iva_factura_compra");
while ($row2 = pg_fetch_row($comprobar)) {
	if($row2[0]==$_POST[id_factura]){
		$data=2;
	}
}
if($data!=2){
	pg_query("insert into retencion_iva_factura_compra values('".$cont1."', '$_POST[id_factura]', '$_POST[id_retencion_iva]','".$fecha."','".$hora."','$_POST[valor_factura]','$_POST[iva_factura]','$_POST[valor_retencion]', '$_POST[autorizacion_ret]')");
	$tran=pg_query("select * from transacciones where comprobante='$_POST[id_factura]' and id_tipo_transaccion='1' and concepto like 'COM%'");
	$fila=pg_fetch_row($tran);
	$iddettran=pg_query("select max(id_detalle_transaccion) from detalle_transaccion");
	$fila1=pg_fetch_row($iddettran);
	$fila1[0]=$fila1[0]+1;
	$cons=pg_query("select cuenta_credito from retencion_iva where id_retencion_iva='$_POST[id_retencion_iva]'");
	$cont2=pg_fetch_row($cons);
	pg_query("insert into detalle_transaccion values('".$fila1[0]."','".$fila[0]."','".$cont2[0]."','0.000','$_POST[valor_retencion]','Activo')");
	$x=$_POST['valor_retencion'];
	$plancaja=pg_query("select cuenta_debito from parametros where descripcion='CAJA GENERAL'");
	$caja=pg_fetch_row($plancaja);
	$tot=pg_query("select credito, id_detalle_transaccion from detalle_transaccion where id_transacciones='".$fila[0]."' and id_plan_cuentas='".$caja[0]."'");
	$s=pg_fetch_row($tot);
	$caja=$s[0]-$x;
	pg_query("update detalle_transaccion set credito='".$caja."' where id_detalle_transaccion='".$s[1]."'");
	$data=1;
}

echo $data;

?>
