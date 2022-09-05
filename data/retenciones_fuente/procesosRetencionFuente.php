<?php

session_start();
include '../../procesos/base.php';
// Auditoria
require_once '../../procesos/auditoria.php';
conectarse();
$cont = 0;
$repe = 0;

//////////////////validar repetidos//////////////////
$consulta = pg_query("select * from retencion_fuentes where descripcion='" . strtoupper($_POST['descripcion']) . "'");
while ($row = pg_fetch_row($consulta)) {
    $repe++;
}
///////////////////////////////////////////////

if ($_POST['oper'] == "add") {
    $consulta = pg_query("select max(id_retencion_fuentes) from retencion_fuentes");
    while ($row = pg_fetch_row($consulta)) {
        $cont = $row[0];
    }
    $cont++;

    if ($repe == 0) {
        pg_query("insert into retencion_fuentes values('$cont','" . strtoupper($_POST['descripcion']) . "','$_POST[valor]','Activo','$_POST[codigo_formulario]','$_POST[cuenta_debito]','$_POST[cuenta_credito]')");
        // Auditoria
        insert_registro('CREACION RET. FUENTE: ' . strtoupper($_POST['descripcion']) );
    }
} elseif ($_POST['oper'] == "edit") {
    //       
    //             echo '<br>GUARDAR FACTURA VENTA: <br>' . "update retencion_fuentes set descripcion='" . strtoupper($_POST['descripcion']) . "' , valor='$_POST[valor]', estado='Activo', cuenta_debito='$_POST[cuenta_debito]', cuenta_credito='$_POST[cuenta_credito]', codigo_formulario='$_POST[codigo_formulario]'  where id_retencion_fuentes='$_POST[id_retencion_fuentes]'";//////////////////////////
    //	 
    //            
    pg_query("update retencion_fuentes set descripcion='" . strtoupper($_POST['descripcion']) . "' , valor='$_POST[valor]', estado='Activo', cuenta_debito='$_POST[cuenta_debito]', cuenta_credito='$_POST[cuenta_credito]', codigo_formulario='$_POST[codigo_formulario]'  where id_retencion_fuentes='$_POST[id_retencion_fuentes]'");
    // Auditoria
    insert_registro('MODIFICACION RET. FUENTE: ' . strtoupper($_POST['descripcion']) );
}
