<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
///////////////////contador empleado////////////////////////
$cont = 0;
$valorid = 0;
$consulta = pg_query("select max(id_empleado) from empleado");
while ($row = pg_fetch_row($consulta)) {
    $cont = $row[0];
}
$cont++;
///////////////////////////////////////////////////////////
//	 echo '<br>GUARDAR FACTURA VENTA: <br>' . "insert into empleado values('$cont','$_POST[ruc_ci]','".strtoupper($_POST['nombres_nomina'])."','$_POST[direccion_nomina]','$_POST[nro_telefono]','$_POST[nro_celular]','".strtoupper($_POST['pais_nomina'])."','$_POST[ciudad_nomina]','$_POST[email]','$_POST[tipo_cargo]','1','$_POST[fecha_actual]','$_POST[fecha_nacimiento]','Activo','$_POST[notas_nomina]','$_POST[referencia_nomina]','$_POST[etnia]','$_POST[genero]','$_POST[afiliado]')";//////////////////////////
////	 

if (pg_query("insert into empleado values('$cont','$_POST[ruc_ci]','".strtoupper($_POST['nombres_nomina'])."','$_POST[direccion_nomina]','$_POST[nro_telefono]','$_POST[nro_celular]','".strtoupper($_POST['pais_nomina'])."','$_POST[ciudad_nomina]','$_POST[email]','$_POST[tipo_cargo]','1','$_POST[fecha_actual]','$_POST[fecha_nacimiento]','Activo','$_POST[notas_nomina]','$_POST[referencia_nomina]','$_POST[etnia]','$_POST[genero]','$_POST[afiliado]','$_POST[fecha_ingreso]','$_POST[fecha_salida]','$_POST[tele_referencia_nomina]','$_POST[decimo]','$_POST[fondos_reserva]','$_POST[fondos_acu_mensual]')")) {
    $data = 1;
   
}

echo $data;
?>
