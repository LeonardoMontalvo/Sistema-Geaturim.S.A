<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
    echo '<br>GUARDAR FACTURA VENTA: <br>' ."Update empleado Set identificacion='$_POST[ruc_ci]', nombres_empleado='$_POST[nombres_nomina]', direccion_empleado='".strtoupper($_POST['direccion_nomina'])."', telefono='$_POST[nro_telefono]', celular='$_POST[nro_celular]', pais='$_POST[pais_nomina]', ciudad='$_POST[ciudad_nomina]', correo='$_POST[email]', id_cargo='$_POST[tipo_cargo]' ,fecha_nacimiento='$_POST[fecha_nacimiento]', notas='$_POST[notas_nomina]', referencia='$_POST[referencia_nomina]', etnia='$_POST[etnia]',sexo='$_POST[genero]',afiliacion='$_POST[afiliado]',fecha_ingreso_empleado='$_POST[fecha_ingreso]',fecha_salida='$_POST[fecha_salida]',tele_referencia_nomina='$_POST[tele_referencia_nomina]',decimo='$_POST[decimo]' where id_empleado='$_POST[id_empleado]'"; //////////////////////////

/////////////////modificar clientes////////////////////
if (pg_query("Update empleado Set identificacion='$_POST[ruc_ci]', nombres_empleado='$_POST[nombres_nomina]', direccion_empleado='".strtoupper($_POST['direccion_nomina'])."', telefono='$_POST[nro_telefono]', celular='$_POST[nro_celular]', pais='$_POST[pais_nomina]', ciudad='$_POST[ciudad_nomina]', correo='$_POST[email]', id_cargo='$_POST[tipo_cargo]' ,fecha_nacimiento='$_POST[fecha_nacimiento]', notas='$_POST[notas_nomina]', referencia='$_POST[referencia_nomina]', etnia='$_POST[etnia]',sexo='$_POST[genero]',afiliacion='$_POST[afiliado]',fecha_ingreso_empleado='$_POST[fecha_ingreso]',fecha_salida='$_POST[fecha_salida]',tele_referencia_nomina='$_POST[tele_referencia_nomina]',decimo='$_POST[decimo]' where id_empleado='$_POST[id_empleado]'")){
$data = 1;	
}
//////////////////////////////////////////////////////

echo $data;
?>
