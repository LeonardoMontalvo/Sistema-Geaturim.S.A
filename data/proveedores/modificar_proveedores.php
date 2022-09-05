<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
error_reporting(0);
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
if(pg_query("Update proveedores Set tipo_documento='$_POST[tipo_docu]', identificacion_pro='$_POST[ruc_ci]',empresa_pro='".strtoupper($_POST['empresa_pro'])."',representante_legal='".strtoupper($_POST['representante_legal'])."', visitador='".strtoupper($_POST['visitador'])."', direccion_pro='$_POST[direccion_pro]', telefono='$_POST[nro_telefono]', celular='$_POST[nro_celular]', fax='$_POST[fax]', pais='".strtoupper($_POST['pais_pro'])."', ciudad='".strtoupper($_POST['ciudad_pro'])."', forma_pago='$_POST[forma_pago]', correo='$_POST[correo]', principal='$_POST[principal_pro]', tipo_proveedor='$_POST[tipo_pro]', credito_cupo='$_POST[cupo_credito]', observaciones='$_POST[observaciones_pro]', estado='Activo',id_plan_cuentas='1',id_tdocu=$tipo_val  where id_proveedor='$_POST[id_proveedor]'")){
   $data = 1;
    // Auditoria
    insert_registro('MODIFICACION PROVEEDOR: ' . $_POST['empresa_pro'] . ' CON RUC: ' . $_POST['ruc_ci'] . ' DE: ' . $_POST['representante_legal']);
}

echo $data;
?>
