<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
$cont = 0;
date_default_timezone_set('America/Lima');
$fecha = date("j/n/Y");
$contrasena = md5($_POST['password_usuario']);

if ($_POST['oper'] == "add") {
    $consulta = pg_query("select max(id_usuario) from usuario");
    while ($row = pg_fetch_row($consulta)) {
        $cont = $row[0];
    }
    $cont++;
/////////////////////////////////////////////
    $conpunto = 1;
    $consultapunto = pg_query("select max(id_punto_venta_empresa) from punto_venta_empresa where punto_venta_empresa.id_usuario='$_SESSION[id]'");
    while ($row = pg_fetch_row($consultapunto)) {
        $conpunto = $row[0];
    }

    $conpuntoresult = 1;
    $consultapuntoresult = pg_query("select id_punto_venta from punto_venta_empresa where id_punto_venta_empresa='$conpunto'");
    while ($row = pg_fetch_row($consultapuntoresult)) {
        $conpuntoresult = $row[0];
    }



    //////////////////////////////////////////////////
//    	 echo '<br>GUARDAR FACTURA VENTA: <br>' . "insert into usuario values('$cont','$_POST[nombre_usuario]','$_POST[apellido_usuario]','$_POST[ci_usuario]','$_POST[telefono_usuario]','$_POST[celular_usuario]','1','$contrasena','$_POST[email_usuario]','$_POST[direccion_usuario]','$_POST[user]','Activo','','$_POST[hora_entrada]','$_POST[hora_salida]','$conpuntoresult','Activo','$fecha')";//////////////////////////
//	 

    pg_query("insert into usuario values('$cont','$_POST[nombre_usuario]','$_POST[apellido_usuario]','$_POST[ci_usuario]','$_POST[telefono_usuario]','$_POST[celular_usuario]','$_POST[cargo_usuario]','$contrasena','$_POST[email_usuario]','$_POST[direccion_usuario]','$_POST[user]','Activo','','$_POST[hora_entrada]','$_POST[hora_salida]','$conpuntoresult','Activo','$fecha')");
    insert_registro('CREACION USUARIO: ' . $_POST['user']);
} elseif ($_POST['oper'] == "edit") {
    pg_query("update usuario set id_usuario='$_POST[id_usuario]', nombre_usuario='$_POST[nombre_usuario]', apellido_usuario='$_POST[apellido_usuario]', id_cargo_usuario='$_POST[cargo_usuario]', ci_usuario='$_POST[ci_usuario]', telefono_usuario='$_POST[telefono_usuario]', celular_usuario='$_POST[celular_usuario]', clave='$contrasena', email_usuario='$_POST[email_usuario]', direccion_usuario='$_POST[direccion_usuario]', usuario='$_POST[user]', hora_entrada='$_POST[hora_entrada]', hora_salida='$_POST[hora_salida]' where id_usuario='$_POST[id_usuario]'");
    insert_registro('MODIFICACION USUARIO: ' . $_POST['user']);
} elseif ($_POST['oper'] == "del") {
    pg_query("update usuario set estado='Pasivo' where id_usuario='$_POST[id]'");
    insert_registro('ELIMINACION USUARIO CON ID: ' . $_POST['id']);
}


////
?>
