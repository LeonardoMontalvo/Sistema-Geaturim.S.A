<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
$repe = 0;
$cuenta_debito = NULL;
$cuenta_credito = NULL;


//////////////////validar repetidos//////////////////
$consulta = pg_query("select * from parametros where descripcion='" . strtoupper($_POST['descripcion']) . "'");
while ($row = pg_fetch_row($consulta)) {
    $repe++;
}
$descripcion = strtoupper($_POST['descripcion']);
//////////////////
if ($descripcion == 'IVA') {
    $_POST[valor]=$_POST[valor];
} else {
    $_POST[valor]=NULL;
}
//////////////////////////////////////
if ($_POST[cuenta_debito] == '0') {
$cuenta_debito=NULL;
   
} else {
$cuenta_debito=$_POST[cuenta_debito];
   
}
/////////////////////7
if ($_POST[cuenta_credito] == '0') {
   $_POST[cuenta_credito]=NULL;
} else {
    $_POST[cuenta_credito]=$_POST[cuenta_credito];
}
/////////////////////////

///////////////////

if ($_POST['oper'] == "add") {
    $id = pg_fetch_row(pg_query("SELECT max(id_parametro)+1 FROM parametros"))[0];

    if ($repe == 0) {

        pg_query("INSERT INTO parametros VALUES('$id','$descripcion', '$_POST[valor]','$_POST[cuenta_debito]','$_POST[cuenta_credito]')");
        // Auditoria
        insert_registro('CREACION PARAMETRO: ' . $descripcion . ', CON CTA. DEBITO: ' . $_POST['cuenta_debito'] . ' Y CTA. CREDITO: ' . $_POST['cuenta_credito']);
    }
    



         pg_query("UPDATE parametros SET  cuenta_debito=NULL WHERE cuenta_debito=''");
   pg_query("UPDATE parametros SET cuenta_credito=NULL WHERE cuenta_credito=''");
      pg_query("UPDATE parametros SET valor=NULL WHERE valor=''");

} elseif ($_POST['oper'] == "edit") {

    /////////////////
    if ($descripcion == 'IVA') {

        pg_query("UPDATE parametros SET descripcion='$descripcion' , valor= $_POST[valor] WHERE id_parametro='$_POST[id]'");
    } else {
        pg_query("UPDATE parametros SET descripcion='$descripcion' , valor=NULL WHERE id_parametro='$_POST[id]'");
    }
////////////////
    if ($_POST[cuenta_debito] == '0') {

        pg_query("UPDATE parametros SET descripcion='$descripcion' , cuenta_debito=NULL WHERE id_parametro='$_POST[id]'");
    } else {

        pg_query("UPDATE parametros SET descripcion='$descripcion' , cuenta_debito=$_POST[cuenta_debito] WHERE id_parametro='$_POST[id]'");
    }
////////////////
    if ($_POST[cuenta_credito] == '0') {
        pg_query("UPDATE parametros SET descripcion='$descripcion' , cuenta_credito=NULL WHERE id_parametro='$_POST[id]'");
    } else {
        pg_query("UPDATE parametros SET descripcion='$descripcion' ,  cuenta_credito=$_POST[cuenta_credito] WHERE id_parametro='$_POST[id]'");
    }
        pg_query("UPDATE parametros SET  cuenta_debito=NULL WHERE cuenta_debito=''");
   pg_query("UPDATE parametros SET cuenta_credito=NULL WHERE cuenta_credito=''");
      pg_query("UPDATE parametros SET valor=NULL WHERE valor=''");

    insert_registro('MODIFICACION PARAMETRO: ' . $descripcion . ', CON CTA. DEBITO: ' . $_POST['cuenta_debito'] . ' Y CTA. CREDITO: ' . $_POST['cuenta_credito']);
}
