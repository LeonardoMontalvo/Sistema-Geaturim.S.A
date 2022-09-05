<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

/////////////////modificar clientes////////////////////
if (pg_query("Update ambiente Set nombre_ambi='$_POST[nombre_ambi]', codigo_ambi='$_POST[codigo_ambi]', estado_ambi='$_POST[estado_tipo_ambiente]'  where id_ambi='$_POST[id_ambi]'")){
$data = 1;	
}
if($data == 1 && $_POST[id_ambi]==1 && $_POST[estado_tipo_ambiente]=='Inactivo'){
 
    pg_query("Update ambiente Set  estado_ambi='Activo'  where id_ambi='2'");
    
}else{
    
    if($data == 1 && $_POST[id_ambi]==2 && $_POST[estado_tipo_ambiente]=='Inactivo'){
 
    pg_query("Update ambiente Set  estado_ambi='Activo'  where id_ambi='1'");
    
}
    
}

echo $data;
?>
