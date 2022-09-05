<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);

/////////////////modificar clientes////////////////////
if (pg_query("Update tipo_emision Set nombre_temision='$_POST[nombre_temision]', codigo_temision='$_POST[codigo_temision]', estado_temision='$_POST[estado_tipo_emision]' where id_temision='$_POST[id_temision]'")){

$data = 1;	
}
//////////////////////////////////////////////////////
if($data == 1 && $_POST[id_temision]==1 && $_POST[estado_tipo_emision]=='Inactivo'){
 
    pg_query("Update tipo_emision Set  estado_temision='Activo'  where id_temision='2'");
    
}else{
    
    if($data == 1 && $_POST[id_temision]==2 && $_POST[estado_tipo_emision]=='Inactivo'){
 
    pg_query("Update tipo_emision Set  estado_temision='Activo'  where id_temision='1'");
    
}
}
echo $data;
?>
