<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

if ($_POST[tipo_docu] == '1') {
    $tipo = 'Ruc';
} else {
    if ($_POST[tipo_docu] == '2') {
        $tipo = 'Cedula';
    } else {
        if ($_POST[tipo_docu] == '3') {
            $tipo = 'Pasaporte';
        } else {
            if ($_POST[tipo_docu] == '5') {
                $tipo = 'Identificacion del Exterior';
            }
        }
    }
}
//echo '//'.$_POST[tipo_docu];
if($_POST[tipo_docu]!='Seleccione tipo Documento'){
//    echo '///'."Update transportista Set  identificacion='$_POST[identificacion]',nombres_trans='".strtoupper($_POST['nombres_trans'])."', direccion_trans='".strtoupper($_POST['direccion_trans'])."',  telefono='$_POST[nro_telefono]', celular='$_POST[celular]', num_placa='$_POST[num_placa]', id_tdocu='$_POST[tipo_docu]'  where id_transportista='$_POST[id_transportista]'";
 
   if(pg_query("Update transportista Set  identificacion='$_POST[identificacion]',nombres_trans='".strtoupper($_POST['nombres_trans'])."', direccion_trans='".strtoupper($_POST['direccion_trans'])."',  telefono='$_POST[nro_telefono]', celular='$_POST[celular]', num_placa='$_POST[num_placa]', id_tdocu='$_POST[tipo_docu]'  where id_transportista='$_POST[id_transportista]'")){



   $data = 1;
}  
}else{
 if(pg_query("Update transportista Set  identificacion='$_POST[identificacion]',nombres_trans='".strtoupper($_POST['nombres_trans'])."', direccion_trans='".strtoupper($_POST['direccion_trans'])."',  telefono='$_POST[nro_telefono]', celular='$_POST[celular]', num_placa='$_POST[num_placa]' where id_transportista='$_POST[id_transportista]'")){
   $data = 1;
}   
}


echo $data;
?>
