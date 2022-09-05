<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
error_reporting(0);

if(pg_query("Update promociones Set cod_productos='".strtoupper($_POST['id_promocion'])."', cod_productos_promo='$_POST[id_promocion_pro]', nombre_producto='$_POST[promocion_pro]', cantidad_promocion='$_POST[cantidad_promocion]', pvp_promocion='$_POST[pvp_promocion]' where id_promociones='$_POST[id_promociones_modulo]'")){
   $data = 1;
    // Auditoria
    insert_registro('MODIFICAR PROMO: ' . strtoupper($_POST['id_promocion']));
}
echo $data;
?>
