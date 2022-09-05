<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
error_reporting(0);

$cont = 0;
$tipo_val=0;
$consulta = pg_query("select max(id_proveedor) from proveedores");
while ($row = pg_fetch_row($consulta)) {
    $cont = $row[0];
}
$cont++;
 $tipo_val = $_POST['tipo_docu'];
if($tipo_val == "Cedula"){
      $tipo_val=2;
}
if($tipo_val == "Ruc"){
    $tipo_val=1;
}
if($tipo_val == "Pasaporte"){
    $tipo_val=3;
}
 $tipo_val1 = $_POST['tipo_docu'];

if($tipo_val1 == "2"){
      $tipo_val1='Cedula';
}
if($tipo_val == "1"){
    $tipo_val1='Ruc';
}
if($tipo_val == "3"){
    $tipo_val1='Pasaporte';
}
  
//	 echo '<br>GUARDAR FACTURA VENTA: <br>' . "insert into proveedores values('$cont','$_POST[tipo_docu]','$_POST[ruc_ci]','".strtoupper($_POST[empresa_pro])."','".strtoupper($_POST[representante_legal])."','".strtoupper($_POST[visitador])."','$_POST[direccion_pro]','$_POST[nro_telefono]','$_POST[nro_celular]','$_POST[fax]','".strtoupper($_POST[pais_pro])."','".strtoupper($_POST[ciudad_pro])."','$_POST[forma_pago]','$_POST[correo]','$_POST[principal_pro]','$_POST[tipo_pro]','$_POST[cupo_credito]','$_POST[observaciones_pro]','Activo','1','$tipo_val')";//////////////////////////
//	 
	 

pg_query("insert into proveedores values('$cont','$tipo_val1','$_POST[ruc_ci]','".strtoupper($_POST[empresa_pro])."','".strtoupper($_POST[representante_legal])."','".strtoupper($_POST[visitador])."','$_POST[direccion_pro]','$_POST[nro_telefono]','$_POST[nro_celular]','$_POST[fax]','".strtoupper($_POST[pais_pro])."','".strtoupper($_POST[ciudad_pro])."','$_POST[forma_pago]','$_POST[correo]','$_POST[principal_pro]','$_POST[tipo_pro]','$_POST[cupo_credito]','$_POST[observaciones_pro]','Activo','1','$tipo_val')");
$data = 1;
// Auditoria
insert_registro('CREACION PROVEEDOR: ' . $_POST['empresa_pro'] . ' CON RUC: ' . $_POST['ruc_ci']);
echo $data;
?>
